<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum DealStatus: int implements HasColor, HasLabel
{
    case Open = 1;
    case Won = 2;
    case Lost = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => 'Open',
            self::Won => 'Won',
            self::Lost => 'Lost'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Open => 'warning',
            self::Won => 'success',
            self::Lost => 'danger'
        };
    }
}