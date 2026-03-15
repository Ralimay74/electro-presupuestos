<?php

namespace App\Filament\Resources\BudgetTemplateResource\Pages;

use App\Filament\Resources\BudgetTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudgetTemplate extends EditRecord
{
    protected static string $resource = BudgetTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
