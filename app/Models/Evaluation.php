<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'documents_score', 'interview_score', 'technical_score'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
