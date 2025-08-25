<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }
        h2 {
            color: #005A8D;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📢 {{ $judul }}</h2>
        <p><strong>Tanggal:</strong> {{ $tanggal }}</p>

        <p>{!! nl2br(e($isi)) !!}</p>

        <div class="footer">
            TBM Mardeka Membaca<br>
            Email ini dikirim otomatis — jangan dibalas.
        </div>
    </div>
</body>
</html>
