<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (! User::withTrashed()->exists()) {
            return redirect()->route('first-admin.create');
        }

        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function createFirstAdmin(): View|RedirectResponse
    {
        if (User::withTrashed()->exists()) {
            return Auth::check()
                ? redirect()->route('dashboard')
                : redirect()->route('login');
        }

        return view('auth.first-admin');
    }

    public function storeFirstAdmin(Request $request): RedirectResponse
    {
        if (User::withTrashed()->exists()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $admin = DB::transaction(function () use ($validated): User {
            if (User::withTrashed()->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'A configuração inicial já foi concluída. Inicie sessão.',
                ]);
            }

            $adminRole = Role::firstOrCreate(['name' => 'User Admin']);
            $normalRole = Role::firstOrCreate(['name' => 'User Normal']);

            Role::query()->whereNotIn('id', [$adminRole->id, $normalRole->id])->delete();

            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role_id' => $adminRole->id,
            ]);

            return $admin;
        });

        Auth::login($admin);
        $request->session()->regenerate();

        return redirect()->route('users.index')->with('success', 'Conta de administrador criada.');
    }

    public function store(Request $request): RedirectResponse
    {
        if (! User::withTrashed()->exists()) {
            return redirect()->route('first-admin.create');
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'As credenciais fornecidas não são válidas.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return $request->user()->isAdmin()
            ? redirect()->route('users.index')
            : redirect()->route('profile');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sessão terminada com sucesso.');
    }
}
