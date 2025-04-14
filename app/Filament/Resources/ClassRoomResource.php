<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Period;
use Filament\Forms\Form;
use App\Models\ClassRoom;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\ClassRoomResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClassRoomResource\RelationManagers;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Academic';

    public static function form(Form $form): Form
    {
        $isEdit = request()->routeIs('filament.admin.resources.class-rooms.edit');

        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('grade')
                            ->required()
                            ->placeholder("enter number of range 10 - 12")
                            ->type('number')
                            ->maxLength(2),
                        Forms\Components\Select::make('period_id')
                            ->label('Period')
                            ->relationship('period', 'value')
                            ->options(Period::pluck('value', 'id')->toArray())
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('section')
                            ->required()
                            ->live(onBlur: true)
                            ->minLength(1)
                            ->maxLength(20)
                            ->columnSpan(2)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('grade')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('section')->searchable(),
                Tables\Columns\TextColumn::make('period.value')->label('School Period')->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('grade')
                    ->options(
                        static::getModel()::query()->distinct()->pluck('grade', 'grade')->toArray()
                    )
                    ->label('Grade'),

                Tables\Filters\SelectFilter::make('section')
                    ->options(
                        static::getModel()::query()->distinct()->pluck('section', 'section')->toArray()
                    )
                    ->label('Section'),

                Tables\Filters\SelectFilter::make('period_id')
                    ->relationship('period', 'value')
                    ->label('School Period'),
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
            'index' => Pages\ListClassRooms::route('/'),
            'create' => Pages\CreateClassRoom::route('/create'),
            'edit' => Pages\EditClassRoom::route('/{record}/edit'),
        ];
    }

    public static function beforeCreate(array $data): void
{
    // Validasi custom untuk menghindari duplikasi data yang sama
    foreach ($data['sections'] as $sectionData) {
        $exists = static::getModel()::where('grade', $data['grade'])
            ->where('period_id', $data['period_id'])
            ->where('section', $sectionData['section'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'sections' => "Kombinasi grade {$data['grade']} dan section {$sectionData['section']} untuk periode ini sudah ada.",
            ]);
        }
    }
}

}
