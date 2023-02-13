<?php

namespace App\Enums;

enum LeadSource: int
{
    case Email = 1;
    case Event = 2;
    case Phone = 3;
    case Referral = 4;
    case Web = 5;
}