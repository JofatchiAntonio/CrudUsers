<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#172554">
    <title>@yield('title', 'Gestão de Utilizadores')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --ink: #172554; --muted: #64748b; --line: #e2e8f0; --brand: #2563eb; }
        body.app-body { min-height: 100vh; color: var(--ink); background: radial-gradient(ellipse at 12% 0%, #e0eaff 0, transparent 35%), #f5f7fb; }
        .app-navbar { min-height: 82px; background: linear-gradient(112deg, #14234b, #1e3a70 65%, #1e40af); box-shadow: 0 8px 30px #17255422; }
        .brand-mark { display: grid; width: 42px; height: 42px; place-items: center; border: 1px solid #ffffff45; border-radius: 13px; background: #ffffff18; font-size: 1.1rem; }
        .brand-caption { margin-top: 2px; color: #ffffff83; font-size: .58rem; letter-spacing: .14em; }
        .btn-logout { color: #fff; border: 1px solid #ffffff50; }
        .btn-logout:hover { color: var(--ink); background: #fff; }
        .app-main { max-width: 1280px; padding-top: 2.25rem; }
        .card { overflow: hidden; border: 1px solid #e7ebf2 !important; border-radius: 16px; box-shadow: 0 12px 34px #1725540b !important; }
        .card-header, .card-footer { border-color: #edf0f5 !important; }
        .table thead th { padding-top: .9rem; padding-bottom: .9rem; color: #64748b; font-size: .72rem; letter-spacing: .055em; text-transform: uppercase; }
        .table tbody td { padding-top: .85rem; padding-bottom: .85rem; }
        .form-control, .form-select { min-height: 44px; border-color: #dbe2ec; border-radius: 9px; }
        .form-control:focus, .form-select:focus { border-color: #7aa7ff; box-shadow: 0 0 0 .22rem #2563eb18; }
        .btn { border-radius: 9px; font-weight: 600; }
        .user-action-modal .modal-content { overflow: hidden; border: 1px solid #e7ebf2; border-radius: 18px; box-shadow: 0 24px 80px #0f172a30; }
        .modal.fade.user-action-modal .modal-dialog { transform: translateY(-16px) scale(.985); transition: transform .28s cubic-bezier(.2, .8, .2, 1); }
        .modal.show.user-action-modal .modal-dialog { transform: none; }
        .user-action-modal .modal-header, .user-action-modal .modal-footer { padding: 1.2rem 1.5rem; border-color: #edf0f5; }
        .user-action-modal .modal-body { padding: 1.5rem; }
        .modal-kicker { display: block; margin-bottom: .25rem; color: #64748b; font-size: .65rem; font-weight: 700; letter-spacing: .12em; }
        .delete-modal-icon { display: grid; width: 58px; height: 58px; place-items: center; margin: 0 auto; border-radius: 18px; color: #dc3545; background: #fff0f1; font-size: 1.35rem; }
        .auth-wrap { min-height: min(680px, calc(100vh - 160px)); }
        .auth-art { position: relative; display: flex; min-height: 460px; flex-direction: column; justify-content: space-between; overflow: hidden; padding: clamp(2rem, 5vw, 4rem); border-radius: 20px; color: #fff; background: linear-gradient(145deg, #172554 0%, #1d4ed8 72%, #4f46e5 100%); }
        .auth-art::before, .auth-art::after { position: absolute; width: 250px; height: 250px; border: 1px solid #ffffff25; border-radius: 50%; content: ''; }
        .auth-art::before { top: -95px; right: -72px; box-shadow: 0 0 0 28px #ffffff0c, 0 0 0 58px #ffffff08; }
        .auth-art::after { right: 12%; bottom: -195px; width: 330px; height: 330px; background: #ffffff09; }
        .auth-art-content, .auth-art-foot { position: relative; z-index: 1; max-width: 460px; }
        .auth-art h1 { margin: 1.4rem 0 1rem; font-size: clamp(2rem, 4vw, 3.25rem); font-weight: 750; letter-spacing: -.04em; }
        .auth-art p { color: #e0eaff; line-height: 1.7; }
        .auth-panel { display: flex; align-items: center; justify-content: center; padding: clamp(1.5rem, 4vw, 3.5rem); }
        .auth-form { width: 100%; max-width: 420px; }
        .auth-form h2 { font-size: 1.75rem; font-weight: 750; letter-spacing: -.03em; }
        .auth-form .form-label { color: #334155; font-size: .9rem; font-weight: 650; }
        .auth-icon { display: grid; width: 52px; height: 52px; place-items: center; margin-bottom: 1.25rem; border-radius: 15px; color: #1d4ed8; background: #eaf1ff; font-size: 1.3rem; }
        @media (max-width: 767.98px) { .app-main { padding-top: 1.25rem; } .auth-art { min-height: 270px; padding: 1.75rem; } .auth-art h1 { margin: .9rem 0 .55rem; font-size: 2rem; } .auth-art-foot { display: none; } .user-action-modal .modal-header, .user-action-modal .modal-footer { padding: 1rem; } .user-action-modal .modal-body { padding: 1rem; } }
        @media (prefers-reduced-motion: reduce) { .modal.fade.user-action-modal .modal-dialog { transition: none; } }
    </style>
</head>
<body class="app-body">

    <nav class="navbar navbar-expand-lg navbar-dark app-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ auth()->user()?->isAdmin() ? route('users.index') : (auth()->check() ? route('profile') : route('login')) }}">
                <span class="brand-mark"><i class="bi bi-people-fill"></i></span>
                <span>Gestão de Utilizadores<small class="brand-caption d-block">PAINEL DE ADMINISTRAÇÃO</small></span>
            </a>
            @auth
                <div class="d-flex align-items-center gap-3 ms-auto text-white">
                    <div class="text-end d-none d-sm-block">
                        <span class="d-block fw-semibold">{{ auth()->user()->name }}</span>
                        <small class="text-white-50">{{ auth()->user()->role?->name ?? 'User Normal' }}</small>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-logout btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Sair</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="container app-main mb-5">
        {{-- Mensagem de Sucesso --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-info shadow-sm" role="alert">{{ session('status') }}</div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
