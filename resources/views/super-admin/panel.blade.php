{{-- resources/views/super-admin/panel.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Super Administrador - Hospital San Pablo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(145deg, #0b2b5e 0%, #1e4a8a 50%, #2e6ab3 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }
        .header {
            background: white;
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 25px;
            border-left: 5px solid #ffd966;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header h1 {
            color: #0b2b5e;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .header h1 i {
            color: #ffd966;
        }
        .header p {
            color: #666;
            font-size: 0.9rem;
            margin-top: 8px;
        }
        .buscador-card {
            background: white;
            border-radius: 20px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-top: 5px solid #ffd966;
        }
        .buscador-title {
            color: #0b2b5e;
            font-size: 1.1rem;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .buscador-title i {
            color: #ffd966;
        }
        .buscador-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        .buscador-input {
            flex: 1;
            min-width: 250px;
        }
        .buscador-input label {
            display: block;
            font-size: 0.7rem;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .buscador-input input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .buscador-input input:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        .btn-buscar {
            background: linear-gradient(135deg, #0b2b5e, #1e4a8a);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-buscar:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(11,43,94,0.4);
        }
        .usuario-seleccionado {
            background: #e8f5e9;
            border-radius: 12px;
            padding: 12px 15px;
            margin-top: 15px;
            display: none;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .usuario-seleccionado span {
            color: #2e7d32;
            font-weight: 600;
        }
        .usuario-seleccionado i {
            color: #2e7d32;
        }
        .btn-limpiar {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .modulos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .modulo-card {
            background: white;
            border-radius: 20px;
            padding: 20px 25px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        .modulo-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .modulo-info {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
        }
        .modulo-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .modulo-icon i {
            font-size: 1.6rem;
            color: white;
        }
        .modulo-texto h3 {
            color: #0b2b5e;
            font-size: 1.1rem;
            margin-bottom: 4px;
        }
        .modulo-texto p {
            color: #666;
            font-size: 0.75rem;
        }
        .modulo-checkbox {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .check-option {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 30px;
            transition: all 0.2s;
        }
        .check-option:hover {
            background: #f0f0f0;
        }
        .check-option input {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #28a745;
        }
        .check-option .check-true {
            color: #28a745;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .check-option .check-false {
            color: #dc3545;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .buttons-container {
            display: flex;
            justify-content: flex-end;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .btn-guardar {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(40,167,69,0.3);
        }
        .btn-guardar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(40,167,69,0.4);
        }
        .btn-actualizar {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #0b2b5e;
            border: none;
            padding: 14px 35px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(255,193,7,0.3);
        }
        .btn-actualizar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255,193,7,0.4);
        }
        .footer {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 15px 25px;
            margin-top: 30px;
            text-align: center;
            color: white;
            font-size: 0.8rem;
        }
        .footer i {
            color: #ffd966;
            margin-right: 5px;
        }
        .notification {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            z-index: 1000;
            display: none;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @media (max-width: 850px) {
            .modulos-grid {
                grid-template-columns: 1fr;
            }
            .modulo-card {
                flex-direction: column;
                text-align: center;
            }
            .modulo-info {
                flex-direction: column;
            }
            .buttons-container {
                justify-content: center;
            }
            .buscador-form {
                flex-direction: column;
            }
            .btn-buscar {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-crown"></i> 
                SUPER ADMIN
            </h1>
            <p><i class="fas fa-hospital-user"></i> Hospital San Pablo - Panel de Control de Permisos</p>
        </div>

        <div class="buscador-card">
            <div class="buscador-title">
                <i class="fas fa-search"></i>
                <span>Buscar Usuario</span>
            </div>
            <div class="buscador-form">
                <div class="buscador-input">
                    <label><i class="fas fa-user"></i> Nombre de Usuario</label>
                    <input type="text" id="searchUsuario" placeholder="Ej: admin, juan, pedro...">
                </div>
                <button class="btn-buscar" onclick="buscarUsuario()">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
            <div class="usuario-seleccionado" id="usuarioSeleccionado">
                <div>
                    <i class="fas fa-user-check"></i> 
                    Usuario seleccionado: <span id="usuarioNombre">---</span>
                </div>
                <button class="btn-limpiar" onclick="limpiarBusqueda()">
                    <i class="fas fa-times"></i> Limpiar
                </button>
            </div>
        </div>

        <div class="modulos-grid" id="modulos-grid">
            <!-- Módulo Login -->
            <div class="modulo-card" data-id="login">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Login</h3>
                        <p>Acceso al sistema - Inicio de sesión</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_login" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_login" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>

            <!-- Módulo Agregar Pacientes -->
            <div class="modulo-card" data-id="agregar_paciente">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Agregar Pacientes</h3>
                        <p>Registrar nuevos pacientes</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_agregar_paciente" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_agregar_paciente" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>

            <!-- Módulo Usuarios -->
            <div class="modulo-card" data-id="usuarios">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Usuarios</h3>
                        <p>Gestionar usuarios del sistema</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_usuarios" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_usuarios" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>

            <!-- Módulo Servicios -->
            <div class="modulo-card" data-id="servicios">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Servicios</h3>
                        <p>Gestionar servicios médicos</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_servicios" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_servicios" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>

            <!-- Módulo Reportes -->
            <div class="modulo-card" data-id="reportes">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Reportes</h3>
                        <p>Ver reportes de turnos</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_reportes" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_reportes" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>

            <!-- Módulo Atender Turnos -->
            <div class="modulo-card" data-id="atender_turnos">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Atender Turnos</h3>
                        <p>Gestionar y atender turnos</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_atender_turnos" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_atender_turnos" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>

            <!-- Módulo Perfil -->
            <div class="modulo-card" data-id="perfil">
                <div class="modulo-info">
                    <div class="modulo-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="modulo-texto">
                        <h3>Perfil</h3>
                        <p>Ver y editar perfil de usuario</p>
                    </div>
                </div>
                <div class="modulo-checkbox">
                    <label class="check-option">
                        <input type="radio" name="permiso_perfil" value="1">
                        <span class="check-true"><i class="fas fa-check-circle"></i> Sí</span>
                    </label>
                    <label class="check-option">
                        <input type="radio" name="permiso_perfil" value="0" checked>
                        <span class="check-false"><i class="fas fa-times-circle"></i> No</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="buttons-container">
            <button type="button" class="btn-actualizar" onclick="actualizarPermisos()">
                <i class="fas fa-sync-alt"></i> Actualizar
            </button>
            <button type="button" class="btn-guardar" onclick="guardarPermisos()">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>

        <div class="footer">
            <i class="fas fa-user-shield"></i> Super Administrador - Gestión de Permisos del Sistema
        </div>
    </div>

    <div id="notification" class="notification"></div>

    <script>
        let modulosPermisos = {};
        let usuarioActual = null;
        let usuarioIdActual = null;

        function getCsrfToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                return token.getAttribute('content');
            }
            return '';
        }

        async function buscarUsuario() {
            const searchTerm = document.getElementById('searchUsuario').value.trim();
            
            if (searchTerm === '') {
                mostrarNotificacion('⚠️ Ingrese un nombre de usuario para buscar', 'warning');
                return;
            }
            
            mostrarNotificacion('🔍 Buscando usuario...', 'info');
            
            try {
                const token = getCsrfToken();
                
                const response = await fetch('/superadmin/buscar-usuario', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ username: searchTerm })
                });
                
                if (response.status === 419) {
                    mostrarNotificacion('❌ Sesión expirada. Recargue la página', 'error');
                    setTimeout(() => location.reload(), 2000);
                    return;
                }
                
                const data = await response.json();
                
                if (data.success) {
                    usuarioActual = data.usuario.name;
                    usuarioIdActual = data.usuario.id;
                    modulosPermisos = data.usuario.permisos;
                    
                    // Mostrar el usuario encontrado
                    document.getElementById('usuarioNombre').innerHTML = `<strong>✅ ${data.usuario.name}</strong> (${data.usuario.email})`;
                    document.getElementById('usuarioSeleccionado').style.display = 'flex';
                    
                    aplicarPermisosARadios();
                    mostrarNotificacion(`✅ Usuario ENCONTRADO: "${data.usuario.name}"`, 'success');
                } else {
                    mostrarNotificacion(`❌ No se encontró el usuario "${searchTerm}"`, 'error');
                    limpiarBusqueda();
                }
            } catch (error) {
                console.error('Error:', error);
                mostrarNotificacion('❌ Error al buscar usuario', 'error');
                limpiarBusqueda();
            }
        }
        
        function aplicarPermisosARadios() {
            // Incluimos 'perfil' en la lista de módulos
            const modulosIds = ['login', 'agregar_paciente', 'usuarios', 'servicios', 'reportes', 'atender_turnos', 'perfil'];
            
            modulosIds.forEach(modulo => {
                const radioSi = document.querySelector(`input[name="permiso_${modulo}"][value="1"]`);
                const radioNo = document.querySelector(`input[name="permiso_${modulo}"][value="0"]`);
                let valor = modulosPermisos[modulo];
                
                if (valor === undefined || valor === null) {
                    valor = 0;
                }
                
                if (radioSi && radioNo) {
                    if (valor == 1) {
                        radioSi.checked = true;
                    } else {
                        radioNo.checked = true;
                    }
                }
            });
        }
        
        function limpiarBusqueda() {
            usuarioActual = null;
            usuarioIdActual = null;
            modulosPermisos = {};
            document.getElementById('searchUsuario').value = '';
            document.getElementById('usuarioSeleccionado').style.display = 'none';
            document.getElementById('usuarioNombre').textContent = '---';
            
            const allRadios = document.querySelectorAll('input[type="radio"][value="0"]');
            allRadios.forEach(radio => {
                radio.checked = true;
            });
        }
        
        async function guardarPermisos() {
            if (!usuarioIdActual) {
                mostrarNotificacion('⚠️ Primero busque un usuario para asignar permisos', 'warning');
                return;
            }
            
            // Incluimos 'perfil' en la lista de módulos
            const modulosIds = ['login', 'agregar_paciente', 'usuarios', 'servicios', 'reportes', 'atender_turnos', 'perfil'];
            const permisosActualizados = {};
            
            modulosIds.forEach(modulo => {
                const selected = document.querySelector(`input[name="permiso_${modulo}"]:checked`);
                if (selected) {
                    permisosActualizados[modulo] = parseInt(selected.value);
                } else {
                    permisosActualizados[modulo] = 0;
                }
            });
            
            console.log('Permisos a guardar:', permisosActualizados);
            
            mostrarNotificacion('💾 Guardando permisos...', 'info');
            
            try {
                const token = getCsrfToken();
                
                const response = await fetch('/superadmin/guardar-permisos', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        user_id: usuarioIdActual,
                        permisos: permisosActualizados
                    })
                });
                
                if (response.status === 419) {
                    mostrarNotificacion('❌ Sesión expirada. Recargue la página', 'error');
                    setTimeout(() => location.reload(), 2000);
                    return;
                }
                
                if (!response.ok) {
                    throw new Error(`Error ${response.status}: ${response.statusText}`);
                }
                
                const data = await response.json();
                console.log('Respuesta del servidor:', data);
                
                if (data.success) {
                    modulosPermisos = permisosActualizados;
                    mostrarNotificacion(`✅ Permisos guardados para "${usuarioActual}"`, 'success');
                } else {
                    mostrarNotificacion('❌ Error: ' + (data.message || 'Error desconocido'), 'error');
                }
            } catch (error) {
                console.error('Error de conexión:', error);
                mostrarNotificacion('❌ Error de conexión: ' + error.message, 'error');
            }
        }
        
        function actualizarPermisos() {
            if (usuarioIdActual) {
                buscarUsuario();
            } else {
                mostrarNotificacion('⚠️ Primero busque un usuario', 'warning');
            }
        }
        
        function mostrarNotificacion(mensaje, tipo) {
            const notif = document.getElementById('notification');
            notif.textContent = mensaje;
            
            if (tipo === 'success') {
                notif.style.background = '#28a745';
                notif.style.color = 'white';
            } else if (tipo === 'warning') {
                notif.style.background = '#ffc107';
                notif.style.color = '#0b2b5e';
            } else if (tipo === 'info') {
                notif.style.background = '#17a2b8';
                notif.style.color = 'white';
            } else {
                notif.style.background = '#dc3545';
                notif.style.color = 'white';
            }
            
            notif.style.display = 'block';
            setTimeout(() => {
                notif.style.display = 'none';
            }, 3000);
        }
        
        document.getElementById('searchUsuario').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                buscarUsuario();
            }
        });
    </script>
</body>
</html>