<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Admin Control';

    protected static ?string $navigationLabel = 'Manage User';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn(Forms\Set $set, ?string $state) => $set('username', Str::slug($state)))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->maxLength(255)
                            ->unique(column: 'username', ignorable: fn($record) => $record),
                        Forms\Components\TextInput::make('email')
                            ->required()
                            ->email()
                            ->maxLength(255)
                            ->unique(column: 'email', ignorable: fn($record) => $record)
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->live(onBlur: true),
                        Forms\Components\DatePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->placeholder('YYYY-MM-DD')
                            ->maxDate(now()),
                        Forms\Components\Select::make('roles')->multiple()->relationship('roles', 'name'),

                        Forms\Components\TextInput::make('password')
                            ->required()
                            ->password()
                            ->minLength(8)
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->confirmed('password_confirmation')
                            ->live(onBlur: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->dehydrated(false)
                            ->maxLength(255),
                        Forms\Components\Textarea::make('address')
                            ->maxLength(65535)
                            ->autosize()
                            ->rows(3)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('postal_code')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('dob')
                            ->label('Date of Birth')
                            ->placeholder('YYYY-MM-DD')
                            ->maxDate(now())
                            ->format('Y-m-d')
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other',
                            ])
                            ->nullable(),
                        Forms\Components\FileUpload::make('profile_picture')
                            ->image()
                            ->preserveFilenames()
                            ->directory('profile_pictures')
                            ->visibility('public')
                            ->maxSize(1024)
                            ->acceptedFileTypes(['image/*'])
                            ->columnSpan(2),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('User')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $avatar = '<img src="' . (Storage::url($record->profile_picture) ?? 'https://ui-avatars.com/api/?name=' . urlencode($record->name)) . '" alt="Avatar" class="w-8 h-8 rounded-full me-4 inline">';
                        $name = '<span class="font-medium">' . e($record->name) . '</span>';
                        $email = '<div class="text-gray-500 text-sm">' . e($record->email) . '</div>';
                        return new \Illuminate\Support\HtmlString(
                            '<div class="flex items-center space-x-2">' .
                                $avatar .
                                '<div>' . $name . $email . '</div>' .
                                '</div>'
                        );
                    })
                    ->html(), // Ini penting agar HTML ditampilkan

                    Tables\Columns\TextColumn::make('username')
                        ->sortable()
                        ->searchable()
                        ->label('Username'),
                Tables\Columns\TextColumn::make('phone')
                    ->sortable()
                    ->searchable()
                    ->label('Phone'),
                Tables\Columns\TextColumn::make('address')
                    ->sortable()
                    ->searchable()
                    ->limit(50)
                    ->label('Address'),
                Tables\Columns\TextColumn::make('postal_code')
                    ->sortable()
                    ->searchable()
                    ->label('Postal Code'),
                Tables\Columns\TextColumn::make('dob')
                    ->sortable()
                    ->searchable()
                    ->label('Date of Birth'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
