<?php

namespace App\Filament\Resources\ClassRoomResource\Pages;

use Filament\Forms;
use Filament\Actions;
use App\Models\Period;
use Filament\Forms\Form;
use App\Models\ClassRoom;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ClassRoomResource;
use Awcodes\TableRepeater;

class CreateClassRoom extends CreateRecord
{
    protected static string $resource = ClassRoomResource::class;

    protected static string $view = 'filament.pages.class-rooms.create';

    public function form(Form $form): Form
    {
        return $form->schema([
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
                    TableRepeater\Components\TableRepeater::make('sections')
                        ->label('Sections')
                        ->headers([          
                            TableRepeater\Header::make('section')->width('150px'),
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('section')
                                ->required()
                                ->minLength(1)
                                ->maxLength(20),
                        ])
                        ->minItems(1)
                        ->columns(1)
                        ->defaultItems(1)
                        ->columnSpan(2),
                ])
        ]);
    }

    public function save() {
        $get = $this->form->getState();

        $insert = [];
        foreach($get['sections'] as $row) {
            array_push($insert, [
                'grade' => $get['grade'],
                'period_id' => $get['period_id'],
                'section' => $row['section'],
            ]);
        }

        ClassRoom::insert($insert);

        return redirect()->to('admin/class-rooms');
    }
}
