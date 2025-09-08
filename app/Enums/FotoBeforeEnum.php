<?php

namespace App\Enums;

enum FotoBeforeEnum: string
{
    case I = 'I';
    case II = 'II';
    case III = 'III';
    case IV = 'IV';
    case V = 'V';

    public function label(): string
    {
        return match($this) {
            self::I => 'Foto Depan',
            self::II => 'Serong Kanan',
            self::III => 'Kanan',
            self::IV => 'Serong Kiri',
            self::V => 'Kiri',
        };
    }
}