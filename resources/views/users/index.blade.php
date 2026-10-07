@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center g-2">
            <div class="col-md-3">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-people-fill me-2"></i>Utilizadores
                </h5>
            </div>
            
            {{-- Formulário de Pesquisa --}}
            <div class="col-md-5">
                <form action="{{ route('users.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Pesquisar por nome ou e-mail..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit" title="Pesquisar">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" title="Limpar pesquisa">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
                </form>
            </div>

            {{-- Botões de Acção (Lixeira e Novo Utilizador) --}}
            <div class="col-md-4 text-md-end d-flex justify-content-md-end gap-2">
                <a href="{{ route('users.trashed') }}" class="btn btn-outline-danger" title="Ver utilizadores na lixeira">
                    <i class="bi bi-trash3-fill me-1"></i> Lixeira
                </a>
                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus-fill me-1"></i> Novo Utilizador
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
                        <th>Cargo / Perfil</th>
                        <th>Data de Registo</th>
                        <th class="text-end pe-3">Acções</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-3 fw-bold text-secondary">#{{ $user->id }}</td>
                            
                            {{-- Coluna do Avatar / Foto de Perfil --}}
                            <td>
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D6EFD&color=fff" alt="{{ $user->name }}" class="rounded-circle" width="40" height="40">
                                @endif
                            </td>

                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role)
                                    <span class="badge bg-primary">{{ $user->role->name }}</span>
                                @else
                                    <span class="badge bg-secondary">Sem cargo</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            
                            <td class="text-end pe-3">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info text-white me-1" title="Ver Detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-warning me-1" title="Editar" data-bs-toggle="modal" data-bs-target="#edit-user-{{ $user->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Mover para a lixeira" data-bs-toggle="modal" data-bs-target="#delete-user-{{ $user->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                @if(request('search'))
                                    Nenhum utilizador encontrado para a pesquisa "{{ request('search') }}".
                                @else
                                    Nenhum utilizador registado.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @foreach($users as $user)
            @include('users.partials.actions-modals', ['user' => $user, 'roles' => $roles])
        @endforeach
    </div>
    
    @if($users->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
