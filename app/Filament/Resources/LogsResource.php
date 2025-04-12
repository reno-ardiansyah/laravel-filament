<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Logs;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\LogsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LogsResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class LogsResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Logs::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Log';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
        ];
    }


    public static function form(Form $form): Form
    {
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('loggable_type')->label('Model'),
                Tables\Columns\TextColumn::make('loggable_id')->label('Model ID'),
                Tables\Columns\TextColumn::make('user.name')->label('User')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('message')->limit(50)->tooltip(fn($record) => $record->message),
                Tables\Columns\TextColumn::make('changes')->limit(50)->tooltip(fn($record) => $record->changes),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Logged At')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canViewAny(): bool
    {
        return true;
    }
}
