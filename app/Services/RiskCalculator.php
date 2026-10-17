<?php

namespace App\Services;

class RiskCalculator
{
    public function score(float $average, int $failedCourses, int $absences, bool $decliningTrend): int
    {
        $score = 0;

        if ($average < 11) {
            $score += 45;
        } elseif ($average < 13) {
            $score += 25;
        }

        $score += min($failedCourses * 15, 30);
        $score += min($absences * 5, 20);

        if ($decliningTrend) {
            $score += 15;
        }

        return min($score, 100);
    }
}
