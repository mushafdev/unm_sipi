<?php
namespace App\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ItemHistory;

class TransactionHelper
{
    public static function jenis_transaksi($jenis_transaksi)
    {
        $result = [
            'Masuk' => 'bg-light-success',
            'Keluar' => 'bg-light-danger',
            'Transfer' => 'bg-light-warning',
        ];
        return $result[$jenis_transaksi] ?? 'bg-light-secondary';
    }
    
    public static function transaksi_kas($type)
    {
        $result = [
            'income' => 'bg-light-success',
            'expense' => 'bg-light-danger',
        ];
        return $result[$type] ?? 'bg-light-secondary';
    }

    public static function statusOpname($status, $type = 'text')
    {
        $result = [
            'draft' => [
                'text' => 'Draft',
                'class' => 'bg-light-warning text-dark',
            ],
            'selesai' => [
                'text' => 'Selesai',
                'class' => 'bg-light-success text-dark',
            ],
            'batal' => [
                'text' => 'Dibatalkan',
                'class' => 'bg-light-danger text-dark',
            ],
        ];

        return $result[$status][$type] ?? '-';
    }
    
    public static function cancel($status, $type = 'text')
    {
        $result = [
            'N' => [
                'text' => 'Berhasil',
                'class' => 'bg-light-success text-success',
            ],
            'Y' => [
                'text' => 'Batal',
                'class' => 'bg-light-danger text-danger',
            ],
        ];

        return $result[$status][$type] ?? '-';
    }
    
    public static function StokAwalisLocked($status, $type = 'text')
    {
        $result = [
            'N' => [
                'text' => 'Draft',
                'class' => 'bg-light-warning text-warning',
            ],
            'Y' => [
                'text' => 'Dikunci',
                'class' => 'bg-light-primary text-primary',
            ],
        ];

        return $result[$status][$type] ?? '-';
    }

    public static function status_pendaftaran($jenis_transaksi)
    {
        $result = [
            'Menunggu' => 'bg-light-secondary',
            'Dilayani' => 'bg-light-info',
            'Pembayaran' => 'bg-light-warning',
            'Selesai' => 'bg-light-success',
        ];
        return $result[$jenis_transaksi] ?? 'bg-light-secondary';
    }
}

/**
 * Write code on Method
 *
 * @return response()
 */

