@extends('layouts.app')

@section('title', 'Iniciar sessão | Gestão de Utilizadores')

@section('content')
<div class="row g-0 auth-wrap align-items-center">
    <div class="col-md-6">
        <section class="auth-art">
            <div class="auth-art-content">
                <span class="badge rounded-pill text-bg-light px-3 py-2"><i class="bi bi-shield-check me-1"></i> Acesso protegido</span>
                <h1>Bem-vindo de volta.</h1>
                <p>Entre no painel para gerir utilizadores, cargos e os seus dados de perfil.</p>
            </div>
            <div class="auth-art-foot small text-white-50">A sua conta e as suas informações estão protegidas.</div>
        </section>
    </div>
    <div class="col-md-6">
        <section class="auth-panel">
            <div class="auth-form">
                <div class="auth-icon"><i class="bi bi-person-lock"></i></div>
                <p class="text-uppercase small fw-bold text-primary mb-2" style="letter-spacing: .13em;">Área de acesso</p>
                <h2>Iniciar sessão</h2>
                <p class="text-secondary mb-4">Insira as credenciais da sua conta para continuar.</p>

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Endereço de e-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="username" placeholder="nome@exemplo.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Palavra-passe</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" placeholder="A sua palavra-passe" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Entrar <i class="bi bi-arrow-right ms-1"></i></button>
                </form>
                <div class="mt-4 pt-3 border-top small text-secondary"><i class="bi bi-info-circle me-1"></i> Não tem acesso? Contacte o administrador do sistema.</div>
            </div>
        </section>
    </div>
</div>
@endsection
