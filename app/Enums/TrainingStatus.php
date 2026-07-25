<?php

namespace App\Enums;

enum TrainingStatus: string
{
    case Planned = 'planned';
    case Approved = 'approved';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function consumesBudget(): bool
    {
        return in_array($this, [self::Approved, self::InProgress, self::Completed], true);
    }
}
