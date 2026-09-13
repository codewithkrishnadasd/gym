<?php

declare(strict_types=1);

namespace App\Enums;

enum AttendanceSubjectType: string
{
    case Member = 'member';
    case User = 'user';
}
