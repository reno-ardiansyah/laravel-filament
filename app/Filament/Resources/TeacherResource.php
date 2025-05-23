<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Teacher;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TeacherResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TeacherResource\RelationManagers;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Academic';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('first_name')->required()->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $lastName = $get('last_name');
                            $set('user.name', trim("$state $lastName"));
                        }),
                    Forms\Components\TextInput::make('last_name')->nullable()->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                            $firstName = $get('first_name');
                            $set('user.name', trim("$firstName $state"));
                        }),
                    Forms\Components\TextInput::make('nip')->required()->integer()->minLength(18)->maxLength(18)->live(onBlur: true),
                    Forms\Components\Select::make('type')->searchable()->required()
                        ->options([
                            'teacher' => 'Teacher',
                            'headmaster' => 'Headmaster',
                            'vice-principal' => 'Vice Principal',
                        ]),
                    Forms\Components\Group::make()
                        ->relationship('user') // pastikan Teacher punya kolom 'user_id'
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
                                ->default('') // pastikan default ada
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
                                ->options(\App\Enums\Gender::class)
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
                        ->columnSpan(2)


                ])->columns([
                    'sm' => 2,
                ])->columnSpan([
                    'sm' => 2,
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Teacher Name')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $avatar = '<img src="' . (Storage::url($record?->user?->profile_picture) ?? 'https://ui-avatars.com/api/?name=' . urlencode($record?->name)) . '" alt="Avatar" class="w-8 h-8 rounded-full me-4 inline">';
                        $name = '<span class="font-medium">' . e($record->first_name) . ' ' . e($record->last_name) . '</span>';
                        $email = '<div class="text-gray-500 text-sm">' . e($record?->user?->email) . '</div>';
                        return new \Illuminate\Support\HtmlString(
                            '<div class="flex items-center space-x-2">' .
                                $avatar .
                                '<div>' . $name . $email . '</div>' .
                                '</div>'
                        );
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('nip')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.dob')
                    ->sortable()
                    ->searchable()
                    ->label('Date of Birth'),
                Tables\Columns\TextColumn::make('user.address')
                    ->sortable()
                    ->limit(60)
                    ->searchable()
                    ->label('Address'),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->searchable()
                    ->label('Position'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $teacher = static::getModel()::create($data);
        $teacher->user->assignRole('Teacher');
        return $teacher;
    }
}
