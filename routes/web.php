<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return User::withTrashed()->exists()
        ? redirect()->route('dashboard')
        : redirect()->route('first-admin.create');
});

Route::get('/setup', [AuthController::class, 'createFirstAdmin'])->name('first-admin.create');
Route::post('/setup', [AuthController::class, 'storeFirstAdmin'])
    ->middleware('throttle:5,1')
    ->name('first-admin.store');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', function () {
        return Auth::user()->isAdmin()
            ? redirect()->route('users.index')
            : redirect()->route('profile');
    })->name('dashboard');
    Route::get('/profile', function () {
        $user = Auth::user()->load('role');

        return view('users.show', compact('user'));
    })->name('profile');

    Route::middleware('admin')->group(function () {
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
        Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
        Route::resource('users', UserController::class);
    });
});
