<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectProcessesRelationManager extends RelationManager
{
    protected static string $relationship = 'projectProcesses';

    protected static ?string $title = 'Processes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('process')
                //     ->required()
                //     ->maxLength(255),
                Select::make('process')
                    ->label('Process')
                    ->options(\App\Models\MasterProcess::pluck('name', 'name'))
                    ->searchable()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('process')
            ->columns([
                TextColumn::make('process')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Add Process'),
                // AssociateAction::make(),
            ])
            ->recordActions([
                Action::make('pdf')
                    ->label('View PDF')
                    ->url(fn($record) => route('hiradc.pdf', ['id' => $record]))
                    ->icon('heroicon-o-document')
                    ->openUrlInNewTab(),
                ViewAction::make()
                    ->url(fn($record) => route('filament.admin.resources.project-processes.edit', ['record' => $record])),
                // EditAction::make(),
                // DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
