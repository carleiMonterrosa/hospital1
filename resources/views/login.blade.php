<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión - {{ $configuracion->nombre_empresa ?? 'Hospital San Pablo' }}</title>
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
            display: flex;
            overflow: hidden;
        }

        .left-panel {
            width: 35%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(145deg, #e8f5e9 0%, #d0e8dc 50%, #b8e0cc 100%);
        }

        .left-panel::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid rgba(100, 100, 100, 0.15);
            border-radius: 40px;
            pointer-events: none;
            z-index: 3;
            animation: borderPulse 3s ease-in-out infinite;
        }

        @keyframes borderPulse {
            0%, 100% {
                border-color: rgba(100, 100, 100, 0.15);
                box-shadow: 0 0 0 0 rgba(100, 100, 100, 0.05);
            }
            50% {
                border-color: rgba(40, 167, 69, 0.4);
                box-shadow: 0 0 20px 5px rgba(40, 167, 69, 0.1);
            }
        }

        .left-panel-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            transition: all 0.3s ease;
            z-index: 2;
            opacity: 0.95;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.1));
        }

        .left-panel:hover .left-panel-bg {
            transform: scale(1.02);
            opacity: 1;
        }

        .bubbles-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
            overflow: hidden;
        }

        .bubble {
            position: absolute;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.8), rgba(255,255,255,0.3));
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255,255,255,0.3);
            animation: bubbleFloat 8s infinite ease-in-out;
            opacity: 0.6;
        }

        @keyframes bubbleFloat {
            0% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.6;
            }
            100% {
                transform: translateY(-100px) translateX(20px) scale(1.2);
                opacity: 0;
            }
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(232, 245, 233, 0.2) 0%, rgba(200, 230, 217, 0.1) 50%, rgba(185, 246, 202, 0.05) 100%);
            z-index: 0;
        }

        .right-panel {
            width: 65%;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
            position: relative;
        }

        .right-panel::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(40,167,69,0.05) 0%, rgba(40,167,69,0) 70%);
            pointer-events: none;
            animation: glowPulse 4s ease-in-out infinite;
        }

        .right-panel::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,217,102,0.06) 0%, rgba(255,217,102,0) 70%);
            pointer-events: none;
            animation: glowPulse 4s ease-in-out infinite reverse;
        }

        @keyframes glowPulse {
            0%, 100% {
                opacity: 0.5;
                transform: scale(1);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        .login-container {
            width: 100%;
            max-width: 500px;
            position: relative;
            z-index: 2;
            animation: fadeInRight 0.8s ease-out;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            padding: 40px 35px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25), 0 0 0 1px rgba(0, 0, 0, 0.02);
            backdrop-filter: blur(2px);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(40,167,69,0.1) 0%, transparent 100%);
            border-radius: 0 0 100px 0;
            pointer-events: none;
        }

        .login-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(315deg, rgba(255,217,102,0.08) 0%, transparent 100%);
            border-radius: 100px 0 0 0;
            pointer-events: none;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header .logo-empresa {
            max-width: 120px;
            max-height: 80px;
            margin-bottom: 15px;
            object-fit: contain;
            display: inline-block;
        }

        .login-header h1 {
            background: linear-gradient(135deg, #2e7d32 0%, #43a047 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .login-header h1 i {
            background: linear-gradient(135deg, #ffd966, #ffc107);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-right: 10px;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .login-header .badge {
            display: inline-block;
            background: linear-gradient(135deg, #e8f5e9, #c8e6d9);
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.7rem;
            color: #2e7d32;
            font-weight: 600;
            margin-top: 12px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group label i {
            margin-right: 8px;
            color: #43a047;
            font-size: 0.85rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i.input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            transition: color 0.3s;
            z-index: 1;
        }

        .form-group input {
            width: 100%;
            padding: 14px 50px 14px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: inherit;
            background: #ffffff;
        }

        .form-group input:focus {
            border-color: #43a047;
            outline: none;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.15);
        }

        .form-group input:focus + .input-icon i {
            color: #43a047;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input {
            width: 100%;
            padding: 14px 50px 14px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 20px;
            font-size: 0.95rem;
            transition: all 0.3s;
            font-family: inherit;
            background: #ffffff;
        }

        .password-container input:focus {
            border-color: #43a047;
            outline: none;
            box-shadow: 0 0 0 4px rgba(67, 160, 71, 0.15);
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.2s;
            background: transparent;
            border: none;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #43a047;
        }

        .info-text {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-top: 8px;
            margin-left: 12px;
        }

        .info-text i {
            font-size: 0.65rem;
            margin-right: 4px;
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            box-shadow: 0 4px 14px rgba(40, 167, 69, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .register-link p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .register-link a {
            background: linear-gradient(135deg, #28a745, #20c997);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s;
        }

        .register-link a:hover {
            opacity: 0.8;
            text-decoration: underline;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 20px;
            margin-bottom: 24px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c8e6d9);
            border-left: 4px solid #28a745;
            color: #155724;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da, #ffe5e5);
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .alert i {
            font-size: 1.1rem;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .corner-decoration {
            position: absolute;
            width: 80px;
            height: 80px;
            border: 3px solid rgba(67, 160, 71, 0.25);
            z-index: 3;
            pointer-events: none;
        }

        .corner-tl {
            top: 30px;
            left: 30px;
            border-right: none;
            border-bottom: none;
            border-radius: 20px 0 0 0;
        }

        .corner-tr {
            top: 30px;
            right: 30px;
            border-left: none;
            border-bottom: none;
            border-radius: 0 20px 0 0;
        }

        .corner-bl {
            bottom: 30px;
            left: 30px;
            border-right: none;
            border-top: none;
            border-radius: 0 0 0 20px;
        }

        .corner-br {
            bottom: 30px;
            right: 30px;
            border-left: none;
            border-top: none;
            border-radius: 0 0 20px 0;
        }

        @media (max-width: 900px) {
            .left-panel-bg {
                width: 100%;
                height: 100%;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            .left-panel, .right-panel {
                width: 100%;
                min-height: 35vh;
            }
            .login-card {
                padding: 24px 20px;
            }
            .corner-decoration {
                display: none;
            }
            .left-panel::after {
                top: 10px;
                left: 10px;
                right: 10px;
                bottom: 10px;
            }
            .left-panel-bg {
                width: 100%;
                height: 100%;
            }
            .login-container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="left-panel" id="leftPanel">
        <!-- IMAGEN DE FONDO DINÁMICA DESDE LA BASE DE DATOS -->
        <div class="left-panel-bg" id="panelBg" style="background-image: url('{{ 
            (isset($configuracion) && $configuracion && $configuracion->imagen_fondo_login) 
                ? asset($configuracion->imagen_fondo_login) 
                : asset('images/hospital-bg.jpg') 
        }}');"></div>
        <div class="bubbles-container" id="bubblesContainer"></div>
        <div class="corner-decoration corner-tl"></div>
        <div class="corner-decoration corner-tr"></div>
        <div class="corner-decoration corner-bl"></div>
        <div class="corner-decoration corner-br"></div>
    </div>

    <div class="right-panel">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <!-- LOGO DINÁMICO DE LA EMPRESA -->
                    @if(isset($configuracion) && $configuracion && $configuracion->logo_empresa_url)
                        <img src="{{ asset($configuracion->logo_empresa_url) }}" alt="Logo Empresa" class="logo-empresa">
                    @else
                        <i class="fas fa-hospital-user" style="font-size: 3.5rem; color: #2e7d32; margin-bottom: 10px; display: block;"></i>
                    @endif
                    
                    <!-- NOMBRE DINÁMICO DE LA EMPRESA -->
                    <h1><i class="fas fa-hospital-user"></i> {{ $configuracion->nombre_empresa ?? 'E.S.E HOSPITAL LOCAL SAN PABLO' }}</h1>
                    <p>Bienvenido de vuelta</p>
                    <div class="badge"><i class="fas fa-shield-alt"></i> Acceso seguro</div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> 
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="loginForm" autocomplete="off">
                    @csrf
                    <div class="form-group">
                        <label><i class="fas fa-user-circle"></i>Usuario</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="text" name="login" id="usuarioInput" value="" placeholder=" " autocomplete="off">
                        </div>
                        <div class="info-text">
                            <i class="fas fa-info-circle"></i> Ingresa tu correo electrónico o nombre de usuario
                        </div>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-lock"></i> Contraseña</label>
                        <div class="password-container">
                            <i class="fas fa-key input-icon" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; z-index: 1;"></i>
                            <input type="password" name="password" id="password" placeholder=" " autocomplete="off" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn-login">
                        <i class="fas fa-arrow-right-to-bracket"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="register-link">
                    <p>¿No tienes una cuenta? <a href="{{ route('register') }}">Registrar</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Limpiar campos inmediatamente cuando se carga la página
        document.getElementById('usuarioInput').value = '';
        document.getElementById('password').value = '';
        
        // También limpiar después de 100ms (por si el navegador es lento)
        setTimeout(function() {
            document.getElementById('usuarioInput').value = '';
            document.getElementById('password').value = '';
        }, 100);
        
        // También limpiar cuando el usuario haga clic en los campos (por si el navegador autocompleta después)
        document.getElementById('usuarioInput').addEventListener('focus', function() {
            if (this.value !== '') {
                this.value = '';
            }
        });
        
        document.getElementById('password').addEventListener('focus', function() {
            if (this.value !== '') {
                this.value = '';
            }
        });

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.querySelector('.toggle-password i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        function generarBurbujas() {
            const container = document.getElementById('bubblesContainer');
            if (!container) return;
            
            const numBurbujas = 35;
            for (let i = 0; i < numBurbujas; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                const size = Math.random() * 25 + 8;
                bubble.style.width = size + 'px';
                bubble.style.height = size + 'px';
                bubble.style.left = Math.random() * 100 + '%';
                bubble.style.bottom = Math.random() * 80 + 'vh';
                bubble.style.animationDelay = Math.random() * 8 + 's';
                bubble.style.animationDuration = (Math.random() * 6 + 5) + 's';
                bubble.style.opacity = Math.random() * 0.4 + 0.2;
                container.appendChild(bubble);
            }
        }

        // Función para cargar el fondo desde la BD o localStorage
        function cargarFondo() {
            const panelBg = document.getElementById('panelBg');
            
            // Verificar si hay imagen de fondo en la base de datos
            @if(isset($configuracion) && $configuracion && $configuracion->imagen_fondo_login)
                // Si hay imagen en la BD, usarla con CONTAIN (se ve completa y nítida)
                panelBg.style.backgroundImage = `url('{{ asset($configuracion->imagen_fondo_login) }}')`;
                panelBg.style.backgroundSize = 'contain';
                panelBg.style.backgroundRepeat = 'no-repeat';
                panelBg.style.backgroundPosition = 'center';
            @else
                // Si no hay imagen en la BD, usar la imagen por defecto
                panelBg.style.backgroundImage = `url('{{ asset('images/hospital-bg.jpg') }}')`;
                panelBg.style.backgroundSize = 'contain';
                panelBg.style.backgroundRepeat = 'no-repeat';
                panelBg.style.backgroundPosition = 'center';
            @endif
        }

        function subirFondo() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const imagenBase64 = event.target.result;
                        localStorage.setItem('loginBackgroundImage', imagenBase64);
                        cargarFondo();
                        mostrarNotificacion('✅ Imagen de fondo actualizada (solo en este navegador)', 'success');
                    };
                    reader.readAsDataURL(file);
                }
            };
            input.click();
        }

        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notif = document.createElement('div');
            notif.innerHTML = `<i class="fas ${tipo === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i> ${mensaje}`;
            notif.style.position = 'fixed';
            notif.style.bottom = '20px';
            notif.style.right = '20px';
            notif.style.backgroundColor = tipo === 'success' ? '#28a745' : '#17a2b8';
            notif.style.color = 'white';
            notif.style.padding = '14px 24px';
            notif.style.borderRadius = '50px';
            notif.style.zIndex = '9999';
            notif.style.fontFamily = 'Inter, sans-serif';
            notif.style.fontSize = '14px';
            notif.style.fontWeight = '500';
            notif.style.boxShadow = '0 4px 15px rgba(0,0,0,0.2)';
            notif.style.display = 'flex';
            notif.style.alignItems = 'center';
            notif.style.gap = '10px';
            notif.style.backdropFilter = 'blur(8px)';
            notif.style.background = tipo === 'success' ? 'linear-gradient(135deg, #28a745, #20c997)' : 'linear-gradient(135deg, #17a2b8, #0d8a9e)';
            document.body.appendChild(notif);
            setTimeout(() => {
                notif.style.opacity = '0';
                notif.style.transition = 'opacity 0.3s';
                setTimeout(() => notif.remove(), 300);
            }, 3000);
        }

        const leftPanel = document.getElementById('leftPanel');
        if (leftPanel) {
            leftPanel.addEventListener('dblclick', function(e) {
                subirFondo();
            });
        }

        cargarFondo();
        generarBurbujas();
    </script>
</body>
</html>