@extends('layouts.app')

@section('title', 'Configuração inicial | Gestão de Utilizadores')

@section('content')
<div class="row g-0 auth-wrap align-items-center">
    <div class="col-md-6">
        <section class="auth-art">
            <div class="auth-art-content">
                <span class="badge rounded-pill text-bg-light px-3 py-2"><i class="bi bi-stars me-1"></i> Configuração inicial</span>
                <h1>O primeiro passo começa aqui.</h1>
                <p>Crie a conta de administrador principal. Esta conta terá acesso à gestão de utilizadores e cargos.</p>
            </div>
            <div class="auth-art-foot small text-white-50">Depois desta configuração, apenas um administrador poderá criar novas contas.</div>
        </section>
    </div>
    <div class="col-md-6">
        <section class="auth-panel">
            <div class="auth-form">
                <div class="auth-icon"><i class="bi bi-person-gear"></i></div>
                <p class="text-uppercase small fw-bold text-primary mb-2" style="letter-spacing: .13em;">Primeira conta</p>
                <h2>Criar administrador</h2>
                <p class="text-secondary mb-4">Esta configuração só pode ser feita uma vez.</p>

                <form action="{{ route('first-admin.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome completo</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" autocomplete="name" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Endereço de e-mail</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Palavra-passe</label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar palavra-passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Criar conta de administrador <i class="bi bi-arrow-right ms-1"></i></button>
                </form>
                <div class="mt-4 pt-3 border-top small text-secondary"><i class="bi bi-lock me-1"></i> O cadastro público ficará fechado após criar esta conta.</div>
            </div>
        </section>
    </div>
</div>
@endsection
