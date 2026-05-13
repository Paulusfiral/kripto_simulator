<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caesar Cipher Simulator | NakamotoX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            --bg-base: #0B0B0C;
            --primary: #E50914;
            --primary-hover: #F40612;
            --text-main: #FFFFFF;
            --text-muted: #B3B3B3;
            --border-glass: rgba(255, 255, 255, 0.1);
            --bg-glass: rgba(20, 20, 20, 0.45);
            --success: #10B981;
            --error: #EF4444;
            --radius: 12px;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        
        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 15px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        .bg-orbs { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: -1; overflow: hidden; }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.3; animation: float 20s infinite ease-in-out alternate; }
        .orb-1 { width: 500px; height: 500px; background: #E50914; top: -100px; left: -100px; }
        .orb-2 { width: 400px; height: 400px; background: #00aa00; bottom: 10%; right: -50px; animation-delay: -5s; }
        .orb-3 { width: 300px; height: 300px; background: #0044ff; top: 40%; left: 40%; animation-delay: -10s; opacity: 0.15; }
        
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(100px, 50px) scale(1.1); }
            100% { transform: translate(-50px, 100px) scale(0.9); }
        }

        .navbar { padding: 24px 4%; display: flex; justify-content: space-between; align-items: center; background: linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, transparent 100%); }
        .logo { font-size: 24px; font-weight: 800; letter-spacing: -1px; text-decoration: none; text-shadow: 0 0 10px rgba(255,255,255,0.3); }
        .logo span { color: var(--primary); }
        
        .hero { padding: 60px 4% 40px; text-align: center; }
        .hero h1 { font-size: 3.5rem; font-weight: 800; margin-bottom: 12px; }
        .hero p { font-size: 1.15rem; color: var(--text-muted); max-width: 600px; margin: 0 auto; font-weight: 300; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 4%; min-height: calc(100vh - 200px); }

        .pipeline { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 24px; margin: 40px 0; }
        @media (max-width: 1024px) { .pipeline { grid-template-columns: 1fr; } }

        .card { background: var(--bg-glass); border: 1px solid var(--border-glass); border-radius: var(--radius); padding: 24px; backdrop-filter: blur(10px); }
        .card-title { font-size: 13px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--primary); margin-bottom: 16px; }

        .mode-tabs { display: flex; gap: 8px; margin-bottom: 20px; border-bottom: 1px solid var(--border-glass); padding-bottom: 16px; }
        .mode-tab { padding: 8px 16px; background: transparent; border: none; color: var(--text-muted); font-size: 14px; font-weight: 600; cursor: pointer; border-bottom: 2px solid transparent; transition: all 0.2s; }
        .mode-tab.active { color: var(--primary); border-bottom-color: var(--primary); }

        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            border-radius: 8px;
            padding: 12px;
            color: var(--text-main);
            font-size: 14px;
            transition: all 0.2s;
        }

        input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
        }

        textarea { resize: vertical; min-height: 100px; font-family: 'Courier New', monospace; }

        .shift-slider { display: flex; align-items: center; gap: 12px; margin-top: 8px; }
        input[type="range"] { flex: 1; height: 6px; border-radius: 3px; background: rgba(255, 255, 255, 0.1); outline: none; -webkit-appearance: none; appearance: none; }
        input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 18px; height: 18px; border-radius: 50%; background: var(--primary); cursor: pointer; }
        .shift-value { background: var(--primary); color: #000; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 700; }

        button { padding: 12px 20px; background: var(--primary); color: #000; border: none; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; width: 100%; }
        button:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 8px 16px rgba(229, 9, 20, 0.3); }
        button:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .result-box { background: rgba(255, 255, 255, 0.02); border: 1px dashed var(--border-glass); border-radius: 8px; padding: 12px; margin-top: 12px; min-height: 60px; word-break: break-all; font-family: 'Courier New', monospace; font-size: 13px; color: var(--success); line-height: 1.6; }
        .result-box.empty { color: var(--text-muted); font-style: italic; }

        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-top: 8px; }
        .badge.error { background: rgba(239, 68, 68, 0.2); color: var(--error); border: 1px solid rgba(239, 68, 68, 0.3); }

        .footer { text-align: center; padding: 20px 4%; color: var(--text-muted); font-size: 13px; border-top: 1px solid var(--border-glass); margin-top: 60px; }
        .footer a { color: var(--primary); text-decoration: none; }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <nav class="navbar">
        <a href="/" class="logo">Nakamoto<span>X</span></a>
        <a href="/chacha20" style="color: var(--text-muted); text-decoration: none; font-size: 14px;">ChaCha20 →</a>
    </nav>

    <div class="hero">
        <h1>Caesar Cipher</h1>
        <p>Classic substitution cipher. Encrypt, decrypt, brute force, and visualize letter mappings.</p>
    </div>

    <div class="container" x-data="app()" x-init="loadHistory()">
        <div class="mode-tabs">
            <button class="mode-tab" :class="{active: mode === 'encrypt'}" @click="mode = 'encrypt'">🔐 Encrypt</button>
            <button class="mode-tab" :class="{active: mode === 'decrypt'}" @click="mode = 'decrypt'">🔓 Decrypt</button>
            <button class="mode-tab" :class="{active: mode === 'transform'}" @click="mode = 'transform'">🛠️ Transform</button>
            <button class="mode-tab" :class="{active: mode === 'brute'}" @click="mode = 'brute'">💥 Brute Force</button>
            <button class="mode-tab" :class="{active: mode === 'table'}" @click="mode = 'table'">📊 Shift Table</button>
            <button class="mode-tab" :class="{active: mode === 'spelling'}" @click="mode = 'spelling'">🔤 Spelling</button>
        </div>

        <div class="pipeline">
            <!-- INPUT -->
            <div class="card">
                <div class="card-title">INPUT</div>
                
                <div class="form-group">
                    <label x-text="mode === 'encrypt' ? 'Plaintext' : mode === 'decrypt' ? 'Ciphertext' : mode === 'spelling' ? 'Text to Spell' : 'Text to Transform'"></label>
                    <textarea x-model="input" placeholder="Enter text..."></textarea>
                </div>

                <div class="form-group" x-show="mode !== 'brute' && mode !== 'transform' && mode !== 'spelling'">
                    <label>Shift Value</label>
                    <div class="shift-slider">
                        <input type="range" x-model.number="shift" min="0" max="25" style="flex:1;">
                        <span class="shift-value" x-text="shift"></span>
                    </div>
                </div>

                <div class="form-group" x-show="mode === 'transform'">
                    <label>Transform Operation</label>
                    <select x-model="transformOperation" style="width:100%; padding:12px; background:rgba(255, 255, 255, 0.05); border:1px solid var(--border-glass); color:var(--text-main); border-radius:8px;">
                        <option value="replace">Replace Text</option>
                        <option value="reverse">Reverse Text</option>
                    </select>
                </div>

                <div class="form-group" x-show="mode === 'transform' && transformOperation === 'replace'">
                    <label>Search</label>
                    <input type="text" x-model="searchText" placeholder="Text to replace...">
                </div>

                <div class="form-group" x-show="mode === 'transform' && transformOperation === 'replace'">
                    <label>Replace With</label>
                    <input type="text" x-model="replaceText" placeholder="Replacement text...">
                </div>
            </div>

            <!-- PROCESS -->
            <div class="card">
                <div class="card-title">PROCESS</div>
                
                <button @click="encrypt()" x-show="mode === 'encrypt'" :disabled="!input || loading">
                    <span x-show="!loading">Encrypt</span>
                    <span x-show="loading">Processing...</span>
                </button>

                <button @click="decrypt()" x-show="mode === 'decrypt'" :disabled="!input || loading">
                    <span x-show="!loading">Decrypt</span>
                    <span x-show="loading">Processing...</span>
                </button>

                <button @click="transformText()" x-show="mode === 'transform'" :disabled="!input || loading || (transformOperation === 'replace' && !searchText)">
                    <span x-show="!loading">Transform</span>
                    <span x-show="loading">Processing...</span>
                </button>

                <button @click="bruteForce()" x-show="mode === 'brute'" :disabled="!input || loading">
                    <span x-show="!loading">Crack (26 shifts)</span>
                    <span x-show="loading">Cracking...</span>
                </button>

                <button @click="generateTable()" x-show="mode === 'table'" :disabled="loading">
                    <span x-show="!loading">Generate</span>
                    <span x-show="loading">Generating...</span>
                </button>

                <button @click="spellingAlphabet()" x-show="mode === 'spelling'" :disabled="!input || loading">
                    <span x-show="!loading">Spell Text</span>
                    <span x-show="loading">Processing...</span>
                </button>

                <div class="badge error" x-show="error" style="width:100%; display:block;">⚠️ <span x-text="error"></span></div>
            </div>

            <!-- OUTPUT -->
            <div class="card">
                <div class="card-title">OUTPUT</div>
                
                <div class="form-group" x-show="mode === 'encrypt' || mode === 'decrypt'">
                    <label x-text="mode === 'encrypt' ? 'Ciphertext' : 'Plaintext'"></label>
                    <div class="result-box" :class="{empty: !output}" x-text="output || '[Result will appear here]'"></div>
                </div>

                <div class="form-group" x-show="mode === 'transform'">
                    <label>Result</label>
                    <div class="result-box" :class="{empty: !output}" x-text="output || '[Transformed text will appear here]'"></div>
                </div>

                <button @click="saveCurrentResult()" x-show="(output && (mode === 'encrypt' || mode === 'decrypt' || mode === 'transform')) || (mode === 'brute' && bruteResults.length) || (mode === 'spelling' && spellingWords.length)" :disabled="loading" style="margin-bottom:16px;">
                    <span x-show="!loading">Save Result</span>
                    <span x-show="loading">Working...</span>
                </button>
                <div class="badge" style="background: rgba(16, 185, 129, 0.12); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.25); margin-top: 0; display: block;" x-show="savedMessage" x-text="savedMessage"></div>

                <div class="form-group" x-show="mode === 'brute'">
                    <label>All Possible Plaintexts</label>
                    <div style="max-height:400px; overflow-y:auto;">
                        <template x-for="(item, i) in bruteResults" :key="i">
                            <div style="padding:8px; background:rgba(255,255,255,0.03); margin:4px 0; border-radius:4px; cursor:pointer; font-family:'Courier New',monospace; font-size:12px;" @click="copyText(item.plaintext)">
                                <strong style="color:var(--primary);">Shift <span x-text="item.shift"></span>:</strong> <span x-text="item.plaintext.substring(0,30)"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="form-group" x-show="mode === 'table'">
                    <label>Alphabet Mapping</label>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(30px,1fr)); gap:4px; margin-top:12px;">
                        <template x-for="(map, i) in tableData" :key="i">
                            <div style="display:flex; flex-direction:column; align-items:center; background:rgba(255,255,255,0.03); border:1px solid var(--border-glass); border-radius:4px; padding:4px; font-size:10px;">
                                <div style="color:var(--text-muted);" x-text="map.from"></div>
                                <div style="color:var(--primary); font-weight:700;" x-text="map.to"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="form-group" x-show="mode === 'spelling'">
                    <label>Spelling Alphabet</label>
                    <div style="max-height:400px; overflow-y:auto;">
                        <template x-for="(item, i) in spellingWords" :key="i">
                            <div style="padding:8px; background:rgba(255,255,255,0.03); margin:4px 0; border-radius:4px; font-family:'Courier New',monospace; font-size:12px;">
                                <strong style="color:var(--primary);" x-text="item.char"></strong>: <span x-text="item.word"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top: 24px;">
            <div class="card-title">HISTORY</div>
            <div class="form-group">
                <label>Saved Results</label>
                <template x-if="historyEntries.length">
                    <div style="max-height:320px; overflow-y:auto;">
                        <template x-for="entry in historyEntries" :key="entry.id">
                            <div style="padding:14px; background:rgba(255,255,255,0.03); margin:8px 0; border-radius:10px; border:1px solid rgba(255,255,255,0.06);">
                                <div style="display:flex; justify-content:space-between; gap:12px; align-items:center; margin-bottom:8px;">
                                    <div>
                                        <strong style="color:var(--primary);" x-text="entry.label"></strong>
                                        <div style="color:var(--text-muted); font-size:12px;" x-text="entry.timestamp"></div>
                                    </div>
                                    <button @click="copyText(entry.output)" style="width:auto; padding:8px 14px;">Copy</button>
                                </div>
                                <div style="font-size:13px; line-height:1.5; white-space:pre-wrap; word-break:break-word; color:var(--text-main);" x-text="entry.output"></div>
                            </div>
                        </template>
                    </div>
                </template>
                <div x-show="!historyEntries.length" style="color:var(--text-muted);">No saved results yet. Use Save Result after running an operation.</div>
            </div>
            <button @click="clearHistory()" :disabled="historyEntries.length === 0 || loading" style="background:transparent; color:var(--text-main); border:1px solid var(--border-glass); width:auto;">
                Clear History
            </button>
        </div>
    </div>

    <footer class="footer">
        <p>🔐 Caesar Cipher Platform | <a href="/chacha20">Explore ChaCha20 →</a></p>
    </footer>

    <script>
        function app() {
            return {
                mode: 'encrypt',
                input: '',
                output: '',
                shift: 3,
                transformOperation: 'replace',
                searchText: '',
                replaceText: '',
                spellingWords: [],
                loading: false,
                error: null,
                bruteResults: [],
                tableData: [],

                async encrypt() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const r = await fetch('/caesar/encrypt', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ plaintext: this.input, shift: this.shift })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Error');
                        this.output = d.ciphertext;
                    } catch(e) { this.error = e.message; } 
                    finally { this.loading = false; }
                },

                async decrypt() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const r = await fetch('/caesar/decrypt', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ ciphertext: this.input, shift: this.shift })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Error');
                        this.output = d.plaintext;
                    } catch(e) { this.error = e.message; } 
                    finally { this.loading = false; }
                },

                async bruteForce() {
                    this.loading = true;
                    this.error = null;
                    this.bruteResults = [];
                    try {
                        const r = await fetch('/caesar/brute-force', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ ciphertext: this.input })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Error');
                        this.bruteResults = d.results || [];
                    } catch(e) { this.error = e.message; } 
                    finally { this.loading = false; }
                },

                async transformText() {
                    this.loading = true;
                    this.error = null;
                    this.output = '';
                    this.spellingWords = [];
                    try {
                        const r = await fetch('/caesar/transform', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({
                                text: this.input,
                                operation: this.transformOperation,
                                search: this.searchText,
                                replace: this.replaceText,
                            })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Error');
                        this.output = d.transformed;
                    } catch(e) { this.error = e.message; } 
                    finally { this.loading = false; }
                },

                async spellingAlphabet() {
                    this.loading = true;
                    this.error = null;
                    this.output = '';
                    this.spellingWords = [];
                    try {
                        const r = await fetch('/caesar/spelling-alphabet', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ text: this.input })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Error');
                        this.spellingWords = d.mapping || [];
                    } catch(e) { this.error = e.message; } 
                    finally { this.loading = false; }
                },

                async generateTable() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const r = await fetch(`/caesar/shift-table?shift=${this.shift}`);
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Error');
                        this.tableData = d.mapping || [];
                    } catch(e) { this.error = e.message; } 
                    finally { this.loading = false; }
                },

                saveCurrentResult() {
                    this.error = null;
                    this.savedMessage = '';
                    const resultText = this.getCurrentResultText();
                    if (!resultText) {
                        this.error = 'Tidak ada hasil untuk disimpan.';
                        return;
                    }

                    const entry = {
                        id: Date.now(),
                        timestamp: new Date().toLocaleString('id-ID', { hour12: false }),
                        mode: this.mode,
                        label: this.getCurrentResultLabel(),
                        output: resultText,
                    };

                    this.historyEntries.unshift(entry);
                    if (this.historyEntries.length > 30) {
                        this.historyEntries = this.historyEntries.slice(0, 30);
                    }
                    localStorage.setItem(this.historyKey, JSON.stringify(this.historyEntries));
                    this.savedMessage = 'Result saved to history.';
                },

                getCurrentResultText() {
                    if (this.mode === 'encrypt' || this.mode === 'decrypt' || this.mode === 'transform') {
                        return this.output || '';
                    }
                    if (this.mode === 'brute') {
                        return this.bruteResults.map(item => `Shift ${item.shift}: ${item.plaintext}`).join('\n');
                    }
                    if (this.mode === 'spelling') {
                        return this.spellingWords.map(item => `${item.char}: ${item.word}`).join(', ');
                    }
                    return '';
                },

                getCurrentResultLabel() {
                    if (this.mode === 'encrypt') return 'Encrypt Result';
                    if (this.mode === 'decrypt') return 'Decrypt Result';
                    if (this.mode === 'transform') return `Transform (${this.transformOperation})`;
                    if (this.mode === 'brute') return 'Brute Force Results';
                    if (this.mode === 'spelling') return 'Spelling Alphabet';
                    return 'Caesar Result';
                },

                loadHistory() {
                    this.historyEntries = JSON.parse(localStorage.getItem(this.historyKey) || '[]');
                },

                clearHistory() {
                    if (!confirm('Hapus semua riwayat Caesar?')) {
                        return;
                    }
                    this.historyEntries = [];
                    localStorage.removeItem(this.historyKey);
                    this.savedMessage = 'History cleared.';
                },

                copyText(text) {
                    navigator.clipboard.writeText(text);
                    alert('Copied: ' + text.substring(0, 30) + '...');
                }
            };
        }
    </script>
</body>
</html>
