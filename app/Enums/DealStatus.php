<?php

namespace App\Enums;

enum DealStatus: int
{
    case Open = 1;
    case Lost = 2;
    case Won = 3;
}