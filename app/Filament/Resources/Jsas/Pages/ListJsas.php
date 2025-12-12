<?php

namespace App\Filament\Resources\Jsas\Pages;

use App\Filament\Resources\Jsas\JsaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJsas extends ListRecords
{
    protected static string $resource = JsaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
