<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Services\AnswerNormalizer;
use App\Services\ConstraintService;
use App\Services\TopsisService;
use App\Jobs\RunTopsisJob;

class AssessmentRunController extends Controller
{
    public function run($id, AnswerNormalizer $norm, ConstraintService $cons, TopsisService $topsis)
    {
        $a = Assessment::with(['answers','alternatives.route.mountain'])->findOrFail($id);

        // Check if we should use background job for large datasets
        if ($a->alternatives()->count() > 500) {
            RunTopsisJob::dispatch($a->id)->onQueue('high');
            return back()->with('ok','Perhitungan dijalankan di background. Silakan refresh halaman dalam beberapa saat.');
        }

        // pastikan jawaban user sudah dinormalisasi (value_numeric)
        $norm->normalize($a);

        // terapkan constraint (exclude alternatif berisiko)
        $cons->apply($a);

        // jalankan TOPSIS
        $topsis->run($a);

        return redirect()->route('assess.result', $a->id);
    }
}