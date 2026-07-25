<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Manager = 'manager';
    case Viewer = 'viewer';

    public function canManage(): bool
    {
        return $this !== self::Viewer;
    }
}
