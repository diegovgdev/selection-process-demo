<?php

use App\Livewire\Candidates;
use App\Livewire\Dashboard;
use App\Livewire\Processes;
use App\Livewire\Ranking;
use Illuminate\Support\Facades\Route;

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/postulantes', Candidates\Index::class)->name('candidates.index');
Route::get('/postulantes/{application}', Candidates\Show::class)->name('candidates.show');
Route::get('/procesos', Processes\Index::class)->name('processes.index');
Route::get('/ranking', Ranking\Index::class)->name('ranking.index');
