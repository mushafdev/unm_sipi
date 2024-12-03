<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{asset('app/assets/compiled/svg/favicon.svg')}}" type="image/x-icon">
    <link rel="shortcut icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC" type="image/png">
   
    <title>{{$title}}</title>
    <style>
        @page { 
            margin-top: 50px;
            margin-bottom: 0px; 
            margin-left: 0px;
            margin-right: 0px;
            
        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FFF;
            color: #333;
        }
        .text-center{
            text-align: center;
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
        .font-bold{
            font-weight: bold;
        }
        .header {
            background-color: #737979;
            color: white;
            padding: 30px;
            text-align: center;
            margin-top: -50px;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
        }
        .header p {
            font-size: 1.2em;
            margin-top: 5px;
        }
        .container {
            padding: 20px;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .card h2 {
            border-left: 5px solid #737979;
            padding-left: 10px;
            color: #737979;
            margin-top: 0;
        }
        table{
            margin: 20px 0; 
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0; 
        }
        .table th {
            background-color: #737979;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 1em;
        }
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

         .table-container {
            page-break-after: always;
            margin-bottom: 40px; /* Tambahkan margin setelah tabel */
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #f1f1f1;
            color: #555;
            font-size: 0.9em;
            text-align: center;
            padding: 10px;
            border-top: 2px solid #ddd;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 0.9em;
            border-radius: 5px;
            color: white;
        }
        .badge.success {
            background-color: #28a745;
        }
        .badge.warning {
            background-color: #ffc107;
        }
        .badge.danger {
            background-color: #dc3545;
        }
        .w-100{
            width: 100%;
        }
    </style>
   
</head>
<body>
    <div class="header">
        <h1>Laporan Kegiatan</h1>
        <p>{{$detail->lokasi_pi}}</p>
    </div>
    <div class="container">
        <div class="card">
            <h2>Info Mahasiswa</h2>
            <table class="w-100">
                <tr>
                    <td width="20%">Nama</td>
                    <td width="5%">:</td>
                    <td width="75%" class="font-bold">{{$detail->nama}}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>:</td>
                    <td class="font-bold">{{$detail->nim}}</td>
                </tr>
                <tr>
                    <td>Prodi</td>
                    <td>:</td>
                    <td class="font-bold">{{$detail->prodi}}</td>
                </tr>
            </table>
        </div>
        <div class="card">
            <p>Adapun kegiatan yang telah dilaksanakan di kantor {{$detail->lokasi_pi}} selama proses praktik industri
                sebagai berikut :<p>
            <h2>Kegiatan</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logbooks as $dt )
                    <tr>
                        <td class="align-top text-center">{{$loop->iteration}}</td>
                        <td class="align-top">{{convertYmdToMdy2($dt->tanggal)}}</td>
                        <td class="align-top">{{$dt->jam}}</td>
                        <td>{{$dt->kegiatan}}</td>
                    </tr>  
                    @endforeach
                    
                </tbody>
            </table>
            <p>Demikianlah laporan kegiatan yang dilakukan selama praktik industri di {{$detail->lokasi_pi}}, dibuat dengan
                sebenar-benarnya.</p>
        </div>

        <div class="card">
            <table class="w-100">
                <tr>
                    <td width="60%"></td>
                    <td width="40%">
                        Mengetahui,<br>
                        Pembimbing Lapangan
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        NIP.
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="footer">
        {{date('Y')}} &copy; {{identity()['title']}}. Supported by <strong>{{identity()['by']}}</strong>
    </div>
</body>
</html>
