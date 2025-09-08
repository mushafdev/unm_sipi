<?php

namespace App\Enums;

enum GolonganDarahEnum: string
{
    case A = 'A';
    case B = 'B';
    case O = 'O';
    case AB = 'AB';

    public function label(): string
    {
        return match($this) {
            self::A => 'A',
            self::B => 'B',
            self::O => 'O',
            self::AB => 'AB',
        };
    }
}
