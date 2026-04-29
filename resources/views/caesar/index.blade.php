{{-- 
    Caesar Cipher Simulator — NakamotoX
    
    TODO: Tim Caesar Cipher, kustomisasi halaman ini!
    Bisa lihat contoh di resources/views/chacha20/index.blade.php
    atau minta tim frontend untuk mendesainnya.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caesar Cipher Simulator — NakamotoX</title>
    <style>
        /* TODO: Tambahkan styling kalian di sini */
        body {
            background: #000;
            color: #00ff00;
            font-family: 'Courier New', monospace;
            padding: 40px;
            text-align: center;
        }
        h1 { margin-bottom: 20px; }
        p { color: #008800; }
    </style>
</head>
<body>
    <h1>🔤 Caesar Cipher Simulator</h1>
    <p>Halaman ini masih kosong — Tim Caesar Cipher, silakan kerjakan UI-nya di sini!</p>
    <p>API URL: <code>{{ $apiUrl }}</code></p>
    <br>
    <p>
        Lihat contoh UI di 
        <a href="/chacha20" style="color:#00ff00">/chacha20</a>
    </p>
</body>
</html>
