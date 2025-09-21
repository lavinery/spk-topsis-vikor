<?php

namespace Tests\Feature;

use App\Services\FuzzyService;
use App\Models\Criterion;
use App\Models\FuzzyTerm;
use App\Models\FuzzyMapping;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FuzzyServiceTest extends TestCase
{
    use RefreshDatabase;
    
    private FuzzyService $fuzzyService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->fuzzyService = new FuzzyService();
    }
    
    public function test_triangular_membership_function_works_correctly()
    {
        $service = $this->fuzzyService;
        
        // Test triangular membership with params [1, 3, 5]
        $params = [1, 3, 5];
        
        // Test various points
        $this->assertEquals(0.0, $service->membershipTriangular(1, $params));
        $this->assertEquals(0.5, $service->membershipTriangular(2, $params));
        $this->assertEquals(1.0, $service->membershipTriangular(3, $params));
        $this->assertEquals(0.5, $service->membershipTriangular(4, $params));
        $this->assertEquals(0.0, $service->membershipTriangular(5, $params));
        
        // Test outside range
        $this->assertEquals(0.0, $service->membershipTriangular(0, $params));
        $this->assertEquals(0.0, $service->membershipTriangular(6, $params));
    }
    
    public function test_trapezoidal_membership_function_works_correctly()
    {
        $service = $this->fuzzyService;
        
        // Test trapezoidal membership with params [1, 2, 4, 5]
        $params = [1, 2, 4, 5];
        
        // Test various points
        $this->assertEquals(0.0, $service->membershipTrapezoidal(1, $params));
        $this->assertEquals(0.5, $service->membershipTrapezoidal(1.5, $params));
        $this->assertEquals(1.0, $service->membershipTrapezoidal(2, $params));
        $this->assertEquals(1.0, $service->membershipTrapezoidal(3, $params));
        $this->assertEquals(1.0, $service->membershipTrapezoidal(4, $params));
        $this->assertEquals(0.5, $service->membershipTrapezoidal(4.5, $params));
        $this->assertEquals(0.0, $service->membershipTrapezoidal(5, $params));
        
        // Test outside range
        $this->assertEquals(0.0, $service->membershipTrapezoidal(0, $params));
        $this->assertEquals(0.0, $service->membershipTrapezoidal(6, $params));
    }
    
    public function test_fuzzify_scalar_returns_correct_membership_degrees()
    {
        $service = $this->fuzzyService;
        
        $terms = [
            [
                'code' => 'RENDAH',
                'shape' => 'triangular',
                'params_json' => json_encode([1, 2, 3])
            ],
            [
                'code' => 'SEDANG',
                'shape' => 'triangular',
                'params_json' => json_encode([2, 3, 4])
            ],
            [
                'code' => 'TINGGI',
                'shape' => 'triangular',
                'params_json' => json_encode([3, 4, 5])
            ]
        ];
        
        $memberships = $service->fuzzifyScalar(2.5, $terms);
        
        $this->assertArrayHasKey('RENDAH', $memberships);
        $this->assertArrayHasKey('SEDANG', $memberships);
        $this->assertArrayHasKey('TINGGI', $memberships);
        $this->assertGreaterThan(0, $memberships['RENDAH']);
        $this->assertGreaterThan(0, $memberships['SEDANG']);
        $this->assertEquals(0.0, $memberships['TINGGI']);
    }
    
    public function test_aggregate_function_works_for_single_input()
    {
        $service = $this->fuzzyService;
        
        $membershipDict = [
            'RENDAH' => 0.5,
            'SEDANG' => 0.8,
            'TINGGI' => 0.2
        ];
        
        $aggregated = $service->aggregate($membershipDict);
        
        $this->assertEquals($membershipDict, $aggregated);
    }
    
    public function test_defuzzify_centroid_calculates_correct_centroid()
    {
        $service = $this->fuzzyService;
        
        $terms = [
            [
                'code' => 'RENDAH',
                'shape' => 'triangular',
                'params_json' => json_encode([1, 2, 3])
            ],
            [
                'code' => 'SEDANG',
                'shape' => 'triangular',
                'params_json' => json_encode([2, 3, 4])
            ]
        ];
        
        $membershipDict = [
            'RENDAH' => 0.5,
            'SEDANG' => 1.0
        ];
        
        $centroid = $service->defuzzifyCentroid($terms, $membershipDict);
        
        $this->assertGreaterThan(2, $centroid);
        $this->assertLessThan(4, $centroid);
        $this->assertIsNumeric($centroid);
    }
    
    public function test_defuzzify_centroid_handles_edge_cases()
    {
        $service = $this->fuzzyService;
        
        // Empty membership dict
        $centroid = $service->defuzzifyCentroid([], []);
        $this->assertEquals(0.0, $centroid);
        
        // Single point membership
        $terms = [
            [
                'code' => 'POINT',
                'shape' => 'triangular',
                'params_json' => json_encode([2, 2, 2])
            ]
        ];
        
        $membershipDict = ['POINT' => 1.0];
        $centroid = $service->defuzzifyCentroid($terms, $membershipDict);
        $this->assertEquals(2.0, $centroid);
    }
    
    public function test_fuzzify_and_defuzzify_works_end_to_end()
    {
        // Create a test criterion
        $criterion = Criterion::create([
            'code' => 'C1',
            'name' => 'Test Criterion',
            'type' => 'benefit',
            'source' => 'USER',
            'data_type' => 'numeric',
            'unit' => 'scale',
            'active' => true,
            'is_fuzzy' => true,
            'version' => 'v1'
        ]);
        
        // Create fuzzy terms
        FuzzyTerm::create([
            'criterion_id' => $criterion->id,
            'code' => 'RENDAH',
            'label' => 'Rendah',
            'shape' => 'triangular',
            'params_json' => json_encode([1, 2, 3])
        ]);
        
        FuzzyTerm::create([
            'criterion_id' => $criterion->id,
            'code' => 'SEDANG',
            'label' => 'Sedang',
            'shape' => 'triangular',
            'params_json' => json_encode([2, 3, 4])
        ]);
        
        FuzzyTerm::create([
            'criterion_id' => $criterion->id,
            'code' => 'TINGGI',
            'label' => 'Tinggi',
            'shape' => 'triangular',
            'params_json' => json_encode([3, 4, 5])
        ]);
        
        // Create fuzzy mapping
        FuzzyMapping::create([
            'criterion_id' => $criterion->id,
            'input_min' => 1.0,
            'input_max' => 5.0,
            'default_term_code' => 'SEDANG'
        ]);
        
        $service = $this->fuzzyService;
        
        // Test fuzzification and defuzzification
        $result = $service->fuzzifyAndDefuzzify(2.5, $criterion->id);
        
        $this->assertArrayHasKey('defuzzified_value', $result);
        $this->assertArrayHasKey('memberships', $result);
        $this->assertArrayHasKey('terms', $result);
        $this->assertArrayHasKey('input_value', $result);
        $this->assertEquals(2.5, $result['input_value']);
        $this->assertIsNumeric($result['defuzzified_value']);
        $this->assertIsArray($result['memberships']);
        $this->assertIsArray($result['terms']);
        $this->assertCount(3, $result['terms']);
    }
    
    public function test_is_criterion_fuzzy_returns_correct_status()
    {
        $service = $this->fuzzyService;
        
        // Create non-fuzzy criterion
        $nonFuzzyCriterion = Criterion::create([
            'code' => 'C1',
            'name' => 'Non-Fuzzy Criterion',
            'type' => 'benefit',
            'source' => 'USER',
            'data_type' => 'numeric',
            'active' => true,
            'is_fuzzy' => false,
            'version' => 'v1'
        ]);
        
        // Create fuzzy criterion
        $fuzzyCriterion = Criterion::create([
            'code' => 'C2',
            'name' => 'Fuzzy Criterion',
            'type' => 'benefit',
            'source' => 'USER',
            'data_type' => 'numeric',
            'active' => true,
            'is_fuzzy' => true,
            'version' => 'v1'
        ]);
        
        $this->assertFalse($service->isCriterionFuzzy($nonFuzzyCriterion->id));
        $this->assertTrue($service->isCriterionFuzzy($fuzzyCriterion->id));
    }
    
    public function test_handles_missing_fuzzy_terms_gracefully()
    {
        $service = $this->fuzzyService;
        
        $result = $service->fuzzifyAndDefuzzify(2.5, 999); // Non-existent criterion
        
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals(2.5, $result['defuzzified_value']); // Falls back to input value
    }
    
    public function test_handles_invalid_input_ranges()
    {
        // Create test criterion with mapping
        $criterion = Criterion::create([
            'code' => 'C1',
            'name' => 'Test Criterion',
            'type' => 'benefit',
            'source' => 'USER',
            'data_type' => 'numeric',
            'active' => true,
            'is_fuzzy' => true,
            'version' => 'v1'
        ]);
        
        FuzzyMapping::create([
            'criterion_id' => $criterion->id,
            'input_min' => 1.0,
            'input_max' => 5.0,
            'default_term_code' => 'SEDANG'
        ]);
        
        $service = $this->fuzzyService;
        
        // Test input outside range
        $result = $service->fuzzifyAndDefuzzify(10.0, $criterion->id);
        
        $this->assertArrayHasKey('warning', $result);
        $this->assertEquals(1.0, $result['memberships']['SEDANG']); // Uses default term
    }
}