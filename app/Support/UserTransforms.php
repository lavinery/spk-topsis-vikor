<?php

namespace App\Support;

final class UserTransforms
{
    // C1 Usia (cost): sweet-spot 20–40, center 30
    public static function age(int $age): float
    {
        $score = 1 - min(1, abs($age - 30) / 20);
        return max(0, min(1, $score)); // 0..1
    }

    // Generic ordinal 1..5 → 0..1
    public static function ord1_5(int $v): float
    {
        $v = max(1, min(5, $v));
        return ($v - 1) / 4;
    }

    // C3 Kardio (cost): raw level → cost score 0..1
    public static function cardio(string $level): float
    {
        return match($level) {
            'high' => 1.0,
            'controlled' => 0.3,
            default => 0.0,
        };
    }

    // C7 Motivasi: pakai repo category map
    public static function motivation(?float $mapped): float
    {
        return max(0, min(1, $mapped ?? 0.8));
    }

    // C8 Pengalaman (jumlah trip → 0..1, cap 10)
    public static function experience(int $trips): float
    {
        return max(0, min(1, $trips / 10));
    }

    // C13 Pemandu: ada=1, tidak=0
    public static function guide(bool $has): float
    {
        return $has ? 1.0 : 0.0;
    }
}
