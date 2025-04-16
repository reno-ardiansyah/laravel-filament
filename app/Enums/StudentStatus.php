<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum StudentStatus: string implements HasLabel
{
    case Active = 'active';
    case InActive = 'inactive';

    public function getLabel(): ?string
    {
        return $this->name;
    }
}
