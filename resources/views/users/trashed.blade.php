@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center g-2">
            <div class="col-md-6">
                <h5 class="mb-0 fw-bold text-danger">
                    <i class="bi bi-trash3-fill me-2"></i>Lixeira de Utilizadores
                </h5>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Voltar à Lista Principal
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Foto</th>
                        <th>Nome</th>
                        <th>E-mail</th>
                        <th>Data de Eliminação</th>
                        <th class="text-end pe-3">Acções</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-3 fw-bold text-secondary">#{{ $user->id }}</td>
                            
                            {{-- Foto / Avatar --}}
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle border opacity-75" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6C757D&color=fff" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                @endif
                            </td>

                            <td class="fw-semibold text-muted">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td class="text-danger small">{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                            
                            <td class="text-end pe-3">
                                {{-- Botão Restaurar --}}
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success me-1" title="Restaurar Utilizador">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restaurar
                                    </button>
                                </form>

                                {{-- Botão Eliminar Definitivamente --}}
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar definitivamente" data-bs-toggle="modal" data-bs-target="#force-delete-user-{{ $user->id }}">
                                    <i class="bi bi-x-circle me-1"></i> Apagar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2 text-secondary"></i>
                                A lixeira está vazia. Nenhum utilizador foi eliminado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($users as $user)
            <div class="modal fade user-action-modal" id="force-delete-user-{{ $user->id }}" tabindex="-1" aria-labelledby="force-delete-title-{{ $user->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body pt-1 text-center">
                            <div class="delete-modal-icon"><i class="bi bi-exclamation-triangle"></i></div>
                            <h2 class="modal-title fs-5 mt-3" id="force-delete-title-{{ $user->id }}">Eliminar permanentemente?</h2>
                            <p class="text-secondary small mt-2 mb-0"><strong>{{ $user->name }}</strong> e a respetiva foto serão apagados sem possibilidade de recuperação.</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center pt-3">
                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                            <form action="{{ route('users.force-delete', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i>Apagar definitivamente</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    @if($users->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection 
