<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PeriodResource\Pages;
use App\Filament\Resources\PeriodResource\RelationManagers;
use App\Models\Period;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PeriodResource extends Resource
{
    protected static ?string $model = Period::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationGroup = 'Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('value')
                            ->label('School Period')
                            ->searchable()
                            ->required()
                            ->live()
                            ->options(function () {
                                $startYear = 2019;
                                $endYear = \Illuminate\Support\Carbon::now()->year + 2;

                                $allPeriods = [];
                                for ($i = $startYear; $i <= $endYear; $i += 2) {
                                    $allPeriods[] = "{$i}/" . ($i + 2);
                                }

                                $existingPeriods = static::getModel()::pluck('value')->toArray();

                                $availablePeriods = collect($allPeriods)
                                    ->reject(fn($period) => in_array($period, $existingPeriods))
                                    ->values()
                                    ->toArray();

                                return array_combine($availablePeriods, $availablePeriods);
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')->label('School Period')->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('value')->label('Filter School Period')->options(fn() => static::getModel()::pluck('value', 'value')->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPeriods::route('/'),
            'create' => Pages\CreatePeriod::route('/create'),
            'edit' => Pages\EditPeriod::route('/{record}/edit'),
        ];
    }
}
