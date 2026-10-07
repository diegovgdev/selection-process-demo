<?php

namespace App\Livewire\Candidates;

use App\Enums\Stage;
use App\Models\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Postulantes')]
class Index extends Component
{
    #[Url(as: 'q', except: '')]
    public string $search = '';

    #[Url(as: 'estado', except: '')]
    public string $status = '';

    public function clearFilters(): void
    {
        $this->reset('search', 'status');
    }

    public function render()
    {
        $search = trim($this->search);
        $status = Stage::tryFrom($this->status);

        $applications = Application::query()
            ->with(['candidate', 'process', 'evaluation'])
            ->when($search !== '', function ($query) use ($search) {
                $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $search).'%';
                $query->where(function ($q) use ($like) {
                    $q->whereHas('candidate', fn ($c) => $c->where('name', 'like', $like)->orWhere('code', 'like', $like))
                        ->orWhereHas('process', fn ($p) => $p->where('name', 'like', $like));
                });
            })
            ->when($status, fn ($query) => $query->where('stage', $status->value))
            ->orderBy('candidate_id')
            ->get();

        return view('livewire.candidates.index', [
            'applications' => $applications,
            'stages' => Stage::cases(),
        ]);
    }
}
