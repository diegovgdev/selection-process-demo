<?php

namespace App\Models;

use App\Enums\Stage;
use App\Services\RankingCalculator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = ['selection_process_id', 'candidate_id', 'stage', 'applied_at', 'comment'];

    protected $casts = [
        'stage' => Stage::class,
        'applied_at' => 'date',
    ];

    public function process(): BelongsTo
    {
        return $this->belongsTo(SelectionProcess::class, 'selection_process_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /** Consolidated weighted score (0-100), or null if nothing has been evaluated yet. */
    public function finalScore(): ?float
    {
        return RankingCalculator::finalScore(
            $this->evaluation?->documents_score,
            $this->evaluation?->interview_score,
            $this->evaluation?->technical_score,
        );
    }
}
