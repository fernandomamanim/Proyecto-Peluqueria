<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\CategoriaServicioController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\Admin\AdminDashboardController;


Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'rol:Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::resource('categorias', CategoriaServicioController::class)->only(['index', 'store', 'update']);
    Route::resource('servicios', ServicioController::class)->except(['show']);
});

Route::middleware(['auth', 'rol:Staff,Administrador'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/pagos/{pago}/aprobar', [PagoController::class, 'aprobar'])->name('pagos.aprobar');
    Route::patch('/pagos/{pago}/rechazar', [PagoController::class, 'rechazar'])->name('pagos.rechazar');
    Route::get('/horarios', [HorarioController::class, 'index'])->name('horarios.index');
    Route::post('/horarios', [HorarioController::class, 'store'])->name('horarios.store');
    Route::put('/horarios/{horario}', [HorarioController::class, 'update'])->name('horarios.update');
    Route::delete('/horarios/{horario}', [HorarioController::class, 'destroy'])->name('horarios.destroy');
});

Route::middleware(['auth', 'rol:Cliente'])->group(function () {
    Route::get('/mi-cuenta', [ReservaController::class, 'index'])->name('cliente.dashboard');
    Route::get('/reservas/nueva', [ReservaController::class, 'create'])->name('cliente.reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('cliente.reservas.store');
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('cliente.reservas.show');
    Route::post('/reservas/{reserva}/pago', [PagoController::class, 'store'])->name('cliente.pagos.store');
});

require __DIR__.'/auth.php';
