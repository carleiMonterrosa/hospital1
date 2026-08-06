<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RecuperarController extends Controller
{
    // PASO 1: Enviar código de verificación al correo
    public function enviarCodigo(Request $request)
    {
        // 🔥 Validación SIN exists - ACEPTA CUALQUIER CORREO
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingrese un correo electrónico válido'
        ]);

        // Generar código de 6 dígitos
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Guardar en la tabla password_resets
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $codigo,
                'created_at' => Carbon::now()
            ]
        );

        // Guardar email en sesión
        session(['email_verificado' => $request->email]);

        // 🔥 OBTENER EL USUARIO POR CORREO
        $usuario = DB::table('users')->where('email', $request->email)->first();
        $nombreUsuario = $usuario ? $usuario->username : $request->email;

        // 🔥 INTENTAR ENVIAR EL CORREO CON EL USUARIO
        try {
            Mail::send('emails.codigo-verificacion', [
                'codigo' => $codigo,
                'email' => $request->email,
                'username' => $nombreUsuario
            ], function($message) use ($request) {
                $message->to($request->email)
                        ->subject('Código de verificación - Recuperar Contraseña');
            });

            // ✅ Si el correo se envió correctamente
            return redirect()->route('password.verificar.codigo')
                ->with('success', '✅ Se ha enviado un código de verificación a tu correo electrónico.')
                ->with('email', $request->email);

        } catch (\Exception $e) {
            // 🔥 SI FALLA EL ENVÍO, MUESTRA EL ERROR
            \Log::error('Error al enviar correo de recuperación: ' . $e->getMessage());
            \Log::error('Correo destino: ' . $request->email);
            \Log::error('Código generado: ' . $codigo);
            
            return redirect()->route('password.verificar.codigo')
                ->with('error', '❌ Error al enviar el código de verificación. Por favor, intenta nuevamente o contacta al administrador.')
                ->with('email', $request->email);
        }
    }

    // PASO 2: Mostrar formulario para verificar el código
    public function mostrarVerificacion()
    {
        $configuracion = \App\Models\ConfiguracionEmpresa::first();
        return view('recuperar-paso2', compact('configuracion'));
    }

    // PASO 3: Verificar el código ingresado
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'codigo' => 'required|string|size:6'
        ]);

        // Buscar el código en la base de datos
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->codigo)
            ->first();

        if (!$reset) {
            return back()->with('error', '❌ Código de verificación incorrecto. Intenta nuevamente.')
                ->withInput();
        }

        // Verificar que el código no haya expirado (15 minutos)
        $createdAt = Carbon::parse($reset->created_at);
        if ($createdAt->diffInMinutes(Carbon::now()) > 15) {
            DB::table('password_resets')->where('email', $request->email)->delete();
            return back()->with('error', '❌ El código ha expirado. Solicita uno nuevo.');
        }

        // Eliminar el código usado
        DB::table('password_resets')->where('email', $request->email)->delete();

        // Guardar email verificado en sesión
        session(['email_verificado' => $request->email]);

        return redirect()->route('password.reset.form')
            ->with('success', '✅ Código verificado. Ahora puedes cambiar tu contraseña.')
            ->with('email', $request->email);
    }

    // PASO 4: Actualizar la contraseña
    public function actualizarPassword(Request $request)
    {
        try {
            // Validar que el email esté verificado
            if (!session('email_verificado')) {
                return redirect()->route('password.request')
                    ->with('error', '⚠️ Primero debes verificar tu correo electrónico.');
            }

            $request->validate([
                'username' => 'required|exists:users,username',
                'password' => 'required|min:8|confirmed',
            ], [
                'username.required' => 'El campo USUARIO es obligatorio.',
                'username.exists' => 'El usuario ingresado no existe en nuestros registros.',
                'password.required' => 'El campo CONTRASEÑA es obligatorio.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',
            ]);

            // ✅ SOLO VERIFICAR QUE EL USUARIO EXISTA - SIN VALIDAR EMAIL
            $usuario = DB::table('users')
                ->where('username', $request->username)
                ->first();

            if (!$usuario) {
                return back()->with('error', '⚠️ El usuario no existe en nuestros registros.');
            }

            // Actualizar contraseña
            DB::table('users')
                ->where('username', $request->username)
                ->update([
                    'password' => Hash::make($request->password)
                ]);

            // Limpiar sesión
            session()->forget('email_verificado');
            
            // Cerrar sesión si está autenticado
            if (auth()->check()) {
                auth()->logout();
            }
            
            return redirect('/login')->with('success', '✅ Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar contraseña: ' . $e->getMessage());
            return back()->with('error', '❌ Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }
} 