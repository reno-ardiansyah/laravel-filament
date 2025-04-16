<?php

namespace App\Filament\Resources\SubjectResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class TeachersRelationManager extends RelationManager
{
    protected static string $relationship = 'teachers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $lastName = $get('last_name');
                            $set('user.name', trim("$state $lastName"));
                        }),

                    Forms\Components\TextInput::make('last_name')
                        ->nullable()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $firstName = $get('first_name');
                            $set('user.name', trim("$firstName $state"));
                            $set('user.username', Str::slug(trim("$firstName $state")));
                        }),

                    Forms\Components\TextInput::make('nip')
                        ->required()
                        ->integer()
                        ->minLength(18)
                        ->maxLength(18)
                        ->live(onBlur: true),

                    Forms\Components\Select::make('type')
                        ->searchable()
                        ->required()
                        ->options([
                            'teacher' => 'Teacher',
                            'headmaster' => 'Headmaster',
                            'vice-principal' => 'Vice Principal',
                        ]),

                    Forms\Components\Group::make()
                        ->relationship('user') // make sure model Teacher has user() relationship
                        ->schema([
                            Forms\Components\FileUpload::make('profile_picture')
                                ->image()
                                ->preserveFilenames()
                                ->directory('profile_pictures')
                                ->visibility('public')
                                ->maxSize(1024)
                                ->acceptedFileTypes(['image/*'])
                                ->default(''),

                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->readOnly()
                                ->live(onBlur: true)
                                ->default('')
                                ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('user.username', Str::slug($state)))
                                ->dehydrated(true),

                            Forms\Components\TextInput::make('username')
                                ->label('Username')
                                ->required()
                                ->default('')
                                ->unique(column: 'username')
                                ->dehydrated(true),

                            Forms\Components\TextInput::make('email')
                                ->label('Email')
                                ->required()
                                ->email()
                                ->default('')
                                ->unique(column: 'email', ignorable: fn($record) => $record)
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->live(onBlur: true),

                            Forms\Components\DatePicker::make('dob')
                                ->label('Date of Birth')
                                ->placeholder('YYYY-MM-DD')
                                ->maxDate(now())
                                ->format('Y-m-d')
                                ->displayFormat('d/m/Y')
                                ->required(),

                            Forms\Components\Select::make('gender')
                                ->label('Gender')
                                ->options([
                                    'male' => 'Male',
                                    'female' => 'Female',
                                    'other' => 'Other',
                                ])
                                ->nullable()
                                ->default('other'),

                            Forms\Components\TextInput::make('password')
                                ->label('Password')
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->password()
                                ->minLength(8)
                                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->confirmed('password_confirmation')
                                ->live(onBlur: true),

                            Forms\Components\TextInput::make('password_confirmation')
                                ->label('Password Confirmation')
                                ->password()
                                ->required(fn(string $operation): bool => $operation === 'create')
                                ->dehydrated(false),

                            Forms\Components\Textarea::make('address')
                                ->label('Address')
                                ->maxLength(65535)
                                ->autosize()
                                ->rows(3)
                                ->default('')
                                ->dehydrated(true),
                        ])
                        ->columnSpan(2),
                ])
                ->columns([
                    'sm' => 2,
                ])
                ->columnSpan([
                    'sm' => 2,
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name', 'grade')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->formatStateUsing(function ($state, $record) {
                    return new \Illuminate\Support\HtmlString(
                        "<span> $record->first_name $record->last_name </span>"
                    );
                }),
                Tables\Columns\TextColumn::make('pivot.grade'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->form(fn(Tables\Actions\AttachAction $action): array => [
                    $action->getRecordSelect(),
                    Forms\Components\TextInput::make('grade')->required(),
                ])
                ->multiple(),
                Tables\Actions\CreateAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
