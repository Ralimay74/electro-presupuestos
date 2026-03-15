<?php

namespace App\Filament\Resources\BudgetTemplateResource\Pages;

use App\Filament\Resources\BudgetTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBudgetTemplates extends ListRecords
{
    protected static string $resource = BudgetTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
