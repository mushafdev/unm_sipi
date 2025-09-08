<?php

namespace App\Enums;

enum StatusKawinEnum: string
{
    case M = 'M';
    case BM = 'BM';
    case CH = 'CH';
    case CM = 'CM';

    public function label(): string
    {
        return match($this) {
            self::M => 'Menikah',
            self::BM => 'Belum Menikah',
            self::CH => 'Cerai Hidup',
            self::CM => 'Cerai Mati',
        };
    }
}
