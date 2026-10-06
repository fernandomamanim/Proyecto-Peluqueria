<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->orderBy('nombre')->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();

        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'rol_id' => ['required', 'exists:roles,id'],
            'nombre' => ['required', 'string', 'max:80'],
            'primer_apellido' => ['required', 'string', 'max:80'],
            'segundo_apellido' => ['nullable', 'string', 'max:80'],
            'correo' => ['required', 'email', 'max:120', 'unique:usuarios,correo'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
            'especialidad' => ['nullable', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'salario_mensual' => ['nullable', 'numeric', 'min:0'],
        ]);

        $especialidad = $datos['especialidad'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $salarioMensual = $datos['salario_mensual'] ?? 0;
        unset($datos['especialidad'], $datos['descripcion'], $datos['salario_mensual']);

        $datos['password'] = Hash::make($datos['password']);

        $usuario = Usuario::create($datos);

        if ($usuario->rol->nombre === 'Staff') {
            \App\Models\Peluquero::create([
                'usuario_id' => $usuario->id,
                'especialidad' => $especialidad,
                'descripcion' => $descripcion,
                'estado' => 'activo',
            ]);
        }
        if ($usuario->rol->nombre === 'Staff') {
            \App\Models\Peluquero::updateOrCreate(
                ['usuario_id' => $usuario->id],
                [
                    'especialidad' => $especialidad,
                    'descripcion' => $descripcion,
                    'salario_mensual' => $datos['salario_mensual'] ?? 0,
                    'estado' => 'activo',
                ]
            );
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Usuario $usuario)
    {
        $roles = Role::all();

        return view('admin.usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $datos = $request->validate([
            'rol_id' => ['required', 'exists:roles,id'],
            'nombre' => ['required', 'string', 'max:80'],
            'primer_apellido' => ['required', 'string', 'max:80'],
            'segundo_apellido' => ['nullable', 'string', 'max:80'],
            'correo' => ['required', 'email', 'max:120', 'unique:usuarios,correo,'.$usuario->id],
            'telefono' => ['nullable', 'string', 'max:20'],
            'estado' => ['required', 'in:activo,inactivo'],
            'especialidad' => ['nullable', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'salario_mensual' => ['nullable', 'numeric', 'min:0'],
        ]);
    
        $especialidad = $datos['especialidad'] ?? null;
        $descripcion = $datos['descripcion'] ?? null;
        $salarioMensual = $datos['salario_mensual'] ?? 0;
        unset($datos['especialidad'], $datos['descripcion'], $datos['salario_mensual']);
    
        $usuario->update($datos);
    
        if ($usuario->rol->nombre === 'Staff') {
            \App\Models\Peluquero::updateOrCreate(
                ['usuario_id' => $usuario->id],
                [
                    'especialidad' => $especialidad,
                    'descripcion' => $descripcion,
                    'salario_mensual' => $salarioMensual,
                    'estado' => $usuario->estado,
                ]
            );
        }
    
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Usuario $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes desactivarte a ti mismo.');
        }

        $usuario->update(['estado' => 'inactivo']);

        return back()->with('success', 'Usuario desactivado.');
    }
}