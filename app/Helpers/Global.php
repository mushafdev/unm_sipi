<?php

use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('encrypt0')) {
    function encrypt0($string)
    {
        return base64_encode(Crypt::encryptString($string));
    }
}

if (! function_exists('decrypt0')) {
    function decrypt0($string)
    {
        try {
            return Crypt::decryptString(base64_decode($string));
        } catch (DecryptException $e) {
            abort(404);
        }
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
        return Carbon::createFromFormat('Y-m-d', $date)->format('m/d/Y');
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
            'title'=>   "Sistem Informasi Praktik Industri",
            'singkat'=>   'SiPi',
            'sub_title'=>'Jurusan Teknik Informatika dan Komputer',
            'text'=>'',
            'by'=>'Jurusan Teknik Informatika dan Komputer',
            'support'=>'Jurusan Teknik Informatika dan Komputer',
            'university'=>'Universitas Negeri Makassar',
        );
        return $result;
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

if (! function_exists('adminVerifyStatus')) {
    function adminVerifyStatus($status,$jenis)
    {
        $result = array (
            'N' =>array(
                'badge'=>'bg-light-secondary',
                'text'=>'Belum diverifikasi admin',
            ),
            
            'X' =>array(
                'badge'=>'bg-danger',
                'text'=>'Ditolak admin',
            ),
            'Y' =>array(
                'badge'=>'bg-success',
                'text'=>'Disetujui admin',
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
