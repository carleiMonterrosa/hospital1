<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class PasswordResetController extends Controller
{
    public function showResetForm($token)
    {
        return view('restablecer', compact('token'));
    }

    public function updatePassword(Request $request)
    {
        // Validar datos
        $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required|min:8|confirmed',
        ], [
            'username.required' => '⚠️ El campo USUARIO es obligatorio.',
            'username.exists' => '⚠️ El usuario ingresado no existe en nuestros registros.',
            'password.required' => '⚠️ El campo CONTRASEÑA es obligatorio.',
            'password.min' => '⚠️ La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => '⚠️ Las contraseñas no coinciden.',
        ]);

        // 🔥 ACTUALIZAR CONTRASEÑA DIRECTAMENTE CON DB
        $actualizado = DB::table('users')
            ->where('username', $request->username)
            ->update([
                'password' => Hash::make($request->password)
            ]);

        // 🔥 VERIFICAR SI SE ACTUALIZÓ
        if ($actualizado) {
            // Cerrar sesión
            Auth::logout();
            Session::flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirigir con mensaje de éxito
            return redirect('/login')->with('success', '✅ Contraseña actualizada correctamente. Ahora puedes iniciar sesión.');
        } else {
            return back()->with('error', '⚠️ No se pudo actualizar la contraseña. Intenta nuevamente.');
        }
    }
}