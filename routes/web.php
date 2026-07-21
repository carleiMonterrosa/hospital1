<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HistorialLlamadoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

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

// ========== RUTA PÚBLICA PARA BUSCAR PERSONA (NO REQUIERE LOGIN) ==========
Route::post('/buscar-persona', [TurnoController::class, 'buscarPersona'])->name('buscar.persona');

// ========== RUTA PÚBLICA PARA GUARDAR PERSONA (REGISTRAR PACIENTE) ==========
Route::post('/personas', [TurnoController::class, 'storePersona'])->name('personas.store');

// ========== RUTAS PARA NIVELES DE ACCESO (PÚBLICAS PARA LA API) ==========
Route::get('/api/niveles-acceso', [TurnoController::class, 'getNivelesAcceso']);
Route::post('/api/niveles-acceso', [TurnoController::class, 'storeNivelAcceso']);

// ========== RUTA PÚBLICA PARA OBTENER CONFIGURACIÓN DE EMPRESA (PARA TV Y LOGIN) ==========
Route::get('/api/configuracion-empresa', [TurnoController::class, 'getConfiguracion']);

// ========== RUTA PRINCIPAL CON /hospital1 (NUEVO) ==========
Route::get('/hospital1', function () {
    return redirect('/');
});

// ============================================================
// 🔥 RUTAS DE TURNOS - DEBEN ESTAR FUERA DEL GRUPO AUTH O DENTRO
// ============================================================

// 🔥 Ruta para GUARDAR turnos (POST) - la usa atenderTurno() y generarTurno()
Route::post('/api/turnos', [TurnoController::class, 'store']);

// 🔥 Ruta para OBTENER turnos (GET) - para reportes y TV - CORREGIDO ✅
Route::get('/api/turnos', function () {
    try {
        // 🔥 CORREGIDO: 'fecha_creacion' NO existe en la tabla 'turnos'
        // Se cambió a 'id_turno' que SI existe
        $turnos = DB::table('turnos')->orderBy('id_turno', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $turnos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener turnos: ' . $e->getMessage()
        ], 500);
    }
});

// 🔥 Ruta para OBTENER turnos por módulo - CORREGIDO ✅
Route::get('/api/turnos/modulo/{id}', function ($id) {
    try {
        // 🔥 CORREGIDO: 'fecha_creacion' NO existe en la tabla 'turnos'
        // Se cambió a 'id_turno' que SI existe
        $turnos = DB::table('turnos')
            ->where('id_modulo', $id)
            ->orderBy('id_turno', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $turnos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener turnos del módulo: ' . $e->getMessage()
        ], 500);
    }
});

// ========== 🔥 NUEVA RUTA PÚBLICA PARA OBTENER TURNOS EN ESPERA POR MÓDULO ==========
Route::get('/turnos/en-espera', [TurnoController::class, 'getTurnosEnEspera'])->name('turnos.en.espera');

// ========== RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN) ==========
Route::middleware(['auth'])->group(function () {
    // Ruta principal - Admin
    Route::get('/', [TurnoController::class, 'admin'])->name('admin');
    
    // ========== RUTA PARA OBTENER PERMISOS DEL USUARIO AUTENTICADO ==========
    Route::get('/api/usuario/permisos', [SuperAdminController::class, 'obtenerMisPermisos'])->name('api.usuario.permisos');

    // ========== RUTA PARA BUSCAR USUARIO POR USERNAME O IDENTIFICACION (PERFIL) ==========
    Route::post('/buscar-usuario-permisos', [AuthController::class, 'buscarUsuarioPermisos'])->name('buscar.usuario.permisos');

    // ========== RUTA PARA GUARDAR PERMISOS DEL USUARIO EN LA BD ==========
    Route::post('/guardar-permisos-usuario', [TurnoController::class, 'guardarPermisosUsuario'])->name('guardar.permisos.usuario');

    // ========== RUTAS PARA EDITAR Y ELIMINAR USUARIOS ==========
    Route::get('/obtener-usuario/{username}', [TurnoController::class, 'obtenerUsuarioPorUsername'])->name('obtener.usuario');
    Route::put('/actualizar-usuario/{id}', [TurnoController::class, 'actualizarUsuario'])->name('actualizar.usuario');
    Route::delete('/eliminar-usuario/{id}', [TurnoController::class, 'eliminarUsuario'])->name('eliminar.usuario');

    // ========== RUTAS DE TURNOS ==========
    // 🔥 CAMBIO IMPORTANTE: /generar-turno ahora usa store() que SÍ guarda en BD
    Route::post('/generar-turno', [TurnoController::class, 'store'])->name('generar.turno');
    Route::post('/turnos/{id}/estado', [TurnoController::class, 'cambiarEstado'])->name('turnos.estado');

    // ========== RUTAS DE SERVICIOS ==========
    Route::post('/get-servicios', [ServicioController::class, 'index'])->name('get.servicios');
    Route::post('/guardar-servicio', [ServicioController::class, 'store'])->name('guardar.servicio');
    Route::post('/actualizar-servicio/{id}', [ServicioController::class, 'update'])->name('actualizar.servicio');
    Route::post('/eliminar-servicio/{id}', [ServicioController::class, 'destroy'])->name('eliminar.servicio');

    // ========== API DE SERVICIOS ==========
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

    // ========== RUTAS DE PERSONAS ==========
    Route::post('/personas/buscar', [PersonaController::class, 'buscarPersona'])->name('personas.buscar');

    // ========== NUEVAS RUTAS PARA CONFIGURACIÓN DE EMPRESA (PROTEGIDAS) ==========
    Route::get('/api/configuracion-empresa', [TurnoController::class, 'getConfiguracion']);
    Route::post('/api/configuracion-empresa', [TurnoController::class, 'guardarConfiguracion']);
});

// ========== 🔥 RUTA PARA LIMPIAR CACHÉ (ÚTIL PARA PRUEBAS) ==========
Route::get('/limpiar-cache', function() {
    try {
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        return response()->json([
            'success' => true,
            'message' => '✅ Caché de Laravel limpiada correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => '❌ Error al limpiar caché: ' . $e->getMessage()
        ], 500);
    }
});