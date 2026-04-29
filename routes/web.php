<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HistorialLlamadoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

// ========== RUTAS DE AUTENTICACIÓN (PÚBLICAS) ==========
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== RUTAS DEL PANEL SUPER ADMIN (SIN AUTENTICACIÓN) ==========
Route::get('/super-panel', [SuperAdminController::class, 'index'])->name('super.panel');
Route::post('/superadmin/buscar-usuario', [SuperAdminController::class, 'buscarUsuario'])->name('superadmin.buscar.usuario');
Route::post('/superadmin/guardar-permisos', [SuperAdminController::class, 'guardarPermisosUsuario'])->name('superadmin.guardar.permisos');
Route::get('/superadmin/permisos/{id}', [SuperAdminController::class, 'obtenerPermisos'])->name('superadmin.obtener.permisos');

// ========== RUTAS PÚBLICAS DE TV (NO REQUIEREN LOGIN) ==========
Route::get('/tv', function () { return view('tv'); })->name('tv');
Route::get('/tv/turnos', [TurnoController::class, 'getTurnosTV'])->name('tv.turnos');

// ========== RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN) ==========
Route::middleware(['auth'])->group(function () {
    // Ruta principal - Admin
    Route::get('/', [TurnoController::class, 'admin'])->name('admin');
    
    // ========== RUTA PARA OBTENER PERMISOS DEL USUARIO AUTENTICADO ==========
    Route::get('/api/usuario/permisos', [SuperAdminController::class, 'obtenerMisPermisos'])->name('api.usuario.permisos');

    // ========== NUEVA RUTA PARA BUSCAR USUARIO POR USERNAME (PERFIL) ==========
    Route::post('/buscar-usuario-permisos', [TurnoController::class, 'buscarUsuarioPorUsername'])->name('buscar.usuario.permisos');

    // ========== NUEVA RUTA PARA GUARDAR PERMISOS DEL USUARIO EN LA BD ==========
    Route::post('/guardar-permisos-usuario', [TurnoController::class, 'guardarPermisosUsuario'])->name('guardar.permisos.usuario');

    // ========== TUS OTRAS RUTAS EXISTENTES ==========
    Route::post('/buscar-persona', [TurnoController::class, 'buscarPersona'])->name('buscar.persona');
    Route::post('/generar-turno', [TurnoController::class, 'generarTurno'])->name('generar.turno');
    Route::post('/turnos/{id}/estado', [TurnoController::class, 'cambiarEstado'])->name('turnos.estado');

    Route::post('/get-servicios', [ServicioController::class, 'index'])->name('get.servicios');
    Route::post('/guardar-servicio', [ServicioController::class, 'store'])->name('guardar.servicio');
    Route::post('/actualizar-servicio/{id}', [ServicioController::class, 'update'])->name('actualizar.servicio');
    Route::post('/eliminar-servicio/{id}', [ServicioController::class, 'destroy'])->name('eliminar.servicio');

    Route::prefix('api')->group(function () {
        Route::get('/servicios', [ServicioController::class, 'index']);
        Route::post('/servicios', [ServicioController::class, 'store']);
        Route::put('/servicios/{id}', [ServicioController::class, 'update']);
        Route::delete('/servicios/{id}', [ServicioController::class, 'destroy']);
        Route::get('/historial-llamados', [HistorialLlamadoController::class, 'index']);
        Route::get('/historial-llamados/{id}', [HistorialLlamadoController::class, 'show']);
        Route::post('/historial-llamados', [HistorialLlamadoController::class, 'store']);
        Route::delete('/historial-llamados/{id}', [HistorialLlamadoController::class, 'destroy']);
        Route::get('/historial-llamados/turno/{id_turno}', [HistorialLlamadoController::class, 'buscarPorTurno']);
        Route::get('/historial-llamados/estadisticas', [HistorialLlamadoController::class, 'estadisticas']);
    });

    Route::post('/personas', [PersonaController::class, 'store'])->name('personas.store');
    Route::post('/personas/buscar', [PersonaController::class, 'buscarPersona'])->name('personas.buscar');
});