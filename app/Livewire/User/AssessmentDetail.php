<?php

namespace App\Livewire\User;

use App\Models\Assessment;
use App\Models\AssessmentStep;
use Livewire\Component;

class AssessmentDetail extends Component
{
    public int $assessmentId;
    public $assessment;
    public $steps = [];
    public $ranking = [];
    public $matrixX = [];
    public $normalizedR = [];
    public $weightedY = [];
    public $idealSolution = [];
    public $distances = [];
    public $closeness = [];

    public function mount($id)
    {
        $this->assessmentId = (int)$id;
        $this->assessment = Assessment::where('id', $this->assessmentId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->loadCalculationSteps();
    }

    private function loadCalculationSteps()
    {
        $steps = AssessmentStep::where('assessment_id', $this->assessmentId)
            ->orderBy('created_at')
            ->get();

        foreach ($steps as $step) {
            $payload = json_decode($step->payload, true);

            switch ($step->step) {
                case 'MATRIX_X':
                    $this->matrixX = $payload;
                    break;
                case 'NORMALIZED_R':
                    $this->normalizedR = $payload;
                    break;
                case 'WEIGHTED_Y':
                    $this->weightedY = $payload;
                    break;
                case 'IDEAL_SOLUTION':
                    $this->idealSolution = $payload;
                    break;
                case 'DISTANCES':
                    $this->distances = $payload;
                    break;
                case 'CLOSENESS_COEFF':
                    $this->closeness = $payload;
                    break;
                case 'RANKING':
                    $this->ranking = $payload;
                    break;
            }
        }
    }

    public function render()
    {
        return view('livewire.user.assessment-detail')->layout('layouts.app');
    }
}
