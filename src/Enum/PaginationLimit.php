<?php

namespace App\Enum;

enum PaginationLimit: int
{
    case DEFAULT = 15;
    case TAGS = 6;
}
