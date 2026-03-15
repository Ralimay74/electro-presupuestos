<?php

namespace App\Filament\Resources\BudgetLineResource\Pages;

use App\Filament\Resources\BudgetLineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBudgetLine extends EditRecord
{
    protected static string $resource = BudgetLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
