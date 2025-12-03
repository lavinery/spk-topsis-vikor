<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Services\AnswerNormalizer;
use App\Services\ConstraintService;
use App\Services\TopsisService;
use App\Jobs\RunTopsisJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentRunController extends Controller
{
    public function run(Request $r, $id, AnswerNormalizer $norm, ConstraintService $cons, TopsisService $topsis)
    {
        try {
            $a = Assessment::with(['answers','alternatives.route.mountain'])->findOrFail($id);

            // Check if we should use background job for large datasets
            if ($a->alternatives()->count() > 500) {
                RunTopsisJob::dispatch($a->id)->onQueue('high');

                if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => true,
                        'message' => 'Perhitungan dijalankan di background. Silakan refresh halaman dalam beberapa saat.'
                    ]);
                }

                return back()->with('ok','Perhitungan dijalankan di background. Silakan refresh halaman dalam beberapa saat.');
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

            // pastikan jawaban user sudah dinormalisasi (value_numeric)
            $norm->normalize($a);

            // terapkan constraint (exclude alternatif berisiko)
            $cons->apply($a);

            // jalankan TOPSIS
            $topsis->run($a);

            // Refresh assessment to get updated status
            $a->refresh();

            // Verify that calculation completed successfully
            if ($a->status !== 'done') {
                throw new \Exception('Perhitungan TOPSIS gagal. Status: ' . $a->status);
            }

            // Check if this is an AJAX request
            if (request()->ajax() || request()->wantsJson() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Perhitungan TOPSIS selesai!',
                    'redirect_url' => route('assess.result', $a->id),
                    'status' => $a->status
                ]);
            }

            return redirect()->route('assess.result', $a->id)->with('success', 'Perhitungan TOPSIS selesai!');

        } catch (\Exception $e) {
            \Log::error('AssessmentRunController error: ' . $e->getMessage(), [
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