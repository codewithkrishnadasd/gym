<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationRecipientType: string
{
    case Member = 'member';
    case User = 'user';
    /** Someone with no record of their own: a walk-in payer or invoice recipient. */
    case Contact = 'contact';
}
