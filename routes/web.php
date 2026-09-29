<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoriaServicioController;
use App\Http\Controllers\Admin\HorarioController as AdminHorarioController;
use App\Http\Controllers\Admin\PeluqueroController as AdminPeluqueroController;
use App\Http\Controllers\Admin\ReservaController as AdminReservaController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\StaffDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// Reservas: accesibles con o sin cuenta
Route::get('/reservar', [ReservaController::class, 'create'])->name('reservas.create');
Route::post('/reservar', [ReservaController::class, 'store'])->name('reservas.store');
Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
Route::post('/reservas/{reserva}/pago', [PagoController::class, 'store'])->name('reservas.pagos.store');

Route::middleware(['auth', 'rol:Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::resource('categorias', CategoriaServicioController::class)->only(['index', 'store', 'update']);
    Route::resource('servicios', ServicioController::class)->except(['show']);
    Route::get('/reservas', [AdminReservaController::class, 'index'])->name('reservas.index');
    Route::get('/peluqueros', [AdminPeluqueroController::class, 'index'])->name('peluqueros.index');
    Route::get('/peluqueros/{peluquero}/horarios', [AdminHorarioController::class, 'index'])->name('peluqueros.horarios.index');
    Route::post('/peluqueros/{peluquero}/horarios', [AdminHorarioController::class, 'store'])->name('peluqueros.horarios.store');
    Route::put('/peluqueros/{peluquero}/horarios/{horario}', [AdminHorarioController::class, 'update'])->name('peluqueros.horarios.update');
    Route::delete('/peluqueros/{peluquero}/horarios/{horario}', [AdminHorarioController::class, 'destroy'])->name('peluqueros.horarios.destroy');
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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';