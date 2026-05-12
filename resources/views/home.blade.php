<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Simulator — NakamotoX</title>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #000;
            --text: #00ff00;
            --muted: #008800;
            --accent: #00ff00;
            --border: #00ff00;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Courier New', monospace;
            padding: 20px;
            text-shadow: 0 0 2px rgba(0,255,0,0.5);
        }

        header {
            border-bottom: 1px dashed var(--border);
            padding-bottom: 10px;
            margin-bottom: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .menu {
            display: grid;
            gap: 20px;
        }

        .card {
            border: 1px solid var(--border);
            padding: 20px;
            cursor: pointer;
            transition: 0.2s;
        }

        .card:hover {
            background: var(--accent);
            color: black;
            text-shadow: none;
        }

        .title {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .desc {
            font-size: 12px;
            color: var(--muted);
        }

        .card:hover .desc {
            color: black;
        }

        .prompt {
            color: var(--accent);
            font-weight: bold;
        }

        .cursor {
            animation: blink 1s infinite;
        }

        @keyframes blink {
            50% { opacity: 0; }
        }
    </style>
</head>
<body>

<header>
    <span class="prompt">root@nakamotox</span>:~$ ./crypto_simulator.sh <span class="cursor">█</span>
</header>

<div class="container">

    <h2 style="margin-bottom: 20px;">[ SELECT MODULE ]</h2>

    <div class="menu">

        <!-- Caesar -->
        <div class="card" onclick="location.href='/caesar'">
            <div class="title">> Caesar Cipher</div>
            <div class="desc">
                Classical substitution cipher using shift (mod 26).
            </div>
        </div>

        <!-- ChaCha20 -->
        <div class="card" onclick="location.href='/chacha20'">
            <div class="title">> ChaCha20 Stream Cipher</div>
            <div class="desc">
                Modern stream cipher (RFC 8439) with full visualization.
            </div>
        </div>

    </div>

</div>

</body>
</html>