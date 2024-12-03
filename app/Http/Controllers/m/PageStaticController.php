<?php

namespace App\Http\Controllers\m;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class PageStaticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


     
     public function logbook($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select('group_details.*','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi','lokasi_pis.lokasi_pi')->first();
        $logbooks=DB::table('logbooks')->where('mahasiswa_id',$detail->mahasiswa_id)
        ->orderBy('tanggal','asc')
        ->orderBy('jam','asc')->get();

          $d['title']='Logbook';
          $d['detail']=$detail;
          $d['logbooks']=$logbooks;
          $pdf = Pdf::setOption(['dpi' => 150, 'defaultFont' => 'poppins'])
          ->setPaper('letter', 'portrait')
          ->loadview('a.pages.page_static.logbook',$d);
          return $pdf->stream();

     }
     
     public function sertifikat_pembekalan($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select('group_details.*','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi','lokasi_pis.lokasi_pi','jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip')->first();
        
          $d['title']='Sertifikat Pembekalan';
          $d['detail']=$detail;
          $pdf = Pdf::setPaper('A4', 'landscape')
          ->loadview('a.pages.page_static.sertifikat_pembekalan',$d);
          return $pdf->stream();

     }
     
     public function permohonan_izin_observasi($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Permohonan Izin Observasi';
          $d['perihal']='Permohonan Izin Observasi';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.permohonan_izin_observasi',$d);
          return $pdf->stream();

     }
     
     public function permohonan_izin_pi($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Permohonan Izin Praktik Industri';
          $d['perihal']='Permohonan Izin Praktik Industri';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.permohonan_izin_pi',$d);
          return $pdf->stream();

     }
     
     public function permohonan_pembimbing($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Permohonan Pembimbing Praktik Industri';
          $d['perihal']='Permohonan Pembimbing Praktik Industri';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.permohonan_pembimbing',$d);
          return $pdf->stream();

     }
     
     public function daftar_hadir($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Daftar Hadir';
          $d['perihal']='Daftar Hadir';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'landscape')
          ->loadview('a.pages.page_static.daftar_hadir',$d);
          return $pdf->stream();

     }
     
     public function daftar_nilai($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Daftar Nilai';
          $d['perihal']='Daftar Nilai';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.daftar_nilai',$d);
          return $pdf->stream();

     }
     
     public function tanda_selesai_pi($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Surat Tanda Bukti Selesai Praktik Industri';
          $d['perihal']='Surat Tanda Bukti Selesai Praktik Industri';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.tanda_selesai_pi',$d);
          return $pdf->stream();

     }
     
     public function persetujuan_hari($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Persetujuan Hari';
          $d['perihal']='Persetujuan Hari';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.persetujuan_hari',$d);
          return $pdf->stream();

     }
     
     public function pengesahan($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Pengesahan';
          $d['perihal']='Pengesahan';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.pengesahan',$d);
          return $pdf->stream();

     }
     
     public function persetujuan_pembimbing($id)
     {
        $id = decrypt0($id);
        $detail=DB::table('group_details')
        ->join('groups','groups.id','=','group_details.group_id')
        ->leftJoin('dosens','dosens.id','=','groups.pembimbing_id')
        ->join('lokasi_pis','lokasi_pis.id','=','groups.lokasi_pi_id')
        ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
        ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
        ->join('jurusans','jurusans.id','=','prodis.jurusan_id')
        ->join('fakultas','fakultas.id','=','jurusans.fakultas_id')
        ->where('group_details.mahasiswa_id',$id)
        ->select(
          'group_details.*','groups.start_month','groups.end_month','groups.year','dosens.nama as pembimbing','dosens.nip as pembimbing_nip',
          'mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','fakultas.fakultas','prodis.prodi','prodis.kaprodi_nama','prodis.kaprodi_nip','lokasi_pis.lokasi_pi','lokasi_pis.kota as lokasi_pi_kota',
          'jurusans.jurusan','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.kajur_nama','jurusans.kajur_nip','jurusans.sekjur_nama','jurusans.sekjur_nip','jurusans.pengelola_nama','jurusans.pengelola_nip','jurusans.alamat','jurusans.telp','jurusans.fax','jurusans.hp','jurusans.email','jurusans.website','jurusans.kota')
        ->first();
          $d['title']='Persetujuan Pembimbing';
          $d['perihal']='Persetujuan Pembimbing';
          $d['detail']=$detail;
          $d['group_details']=DB::table('group_details')
          ->join('mahasiswas','mahasiswas.id','=','group_details.mahasiswa_id')
          ->join('prodis','prodis.id','=','mahasiswas.prodi_id')
          ->where('group_details.group_id',$detail->group_id)
          ->select('group_details.id','mahasiswas.nama','mahasiswas.nim','mahasiswas.kelas','prodis.prodi')->get();
          
          $pdf = Pdf::setPaper('A4', 'portrait')
          ->loadview('a.pages.page_static.persetujuan_pembimbing',$d);
          return $pdf->stream();

     }




}
