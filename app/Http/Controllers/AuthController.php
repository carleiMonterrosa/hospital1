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

    // ========== REGISTRO CORREGIDO ==========
    public function register(Request $request)
    {
        // Construir el nombre completo desde los campos del formulario
        $primerNombre = $request->input('primer_nombre', '');
        $segundoNombre = $request->input('segundo_nombre', '');
        $primerApellido = $request->input('primer_apellido', '');
        $segundoApellido = $request->input('segundo_apellido', '');
        
        // Combinar nombres y apellidos en un solo campo 'name'
        $nombreCompleto = trim($primerNombre . ' ' . $segundoNombre . ' ' . $primerApellido . ' ' . $segundoApellido);
        $nombreCompleto = preg_replace('/\s+/', ' ', $nombreCompleto); // Limpiar espacios extra
        
        // Validaciones
        $validator = Validator::make($request->all(), [
            'identificacion' => 'required|string|max:20', // Ya no es unique
            'username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'primer_nombre' => 'required|string|max:50',
            'primer_apellido' => 'required|string|max:50',
        ], [
            'identificacion.required' => 'La identificación es obligatoria',
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'primer_nombre.required' => 'El primer nombre es obligatorio',
            'primer_apellido.required' => 'El primer apellido es obligatorio',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generar email automático
        $email = $request->username . '@temp.com';
        
        // Hashear contraseña
        $hashedPassword = Hash::make($request->password);

        // ========== PERMISOS POR DEFECTO ==========
        $permisosPorDefecto = [
            'login' => 0,
            'agregar_paciente' => 0,
            'usuarios' => 0,
            'servicios' => 0,
            'reportes' => 0,
            'atender_turnos' => 0,
            'perfil' => 0,
        ];

        // ========== 1. GUARDAR EN TABLA users ==========
        $user = User::create([
            'name' => $nombreCompleto,
            'username' => $request->username,
            'email' => $email,
            'password' => $hashedPassword,
            'permisos' => $permisosPorDefecto,
        ]);

        // ========== 2. GUARDAR EN TABLA personas SOLO SI NO EXISTE ==========
        $identificacion = $request->identificacion;
        $personaExistente = Persona::where('identificacion', $identificacion)->first();

        if (!$personaExistente) {
            // La cédula NO existe, la guardamos en personas
            Persona::create([
                'identificacion' => $identificacion,
                'primer_nombre' => strtoupper($primerNombre),
                'segundo_nombre' => $segundoNombre ? strtoupper($segundoNombre) : null,
                'primer_apellido' => strtoupper($primerApellido),
                'segundo_apellido' => $segundoApellido ? strtoupper($segundoApellido) : null,
            ]);
        }
        // Si la cédula YA EXISTE, NO se guarda en personas (solo en users)

        return redirect()->route('login')->with('success', '✅ ¡Registro exitoso! Tu cuenta ha sido creada. Espera a que el administrador active tu acceso.');
    }

    // ==================== MÉTODO PARA REGISTRO RÁPIDO ====================
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

        $email = $request->username . '@temp.com';
        $password = Hash::make('password123');

        $permisosPorDefecto = [
            'login' => 0,
            'agregar_paciente' => 0,
            'usuarios' => 0,
            'servicios' => 0,
            'reportes' => 0,
            'atender_turnos' => 0,
            'perfil' => 0,
        ];

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $email,
            'password' => $password,
            'permisos' => $permisosPorDefecto,
        ]);

        return redirect()->route('login')->with('success', '✅ ¡Usuario registrado exitosamente! El administrador debe activar tu cuenta.');
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