<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationEntityType: string
{
    case Member = 'member';
    case User = 'user';
    case FeePayment = 'fee_payment';
    case Subscription = 'subscription';
    case Attendance = 'attendance';
    case Invoice = 'invoice';
}
