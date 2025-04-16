<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DayOptions: string implements HasLabel
{
    case Monday = 'monday';
    case Tuesday = 'tuesday';
    case Wednesday = 'wednesday';
    case Thursday = 'thursday';
    case Friday = 'friday';
    case Saturday = 'saturday';
    case Sunday = 'sunday';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
