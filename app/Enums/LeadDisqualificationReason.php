<?php

namespace App\Enums;

enum LeadDisqualificationReason: int
{
    case Budget = 1;
    case Bad_Data = 2;
    case Not_Responsive = 3;
    case LostToCompetitor = 4;
    case Timeline = 5;
}