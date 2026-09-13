<?php

declare(strict_types=1);

namespace App\Enums;

enum ExpenseTargetType: string
{
    case Organisation = 'organisation';
    case Club = 'club';
    case Member = 'member';
    case User = 'user';
}
