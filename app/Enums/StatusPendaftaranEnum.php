<?php

namespace App\Enums;

enum StatusPendaftaranEnum: string
{
    case Menunggu = 'Menunggu';
    case Dilayani = 'Dilayani';
    case Pembayaran = 'Pembayaran';
    case Selesai = 'Selesai';

    public function label(): string
    {
        return match($this) {
            self::Menunggu => 'Menunggu',
            self::Dilayani => 'Dilayani',
            self::Pembayaran => 'Pembayaran',
            self::Selesai => 'Selesai',
        };
    }
}
