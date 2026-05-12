<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Caesar Cipher — NakamotoX</title>

<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
:root {
    --bg:#000;
    --accent:#00ff00;
    --muted:#008800;
    --error:#ff003c;
}

body {
    background: var(--bg);
    color: var(--accent);
    font-family: 'Courier New', monospace;
    padding:20px;
}

.container {
    max-width: 1000px;
    margin:auto;
}

.header {
    margin-bottom:20px;
    border-bottom:1px dashed var(--accent);
    padding-bottom:10px;
}

.card {
    border:1px solid var(--accent);
    padding:15px;
    margin-bottom:20px;
}

.title {
    font-size:13px;
    margin-bottom:10px;
    color:var(--accent);
}

input, textarea {
    width:100%;
    background:transparent;
    border:1px solid var(--muted);
    color:var(--accent);
    padding:8px;
}

button {
    width:100%;
    padding:10px;
    border:1px solid var(--accent);
    background:transparent;
    color:var(--accent);
    cursor:pointer;
}

button:hover {
    background:var(--accent);
    color:#000;
}

.tab {
    cursor:pointer;
    margin-right:15px;
    color:var(--muted);
}

.tab.active {
    color:var(--accent);
    font-weight:bold;
}

.result {
    border:1px dashed var(--muted);
    padding:10px;
    min-height:50px;
}

.error {
    color:var(--error);
}

.grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}
</style>
</head>

<body x-data="caesarApp()">

<div class="container">

<!-- HEADER -->
<div class="header">
    <div>root@nakamotox:~/caesar$ ./run_cipher.sh</div>
    <div style="font-size:12px; color:var(--muted)">
        Caesar Cipher Engine — Substitution Cipher (mod 26)
    </div>
</div>

<!-- MODE -->
<div style="margin-bottom:20px;">
    <span class="tab" :class="{active: mode==='encrypt'}" @click="mode='encrypt'">[ ENCRYPT ]</span>
    <span class="tab" :class="{active: mode==='decrypt'}" @click="mode='decrypt'">[ DECRYPT ]</span>
</div>

<div class="grid">

<!-- INPUT -->
<div>

    <div class="card">
        <div class="title">INPUT DATA</div>

        <template x-if="mode==='encrypt'">
            <div>
                <label>> PLAINTEXT</label>
                <textarea x-model="plaintext" placeholder="Enter plaintext..."></textarea>
            </div>
        </template>

        <template x-if="mode==='decrypt'">
            <div>
                <label>> CIPHERTEXT</label>
                <textarea x-model="ciphertext" placeholder="Enter ciphertext..."></textarea>
            </div>
        </template>
    </div>

    <div class="card">
        <div class="title">PARAMETER</div>

        <label>> SHIFT (1 - 25)</label>
        <input type="number" x-model="shift" min="1" max="25">
    </div>

    <button @click="run()">> EXECUTE</button>

</div>

<!-- OUTPUT -->
<div>

    <!-- ERROR -->
    <div class="card" x-show="error">
        <div class="title" style="color:var(--error)">ERROR</div>
        <div class="error" x-text="error"></div>
    </div>

    <!-- RESULT -->
    <div class="card" x-show="result">
        <div class="title">OUTPUT</div>

        <template x-if="mode==='encrypt'">
            <div>
                <label>CIPHERTEXT</label>
                <div class="result" x-text="result.ciphertext"></div>
            </div>
        </template>

        <template x-if="mode==='decrypt'">
            <div>
                <label>PLAINTEXT</label>
                <div class="result" x-text="result.plaintext"></div>
            </div>
        </template>

        <div style="margin-top:10px; font-size:12px; color:var(--muted)">
            SHIFT USED: <span x-text="shift"></span>
        </div>
    </div>

</div>

</div>
</div>

<script>
function caesarApp() {
    return {
        mode:'encrypt',
        plaintext:'',
        ciphertext:'',
        shift:3,
        result:null,
        error:null,

        async run() {
            this.error = null;
            this.result = null;

            try {
                const url = this.mode==='encrypt'
                    ? '{{ route("caesar.encrypt") }}'
                    : '{{ route("caesar.decrypt") }}';

                const payload = this.mode==='encrypt'
                    ? { plaintext:this.plaintext, shift:parseInt(this.shift) }
                    : { ciphertext:this.ciphertext, shift:parseInt(this.shift) };

                const res = await fetch(url,{
                    method:'POST',
                    headers:{
                        'Content-Type':'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body:JSON.stringify(payload)
                });

                const data = await res.json();

                if(!res.ok){
                    throw new Error(data.error || 'API Error');
                }

                this.result = data;

            } catch(e){
                this.error = e.message;
            }
        }
    }
}
</script>

</body>
</html>