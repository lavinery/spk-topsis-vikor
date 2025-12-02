<?php

namespace App\Livewire\Admin;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use Livewire\Component;
use Livewire\WithPagination;

class AssessmentsMonitor extends Component
{
    use WithPagination;
    public string $q = '';

    public function delete($id)
    {
        $assessment = Assessment::find($id);

        if ($assessment) {
            // Delete related data
            $assessment->answers()->delete();
            $assessment->alternatives()->delete();
            $assessment->steps()->delete();
            $assessment->snapshot()->delete();

            // Delete assessment
            $assessment->delete();

            session()->flash('message', 'Assessment berhasil dihapus.');
        }
    }

    public function render()
    {
        $list = Assessment::when($this->q, fn($q)=>$q->where('title','like','%'.$this->q.'%'))
            ->latest()->paginate(10);

        // ringkas top-5 untuk list
        $rows = [];
        foreach ($list as $a) {
            $rank = AssessmentStep::where('assessment_id',$a->id)->where('step','RANKING')->first();
            $mat  = AssessmentStep::where('assessment_id',$a->id)->where('step','MATRIX_X')->first();
            $r = $rank ? json_decode($rank->payload,true) : [];
            $m = $mat  ? json_decode($mat->payload,true)  : [];
            $rows[] = [
                'a'=>$a,
                'top5'=> $this->topN($r, $m, 5)
            ];
        }

        return view('livewire.admin.assessments-monitor', ['rows'=>$rows, 'list'=>$list]);
    }

    private function topN(array $rank, array $mat, int $n=5): array
    {
        $names = $mat['rows'] ?? [];
        $CC = $rank['CC'] ?? [];
        $rk = $rank['ranking'] ?? [];
        $take = array_slice($rk,0,$n);
        $out = [];
        foreach ($take as $i) $out[] = ['name'=>$names[$i]??('Route #'.$i),'cc'=>round($CC[$i]??0,4)];
        return $out;
    }
}