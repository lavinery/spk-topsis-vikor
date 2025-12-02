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
    public string $activeMethod = 'topsis';
    public string $activeStep = 'MATRIX_X';
    public array $data = [];
    public array $topsisSteps = [];

    public function mount($id)
    {
        $this->assessmentId = (int) $id;
        $this->topsisSteps = [
          'FUZZY_PROCESSING','MATRIX_X','NORMALIZED_R','WEIGHTED_Y','IDEAL_SOLUTION',
          'DISTANCES','CLOSENESS_COEFF','RANKING'
        ];
        $this->loadStep($this->activeStep);
    }

    public function switchMethod($method)
    {
        $this->activeMethod = $method;
        // Reset to first step when switching method
        if ($method === 'topsis') {
            $this->loadStep('MATRIX_X');
        }
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


    public function render()
    {
        $assessment = Assessment::findOrFail($this->assessmentId);
        return view('livewire.assessment.steps-viewer', compact('assessment'));
    }
}