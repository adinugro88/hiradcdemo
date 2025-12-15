<?php

namespace App\Filament\Resources\Sixmethods\Pages;

use App\Filament\Resources\Sixmethods\SixmethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSixmethods extends ListRecords
{
    protected static string $resource = SixmethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
