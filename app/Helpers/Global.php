<?php

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
/**
 * Write code on Method
 *
 * @return response()
 */
if (!function_exists('encrypt0')) {
    function encrypt0($string)
    {
        try {
            return base64_encode(Crypt::encryptString($string));
        } catch (\Exception $e) {
            // Bisa log error di sini jika perlu
            return null;
        }
    }
}

if (!function_exists('decrypt0')) {
    function decrypt0($string)
    {
        try {
            if (!$string) return null;
            return Crypt::decryptString(base64_decode($string));
        } catch (DecryptException $e) {
            // Bisa log error di sini jika perlu
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('encrypt1')) {
    function encrypt1($value)
    {
        try {
            $ciphering = "AES-256-CBC";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $iv = '1234567891011121'; // 16 bytes (128 bit)
            $key = hash('sha256', 'MushafSijaya10', true); // 32 bytes key

            $encrypted = openssl_encrypt($value, $ciphering, $key, $options, $iv);
            return base64_encode($encrypted);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('decrypt1')) {
    function decrypt1($encrypted)
    {
        try {
            if (!$encrypted) return null;

            $ciphering = "AES-256-CBC";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $iv = '1234567891011121'; 
            $key = hash('sha256', 'MushafSijaya10', true); 

            $decoded = base64_decode($encrypted);
            return openssl_decrypt($decoded, $ciphering, $key, $options, $iv);
        } catch (\Exception $e) {
            return null;
        }
    }
}


if (! function_exists('toCurrency')) {
    function toCurrency($number)
    {
        $rp = number_format($number,2,',','.');
        return $rp;
    }
}

if (! function_exists('CurrencytoDb')) {
    function CurrencytoDb($number)
    {
        $db = str_replace('.', '', $number);
        $db2 = str_replace(',', '.', $db);
        return (float) $db2;
    }
}

if (!function_exists('pembulatan_ke_atas')) {
    function pembulatan_ke_atas($angka, $kelipatan = 100)
    {
        return ceil($angka / $kelipatan) * $kelipatan;
    }
}

if (! function_exists('convertYmdToMdy')) {
    function convertYmdToMdy($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('m-d-Y');
    }
}

if (! function_exists('convertYmdToMdy2')) {
    function convertYmdToMdy2($date)
    {
        return Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
    }
}

if (! function_exists('toDatetime')) {
    function toDatetime($datetime)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('d/m/Y H:i');
    }
}
if (! function_exists('toDatetime2')) {
    function toDatetime2($datetime)
    {
        if (empty($datetime)) {
            return '-'; // atau 'Tidak tersedia', 'Belum diisi', dsb.
        }

        return Carbon::parse($datetime)
            ->locale('id')
            ->translatedFormat('d F Y, H:i');
    }
}

if (! function_exists('DatetimeToHour')) {
    function DatetimeToHour($datetime)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $datetime)->format('H:i');
    }
}
  
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('convertMdyToYmd')) {
    function convertMdyToYmd($date)
    {
        return Carbon::createFromFormat('m-d-Y', $date)->format('Y-m-d');
    }
}

if (! function_exists('identity')) {
    function identity()
    {
        $result = array (
            'title'=>   "Alisa Skin Clinic",
            'singkat'=>   'Alisa Skin Clinic',
            'sub_title'=>'Alisa Skin Clinic',
            'text'=>'',
            'by'=>'Alisa Skin Clinic',
            'support'=>'PT. King Lambda Teknologi',
            'assets_version'=>'1.0',
            'migration'=>'Y',
        );
        return $result;
    }
}

if (! function_exists('setting')) {
    function setting()
    {
        $setting = DB::table('settings')->first();
        return $setting;
    }
}

if (!function_exists('menu_active')) {
    function menu_active($patterns, $class = 'active') {
        foreach ((array) $patterns as $pattern) {
            if (request()->is($pattern)) {
                return $class;
            }
        }
        return '';
    }
}

if (! function_exists('getMonthFromNumber')) {
    function getMonthFromNumber($num)
    {
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        return $bulan[$num];
    }
}

if (! function_exists('statusUjian')) {
    function statusUjian($status,$jenis)
    {
        $result = array (
            'draft' =>array(
                'badge'=>'bg-light-secondary',
                'text'=>'Draft',
            ),
            
            'published' =>array(
                'badge'=>'bg-warning',
                'text'=>'Dipublish',
            ),
            'complete' =>array(
                'badge'=>'bg-success',
                'text'=>'Selesai',
            ),
        );

        return $result[$status][$jenis];
    }
}

if (! function_exists('tgl_indo')) {
    function tgl_indo($tanggal){
        if($tanggal=='' || $tanggal=='0000-00-00' ){
            return '-';
        }else{
            $bulan = array (
                1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
            $pecahkan = explode('-', $tanggal);
            return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
        }

    }
}

if (! function_exists('umur')) {
    function umur($tanggal){
        $umur = Carbon::parse($tanggal)->age;
        return $umur. ' Tahun';
    }
}

if (! function_exists('monthToRomawi')) {
    function monthToRomawi($bulan) {
        // Array mapping bulan ke angka Romawi
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
    
        if (isset($romawi[$bulan])) {
            return $romawi[$bulan];
        } else {
            return "???";
        }
    }
}
