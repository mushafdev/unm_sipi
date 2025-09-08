<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\ItemStok;

class StokCukup implements ValidationRule
{
    protected $item_id;
    protected $gudang_id;
    protected $no_batch;
    protected $tgl_kadaluarsa;
    protected $message;

    public function __construct($item_id, $gudang_id, $tgl_kadaluarsa = null, $no_batch = null)
    {
        $this->item_id = $item_id;
        $this->gudang_id = $gudang_id;
        $this->no_batch = $no_batch;
        $this->tgl_kadaluarsa = $tgl_kadaluarsa;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = ItemStok::where('item_id', $this->item_id)
            ->where('gudang_id', $this->gudang_id);

        if ($this->no_batch !== null) {
            $query->where('no_batch', $this->no_batch);
        }

        if ($this->tgl_kadaluarsa !== null) {
            $query->where('tgl_kadaluarsa', $this->tgl_kadaluarsa);
        }

        $stok = $query->sum('stok');

        if ($value > $stok) {
            $fail("Stok tidak cukup (tersedia: $stok)");
        }
    }
}
