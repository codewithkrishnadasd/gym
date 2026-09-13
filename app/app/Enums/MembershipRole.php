<?php

declare(strict_types=1);

namespace App\Enums;

enum MembershipRole: string
{
    case Admin = 'admin';
    case User = 'user';
}
