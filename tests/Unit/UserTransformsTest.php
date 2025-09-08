<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Support\UserTransforms;

class UserTransformsTest extends TestCase
{
    public function test_age_maps_to_0_1_with_sweet_spot_around_30()
    {
        $this->assertIsFloat(UserTransforms::age(30));
        $this->assertGreaterThan(0.95, UserTransforms::age(30));
        $this->assertLessThan(0.5, UserTransforms::age(10));
        $this->assertLessThan(0.5, UserTransforms::age(50));
    }

    public function test_ordinal_1_5_maps_to_0_1()
    {
        $this->assertEquals(0.0, UserTransforms::ord1_5(1));
        $this->assertEquals(1.0, UserTransforms::ord1_5(5));
        $this->assertEquals(0.5, UserTransforms::ord1_5(3));
    }

    public function test_cardio_risk_cost_scoring()
    {
        $this->assertEquals(0.0, UserTransforms::cardio('none'));
        $this->assertEquals(0.3, UserTransforms::cardio('controlled'));
        $this->assertEquals(1.0, UserTransforms::cardio('high'));
    }

    public function test_experience_caps_at_1_0()
    {
        $this->assertEquals(0.0, UserTransforms::experience(0));
        $this->assertEquals(1.0, UserTransforms::experience(10));
        $this->assertEquals(1.0, UserTransforms::experience(20));
    }

    public function test_motivation_mapping()
    {
        $this->assertIsFloat(UserTransforms::motivation(0.5));
        $this->assertGreaterThanOrEqual(0.0, UserTransforms::motivation(0.5));
        $this->assertLessThanOrEqual(1.0, UserTransforms::motivation(0.5));
    }

    public function test_guide_boolean_mapping()
    {
        $this->assertEquals(1.0, UserTransforms::guide(true));
        $this->assertEquals(0.0, UserTransforms::guide(false));
    }
}