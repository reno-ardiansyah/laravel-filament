<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\{Models, Enums};
use Filament\Forms;
use Filament\Actions;
use Filament\Forms\Form;
use Illuminate\Support\Collection;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ScheduleResource;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected static string $view = 'filament.pages.schedule.create';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->columns(2)
                    ->schema([
                        Forms\Components\Select::make('subject_id')->label('Subject')->options(Models\Subject::all()->pluck('name', 'id'))->live()->searchable()->required(),
                        Forms\Components\Select::make('period_id')->label('Period')->options(Models\Period::all()->pluck('value', 'id'))->searchable()->required()->live(),
                        Forms\Components\Select::make('class_room')->label('Class Room')->searchable()->required()->reactive()
                            ->options(fn(Forms\Get $get): Collection => Models\ClassRoom::query()->where('period_id', $get('period_id'))->get()->mapWithKeys(fn($classroom) => [$classroom->id => "$classroom->grade - $classroom->section"]))
                            ->disabled(fn(Forms\Get $get) => !$get('period_id'))->columnSpan(2),
                        Forms\Components\Repeater::make('entitys')
                            ->schema([

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('day')->options(Enums\DayOptions::class)->searchable()->required()->columnSpan(2),
                                        Forms\Components\TimePicker::make('start_time')
                                            ->label('Start Time')
                                            ->seconds(false)
                                            ->required(),

                                        Forms\Components\TimePicker::make('end_time')
                                            ->label('End Time')
                                            ->seconds(false)
                                            ->required(),
                                        Forms\Components\Select::make('schedulable_type')->label('Schedule For')->options([
                                            Models\Teacher::class => 'Teacher',
                                            Models\Student::class => 'Student',
                                        ])->required()->live()->afterStateUpdated(fn(Forms\Set $set) => $set('schedulable_id', null)),
                                        Forms\Components\Select::make('schedulable_id')->label('Entity')
                                            ->options(fn(Forms\Get $get) => match ($get('schedulable_type')) {
                                                Models\Teacher::class => Models\Teacher::whereHas('subjects', fn($q) => $q->whereNot('id', $get('subject_id')))->pluck('first_name', 'id'),
                                                Models\Student::class => Models\Student::all()->pluck('first_name', 'id'),
                                                default => [],
                                            })->searchable()->required()->reactive()->hidden(fn(Forms\Get $get) => !$get('schedulable_type'))
                                    ])
                            ])
                            ->minItems(1)
                            ->columns(1)
                            ->defaultItems(1)
                            ->columnSpan(2),
                    ])
            ]);
    }

    public function save()
    {
        $get = $this->form->getState();

        $insert = [];
        foreach ($get['entitys'] as $row) {
            array_push($insert, [
                'subject_id' => $get['subject_id'],
                'period_id' => $get['period_id'],
                'class_room' => $get['class_room'],
                'day' => $row['day'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'schedulable_type' => $row['schedulable_type'],
                'schedulable_id' => $row['schedulable_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Models\Schedule::insert($insert);

        return redirect()->to('admin/schedules');
    }
}
