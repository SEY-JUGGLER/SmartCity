<?php

namespace App\Filament\Widgets;

use App\Services\ClassificationService;
use Filament\Widgets\Widget;

class ClassificationsWidget extends Widget
{
    protected static ?int $sort = 8;
    protected static bool $isLazy = true;
    protected int|string|array $columnSpan = ['default' => 'full'];
    protected string $view = 'filament.widgets.classifications';
    protected ?string $pollingInterval = '60s';

    public function getAgentCounts(): array
    {
        return ClassificationService::countAgentClasses();
    }

    public function getCitoyenCounts(): array
    {
        return ClassificationService::countCitoyenClasses();
    }

    public function getAgentClasses(): array
    {
        return ClassificationService::AGENT_CLASSES;
    }

    public function getCitoyenClasses(): array
    {
        return ClassificationService::CITOYEN_CLASSES;
    }
}
