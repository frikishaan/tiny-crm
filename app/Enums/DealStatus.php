<?php

namespace App\Enums;

enum DealStatus: int
{
    case Open = 1;
    case Won = 2;
    case Lost = 3;
}