<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductType: int implements HasColor, HasLabel
{
    case Service = 1;
    case Physical = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::Service => 'Service',
            self::Physical => 'Physical',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Service => 'success',
            self::Physical => 'warning'
        };
    }
}