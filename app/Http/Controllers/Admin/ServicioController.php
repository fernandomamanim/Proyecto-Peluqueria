<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaServicio;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::with('categoria')->orderBy('nombre')->get();

        return view('admin.servicios.index', compact('servicios'));
    }

    public function create()
    {
        $categorias = CategoriaServicio::where('estado', 'activo')->get();

        return view('admin.servicios.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'categoria_servicio_id' => ['nullable', 'exists:categorias_servicios,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion' => ['required', 'integer', 'min:5', 'max:480'],
            'imagen' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('servicios', 'public');
        }

        Servicio::create($datos);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio creado correctamente.');
    }

    public function edit(Servicio $servicio)
    {
        $categorias = CategoriaServicio::where('estado', 'activo')->get();

        return view('admin.servicios.edit', compact('servicio', 'categorias'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $datos = $request->validate([
            'categoria_servicio_id' => ['nullable', 'exists:categorias_servicios,id'],
            'nombre' => ['required', 'string', 'max:100'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion' => ['required', 'integer', 'min:5', 'max:480'],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('servicios', 'public');
        }

        $servicio->update($datos);

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Servicio $servicio)
    {
        $servicio->update(['estado' => 'inactivo']);

        return back()->with('success', 'Servicio desactivado.');
    }
}