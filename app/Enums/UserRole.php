<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Secretaire = 'secretaire';
    case Protocole = 'protocole';
    case Ancienne = 'ancienne';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Secretaire => 'Secrétaire',
            self::Protocole => 'Protocole',
            self::Ancienne => 'Ancienne d\'église',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
