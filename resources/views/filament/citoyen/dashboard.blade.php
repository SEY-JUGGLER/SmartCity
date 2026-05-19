<x-filament-panels::page>
    {{ $this->headerWidgets }}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-2">
            @livewire(\App\Filament\Citoyen\Widgets\CitoyenActiviteRecenteWidget::class)
        </div>
        <div class="lg:col-span-1">
            @livewire(\App\Filament\Citoyen\Widgets\CitoyenCarteSignalementsWidget::class)
        </div>
    </div>
</x-filament-panels::page>
