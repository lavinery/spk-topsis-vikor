<?php

namespace App\Livewire\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use Livewire\Component;

class ResultTop extends Component
{
    public int $assessmentId;
    public array $top = [];     // [ ['name'=>'GnX — Jalur Y', 'cc'=>0.73], ... ]
    public array $all = [];     // (opsional) full ranking untuk admin toggle
    public array $explain = []; // kontribusi pro/kontra untuk Top-5
    public array $vikorTop = []; // VIKOR top results
    public array $vikorAll = []; // VIKOR all results

    public function mount($id)
    {
        $this->assessmentId = (int)$id;

        $steps = AssessmentStep::where('assessment_id',$this->assessmentId)->get()->keyBy('step');
        $rank = $steps['RANKING']?->payload ?? '[]';
        $cc   = $steps['CLOSENESS_COEFF']?->payload ?? '[]';
        $mat  = $steps['MATRIX_X']?->payload ?? '[]';
        $dist = $steps['DISTANCES']?->payload ?? '[]';
        
        // VIKOR data
        $vikorRank = $steps['VIKOR_RANKING']?->payload ?? '[]';
        $vikorS = $steps['VIKOR_S_R']?->payload ?? '[]';
        
        // Decode JSON if payload is string
        if (is_string($rank)) $rank = json_decode($rank, true) ?? [];
        if (is_string($cc)) $cc = json_decode($cc, true) ?? [];
        if (is_string($mat)) $mat = json_decode($mat, true) ?? [];
        if (is_string($dist)) $dist = json_decode($dist, true) ?? [];
        if (is_string($vikorRank)) $vikorRank = json_decode($vikorRank, true) ?? [];
        if (is_string($vikorS)) $vikorS = json_decode($vikorS, true) ?? [];

        $rows = $mat['rows'] ?? []; // index -> "Gn — Jalur"
        $CC   = $cc['CC']   ?? [];  // index -> value
        $rankingIdx = $rank['ranking'] ?? []; // index numeric (string) urut terbaik→terburuk

        // Top-K
        $topK = optional(Assessment::find($this->assessmentId))->top_k ?? 5;
        $take = array_slice($rankingIdx, 0, $topK);

        // Bangun list top beserta CC
        $this->top = [];
        foreach ($take as $idx) {
            $name = $rows[$idx] ?? ("Route #".$idx);
            $this->top[] = ['name'=>$name, 'cc'=>round($CC[$idx] ?? 0, 4), 'i'=>$idx];
        }

        // Simpan full (opsional)
        foreach ($rankingIdx as $idx) {
            $this->all[] = ['name'=>$rows[$idx] ?? ("Route #".$idx), 'cc'=>round($CC[$idx] ?? 0, 4)];
        }

        // Explainability: ambil dari DISTANCES.top_contrib
        $tc = $dist['top_contrib'] ?? [];
        foreach ($this->top as $t) {
            $i = $t['i'];
            $this->explain[$i] = $tc[$i] ?? ['pro'=>[],'con'=>[]];
        }

        // VIKOR data processing
        $vikorRankingIdx = $vikorRank['ranking'] ?? [];
        $vikorSValues = $vikorS['S'] ?? [];
        $vikorRValues = $vikorS['R'] ?? [];
        $vikorQValues = $vikorRank['Q'] ?? []; // Q values are in VIKOR_RANKING step, not VIKOR_S_R

        // VIKOR Top-K
        $vikorTake = array_slice($vikorRankingIdx, 0, $topK);
        $this->vikorTop = [];
        foreach ($vikorTake as $idx) {
            $name = $rows[$idx] ?? ("Route #".$idx);
            $this->vikorTop[] = [
                'name' => $name, 
                'q' => round($vikorQValues[$idx] ?? 0, 4),
                's' => round($vikorSValues[$idx] ?? 0, 4),
                'r' => round($vikorRValues[$idx] ?? 0, 4),
                'i' => $idx
            ];
        }

        // VIKOR All results
        $this->vikorAll = [];
        foreach ($vikorRankingIdx as $idx) {
            $this->vikorAll[] = [
                'name' => $rows[$idx] ?? ("Route #".$idx), 
                'q' => round($vikorQValues[$idx] ?? 0, 4),
                's' => round($vikorSValues[$idx] ?? 0, 4),
                'r' => round($vikorRValues[$idx] ?? 0, 4)
            ];
        }
    }


    public function render()
    {
        $assessment = Assessment::findOrFail($this->assessmentId);
        return view('livewire.assessment.result-top', compact('assessment'));
    }
}