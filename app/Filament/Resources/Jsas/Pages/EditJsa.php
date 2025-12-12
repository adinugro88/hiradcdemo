<?php

namespace App\Filament\Resources\Jsas\Pages;

use App\Filament\Resources\Jsas\JsaResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditJsa extends EditRecord
{
    protected static string $resource = JsaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
