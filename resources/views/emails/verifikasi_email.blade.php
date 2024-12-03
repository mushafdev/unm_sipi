<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verifikasi Email</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9fc;
      margin: 0;
      padding: 0;
    }

    .email-container {
      max-width: 600px;
      margin: 40px auto;
      background: #ffffff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .header {
      background-color: #435ebe;
      color: #ffffff;
      text-align: center;
      padding: 30px 20px;
    }

    .header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: bold;
      line-height: 1.5;
    }

    .header img {
      width: 80px;
      margin: 10px 0;
    }

    .content {
      padding: 25px 20px;
      text-align: center;
    }

    .content h2 {
      font-size: 24px;
      color: #333333;
      margin-bottom: 15px;
    }

    .content p {
      font-size: 16px;
      color: #555555;
      line-height: 1.6;
      margin: 15px 0;
    }

    .verify-button {
      display: inline-block;
      background-color: #435ebe;
      color: #ffffff;
      text-decoration: none;
      padding: 15px 30px;
      font-size: 16px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .verify-button:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
    }

    .footer {
      background-color: #f4f4f9;
      text-align: center;
      padding: 15px;
      font-size: 14px;
      color: #777777;
    }

    .footer a {
      color: #435ebe;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      {{-- <img src="{{public_path('app/assets/static/images/logo/unm_logo.png')}}" alt="Logo"> --}}
      <h1>Selamat Datang di SiPi App</h1>
    </div>
    <div class="content">
      <h2>Halo, {{ $data['nama'] }}!</h2>
      <p>Kami senang Anda bergabung dengan kami. Silakan verifikasi alamat email Anda untuk mulai menggunakan layanan kami.</p>
      <a href="{{$data['link_verifikasi']}}" class="verify-button" style="color:#FFF;">Verifikasi Email Sekarang</a>
      <p>Jika Anda merasa tidak meminta email ini, Anda dapat mengabaikannya.</p>
    </div>
    <div class="footer">
      <p>&copy; 2024 {{identity()['support']}} {{identity()['university']}}. <a href="#">Kebijakan Privasi</a></p>
    </div>
  </div>
</body>
</html>
