<?php

namespace App\Http\Controllers\u;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\Peserta;
use App\Models\UjianSoal;
use App\Models\UjianPeserta;
use App\Models\UjianPesertaJawaban;
use App\Models\LevelKompetensi;
use App\Enums\HotsEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Inertia\Inertia;

class UjianController extends Controller
{
   

    public function index(Request $request)
    {
        $userId =  $request->session()->get('id'); 
        $ujianId =  $request->session()->get('ujian_id'); 
        $soals = DB::table('ujian_soals')
        ->join('bank_soal_lists','bank_soal_lists.id','=','ujian_soals.bank_soal_list_id')
        ->leftJoin('level_kompetensis','level_kompetensis.id','=','bank_soal_lists.level_kompetensi_id')
        ->select('ujian_soals.id','bank_soal_lists.soal_teks','bank_soal_lists.level_kompetensi_id','bank_soal_lists.hots','bank_soal_lists.pilihan','level_kompetensis.level_kompetensi')
        ->where('ujian_soals.ujian_id', $ujianId)->orderBy('bank_soal_lists.level_kompetensi_id','asc')->get();
        
        $level_kompetensis=LevelKompetensi::select('id','level_kompetensi')->get();
        $ujian = Ujian::find($ujianId);
        $ujianPeserta=UjianPeserta::where('ujian_id', $request->session()->get('ujian_id'))
        ->where('user_id', $userId)->first();
        $jawabans = DB::table('ujian_peserta_jawabans')
        ->where('ujian_peserta_id', $ujianPeserta->id)
        ->pluck('jawaban', 'ujian_soal_id'); 
        $start_time = Carbon::parse($ujianPeserta->start_time);

        return Inertia::render('Ujian/Index', [
            'soals' => $soals->map(function ($q) use ($jawabans) {
                $q->pilihan = json_decode($q->pilihan, true);
                $q->jawaban = $jawabans[$q->id] ?? null;
                return $q;
            }),
            'level_kompetensis' => $level_kompetensis, 
            'ujian' => $ujian, 
            'ujian_peserta_id' => encrypt0($ujianPeserta->id), 
            'start_time' => $start_time, 
            'durasi' => $ujian->durasi*60, 
            'nama_user' => $request->session()->get('detailUser')->nama,
            'role' => $request->session()->get('role')
        ]);
     
       
    }

    public function pra_ujian(Request $request)
    {

        $userId =  $request->session()->get('id'); 
        $ujianId=session('ujian_id');
        $d['title']='Ujian';
        $ujian=Ujian::join('mapels','mapels.id','=','ujians.mapel_id')
        ->select('ujians.*','mapels.mapel')->find($ujianId);
        $d['ujian']=$ujian;
        $d['now']=Carbon::now();
        $peserta=UjianPeserta::where('user_id',$userId)->where('ujian_id',$ujianId);
        if($peserta->count()>0){
            if($peserta->first()->status=='done'){
                return redirect()->route('ujian.hasil',encrypt0($peserta->first()->id));
            }
        }
        return view('u.pages.ujian.pra-ujian',$d);
    }
    
    public function hasil($id)
    {
        $id=decrypt0($id);
        $d['title']='Hasil Ujian';
        $ujian=UjianPeserta::join('ujians','ujians.id','=','ujian_pesertas.ujian_id')
        ->join('mapels','mapels.id','=','ujians.mapel_id')
        ->select('ujian_pesertas.*','ujians.satuan_pendidikan','ujians.durasi','ujians.judul','ujians.start_time','ujians.end_time','mapels.mapel')->find($id);
        $peserta=Peserta::where('user_id',$ujian->user_id)->first();
        $d['peserta']=$peserta;
        $d['ujian']=$ujian;
        $d['now']=Carbon::now();
        return view('u.pages.ujian.hasil',$d);
    }


