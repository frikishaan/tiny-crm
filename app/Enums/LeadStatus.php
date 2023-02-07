<?php

namespace App\Enums;

enum LeadStatus: int
{
    case Prospect = 1;
    case Open = 2;
    case Qualified = 3;
    case Disqualified = 4;
}