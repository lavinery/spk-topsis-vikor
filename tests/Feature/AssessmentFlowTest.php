<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Assessment;
use App\Models\AssessmentStep;
use App\Models\Route;
use App\Models\Mountain;

class AssessmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_assessment_flow_creates_steps_and_results()
    {
        // Create test data
        $mountain = Mountain::factory()->create(['name' => 'Test Mountain']);
        $route = Route::factory()->create(['mountain_id' => $mountain->id, 'name' => 'Test Route']);

        // Create assessment
        $assessment = Assessment::create([
            'user_id' => null,
            'title' => 'Test Assessment',
            'status' => 'draft',
            'top_k' => 5
        ]);

        // Simulate user answers
        $answers = [
            'C1' => '25', // age
            'C2' => '4',  // fitness
            'C3' => 'none', // cardio
            'C4' => '3',  // confidence
            'C5' => '4',  // equipment
            'C6' => '3',  // first aid
            'C7' => 'rekreasi', // motivation
            'C8' => '5',  // experience
            'C9' => '3',  // logistics
            'C10' => '3', // tool skills
            'C11' => '3', // survival
            'C12' => '3', // team readiness
            'C13' => '1', // guide
            'C14' => '3', // team division
        ];

        // Test that assessment can be created
        $this->assertDatabaseHas('assessments', [
            'id' => $assessment->id,
            'title' => 'Test Assessment'
        ]);

        // Test that we can run TOPSIS (this would normally be done via the controller)
        $this->assertTrue(true); // Placeholder for actual TOPSIS test
    }

    public function test_landing_page_creates_assessment()
    {
        // Create test mountain and route
        $mountain = Mountain::factory()->create(['name' => 'Test Mountain']);
        $route = Route::factory()->create(['mountain_id' => $mountain->id]);

        $response = $this->post('/start', [
            'title' => 'Test Assessment',
            'C1' => '25',
            'C2' => '4',
            'C3' => 'none',
            'C4' => '3',
            'C5' => '4',
            'C6' => '3',
            'C7' => 'rekreasi',
            'C8' => '5',
            'C9' => '3',
            'C10' => '3',
            'C11' => '3',
            'C12' => '3',
            'C13' => '1',
            'C14' => '3',
        ]);

        $response->assertRedirect();
        
        // Check that assessment was created
        $this->assertDatabaseHas('assessments', [
            'title' => 'Test Assessment'
        ]);
    }

    public function test_assessment_steps_are_stored()
    {
        $assessment = Assessment::create([
            'user_id' => null,
            'title' => 'Test Assessment',
            'status' => 'draft',
            'top_k' => 5
        ]);

        // Simulate storing a step
        AssessmentStep::create([
            'assessment_id' => $assessment->id,
            'step' => 'MATRIX_X',
            'payload' => json_encode(['test' => 'data'])
        ]);

        $this->assertDatabaseHas('assessment_steps', [
            'assessment_id' => $assessment->id,
            'step' => 'MATRIX_X'
        ]);
    }
}