<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required' => 'Informe seu nome.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'email.required' => 'Informe seu email.',
            'email.email' => 'Informe um email válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'Informe sua senha.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => trim($data['name']),
                'email' => trim($data['email']),
                'password' => Hash::make($data['password']),
                'email_verified' => false,
                'disabled' => false,
                'roles' => null,
            ]);

            Subscription::query()->create([
                'user_id' => $user->id,
                'plan' => 'free',
                'status' => 'active',
            ]);

            DB::commit();

            event(new Registered($user));

            Auth::login($user, remember: false);

            return redirect()->route('dashboard.profile.edit');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Erro ao registrar usuário', [
                'email' => $request->input('email'),
                'message' => $e->getMessage(),
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'general' => 'Não foi possível concluir o cadastro.',
                ]);
        }
    }
}