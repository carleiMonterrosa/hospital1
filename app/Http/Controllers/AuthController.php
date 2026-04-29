<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Mostrar vista de login
    public function showLogin()
    {
        return view('login');
    }

    // Procesar login - ACEPTA EMAIL O USERNAME Y VERIFICA PERMISO DE LOGIN
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $login = $request->input('login');
        $password = $request->input('password');

        // Intentar encontrar usuario por email o username
        $user = User::where('email', $login)
                    ->orWhere('username', $login)
                    ->first();

        // Verificar si el usuario existe
        if (!$user) {
            return redirect()->back()
                ->with('error', '❌ Credenciales incorrectas')
                ->withInput();
        }

        // ========== VERIFICAR PERMISO DE LOGIN ==========
        $permisos = $user->permisos ?? [];
        $permisoLogin = $permisos['login'] ?? 0; // 0 = No, 1 = Sí

        if ($permisoLogin != 1) {
            return redirect()->back()
                ->with('error', '❌ Acceso denegado. No tiene permiso para iniciar sesión. Contacte al administrador.')
                ->withInput();
        }
        // ===============================================

        // Verificar contraseña
        if (Hash::check($password, $user->password)) {
            Auth::login($user);
            return redirect()->route('admin')->with('success', '✅ Bienvenido ' . $user->name);
        }

        return redirect()->back()
            ->with('error', '❌ Credenciales incorrectas')
            ->withInput();
    }

    // Mostrar vista de registro
    public function showRegister()
    {
        return view('register');
    }

    // Procesar registro - EMAIL AHORA ES OPCIONAL
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
            'email' => 'nullable|string|email|max:255|unique:users', // AHORA ES OPCIONAL (nullable)
            'password' => 'nullable|string|min:6|confirmed', // AHORA ES OPCIONAL
            // Validaciones para los campos de personas
            'identificacion' => 'required|string|max:20|unique:personas,identificacion',
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'segundo_apellido' => 'nullable|string|max:50',
        ], [
            'name.required' => 'El nombre completo es obligatorio',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'email.email' => 'Ingrese un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'identificacion.required' => 'La identificación es obligatoria',
            'identificacion.unique' => 'Esta identificación ya está registrada en el sistema',
            'primer_nombre.required' => 'El primer nombre es obligatorio',
            'primer_apellido.required' => 'El primer apellido es obligatorio',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generar email automático si no se proporcionó
        $email = $request->email ?? $request->username . '@temp.com';
        
        // Generar contraseña por defecto si no se proporcionó
        $password = $request->password ? Hash::make($request->password) : Hash::make('password123');

        // ========== PERMISOS POR DEFECTO: LOGIN EN 0 (NO PUEDE INICIAR SESIÓN) ==========
        $permisosPorDefecto = [
            'login' => 0,  // EL USUARIO NO PUEDE INICIAR SESIÓN - DEBE SER ACTIVADO POR EL SUPER ADMINISTRADOR
            'agregar_paciente' => 0,
            'usuarios' => 0,
            'servicios' => 0,
            'reportes' => 0,
            'atender_turnos' => 0,
            'perfil' => 0,
        ];
        // =================================================================================

        // ========== GUARDAR EN TABLA users (EXISTENTE) ==========
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $email,
            'password' => $password,
            'permisos' => $permisosPorDefecto,
        ]);

        // ========== NUEVO: GUARDAR EN TABLA personas ==========
        try {
            Persona::create([
                'identificacion' => $request->identificacion,
                'primer_nombre' => strtoupper($request->primer_nombre),
                'segundo_nombre' => $request->segundo_nombre ? strtoupper($request->segundo_nombre) : null,
                'primer_apellido' => strtoupper($request->primer_apellido),
                'segundo_apellido' => $request->segundo_apellido ? strtoupper($request->segundo_apellido) : null,
            ]);
        } catch (\Exception $e) {
            // Si falla el guardado en personas, igualmente se guardó en users
            // Pero mostramos un mensaje de advertencia
            return redirect()->route('login')->with('warning', '⚠️ Usuario registrado en el sistema, pero hubo un problema al guardar los datos personales. Contacte al administrador.');
        }

        return redirect()->route('login')->with('success', '✅ ¡Registro exitoso! Tu cuenta ha sido creada. Espera a que el administrador active tu acceso.');
    }

    // ==================== MÉTODO PARA REGISTRO RÁPIDO (SOLO NOMBRE Y USUARIO) ====================
    public function registrarUsuarioRapido(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users',
        ], [
            'name.required' => 'El nombre completo es obligatorio',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generar email automático
        $email = $request->username . '@temp.com';
        
        // Contraseña por defecto (el usuario la puede cambiar después)
        $password = Hash::make('password123');

        // ========== PERMISOS POR DEFECTO: LOGIN EN 0 (NO PUEDE INICIAR SESIÓN) ==========
        $permisosPorDefecto = [
            'login' => 0,  // EL USUARIO NO PUEDE INICIAR SESIÓN - DEBE SER ACTIVADO POR EL SUPER ADMINISTRADOR
            'agregar_paciente' => 0,
            'usuarios' => 0,
            'servicios' => 0,
            'reportes' => 0,
            'atender_turnos' => 0,
            'perfil' => 0,
        ];
        // =================================================================================

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $email,
            'password' => $password,
            'permisos' => $permisosPorDefecto,
        ]);

        return redirect()->route('login')->with('success', '✅ ¡Usuario registrado exitosamente! El administrador debe activar tu cuenta para poder iniciar sesión.');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', '✅ Sesión cerrada correctamente');
    }
}