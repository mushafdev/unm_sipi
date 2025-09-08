<?php

namespace App\Enums;

enum DisplayNamaEnum: string {
    case Lengkap = 'l';
    case Panggilan = 'p';

    public function label(): string {
        return match($this) {
            self::Lengkap => 'Nama Lengkap + Gelar',
            self::Panggilan => 'Nama Panggilan',
        };
    }
}