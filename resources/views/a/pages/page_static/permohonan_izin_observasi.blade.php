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
            font-size: 12pt;
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
            margin-top: 0px;
            margin-left: 15mm;
            margin-right: 15mm;
        }
        .content p {
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
                <td width="50%">
                    <table class="w-100">
                        <tr>
                            <td width="30%">Nomor</td><td width="5%">:</td><td width="65%">XXX</td>
                        </tr>
                        <tr>
                            <td>Lampiran</td><td>:</td><td>-</td>
                        </tr>
                        <tr>
                            <td>Perihal</td><td>:</td><td>{{$perihal}}</td>
                        </tr>
                    </table>
                </td>
                <td width="50%" class="align-top text-right">
                    {{$detail->kota}}, {{tgl_indo(date('Y-m-d'))}} 
                </td>
            </tr>
        </table>


        <div class="content">
            <p class="no-margin" style="margin-left:-15mm">Kepada Yth</p>
            <p class="no-margin">
            Bapak/Ibu Pimpinan<br>
            {{$detail->lokasi_pi}}<br>
            di-<br>
            &nbsp;&nbsp;&nbsp;&nbsp;{{$detail->lokasi_pi_kota}}
            </p>
            <p>Dengan Hormat,</p>
            <p>Sehubung dengan rencana pelaksanaan praktik industri dari mahasiswa Program Studi {{$detail->prodi}} Jurusan {{$detail->jurusan}} Fakultas {{$detail->fakultas}} {{identity()['university']}}, mahasiswa tersebut di bawah ini:</p>
            <table class="table" style="margin-left:auto;margin-right:auto;">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama</th>
                        <th class="text-center">NIM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($group_details as $dt )
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>{{$dt->nama}}</td>
                        <td>{{$dt->nim}}</td>
                    </tr>    
                    @endforeach
                </tbody>
            </table>
            <p>Bermaksud akan mengadakan observasi di instansi/perusahaan yang Bapak/Ibu pimpin untuk melaksanakan praktik industri yang akan dilaksanakan pada Bulan {{getMonthFromNumber($detail->start_month)}} - {{getMonthFromNumber($detail->end_month)}} {{$detail->year}}.</p>
            <p>Berkenaan dengan hal tersebut, kami mohon kepada Bapak/Ibu agar kiranya dapat memberikan izin observasi kepada mahasiswa yang bersangkutan.</p>
            <p>Demikian permohonan ini kami sampaikan, atas perkenaan Bapak/Ibu, kami ucapkan terima kasih.</p>
        </div>
    </main>
    <footer>
        <table class="w-100">
            <tr>
                <td width="40%"></td>
                <td width="60%">
                    <div class="signature">
                        <p>Mengetahui,<br>
                        Ketua Jurusan<br>
                        {{$detail->jurusan}}</p>
                        <p style="margin-top: 80px;"><b>{{$detail->kajur_nama}}</b><br>
                        NIP. {{$detail->kajur_nip}}</p>
                    </div>
                </td>
            </tr>
        </table>
    </footer>
</body>
</html>
