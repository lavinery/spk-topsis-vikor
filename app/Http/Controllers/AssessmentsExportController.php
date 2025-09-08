<?php

namespace App\Http\Controllers;

use App\Models\AssessmentStep;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StepArrayExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AssessmentsExportController extends Controller
{
    public function export($id, $step, $format)
    {
        $row = AssessmentStep::where('assessment_id',$id)->where('step',$step)->firstOrFail();
        $payload = json_decode($row->payload,true) ?? [];

        // Bentuk array 2D sederhana berdasarkan step
        $table = $this->toTable($step, $payload);

        if ($format === 'csv' || $format === 'xlsx') {
            $name = "assessment-{$id}-{$step}.".($format==='csv'?'csv':'xlsx');
            return Excel::download(new StepArrayExport($table), $name);
        }

        // PDF
        $pdf = Pdf::loadView('pdf.step-export', ['step'=>$step,'rows'=>$table]);
        return $pdf->download("assessment-{$id}-{$step}.pdf");
    }

    private function toTable(string $step, array $p): array
    {
        switch ($step) {
            case 'MATRIX_X':
                $rows = $p['rows'] ?? []; $cols = $p['cols'] ?? []; $X = $p['X'] ?? [];
                $table = [['Alternatif', ...$cols]];
                foreach ($rows as $i=>$name) $table[] = [$name, ...array_map(fn($v)=> is_numeric($v)?round($v,6):$v, $X[$i] ?? [])];
                return $table;
            case 'NORMALIZED_R':
                $rows = $p['rows'] ?? []; $cols = $p['cols'] ?? []; $R = $p['R'] ?? [];
                $table = [['Alternatif', ...$cols]];
                foreach ($rows as $i=>$name) $table[] = [$name, ...array_map(fn($v)=> round($v,6), $R[$i] ?? [])];
                return $table;
            case 'WEIGHTED_Y':
                $rows = $p['rows'] ?? []; $cols = $p['cols'] ?? []; $Y = $p['Y'] ?? [];
                $table = [['Alternatif', ...$cols]];
                foreach ($rows as $i=>$name) $table[] = [$name, ...array_map(fn($v)=> round($v,6), $Y[$i] ?? [])];
                return $table;
            case 'IDEAL_SOLUTION':
                $cols = $p['cols'] ?? []; $Ap = $p['A_plus'] ?? []; $Am = $p['A_minus'] ?? []; $types=$p['types'] ?? [];
                $table = [['Kriteria','Type','A+','A-']];
                foreach ($cols as $j=>$c) $table[] = [$c, $types[$c]??'-', round($Ap[$j]??0,6), round($Am[$j]??0,6)];
                return $table;
            case 'DISTANCES':
                $rows = $p['rows'] ?? []; $Sp = $p['S_plus'] ?? []; $Sm = $p['S_minus'] ?? [];
                $table = [['Alternatif','S+','S-']];
                foreach ($rows as $i=>$name) $table[] = [$name, round($Sp[$i]??0,6), round($Sm[$i]??0,6)];
                return $table;
            case 'CLOSENESS_COEFF':
                $rows = $p['rows'] ?? []; $CC = $p['CC'] ?? [];
                $table = [['Alternatif','CC']];
                foreach ($rows as $i=>$name) $table[] = [$name, round($CC[$i]??0,6)];
                return $table;
            case 'RANKING':
                $rank = $p['ranking'] ?? []; $CC = $p['CC'] ?? [];
                // Dapatkan rows dari MATRIX_X
                $m = AssessmentStep::where('assessment_id',request()->route('id'))->where('step','MATRIX_X')->first();
                $rows = $m ? (json_decode($m->payload,true)['rows'] ?? []) : [];
                $table = [['#','Alternatif','CC']];
                foreach ($rank as $k=>$idx) $table[] = [$k+1, $rows[$idx] ?? ('Route #'.$idx), round($CC[$idx]??0,6)];
                return $table;
        }
        return [];
    }
}