<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with('proveedor')->orderBy('nombre')->get();

        return view('admin.productos.index', compact('productos'));
    }

    public function create()
    {
        $proveedores = Proveedor::where('estado', 'activo')->get();

        return view('admin.productos.create', compact('proveedores'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'proveedor_id' => ['nullable', 'exists:proveedores,id'],
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'imagen' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($datos);

        if ($producto->stock > 0) {
            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'usuario_id' => $request->user()->id,
                'tipo_movimiento' => 'Entrada',
                'cantidad' => $producto->stock,
                'motivo' => 'Stock inicial al crear el producto',
                'fecha_movimiento' => now(),
            ]);
        }

        return redirect()->route('admin.productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Producto $producto)
    {
        $proveedores = Proveedor::where('estado', 'activo')->get();

        return view('admin.productos.edit', compact('producto', 'proveedores'));
    }

    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'proveedor_id' => ['nullable', 'exists:proveedores,id'],
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string'],
            'precio' => ['required', 'numeric', 'min:0'],
            'imagen' => ['nullable', 'image', 'max:2048'],
            'estado' => ['required', 'in:activo,inactivo'],
        ]);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($datos);

        return redirect()->route('admin.productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function ajustarStock(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'tipo_movimiento' => ['required', 'in:Entrada,Salida'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'motivo' => ['nullable', 'string', 'max:255'],
        ]);

        if ($datos['tipo_movimiento'] === 'Salida' && $datos['cantidad'] > $producto->stock) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock para esa salida.']);
        }

        $producto->stock += $datos['tipo_movimiento'] === 'Entrada' ? $datos['cantidad'] : -$datos['cantidad'];
        $producto->save();

        MovimientoInventario::create([
            'producto_id' => $producto->id,
            'usuario_id' => $request->user()->id,
            'tipo_movimiento' => $datos['tipo_movimiento'],
            'cantidad' => $datos['cantidad'],
            'motivo' => $datos['motivo'] ?? 'Ajuste manual',
            'fecha_movimiento' => now(),
        ]);

        return back()->with('success', 'Stock ajustado correctamente.');
    }

    public function destroy(Producto $producto)
    {
        $producto->update(['estado' => 'inactivo']);

        return back()->with('success', 'Producto desactivado.');
    }
}