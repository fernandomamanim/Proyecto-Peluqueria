<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asistencia;
use App\Models\MovimientoInventario;
use App\Models\Pago;
use App\Models\Peluquero;
use App\Models\Producto;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function index()
    {
        return view('admin.reportes.index');
    }

    // ---------- FINANCIERO ----------

    public function financiero(Request $request)
    {
        $data = $this->datosFinancieros($request);

        return view('admin.reportes.financiero', $data);
    }

    public function financieroPdf(Request $request)
    {
        $data = $this->datosFinancieros($request);
        $pdf = Pdf::loadView('admin.reportes.pdf.financiero', $data);

        return $pdf->download('reporte-financiero.pdf');
    }

    private function datosFinancieros(Request $request): array
    {
        [$desde, $hasta] = $this->rangoFechas($request);

        $pagos = Pago::with('reserva')
            ->where('estado', 'Aprobado')
            ->whereBetween('fecha_pago', [$desde->startOfDay(), $hasta->endOfDay()])
            ->get();

        $ingresosServicios = $pagos->sum(fn ($p) => $p->reserva->servicio->precio ?? 0);
        $ingresosProductos = $pagos->sum(fn ($p) => $p->reserva->total_productos ?? 0);
        $totalDescuentos = $pagos->sum(fn ($p) => (($p->reserva->servicio->precio ?? 0) + ($p->reserva->total_productos ?? 0)) - $p->monto);
        $totalIngresos = $pagos->sum('monto');

        $nominaMensual = Peluquero::where('estado', 'activo')->sum('salario_mensual');

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'totalIngresos' => $totalIngresos,
            'ingresosServicios' => $ingresosServicios,
            'ingresosProductos' => $ingresosProductos,
            'totalDescuentos' => $totalDescuentos,
            'nominaMensual' => $nominaMensual,
            'utilidadNeta' => $totalIngresos - $nominaMensual,
            'cantidadPagos' => $pagos->count(),
        ];
    }

    // ---------- VENTAS ----------

    public function ventas(Request $request)
    {
        $data = $this->datosVentas($request);

        return view('admin.reportes.ventas', $data);
    }

    public function ventasPdf(Request $request)
    {
        $data = $this->datosVentas($request);
        $pdf = Pdf::loadView('admin.reportes.pdf.ventas', $data);

        return $pdf->download('reporte-ventas.pdf');
    }

    private function datosVentas(Request $request): array
    {
        [$desde, $hasta] = $this->rangoFechas($request);

        $pagos = Pago::with(['reserva.servicio', 'reserva.reservaProductos.producto'])
            ->where('estado', 'Aprobado')
            ->whereBetween('fecha_pago', [$desde->startOfDay(), $hasta->endOfDay()])
            ->orderBy('fecha_pago')
            ->get();

        $porServicio = $pagos->groupBy(fn ($p) => $p->reserva->servicio->nombre ?? 'Sin servicio')
            ->map(fn ($grupo) => $grupo->count())
            ->sortDesc();

        $porProducto = $pagos->flatMap(fn ($p) => $p->reserva->reservaProductos)
            ->groupBy(fn ($rp) => $rp->producto->nombre)
            ->map(fn ($grupo) => $grupo->sum('cantidad'))
            ->sortDesc();

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'pagos' => $pagos,
            'totalVentas' => $pagos->sum('monto'),
            'porServicio' => $porServicio,
            'porProducto' => $porProducto,
        ];
    }

    // ---------- INVENTARIO ----------

    public function inventario(Request $request)
    {
        $data = $this->datosInventario($request);

        return view('admin.reportes.inventario', $data);
    }

    public function inventarioPdf(Request $request)
    {
        $data = $this->datosInventario($request);
        $pdf = Pdf::loadView('admin.reportes.pdf.inventario', $data);

        return $pdf->download('reporte-inventario.pdf');
    }

    private function datosInventario(Request $request): array
    {
        [$desde, $hasta] = $this->rangoFechas($request);

        $productos = Producto::with('proveedor')->orderBy('nombre')->get();

        $movimientos = MovimientoInventario::with('producto')
            ->whereBetween('fecha_movimiento', [$desde->startOfDay(), $hasta->endOfDay()])
            ->orderByDesc('fecha_movimiento')
            ->get();

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'productos' => $productos,
            'valorInventario' => $productos->sum(fn ($p) => $p->precio * $p->stock),
            'productosStockBajo' => $productos->where('stock', '<=', 5),
            'movimientos' => $movimientos,
        ];
    }

    // ---------- RRHH ----------

    public function rrhh(Request $request)
    {
        $data = $this->datosRrhh($request);

        return view('admin.reportes.rrhh', $data);
    }

    public function rrhhPdf(Request $request)
    {
        $data = $this->datosRrhh($request);
        $pdf = Pdf::loadView('admin.reportes.pdf.rrhh', $data);

        return $pdf->download('reporte-rrhh.pdf');
    }

    private function datosRrhh(Request $request): array
    {
        [$desde, $hasta] = $this->rangoFechas($request);

        $peluqueros = Peluquero::with(['usuario', 'asistencias' => function ($q) use ($desde, $hasta) {
            $q->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()]);
        }])->get();

        $filas = $peluqueros->map(function ($peluquero) {
            $diasAsistidos = $peluquero->asistencias->whereNotNull('hora_entrada')->count();

            return [
                'peluquero' => $peluquero,
                'dias_asistidos' => $diasAsistidos,
                'salario_mensual' => $peluquero->salario_mensual,
            ];
        });

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'filas' => $filas,
            'totalNomina' => $filas->sum('salario_mensual'),
        ];
    }

    // ---------- RESERVAS ----------

    public function reservas(Request $request)
    {
        $data = $this->datosReservas($request);

        return view('admin.reportes.reservas', $data);
    }

    public function reservasPdf(Request $request)
    {
        $data = $this->datosReservas($request);
        $pdf = Pdf::loadView('admin.reportes.pdf.reservas', $data);

        return $pdf->download('reporte-reservas.pdf');
    }

    private function datosReservas(Request $request): array
    {
        [$desde, $hasta] = $this->rangoFechas($request);

        $reservas = Reserva::with(['peluquero.usuario', 'servicio'])
            ->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()])
            ->get();

        $porEstado = $reservas->groupBy('estado')->map->count();

        $porPeluquero = $reservas->groupBy(fn ($r) => $r->peluquero->usuario->nombre.' '.$r->peluquero->usuario->primer_apellido)
            ->map->count()
            ->sortDesc();

        return [
            'desde' => $desde,
            'hasta' => $hasta,
            'totalReservas' => $reservas->count(),
            'porEstado' => $porEstado,
            'porPeluquero' => $porPeluquero,
        ];
    }

    // ---------- Utilidad compartida ----------

    private function rangoFechas(Request $request): array
    {
        $desde = $request->filled('desde')
            ? \Carbon\Carbon::parse($request->desde)
            : now()->startOfMonth();

        $hasta = $request->filled('hasta')
            ? \Carbon\Carbon::parse($request->hasta)
            : now()->endOfMonth();

        return [$desde, $hasta];
    }
}