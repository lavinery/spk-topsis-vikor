<?php

namespace Tests\Unit;

use App\Services\FuzzyService;
use Tests\TestCase;

class FuzzyServiceUnitTest extends TestCase
{
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
    
    public function test_crisp_to_fuzzy_to_crisp_consistency()
    {
        $service = $this->fuzzyService;
        
        $terms = [
            [
                'code' => 'RENDAH',
                'shape' => 'triangular',
                'params_json' => json_encode([1, 1.5, 2.5])
            ],
            [
                'code' => 'SEDANG',
                'shape' => 'triangular',
                'params_json' => json_encode([2, 3, 4])
            ],
            [
                'code' => 'TINGGI',
                'shape' => 'triangular',
                'params_json' => json_encode([3.5, 4.5, 5])
            ]
        ];
        
        // Test input value 3.0 (should be in SEDANG range)
        $memberships = $service->fuzzifyScalar(3.0, $terms);
        $aggregated = $service->aggregate($memberships);
        $defuzzified = $service->defuzzifyCentroid($terms, $aggregated);
        
        // The defuzzified value should be close to the input for a reasonable fuzzy system
        $this->assertGreaterThan(2.5, $defuzzified);
        $this->assertLessThan(3.5, $defuzzified);
        
        // Test input value 1.0 (should be in RENDAH range)
        $memberships = $service->fuzzifyScalar(1.0, $terms);
        $aggregated = $service->aggregate($memberships);
        $defuzzified = $service->defuzzifyCentroid($terms, $aggregated);
        
        $this->assertGreaterThanOrEqual(1.0, $defuzzified);
        $this->assertLessThanOrEqual(2.0, $defuzzified);
    }
}
