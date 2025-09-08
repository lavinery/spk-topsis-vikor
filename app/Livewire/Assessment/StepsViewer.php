<?php

namespace App\Livewire\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use Livewire\Component;
use Livewire\WithPagination;

class StepsViewer extends Component
{
    use WithPagination;

    public int $assessmentId;
    public string $activeStep = 'MATRIX_X';
    public string $activeMethod = 'topsis';
    public array $data = [];
    public array $topsisSteps = [];
    public array $vikorSteps = [];

    public function mount($id)
    {
        $this->assessmentId = (int) $id;
        $this->topsisSteps = [
          'MATRIX_X','NORMALIZED_R','WEIGHTED_Y','IDEAL_SOLUTION',
          'DISTANCES','CLOSENESS_COEFF','RANKING'
        ];
        $this->vikorSteps = [
          'VIKOR_MATRIX_X','VIKOR_BEST_WORST','VIKOR_S_R','VIKOR_Q','VIKOR_RANKING'
        ];
        $this->loadStep($this->activeStep);
    }

    public function loadStep($step)
    {
        $this->activeStep = $step;
        $row = AssessmentStep::where('assessment_id',$this->assessmentId)->where('step',$step)->first();
        if ($row) {
            $payload = $row->payload;
            $this->data = is_string($payload) ? json_decode($payload, true) ?? [] : $payload;
        } else {
            $this->data = [];
        }
    }

    public function switchMethod($method)
    {
        $this->activeMethod = $method;
        if ($method === 'topsis') {
            $this->activeStep = 'MATRIX_X';
        } else {
            $this->activeStep = 'VIKOR_MATRIX_X';
        }
        $this->loadStep($this->activeStep);
    }

    public function render()
    {
        $assessment = Assessment::findOrFail($this->assessmentId);
        return view('livewire.assessment.steps-viewer', compact('assessment'));
    }
}