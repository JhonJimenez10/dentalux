<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    private function checkAdmin(): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if(!$user || !$user->isAdmin()){
            abort(403, 'Solo el administrador puede gestionar cuentas bancarias.');
        }
    }

    public function index(): View
    {
        $accounts = BankAccount::orderBy('order')->get();
        return view('bank-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        $this->checkAdmin();
        return view('bank-accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_type'   => 'required|string',
            'account_number' => 'required|string|max:50',
            'owner_name'     => 'required|string|max:100',
            'owner_id'       => 'nullable|string|max:20',
            'phone'          => 'nullable|string|max:20',
            'active'         => 'nullable|boolean',
        ]);

        $data['active'] = $request->boolean('active', true);
        $data['order']  = BankAccount::max('order') + 1;

        // Si eligió "Otro" banco, usar el campo de texto
        if($data['bank_name'] === 'Otro'){
            $other = $request->input('bank_name_other', '');
            $data['bank_name'] = !empty($other) ? $other : 'Otro';
        }

        BankAccount::create($data);

        return redirect()
            ->route('bank-accounts.index')
            ->with('success', 'Cuenta bancaria agregada correctamente.');
    }

    public function edit(BankAccount $bankAccount): View
    {
        $this->checkAdmin();
        return view('bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount): RedirectResponse
    {
        $this->checkAdmin();

        $data = $request->validate([
            'bank_name'      => 'required|string|max:100',
            'account_type'   => 'required|string',
            'account_number' => 'required|string|max:50',
            'owner_name'     => 'required|string|max:100',
            'owner_id'       => 'nullable|string|max:20',
            'phone'          => 'nullable|string|max:20',
            'active'         => 'nullable|boolean',
            'order'          => 'nullable|integer|min:0',
        ]);

        $data['active'] = $request->boolean('active');
        $bankAccount->update($data);

        return redirect()
            ->route('bank-accounts.index')
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        $this->checkAdmin();
        $bankAccount->delete();

        return redirect()
            ->route('bank-accounts.index')
            ->with('success', 'Cuenta eliminada.');
    }
}