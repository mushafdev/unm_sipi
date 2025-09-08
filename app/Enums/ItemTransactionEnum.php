<?php

namespace App\Enums;

enum ItemTransactionEnum: string
{
    case MASUK = 'Masuk';
    case KELUAR = 'Keluar';
    case TRANSFER = 'Transfer';

    public function label(): string
    {
        return match($this) {
            self::MASUK => 'Masuk',
            self::KELUAR => 'Keluar',
            self::TRANSFER => 'Transfer',
        };
    }
}
