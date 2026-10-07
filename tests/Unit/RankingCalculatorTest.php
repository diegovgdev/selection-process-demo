<?php

namespace Tests\Unit;

use App\Services\RankingCalculator;
use PHPUnit\Framework\TestCase;

class RankingCalculatorTest extends TestCase
{
    public function test_final_score_uses_weights_30_30_40(): void
    {
        // 100*0.3 + 50*0.3 + 0*0.4 = 45
        $this->assertSame(45.0, RankingCalculator::finalScore(100, 50, 0));
        $this->assertSame(90.0, RankingCalculator::finalScore(90, 90, 90));
    }

    public function test_missing_components_are_renormalised(): void
    {
        // Only documents (0.3) and interview (0.3): (80*0.3 + 60*0.3) / 0.6 = 70
        $this->assertSame(70.0, RankingCalculator::finalScore(80, 60, null));
        $this->assertSame(75.0, RankingCalculator::finalScore(75, null, null));
    }

    public function test_final_score_is_null_without_scores(): void
    {
        $this->assertNull(RankingCalculator::finalScore(null, null, null));
    }
}
