<?php

namespace App\Livewire;

use App\Services\DemoResetter;
use Livewire\Component;

class DemoResetButton extends Component
{
    public function resetDemo(DemoResetter $resetter)
    {
        try {
            $resetter->reset();
        } catch (\Throwable $e) {
            report($e);
            session()->flash('error', 'No se pudo reiniciar la demo. Inténtalo nuevamente.');

            return $this->redirectRoute('dashboard', navigate: true);
        }

        session()->flash('status', 'Demo reiniciada: se restauraron los datos ficticios iniciales.');

        return $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.demo-reset-button');
    }
}
