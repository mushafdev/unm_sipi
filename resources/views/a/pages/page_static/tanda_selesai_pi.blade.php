<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        @page {
            margin: 10mm 20mm 10mm 20mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
        }
        header {
            display: flex;
            text-align: center;
            margin-bottom: 10px;
        }
        .hr-1{
            border-top: 1px solid;
            border-bottom: 3px solid;
            border-right: none;
            border-left: none;
            height: 2px;
        }
        header h1 {
            font-size: 12pt;
            margin: 0;
            line-height: 20px;
            font-weight: normal;
        }
        header h2 {
            font-size: 14pt;
            margin: 0;
        }
        .contact-info {
            text-align: center;
            font-size: 9.5pt;
            margin-top: 0px;
            margin-bottom: 0px;
        }
        .content {
            font-size: 11pt;
            margin-top: 15px;
            margin-left: 15mm;
            margin-right: 15mm;
        }
        .content p {
            font-size: 11pt;
            text-align: justify;
        }
        table{
            margin: 0px;    
            border-collapse: collapse;
        }
        .table {
            width: 70%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-top: 15px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 2px;
            padding-left: 5px;
            padding-right: 5px;
            text-align: left;
        }
        footer {
            margin-top: -20px;
        }
        .signature {
            text-align: left;
            margin-top: 0px;
        }

        .text-center{
            text-align: center !important;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .align-top{
            vertical-align: top;
        }
        .w-100{
            width: 100%;
        }
        .no-margin{
            margin: 0px;
        }
    </style>
</head>
<body>
    <header>
        <table class="w-100">
            <tr>
                <td>
                    <img width="100px" src="{{public_path('app/assets/static/images/logo/unm_logo.png')}}" alt="Logo Universitas">
                </td>
                <td class="text-center">
                    <h1>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN<br>
                        {{Str::upper(identity()['university'])}}<br>
                        <b>JURUSAN {{Str::upper($detail->jurusan)}}</b><br></h1>
                    
                    <p class="contact-info">
                        Alamat: {{$detail->alamat}}<br>
                        Telp. {{$detail->telp}}, Fax. {{$detail->fax}}, Hp. {{$detail->hp}}, Email: {{$detail->email}} | Laman: {{$detail->website}}
                    </p>
                </td>
            </tr>
        </table>
        <hr class="hr-1">
    </header>
    <main>
        <h4 class="text-center" style="margin: 5px;font-size: 11pt;">SURAT TANDA BUKTI SELESAI PRAKTIK INDUSTRI</h4>

        <div class="content">
            <p>Yang bertanda tangan dibawah ini telah mengadakan pemeriksaan dengan teliti naskah
                laporan praktik industri pada {{$detail->lokasi_pi}}, menerangkan bahwa :</p>
            <div class="text-center">
                <table style="width: 70%;margin: 0 auto;">
                    <tbody>
                        <tr>
                            <td width="30%" rowspan="" class="align-top text-left">
                                Nama/NIM
                            </td>
                            <td width="3%" class="align-top">:</td>
                            <td  rowspan="" class="align-top">
                                <table style="width: 100%">
                                    @foreach ($group_details as $dt )
                                    <tr>
                                        <td class="align-top">{{$loop->iteration}}. {{Str::upper($dt->nama)}}</td>
                                        <td class="align-top text-right">({{$dt->nim}})</td>
                                    </tr>    
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td  class="align-top text-left">
                                Jurusan/Prodi 
                            </td>
                            <td width="3%" class="align-top">:</td>
                            <td class="align-top" colspan="2">
                                {{$detail->jurusan}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p>Telah melaksanakan praktik industri pada perusahaan tersebut diatas selama 3 (tiga) bulan, terhitung
                sejak {{getMonthFromNumber($detail->start_month)}} s/d {{getMonthFromNumber($detail->end_month)}} dan menyetujui laporan praktik industri tersebut.</p>
            
            <table class="w-100">
                <tr>
                    <td width="60%">
                        
                    </td>
                    <td width="40%">
                        <div class="signature">
                            <p>{{$detail->kota}}, {{tgl_indo(date('Y-m-d'))}}<br>Menyetujui,<br>
                            Dosen Pembimbing</p>
                            <p style="margin-top: 95px;"><b>{{$detail->pembimbing}}</b><br>
                            NIP. {{$detail->pembimbing_nip}}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </main>
</body>
</html>