    public function get_hasil(Request $request)
    {
        $skor=0;
        $ujianPesertaId = decrypt0($request->id);
        $ujianPeserta=UjianPeserta::where('id',$ujianPesertaId)->first();
        $totalSoal = UjianSoal::where('ujian_id', $ujianPeserta->ujian_id)->count();
        $jawabanBenar=DB::table('ujian_peserta_jawabans')
        ->where('ujian_peserta_id', $ujianPesertaId)
        ->where('is_correct', 'Y')
        ->count();
        $skor = round(($jawabanBenar / $totalSoal) * 100, 1);

        $progressPerLevelKompetensi = DB::table('level_kompetensis')->select('id','level_kompetensi')->get();
        $progressPerLevelKompetensi->map(function ($row) use ($ujianPeserta) {
            $rekomendasi='';
            $totalSoal = DB::table('ujian_soals')
                ->join('bank_soal_lists', 'bank_soal_lists.id', '=', 'ujian_soals.bank_soal_list_id')
                ->where('ujian_soals.ujian_id', $ujianPeserta->ujian_id)
                ->where('bank_soal_lists.level_kompetensi_id', $row->id)
                ->count();

            $jawabanBenar = DB::table('ujian_peserta_jawabans')
                ->join('ujian_soals', 'ujian_soals.id', '=', 'ujian_peserta_jawabans.ujian_soal_id')
                ->join('bank_soal_lists', 'bank_soal_lists.id', '=', 'ujian_soals.bank_soal_list_id')
                ->where('ujian_peserta_jawabans.ujian_peserta_id', $ujianPeserta->id)
                ->where('bank_soal_lists.level_kompetensi_id', $row->id)
                ->where('ujian_peserta_jawabans.is_correct', 'Y')
                ->count();

            $row->skor = round(($jawabanBenar / $totalSoal) * 100, 2);
            // $fakeValue=[30,90, 70,80,100,50];
            // $row->skor = $fakeValue[array_rand($fakeValue)];
            $row->jawaban_benar = $jawabanBenar;
            $row->total_soal = $totalSoal;
            if($row->skor<=60){
                $rekomendasi='Perbanyak latihan';
            }
            $row->rekomendasi=$rekomendasi;
            return $row;
        });

        $hots = HotsEnum::cases();
        $hots = collect($hots)->map(function ($row) use ($ujianPeserta) {
            $totalSoal = DB::table('ujian_soals')
                ->join('bank_soal_lists', 'bank_soal_lists.id', '=', 'ujian_soals.bank_soal_list_id')
                ->where('ujian_soals.ujian_id', $ujianPeserta->ujian_id)
                ->where('bank_soal_lists.hots', $row->value)
                ->count();

            $jawabanBenar = DB::table('ujian_peserta_jawabans')
                ->join('ujian_soals', 'ujian_soals.id', '=', 'ujian_peserta_jawabans.ujian_soal_id')
                ->join('bank_soal_lists', 'bank_soal_lists.id', '=', 'ujian_soals.bank_soal_list_id')
                ->where('ujian_peserta_jawabans.ujian_peserta_id', $ujianPeserta->id)
                ->where('bank_soal_lists.hots', $row->value)
                ->where('ujian_peserta_jawabans.is_correct', 'Y')
                ->count();

            return [
                'value' => $row->value,
                'label' => $row->label(),
                'jawaban_benar' => $jawabanBenar,
                'skor' => $totalSoal > 0 ? round(($jawabanBenar / $totalSoal) * 100, 2) : 0,
                'total_soal' => $totalSoal,
            ];
        });

        return response()->json([
            'skor'=>$skor,
            'jawaban_benar'=>$jawabanBenar,
            'total_soal'=>$totalSoal,
            'progressPerLevelKompetensi' => $progressPerLevelKompetensi,
            'progressPerHots' => $hots,
        ]);
    }

   

    public function mulai_ujian(Request $request)
    {
        try {
            $userId =  $request->session()->get('id'); 
            $ujianId =  $request->session()->get('ujian_id'); 
            $cekUjian = Ujian::where('id', $ujianId)
                ->where('status', 'published')
                ->where('start_time', '<=', Carbon::now())
                ->where('end_time', '>=', Carbon::now())
                ->first();

            if (!$cekUjian) { // Ubah menjadi !$cekUjian
                return back()->withErrors([
                    'error_message' => 'Ujian tidak ditemukan atau sudah tidak tersedia.',
                ]);
            }
            $ujianPesertaValid=TRUE;

            $cekUjianPeserta=UjianPeserta::where('ujian_id',$ujianId)->where('user_id',$userId);
            if($cekUjianPeserta->count()==0){
                $ujianPeserta=new UjianPeserta;
                $ujianPeserta->ujian_id=$ujianId;
                $ujianPeserta->user_id=$userId;
                $ujianPeserta->status='start';
                $ujianPeserta->start_time=Carbon::now();
                $ujianPeserta->save();
                if($ujianPeserta){
                    $ujianPesertaValid=TRUE;
                }else{
                    $ujianPesertaValid=FALSE;
                }
            }
            
            if($ujianPesertaValid){
                  return redirect()->intended('ujian');
            }else{
                return back()->withErrors([
                    'error_message' => 'Terjadi Kesalahan. Silahkan diulangi',
                ]);
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memulai ujian.');
        }
    }

    public function simpan_jawaban(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'ujian_soal_id' => 'required|integer',
                'jawaban' => 'required',
            ]);
        
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $ujianPesertaId =  decrypt0($request->ujian_peserta_id); 
            $cekJawaban = DB::table('ujian_soals')->join('bank_soal_lists','bank_soal_lists.id','=','ujian_soals.bank_soal_list_id')
            ->where('ujian_soals.id', $request->ujian_soal_id)->select('bank_soal_lists.jawaban')->first();
            if ($cekJawaban) {
                $jawabanBenar = $cekJawaban->jawaban;
                $isCorrect = ($request->jawaban == $jawabanBenar) ? 'Y' : 'N';
            } else {
                $isCorrect = 'N'; 
            }

            DB::table('ujian_peserta_jawabans')->updateOrInsert(
                ['ujian_peserta_id' => $ujianPesertaId, 'ujian_soal_id' => $request->ujian_soal_id],
                ['jawaban' => $request->jawaban, 'is_correct'=>$isCorrect, 'updated_at' => now()]
            );
            
            return redirect()->back()->with('toast', [
                'type' => 'success',
                'message' => 'Data berhasil disimpan!',
            ]);
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban.');
        }
    }
    
    public function selesai(Request $request)
    {
        try {

            $userId =  $request->session()->get('id'); // atau ambil dari session jika belum login
            $ujianId =  $request->session()->get('ujian_id'); // atau ambil dari session jika belum login

            // Cek apakah sudah ada jawaban sebelumnya
            $ujian=UjianPeserta::where('ujian_id', $ujianId)
                ->where('user_id', $userId)
                ->update([
                    'status' => 'done',
                    'done_time' => Carbon::now()
                ]);
            if (!$ujian) {
                return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan status ujian.');
            }

            return redirect()->back()->with('success', 'Ujian berhasil diselesaikan.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jawaban.');
        }
    }

    

}
