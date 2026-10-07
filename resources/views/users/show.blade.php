@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-person-badge me-2"></i>Detalhes do Utilizador</h5>
                <a href="{{ auth()->user()->isAdmin() ? route('users.index') : route('profile') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block">ID do Utilizador</label>
                    <span class="fs-6 fw-bold">#{{ $user->id }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Nome</label>
                    <span class="fs-5 fw-semibold">{{ $user->name }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">E-mail</label>
                    <span class="fs-6">{{ $user->email }}</span>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="text-muted small d-block">Criado em</label>
                        <span class="small">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="text-muted small d-block">Última Atualização</label>
                        <span class="small">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            @if(auth()->user()->isAdmin())
                <div class="card-footer bg-white py-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#edit-user-{{ $user->id }}">
                        <i class="bi bi-pencil-square me-1"></i> Editar
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-user-{{ $user->id }}">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@if(auth()->user()->isAdmin())
    @include('users.partials.actions-modals', ['user' => $user, 'roles' => $roles])
@endif
@endsection
