<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Collection;

class RankingCalculator
{
    public const WEIGHT_DOCUMENTS = 0.30;

    public const WEIGHT_INTERVIEW = 0.30;

    public const WEIGHT_TECHNICAL = 0.40;

    /**
     * Weighted average of the scores that already exist. Missing components are
     * ignored and the remaining weights are re-normalised, so a candidate that
     * has only passed the document review is still comparable.
     */
    public static function finalScore(?int $documents, ?int $interview, ?int $technical): ?float
    {
        $components = [
            [$documents, self::WEIGHT_DOCUMENTS],
            [$interview, self::WEIGHT_INTERVIEW],
            [$technical, self::WEIGHT_TECHNICAL],
        ];

        $weighted = 0.0;
        $totalWeight = 0.0;

        foreach ($components as [$score, $weight]) {
            if ($score === null) {
                continue;
            }
            $weighted += $score * $weight;
            $totalWeight += $weight;
        }

        return $totalWeight > 0 ? round($weighted / $totalWeight, 1) : null;
    }

    /**
     * Rank applications by final score (desc). Ties are broken by the earliest
     * application date, then by id. Unscored applications go last.
     *
     * @param  Collection<int, Application>  $applications
     * @return Collection<int, array{position: int, application: Application, final: ?float}>
     */
    public function rank(Collection $applications): Collection
    {
        return $applications
            ->map(fn (Application $a) => ['application' => $a, 'final' => $a->finalScore()])
            ->sort(function (array $a, array $b) {
                return [$b['final'] ?? -1, $a['application']->applied_at->timestamp, $a['application']->id]
                    <=> [$a['final'] ?? -1, $b['application']->applied_at->timestamp, $b['application']->id];
            })
            ->values()
            ->map(fn (array $row, int $i) => ['position' => $i + 1] + $row);
    }
}
