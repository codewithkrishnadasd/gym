<?php

declare(strict_types=1);

namespace App\Enums;

enum WhatsappStatus: string
{
    case NotSent = 'not_sent';
    case Ready = 'ready';
    case Opened = 'opened';
    case Skipped = 'skipped';
    case Failed = 'failed';
}
