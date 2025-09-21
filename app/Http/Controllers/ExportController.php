<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export assessment step data as CSV
     */
    public function csv($id, $algo, $step): StreamedResponse
    {
        $assessment = Assessment::findOrFail($id);
        
        // Check if user has access to this assessment
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }

        $row = AssessmentStep::where('assessment_id', $id)
            ->where('algorithm', $algo)
            ->where('step', $step)
            ->firstOrFail();
            
        $data = json_decode($row->payload, true) ?? [];
        
        $filename = "{$algo}_{$step}_{$id}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function() use($data, $algo, $step) {
            $out = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fwrite($out, "\xEF\xBB\xBF");
            
            // Heuristic: standard table format with rows/cols/X
            if (isset($data['rows'], $data['cols'], $data['X'])) {
                // Header row
                fputcsv($out, array_merge(['#'], $data['cols']));
                
                // Data rows
                foreach ($data['X'] as $i => $row) {
                    $rowData = array_merge([$data['rows'][$i] ?? $i], $row);
                    fputcsv($out, $rowData);
                }
            }
            // Matrix format with specific data
            elseif (isset($data['cols'], $data['X'])) {
                fputcsv($out, $data['cols']);
                foreach ($data['X'] as $row) {
                    fputcsv($out, $row);
                }
            }
            // Ranking format
            elseif (isset($data['ranking'], $data['CC'])) {
                fputcsv($out, ['Rank', 'Alternative', 'CC Score']);
                foreach ($data['ranking'] as $i => $alternative) {
                    $ccValue = $data['CC'][$alternative] ?? 0;
                    fputcsv($out, [$i + 1, $alternative, $ccValue]);
                }
            }
            // Generic key=>value dump
            else {
                fputcsv($out, ['Key', 'Value']);
                foreach ($data as $k => $v) {
                    $value = is_scalar($v) ? $v : json_encode($v);
                    fputcsv($out, [$k, $value]);
                }
            }
            
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export assessment summary as CSV
     */
    public function summary($id): StreamedResponse
    {
        $assessment = Assessment::with(['steps'])->findOrFail($id);
        
        // Check if user has access to this assessment
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }

        $filename = "assessment_summary_{$id}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function() use($assessment) {
            $out = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fwrite($out, "\xEF\xBB\xBF");
            
            // Assessment info
            fputcsv($out, ['Assessment Information']);
            fputcsv($out, ['Title', $assessment->title]);
            fputcsv($out, ['Status', $assessment->status]);
            fputcsv($out, ['Created', $assessment->created_at]);
            fputcsv($out, ['Pure Formula', $assessment->pure_formula ? 'Yes' : 'No']);
            fputcsv($out, ['Top-K', $assessment->top_k]);
            fputcsv($out, ['Weight Preset ID', $assessment->weight_preset_id]);
            fputcsv($out, []); // Empty row
            
            // Get TOPSIS ranking
            $topsisRanking = $assessment->steps()
                ->where('step', 'RANKING')
                ->where('algorithm', 'TOPSIS')
                ->first();
                
            if ($topsisRanking) {
                $data = json_decode($topsisRanking->payload, true);
                fputcsv($out, ['TOPSIS Ranking']);
                fputcsv($out, ['Rank', 'Alternative', 'CC Score']);
                foreach ($data['ranking'] ?? [] as $i => $alternative) {
                    $ccValue = $data['CC'][$alternative] ?? 0;
                    fputcsv($out, [$i + 1, $alternative, $ccValue]);
                }
                fputcsv($out, []); // Empty row
            }
            
            
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }

    /**
     * Export assessment snapshot as CSV
     */
    public function snapshot($id): StreamedResponse
    {
        $assessment = Assessment::with('snapshot')->findOrFail($id);
        
        // Check if user has access to this assessment
        if ($assessment->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to assessment');
        }

        if (!$assessment->snapshot) {
            abort(404, 'No snapshot found for this assessment');
        }

        $filename = "assessment_snapshot_{$id}_" . now()->format('Y-m-d_H-i-s') . '.csv';
        
        return response()->streamDownload(function() use($assessment) {
            $out = fopen('php://output', 'w');
            
            // Add BOM for Excel compatibility
            fwrite($out, "\xEF\xBB\xBF");
            
            $snapshot = $assessment->snapshot;
            
            // Assessment info
            fputcsv($out, ['Assessment Snapshot']);
            fputcsv($out, ['Assessment ID', $assessment->id]);
            fputcsv($out, ['Title', $assessment->title]);
            fputcsv($out, ['Snapshot Created', $snapshot->created_at]);
            fputcsv($out, []); // Empty row
            
            // Parameters
            fputcsv($out, ['Parameters']);
            foreach ($snapshot->params as $key => $value) {
                if (is_array($value)) {
                    fputcsv($out, [$key, json_encode($value)]);
                } else {
                    fputcsv($out, [$key, $value]);
                }
            }
            fputcsv($out, []); // Empty row
            
            // Criteria
            fputcsv($out, ['Criteria Used']);
            fputcsv($out, ['Code', 'Name', 'Type', 'Source', 'Active', 'Sort Order']);
            foreach ($snapshot->criteria as $criterion) {
                fputcsv($out, [
                    $criterion['code'],
                    $criterion['name'],
                    $criterion['type'],
                    $criterion['source'],
                    $criterion['active'] ? 'Yes' : 'No',
                    $criterion['sort_order']
                ]);
            }
            fputcsv($out, []); // Empty row
            
            // Weights
            fputcsv($out, ['Weights Used']);
            fputcsv($out, ['Code', 'Weight']);
            foreach ($snapshot->weights as $code => $weight) {
                fputcsv($out, [$code, $weight]);
            }
            fputcsv($out, []); // Empty row
            
            // Category maps
            if (!empty($snapshot->category_maps)) {
                fputcsv($out, ['Category Maps']);
                foreach ($snapshot->category_maps as $code => $maps) {
                    fputcsv($out, ["Criterion: {$code}"]);
                    fputcsv($out, ['Key', 'Score']);
                    foreach ($maps as $key => $score) {
                        fputcsv($out, [$key, $score]);
                    }
                    fputcsv($out, []); // Empty row
                }
            }
            
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ]);
    }
}