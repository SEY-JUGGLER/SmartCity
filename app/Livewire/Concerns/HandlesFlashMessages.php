<?php

namespace App\Livewire\Concerns;

trait HandlesFlashMessages
{
    protected function flashSuccess(string $message): void
    {
        session()->flash('success', $message);
    }

    protected function flashWarning(string $message): void
    {
        session()->flash('warning', $message);
    }

    protected function flashError(string $message): void
    {
        session()->flash('error', $message);
    }
}
