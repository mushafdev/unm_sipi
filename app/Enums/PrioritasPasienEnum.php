<?php

namespace App\Enums;

enum PrioritasPasienEnum: string {
    case Tidak = 'N';
    case Ya = 'Y';

    public function label(): string {
        return match($this) {
            self::Tidak => 'Tidak',
            self::Ya => 'Ya',
        };
    }
}