@php($isEditingThisUser = (string) old('_modal_user_id') === (string) $user->id)

<div class="modal fade user-action-modal" id="edit-user-{{ $user->id }}" tabindex="-1" aria-labelledby="edit-user-title-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="_modal_user_id" value="{{ $user->id }}">

                <div class="modal-header">
                    <div>
                        <span class="modal-kicker">CONTA DO UTILIZADOR</span>
                        <h2 class="modal-title fs-5" id="edit-user-title-{{ $user->id }}">Editar {{ $user->name }}</h2>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    @if($user->avatar)
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto atual de {{ $user->name }}" class="rounded-circle border" width="64" height="64" style="object-fit: cover;">
                            <div>
                                <div class="fw-semibold">Foto atual</div>
                                <small class="text-secondary">Escolha outra imagem abaixo para substituir.</small>
                            </div>
                        </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit-name-{{ $user->id }}" class="form-label">Nome completo</label>
                            <input type="text" name="name" id="edit-name-{{ $user->id }}" value="{{ $isEditingThisUser ? old('name', $user->name) : $user->name }}" class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="edit-email-{{ $user->id }}" class="form-label">E-mail</label>
                            <input type="email" name="email" id="edit-email-{{ $user->id }}" value="{{ $isEditingThisUser ? old('email', $user->email) : $user->email }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="edit-role-{{ $user->id }}" class="form-label">Cargo / Perfil</label>
                            <select name="role_id" id="edit-role-{{ $user->id }}" class="form-select @error('role_id') is-invalid @enderror" required>
                                <option value="">Selecione um cargo</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected(($isEditingThisUser ? old('role_id', $user->role_id) : $user->role_id) == $role->id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="edit-avatar-{{ $user->id }}" class="form-label">Nova foto</label>
                            <input type="file" name="avatar" id="edit-avatar-{{ $user->id }}" accept="image/jpeg,image/png,image/gif" class="form-control @error('avatar') is-invalid @enderror">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">JPG, PNG ou GIF, até 2 MB.</div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <p class="small text-secondary mb-3">Deixe a palavra-passe em branco para a manter.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit-password-{{ $user->id }}" class="form-label">Nova palavra-passe</label>
                            <input type="password" name="password" id="edit-password-{{ $user->id }}" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="edit-password-confirmation-{{ $user->id }}" class="form-label">Confirmar palavra-passe</label>
                            <input type="password" name="password_confirmation" id="edit-password-confirmation-{{ $user->id }}" class="form-control" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Guardar alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade user-action-modal" id="delete-user-{{ $user->id }}" tabindex="-1" aria-labelledby="delete-user-title-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body pt-1 text-center">
                <div class="delete-modal-icon"><i class="bi bi-trash3"></i></div>
                <h2 class="modal-title fs-5 mt-3" id="delete-user-title-{{ $user->id }}">Mover para a lixeira?</h2>
                <p class="text-secondary small mt-2 mb-0">O utilizador <strong>{{ $user->name }}</strong> poderá ser restaurado mais tarde.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pt-3">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i>Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@if($errors->any() && $isEditingThisUser)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('edit-user-{{ $user->id }}');
            if (modal) {
                bootstrap.Modal.getOrCreateInstance(modal).show();
            }
        });
    </script>
@endif
