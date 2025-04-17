<?php

namespace App\Filament\Resources\GradeResource\Widgets;

use Filament\Widgets\ChartWidget;

class GradesStudentWidget extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
