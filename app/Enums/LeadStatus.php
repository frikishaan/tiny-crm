<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LeadStatus: int implements HasColor, HasLabel
{
    case Prospect = 1;
    case Open = 2;
    case Qualified = 3;
    case Disqualified = 4;

    public function getLabel(): string
    {
        return match ($this) {
            self::Prospect => 'Prospect',
            self::Open => 'Open',
            self::Qualified => 'Qualified',
            self::Disqualified => 'Disqualified'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Prospect => 'secondary',
            self::Open => 'warning',
            self::Qualified => 'success',
            self::Disqualified => 'danger'
        };
    }
}