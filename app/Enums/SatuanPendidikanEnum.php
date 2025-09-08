<?php

namespace App\Enums;

enum SatuanPendidikanEnum: string
{
    case TK = 'TK';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';

    public function label(): string
    {
        return match($this) {
            self::TK => 'TK',
            self::SD => 'SD',
            self::SMP => 'SMP',
            self::SMA => 'SMA',
        };
    }
}
