<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="description" content="<?= setting('site_description', 'Birth Month Association Management System') ?>">
    <meta name="keywords" content="<?= setting('site_keywords', 'birth month, association, membership') ?>">
    <meta name="author" content="<?= get_association_name() ?>">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?><?= setting('site_title', get_association_name()) ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= setting('favicon', '/assets/images/favicon.ico') ?>">
    
    <!-- Google Fonts - Vintage Style -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Merriweather:wght@300;400;700;900&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0,1" rel="stylesheet">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        /* CSS Variables - Dynamic from settings */
        <?php 
        $themeManagerInstance = new \App\Libraries\ThemeManager();
        echo $themeManagerInstance->getThemeCssVariables(); 
        ?>
        
        /* Reset & Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Merriweather', Georgia, 'Times New Roman', serif;
            background: var(--color-background);
            color: var(--color-text);
            line-height: 1.6;
            position: relative;
            min-height: 100vh;
        }
        
        /* Grain Overlay */
        .grain-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9998;
            opacity: 0.05;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
            background-repeat: repeat;
        }
        
        /* Paper Texture Background */
        .paper-bg {
            background-color: var(--color-background);
            background-image: var(--paper-texture);
            background-repeat: repeat;
            background-blend-mode: overlay;
        }
        
        /* Typography */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', Georgia, 'Times New Roman', serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        
        .font-mono-courier {
            font-family: 'Courier Prime', 'Courier New', monospace;
        }
        
        /* Ledger Lines */
        .ledger-lines {
            background: repeating-linear-gradient(
                transparent,
                transparent 32px,
                var(--color-outline) 32px,
                var(--color-outline) 33px
            );
            padding: 16px;
            line-height: 32px;
        }
        
        .ledger-lines h3,
        .ledger-lines h4,
        .ledger-lines p,
        .ledger-lines nav {
            line-height: 32px;
            position: relative;
            z-index: 1;
        }
        
        .ledger-lines nav a {
            display: flex;
            align-items: center;
            line-height: 32px;
        }
        
        /* Index Card Ruled Lines */
        /* .ruled-lines {
            background: repeating-linear-gradient(
                var(--color-surface) 0px,
                var(--color-surface) 28px,
                var(--color-outline) 28px,
                var(--color-outline) 29px
            );
        } */
        
        /* Polaroid Frame */
        .polaroid-card {
            background: var(--color-surface);
            padding: 12px 12px 24px 12px;
            box-shadow: 8px 8px 16px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }
        
        .polaroid-card:hover {
            transform: translateY(-4px);
            box-shadow: 12px 12px 24px rgba(0, 0, 0, 0.15);
        }
        
        .polaroid-card.rotate-1 {
            transform: rotate(1deg);
        }
        
        .polaroid-card.rotate-2 {
            transform: rotate(2deg);
        }
        
        .polaroid-card.-rotate-1 {
            transform: rotate(-1deg);
        }
        
        .polaroid-card.-rotate-2 {
            transform: rotate(-2deg);
        }
        
        .polaroid-card:hover {
            transform: rotate(0deg) translateY(-4px);
        }
        
        /* Stamp Effect */
        .stamp {
            position: absolute;
            bottom: 8px;
            right: 12px;
            font-family: 'Courier Prime', monospace;
            font-size: 10px;
            text-transform: uppercase;
            color: var(--color-secondary);
            opacity: 0.6;
            transform: rotate(-5deg);
            border: 1px solid var(--color-secondary);
            padding: 2px 6px;
            background: rgba(234, 179, 8, 0.1);
        }
        
        /* Buttons - Vintage Style */
        .btn-vintage {
            display: inline-block;
            padding: 10px 24px;
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: transparent;
            border: 2px solid var(--color-primary);
            color: var(--color-primary);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 14px;
        }
        
        .btn-vintage:hover {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }
        
        .btn-vintage-primary {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
        }
        
        .btn-vintage-primary:hover {
            background: transparent;
            color: var(--color-primary);
        }
        
        .btn-vintage-secondary {
            border-color: var(--color-secondary);
            color: var(--color-secondary);
        }
        
        .btn-vintage-secondary:hover {
            background: var(--color-secondary);
            color: var(--color-text);
        }
        
        /* Form Elements - Vintage */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-family: 'Courier Prime', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
            margin-bottom: 6px;
            color: var(--color-text-secondary);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            font-family: 'Merriweather', serif;
            background: var(--color-surface);
            border: 1px solid var(--color-outline);
            color: var(--color-text);
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(29, 78, 216, 0.2);
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        /* Alert Messages */
        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-left: 4px solid;
            font-family: 'Courier Prime', monospace;
            position: relative;
        }
        
        .alert-success {
            background: rgba(5, 150, 105, 0.1);
            border-left-color: var(--color-success);
        }
        
        .alert-error, .alert-danger {
            background: rgba(220, 38, 38, 0.1);
            border-left-color: var(--color-error);
        }
        
        .alert-warning {
            background: rgba(234, 179, 8, 0.1);
            border-left-color: var(--color-secondary);
        }
        
        .alert-info {
            background: rgba(29, 78, 216, 0.1);
            border-left-color: var(--color-primary);
        }
        
        /* Navigation */
        .navbar {
            background: var(--color-surface);
            border-bottom: 3px solid var(--color-primary);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .nav-link {
            font-family: 'Courier Prime', monospace;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--color-text);
            text-decoration: none;
            padding: 8px 16px;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        /* Desktop nav container - default visible on larger screens */
        .nav-links {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .nav-link:hover {
            color: var(--color-primary);
            background: rgba(29, 78, 216, 0.05);
        }
        
        .nav-link.active {
            color: var(--color-primary);
            border-bottom: 2px solid var(--color-primary);
        }
        
        /* Footer */
        .footer {
            background: var(--color-surface);
            border-top: 1px solid var(--color-outline);
            padding: 40px 0 20px;
            margin-top: 60px;
            position: relative;
        }
        
        /* Cards Grid */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        
        /* Progress Bar */
        .progress-bar-vintage {
            height: 8px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--color-primary);
            width: 0%;
            transition: width 0.5s ease;
        }
        
        /* Countdown Timer */
        .countdown-timer {
            font-family: 'Courier Prime', monospace;
            font-size: 32px;
            font-weight: 700;
            color: var(--color-primary);
        }
        
        /* Dotted Divider */
        .dotted-divider {
            border: none;
            border-top: 2px dotted var(--color-outline);
            margin: 20px 0;
            opacity: 0.5;
        }
        
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .container {
                padding: 0 16px;
            }
            
            .card-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .card-grid {
                grid-template-columns: 1fr;
            }
            
            .navbar {
                padding: 12px 0;
            }
            
            .nav-link {
                padding: 6px 12px;
                font-size: 11px;
            }
            
            .countdown-timer {
                font-size: 24px;
            }
            
            h1, h2 {
                font-size: 24px !important;
            }
            
            h3, h4 {
                font-size: 18px !important;
            }
            
            .form-control {
                font-size: 16px;
            }
            
            .btn-vintage {
                padding: 8px 16px;
                font-size: 12px;
            }
        }
        
        @media (max-width: 600px) {
            .navbar {
                padding: 8px 0;
            }
            
            .navbar .container {
                padding: 0 12px;
            }
            
            .navbar > .container > div {
                flex-direction: column;
                gap: 12px;
            }
            
            .nav-links {
                display: none;
                flex-direction: column;
                width: 100%;
                gap: 0;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .nav-links .nav-link {
                padding: 8px 12px;
                font-size: 11px;
                border-bottom: 1px solid var(--color-outline);
            }
            
            .hamburger-toggle {
                display: block !important;
                background: transparent;
                border: none;
                color: var(--color-text);
                font-size: 24px;
                cursor: pointer;
                padding: 6px 12px;
            }
            
            .hamburger-toggle.active {
                opacity: 0.7;
            }
            
            .hamburger-toggle.active::after {
                content: ' ×';
                font-size: 20px;
            }
            
            .nav-link {
                padding: 4px 8px;
                font-size: 10px;
            }
            
            .card-grid {
                gap: 16px;
            }
            
            .polaroid-card {
                padding: 8px 8px 16px 8px;
            }
            
            .countdown-timer {
                font-size: 18px;
            }
            
            .form-control {
                padding: 8px 10px;
            }
            
            .btn-vintage {
                padding: 6px 12px;
                font-size: 11px;
            }
            
            .stamp {
                font-size: 8px;
                padding: 1px 4px;
            }
            
            .container {
                padding: 0 12px;
            }
            
            h1 {
                font-size: 18px !important;
            }
        }
        
        .hamburger-toggle {
            display: none;
        }
        
        /* Loading Spinner (Traditional) */
        .spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid var(--color-outline);
            border-top-color: var(--color-primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Confetti Canvas (Birthday) */
        #confetti-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
        }

        
        
        main {
            min-height: calc(100vh - 200px);
        }
        /* Remove underlines from wish cards and all text decorations */
.polaroid-card,
.polaroid-card p,
.polaroid-card span,
.polaroid-card div,
.polaroid-card h1,
.polaroid-card h2,
.polaroid-card h3,
.polaroid-card h4,
.polaroid-card h5,
.polaroid-card h6,
.ledger-lines,
.ledger-lines p,
.ledger-lines span,
.ledger-lines div,
.ruled-lines,
.ruled-lines p,
.ruled-lines span,
.ruled-lines span,
.ruled-lines div {
    text-decoration: none !important;
    text-underline: none !important;
    text-overline: none !important;
}

/* Keep buttons looking normal */
.btn-vintage,
.btn-vintage * {
    text-decoration: none !important;
}

/* Keep links in cards with proper styling */
.polaroid-card a {
    text-decoration: none !important;
}

.polaroid-card a:hover {
    text-decoration: none !important;
}
    </style>
    
    <?= csrf_meta() ?>
</head>
<body class="paper-bg">
    <div class="grain-overlay"></div>
    
    <!-- Confetti Canvas -->
    <canvas id="confetti-canvas"></canvas>
    
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; width: 100%;">
                <div>
                    <a href="/" style="text-decoration: none;">
    <h1 style="font-size: 20px; letter-spacing: 2px; margin: 0; color: var(--color-text);">
        <?= get_association_name() ?>
    </h1>
    <p class="font-mono-courier" style="font-size: 10px; margin: 4px 0 0; opacity: 0.7; color: var(--color-text);">
        Est. <?= date('Y') ?>
    </p>
    <hr>
</a>
                </div>
                
                <button class="hamburger-toggle" onclick="toggleNavMenu()" title="Toggle Menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                
                <div id="nav-menu" class="nav-links">
                    <a href="/" class="nav-link">Home</a>
                    <a href="/birthday/calendar" class="nav-link">Birthday Calendar</a>
                    <a href="/birthday/wall" class="nav-link">Birthday Wall</a>
                    <a href="/members/directory" class="nav-link">Directory</a>
                    <a href="/events" class="nav-link">Events</a>
                    <a href="/news" class="nav-link">News</a>
                    <a href="/gallery" class="nav-link">Gallery</a>
                    <a href="/about" class="nav-link">About</a>
                    <a href="/contact" class="nav-link">Contact</a>
                </div>
                
                <div style="display: flex; gap: 12px; align-items: center;">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <div class="dropdown" style="position: relative;">
                            <button class="btn-vintage" style="padding: 6px 16px;" onclick="toggleDropdown()">
                                <?= session()->get('firstName') ?>
                                <span class="material-symbols-outlined" style="font-size: 16px; vertical-align: middle;">expand_more</span>
                            </button>
                            <div id="userDropdown" style="display: none; position: absolute; top: 100%; right: 0; background: var(--color-surface); border: 1px solid var(--color-outline); min-width: 180px; z-index: 1000; margin-top: 8px;">
                                <a href="/members/profile" style="display: block; padding: 10px 16px; text-decoration: none; color: var(--color-text);">My Profile</a>
                                <?php if (session()->get('isAdmin')): ?>
                                    <a href="/admin/dashboard" style="display: block; padding: 10px 16px; text-decoration: none; color: var(--color-text);">Admin Panel</a>
                                <?php endif; ?>
                                <hr class="dotted-divider" style="margin: 4px 0;">
                                <a href="/auth/logout" style="display: block; padding: 10px 16px; text-decoration: none; color: var(--color-error);">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/auth/login" class="btn-vintage" style="padding: 6px 16px;">Login</a>
                        <a href="/auth/register" class="btn-vintage btn-vintage-primary" style="padding: 6px 16px;">Register</a>
                    <?php endif; ?>
                    
                    <!-- Theme Toggle -->
                    <button id="theme-toggle" class="btn-vintage" style="padding: 6px 12px;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">dark_mode</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <main>
        <?= $this->renderSection('content') ?>
        <?= $this->include('layouts/footer') ?>