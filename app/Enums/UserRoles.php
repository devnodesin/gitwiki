<?php

namespace App\Enums;

enum UserRoles: string
{
    case Admin = 'admin';
    case Reader = 'reader';

    /**
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
