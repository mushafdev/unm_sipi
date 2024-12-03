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
            font-size: 12pt;
            line-height: 1.3;
        }
        header {
            display: flex;
            text-align: center;
            margin-bottom: 0px;
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
        <h4 class="text-center" style="margin: 5px;font-size: 11pt;">LEMBAR PERSETUJUAN JADWAL<br>
            SEMINAR PRAKTEK INDUSTRI</h4>
            @php
            $nama_mahasiswa='';
            $nim_mahasiswa='';
            foreach ($group_details as $dt ){
                $nama_mahasiswa.=Str::upper($dt->nama).', ';
                $nim_mahasiswa.=$dt->nim.', ';
            }
            $nama_mahasiswa=rtrim($nama_mahasiswa, ', ');
            $nim_mahasiswa=rtrim($nim_mahasiswa, ', ');
            @endphp
            <table style="width: 100%">
                <tr>
                    <td width="25%">Nama Mahasiswa</td>
                    <td width="3%">:</td>
                    <td>{{$nama_mahasiswa}}</td>
                </tr>
                <tr>
                    <td width="25%">NIM</td>
                    <td width="3%">:</td>
                    <td>
                        {{$nim_mahasiswa}}
                    </td>
                </tr>
                <tr>
                    <td width="25%">Prodi</td>
                    <td width="3%">:</td>
                    <td>
                        {{$detail->prodi}}
                    </td>
                </tr>
                <tr>
                    <td width="25%">Jurusan</td>
                    <td width="3%">:</td>
                    <td>
                        {{$detail->jurusan}}
                    </td>
                </tr>
                <tr>
                    <td width="25%">Lokasi Praktik Industri</td>
                    <td width="3%">:</td>
                    <td>
                        {{$detail->lokasi_pi}}
                    </td>
                </tr>
            </table>

            <table class="table" style="margin-left:auto;margin-right:auto;width:100%;">
                <thead>
                    <tr>
                        <th class="text-center" rowspan="2">No</th>
                        <th class="text-center" rowspan="2" width="40%">Dosen Pembimbing/Penanggap</th>
                        <th class="text-center" colspan="4">Hari</th>
                        <th class="text-center" rowspan="2">Ket</th>
                    </tr>
                    <tr>
                        <th class="text-center">SN</th>
                        <th class="text-center">SL</th>
                        <th class="text-center">RB</th>
                        <th class="text-center">KM</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center" style="height: 150px;">1</td>
                        <td class="text-center"><b>{{$detail->pembimbing}}</b><br>(Pembimbing/Penanggap)</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table style="width: 100%">
                <tr>
                    <td width="25%">Rekomendasi</td>
                    <td width="3%">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td width="25%">Hari/Tanggal</td>
                    <td width="3%">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td width="25%">Pukul</td>
                    <td width="3%">:</td>
                    <td></td>
                </tr>
                <tr>
                    <td width="25%">Tempat</td>
                    <td width="3%">:</td>
                    <td></td>
                </tr>
            </table>
        
    </main>
    <footer style="margin-top:10px;">
        <table class="w-100" style="font-size: 11pt">
            <tr>
                <td width="60%"></td>
                <td width="40%">
                    <div class="signature">
                        <p>{{$detail->kota}}, {{tgl_indo(date('Y-m-d'))}} <br>
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
