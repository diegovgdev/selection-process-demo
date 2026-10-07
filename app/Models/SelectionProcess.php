<?php

namespace App\Models;

use App\Enums\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SelectionProcess extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'slots', 'status'];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function selectedCount(): int
    {
        return $this->applications()->where('stage', Stage::Selected->value)->count();
    }

    public function hasFreeSlots(): bool
    {
        return $this->selectedCount() < $this->slots;
    }

    public function statusLabel(): string
    {
        return $this->status === 'open' ? 'Abierto' : 'Cerrado';
    }
}
