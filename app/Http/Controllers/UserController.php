<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ─── Verificar que sea admin ──────────────────────────────
    private function checkAdmin(): void
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
    }

    // ─── Lista de usuarios ────────────────────────────────────
    public function index(): View
    {
        $this->checkAdmin();
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    // ─── Formulario crear ─────────────────────────────────────
    public function create(): View
    {
        $this->checkAdmin();
        return view('users.create');
    }

    // ─── Guardar usuario ──────────────────────────────────────
    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'role'                  => 'required|in:admin,dentist,receptionist',
            'specialty'             => 'nullable|string|max:100',
            'phone'                 => 'nullable|string|max:20',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['active']   = true;

        User::create($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    // ─── Formulario editar ────────────────────────────────────
    public function edit(User $user): View
    {
        $this->checkAdmin();
        return view('users.edit', compact('user'));
    }

    // ─── Actualizar usuario ───────────────────────────────────
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'role'                  => 'required|in:admin,dentist,receptionist',
            'specialty'             => 'nullable|string|max:100',
            'phone'                 => 'nullable|string|max:20',
            'active'                => 'nullable|boolean',
            'password'              => 'nullable|string|min:8|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['active'] = $request->boolean('active');
        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    // ─── Eliminar usuario ─────────────────────────────────────
    public function destroy(User $user): RedirectResponse
    {
        $this->checkAdmin();

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario eliminado.');
    }
}