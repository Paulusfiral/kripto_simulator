<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Caesar Cipher Simulator | NakamotoX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        :root {
            /* Professional Color Palette */
            --bg-primary: #ffffff;
            --bg-secondary: #f8fafc;
            --bg-tertiary: #f1f5f9;
            --bg-card: #ffffff;
            --bg-accent: #e50914;
            --bg-glass: rgba(255, 255, 255, 0.95);
            --border-light: #e2e8f0;
            --border-medium: #cbd5e1;
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --accent-primary: #e50914;
            --accent-secondary: #dc2626;
            --accent-success: #059669;
            --accent-warning: #d97706;
            --accent-info: #0891b2;
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            --gradient-primary: linear-gradient(135deg, #e50914 0%, #dc2626 100%);
            --gradient-secondary: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        .dark {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-tertiary: #334155;
            --bg-card: #1e293b;
            --bg-glass: rgba(15, 23, 42, 0.95);
            --border-light: #334155;
            --border-medium: #475569;
            --text-primary: #f8fafc;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --accent-primary: #f87171;
            --accent-secondary: #fca5a5;
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.4);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Layout Grid System */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .grid {
            display: grid;
            gap: 24px;
        }

        .grid-cols-1 { grid-template-columns: 1fr; }
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }

        @media (max-width: 1024px) {
            .grid-cols-2 { grid-template-columns: 1fr; }
            .grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
            .grid-cols-4 { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .grid-cols-3, .grid-cols-4 { grid-template-columns: 1fr; }
        }

        /* Navigation */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(20px);
            background: var(--bg-glass);
            border-bottom: 1px solid var(--border-light);
            padding: 16px 0;
            box-shadow: var(--shadow-xs);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            color: var(--accent-primary);
        }

        .logo span {
            color: var(--accent-secondary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--accent-primary);
            background: var(--bg-secondary);
        }

        .theme-toggle {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 8px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 16px;
        }

        .theme-toggle:hover {
            background: var(--bg-tertiary);
            border-color: var(--border-medium);
        }

        /* Header Section */
        .header {
            text-align: center;
            padding: 48px 0 32px;
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
        }

        .header-title {
            font-size: clamp(2.5rem, 4vw, 3.5rem);
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 16px;
            line-height: 1.1;
        }

        .header-subtitle {
            font-size: 1.125rem;
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto 32px;
            line-height: 1.6;
        }

        .header-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--accent-primary);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 16px;
            display: block;
        }

        .feature-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .feature-desc {
            color: var(--text-muted);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Mode Selector */
        .mode-selector {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
        }

        .mode-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            text-align: center;
            margin-bottom: 24px;
        }

        .mode-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
        }

        .mode-button {
            background: var(--bg-secondary);
            border: 2px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 20px 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .mode-button:hover,
        .mode-button:focus-visible {
            border-color: var(--accent-info);
            background: var(--bg-primary);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .mode-button:active,
        .mode-button.active,
        .mode-button.active:hover,
        .mode-button.active:focus-visible,
        .dark .mode-button.active:hover {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
            border-color: #0ea5e9;
            color: white;
            transform: translateY(0);
            box-shadow: 0 12px 24px rgba(2, 132, 199, 0.18);
        }

        .mode-button.active .mode-desc {
            color: rgba(255, 255, 255, 0.9);
        }

        .mode-button.active .mode-name,
        .mode-button.active .mode-icon {
            color: white;
        }

        .dark .mode-button {
            color: #f8fafc;
        }

        .dark .mode-button .mode-name,
        .dark .mode-button .mode-icon {
            color: #f8fafc;
        }

        .dark .mode-button .mode-desc {
            color: rgba(248, 250, 252, 0.72);
        }

        .dark .mode-button:hover {
            border-color: var(--accent-info);
            background: rgba(248, 250, 252, 0.08);
        }

        .mode-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
        }

        .mode-name {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .mode-desc {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .hero-note {
            background: rgba(15, 116, 183, 0.1);
            border: 1px solid rgba(15, 116, 183, 0.18);
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            color: var(--text-primary);
            margin: 0 auto 28px;
            font-size: 0.95rem;
            max-width: 820px;
            min-width: 320px;
            width: min(100%, 820px);
            text-align: center;
            line-height: 1.6;
        }

        .feature-card {
            min-height: 220px;
        }

        .result-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            margin-top: 18px;
        }

        .btn-copy {
            background: #ea7c2f;
            color: white;
            border: 1px solid rgba(234, 124, 47, 0.4);
            box-shadow: 0 8px 20px rgba(234, 124, 47, 0.18);
        }

        .btn-copy:hover {
            background: #d66d21;
            border-color: #c3561b;
        }

        .btn-clear {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-medium);
        }

        .btn-clear:hover {
            background: var(--bg-tertiary);
        }

        /* Main Content */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            margin-bottom: 48px;
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 24px;
            }
        }

        /* Card Styles */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--accent-primary);
            border-radius: 2px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Input Styles */
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            background: var(--bg-secondary);
            border: 2px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 16px;
            color: var(--text-primary);
            font-size: 14px;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        input[type="text"]:focus, input[type="number"]:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(229, 9, 20, 0.1);
            background: var(--bg-primary);
        }

        textarea {
            resize: vertical;
            min-height: 120px;
            line-height: 1.6;
        }

        /* Button Styles */
        .btn {
            background: var(--accent-primary);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            padding: 14px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            min-height: 48px;
            justify-content: center;
        }

        .btn:hover {
            background: var(--accent-secondary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-light);
        }

        .btn-secondary:hover {
            background: var(--bg-tertiary);
            border-color: var(--border-medium);
        }

        .btn-success {
            background: var(--accent-success);
        }

        .btn-success:hover {
            background: #047857;
        }

        .btn-warning {
            background: var(--accent-warning);
        }

        .btn-warning:hover {
            background: #b45309;
        }

        .btn-info {
            background: var(--accent-info);
        }

        .btn-info:hover {
            background: #0e7490;
        }

        /* Action Buttons */
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        /* Result Box */
        .result-box {
            background: var(--bg-secondary);
            border: 2px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 20px;
            min-height: 120px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            white-space: pre-wrap;
            word-break: break-word;
            transition: all 0.2s ease;
            position: relative;
        }

        .result-box:not(.empty) {
            border-color: var(--accent-success);
            background: rgba(5, 150, 105, 0.05);
        }

        .result-box.empty {
            color: var(--text-muted);
            font-style: italic;
        }

        .result-box::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 16px;
            background: var(--bg-primary);
            padding: 0 8px;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* Shift Slider */
        .shift-control {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-top: 16px;
        }

        .shift-slider {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .shift-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-primary);
            min-width: 60px;
            text-align: center;
        }

        input[type="range"] {
            flex: 1;
            height: 8px;
            border-radius: 4px;
            background: var(--border-light);
            outline: none;
            -webkit-appearance: none;
            appearance: none;
            cursor: pointer;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--accent-primary);
            cursor: pointer;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.1);
        }

        .shift-presets {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .shift-preset {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .shift-preset:hover {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        /* Transform Controls */
        .transform-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .transform-controls {
                grid-template-columns: 1fr;
            }
        }

        /* Brute Force Results */
        .brute-results {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
        }

        .brute-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid var(--border-light);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .brute-item:hover {
            background: var(--bg-secondary);
        }

        .brute-item:last-child {
            border-bottom: none;
        }

        .brute-shift {
            font-weight: 600;
            color: var(--accent-primary);
            min-width: 80px;
        }

        .brute-text {
            flex: 1;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
            margin: 0 16px;
        }

        /* Table Grid */
        .table-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 8px;
            margin-top: 16px;
        }

        .table-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
            padding: 12px 8px;
            font-size: 12px;
            text-align: center;
        }

        .table-from {
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .table-to {
            color: var(--accent-primary);
            font-weight: 700;
            font-size: 14px;
        }

        /* Spelling List */
        .spelling-list {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid var(--border-light);
            border-radius: var(--radius-md);
        }

        .spelling-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-light);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 13px;
        }

        .spelling-item:last-child {
            border-bottom: none;
        }

        .spelling-char {
            font-weight: 600;
            color: var(--accent-primary);
            min-width: 30px;
        }

        .spelling-arrow {
            margin: 0 12px;
            color: var(--text-muted);
        }

        .spelling-word {
            color: var(--text-primary);
        }

        /* History Section */
        .history-section {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            padding: 32px;
            box-shadow: var(--shadow-sm);
        }

        .history-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .history-title::before {
            content: '';
            width: 4px;
            height: 24px;
            background: var(--accent-primary);
            border-radius: 2px;
        }

        .history-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .history-item {
            background: var(--bg-secondary);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.2s ease;
        }

        .history-item:hover {
            box-shadow: var(--shadow-sm);
            border-color: var(--border-medium);
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .history-label {
            font-weight: 600;
            color: var(--accent-primary);
            font-size: 16px;
        }

        .history-timestamp {
            color: var(--text-muted);
            font-size: 12px;
        }

        .history-content {
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
            color: var(--text-primary);
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            background: var(--bg-primary);
            padding: 12px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-light);
        }

        .history-empty {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .history-empty-icon {
            font-size: 4rem;
            margin-bottom: 16px;
        }

        .history-empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .history-empty-text {
            font-size: 0.875rem;
        }

        /* Status Messages */
        .status-message {
            padding: 12px 16px;
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-success {
            background: rgba(5, 150, 105, 0.1);
            color: var(--accent-success);
            border: 1px solid rgba(5, 150, 105, 0.2);
        }

        [x-cloak] { display: none !important; }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.72);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 999;
            padding: 20px;
        }

        .modal-card {
            width: min(100%, 520px);
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            padding: 28px;
            position: relative;
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .modal-text {
            color: var(--text-muted);
            line-height: 1.75;
            margin-bottom: 16px;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            padding: 8px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .modal-btn {
            min-width: 140px;
        }

        .modal-btn.cancel {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-light);
        }

        .modal-btn.cancel:hover {
            background: var(--bg-tertiary);
        }

        .modal-btn.confirm {
            background: #dc2626;
            color: white;
        }

        .modal-btn.confirm:hover {
            background: #b91c1c;
        }

        .status-error {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        /* Loading Spinner */
        .loading-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Footer */
        .footer {
            text-align: center;
            padding: 32px 0;
            color: var(--text-muted);
            border-top: 1px solid var(--border-light);
            margin-top: 48px;
        }

        .footer-content {
            max-width: 600px;
            margin: 0 auto;
        }

        .footer-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 16px;
        }

        .footer-link {
            color: var(--accent-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .footer-link:hover {
            color: var(--accent-secondary);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 16px;
            }

            .header {
                padding: 32px 0 24px;
            }

            .header-title {
                font-size: 2rem;
            }

            .header-subtitle {
                font-size: 1rem;
            }

            .card {
                padding: 24px;
            }

            .mode-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .mode-button {
                padding: 16px 12px;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .shift-slider {
                flex-direction: column;
                gap: 12px;
            }

            .shift-value {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .mode-grid {
                grid-template-columns: 1fr;
            }

            .header-features {
                grid-template-columns: 1fr;
            }

            .nav-links {
                gap: 16px;
            }

            .nav-link {
                padding: 6px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">Nakamoto<span>X</span></a>
            <div class="nav-links">
                <a href="/" class="nav-link">Home</a>
                <a href="/chacha20" class="nav-link">ChaCha20</a>
                <button class="theme-toggle" @click="darkMode = !darkMode" :aria-label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
                    <span x-text="darkMode ? '☀️' : '🌙'"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="header">
        <div class="container">
            <h1 class="header-title">Caesar Cipher Simulator</h1>
            <p class="header-subtitle">
                Master the ancient art of cryptography with our comprehensive Caesar cipher toolkit.
                Encrypt, decrypt, analyze patterns, and explore cryptographic concepts interactively.
            </p>
            <div class="hero-note">
                <strong>Quick start:</strong> Select an operation, enter text or digits, choose a shift, run the action, and save the result to history.
            </div>

            <div class="header-features">
                <div class="feature-card">
                    <span class="feature-icon">🔐</span>
                    <h3 class="feature-title">Bidirectional Encryption</h3>
                    <p class="feature-desc">
                        Transform plaintext to ciphertext and back again with a configurable shift.
                        Supports letters (A-Z) and numbers (0-9) for complete coverage.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🔍</span>
                    <h3 class="feature-title">Brute Force Analysis</h3>
                    <p class="feature-desc">
                        Automatically try all 26 possible shifts to find the correct decryption.
                        Perfect for cryptanalysis and understanding cipher vulnerabilities.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🛠️</span>
                    <h3 class="feature-title">Text Transformation</h3>
                    <p class="feature-desc">
                        Advanced text manipulation tools including reverse, find-and-replace,
                        and character substitution for enhanced cryptographic operations.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">📊</span>
                    <h3 class="feature-title">Visual Mapping Table</h3>
                    <p class="feature-desc">
                        See exactly how each character transforms with your chosen shift value.
                        Interactive table showing the complete character mapping.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">🔤</span>
                    <h3 class="feature-title">NATO Phonetic Alphabet</h3>
                    <p class="feature-desc">
                        Convert text to phonetic spelling using international aviation standards.
                        Essential for clear communication in cryptographic contexts.
                    </p>
                </div>
                <div class="feature-card">
                    <span class="feature-icon">📚</span>
                    <h3 class="feature-title">Operation History</h3>
                    <p class="feature-desc">
                        Track all your cryptographic operations with timestamps.
                        Review previous encryptions and maintain a comprehensive audit trail.
                    </p>
                </div>
            </div>
        </div>
    </header>

    <div class="container" x-data="app()" x-init="loadHistory()">
        <!-- Mode Selector -->
        <div class="mode-selector">
            <h2 class="mode-title">Choose Your Operation</h2>
            <div class="mode-grid">
                <button class="mode-button" @click="setMode('encrypt')" :class="{active: mode === 'encrypt'}">
                    <span class="mode-icon">🔐</span>
                    <div class="mode-name">Encrypt</div>
                    <div class="mode-desc">Convert plaintext to ciphertext using Caesar cipher</div>
                </button>
                <button class="mode-button" @click="setMode('decrypt')" :class="{active: mode === 'decrypt'}">
                    <span class="mode-icon">🔓</span>
                    <div class="mode-name">Decrypt</div>
                    <div class="mode-desc">Convert ciphertext back to plaintext</div>
                </button>
                <button class="mode-button" @click="setMode('transform')" :class="{active: mode === 'transform'}">
                    <span class="mode-icon">🛠️</span>
                    <div class="mode-name">Transform</div>
                    <div class="mode-desc">Advanced text manipulation and processing</div>
                </button>
                <button class="mode-button" @click="setMode('brute')" :class="{active: mode === 'brute'}">
                    <span class="mode-icon">💥</span>
                    <div class="mode-name">Brute Force</div>
                    <div class="mode-desc">Try all shifts to find correct decryption</div>
                </button>
                <button class="mode-button" @click="setMode('table')" :class="{active: mode === 'table'}">
                    <span class="mode-icon">📊</span>
                    <div class="mode-name">Shift Table</div>
                    <div class="mode-desc">Visual character mapping for current shift</div>
                </button>
                <button class="mode-button" @click="setMode('spelling')" :class="{active: mode === 'spelling'}">
                    <span class="mode-icon">🔤</span>
                    <div class="mode-name">Phonetic</div>
                    <div class="mode-desc">Convert text to NATO phonetic alphabet</div>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Input Section -->
            <div class="card">
                <h2 class="card-title">📝 Input</h2>

                <div class="form-group">
                    <div class="form-label">
                        <span x-text="getInputLabel()"></span>
                        <span class="form-hint" x-text="getInputTooltip()"></span>
                    </div>
                    <textarea x-model="input" :placeholder="getInputPlaceholder()" rows="8"></textarea>
                </div>

                <!-- Shift Control -->
                <div class="form-group" x-show="mode === 'encrypt' || mode === 'decrypt' || mode === 'table'">
                    <div class="form-label">
                        Shift Value
                        <span class="form-hint">Choose shift amount (0-25)</span>
                    </div>
                    <div class="shift-control">
                        <div class="shift-slider">
                            <span class="shift-value" x-text="shift"></span>
                            <input type="range" x-model.number="shift" min="0" max="25" step="1">
                        </div>
                        <div class="shift-presets">
                            <button class="shift-preset" @click="shift = 3">3</button>
                            <button class="shift-preset" @click="shift = 13">13</button>
                            <button class="shift-preset" @click="shift = 7">7</button>
                            <button class="shift-preset" @click="shift = 1">1</button>
                            <button class="shift-preset" @click="shift = 0">0</button>
                        </div>
                    </div>
                </div>

                <!-- Transform Controls -->
                <div class="form-group" x-show="mode === 'transform'">
                    <div class="form-label">
                        Transformation Type
                        <span class="form-hint">Select the type of text transformation</span>
                    </div>
                    <div class="transform-controls">
                        <select x-model="transformOperation">
                            <option value="reverse">Reverse Text</option>
                            <option value="uppercase">Convert to Uppercase</option>
                            <option value="lowercase">Convert to Lowercase</option>
                            <option value="replace">Find & Replace</option>
                        </select>
                        <div x-show="transformOperation === 'replace'">
                            <input type="text" x-model="searchText" placeholder="Find text">
                            <input type="text" x-model="replaceText" placeholder="Replace with">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn btn-info" @click="processText()" x-show="mode === 'encrypt' || mode === 'decrypt'" :disabled="!input || loading">
                        <span x-show="!loading" x-text="mode === 'encrypt' ? '🔐 Encrypt Text' : '🔓 Decrypt Text'"></span>
                        <span x-show="loading" class="loading-spinner"></span>
                        <span x-show="loading">Processing...</span>
                    </button>

                    <button class="btn btn-warning" @click="transformText()" x-show="mode === 'transform'" :disabled="!input || loading || (transformOperation === 'replace' && !searchText)">
                        <span x-show="!loading">🛠️ Transform Text</span>
                        <span x-show="loading" class="loading-spinner"></span>
                        <span x-show="loading">Transforming...</span>
                    </button>

                    <button class="btn btn-warning" @click="bruteForce()" x-show="mode === 'brute'" :disabled="!input || loading">
                        <span x-show="!loading">💥 Brute Force (26 shifts)</span>
                        <span x-show="loading" class="loading-spinner"></span>
                        <span x-show="loading">Cracking...</span>
                    </button>

                    <button class="btn btn-secondary" @click="generateTable()" x-show="mode === 'table'" :disabled="loading">
                        <span x-show="!loading">📊 Generate Mapping</span>
                        <span x-show="loading" class="loading-spinner"></span>
                        <span x-show="loading">Generating...</span>
                    </button>

                    <button class="btn btn-secondary" @click="spellingAlphabet()" x-show="mode === 'spelling'" :disabled="!input || loading">
                        <span x-show="!loading">🔤 Generate Phonetic</span>
                        <span x-show="loading" class="loading-spinner"></span>
                        <span x-show="loading">Processing...</span>
                    </button>
                </div>

                <!-- Status Messages -->
                <div class="status-message status-error" x-show="error" x-text="error"></div>
            </div>

            <!-- Output Section -->
            <div class="card">
                <h2 class="card-title">📤 Output</h2>

                <!-- Encrypt/Decrypt Results -->
                <div class="form-group" x-show="mode === 'encrypt' || mode === 'decrypt'">
                    <div class="form-label" x-text="mode === 'encrypt' ? 'Encrypted Result' : 'Decrypted Result'"></div>
                    <div class="result-box" :class="{empty: !output}" x-text="output || getOutputPlaceholder()"></div>
                </div>

                <!-- Transform Results -->
                <div class="form-group" x-show="mode === 'transform'">
                    <div class="form-label">Transformed Result</div>
                    <div class="result-box" :class="{empty: !output}" x-text="output || 'Your transformed text will appear here...'"></div>
                </div>

                <!-- Brute Force Results -->
                <div class="form-group" x-show="mode === 'brute'">
                    <div class="form-label">All Possible Decryptions</div>
                    <div class="brute-results" x-show="bruteResults.length">
                        <template x-for="(item, i) in bruteResults" :key="i">
                            <div class="brute-item" @click="copyText(item.plaintext)">
                                <div class="brute-shift">Shift @{{ item.shift }}</div>
                                <div class="brute-text" x-text="item.plaintext.substring(0, 50) + (item.plaintext.length > 50 ? '...' : '')"></div>
                            </div>
                        </template>
                    </div>
                    <div class="result-box empty" x-show="!bruteResults.length">
                        Run brute force analysis to see all possible decryptions
                    </div>
                </div>

                <!-- Shift Table -->
                <div class="form-group" x-show="mode === 'table'">
                    <div class="form-label">Character Mapping (Shift: <span x-text="shift"></span>)</div>
                    <div class="table-grid" x-show="tableData.length">
                        <template x-for="(map, i) in tableData" :key="i">
                            <div class="table-item">
                                <div class="table-from" x-text="map.from"></div>
                                <div class="table-to" x-text="map.to"></div>
                            </div>
                        </template>
                    </div>
                    <div class="result-box empty" x-show="!tableData.length">
                        Generate mapping table to see character transformations
                    </div>
                </div>

                <!-- Spelling Alphabet -->
                <div class="form-group" x-show="mode === 'spelling'">
                    <div class="form-label">NATO Phonetic Alphabet</div>
                    <div class="spelling-list" x-show="spellingWords.length">
                        <template x-for="(item, i) in spellingWords" :key="i">
                            <div class="spelling-item">
                                <span class="spelling-char" x-text="item.char"></span>
                                <span class="spelling-arrow">→</span>
                                <span class="spelling-word" x-text="item.word"></span>
                            </div>
                        </template>
                    </div>
                    <div class="result-box empty" x-show="!spellingWords.length">
                        Generate phonetic spelling for your input text
                    </div>
                </div>

                <div class="result-actions" x-show="mode !== 'table'">
                    <button class="btn btn-copy" @click="copyText(getCurrentResultText())" :disabled="!getCurrentResultText()">
                        📋 Copy Output
                    </button>
                    <button class="btn btn-clear" @click="clearResult()" :disabled="!input && !output && !bruteResults.length && !tableData.length && !spellingWords.length">
                        🧹 Clear Fields
                    </button>
                </div>

                <!-- Save Result Button -->
                <button class="btn btn-success" @click="saveCurrentResult()" x-show="(output && (mode === 'encrypt' || mode === 'decrypt' || mode === 'transform')) || (mode === 'brute' && bruteResults.length) || (mode === 'spelling' && spellingWords.length)" :disabled="loading" style="margin-top: 24px;">
                    <span x-show="!loading">💾 Save Result</span>
                    <span x-show="loading" class="loading-spinner"></span>
                    <span x-show="loading">Saving...</span>
                </button>

                <div class="status-message status-success" x-show="savedMessage || copyMessage" x-text="savedMessage || copyMessage"></div>
            </div>
        </div>

        <!-- History Section -->
        <div class="history-section">
            <div class="history-title">📚 Operation History</div>
            <div class="history-list" x-show="historyEntries.length">
                <template x-for="entry in historyEntries" :key="entry.id">
                    <div class="history-item">
                        <div class="history-header">
                            <div class="history-label" x-text="entry.label"></div>
                            <div class="history-timestamp" x-text="entry.timestamp"></div>
                        </div>
                        <div class="history-content" x-text="entry.output"></div>
                    </div>
                </template>
            </div>
            <div class="history-empty" x-show="!historyEntries.length">
                <div class="history-empty-icon">📝</div>
                <div class="history-empty-title">No History Yet</div>
                <div class="history-empty-text">Start using the Caesar cipher tools above to build your operation history!</div>
            </div>

            <button class="btn btn-secondary" @click="openClearHistoryModal()" :disabled="historyEntries.length === 0" style="margin-top: 24px;">
                🗑️ Clear All History
            </button>

            <div x-show="showClearHistoryModal" x-cloak class="modal-backdrop" @keydown.escape.window="closeClearHistoryModal()">
                <div class="modal-card" @click.outside="closeClearHistoryModal()" role="dialog" aria-modal="true" aria-labelledby="clearHistoryTitle">
                    <div class="modal-header">
                        <div>
                            <h3 id="clearHistoryTitle" class="modal-title">Confirm History Deletion</h3>
                            <p class="modal-text">This action will permanently remove all saved operations from the history log. Once deleted, the history cannot be recovered.</p>
                        </div>
                        <button class="modal-close" @click="closeClearHistoryModal()" aria-label="Close modal">×</button>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn modal-btn cancel" @click="closeClearHistoryModal()">Cancel</button>
                        <button type="button" class="btn modal-btn confirm" @click="confirmClearHistory()">Delete History</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-title">Caesar Cipher Simulator</div>
            <div>Built with ❤️ using Laravel & Python FastAPI</div>
            <div class="footer-links">
                <a href="/chacha20" class="footer-link">Try ChaCha20</a>
                <a href="/" class="footer-link">Back to Home</a>
            </div>
        </div>
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
                historyEntries: [],
                loading: false,
                error: null,
                savedMessage: '',
                copyMessage: '',
                historyKey: 'caesar_history',
                showClearHistoryModal: false,

                setMode(newMode) {
                    this.mode = newMode;
                    this.output = '';
                    this.error = '';
                    this.bruteResults = [];
                    this.tableData = [];
                    this.spellingWords = [];
                },

                getInputLabel() {
                    const labels = {
                        encrypt: 'Plaintext to Encrypt',
                        decrypt: 'Ciphertext to Decrypt',
                        transform: 'Text to Transform',
                        brute: 'Ciphertext to Crack',
                        table: 'Preview Shift Mapping',
                        spelling: 'Text to Spell'
                    };
                    return labels[this.mode] || 'Input Text';
                },

                getInputTooltip() {
                    const tooltips = {
                        encrypt: 'Enter the text you want to encrypt. Supports letters (A-Z, a-z) and numbers (0-9).',
                        decrypt: 'Enter the encrypted text you want to decrypt. Must use the same shift value used for encryption.',
                        transform: 'Enter text to apply transformation operations like reversing or find-and-replace.',
                        brute: 'Enter encrypted text to try decrypting with all 26 possible shift values.',
                        table: 'Preview how characters will be mapped with the current shift value.',
                        spelling: 'Enter text to convert each character to its NATO phonetic alphabet equivalent.'
                    };
                    return tooltips[this.mode] || 'Enter your text here';
                },

                getInputPlaceholder() {
                    const placeholders = {
                        encrypt: 'Enter plaintext to encrypt...',
                        decrypt: 'Enter ciphertext to decrypt...',
                        transform: 'Enter text to transform...',
                        brute: 'Enter ciphertext to brute force...',
                        table: 'Enter text to preview mapping...',
                        spelling: 'Enter text to convert to phonetic spelling...'
                    };
                    return placeholders[this.mode] || 'Enter your text here...';
                },

                getOutputPlaceholder() {
                    const placeholders = {
                        encrypt: 'Your encrypted text will appear here... 🔐',
                        decrypt: 'Your decrypted text will appear here... 🔓',
                        transform: 'Your transformed text will appear here... 🛠️',
                        brute: 'Brute force results will appear here... 💥',
                        table: 'Character mapping will appear here... 📊',
                        spelling: 'Phonetic spelling will appear here... 🔤'
                    };
                    return placeholders[this.mode] || 'Result will appear here...';
                },

                async processText() {
                    if (this.mode === 'encrypt') {
                        await this.encrypt();
                    } else if (this.mode === 'decrypt') {
                        await this.decrypt();
                    }
                },

                async encrypt() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const r = await fetch('/caesar/encrypt', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ plaintext: this.input, shift: this.shift })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Encryption failed');
                        this.output = d.ciphertext;
                    } catch(e) {
                        this.error = '❌ ' + e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async decrypt() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const r = await fetch('/caesar/decrypt', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ ciphertext: this.input, shift: this.shift })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.plaintext || 'Decryption failed');
                        this.output = d.plaintext;
                    } catch(e) {
                        this.error = '❌ ' + e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async transformText() {
                    this.loading = true;
                    this.error = null;
                    this.output = '';
                    try {
                        const r = await fetch('/caesar/transform', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                text: this.input,
                                operation: this.transformOperation,
                                search: this.searchText,
                                replace: this.replaceText,
                            })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Transform failed');
                        this.output = d.transformed;
                    } catch(e) {
                        this.error = '❌ ' + e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async bruteForce() {
                    this.loading = true;
                    this.error = null;
                    this.bruteResults = [];
                    try {
                        const r = await fetch('/caesar/brute-force', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ ciphertext: this.input })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Brute force failed');
                        this.bruteResults = d.results || [];
                    } catch(e) {
                        this.error = '❌ ' + e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async spellingAlphabet() {
                    this.loading = true;
                    this.error = null;
                    this.spellingWords = [];
                    try {
                        const r = await fetch('/caesar/spelling-alphabet', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ text: this.input })
                        });
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Spelling generation failed');
                        this.spellingWords = d.mapping || [];
                    } catch(e) {
                        this.error = '❌ ' + e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                async generateTable() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const r = await fetch(`/caesar/shift-table?shift=${this.shift}`);
                        const d = await r.json();
                        if (!r.ok) throw new Error(d.message || 'Table generation failed');
                        this.tableData = d.mapping || [];
                    } catch(e) {
                        this.error = '❌ ' + e.message;
                    } finally {
                        this.loading = false;
                    }
                },

                saveCurrentResult() {
                    this.error = null;
                    this.savedMessage = '';
                    this.copyMessage = '';
                    const resultText = this.getCurrentResultText();
                    if (!resultText) {
                        this.error = '❌ No result to save';
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
                    if (this.historyEntries.length > 50) {
                        this.historyEntries = this.historyEntries.slice(0, 50);
                    }
                    localStorage.setItem(this.historyKey, JSON.stringify(this.historyEntries));
                    this.savedMessage = '✅ Result saved to history!';

                    setTimeout(() => {
                        this.savedMessage = '';
                    }, 3000);
                },

                clearResult() {
                    this.output = '';
                    this.bruteResults = [];
                    this.tableData = [];
                    this.spellingWords = [];
                    this.error = null;
                    this.savedMessage = '';
                    this.copyMessage = '';
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
                    const labels = {
                        encrypt: '🔐 Encryption Result',
                        decrypt: '🔓 Decryption Result',
                        transform: `🛠️ Transform (${this.transformOperation})`,
                        brute: '💥 Brute Force Results',
                        spelling: '🔤 Spelling Alphabet'
                    };
                    return labels[this.mode] || 'Caesar Result';
                },

                loadHistory() {
                    this.historyEntries = JSON.parse(localStorage.getItem(this.historyKey) || '[]');
                },

                openClearHistoryModal() {
                    this.showClearHistoryModal = true;
                },

                closeClearHistoryModal() {
                    this.showClearHistoryModal = false;
                },

                confirmClearHistory() {
                    this.historyEntries = [];
                    localStorage.removeItem(this.historyKey);
                    this.savedMessage = 'History has been successfully cleared.';
                    this.closeClearHistoryModal();
                    setTimeout(() => {
                        this.savedMessage = '';
                    }, 2000);
                },

                copyText(text) {
                    if (!text) {
                        this.error = '❌ Nothing to copy';
                        return;
                    }

                    navigator.clipboard.writeText(text).then(() => {
                        this.copyMessage = '✅ Result copied to clipboard!';
                        this.error = null;
                        this.savedMessage = '';
                        setTimeout(() => {
                            this.copyMessage = '';
                        }, 2500);
                    }).catch(err => {
                        this.error = '❌ Failed to copy text.';
                        console.error('Failed to copy text: ', err);
                    });
                }
            };
        }
    </script>
</body>
</html>
