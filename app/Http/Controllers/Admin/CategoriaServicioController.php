<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaServicio;
use Illuminate\Http\Request;

class CategoriaServicioController extends Controller
{
    public function index()
    {
        $categorias = CategoriaServicio::withCount('servicios')->orderBy('nombre')->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias_servicios,nombre'],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        CategoriaServicio::create($datos);

        return back()->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, CategoriaServicio $categoria)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100', 'unique:categorias_servicios,nombre,'.$categoria->id],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        $categoria->update($datos);

        return back()->with('success', 'Categoría actualizada correctamente.');
    }

}
