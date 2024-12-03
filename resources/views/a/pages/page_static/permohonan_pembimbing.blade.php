<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        @page {
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
        <table class="w-100">
            <tr>
                <td width="60%">
                    <table class="w-100">
                        <tr>
                            <td width="30%">No</td><td width="5%">:</td><td width="65%">XXX</td>
                        </tr>
                        <tr>
                            <td>Perihal</td><td>:</td><td>{{$perihal}}</td>
                        </tr>
                        <tr>
                            <td>Lampiran</td><td>:</td><td>-</td>
                        </tr>
                    </table>
                </td>
                <td width="40%" class="align-top text-right">
                    {{$detail->kota}}, {{tgl_indo(date('Y-m-d'))}} 
                </td>
            </tr>
        </table>


        <div class="content">
            <p class="no-margin" style="margin-left:-15mm">Kepada Yth</p>
            <p class="no-margin">
                <b>Kasubag Akademik FT UNM</b><br>
            di-<br>
            &nbsp;&nbsp;&nbsp;&nbsp;{{$detail->kota}}
            </p>
            <p>Dengan Hormat,<br>
            Sehubung dengan rencana pelaksanaan Praktik Industri dari Mahasiswa Program Studi {{$detail->prodi}} Jurusan {{$detail->jurusan}} Fakultas {{$detail->fakultas}} {{identity()['university']}}, yang akan dilakukan dari tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{getMonthFromNumber($detail->start_month)}} -  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    {{getMonthFromNumber($detail->end_month)}} {{$detail->year}}, bahwa mahasiswa tersebut di bawah ini :    </p>
            <table style="width: 100%">
                <tbody>
                    <tr>
                        <td width="20%" rowspan="{{$group_details->count()+1}}" class="align-top text-right">
                            Nama/NIM:
                        </td>
                        <td width="5%" rowspan="{{$group_details->count()+1}}" class="align-top">
    
                        </td>
                    </tr>
                    @foreach ($group_details as $dt )
                    <tr>
                        <td class="align-top">{{$loop->iteration}}. {{Str::upper($dt->nama)}}</td>
                        <td class="align-top text-right">({{$dt->nim}})</td>
                    </tr>    
                    @endforeach
                </tbody>
            </table>
            <p>Dalam rangka memenuhi tugas mata kuliah Praktik Industri dari mahasiswa tersebut di atas, kami memohon agar kiranya bapak/ibu dapat menerbitkan SK Penetapan Pembimbing PI dari mahasiswa tersebut di atas dengan pembimbing dan lokasi sebagai berikut</p>
            <table class="table" style="margin-left:auto;margin-right:auto;width:100%;">
                <thead>
                    <tr>
                        <th class="text-center">Nama Pembimbing</th>
                        <th class="text-center">Lokasi Praktik Industri</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">{{$detail->pembimbing}}</td>
                        <td class="text-center">{{$detail->lokasi_pi}}</td>
                    </tr>    
                </tbody>
            </table>
            <p>Demikian, atas kerjasamanya kami ucapkan terima kasih,</p>
            <table class="w-100">
                <tr>
                    <td>
                        <div class="signature">
                            <p>Mengetahui,<br>
                            Ketua Prodi<br>
                            {{$detail->prodi}}</p>
                            <p style="margin-top: 80px;"><b>{{$detail->kaprodi_nama}}</b><br>
                            NIP. {{$detail->kaprodi_nip}}</p>
                        </div>
                    </td>
                    <td></td>
                    <td>
                        <div class="signature">
                            <p>Menyetujui,<br>
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
