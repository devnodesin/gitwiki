<?php

namespace App\Enums;

enum UserRoles: string
{
    case Admin = 'admin';
    case User = 'user';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
