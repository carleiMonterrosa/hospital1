<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrar Usuario - Hospital San Pablo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #7FFFD4  0%, #7FFFD4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .register-container {
            width: 100%;
            max-width: 1800px;
        }

        .register-card {
            background: white;
            border-radius: 60px;
            padding: 50px 100px;
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.4);
        }

        .register-header {
            text-align: center;
            margin-bottom: 55px;
        }

        .register-header h1 {
            background: linear-gradient(135deg, #2e7d32 0%, #43a047 100%);
            -webkit-background-clip: text;
            color: transparent;
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .hospital-logo-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .register-header h1 i {
            color: #ffc107;
            font-size: 3.5rem;
        }

        .register-header p {
            color: #64748b;
            font-size: 1.3rem;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            background: linear-gradient(135deg, #e8f5e9, #c8e6d9);
            padding: 10px 25px;
            border-radius: 50px;
            font-size: 1rem;
            color: #2e7d32;
            font-weight: 700;
            margin-top: 18px;
        }

        .form-group {
            margin-bottom: 32px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 12px;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group label i {
            margin-right: 10px;
            color: #43a047;
            font-size: 1.2rem;
        }

        .form-group label .required {
            color: #dc3545;
            margin-left: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 22px 28px;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            font-size: 1.3rem;
            font-family: inherit;
            transition: all 0.2s;
            background: white;
        }

        .form-group input:focus {
            border-color: #43a047;
            outline: none;
            box-shadow: 0 0 0 5px rgba(67, 160, 71, 0.2);
        }

        .form-group input::placeholder {
            color: transparent;
        }

        .form-group input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        .password-input-container {
            position: relative;
            width: 100%;
        }

        .password-input-container input {
            width: 100%;
            padding: 22px 60px 22px 28px;
            border: 2px solid #e2e8f0;
            border-radius: 24px;
            font-size: 1.3rem;
            font-family: inherit;
            transition: all 0.2s;
            background: white;
        }

        .password-input-container input:focus {
            border-color: #43a047;
            outline: none;
            box-shadow: 0 0 0 5px rgba(67, 160, 71, 0.2);
        }

        .password-input-container input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
        }

        .toggle-password {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.3rem;
            transition: color 0.2s;
            background: transparent;
            border: none;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #43a047;
        }

        .form-row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 45px;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 24px 32px;
            border-radius: 60px;
            cursor: pointer;
            font-weight: 800;
            font-size: 1.5rem;
            transition: all 0.3s;
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-register i {
            font-size: 1.7rem;
        }

        .alert {
            padding: 18px 24px;
            border-radius: 24px;
            margin-bottom: 30px;
            font-size: 1.1rem;
            font-weight: 500;
        }

        .alert-error {
            background: #f8d7da;
            border-left: 6px solid #dc3545;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            border-left: 6px solid #28a745;
            color: #155724;
        }

        .alert-info {
            background: #cff4fc;
            border-left: 6px solid #0dcaf0;
            color: #055160;
        }

        .alert-warning {
            background: #fff3cd;
            border-left: 6px solid #ffc107;
            color: #856404;
        }

        /* Estilos para el mensaje de confirmación tipo modal */
        .confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.2s ease;
        }
        .confirm-card {
            background: white;
            border-radius: 40px;
            padding: 40px 50px;
            max-width: 500px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: slideIn 0.2s ease;
        }
        .confirm-card i {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 15px;
        }
        .confirm-card h3 {
            font-size: 1.8rem;
            color: #0b2b5e;
            margin-bottom: 15px;
        }
        .confirm-card p {
            color: #64748b;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }
        .confirm-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .confirm-btn-si {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
        }
        .confirm-btn-no {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }
            .register-container {
                max-width: 100%;
            }
            .register-card {
                padding: 35px 30px;
            }
            .register-header h1 {
                font-size: 2.2rem;
            }
            .register-header h1 i {
                font-size: 2.2rem;
            }
            .hospital-logo-img {
                width: 50px;
                height: 50px;
            }
            .register-header p {
                font-size: 1rem;
            }
            .form-row-2 {
                grid-template-columns: 1fr;
                gap: 0;
            }
            .form-group input, .password-input-container input {
                padding: 16px 50px 16px 20px;
                font-size: 1.1rem;
            }
            .btn-register {
                padding: 18px 24px;
                font-size: 1.2rem;
            }
            .form-group label {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="register-card">

            <div class="register-header">
                <h1><img src="images/hls.jpg" alt="Logo" class="hospital-logo-img"> Hospital San Pablo</h1>
                <p>Registro de Usuario</p>
                <div class="badge"><i class="fas fa-notes-medical"></i> Datos personales</div>
            </div>

            <div id="alertContainer"></div>

            <form id="registerForm" action="{{ route('register') }}" method="POST">
                @csrf

                <input type="hidden" name="name" id="nameHidden">

                <div class="form-group">
                    <label><i class="fas fa-id-card"></i> IDENTIFICACIÓN <span class="required">*</span></label>
                    <input type="text" name="identificacion" id="identificacion" placeholder="" autocomplete="off" required>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-user-circle"></i> USUARIO <span class="required">*</span></label>
                    <input type="text" name="username" id="usuario" placeholder="Se generará automáticamente" autocomplete="off" required>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> PRIMER NOMBRE <span class="required">*</span></label>
                        <input type="text" name="primer_nombre" id="primer_nombre" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> SEGUNDO NOMBRE</label>
                        <input type="text" name="segundo_nombre" id="segundo_nombre" placeholder="" autocomplete="off">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label><i class="fas fa-user-tag"></i> PRIMER APELLIDO <span class="required">*</span></label>
                        <input type="text" name="primer_apellido" id="primer_apellido" placeholder="" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-user-tag"></i> SEGUNDO APELLIDO</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" placeholder="" autocomplete="off">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> CONTRASEÑA <span class="required">*</span></label>
                        <div class="password-input-container">
                            <input type="password" name="password" id="password" placeholder="Opcional" autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-check-circle"></i> CONFIRMAR CONTRASEÑA</label>
                        <div class="password-input-container">
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Opcional" autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register" id="btnRegister">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </form>

        </div>
    </div>

    <script>
        let timeoutBuscador = null;
        let autoGenerandoUsuario = true;
        let identificacionPendiente = null;
        let confirmacionMostrada = false;

        function redirigirAlLogin() {
            window.location.href = "{{ route('login') }}";
        }

        function obtenerNombreCompleto() {
            const primerNombre = document.getElementById('primer_nombre').value.trim();
            const segundoNombre = document.getElementById('segundo_nombre').value.trim();
            const primerApellido = document.getElementById('primer_apellido').value.trim();
            const segundoApellido = document.getElementById('segundo_apellido').value.trim();
            
            const nombreCompleto = `${primerNombre} ${segundoNombre ? segundoNombre + ' ' : ''}${primerApellido} ${segundoApellido ? segundoApellido : ''}`.trim().replace(/\s+/g, ' ');
            return nombreCompleto;
        }

        function generarNombreUsuario() {
            const primerNombre = document.getElementById('primer_nombre').value.trim();
            const primerApellido = document.getElementById('primer_apellido').value.trim();
            
            if (primerNombre && primerApellido) {
                const usuarioGenerado = `${primerNombre.toLowerCase()}.${primerApellido.toLowerCase()}`;
                const usuarioInput = document.getElementById('usuario');
                
                if (autoGenerandoUsuario && (usuarioInput.value === '' || usuarioInput.value === usuarioGenerado)) {
                    usuarioInput.value = usuarioGenerado;
                }
            }
        }

        function usuarioManual() {
            autoGenerandoUsuario = false;
        }

        function reactivarAutocompletado() {
            const usuarioInput = document.getElementById('usuario');
            if (usuarioInput.value === '') {
                autoGenerandoUsuario = true;
                const primerNombre = document.getElementById('primer_nombre').value.trim();
                const primerApellido = document.getElementById('primer_apellido').value.trim();
                if (primerNombre && primerApellido) {
                    usuarioInput.value = `${primerNombre.toLowerCase()}.${primerApellido.toLowerCase()}`;
                }
            }
        }

        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function mostrarAlerta(mensaje, tipo) {
            const container = document.getElementById('alertContainer');
            if (!container) return;
            const alertClass = tipo === 'error' ? 'alert-error' : (tipo === 'success' ? 'alert-success' : (tipo === 'warning' ? 'alert-warning' : 'alert-info'));
            container.innerHTML = `<div class="alert ${alertClass}"><i class="fas ${tipo === 'error' ? 'fa-exclamation-circle' : (tipo === 'success' ? 'fa-check-circle' : (tipo === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'))}"></i> ${mensaje}</div>`;
            setTimeout(() => {
                if (container.innerHTML.includes(mensaje)) {
                    container.innerHTML = '';
                }
            }, 4000);
        }

        function mostrarConfirmacion(identificacion) {
            // Eliminar overlay existente si hay
            const overlayExistente = document.querySelector('.confirm-overlay');
            if (overlayExistente) overlayExistente.remove();
            
            const overlay = document.createElement('div');
            overlay.className = 'confirm-overlay';
            overlay.innerHTML = `
                <div class="confirm-card">
                    <i class="fas fa-question-circle"></i>
                    <h3>Identificación no registrada</h3>
                    <p>No se encontró los datos con la identificación: <strong>${identificacion}</strong></p>
                    <p>¿Quiere seguir registrando?</p>
                    <div class="confirm-buttons">
                        <button class="confirm-btn-si" id="confirmSiBtn">Sí, continuar</button>
                        <button class="confirm-btn-no" id="confirmNoBtn">Cancelar</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            
            document.getElementById('confirmSiBtn').onclick = function() {
                overlay.remove();
                confirmacionMostrada = false;
                // Habilitar todos los campos para que el usuario pueda llenarlos
                document.getElementById('usuario').disabled = false;
                document.getElementById('primer_nombre').disabled = false;
                document.getElementById('segundo_nombre').disabled = false;
                document.getElementById('primer_apellido').disabled = false;
                document.getElementById('segundo_apellido').disabled = false;
                document.getElementById('password').disabled = false;
                document.getElementById('password_confirmation').disabled = false;
                // Limpiar campos para que el usuario los llene desde cero
                document.getElementById('usuario').value = '';
                document.getElementById('primer_nombre').value = '';
                document.getElementById('segundo_nombre').value = '';
                document.getElementById('primer_apellido').value = '';
                document.getElementById('segundo_apellido').value = '';
                autoGenerandoUsuario = true;
                mostrarAlerta('📝 Complete los datos del nuevo usuario', 'info');
            };
            
            document.getElementById('confirmNoBtn').onclick = function() {
                overlay.remove();
                confirmacionMostrada = false;
                // Limpiar el campo de identificación también
                document.getElementById('identificacion').value = '';
                limpiarFormulario();
                mostrarAlerta('Registro cancelado. Puede intentar con otra identificación.', 'info');
            };
        }

        function limpiarFormulario() {
            document.getElementById('usuario').value = '';
            document.getElementById('primer_nombre').value = '';
            document.getElementById('segundo_nombre').value = '';
            document.getElementById('primer_apellido').value = '';
            document.getElementById('segundo_apellido').value = '';
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
            
            document.getElementById('usuario').disabled = false;
            document.getElementById('primer_nombre').disabled = false;
            document.getElementById('segundo_nombre').disabled = false;
            document.getElementById('primer_apellido').disabled = false;
            document.getElementById('segundo_apellido').disabled = false;
            document.getElementById('password').disabled = false;
            document.getElementById('password_confirmation').disabled = false;
            
            autoGenerandoUsuario = true;
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const nombreCompleto = obtenerNombreCompleto();
            document.getElementById('nameHidden').value = nombreCompleto;
        });

        async function buscarPacientePorIdentificacion(identificacion) {
            if (!identificacion || identificacion.length < 4) {
                limpiarFormulario();
                return;
            }

            try {
                const response = await fetch('/buscar-persona', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ identificacion: identificacion })
                });

                const data = await response.json();

                if (data.success && data.persona) {
                    const persona = data.persona;
                    
                    document.getElementById('usuario').value = persona.usuario || '';
                    document.getElementById('primer_nombre').value = persona.primer_nombre || '';
                    document.getElementById('segundo_nombre').value = persona.segundo_nombre || '';
                    document.getElementById('primer_apellido').value = persona.primer_apellido || '';
                    document.getElementById('segundo_apellido').value = persona.segundo_apellido || '';
                    
                    document.getElementById('usuario').disabled = false;
                    document.getElementById('primer_nombre').disabled = true;
                    document.getElementById('segundo_nombre').disabled = true;
                    document.getElementById('primer_apellido').disabled = true;
                    document.getElementById('segundo_apellido').disabled = true;
                    
                    mostrarAlerta(`✅ Usuario encontrado: ${persona.primer_nombre} ${persona.primer_apellido}`, 'success');
                } else {
                    limpiarFormulario();
                    if (identificacion.length >= 5 && !confirmacionMostrada) {
                        confirmacionMostrada = true;
                        mostrarConfirmacion(identificacion);
                    }
                }
            } catch (error) {
                console.error('Error al buscar usuario:', error);
                limpiarFormulario();
            }
        }

        document.getElementById('primer_nombre').addEventListener('input', function() {
            generarNombreUsuario();
        });
        
        document.getElementById('primer_apellido').addEventListener('input', function() {
            generarNombreUsuario();
        });
        
        document.getElementById('usuario').addEventListener('input', usuarioManual);
        document.getElementById('usuario').addEventListener('blur', reactivarAutocompletado);

        document.getElementById('identificacion').addEventListener('input', function(e) {
            clearTimeout(timeoutBuscador);
            const identificacion = e.target.value.trim();
            
            if (identificacion.length >= 4) {
                timeoutBuscador = setTimeout(() => {
                    buscarPacientePorIdentificacion(identificacion);
                }, 500);
            } else {
                limpiarFormulario();
            }
        });

        @if($errors->any())
            let mensajes = [];
            @foreach($errors->all() as $error)
                mensajes.push("{{ $error }}");
            @endforeach
            mostrarAlerta('⚠️ ' + mensajes.join(', '), 'error');
        @endif

        @if(session('success'))
            mostrarAlerta('✅ {{ session('success') }}', 'success');
            setTimeout(() => {
                redirigirAlLogin();
            }, 2000);
        @endif

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const identificacion = document.getElementById('identificacion').value.trim();
            const usuario = document.getElementById('usuario').value.trim();
            const primer_nombre = document.getElementById('primer_nombre').value.trim();
            const primer_apellido = document.getElementById('primer_apellido').value.trim();
            let errores = [];

            if (!identificacion) {
                errores.push('La identificación es obligatoria');
            } else if (!/^\d+$/.test(identificacion)) {
                errores.push('La identificación solo debe contener números');
            }

            if (!usuario) {
                errores.push('El nombre de usuario es obligatorio');
            }

            if (!primer_nombre) {
                errores.push('El primer nombre es obligatorio');
            }

            if (!primer_apellido) {
                errores.push('El primer apellido es obligatorio');
            }

            if (errores.length > 0) {
                e.preventDefault();
                mostrarAlerta('⚠️ ' + errores.join(', '), 'error');
                return;
            }
        });
    </script>

</body>
</html>