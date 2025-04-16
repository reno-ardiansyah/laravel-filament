<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use App\{Models, Enums};
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use phpDocumentor\Reflection\Types\Boolean;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Records';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->columns(2)
                    ->schema([
                        Forms\Components\Select::make('subject_id')->label('Subject')->options(Models\Subject::all()->pluck('name', 'id'))->searchable()->required(),
                        Forms\Components\Select::make('period_id')->label('Period')->options(Models\Period::all()->pluck('value', 'id'))->searchable()->required()->live(),
                        Forms\Components\Select::make('class_room')->label('Class Room')->searchable()->required()->reactive()
                            ->options(fn(Forms\Get $get): Collection => Models\ClassRoom::query()->where('period_id', $get('period_id'))->get()->mapWithKeys(fn($classroom) => [$classroom->id => "$classroom->grade - $classroom->section"]))
                            ->disabled(fn(Forms\Get $get) => !$get('period_id')),
                        Forms\Components\Select::make('day')->options(Enums\DayOptions::class)->searchable()->required(),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Start Time')
                            ->seconds(false)
                            ->required(),

                        Forms\Components\TimePicker::make('end_time')
                            ->label('End Time')
                            ->seconds(false)
                            ->required(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('schedulable_type')->label('Schedule For')->options([
                                    Models\Teacher::class => 'Teacher',
                                    Models\Student::class => 'Student',
                                ])->required()->live()->afterStateUpdated(fn(Forms\Set $set) => $set('schedulable_id', null)),
                                Forms\Components\Select::make('schedulable_id')->label('Entity')
                                    ->options(fn(Forms\Get $get) => match ($get('schedulable_type')) {
                                        Models\Teacher::class => Models\Teacher::all()->pluck('first_name', 'id'),
                                        Models\Student::class => Models\Student::all()->pluck('first_name', 'id'),
                                        default => [],
                                    })->searchable()->required()->reactive()->hidden(fn(Forms\Get $get) => !$get('schedulable_type'))
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedulable_type')->label('Type')->formatStateUsing(fn($state) => class_basename($state))->sortable(),
                Tables\Columns\TextColumn::make('schedulable.first_name')->label('Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('subject.name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('classRoom.section')
                    ->formatStateUsing(fn($state, $record) => new \Illuminate\Support\HtmlString($record->classRoom->grade . " " . $record->classRoom->section))
                    ->sortable()
                    ->searchable()
                    ->label('Class Room'),
                Tables\Columns\TextColumn::make('period.value')->label('Period')->sortable(),
                Tables\Columns\TextColumn::make('day')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),

            ])
            ->filters([
                //
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
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
