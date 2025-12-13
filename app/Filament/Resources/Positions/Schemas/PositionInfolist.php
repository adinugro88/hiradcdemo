<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class PositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
       return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('deskripsi (boleh kosong)'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
