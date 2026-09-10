<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peluquero;

class PeluqueroController extends Controller
{
    public function index()
    {
        $peluqueros = Peluquero::with('usuario')->withCount('horarios')->get();

        return view('admin.peluqueros.index', compact('peluqueros'));
    }
}