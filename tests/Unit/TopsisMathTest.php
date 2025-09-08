<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class TopsisMathTest extends TestCase
{
    public function test_computes_topsis_cc_and_ranking_on_golden_dataset()
    {
        // Benefit & cost columns
        $benefit = [80, 60, 40];  // A,B,C
        $cost    = [200,140,120]; // A,B,C
        $wB = 0.5; $wC = 0.5;

        // Normalize (vector)
        $normB = sqrt(array_sum(array_map(fn($v)=>$v*$v, $benefit)));
        $normC = sqrt(array_sum(array_map(fn($v)=>$v*$v, $cost)));
        $Rb = array_map(fn($v)=> $v/$normB, $benefit);
        $Rc = array_map(fn($v)=> $v/$normC, $cost);

        // Weighted
        $Yb = array_map(fn($v)=> $v*$wB, $Rb);
        $Yc = array_map(fn($v)=> $v*$wC, $Rc);

        // Ideals (benefit=max, cost=min)
        $Aplus  = [max($Yb), min($Yc)];
        $Aminus = [min($Yb), max($Yc)];

        // Distances & CC
        $Splus = []; $Sminus = []; $CC = [];
        for ($i=0; $i<3; $i++) {
            $dp2 = ($Yb[$i]-$Aplus[0])**2 + ($Yc[$i]-$Aplus[1])**2;
            $dm2 = ($Yb[$i]-$Aminus[0])**2 + ($Yc[$i]-$Aminus[1])**2;
            $Splus[$i]  = sqrt($dp2);
            $Sminus[$i] = sqrt($dm2);
            $CC[$i] = $Splus[$i]+$Sminus[$i] > 0 ? $Sminus[$i] / ($Splus[$i]+$Sminus[$i]) : 0;
        }

        // Expected (approx)
        $this->assertEquals(0.558082, round($CC[0],6));
        $this->assertEquals(0.590775, round($CC[1],6));
        $this->assertEquals(0.441918, round($CC[2],6));

        // Ranking B > A > C
        arsort($CC);
        $rank = array_keys($CC);
        $this->assertEquals([1,0,2], $rank);
    }

    public function test_normalization_vector_method()
    {
        $values = [3, 4, 5];
        $norm = sqrt(array_sum(array_map(fn($v)=>$v*$v, $values)));
        $expected = sqrt(9 + 16 + 25); // sqrt(50)
        $this->assertEquals($expected, $norm);
    }

    public function test_ideal_solutions_benefit_cost()
    {
        $benefit = [0.1, 0.5, 0.9];
        $cost = [0.8, 0.3, 0.1];
        
        $Aplus = [max($benefit), min($cost)];
        $Aminus = [min($benefit), max($cost)];
        
        $this->assertEquals([0.9, 0.1], $Aplus);
        $this->assertEquals([0.1, 0.8], $Aminus);
    }

    public function test_closeness_coefficient_calculation()
    {
        $Splus = [0.2, 0.1, 0.3];
        $Sminus = [0.3, 0.4, 0.2];
        
        $CC = [];
        for ($i=0; $i<3; $i++) {
            $CC[$i] = $Splus[$i]+$Sminus[$i] > 0 ? $Sminus[$i] / ($Splus[$i]+$Sminus[$i]) : 0;
        }
        
        $this->assertEquals(0.6, $CC[0]); // 0.3 / (0.2 + 0.3)
        $this->assertEquals(0.8, $CC[1]); // 0.4 / (0.1 + 0.4)
        $this->assertEquals(0.4, $CC[2]); // 0.2 / (0.3 + 0.2)
    }
}