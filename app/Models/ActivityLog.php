<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['application_id', 'message', 'occurred_at'];

    protected $casts = ['occurred_at' => 'datetime'];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
