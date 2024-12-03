<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Group;

class CheckGroup implements ValidationRule
{
    protected string $group_id;

    public function __construct(string $group_id)
    {
        $this->group_id = 'x';
        if(!empty($group_id)){
            $this->group_id = decrypt0($group_id);
        }
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cek=Group::where('lokasi_pi_id',decrypt0($value))
        ->where('done','N')->where('id','<>',$this->group_id)->count();
        if ($cek>=2) {
            $fail('Lokasi ini sudah memenuhi kuota 2 kelompok');
        }
    }
}
