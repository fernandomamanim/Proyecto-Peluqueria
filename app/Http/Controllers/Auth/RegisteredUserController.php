<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'primer_apellido' => ['required', 'string', 'max:80'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:120', 'unique:usuarios,correo'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $rolCliente = Role::where('nombre', 'Cliente')->firstOrFail();

        $usuario = Usuario::create([
            'rol_id' => $rolCliente->id,
            'nombre' => $request->nombre,
            'primer_apellido' => $request->primer_apellido,
            'correo' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($usuario));

        Auth::login($usuario);

        return redirect(route('cliente.dashboard', absolute: false));
    }
}