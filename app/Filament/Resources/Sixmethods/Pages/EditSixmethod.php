<?php

namespace App\Filament\Resources\Sixmethods\Pages;

use App\Filament\Resources\Sixmethods\SixmethodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSixmethod extends EditRecord
{
    protected static string $resource = SixmethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
