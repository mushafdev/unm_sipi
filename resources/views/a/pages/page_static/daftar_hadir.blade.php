<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$title}}</title>
    <style>
        @page {
            margin: 10px 20px 0px 20px;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8pt;
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
        <h4 class="text-center" style="margin: 5px;font-size: 11pt;">DAFTAR HADIR MAHASISWA PRAKTIK INDUSTRI</h4>
        @for ($b=$detail->start_month;$b<=$detail->end_month;$b++)
        @php
            $jumlahHari = cal_days_in_month(CAL_GREGORIAN, $b, $detail->year);
        @endphp
        <br>
        <table style="width: 100%;" border="1">
            <thead>
                <tr>
                    <th class="text-center" width="3%" rowspan="2">No</th>
                    <th width="20%" rowspan="2">Nama</th>
                    <th class="text-center" colspan="{{$jumlahHari}}">{{getMonthFromNumber($b)}}</th>
                </tr>
                <tr>
                    @for ($i = 1; $i <= $jumlahHari; $i++) 
                    @php
                    $tanggal = sprintf('%04d-%02d-%02d', $detail->year, $b, $i);
                    $hari = date('l', strtotime($tanggal));
                    $color[$b][$i]='';
                    if ($hari == 'Sunday') {
                        $color[$b][$i]='grey';
                    } 
                    @endphp
                        <th class="text-center" style="background-color:{{$color[$b][$i]}}">{{$i}}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach ($group_details as $dt )
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>{{Str::upper($dt->nama)}} ({{$dt->nim}})</td>
                        @for ($i = 1; $i <= $jumlahHari; $i++) 
                            <td class="text-center" style="background-color:{{$color[$b][$i]}}"></td>
                        @endfor
                    </tr>    
                @endforeach
            </tbody>
        </table>
            
        @endfor
    </main>
    <footer style="margin-top:10px;">
        <table class="w-100" style="font-size: 11pt">
            <tr>
                <td width="60%"></td>
                <td width="40%">
                    <div class="signature">
                        <p>{{$detail->kota}}, {{tgl_indo(date('Y-m-d'))}} <br>
                        Mengetahui,<br>
                        Pembimbing Lapangan</p>
                        <p style="margin-top: 70px;"><b>NIP. </b></p>
                    </div>
                </td>
            </tr>
        </table>
    </footer>
</body>
</html>
