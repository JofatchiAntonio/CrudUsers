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

            <div class="col-md-4 text-md-end d-flex justify-content-md-end gap-2">
                <a href="{{ route('users.trashed') }}" class="btn btn-outline-danger" title="Ver utilizadores na lixeira">
                    <i class="bi bi-trash3-fill me-1"></i> Lixeira
                </a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="bi bi-person-plus-fill me-1"></i> Novo Utilizador
                </button>
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

<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createUserForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Adicionar Novo Utilizador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div id="createUserFeedback" class="alert alert-danger d-none" role="alert" aria-live="polite"></div>

                    <div class="mb-3">
                        <label for="createUserName" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control" id="createUserName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="createUserEmail" class="form-label">Endereço de E-mail</label>
                        <input type="email" class="form-control" id="createUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="createUserAvatar" class="form-label">Foto do Utilizador</label>
                        <input type="file" class="form-control" id="createUserAvatar" name="avatar" accept="image/jpeg,image/png,image/gif">
                        <div class="form-text">Formatos permitidos: JPG, PNG ou GIF. Tamanho máximo: 2 MB.</div>
                    </div>
                    <div class="mb-3">
                        <label for="createUserRole" class="form-label">Cargo / Perfil</label>
                        <select class="form-select" id="createUserRole" name="role_id" required>
                            <option value="">Selecione um cargo</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="createUserPassword" class="form-label">Palavra-passe</label>
                            <input type="password" class="form-control" id="createUserPassword" name="password" minlength="8" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="createUserPasswordConfirmation" class="form-label">Confirmar Palavra-passe</label>
                            <input type="password" class="form-control" id="createUserPasswordConfirmation" name="password_confirmation" minlength="8" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success" id="createUserSubmit">
                        <i class="bi bi-save me-1"></i> Guardar Utilizador
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const createUserForm = document.getElementById('createUserForm');
    const createUserModal = document.getElementById('createUserModal');
    const createUserFeedback = document.getElementById('createUserFeedback');
    const createUserSubmit = document.getElementById('createUserSubmit');
    const createUserSubmitContent = createUserSubmit.innerHTML;

    function clearCreateUserErrors() {
        createUserForm.querySelectorAll('.is-invalid').forEach((field) => field.classList.remove('is-invalid'));
        createUserForm.querySelectorAll('[data-ajax-error]').forEach((message) => message.remove());
        createUserFeedback.textContent = '';
        createUserFeedback.classList.add('d-none');
    }

    function showCreateUserFeedback(message) {
        createUserFeedback.textContent = message;
        createUserFeedback.classList.remove('d-none');
    }

    function showCreateUserSuccess() {
        const main = document.querySelector('.app-main');
        const alert = document.createElement('div');
        const closeButton = document.createElement('button');

        alert.className = 'alert alert-success alert-dismissible fade show shadow-sm';
        alert.setAttribute('role', 'alert');
        alert.append('Utilizador criado com sucesso!');
        closeButton.type = 'button';
        closeButton.className = 'btn-close';
        closeButton.setAttribute('data-bs-dismiss', 'alert');
        closeButton.setAttribute('aria-label', 'Fechar');
        alert.append(closeButton);
        main.prepend(alert);
    }

    try {
        const storedSuccessMessage = sessionStorage.getItem('user-create-success');
        if (storedSuccessMessage) {
            sessionStorage.removeItem('user-create-success');
            showCreateUserSuccess();
        }
    } catch (error) {
        console.error('Não foi possível recuperar a mensagem de sucesso.', error);
    }

    createUserForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearCreateUserErrors();
        createUserSubmit.disabled = true;
        createUserSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>A guardar...';

        try {
            const response = await fetch(createUserForm.action, {
                method: createUserForm.method,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(createUserForm),
            });
            const contentType = response.headers.get('content-type') || '';
            const payload = contentType.includes('application/json') ? await response.json() : {};

            if (!response.ok) {
                if (response.status === 422 && payload.errors) {
                    Object.entries(payload.errors).forEach(([name, messages]) => {
                        const field = createUserForm.elements.namedItem(name);
                        if (!(field instanceof HTMLElement)) {
                            return;
                        }

                        field.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback d-block';
                        feedback.dataset.ajaxError = 'true';
                        feedback.textContent = messages[0];
                        field.insertAdjacentElement('afterend', feedback);
                    });
                    showCreateUserFeedback('Verifique os campos assinalados e tente novamente.');
                    return;
                }

                showCreateUserFeedback(payload.message || 'Não foi possível criar o utilizador. Tente novamente.');
                return;
            }

            const userDetailsPathPrefix = `${window.location.pathname.replace(/\/$/, '')}/`;
            const redirectedToUserDetails = response.redirected
                && new URL(response.url).pathname.startsWith(userDetailsPathPrefix);

            if (!redirectedToUserDetails && payload.success !== true) {
                showCreateUserFeedback('O servidor devolveu uma resposta inesperada. Tente novamente.');
                return;
            }

            try {
                sessionStorage.setItem('user-create-success', 'true');
            } catch (error) {
                console.error('Não foi possível guardar a mensagem de sucesso.', error);
            }
            bootstrap.Modal.getOrCreateInstance(createUserModal).hide();
            window.location.reload();
        } catch (error) {
            console.error('Falha ao criar o utilizador.', error);
            showCreateUserFeedback('Não foi possível comunicar com o servidor. Verifique a ligação e tente novamente.');
        } finally {
            createUserSubmit.disabled = false;
            createUserSubmit.innerHTML = createUserSubmitContent;
        }
    });
</script>
@endsection
