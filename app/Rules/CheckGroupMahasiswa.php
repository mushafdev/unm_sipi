<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\GroupDetail;

class CheckGroupMahasiswa implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cek=GroupDetail::where('mahasiswa_id',decrypt0($value))->count();
        if ($cek>0) {
            $fail('mahasiswa ini sudah terdaftar dalam kelompok');
        }
    }
}
