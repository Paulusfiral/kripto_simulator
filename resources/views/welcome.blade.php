<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kripto Simulator - Advanced Cryptography Platform</title>
    <meta name="description" content="Explore Caesar and ChaCha20 encryption algorithms with interactive tools and educational features">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #2a2a2a;
            --bg-accent: #E50914;
            --text-primary: #ffffff;
            --text-secondary: #cccccc;
            --text-muted: #888888;
            --border-color: #333333;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        [data-theme="light"] {
            --bg-primary: #ffffff;
            --bg-secondary: #f8f9fa;
            --bg-tertiary: #e9ecef;
            --bg-accent: #E50914;
            --text-primary: #1a1a1a;
            --text-secondary: #6c757d;
            --text-muted: #adb5bd;
            --border-color: #dee2e6;
            --glass-bg: rgba(0, 0, 0, 0.02);
            --glass-border: rgba(0, 0, 0, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--bg-accent), transparent);
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-shape:nth-child(1) {
            width: 200px;
            height: 200px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-shape:nth-child(2) {
            width: 150px;
            height: 150px;
            top: 60%;
            right: 10%;
            animation-delay: 2s;
        }

        .floating-shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .navbar {
            backdrop-filter: blur(20px);
            background: var(--glass-bg);
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .hero-section {
            padding: 4rem 2rem;
            text-align: center;
            position: relative;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            background: linear-gradient(135deg, var(--bg-accent), #ff6b6b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto 2rem;
        }

        .theme-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .theme-toggle:hover {
            transform: scale(1.1);
            background: var(--bg-accent);
            color: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            backdrop-filter: blur(20px);
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(229, 9, 20, 0.1), transparent);
            transition: left 0.5s;
        }

        .feature-card:hover::before {
            left: 100%;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            border-color: var(--bg-accent);
            box-shadow: 0 20px 40px rgba(229, 9, 20, 0.1);
        }

        .feature-icon {
            font-size: 3rem;
            color: var(--bg-accent);
            margin-bottom: 1rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .feature-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .feature-tag {
            background: var(--bg-accent);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .feature-button {
            background: var(--bg-accent);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .feature-button:hover {
            background: #d40813;
            transform: translateY(-2px);
        }

        .stats-section {
            padding: 4rem 2rem;
            background: var(--glass-bg);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--bg-accent);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
        }

        .footer {
            padding: 2rem;
            text-align: center;
            color: var(--text-muted);
            border-top: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 1rem;
            }

            .features-grid {
                padding: 1rem;
                grid-template-columns: 1fr;
            }

            .feature-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body data-theme="dark">
    <div class="animated-bg">
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
        <div class="floating-shape"></div>
    </div>

    <!-- Theme Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <i class="fas fa-moon" id="theme-icon"></i>
    </button>

    <!-- Navigation -->
    <nav class="navbar">
        <div style="max-width: 1200px; margin: 0 auto; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-weight: 700; font-size: 1.5rem; color: var(--bg-accent);">
                <i class="fas fa-shield-alt" style="margin-right: 0.5rem;"></i>
                Kripto Simulator
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                @auth
                    <a href="{{ url('/dashboard') }}" style="color: var(--text-secondary); text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='var(--glass-bg)'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-tachometer-alt" style="margin-right: 0.5rem;"></i>Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" style="color: var(--text-secondary); text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.background='var(--glass-bg)'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>Login
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" style="background: var(--bg-accent); color: white; text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;" onmouseover="this.style.background='#d40813'" onmouseout="this.style.background='var(--bg-accent)'">
                            <i class="fas fa-user-plus" style="margin-right: 0.5rem;"></i>Register
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <h1 class="hero-title">
            <i class="fas fa-lock" style="margin-right: 0.5rem;"></i>
            Advanced Cryptography Platform
        </h1>
        <p class="hero-subtitle">
            Explore classical and modern encryption algorithms through interactive tools.
            Learn cryptography concepts with hands-on experience and educational features.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
            <a href="{{ route('caesar.index') }}" style="background: var(--bg-accent); color: white; text-decoration: none; padding: 1rem 2rem; border-radius: 12px; font-weight: 600; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(229, 9, 20, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <i class="fas fa-key"></i>
                Try Caesar Cipher
            </a>
            <a href="{{ route('chacha20.index') }}" style="background: var(--glass-bg); color: var(--text-primary); text-decoration: none; padding: 1rem 2rem; border-radius: 12px; font-weight: 600; border: 1px solid var(--glass-border); transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 0.5rem;" onmouseover="this.style.background='var(--bg-accent)'; this.style.color='white'" onmouseout="this.style.background='var(--glass-bg)'; this.style.color='var(--text-primary)'">
                <i class="fas fa-stream"></i>
                Try ChaCha20
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 4rem 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
            <h2 style="text-align: center; font-size: 2.5rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-primary);">
                Cryptography Tools
            </h2>
            <p style="text-align: center; color: var(--text-secondary); margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Interactive encryption and decryption tools with educational features and real-time processing.
            </p>

            <div class="features-grid">
                <!-- Caesar Cipher Card -->
                <div class="feature-card" onclick="window.location.href='{{ route('caesar.index') }}'">
                    <div class="feature-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3 class="feature-title">Caesar Cipher</h3>
                    <p class="feature-description">
                        The classic substitution cipher used by Julius Caesar. Shift letters by a fixed number of positions in the alphabet.
                    </p>
                    <div class="feature-features">
                        <span class="feature-tag">Classic Algorithm</span>
                        <span class="feature-tag">Educational</span>
                        <span class="feature-tag">Interactive</span>
                        <span class="feature-tag">Alphanumeric</span>
                    </div>
                    <button class="feature-button">
                        <i class="fas fa-play" style="margin-right: 0.5rem;"></i>
                        Start Encrypting
                    </button>
                </div>

                <!-- ChaCha20 Card -->
                <div class="feature-card" onclick="window.location.href='{{ route('chacha20.index') }}'">
                    <div class="feature-icon">
                        <i class="fas fa-stream"></i>
                    </div>
                    <h3 class="feature-title">ChaCha20</h3>
                    <p class="feature-description">
                        Modern stream cipher known for its speed and security. Used in protocols like TLS and SSH.
                    </p>
                    <div class="feature-features">
                        <span class="feature-tag">Modern Algorithm</span>
                        <span class="feature-tag">High Performance</span>
                        <span class="feature-tag">Secure</span>
                        <span class="feature-tag">Industry Standard</span>
                    </div>
                    <button class="feature-button">
                        <i class="fas fa-play" style="margin-right: 0.5rem;"></i>
                        Start Encrypting
                    </button>
                </div>

                <!-- Features Overview Card -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="feature-title">Educational Features</h3>
                    <p class="feature-description">
                        Learn cryptography through interactive tools, step-by-step explanations, and hands-on practice.
                    </p>
                    <div class="feature-features">
                        <span class="feature-tag">Step-by-Step</span>
                        <span class="feature-tag">Visual Learning</span>
                        <span class="feature-tag">Practice Mode</span>
                        <span class="feature-tag">History Tracking</span>
                    </div>
                    <button class="feature-button" onclick="scrollToFeatures()">
                        <i class="fas fa-info-circle" style="margin-right: 0.5rem;"></i>
                        Learn More
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">2</div>
                <div class="stat-label">Encryption Algorithms</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">∞</div>
                <div class="stat-label">Possible Combinations</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100%</div>
                <div class="stat-label">Educational Focus</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Available Learning</div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div style="max-width: 1200px; margin: 0 auto;">
            <p>&copy; {{ date('Y') }} Kripto Simulator. Built with Laravel & FastAPI.</p>
            <p style="margin-top: 0.5rem; font-size: 0.875rem;">
                Version {{ app()->version() }}
                <a href="https://github.com/laravel/laravel/blob/13.x/CHANGELOG.md" target="_blank" style="color: var(--bg-accent); text-decoration: none; margin-left: 1rem;">
                    <i class="fas fa-external-link-alt" style="margin-right: 0.25rem;"></i>View Changelog
                </a>
            </p>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            const body = document.body;
            const icon = document.getElementById('theme-icon');
            const currentTheme = body.getAttribute('data-theme');

            if (currentTheme === 'dark') {
                body.setAttribute('data-theme', 'light');
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'light');
            } else {
                body.setAttribute('data-theme', 'dark');
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'dark');
            }
        }

        function scrollToFeatures() {
            document.querySelector('.features-grid').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.body.setAttribute('data-theme', savedTheme);
        document.getElementById('theme-icon').className = savedTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';

        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>