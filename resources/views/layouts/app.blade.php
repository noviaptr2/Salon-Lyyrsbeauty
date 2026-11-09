<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'LyyrsBeauty Salon')</title>

    <meta name="description"
          content="LyyrsBeauty Salon menyediakan layanan perawatan dan penataan rambut untuk penampilan yang lebih cantik dan percaya diri.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/figma-match.css') }}?v=1">

    <style>
        :root {
            --pink: #d96b91;
            --pink-dark: #b94d75;
            --pink-light: #fff0f5;
            --cream: #fffaf8;
            --text: #35262c;
            --muted: #796b71;
            --white: #ffffff;
            --border: #f0dfe6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            line-height: 1.7;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button, input, select, textarea {
            font: inherit;
        }

        .navbar {
            position: sticky; top: 0; z-index: 1000;
            background: rgba(255,250,248,.90);
            border-bottom: 1px solid rgba(240,223,230,.9);
            backdrop-filter: blur(18px);
            box-shadow: 0 8px 30px rgba(75,36,53,.04);
        }
        .nav-container {
            max-width: 1240px; margin: auto; padding: 14px 24px;
            display:flex; justify-content:space-between; align-items:center; gap:18px;
        }
        .brand { display:flex; align-items:center; gap:12px; min-width:0; }
        .brand img { width:49px;height:49px;object-fit:contain;border-radius:50%; }
        .brand-name { font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:var(--pink-dark);line-height:1.2; }
        .brand-caption { display:block;font-size:9px;letter-spacing:2.2px;color:var(--muted);text-transform:uppercase; }
        .nav-toggle {
            width:46px;height:46px;display:grid;place-items:center;border:1px solid var(--border);
            border-radius:15px;background:#fff;color:var(--pink-dark);cursor:pointer;
            box-shadow:0 5px 16px rgba(75,36,53,.06);transition:transform .25s,background .25s;
        }
        .nav-toggle:hover { transform:translateY(-2px);background:var(--pink-light); }
        .hamburger { display:grid;gap:5px; }
        .hamburger span { width:19px;height:2px;background:currentColor;border-radius:4px;transition:transform .25s,opacity .25s; }
        .nav-toggle[aria-expanded="true"] .hamburger span:first-child { transform:translateY(7px) rotate(45deg); }
        .nav-toggle[aria-expanded="true"] .hamburger span:nth-child(2) { opacity:0; }
        .nav-toggle[aria-expanded="true"] .hamburger span:last-child { transform:translateY(-7px) rotate(-45deg); }
        .nav-links {
            position:absolute;right:max(24px,calc((100vw - 1192px)/2));top:calc(100% + 10px);
            width:min(320px,calc(100vw - 32px));padding:12px;display:flex;flex-direction:column;align-items:stretch;gap:5px;
            background:rgba(255,255,255,.97);border:1px solid var(--border);border-radius:22px;
            box-shadow:0 24px 70px rgba(69,31,48,.16);opacity:0;visibility:hidden;transform:translateY(-10px) scale(.98);
            transform-origin:top right;transition:opacity .22s ease,transform .22s ease,visibility .22s;
        }
        .nav-links.open { opacity:1;visibility:visible;transform:translateY(0) scale(1); }
        .nav-links > a { padding:12px 14px;border-radius:12px;font-size:14px;font-weight:650;transition:background .2s,color .2s,transform .2s; }
        .nav-links > a:hover { color:var(--pink-dark);background:var(--pink-light);transform:translateX(3px); }
        .nav-links form { padding:4px; }
        .nav-links form .btn { width:100%; }
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 12px 23px;
            border-radius: 30px;
            border: none;
            background: var(--pink);
            color: white;
            font-weight: 700;
            cursor: pointer;
            transition: .25s;
        }

        .btn:hover {
            background: var(--pink-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--pink-dark);
            border: 1px solid var(--pink);
        }

        .btn-outline:hover {
            background: var(--pink-light);
            color: var(--pink-dark);
        }

        .container {
            max-width: 1180px;
            margin: auto;
            padding: 65px 24px;
        }

        .section-label {
            color: var(--pink-dark);
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 2px;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.25;
            margin-bottom: 14px;
        }

        .section-description {
            color: var(--muted);
            max-width: 620px;
            margin-bottom: 32px;
        }

        .footer {
            background: #39252f;
            color: white;
            padding: 38px 24px;
        }

        .footer-inner {
            max-width: 1180px;
            margin: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer p {
            color: #e9dce2;
            font-size: 13px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 700;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--pink);
            box-shadow: 0 0 0 3px rgba(217, 107, 145, .12);
        }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #e8f8ed;
            color: #216c3a;
        }

        .alert-error {
            background: #fff0f0;
            color: #a22b36;
        }

        .admin-shortcut { display:flex;align-items:center;gap:8px;padding:10px 13px;border-radius:13px;background:#fff3f7;color:var(--pink-dark);font-size:12px;font-weight:800; }
        .admin-shortcut:hover { background:#ffe5ef; }
        .dashboard-hero { position:relative;overflow:hidden;background:linear-gradient(125deg,#482c3a 0%,#8d4564 58%,#d96b91 100%);color:#fff;border-radius:28px;padding:32px;box-shadow:0 20px 45px rgba(137,60,94,.18);animation:riseIn .65s ease both; }
        .dashboard-hero:after { content:"";position:absolute;width:260px;height:260px;border:1px solid rgba(255,255,255,.18);border-radius:50%;right:-60px;top:-110px;box-shadow:0 0 0 28px rgba(255,255,255,.04),0 0 0 58px rgba(255,255,255,.035); }
        .stat-card { position:relative;overflow:hidden;background:#fff;border:1px solid var(--border);border-radius:22px;padding:23px;box-shadow:0 8px 24px rgba(71,37,51,.045);transition:transform .25s,box-shadow .25s;animation:riseIn .6s ease both; }
        .stat-card:hover { transform:translateY(-6px);box-shadow:0 18px 38px rgba(71,37,51,.10); }
        .stat-card .stat-icon { width:43px;height:43px;display:grid;place-items:center;border-radius:14px;background:var(--pink-light);font-size:21px;margin-bottom:17px; }
        .stat-value { font-size:clamp(27px,3vw,36px);font-weight:800;letter-spacing:-1px;color:var(--pink-dark);line-height:1.2; }
        .dashboard-section { background:#fff;border:1px solid var(--border);border-radius:24px;padding:24px;box-shadow:0 8px 26px rgba(71,37,51,.035);animation:riseIn .7s ease both; }
        @keyframes riseIn { from {opacity:0;transform:translateY(14px)} to {opacity:1;transform:translateY(0)} }
        @media (prefers-reduced-motion: reduce) { *,*:before,*:after { animation:none!important;transition:none!important;scroll-behavior:auto!important; } }
        @media (max-width: 760px) {
            .nav-container { padding:12px 18px; }
            .nav-links { right:16px; }
            .container { padding:38px 16px; }
            .brand-name { font-size:20px; }
            .dashboard-hero { padding:25px 22px;border-radius:22px; }
            .dashboard-section { padding:17px; }
        }
    </style>

    @stack('styles')
</head>

<body>

<header class="navbar">
    <div class="nav-container">

        <a href="{{ url('/') }}" class="brand">
            <img
                src="{{ asset('images/logo-salon.png') }}"
                alt="Logo LyyrsBeauty"
                onerror="this.style.display='none'"
            >

            <div>
                <div class="brand-name">LyyrsBeauty</div>
                <span class="brand-caption">Beauty & Hair Studio</span>
            </div>
        </a>

        <button class="nav-toggle" type="button" aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="site-menu">
            <span class="hamburger"><span></span><span></span><span></span></span>
        </button>
        <nav class="nav-links" id="site-menu" aria-label="Navigasi utama">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('layanan.index') }}">Layanan</a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    <a href="{{ route('admin.layanan.index') }}">Kelola Layanan</a>
                    <a href="{{ route('antrean.index') }}">Data Antrean</a>
                @else
                    <a href="{{ route('antrean.create') }}">Daftar Antrean</a>
                    <a href="{{ route('antrean.index') }}">Antrean Saya</a>
                @endif
                <a href="{{ route('profile.edit') }}">Profil ({{ auth()->user()->name }})</a>
                <form action="{{ route('logout') }}" method="POST">@csrf
                    <button class="btn btn-outline" type="submit" style="padding:10px 16px">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}" class="btn">Daftar Akun</a>
            @endauth
        </nav>

    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="footer">
    <div class="footer-inner">
        <div>
            <h3 style="font-family: 'Playfair Display', serif;">
                LyyrsBeauty
            </h3>
            <p>Beauty starts with taking care of yourself.</p>
        </div>

        <p>
            &copy; {{ date('Y') }} LyyrsBeauty Salon.
            All rights reserved.
        </p>
    </div>
</footer>

@stack('scripts')

<script>
(() => {
    const toggle = document.querySelector('.nav-toggle');
    const menu = document.querySelector('#site-menu');
    if (!toggle || !menu) return;
    toggle.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!open));
        menu.classList.toggle('open', !open);
    });
    document.addEventListener('click', event => {
        if (!menu.contains(event.target) && !toggle.contains(event.target)) {
            toggle.setAttribute('aria-expanded', 'false');
            menu.classList.remove('open');
        }
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            toggle.setAttribute('aria-expanded', 'false');
            menu.classList.remove('open');
        }
    });
})();
</script>
</body>
</html>
