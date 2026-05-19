<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}
        <div class="mt-6">
            {{ $this->submitAction }}
        </div>
    </form>
</x-filament-panels::page>
