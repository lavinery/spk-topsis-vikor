<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Services\AnswerNormalizer;
use App\Services\ConstraintService;
use App\Services\TopsisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentRunBothController extends Controller
{
    public function run(Request $r, $id, AnswerNormalizer $norm, ConstraintService $cons, TopsisService $topsis)
    {
        try {
            $a = Assessment::with(['answers','alternatives.route.mountain'])->findOrFail($id);

            // Check if we should use background job for large datasets
            if ($a->alternatives()->count() > 500) {
                // TODO: Create RunBothJob for background processing
                return back()->with('error','Dataset terlalu besar. Silakan gunakan background job.');
            }

            // Update assessment configuration if provided
            if ($r->has('weight_preset_id')) {
                $a->update(['weight_preset_id' => $r->input('weight_preset_id')]);
            }
            if ($r->has('pure_formula')) {
                $a->update(['pure_formula' => $r->boolean('pure_formula')]);
            }
            if ($r->has('top_k')) {
                $a->update(['top_k' => (int)$r->input('top_k')]);
            }

            // Create snapshot for reproducibility
            $this->createSnapshot($a);

            // Ensure user answers are normalized
            $norm->normalize($a);

            // Apply constraints (exclude risky alternatives)
            $cons->apply($a);

            // Run TOPSIS only
            $topsis->run($a);

            // Check if this is an AJAX request
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Perhitungan TOPSIS selesai!',
                    'redirect_url' => route('assess.result', $a->id)
                ]);
            }
            
            return redirect()->route('assess.result', $a->id)->with('success', 'Perhitungan TOPSIS selesai!');
            
        } catch (\Exception $e) {
            \Log::error('AssessmentRunBothController error: ' . $e->getMessage(), [
                'assessment_id' => $id,
                'stack' => $e->getTraceAsString()
            ]);
            
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'error' => 'Terjadi kesalahan saat menjalankan perhitungan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menjalankan perhitungan: ' . $e->getMessage());
        }
    }

    public function runTopsis($id, AnswerNormalizer $norm, ConstraintService $cons, TopsisService $topsis)
    {
        $a = Assessment::with(['answers','alternatives.route.mountain'])->findOrFail($id);

        // Check if we should use background job for large datasets
        if ($a->alternatives()->count() > 500) {
            // TODO: Create RunTopsisJob for background processing
            return back()->with('error','Dataset terlalu besar. Silakan gunakan background job.');
        }

        // Update assessment configuration if provided
        if (request()->has('weight_preset_id')) {
            $a->update(['weight_preset_id' => request()->input('weight_preset_id')]);
        }
        if (request()->has('pure_formula')) {
            $a->update(['pure_formula' => request()->boolean('pure_formula')]);
        }

        // Ensure user answers are normalized
        $norm->normalize($a);

        // Apply constraints (exclude risky alternatives)
        $cons->apply($a);

        // Run TOPSIS only
        $topsis->run($a);

        // Check if this is an AJAX request
        if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Perhitungan TOPSIS selesai!',
                'redirect_url' => route('assess.result', $a->id)
            ]);
        }
        
        return redirect()->route('assess.result', $a->id)->with('success', 'Perhitungan TOPSIS selesai!');
    }


    /**
     * Create snapshot for reproducibility
     */
    private function createSnapshot(Assessment $a): void
    {
        // Get criteria used in calculation
        $criteria = \App\Models\Criterion::where('active', 1)
            ->when($a->pure_formula, fn($q) => $q->whereIn('source', ['MOUNTAIN', 'ROUTE']))
            ->orderBy('sort_order')
            ->get();

        // Get weights using the same logic as services
        $cols = $criteria->pluck('code')->all();
        $topsisService = app(\App\Services\TopsisService::class);
        $weights = $topsisService->resolveWeights($a, $cols);

        // Get category maps for categorical criteria
        $categoryMaps = \App\Models\Criterion::where('scale', 'categorical')->get()
            ->mapWithKeys(function($c) {
                $items = DB::table('category_maps')->where('criterion_id', $c->id)->get();
                return [$c->code => $items->mapWithKeys(fn($i) => [$i->key => (float)$i->score])->all()];
            })->all();

        // Ensure weights array has the same keys as cols array
        $weightsArray = [];
        foreach ($cols as $col) {
            $weightsArray[$col] = $weights[$col] ?? 0.0;
        }
        
        $snapshot = [
            'criteria' => $criteria->map(fn($c) => $c->only(['id', 'code', 'name', 'type', 'source', 'active', 'sort_order']))->all(),
            'weights' => $weightsArray,
            'category_maps' => $categoryMaps,
            'params' => [
                'top_k' => $a->top_k,
                'pure' => $a->pure_formula,
                'weight_preset_id' => $a->weight_preset_id,
                'timestamp' => now()->toISOString()
            ]
        ];

        \App\Models\AssessmentSnapshot::updateOrCreate(
            ['assessment_id' => $a->id],
            [
                'criteria' => $snapshot['criteria'],
                'weights' => $snapshot['weights'],
                'category_maps' => $snapshot['category_maps'],
                'params' => $snapshot['params']
            ]
        );
    }
}