{{-- resources/views/admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrador Turnos - {{ $configuracion->nombre_empresa ?? 'Hospital San Pablo' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ===== TODOS TUS ESTILOS EXISTENTES (MANTENIDOS IGUALES) ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(145deg, #0b2b5e 0%, #1e4a8a 50%, #2e6ab3 100%);
            min-height: 100vh;
        }
        .app-layout { display: flex; min-height: 100vh; }
        .sidebar {
            width: 320px;
            background: linear-gradient(180deg, #0a2351, #0b2b5e);
            color: white;
            box-shadow: 4px 0 20px rgba(0,0,0,0.3);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 10;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header { 
            padding: 20px; 
            text-align: center; 
            border-bottom: 1px solid rgba(255,215,0,0.3); 
            margin-bottom: 15px; 
        }
        .sidebar-header .logo-empresa {
            max-width: 80px;
            max-height: 60px;
            object-fit: contain;
            display: block;
            margin: 0 auto 10px auto;
        }
        .sidebar-header h2 { 
            font-size: 1.2em; 
            color: #ffd966; 
            word-wrap: break-word;
        }
        .nav-menu { flex: 1; padding: 0 15px; }
        .nav-section { margin-bottom: 20px; }
        .nav-section-title { color: #ffd966; font-size: 0.75em; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; padding-left: 10px; font-weight: 600; }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            border-radius: 12px;
            color: #e0e7ff;
            cursor: pointer;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .nav-item:hover { background: rgba(255,215,0,0.2); transform: translateX(5px); color: white; }
        .nav-item.active { background: rgba(255,215,0,0.3); color: #ffd966; border-left: 3px solid #ffd966; }
        .nav-item i { width: 24px; text-align: center; }
        .voice-control { background: rgba(255,215,0,0.15); margin: 10px 15px; padding: 12px; border-radius: 20px; text-align: center; border: 1px solid #ffd966; }
        .voice-control h3 { font-size: 0.9em; margin-bottom: 8px; color: #ffd966; }
        .voice-buttons { display: flex; gap: 8px; justify-content: center; }
        .voice-btn { background: rgba(255,255,255,0.2); border: none; padding: 6px 12px; border-radius: 30px; color: white; cursor: pointer; transition: all 0.3s; font-size: 0.75em; }
        .voice-btn:hover, .voice-btn.active { background: #ffd966; color: #0b2b5e; }
        .main-content { flex: 1; margin-left: 320px; padding: 20px; }
        
        /* ESTILOS PARA MODO TRABAJADOR (PANTALLA COMPLETA) */
        .sidebar-hidden {
            display: none !important;
        }
        .main-content-full {
            margin-left: 0 !important;
            width: 100%;
            padding: 20px;
        }
        
        /* BARRA DE MÓDULOS (PESTAÑAS) PARA USUARIOS NORMALES */
        .modulos-bar {
            background: white;
            border-radius: 16px;
            margin-bottom: 20px;
            padding: 8px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        .modulo-tab {
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            background: #f1f5f9;
            color: #1e293b;
            border: none;
            font-size: 0.9rem;
        }
        .modulo-tab:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }
        .modulo-tab.active {
            background: linear-gradient(135deg, #0b2b5e, #1e4a8a);
            color: white;
            box-shadow: 0 4px 12px rgba(11,43,94,0.3);
        }
        .modulo-tab i {
            margin-right: 8px;
        }
        
        /* BOTÓN DE CERRAR SESIÓN EN BARRA DE MÓDULOS */
        .btn-cerrar-sesion-modulo {
            margin-left: auto;
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .btn-cerrar-sesion-modulo:hover {
            background: #b02a37;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220,53,69,0.4);
        }
        
        /* MODO TRABAJADOR PANTALLA COMPLETA PARA TODAS LAS SECCIONES */
        .modo-trabajador .main-content-full {
            padding: 0 !important;
        }
        
        .modo-trabajador .modulos-bar {
            border-radius: 0;
            margin: 0;
            padding: 12px 20px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }
        
        /* Estilos para sección SERVICIOS en modo trabajador */
        .modo-trabajador .servicios-section {
            border-radius: 0 !important;
            margin: 0 !important;
            min-height: calc(100vh - 70px);
            padding: 28px;
        }
        
        .modo-trabajador .servicios-section .servicios-grid {
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 25px;
        }
        
        .modo-trabajador .servicios-section .servicios-header {
            margin-bottom: 30px;
        }
        
        .modo-trabajador .servicios-section .servicios-header h2 {
            font-size: 1.8rem;
        }
        
        .modo-trabajador .servicios-section .btn-agregar-servicio {
            padding: 14px 28px;
            font-size: 1rem;
        }
        
        .modo-trabajador .servicio-card {
            padding: 25px;
        }
        
        .modo-trabajador .servicio-card h3 {
            font-size: 1.3rem;
        }
        
        .modo-trabajador .servicio-card .descripcion {
            font-size: 0.95rem;
        }
        
        .modo-trabajador .servicio-card .badge-orden,
        .modo-trabajador .servicio-card .estado {
            padding: 6px 14px;
            font-size: 0.8rem;
        }
        
        /* Estilos para sección REPORTES en modo trabajador */
        .modo-trabajador .reportes-section {
            border-radius: 0 !important;
            margin: 0 !important;
            min-height: calc(100vh - 70px);
            padding: 28px;
        }
        
        .modo-trabajador .reportes-section .report-header h2 {
            font-size: 1.8rem;
        }
        
        .modo-trabajador .reportes-section .filtros-fecha {
            padding: 25px;
        }
        
        .modo-trabajador .reportes-section .filtros-fecha input {
            padding: 14px 22px;
            font-size: 1rem;
        }
        
        .modo-trabajador .reportes-section .btn-generar-reporte,
        .modo-trabajador .reportes-section .btn-descargar,
        .modo-trabajador .reportes-section .btn-buscar-reporte {
            padding: 14px 28px;
            font-size: 1rem;
        }
        
        .modo-trabajador .reportes-section .reporte-table th,
        .modo-trabajador .reportes-section .reporte-table td {
            padding: 14px 12px;
        }
        
        /* Estilos para sección ATENDER en modo trabajador */
        .modo-trabajador .atender-section {
            border-radius: 0 !important;
            margin: 0 !important;
            min-height: calc(100vh - 70px);
            padding: 28px;
        }
        
        .modo-trabajador .gestionar-turno-layout {
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        
        .modo-trabajador .gestionar-izquierda,
        .modo-trabajador .gestionar-derecha {
            padding: 30px;
        }
        
        .modo-trabajador .turno-numero-grande {
            font-size: 2.2rem;
            padding: 18px;
        }
        
        /* Estilos para sección AGREGAR NIVEL en modo trabajador */
        .modo-trabajador .agregar-nivel-section {
            background: white;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        
        .modo-trabajador .agregar-nivel-section h2 {
            color: #0b2b5e;
            font-size: 1.6rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #ffd966;
            padding-bottom: 15px;
        }
        
        .modo-trabajador .agregar-nivel-section .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        
        .modo-trabajador .agregar-nivel-section .form-group label {
            display: block;
            font-weight: 600;
            color: #0b2b5e;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        
        .modo-trabajador .agregar-nivel-section .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
        }
        
        .modo-trabajador .agregar-nivel-section .form-group input:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        
        .modo-trabajador .btn-guardar-nivel-section {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            margin-right: 10px;
        }
        
        .modo-trabajador .btn-cancelar-nivel-section {
            background: #6c757d;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
        }
        
        /* Ajustes para pantallas más grandes */
        @media (min-width: 1400px) {
            .modo-trabajador .gestionar-turno-layout {
                gap: 40px;
            }
            .modo-trabajador .gestionar-izquierda,
            .modo-trabajador .gestionar-derecha {
                padding: 35px;
            }
            .modo-trabajador .turno-numero-grande {
                font-size: 2.5rem;
                padding: 20px;
            }
            .modo-trabajador .campo-fila .campo-valor-display {
                padding: 14px 18px;
                font-size: 1rem;
            }
            
            .modo-trabajador .servicios-section .servicios-grid {
                grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
                gap: 30px;
            }
        }
        
        /* ===== NUEVOS ESTILOS PARA ADMINISTRACIÓN DE BANNERS - ANCHO COMPLETO ===== */
        .banners-section-fullwidth {
            background: white;
            border-radius: 0px;
            padding: 28px;
            box-shadow: none;
            width: 100%;
            margin: 0;
            min-height: calc(100vh - 100px);
        }
        
        .modo-trabajador .banners-section-fullwidth {
            border-radius: 0;
            margin: 0;
            min-height: calc(100vh - 70px);
        }
        
        .banners-section-fullwidth h2 {
            color: #0b2b5e;
            font-size: 1.6rem;
            margin-bottom: 10px;
            border-bottom: 2px solid #ffd966;
            padding-bottom: 15px;
        }
        
        .banners-subtitle {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 25px;
        }
        
        .btn-agregar-banner {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            transition: all 0.3s;
        }
        
        .btn-agregar-banner:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.4);
        }
        
        .banners-lista-fullwidth {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 30px;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .banner-item-clickable {
            background: #f8fafc;
            border-radius: 16px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 5px solid #ffd966;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .banner-item-clickable:hover {
            background: #f1f5f9;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .banner-numero {
            width: 40px;
            height: 40px;
            background: #0b2b5e;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .banner-imagen-preview {
            width: 80px;
            height: 80px;
            background: #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .banner-imagen-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        
        /* Vista previa del color de fondo */
        .banner-color-preview {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
        }
        
        .banner-info {
            flex: 1;
        }
        
        .banner-info h4 {
            color: #0b2b5e;
            margin-bottom: 5px;
        }
        
        .banner-info p {
            color: #666;
            font-size: 0.75rem;
        }
        
        .btn-eliminar-banner {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 0.75rem;
            transition: all 0.2s;
        }
        
        .btn-eliminar-banner:hover {
            background: #b02a37;
        }
        
        .banners-duracion-fullwidth {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .banners-duracion-fullwidth label {
            display: block;
            font-weight: 600;
            color: #0b2b5e;
            margin-bottom: 8px;
        }
        
        .banners-duracion-fullwidth input {
            width: 150px;
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
        }
        
        .banners-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Estilos para selector de tipo de fondo */
        .tipo-fondo-group {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        .tipo-fondo-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: normal !important;
            margin-bottom: 0 !important;
        }
        
        .campo-color-fondo {
            margin-top: 10px;
            padding: 10px;
            background: #f8fafc;
            border-radius: 12px;
        }
        
        /* ===== MODAL PARA AGREGAR/EDITAR BANNER - MÁS ANCHO Y ORGANIZADO EN COLUMNAS ===== */
        .modal-banner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 10001;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-banner-card {
            background: white;
            border-radius: 28px;
            padding: 32px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        
        /* Organizar los campos en 2 columnas */
        .modal-banner-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 30px;
        }
        
        /* Campos que ocupan las dos columnas (ancho completo) */
        .modal-banner-grid .full-width {
            grid-column: span 2;
        }
        
        .modal-banner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #ffd966;
            padding-bottom: 15px;
        }
        .modal-banner-header h3 {
            color: #0b2b5e;
            font-size: 1.5rem;
            margin: 0;
        }
        .modal-banner-close {
            background: #dc3545;
            color: white;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
        }
        .form-row {
            margin-bottom: 15px;
        }
        .form-row label {
            display: block;
            font-weight: 600;
            color: #0b2b5e;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }
        .form-row input[type="text"],
        .form-row input[type="number"],
        .form-row input[type="url"],
        .form-row input[type="color"],
        .form-row textarea,
        .form-row select {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
        }
        .form-row input[type="color"] {
            width: 60px;
            height: 40px;
            padding: 2px;
        }
        .form-row input:focus,
        .form-row textarea:focus,
        .form-row select:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        .form-row textarea {
            resize: vertical;
            min-height: 80px;
        }
        .color-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .color-input-group input[type="color"] {
            width: 50px;
            height: 40px;
            padding: 2px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
        }
        .color-input-group input[type="text"] {
            flex: 1;
        }
        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: normal;
            margin-bottom: 0;
        }
        .modal-banner-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        /* El resto de tus estilos existentes continúan igual */
        .header-hospital { background: white; border-radius: 20px; padding: 15px 25px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid #ffd966; }
        .header-hospital h1 { color: #0b2b5e; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: white; border-radius: 15px; padding: 15px; display: flex; align-items: center; gap: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .stat-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; }
        .stat-info h3 { font-size: 0.75em; color: #666; }
        .stat-info .value { font-size: 1.5em; font-weight: bold; color: #0b2b5e; }
        .dashboard { display: block; margin-bottom: 25px; }
        .section-card { background: white; border-radius: 20px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid #e0e7ff; width: 100%; }
        .section-title { font-size: 1.1em; color: #0b2b5e; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; padding-bottom: 10px; border-bottom: 2px solid #ffd966; }
        .busqueda-container { display: flex; gap: 8px; margin-bottom: 15px; }
        .busqueda-container input { flex: 1; padding: 10px 15px; border: 2px solid #e0e7ff; border-radius: 50px; }
        .btn-buscar { background: linear-gradient(145deg, #28a745, #20c997); color: white; border: none; padding: 0 20px; border-radius: 50px; cursor: pointer; display: flex; align-items: center; gap: 6px; font-weight: bold; }
        .persona-info { background: linear-gradient(145deg, #e8f5e9, #d0ebff); padding: 12px; border-radius: 15px; margin-bottom: 15px; border: 2px solid #4caf50; display: none; }
        .persona-info .nombre-completo { font-weight: bold; color: #2e7d32; text-align: center; }
        .grid-nombres { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-top: 8px; }
        .campo { background: white; padding: 6px; border-radius: 8px; border-left: 3px solid #4caf50; }
        .campo-label { font-size: 0.6em; color: #666; }
        .campo-valor { font-size: 0.8em; font-weight: bold; color: #0b2b5e; }
        /* Estilo para el campo zona con color diferenciado */
        .campo-zona { background: #fff3cd; border-left: 3px solid #ffc107; }
        .campo-zona .campo-label { color: #856404; }
        .campo-zona .campo-valor { color: #856404; font-weight: bold; }
        .campo-zona.urbano { background: #d4edda; border-left: 3px solid #28a745; }
        .campo-zona.urbano .campo-label { color: #155724; }
        .campo-zona.urbano .campo-valor { color: #155724; }
        .campo-zona.rural { background: #f8d7da; border-left: 3px solid #dc3545; }
        .campo-zona.rural .campo-label { color: #721c24; }
        .campo-zona.rural .campo-valor { color: #721c24; }
        
        /* ===== NUEVO ESTILO PARA EL CAMPO EDAD ===== */
        .campo-edad { background: #e8f0fe; border-left: 3px solid #1565c0; }
        .campo-edad .campo-label { color: #0d47a1; }
        .campo-edad .campo-valor { color: #0d47a1; font-weight: bold; }
        .campo-edad .campo-valor i { color: #1565c0; margin-right: 4px; }
        /* ===== FIN NUEVO ESTILO ===== */
        
        .no-encontrado-mensaje { background: #fff3cd; border: 1px solid #ffc107; border-radius: 12px; padding: 15px; margin: 10px 0; text-align: center; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .no-encontrado-mensaje i { font-size: 28px; color: #856404; margin-bottom: 8px; display: block; }
        .no-encontrado-mensaje strong { color: #856404; font-size: 1rem; }
        .no-encontrado-mensaje p { margin-top: 5px; font-size: 0.85rem; color: #856404; }
        .btn-crear-paciente { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 10px 20px; border-radius: 30px; cursor: pointer; font-weight: 600; font-size: 0.85rem; margin-top: 12px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s; }
        .btn-crear-paciente:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(40,167,69,0.4); }
        .specialties-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 15px; }
        .specialty-card { background: #f8f9fa; border-radius: 12px; padding: 12px; text-align: center; cursor: pointer; transition: all 0.3s; border: 2px solid transparent; }
        .specialty-card:hover { transform: translateY(-3px); border-color: #ffd966; }
        .specialty-card.selected { border-color: #ffd966; background: #fff9e6; }
        .specialty-card.disabled { opacity: 0.5; cursor: not-allowed; pointer-events: none; }
        .specialty-icon { font-size: 1.8em; }
        .specialty-name { font-size: 0.8em; font-weight: bold; color: #0b2b5e; }
        .window-info { font-size: 0.65em; color: #666; }
        .btn-generate { width: 100%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 10px; border-radius: 50px; cursor: pointer; font-weight: bold; }
        .btn-generate:disabled { opacity: 0.5; cursor: not-allowed; }
        .servicios-section { background: white; border-radius: 24px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .servicios-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .servicios-header h2 { color: #0b2b5e; font-size: 1.6rem; display: flex; align-items: center; gap: 10px; }
        .servicios-header h2 i { color: #ffd966; }
        .btn-agregar-servicio { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 24px; border-radius: 50px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: all 0.3s; }
        .btn-agregar-servicio:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(40,167,69,0.4); }
        .servicios-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 20px; }
        .servicio-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s, box-shadow 0.2s; border-left: 5px solid #ffd966; position: relative; }
        .servicio-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
        .servicio-card h3 { color: #0b2b5e; font-size: 1.2rem; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; }
        .servicio-card .descripcion { color: #666; font-size: 0.85rem; margin-bottom: 12px; line-height: 1.4; }
        .servicio-card .badge-orden { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; margin-bottom: 12px; }
        .badge-orden.si { background: #dc3545; color: white; }
        .badge-orden.no { background: #28a745; color: white; }
        .servicio-card .estado { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; margin-bottom: 15px; }
        .estado.activo { background: #d4edda; color: #155724; }
        .estado.inactivo { background: #f8d7da; color: #721c24; }
        .servicio-card .card-actions { display: flex; gap: 10px; justify-content: flex-end; border-top: 1px solid #eee; padding-top: 12px; margin-top: 5px; }
        .card-actions button { background: none; border: none; cursor: pointer; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; transition: all 0.2s; }
        .card-actions .btn-editar-card { color: #007bff; background: #e6f2ff; }
        .card-actions .btn-editar-card:hover { background: #007bff; color: white; }
        .card-actions .btn-eliminar-card { color: #dc3545; background: #ffe6e6; }
        .card-actions .btn-eliminar-card:hover { background: #dc3545; color: white; }

        /* ===== MODAL SERVICIO ===== */
        #modalServicio {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 99999;
            justify-content: center;
            align-items: center;
        }
        #modalServicio.abierto {
            display: flex !important;
        }
        #modalServicio .modal-servicio-content {
            background: white;
            border-radius: 28px;
            padding: 32px;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            margin: 0 auto;
            max-height: 90vh;
            overflow-y: auto;
        }
        #modalServicio .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #ffd966;
            padding-bottom: 15px;
        }
        #modalServicio .modal-header h3 {
            color: #0b2b5e;
            font-size: 1.5rem;
            margin: 0;
        }
        #modalServicio .close-modal-servicio {
            background: #dc3545;
            color: white;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        #modalServicio .close-modal-servicio:hover {
            background: #b02a37;
            transform: scale(1.05);
        }
        #modalServicio .form-group {
            margin-bottom: 22px;
        }
        #modalServicio label {
            display: block;
            font-weight: 600;
            color: #0b2b5e;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        #modalServicio input[type="text"],
        #modalServicio textarea,
        #modalServicio select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: all 0.2s;
            font-family: inherit;
        }
        #modalServicio input:focus,
        #modalServicio textarea:focus,
        #modalServicio select:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        #modalServicio textarea {
            resize: vertical;
            min-height: 90px;
        }
        #modalServicio .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            padding: 10px 0;
        }
        #modalServicio .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin: 0;
            cursor: pointer;
            border-radius: 4px;
        }
        #modalServicio .checkbox-group label {
            margin: 0;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: normal;
        }
        #modalServicio .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        #modalServicio .btn-guardar-serv {
            padding: 12px 28px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        #modalServicio .btn-guardar-serv:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(40,167,69,0.4);
        }
        #modalServicio .btn-cancelar-serv {
            padding: 12px 28px;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            background: #6c757d;
            color: white;
        }
        #modalServicio .btn-cancelar-serv:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .modal-persona { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 2100; justify-content: center; align-items: center; }
        .modal-persona .modal-content { background: white; border-radius: 28px; padding: 32px; width: 90%; max-width: 550px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); }
        .modal-persona .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 3px solid #ffd966; padding-bottom: 15px; }
        .modal-persona .modal-header h2 { color: #0b2b5e; font-size: 1.5rem; display: flex; align-items; center; gap: 10px; }
        .modal-persona .close-modal-persona { background: #dc3545; color: white; border: none; width: 38px; height: 38px; border-radius: 50%; cursor: pointer; font-size: 20px; transition: 0.2s; }
        .modal-persona .close-modal-persona:hover { background: #b02a37; transform: scale(1.05); }
        .form-persona-group { margin-bottom: 18px; }
        .form-persona-group label { display: block; font-weight: 600; color: #0b2b5e; margin-bottom: 6px; font-size: 0.85rem; }
        .form-persona-group input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 16px; font-size: 0.9rem; transition: 0.2s; }
        .form-persona-group input:focus { border-color: #ffd966; outline: none; box-shadow: 0 0 0 3px rgba(255,217,102,0.3); }
        /* Estilo para el select de zona */
        .form-persona-group select { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 16px; font-size: 0.9rem; transition: 0.2s; background: white; }
        .form-persona-group select:focus { border-color: #ffd966; outline: none; box-shadow: 0 0 0 3px rgba(255,217,102,0.3); }
        .modal-persona-actions { display: flex; gap: 15px; justify-content: flex-end; margin-top: 28px; }
        .btn-guardar-persona { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; font-size: 0.9rem; transition: 0.2s; }
        .btn-guardar-persona:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(40,167,69,0.4); }
        .btn-cancelar-persona { background: #6c757d; color: white; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .btn-cancelar-persona:hover { background: #5a6268; }
        .reportes-section { background: white; border-radius: 24px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .report-header { margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #ffd966; }
        .report-header h2 { color: #0b2b5e; display: flex; align-items: center; gap: 12px; }
        .filtros-fecha { background: #f8fafc; padding: 20px; border-radius: 20px; margin-bottom: 25px; display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap; }
        .filtros-fecha input { padding: 12px 20px; border: 2px solid #e2e8f0; border-radius: 12px; }
        .btn-generar-reporte { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 28px; border-radius: 50px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 10px; }
        .acciones-reporte { display: flex; gap: 15px; justify-content: flex-end; margin-bottom: 25px; }
        .btn-descargar, .btn-buscar-reporte { border: none; padding: 10px 20px; border-radius: 50px; cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
        .btn-descargar { background: #17a2b8; color: white; }
        .btn-buscar-reporte { background: #667eea; color: white; }
        .reporte-table { width: 100%; border-collapse: collapse; }
        .reporte-table th { background: linear-gradient(135deg, #0b2b5e, #1e4a8a); color: white; padding: 12px; text-align: left; }
        .reporte-table td { padding: 10px; border-bottom: 1px solid #eef2f8; }
        .estado-activo { background: #e6f7e6; color: #2e7d32; padding: 4px 12px; border-radius: 40px; display: inline-block; }
        .estado-finalizado { background: #e3f2fd; color: #0b5e7e; padding: 4px 12px; border-radius: 40px; display: inline-block; }
        .footer-reporte { text-align: center; padding-top: 20px; margin-top: 10px; color: #64748b; }
        .notification { position: fixed; top: 20px; right: 20px; background: #28a745; color: white; padding: 12px 24px; border-radius: 50px; z-index: 3000; display: none; }
        .modal, .modal-history, .modal-turno-generado { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; overflow-y: auto; }
        .modal-content { max-width: 800px; margin: 50px auto; background: white; border-radius: 25px; padding: 25px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #ffd966; padding-bottom: 15px; }
        .close-modal { background: #dc3545; color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; }
        .turno-generado-content { text-align: center; max-width: 500px; }
        .turno-generado-icon { font-size: 4rem; color: #28a745; margin-bottom: 20px; }
        .turno-generado-numero { background: linear-gradient(135deg, #ffd966, #ffc107); color: #0b2b5e; font-size: 3rem; font-weight: 800; padding: 20px 30px; border-radius: 20px; display: inline-block; margin: 20px 0; letter-spacing: 3px; box-shadow: 0 8px 20px rgba(0,0,0,0.2); }
        .turno-generado-paciente { font-size: 1.2rem; color: #2e7d32; font-weight: 600; margin: 15px 0; }
        .turno-generado-servicio { background: #f8f9fa; padding: 12px; border-radius: 12px; margin: 15px 0; }
        .turno-generado-ventanilla { background: #e3f2fd; padding: 10px; border-radius: 12px; margin: 10px 0; }
        .btn-cerrar-turno { background: #007bff; color: white; border: none; padding: 12px 30px; border-radius: 50px; cursor: pointer; font-size: 1rem; font-weight: 600; margin-top: 20px; transition: all 0.3s; }

        /* ===== SECCIÓN ATENDER TURNOS ===== */
        .atender-section {
            background: white;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .atender-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
            border-bottom: 2px solid #ffd966;
            padding-bottom: 15px;
        }
        .atender-header h2 {
            color: #0b2b5e;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .atender-header h2 i {
            color: #ffd966;
        }
        .gestionar-turno-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            min-height: 400px;
        }
        .gestionar-izquierda {
            background: #f8fafc;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #e0e7ff;
        }
        .gestionar-titulo { text-align: center; font-size: 1.1rem; color: #0b2b5e; font-weight: 700; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 2px solid #ffd966; }
        .turno-numero-grande { background: #1565c0; color: white; font-size: 2rem; font-weight: 800; text-align: center; padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; letter-spacing: 3px; box-shadow: 0 4px 12px rgba(21,101,192,0.4); }
        .turno-numero-grande.vacio { background: #e0e7ff; color: #9e9e9e; font-size: 1rem; letter-spacing: 1px; }
        .campo-fila { margin-bottom: 14px; }
        .campo-fila label { display: block; font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .campo-fila .campo-valor-display { background: white; border: 1.5px solid #e0e7ff; border-radius: 8px; padding: 10px 14px; font-size: 0.9rem; font-weight: 600; color: #0b2b5e; min-height: 40px; }
        .campo-fila .campo-valor-display.vacio { color: #bbb; font-weight: 400; }
        .campos-dos-col { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .gestionar-derecha {
            background: #f8fafc;
            border-radius: 20px;
            padding: 25px;
            border: 1px solid #e0e7ff;
        }
        .datos-usuario-titulo { text-align: right; font-size: 0.85rem; color: #888; margin-bottom: 15px; font-weight: 600; }
        .usuario-avatar { display: flex; justify-content: center; margin-bottom: 18px; }
        .avatar-circulo { width: 80px; height: 80px; background: #e0e7ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #c7d2fe; }
        .avatar-circulo i { font-size: 2.5rem; color: #6b7280; }
        .selector-servicio { margin-bottom: 15px; }
        .selector-servicio label { font-size: 0.7rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; display: block; }
        .selector-servicio select { width: 100%; padding: 10px 14px; border: 1.5px solid #e0e7ff; border-radius: 8px; font-size: 0.9rem; font-weight: 600; color: #0b2b5e; background: white; cursor: pointer; }
        .modulo-selector-wrapper { position: relative; margin-bottom: 15px; }
        .modulo-btn-click { background: white; border: 1.5px solid #e0e7ff; border-radius: 10px; padding: 14px 18px; cursor: pointer; width: 100%; transition: all 0.2s; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
        .modulo-btn-click:hover { border-color: #667eea; background: #f0f4ff; }
        .modulo-btn-click .modulo-nombre-text { font-weight: 700; color: #0b2b5e; font-size: 0.95rem; }
        .modulo-btn-click .modulo-chevron { color: #667eea; transition: transform 0.2s; font-size: 0.85rem; }
        .modulo-btn-click.open .modulo-chevron { transform: rotate(180deg); }
        .modulo-dropdown { display: none; position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: white; border: 1.5px solid #e0e7ff; border-radius: 12px; z-index: 999; box-shadow: 0 10px 30px rgba(0,0,0,0.18); overflow: hidden; }
        .modulo-dropdown.open { display: block; animation: dropFade 0.18s ease; }
        @keyframes dropFade { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
        .modulo-option { padding: 13px 18px; cursor: pointer; display: flex; align-items: center; gap: 12px; font-weight: 600; color: #0b2b5e; font-size: 0.88rem; transition: background 0.15s; border-bottom: 1px solid #f0f4ff; }
        .modulo-option:last-child { border-bottom: none; }
        .modulo-option:hover { background: #eef2ff; }
        .modulo-option.active-mod { background: #dde8ff; color: #1565c0; }
        .modulo-option .mod-icon { width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 800; flex-shrink: 0; }
        .modulo-option.active-mod .mod-icon { background: linear-gradient(135deg, #1565c0, #0d47a1); }
        .mod-info-text { display: flex; flex-direction: column; }
        .mod-info-name { font-weight: 700; }
        .mod-info-count { font-size: 0.72rem; color: #888; font-weight: 400; margin-top: 1px; }
        .modulo-info-box { background: white; border: 1.5px solid #e0e7ff; border-radius: 10px; padding: 14px; margin-bottom: 15px; }
        .modulo-nombre { font-weight: 700; color: #0b2b5e; font-size: 0.95rem; margin-bottom: 10px; }
        .modulo-contadores { display: flex; gap: 8px; flex-wrap: wrap; }
        .contador-badge { padding: 4px 12px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
        .contador-badge.activos { background: #28a745; color: white; }
        .contador-badge.atendidos { background: #007bff; color: white; }
        .contador-badge.totales { background: #6c757d; color: white; }
        .btn-ver-turnos-modal { width: 100%; background: #1565c0; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .btn-ver-turnos-modal:hover { background: #0d47a1; }
        .turnos-espera-lista { max-height: 220px; overflow-y: auto; display: none; }
        .turnos-espera-lista.visible { display: block; }
        .turno-espera-item { background: white; border-left: 4px solid #1565c0; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; cursor: pointer; transition: 0.2s; display: flex; justify-content: space-between; align-items: center; }
        .turno-espera-item:hover { background: #e3f2fd; }
        .turno-espera-item.seleccionado { background: #e3f2fd; border-left-color: #ffd966; }
        .turno-espera-num { font-weight: 800; color: #1565c0; font-size: 1rem; }
        .turno-espera-pac { font-size: 0.75rem; color: #555; }
        .turno-actions-lista { display: flex; gap: 6px; margin-left: 10px; }
        .btn-llamar-lista, .btn-atender-lista { border: none; padding: 5px 12px; border-radius: 6px; cursor: pointer; font-size: 0.7em; font-weight: bold; }
        .btn-llamar-lista { background: #28a745; color: white; }
        .btn-atender-lista { background: #007bff; color: white; }
        @media (max-width: 700px) { .gestionar-turno-layout { grid-template-columns: 1fr; } }
        @media (max-width: 1024px) { .sidebar { width: 280px; } .main-content { margin-left: 280px; } .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .sidebar { width: 100%; position: relative; height: auto; } .main-content { margin-left: 0; } .app-layout { flex-direction: column; } }
        .usuarios-section { background: white; border-radius: 24px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .usuarios-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; border-bottom: 2px solid #ffd966; padding-bottom: 15px; }
        .usuarios-header h2 { color: #0b2b5e; display: flex; align-items: center; gap: 10px; }
        .form-usuario-container { background: #f8fafc; border-radius: 20px; padding: 25px; margin-bottom: 30px; border: 1px solid #e0e7ff; }
        .form-usuario-container h3 { color: #0b2b5e; margin-bottom: 20px; font-size: 1.2rem; border-left: 4px solid #ffd966; padding-left: 12px; }
        .form-usuario-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .form-usuario-field { margin-bottom: 15px; }
        .form-usuario-field label { display: block; font-weight: 600; color: #0b2b5e; margin-bottom: 6px; font-size: 0.8rem; text-transform: uppercase; }
        .form-usuario-field input, .form-usuario-field select { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; transition: 0.2s; }
        .form-usuario-field input:focus, .form-usuario-field select:focus { border-color: #ffd966; outline: none; box-shadow: 0 0 0 3px rgba(255,217,102,0.3); }
        
        /* ===== NUEVO ESTILO PARA SELECTOR MÚLTIPLE DE MÓDULOS ===== */
        .modulos-select-container {
            position: relative;
            width: 100%;
        }
        .modulos-select-btn {
            width: 100%;
            padding: 12px 15px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: inherit;
        }
        .modulos-select-btn:hover {
            border-color: #667eea;
        }
        .modulos-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 1000;
            display: none;
            max-height: 250px;
            overflow-y: auto;
        }
        .modulos-dropdown.open {
            display: block;
        }
        .modulo-check-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .modulo-check-option:hover {
            background: #f1f5f9;
        }
        .modulo-check-option input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin: 0;
        }
        .modulo-check-option label {
            cursor: pointer;
            font-size: 0.9rem;
            color: #1e293b;
            margin: 0;
            text-transform: none;
            letter-spacing: normal;
            font-weight: 400;
        }
        
        .form-usuario-actions { display: flex; gap: 15px; justify-content: flex-end; margin-top: 25px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .btn-guardar-usuario-form { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; }
        .btn-actualizar-usuario { background: linear-gradient(135deg, #ffc107, #ff9800); color: #0b2b5e; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; }
        .btn-cancelar-usuario-form { background: #6c757d; color: white; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; }
        .usuarios-list { margin-top: 30px; }
        .usuarios-list h3 { color: #0b2b5e; margin-bottom: 15px; font-size: 1.1rem; }
        .usuarios-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; }
        .usuario-card { background: #f8fafc; border-radius: 16px; padding: 18px; border-left: 5px solid #ffd966; transition: 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .usuario-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
        .usuario-card h4 { color: #0b2b5e; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .usuario-card p { margin: 5px 0; color: #4b5563; font-size: 0.85rem; }
        .usuario-card .modulos-asignados { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
        .modulo-badge { background: #667eea; color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.7rem; }
        .btn-eliminar-usuario-card { background: #dc3545; color: white; border: none; padding: 6px 14px; border-radius: 30px; cursor: pointer; font-size: 0.7rem; margin-top: 12px; transition: 0.2s; }
        .btn-eliminar-usuario-card:hover { background: #b02a37; }
        .btn-editar-usuario-card { background: #ffc107; color: #0b2b5e; border: none; padding: 6px 14px; border-radius: 30px; cursor: pointer; font-size: 0.7rem; margin-top: 12px; margin-right: 8px; transition: 0.2s; }
        .btn-editar-usuario-card:hover { background: #e0a800; }
        .acceso-denegado-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; display: flex; justify-content: center; align-items: center; animation: fadeIn 0.3s ease; }
        .acceso-denegado-card { background: white; border-radius: 24px; padding: 40px; text-align: center; max-width: 400px; width: 90%; box-shadow: 0 20px 40px rgba(0,0,0,0.3); border-top: 8px solid #dc3545; }
        .acceso-denegado-card i { font-size: 64px; color: #dc3545; margin-bottom: 20px; }
        .acceso-denegado-card h2 { color: #0b2b5e; margin-bottom: 10px; }
        .acceso-denegado-card p { color: #666; margin-bottom: 25px; }
        .acceso-denegado-card button { background: #0b2b5e; color: white; border: none; padding: 12px 30px; border-radius: 40px; cursor: pointer; font-weight: bold; transition: all 0.3s; }
        .acceso-denegado-card button:hover { background: #1e4a8a; transform: scale(1.02); }
        .perfil-section { background: white; border-radius: 24px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .perfil-header { margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid #ffd966; }
        .perfil-header h2 { color: #0b2b5e; font-size: 1.3rem; display: flex; align-items: center; gap: 10px; }
        .perfil-search { margin-bottom: 25px; display: flex; gap: 10px; align-items: flex-end; }
        .perfil-search label { display: block; font-weight: 600; color: #0b2b5e; margin-bottom: 8px; font-size: 0.85rem; }
        .perfil-search .search-input-wrapper { flex: 1; }
        .perfil-search input { width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 0.9rem; }
        .btn-buscar-perfil { background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; padding: 12px 24px; border-radius: 12px; cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 8px; height: 48px; transition: all 0.3s; }
        .btn-buscar-perfil:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
        .permisos-container { display: flex; flex-direction: column; gap: 15px; margin-bottom: 30px; }
        .permiso-item { background: #f8fafc; border-radius: 16px; padding: 18px 20px; border-left: 4px solid #ffd966; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .permiso-info h4 { color: #0b2b5e; margin-bottom: 5px; font-size: 1rem; }
        .permiso-info p { color: #666; font-size: 0.75rem; }
        .permiso-buttons { display: flex; gap: 20px; align-items: center; }
        .permiso-buttons label { display: flex; align-items: center; gap: 6px; cursor: pointer; font-weight: 500; color: #4b5563; }
        .permiso-buttons input { width: 18px; height: 18px; cursor: pointer; margin: 0; }
        .perfil-actions { display: flex; gap: 15px; justify-content: flex-end; margin-top: 20px; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .btn-actualizar-perfil { background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; }
        .btn-guardar-perfil { background: linear-gradient(135deg, #ffc107, #ff9800); color: #0b2b5e; border: none; padding: 12px 28px; border-radius: 40px; cursor: pointer; font-weight: bold; }
        .perfil-footer { text-align: center; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e2e8f0; color: #666; font-size: 0.8rem; }

        /* ===== ESTILO PARA OCULTAR EL FORMULARIO DE USUARIO ===== */
        .form-usuario-container {
            display: none;
        }
        .form-usuario-container.visible {
            display: block;
        }
        
        /* ===== NUEVO ESTILO PARA MODAL DE NIVEL ACCESO ===== */
        .modal-nivel-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 10001;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-nivel-card {
            background: white;
            border-radius: 28px;
            padding: 32px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        .modal-nivel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #ffd966;
            padding-bottom: 15px;
        }
        .modal-nivel-header h3 {
            color: #0b2b5e;
            font-size: 1.5rem;
            margin: 0;
        }
        .modal-nivel-close {
            background: #dc3545;
            color: white;
            border: none;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
        }
        .modal-nivel-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
        }
        .btn-guardar-nivel {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-cancelar-nivel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
        }
        
        /* Estilos para la sección de agregar nivel (modo trabajador) */
        .agregar-nivel-section {
            background: white;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }
        .agregar-nivel-section h2 {
            color: #0b2b5e;
            font-size: 1.6rem;
            margin-bottom: 20px;
            border-bottom: 2px solid #ffd966;
            padding-bottom: 15px;
        }
        .agregar-nivel-section .form-group {
            margin-bottom: 25px;
            text-align: left;
        }
        .agregar-nivel-section .form-group label {
            display: block;
            font-weight: 600;
            color: #0b2b5e;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .agregar-nivel-section .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
        }
        .agregar-nivel-section .form-group input:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        .btn-guardar-nivel-section {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            margin-right: 10px;
        }
        .btn-cancelar-nivel-section {
            background: #6c757d;
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
        }
        
        /* ===== ESTILO PARA EL BOTÓN DE OJO EN CONTRASEÑAS ===== */
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-wrapper input {
            width: 100%;
            padding: 12px 50px 12px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            transition: all 0.2s;
            font-family: inherit;
            background: #ffffff;
        }
        .password-wrapper input:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        .toggle-password-btn {
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
        .toggle-password-btn:hover {
            color: #43a047;
        }

        /* ===== ESTILOS PARA MODAL DE CONFIRMACIÓN RURAL ===== */
        .modal-confirmacion-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.85);
            z-index: 999999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .modal-confirmacion-overlay.active {
            display: flex;
        }
        .modal-confirmacion-card {
            background: white;
            border-radius: 28px;
            padding: 40px;
            width: 90%;
            max-width: 450px;
            text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            animation: fadeIn 0.3s ease;
        }
        .modal-confirmacion-card .icono-warning {
            font-size: 4rem;
            color: #ffc107;
            margin-bottom: 15px;
        }
        .modal-confirmacion-card h2 {
            color: #0b2b5e;
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        .modal-confirmacion-card p {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 10px;
            line-height: 1.5;
        }
        .modal-confirmacion-card .paciente-nombre {
            font-weight: 700;
            color: #0b2b5e;
            font-size: 1.1rem;
            background: #f8fafc;
            padding: 10px;
            border-radius: 10px;
            margin: 10px 0 20px 0;
        }
        .modal-confirmacion-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }
        .modal-confirmacion-buttons button {
            padding: 12px 35px;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-confirmar-si {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        .btn-confirmar-si:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.4);
        }
        .btn-confirmar-no {
            background: #dc3545;
            color: white;
        }
        .btn-confirmar-no:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220,53,69,0.4);
        }
        
        /* ===== ESTILOS PARA SECCIÓN CONFIGURACIÓN ===== */
        .configuracion-section {
            background: white;
            border-radius: 24px;
            padding: 28px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            max-width: 700px;
            margin: 0 auto;
        }
        .configuracion-section h2 {
            color: #0b2b5e;
            font-size: 1.6rem;
            margin-bottom: 25px;
            border-bottom: 2px solid #ffd966;
            padding-bottom: 15px;
        }
        .configuracion-section .form-group {
            margin-bottom: 25px;
        }
        .configuracion-section .form-group label {
            display: block;
            font-weight: 600;
            color: #0b2b5e;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .configuracion-section .form-group label i {
            color: #ffd966;
            margin-right: 8px;
        }
        .configuracion-section .form-group input[type="text"] {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .configuracion-section .form-group input[type="text"]:focus {
            border-color: #ffd966;
            outline: none;
            box-shadow: 0 0 0 3px rgba(255,217,102,0.3);
        }
        .configuracion-section .form-group input[type="file"] {
            width: 100%;
            padding: 12px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 0.9rem;
            background: #f8fafc;
        }
        .configuracion-section .form-group input[type="file"]:hover {
            border-color: #ffd966;
        }
        .configuracion-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .btn-guardar-config {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn-guardar-config:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40,167,69,0.4);
        }
        .btn-cancelar-config {
            background: #6c757d;
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 40px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
            transition: all 0.3s;
        }
        .btn-cancelar-config:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        .modo-trabajador .configuracion-section {
            border-radius: 0;
            margin: 0;
            max-width: 100%;
            padding: 28px;
            min-height: calc(100vh - 70px);
        }
    </style>
</head>
<body>
    <div class="notification" id="notification"></div>

    <!-- Modal Turno Generado -->
    <div class="modal-turno-generado" id="turnoGeneradoModal">
        <div class="modal-content turno-generado-content">
            <div class="modal-header">
                <h2><i class="fas fa-ticket-alt"></i> ¡Turno Generado!</h2>
                <button class="close-modal" onclick="cerrarTurnoGenerado()">&times;</button>
            </div>
            <div class="turno-generado-icon"><i class="fas fa-check-circle"></i></div>
            <div class="turno-generado-numero" id="turnoGeneradoNumero"></div>
            <div class="turno-generado-paciente" id="turnoGeneradoPaciente"></div>
            <div class="turno-generado-servicio" id="turnoGeneradoServicio"></div>
            <div class="turno-generado-ventanilla" id="turnoGeneradoVentanilla"></div>
            <button class="btn-cerrar-turno" onclick="cerrarTurnoGenerado()"><i class="fas fa-check"></i> Aceptar</button>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN PARA PACIENTES RURALES -->
    <div class="modal-confirmacion-overlay" id="modalConfirmacionRural">
        <div class="modal-confirmacion-card">
            <div class="icono-warning"><i class="fas fa-triangle-exclamation"></i></div>
            <h2>⚠️ Paciente de Zona Rural</h2>
            <p>Este paciente es de <strong>ZONA RURAL</strong>.</p>
            <p style="font-size: 0.85rem; color: #888;">¿Desea continuar con la generación del turno?</p>
            <div class="paciente-nombre" id="confirmacionPacienteNombre">Cargando...</div>
            <div class="modal-confirmacion-buttons">
                <button class="btn-confirmar-si" id="btnConfirmarSi"><i class="fas fa-check"></i> Sí, continuar</button>
                <button class="btn-confirmar-no" id="btnConfirmarNo"><i class="fas fa-times"></i> No, cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal Historial -->
    <div class="modal-history" id="historyModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-history"></i> Historial Completo de Turnos</h2>
                <button class="close-modal" onclick="cerrarHistorial()">&times;</button>
            </div>
            <button onclick="limpiarHistorial()" style="background:#dc3545; color:white; border:none; padding:8px 20px; border-radius:20px; margin-bottom:15px; cursor:pointer;"><i class="fas fa-trash-alt"></i> Limpiar todo el historial</button>
            <table style="width:100%; border-collapse:collapse;">
                <thead><tr><th>Turno</th><th>Paciente</th><th>Cédula</th><th>Hora</th><th>Estado</th><th>Acción</th></tr></thead>
                <tbody id="historyModalBody"></tbody>
            </table>
        </div>
    </div>

    <!-- MODAL AGREGAR PERSONA (PACIENTE) - CON FECHA DE NACIMIENTO -->
    <div id="modalAgregarPersona" class="modal-persona">
        <div class="modal-content">
            <div class="modal-header"><h2><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h2><button class="close-modal-persona" onclick="cerrarModalPersona()">&times;</button></div>
            <form id="formRegistrarPersona">
                <div class="form-persona-group"><label><i class="fas fa-id-card"></i> Identificación *</label><input type="text" id="regIdentificacion" placeholder="" required></div>
                <div class="form-persona-group"><label><i class="fas fa-user"></i> Primer Nombre *</label><input type="text" id="regPrimerNombre" placeholder="" required></div>
                <div class="form-persona-group"><label><i class="fas fa-user"></i> Segundo Nombre</label><input type="text" id="regSegundoNombre" placeholder=""></div>
                <div class="form-persona-group"><label><i class="fas fa-user-tag"></i> Primer Apellido *</label><input type="text" id="regPrimerApellido" placeholder="" required></div>
                <div class="form-persona-group"><label><i class="fas fa-user-tag"></i> Segundo Apellido</label><input type="text" id="regSegundoApellido" placeholder=""></div>
                <!-- CAMPO ZONA -->
                <div class="form-persona-group">
                    <label><i class="fas fa-map-marker-alt"></i> Zona *</label>
                    <select id="regZona" required>
                        <option value="">Seleccionar zona...</option>
                        <option value="U">URBANO</option>
                        <option value="R">RURAL</option>
                    </select>
                </div>
                <!-- ===== NUEVO CAMPO: FECHA DE NACIMIENTO ===== -->
                <div class="form-persona-group">
                    <label><i class="fas fa-calendar-alt"></i> Fecha de Nacimiento *</label>
                    <input type="date" id="regFechaNacimiento" required>
                </div>
                <!-- ===== FIN NUEVO CAMPO ===== -->
                <div class="modal-persona-actions">
                    <button type="button" class="btn-cancelar-persona" onclick="cerrarModalPersona()">Cancelar</button>
                    <button type="submit" class="btn-guardar-persona"><i class="fas fa-save"></i> Guardar Paciente</button>
                </div>
            </form>
        </div>
    </div>

    <div class="app-layout">
        <div class="sidebar" id="mainSidebar">
            <div class="sidebar-header">
                <!-- LOGO DINÁMICO DE LA EMPRESA -->
                @if(isset($configuracion) && $configuracion && $configuracion->logo_empresa_url)
                    <img src="{{ asset($configuracion->logo_empresa_url) }}" alt="Logo Empresa" class="logo-empresa">
                @else
                    <i class="fas fa-hospital-user" style="font-size: 2.5rem; color: #ffd966; display: block; margin: 0 auto 10px auto;"></i>
                @endif
                <!-- NOMBRE DINÁMICO DE LA EMPRESA Y DIRECCIÓN -->
                <h2 style="margin-bottom: 2px;"><i class="fas fa-hospital-user"></i> {{ $configuracion->nombre_empresa ?? 'E.S.E HOSPITAL LOCAL SANPABLO' }}</h2>
                @if(isset($configuracion) && $configuracion && $configuracion->direccion_empresa)
                    <div style="font-size: 0.55rem; color: #c7d2fe; margin-top: 2px; padding: 0 5px; border-top: 1px solid rgba(255,215,0,0.2); padding-top: 6px;">
                        <i class="fas fa-map-pin" style="font-size: 0.5rem; margin-right: 4px;"></i>
                        {{ $configuracion->direccion_empresa }}
                    </div>
                @endif
            </div>
            <div class="nav-menu">
                <!-- INICIO -->
                <div class="nav-section">
                    <div class="nav-section-title">INICIO</div>
                    <div class="nav-item" onclick="mostrarSeccion('inicio')"><i class="fas fa-home"></i><span>Inicio</span></div>
                </div>
                
                <!-- SERVICIOS - Agrupa Servicios y Agregar Nivel de Acceso -->
                <div class="nav-section">
                    <div class="nav-section-title">SERVICIOS</div>
                    <div class="nav-item" id="navServicios" onclick="verificarAcceso('servicios')"><i class="fas fa-stethoscope"></i><span>Servicios</span></div>
                    <div class="nav-item" onclick="abrirModalNivelAcceso()"><i class="fas fa-plus-circle"></i><span>Agregar Nivel de Acceso</span></div>
                </div>
                
                <!-- USUARIOS - Agrupa Usuarios, Atender Turnos y Perfil -->
                <div class="nav-section">
                    <div class="nav-section-title">USUARIOS</div>
                    <div class="nav-item" id="navUsuarios" onclick="verificarAcceso('usuarios')"><i class="fas fa-users"></i><span>Usuarios</span></div>
                    <div class="nav-item" id="navAtenderTurnos" onclick="verificarAcceso('atender_turnos')"><i class="fas fa-users"></i><span>Atender Turnos</span></div>
                    <div class="nav-item" id="navPerfil" onclick="verificarAcceso('perfil')"><i class="fas fa-id-card"></i><span>Perfil</span></div>
                </div>
                
                <!-- PUBLICIDAD TV + CONFIGURACIÓN -->
                <div class="nav-section">
                    <div class="nav-section-title" style="margin-top: 15px; border-top: 1px solid rgba(255,215,0,0.3); padding-top: 15px;">PARAMETRO DE CONFIGURACION</div>
                    <div class="nav-item" id="navPublicidadTV" onclick="mostrarSeccion('publicidad_tv')">
                        <i class="fas fa-tv"></i><span>Publicidad TV</span>
                    </div>
                    <div class="nav-item" onclick="mostrarSeccion('configuracion')">
                        <i class="fas fa-cog"></i><span>Configuración</span>
                    </div>
                </div>
                
                <!-- REPORTES -->
                <div class="nav-section">
                    <div class="nav-section-title">REPORTES</div>
                    <div class="nav-item" id="navReportes" onclick="verificarAcceso('reportes')"><i class="fas fa-chart-bar"></i><span>Reportes</span></div>
                </div>
                
                <!-- SALIR -->
                <div class="nav-section">
                    <div class="nav-section-title">SALIR</div>
                    <div class="nav-item" onclick="cerrarSesion()"><i class="fas fa-sign-out-alt"></i><span>Cerrar Sesión</span></div>
                </div>
            </div>
            <div class="voice-control"><h3><i class="fas fa-volume-up"></i> Control de Voz</h3><div class="voice-buttons"><button class="voice-btn" id="voiceOnBtn" onclick="toggleVoice(true)">Activar</button><button class="voice-btn" id="voiceOffBtn" onclick="toggleVoice(false)">Desactivar</button></div><div id="voiceStatus">✅ Voz activada</div></div>
        </div>

        <div class="main-content" id="mainContent">
            <div id="modulosBarContainer" style="display: none;"></div>
            
            <div id="seccion-inicio">
                <div class="header-hospital"><h1><i class="fas fa-ticket-alt"></i> Gestión de Turnos</h1><p>Administre los turnos de los pacientes - Llamado en voz alta</p></div>
                <div class="stats-grid">
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3>Atendidos</h3><div class="value" id="statsAtendidos">0</div></div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-location-dot"></i></div><div class="stat-info"><h3>Atendidos Zona Rural</h3><div class="value" id="statsZona">0</div></div></div>
                    <div class="stat-card"><div class="stat-icon" style="background: linear-gradient(135deg, #ff6b6b, #ee5a24);"><i class="fas fa-clock"></i></div><div class="stat-info"><h3>Total por atender Zona Rural</h3><div class="value" id="statsZonaPendiente">0</div></div></div>
                </div>
                <div class="dashboard">
                    <div class="section-card">
                        <div class="section-title"><i class="fas fa-user-plus"></i><span>Registrar Nuevo Turno</span></div>
                        <div class="busqueda-container">
                            <input type="text" id="cedula" placeholder="Número de cédula">
                            <button class="btn-buscar" id="btnBuscar"><i class="fas fa-search"></i> Buscar</button>
                        </div>
                        <div id="resultadoBusqueda"></div>
                        <div class="persona-info" id="personaInfo">
                            <div class="nombre-completo" id="personaNombreCompleto"></div>
                            <div class="grid-nombres">
                                <div class="campo"><div class="campo-label">Primer Nombre</div><div class="campo-valor" id="primerNombre"></div></div>
                                <div class="campo"><div class="campo-label">Segundo Nombre</div><div class="campo-valor" id="segundoNombre"></div></div>
                                <div class="campo"><div class="campo-label">Primer Apellido</div><div class="campo-valor" id="primerApellido"></div></div>
                                <div class="campo"><div class="campo-label">Segundo Apellido</div><div class="campo-valor" id="segundoApellido"></div></div>
                                <div class="campo" id="campoZona" style="display:none;">
                                    <div class="campo-label">Zona </div>
                                    <div class="campo-valor" id="personaZona"></div>
                                </div>
                                <!-- ===== NUEVO CAMPO: FECHA DE NACIMIENTO ===== -->
                                <div class="campo" id="campoFechaNacimiento" style="display:none;">
                                    <div class="campo-label">Fecha de Nacimiento</div>
                                    <div class="campo-valor" id="personaFechaNacimiento"></div>
                                </div>
                                <!-- ===== NUEVO CAMPO: EDAD (se muestra junto a la fecha) ===== -->
                                <div class="campo campo-edad" id="campoEdad" style="display:none;">
                                    <div class="campo-label"><i class="fas fa-cake-candles"></i> Edad</div>
                                    <div class="campo-valor" id="personaEdad"></div>
                                </div>
                                <!-- ===== FIN NUEVO CAMPO EDAD ===== -->
                            </div>
                        </div>
                        <div class="specialties-grid" id="serviciosGridInicio"></div>
                        <button class="btn-generate" id="generateBtn" disabled>Generar Turno</button>
                    </div>
                </div>
            </div>

            <div id="seccion-servicios" style="display:none;">
                <div class="servicios-section">
                    <div class="servicios-header">
                        <h2><i class="fas fa-stethoscope"></i> Servicios Médicos</h2>
                        <button class="btn-agregar-servicio" onclick="abrirModalServicio()"><i class="fas fa-plus"></i> Agregar Servicio</button>
                    </div>
                    <div class="servicios-grid" id="serviciosGridContainer"></div>
                </div>
            </div>

            <div id="seccion-usuarios" style="display:none;">
                <div class="usuarios-section">
                    <div class="usuarios-header">
                        <button class="btn-agregar-servicio" onclick="mostrarFormularioUsuario()"><i class="fas fa-user-plus"></i> Agregar Usuario</button>
                    </div>
                    <div class="form-usuario-container" id="formUsuarioContainer">
                        <h3><i class="fas fa-user-edit"></i> Registrar Usuario</h3>
                        <form id="formRegistrarUsuarioNuevo">
                            <div class="form-usuario-grid">
                                <div class="form-usuario-field"><label>NOMBRES</label><input type="text" id="nombresUsuario" required></div>
                                <div class="form-usuario-field"><label>APELLIDOS</label><input type="text" id="apellidosUsuario" required></div>
                                <div class="form-usuario-field"><label>CÉDULA</label><input type="text" id="cedulaUsuario" value="" required></div>
                                <div class="form-usuario-field">
                                    <label>CONTRASEÑA</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="passwordUsuarioNuevo" value="">
                                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('passwordUsuarioNuevo', this)"><i class="fas fa-eye-slash"></i></button>
                                    </div>
                                </div>
                                <div class="form-usuario-field">
                                    <label>ACTUALIZAR CONTRASEÑA</label>
                                    <div class="password-wrapper">
                                        <input type="password" id="nuevaPasswordUsuario" value="" placeholder="">
                                        <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('nuevaPasswordUsuario', this)"><i class="fas fa-eye-slash"></i></button>
                                    </div>
                                </div>
                                <div class="form-usuario-field"><label>USUARIO / ASESOR</label><input type="text" id="usuarioAsesor"></div>
                                <div class="form-usuario-field"><label>SERVICIO</label><select id="servicioUsuario"><option value="">Seleccionar servicio</option></select></div>
                                <div class="form-usuario-field">
                                    <label>MÓDULOS DE ACCESO</label>
                                    <div class="modulos-select-container" id="modulosSelectContainer">
                                        <button type="button" class="modulos-select-btn" id="modulosSelectBtn">
                                            <span id="modulosSelectText">Seleccionar módulos</span>
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="modulos-dropdown" id="modulosDropdown">
                                            <div class="modulo-check-option" data-modulo="1">
                                                <input type="checkbox" value="1" id="moduloCheck1">
                                                <label for="moduloCheck1">MÓDULO 1</label>
                                            </div>
                                            <div class="modulo-check-option" data-modulo="2">
                                                <input type="checkbox" value="2" id="moduloCheck2">
                                                <label for="moduloCheck2">MÓDULO 2</label>
                                            </div>
                                            <div class="modulo-check-option" data-modulo="3">
                                                <input type="checkbox" value="3" id="moduloCheck3">
                                                <label for="moduloCheck3">MÓDULO 3</label>
                                            </div>
                                            <div class="modulo-check-option" data-modulo="4">
                                                <input type="checkbox" value="4" id="moduloCheck4">
                                                <label for="moduloCheck4">MÓDULO 4</label>
                                            </div>
                                            <div class="modulo-check-option" data-modulo="5">
                                                <input type="checkbox" value="5" id="moduloCheck5">
                                                <label for="moduloCheck5">MÓDULO 5</label>
                                            </div>
                                            <div class="modulo-check-option" data-modulo="6">
                                                <input type="checkbox" value="6" id="moduloCheck6">
                                                <label for="moduloCheck6">MÓDULO 6</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-usuario-field"><label>NIVEL ACCESO</label><select id="nivelAccesoUsuario"><option value="admin">Administrador</option><option value="facturador">Facturador</option></select></div>
                            </div>
                            <div class="form-usuario-actions">
                                <button type="button" class="btn-cancelar-usuario-form" onclick="ocultarFormularioUsuario()">Cancelar</button>
                                <button type="button" class="btn-actualizar-usuario" id="btnActualizarUsuario" style="display:none;" onclick="actualizarUsuario()">Actualizar</button>
                                <button type="submit" class="btn-guardar-usuario-form" id="btnGuardarUsuario">Guardar Usuario</button>
                            </div>
                        </form>
                    </div>
                    <div class="usuarios-list"><h3>Usuarios Registrados</h3><div class="usuarios-grid" id="usuariosGridContainer"></div></div>
                </div>
            </div>

            <div id="seccion-perfil" style="display:none;">
                <div class="perfil-section">
                    <div class="perfil-header">
                        <h2><i class="fas fa-lock"></i> Super Administrador - Gestión de Permisos del Sistema</h2>
                    </div>
                    <div class="perfil-search">
                        <div class="search-input-wrapper">
                            <label><i class="fas fa-search"></i> Buscar Usuario</label>
                            <input type="text" id="buscarUsuarioPermisos" placeholder="">
                        </div>
                        <button class="btn-buscar-perfil" onclick="filtrarPermisos()"><i class="fas fa-search"></i> Buscar</button>
                    </div>
                    <div class="permisos-container" id="permisosContainer">
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-sign-in-alt"></i> Login</h4><p>Acceso al sistema - Inicio de sesión</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_login" value="1"> Sí</label><label><input type="radio" name="permiso_login" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-home"></i> Inicio</h4><p>Pantalla principal - Gestión de turnos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_inicio" value="1"> Sí</label><label><input type="radio" name="permiso_inicio" value="0" checked> No</label></div></div>
                        <div class="permiso-item">
                            <div class="permiso-info">
                                <h4><i class="fas fa-lock"></i> Agregar Nivel de Acceso</h4>
                                <p>Crear y gestionar niveles de acceso para usuarios</p>
                            </div>
                            <div class="permiso-buttons">
                                <label><input type="radio" name="permiso_agregar_nivel_acceso" value="1"> Sí</label>
                                <label><input type="radio" name="permiso_agregar_nivel_acceso" value="0" checked> No</label>
                            </div>
                        </div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-user-plus"></i> Agregar Pacientes</h4><p>Registrar nuevos pacientes</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_agregar_pacientes" value="1"> Sí</label><label><input type="radio" name="permiso_agregar_pacientes" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-users"></i> Usuarios</h4><p>Gestionar usuarios del sistema</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_usuarios" value="1"> Sí</label><label><input type="radio" name="permiso_usuarios" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-stethoscope"></i> Servicios</h4><p>Gestionar servicios médicos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_servicios" value="1"> Sí</label><label><input type="radio" name="permiso_servicios" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-chart-bar"></i> Reportes</h4><p>Ver reportes de turnos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_reportes" value="1"> Sí</label><label><input type="radio" name="permiso_reportes" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-users"></i> Atender Turnos</h4><p>Gestionar y atender turnos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_atender_turnos" value="1"> Sí</label><label><input type="radio" name="permiso_atender_turnos" value="0" checked> No</label></div></div>
                        <div class="permiso-item">
                            <div class="permiso-info">
                                <h4><i class="fas fa-tv"></i> Publicidad TV</h4>
                                <p>Acceso a la sección de Publicidad TV - Gestión de banners</p>
                            </div>
                            <div class="permiso-buttons">
                                <label><input type="radio" name="permiso_publicidad_tv" value="1"> Sí</label>
                                <label><input type="radio" name="permiso_publicidad_tv" value="0" checked> No</label>
                            </div>
                        </div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-id-card"></i> Perfil</h4><p>Ver y editar perfil de usuario</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_perfil" value="1"> Sí</label><label><input type="radio" name="permiso_perfil" value="0" checked> No</label></div></div>
                    </div>
                    <div class="perfil-actions">
                        <button class="btn-actualizar-perfil" onclick="actualizarPermisos()"><i class="fas fa-sync-alt"></i> Actualizar</button>
                        <button class="btn-guardar-perfil" onclick="guardarPermisos()"><i class="fas fa-save"></i> Guardar Cambios</button>
                    </div>
                    <div class="perfil-footer"><i class="fas fa-shield-alt"></i> Sistema de Gestión de Permisos</div>
                </div>
            </div>

            <div id="seccion-reportes" style="display:none;">
                <div class="reportes-section">
                    <div class="report-header"><h2><i class="fas fa-chart-line"></i> Reporte de Turnos</h2></div>
                    <div class="filtros-fecha">
                        <input type="date" id="fechaInicio" value="2024-01-01">
                        <span>→</span>
                        <input type="date" id="fechaFin" value="2026-12-31">
                        <button class="btn-generar-reporte" onclick="generarReporte()">Generar Reporte</button>
                    </div>
                    <div class="acciones-reporte">
                        <button class="btn-descargar" onclick="descargarReporte()">Descargar CSV</button>
                        <button class="btn-buscar-reporte" onclick="buscarEnReporte()">Buscar</button>
                    </div>
                    <table class="reporte-table">
                        <thead><tr><th>ESTADO</th><th>TURNO</th><th>SERVICIO</th><th>DOCUMENTO</th><th>NOMBRE</th><th>INGRESO</th><th>SALIDA</th><th>DIFERENCIA</th></tr></thead>
                        <tbody id="reporteBody"><tr><td colspan="8">Seleccione fechas y genere reporte</td</tr</tbody>
                    </table>
                    <div class="footer-reporte">SISTEMA DE GESTIÓN DE TURNOS</div>
                </div>
            </div>

            <!-- SECCIÓN ATENDER TURNOS -->
            <div id="seccion-atender" style="display:none;">
                <div class="atender-section">
                    <div class="atender-header">
                        <h2><i class="fas fa-stethoscope"></i> Gestionar Turnos</h2>
                    </div>
                    <div class="gestionar-turno-layout">
                        <div class="gestionar-izquierda">
                            <div class="gestionar-titulo">Turno Actual</div>
                            <div class="turno-numero-grande vacio" id="gtNumeroTurno">NÚMERO</div>
                            <div class="campo-fila">
                                <label>Documento</label>
                                <div style="display:flex; gap:8px; align-items:center;">
                                    <div class="campo-valor-display vacio" id="gtDocumento" style="flex:1;">—</div>
                                    <select id="gtTipoDoc" style="padding:8px; border:1.5px solid #e0e7ff; border-radius:8px; font-size:0.8rem; color:#0b2b5e; background:white;">
                                        <option>Cédula de Ciudadanía</option>
                                        <option>Tarjeta de Identidad</option>
                                        <option>Pasaporte</option>
                                        <option>Cédula Extranjería</option>
                                    </select>
                                </div>
                            </div>
                            <div class="campos-dos-col">
                                <div class="campo-fila"><label>Primer Apellido</label><div class="campo-valor-display vacio" id="gtPrimerApellido">—</div></div>
                                <div class="campo-fila"><label>Segundo Apellido</label><div class="campo-valor-display vacio" id="gtSegundoApellido">—</div></div>
                            </div>
                            <div class="campo-fila"><label>Primer Nombre</label><div class="campo-valor-display vacio" id="gtPrimerNombre">—</div></div>
                        </div>
                        <div class="gestionar-derecha">
                            <div class="datos-usuario-titulo">Datos del Usuario</div>
                            <div class="usuario-avatar"><div class="avatar-circulo"><i class="fas fa-user"></i></div></div>
                            <div id="usuarioAutenticadoNombre" style="text-align: center; margin-top: 5px; font-weight: 600; color: #0b2b5e; font-size: 1rem;"></div>
                            <div class="selector-servicio"><label>Servicio:</label><select id="gtServicioFiltro" onchange="filtrarPorServicio()"><option value="">— Todos los servicios —</option></select></div>
                            <div class="modulo-selector-wrapper" id="moduloSelectorWrapper">
                                <button type="button" class="modulo-btn-click" id="moduloBtnClick" onclick="toggleModuloDropdown()"><span class="modulo-nombre-text" id="moduloBtnTexto">MÓDULO 1</span><i class="fas fa-chevron-down modulo-chevron"></i></button>
                                <div class="modulo-dropdown" id="moduloDropdownM">
                                    <div class="modulo-option active-mod" data-mod="1" onclick="seleccionarModulo(1)"><div class="mod-icon">M1</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 1</span><span class="mod-info-count" id="countMod1">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="2" onclick="seleccionarModulo(2)"><div class="mod-icon">M2</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 2</span><span class="mod-info-count" id="countMod2">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="3" onclick="seleccionarModulo(3)"><div class="mod-icon">M3</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 3</span><span class="mod-info-count" id="countMod3">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="4" onclick="seleccionarModulo(4)"><div class="mod-icon">M4</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 4</span><span class="mod-info-count" id="countMod4">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="5" onclick="seleccionarModulo(5)"><div class="mod-icon">M5</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 5</span><span class="mod-info-count" id="countMod5">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="6" onclick="seleccionarModulo(6)"><div class="mod-icon">M6</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 6</span><span class="mod-info-count" id="countMod6">Cargando...</span></div></div>
                                </div>
                            </div>
                            <div class="modulo-info-box"><div class="modulo-nombre" id="gtModuloNombre">MÓDULO 1</div><div class="modulo-contadores"><span class="contador-badge activos"><i class="fas fa-clock"></i> ACTIVOS: <span id="gtContActivos">0</span></span><span class="contador-badge atendidos"><i class="fas fa-check"></i> ATENDIDOS: <span id="gtContAtendidos">0</span></span><span class="contador-badge totales"><i class="fas fa-list"></i> TOTALES: <span id="gtContTotales">0</span></span></div></div>
                            <button class="btn-ver-turnos-modal" onclick="toggleListaTurnos()"><i class="fas fa-eye"></i> Ver Turnos</button>
                            <div class="turnos-espera-lista" id="turnos-espera-lista"><div id="turnosEsperaBody"></div></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- SECCIÓN AGREGAR NIVEL DE ACCESO -->
            <div id="seccion-agregar-nivel" style="display:none;">
                <div class="agregar-nivel-section">
                    <h2><i class="fas fa-plus-circle"></i> Agregar Nivel de Acceso</h2>
                    <div class="form-group">
                        <label>Nombre del Nivel</label>
                        <input type="text" id="nuevoNivelAccesoSeccion" placeholder="">
                    </div>
                    <div>
                        <button class="btn-guardar-nivel-section" onclick="guardarNivelAccesoSeccion()">Guardar</button>
                        <button class="btn-cancelar-nivel-section" onclick="mostrarSeccion('inicio')">Cancelar</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN ADMINISTRACIÓN DE BANNERS -->
            <div id="seccion-publicidad-tv" style="display:none;">
                <div class="banners-section-fullwidth">
                    <h2><i class="fas fa-tv"></i> Administración de banners</h2>
                    <div class="banners-subtitle">Agrega y/o edita el contenido de los banners (Máximo 5 banners)</div>
                    
                    <button class="btn-agregar-banner" onclick="abrirModalAgregarBanner()">
                        <i class="fas fa-plus"></i> Agregar
                    </button>
                    
                    <div class="banners-lista-fullwidth" id="bannersLista"></div>
                    
                    <div class="banners-duracion-fullwidth">
                        <label><i class="fas fa-clock"></i> Duración de rotación</label>
                        <div style="font-size: 0.8rem; color: #666; margin-bottom: 8px;">Define los segundos que durará la rotación automáticamente de los banners</div>
                        <input type="number" id="rotacionDuracion" value="10" min="3" max="60">
                        <span style="margin-left: 10px;">segundos</span>
                    </div>
                    
                    <div class="banners-actions">
                        <button class="btn-cancelar-publicidad" onclick="mostrarSeccion('inicio')"><i class="fas fa-times"></i> Cancelar</button>
                        <button class="btn-guardar-publicidad" onclick="guardarBanners()"><i class="fas fa-save"></i> Guardar</button>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN CONFIGURACIÓN CON NUEVO CAMPO PARA IMAGEN DE FONDO DEL LOGIN -->
            <div id="seccion-configuracion" style="display:none;">
                <div class="configuracion-section">
                    <h2><i class="fas fa-cog"></i> Configuración del Sistema</h2>
                    
                    <div class="form-group">
                        <label><i class="fas fa-building"></i> Nombre de la empresa</label>
                        <input type="text" id="nombreEmpresa" placeholder="Ej: Hospital San Pablo" value="{{ $configuracion->nombre_empresa ?? 'E.S.E HOSPITAL LOCAL SANPABLO' }}">
                    </div>
                    
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Logo de la empresa</label>
                        <input type="file" id="logoEmpresa" accept="image/*">
                        @if(isset($configuracion) && $configuracion && $configuracion->logo_empresa_url)
                            <div style="margin-top: 8px;">
                                <img src="{{ asset($configuracion->logo_empresa_url) }}" alt="Logo actual" style="max-width: 150px; max-height: 80px; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px;">
                                <small style="display: block; color: #666; margin-top: 4px;">Logo actual</small>
                            </div>
                        @endif
                        <div style="margin-top: 8px; font-size: 0.8rem; color: #888;">Formatos permitidos: JPG, PNG, SVG | Tamaño máximo: 2MB</div>
                    </div>
                    
                    <!-- ===== NUEVO CAMPO: IMAGEN DE FONDO DEL LOGIN ===== -->
                    <div class="form-group">
                        <label><i class="fas fa-image"></i> Imagen de fondo del login</label>
                        <input type="file" id="fondoLogin" accept="image/*">
                        @if(isset($configuracion) && $configuracion && $configuracion->imagen_fondo_login)
                            <div style="margin-top: 8px;">
                                <img src="{{ asset($configuracion->imagen_fondo_login) }}" alt="Fondo actual" style="max-width: 100%; max-height: 150px; border-radius: 8px; border: 1px solid #e2e8f0; padding: 4px;">
                                <small style="display: block; color: #666; margin-top: 4px;">Imagen de fondo actual</small>
                            </div>
                        @endif
                        <div style="margin-top: 8px; font-size: 0.8rem; color: #888;">Formatos permitidos: JPG, PNG | Tamaño máximo: 5MB | Recomendado: 1200x800px</div>
                    </div>
                    <!-- ===== FIN NUEVO CAMPO ===== -->
                    
                    <div class="form-group">
                        <label><i class="fas fa-map-pin"></i> Dirección de la empresa</label>
                        <input type="text" id="direccionEmpresa" placeholder="Ej: Calle 10 # 20-30, Centro" value="{{ $configuracion->direccion_empresa ?? 'Calle Principal # 1-20, San Pablo' }}">
                    </div>
                    
                    <div class="configuracion-actions">
                        <button class="btn-cancelar-config" onclick="mostrarSeccion('inicio')"><i class="fas fa-times"></i> Cancelar</button>
                        <button class="btn-guardar-config" onclick="guardarConfiguracion()"><i class="fas fa-save"></i> Guardar Configuración</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA AGREGAR/EDITAR BANNER -->
    <div id="modalAgregarBanner" style="display: none;">
        <div class="modal-banner-overlay">
            <div class="modal-banner-card">
                <div class="modal-banner-header">
                    <h3 id="modalBannerTitulo"><i class="fas fa-plus-circle"></i> Agregar Banner</h3>
                    <button class="modal-banner-close" onclick="cerrarModalAgregarBanner()">&times;</button>
                </div>
                
                <input type="hidden" id="editandoBannerIndex" value="-1">
                
                <div class="modal-banner-grid">
                    <div class="form-row">
                        <label><i class="fas fa-film"></i> Tipo de contenido</label>
                        <div class="radio-group">
                            <label><input type="radio" name="tipoContenido" value="imagen" checked> Imagen</label>
                            <label><input type="radio" name="tipoContenido" value="video"> Video</label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <label><i class="fas fa-palette"></i> Tipo de fondo</label>
                        <div class="tipo-fondo-group">
                            <label><input type="radio" name="tipoFondo" value="imagen" checked> Imagen</label>
                            <label><input type="radio" name="tipoFondo" value="color"> Color sólido</label>
                        </div>
                    </div>
                    
                    <div class="form-row full-width" id="campoImagenFondo">
                        <label><i class="fas fa-image"></i> Foto de fondo</label>
                        <div style="font-size: 0.75rem; color: #666; margin-bottom: 8px;">Tamaño recomendado: 1200px x 300px | Peso máximo: 2 Mb</div>
                        <input type="file" id="bannerImagen" accept="image/*" style="padding: 8px;">
                        <div id="imagenActualPreview" style="margin-top: 8px; display: none;">
                            <small>Imagen actual:</small>
                            <img id="imagenActualPreviewImg" src="" style="max-width: 100px; max-height: 50px;">
                        </div>
                    </div>
                    
                    <div class="form-row full-width campo-color-fondo" id="campoColorFondo" style="display: none;">
                        <label><i class="fas fa-fill-drip"></i> Color de fondo</label>
                        <input type="color" id="colorFondoSolido" value="#0b2b5e">
                    </div>
                    
                    <div class="form-row">
                        <label><i class="fas fa-image"></i> Ajuste de imagen (en TV)</label>
                        <select id="ajusteImagen" style="width: 100%;">
                            <option value="cover">Cubrir (Cover) - Ocupa todo, puede cortar bordes</option>
                            <option value="contain">Contener (Contain) - Se ve toda la imagen, puede dejar bordes</option>
                            <option value="fill">Estirar (Fill) - Llena todo, puede deformarse</option>
                            <option value="scale-down">Escala original - Muestra la imagen en su tamaño original</option>
                        </select>
                        <div style="font-size: 0.7rem; color: #666; margin-top: 4px;">
                            ⚡ Cubrir: ideal para fondos | Contener: ideal para mostrar texto completo
                        </div>
                    </div>
                    
                    <div class="form-row" id="campoFiltro">
                        <label><i class="fas fa-filter"></i> Filtro (solo para imagen)</label>
                        <div class="color-input-group">
                            <input type="color" id="filtroColor" value="#000000">
                            <input type="range" id="filtroOpacidad" min="0" max="100" value="0" style="flex: 1;">
                            <span id="opacidadValor" style="width: 40px;">0%</span>
                        </div>
                    </div>
                    
                    <div class="form-row full-width">
                        <label><i class="fas fa-heading"></i> Título</label>
                        <div class="color-input-group" style="margin-bottom: 8px;">
                            <span style="font-size: 0.75rem;">Color de letra:</span>
                            <input type="color" id="tituloColor" value="#FFFFFF">
                            <span style="font-size: 0.75rem;">Color de fondo:</span>
                            <input type="color" id="tituloFondoColor" value="#000000">
                            <span style="font-size: 0.75rem;">Tipografía:</span>
                            <select id="tituloTipografia" style="width: 150px;">
                                <option value="Arial">Arial</option>
                                <option value="Montserrat" selected>Montserrat</option>
                                <option value="Poppins">Poppins</option>
                                <option value="Roboto">Roboto</option>
                            </select>
                        </div>
                        <input type="text" id="tituloTexto" placeholder="Texto del título (máx. 80 caracteres)" maxlength="80">
                    </div>
                    
                    <div class="form-row full-width">
                        <label><i class="fas fa-align-left"></i> Justificación</label>
                        <div class="radio-group">
                            <label><input type="radio" name="justificacion" value="left"> Izquierda</label>
                            <label><input type="radio" name="justificacion" value="center" checked> Centro</label>
                            <label><input type="radio" name="justificacion" value="right"> Derecha</label>
                        </div>
                    </div>
                    
                    <div class="form-row full-width">
                        <label><i class="fas fa-universal-access"></i> Texto descriptivo para accesibilidad</label>
                        <textarea id="textoAccesibilidad" rows="2" placeholder="Describe el banner para personas con discapacidad visual" maxlength="250"></textarea>
                    </div>
                    
                    <div class="form-row full-width">
                        <label><i class="fas fa-link"></i> Agregar enlace</label>
                        <input type="url" id="bannerEnlace" placeholder="https://ejemplo.com">
                    </div>
                </div>
                
                <div class="modal-banner-actions">
                    <button class="btn-cancelar-nivel" onclick="cerrarModalAgregarBanner()">Cancelar</button>
                    <button class="btn-guardar-nivel" onclick="guardarBanner()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PARA SERVICIOS -->
    <div id="modalServicio">
        <div class="modal-servicio-content">
            <div class="modal-header">
                <h3 id="modalTitulo">Agregar Servicio</h3>
                <button class="close-modal-servicio" onclick="cerrarModalServicio()">&times;</button>
            </div>
            <form id="formServicio" onsubmit="guardarServicioDB(event); return false;">
                <input type="hidden" id="servicioId">
                <div class="form-group">
                    <label>Nombre del Servicio:</label>
                    <input type="text" id="nombreServicio" required placeholder="Ej: Consulta Externa">
                </div>
                <div class="form-group">
                    <label>Descripción:</label>
                    <textarea id="descripcion" rows="3" placeholder="Breve descripción del servicio"></textarea>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="requiereOrdenMedica">
                    <label for="requiereOrdenMedica">Requiere orden médica</label>
                </div>
                <div class="form-group">
                    <label>Estado del Servicio:</label>
                    <select id="activo">
                        <option value="1">ACTIVO</option>
                        <option value="0">INACTIVO</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="btn-guardar-serv">Guardar</button>
                    <button type="button" class="btn-cancelar-serv" onclick="cerrarModalServicio()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL PARA AGREGAR NIVEL DE ACCESO -->
    <div id="modalNivelAcceso" style="display: none;">
        <div class="modal-nivel-overlay">
            <div class="modal-nivel-card">
                <div class="modal-nivel-header">
                    <h3><i class="fas fa-plus-circle"></i> Agregar Nivel de Acceso</h3>
                    <button class="modal-nivel-close" onclick="cerrarModalNivelAcceso()">&times;</button>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>NOMBRE DEL NIVEL</label>
                    <input type="text" id="nuevoNivelAcceso" placeholder="" style="width:100%; padding:12px; border:2px solid #e2e8f0; border-radius:12px;">
                </div>
                <div class="modal-nivel-actions">
                    <button class="btn-cancelar-nivel" onclick="cerrarModalNivelAcceso()">Cancelar</button>
                    <button class="btn-guardar-nivel" onclick="guardarNivelAcceso()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ==================== USUARIOS DESDE BASE DE DATOS ====================
        const usuariosDesdeBD = @json($usuariosBD ?? []);
        
        // ==================== VARIABLES PARA BANNERS ====================
        let banners = [];
        let bannerEditandoIndex = -1;
        let serviciosDB = [];
        
        // ==================== FUNCIÓN GUARDAR CONFIGURACIÓN ====================
        function guardarConfiguracion() {
            const nombreEmpresa = document.getElementById('nombreEmpresa').value.trim();
            const direccionEmpresa = document.getElementById('direccionEmpresa').value.trim();
            const logoFile = document.getElementById('logoEmpresa').files[0];
            const fondoFile = document.getElementById('fondoLogin').files[0];
            
            if (!nombreEmpresa) {
                showNotification('⚠️ El nombre de la empresa es obligatorio', 'warning');
                return;
            }
            
            const formData = new FormData();
            formData.append('nombre_empresa', nombreEmpresa);
            formData.append('direccion_empresa', direccionEmpresa);
            
            if (logoFile) {
                formData.append('logo', logoFile);
            }
            
            if (fondoFile) {
                formData.append('fondo_login', fondoFile);
            }
            
            showNotification('💾 Guardando configuración...', 'info');
            
            fetch('/api/configuracion-empresa', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('✅ Configuración guardada correctamente', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showNotification('❌ Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Error de conexión con el servidor', 'error');
            });
        }
        
        // ==================== FUNCIONES PARA BANNERS ====================
        function cargarBanners() {
            const bannersGuardados = localStorage.getItem('banners_tv');
            if(bannersGuardados) {
                banners = JSON.parse(bannersGuardados);
            } else {
                banners = [];
            }
            
            const duracionGuardada = localStorage.getItem('rotacion_duracion');
            if(duracionGuardada) {
                document.getElementById('rotacionDuracion').value = duracionGuardada;
            }
            
            renderizarListaBanners();
        }
        
        function renderizarListaBanners() {
            const container = document.getElementById('bannersLista');
            if(!container) return;
            
            if(banners.length === 0) {
                container.innerHTML = '<div style="text-align:center; padding:40px; color:#999;">No hay banners agregados. Haz clic en "+ Agregar" para crear banners.</div>';
                return;
            }
            
            container.innerHTML = banners.map((banner, index) => {
                const tieneImagen = banner.url && banner.url !== '';
                const previewHtml = tieneImagen ? 
                    `<div class="banner-imagen-preview"><img src="${banner.url}" alt="Banner ${index + 1}"></div>` :
                    `<div class="banner-color-preview" style="background-color: ${banner.colorFondoSolido || '#0b2b5e'};"></div>`;
                
                return `
                    <div class="banner-item-clickable" data-index="${index}" onclick="abrirModalEditarBanner(${index})">
                        <div class="banner-numero">${index + 1}</div>
                        ${previewHtml}
                        <div class="banner-info">
                            <h4>Banner ${index + 1}</h4>
                            <p>${banner.titulo || 'Sin título'}</p>
                        </div>
                        <button class="btn-eliminar-banner" onclick="event.stopPropagation(); eliminarBanner(${index})">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                `;
            }).join('');
        }
        
        document.getElementById('filtroOpacidad')?.addEventListener('input', function() {
            document.getElementById('opacidadValor').textContent = this.value + '%';
        });
        
        function toggleCamposPorTipoFondo() {
            const tipoFondo = document.querySelector('input[name="tipoFondo"]:checked').value;
            const campoImagen = document.getElementById('campoImagenFondo');
            const campoColor = document.getElementById('campoColorFondo');
            const campoFiltro = document.getElementById('campoFiltro');
            
            if(tipoFondo === 'imagen') {
                campoImagen.style.display = 'block';
                campoColor.style.display = 'none';
                campoFiltro.style.display = 'block';
            } else {
                campoImagen.style.display = 'none';
                campoColor.style.display = 'block';
                campoFiltro.style.display = 'none';
            }
        }
        
        document.querySelectorAll('input[name="tipoFondo"]').forEach(radio => {
            radio.addEventListener('change', toggleCamposPorTipoFondo);
        });
        
        function abrirModalAgregarBanner() {
            if(banners.length >= 5) {
                showNotification('⚠️ Máximo 5 banners permitidos', 'warning');
                return;
            }
            bannerEditandoIndex = -1;
            document.getElementById('modalBannerTitulo').innerHTML = '<i class="fas fa-plus-circle"></i> Agregar Banner';
            document.getElementById('editandoBannerIndex').value = '-1';
            resetearFormularioBanner();
            document.getElementById('modalAgregarBanner').style.display = 'flex';
        }
        
        function abrirModalEditarBanner(index) {
            if(index < 0 || index >= banners.length) return;
            bannerEditandoIndex = index;
            const banner = banners[index];
            document.getElementById('modalBannerTitulo').innerHTML = '<i class="fas fa-edit"></i> Editar Banner';
            document.getElementById('editandoBannerIndex').value = index;
            
            document.querySelector(`input[name="tipoContenido"][value="${banner.tipoContenido || 'imagen'}"]`).checked = true;
            
            const tieneImagen = banner.url && banner.url !== '';
            if(tieneImagen) {
                document.querySelector('input[name="tipoFondo"][value="imagen"]').checked = true;
            } else {
                document.querySelector('input[name="tipoFondo"][value="color"]').checked = true;
            }
            toggleCamposPorTipoFondo();
            
            document.getElementById('colorFondoSolido').value = banner.colorFondoSolido || '#0b2b5e';
            document.getElementById('filtroColor').value = banner.filtroColor || '#000000';
            document.getElementById('filtroOpacidad').value = banner.filtroOpacidad || '0';
            document.getElementById('opacidadValor').textContent = (banner.filtroOpacidad || '0') + '%';
            document.getElementById('tituloTexto').value = banner.titulo || '';
            document.getElementById('tituloColor').value = banner.tituloColor || '#FFFFFF';
            document.getElementById('tituloFondoColor').value = banner.tituloFondoColor || '#000000';
            document.getElementById('tituloTipografia').value = banner.tituloTipografia || 'Montserrat';
            document.querySelector(`input[name="justificacion"][value="${banner.justificacion || 'center'}"]`).checked = true;
            document.getElementById('textoAccesibilidad').value = banner.textoAccesibilidad || '';
            document.getElementById('bannerEnlace').value = banner.enlace || '';
            
            const ajusteSelect = document.getElementById('ajusteImagen');
            if(ajusteSelect && banner.ajusteImagen) {
                ajusteSelect.value = banner.ajusteImagen;
            } else if(ajusteSelect) {
                ajusteSelect.value = 'cover';
            }
            
            if(banner.url) {
                const previewDiv = document.getElementById('imagenActualPreview');
                const previewImg = document.getElementById('imagenActualPreviewImg');
                previewImg.src = banner.url;
                previewDiv.style.display = 'block';
            } else {
                document.getElementById('imagenActualPreview').style.display = 'none';
            }
            
            document.getElementById('modalAgregarBanner').style.display = 'flex';
        }
        
        function resetearFormularioBanner() {
            document.getElementById('bannerImagen').value = '';
            document.getElementById('tituloTexto').value = '';
            document.getElementById('textoAccesibilidad').value = '';
            document.getElementById('bannerEnlace').value = '';
            document.getElementById('filtroOpacidad').value = '0';
            document.getElementById('opacidadValor').textContent = '0%';
            document.getElementById('filtroColor').value = '#000000';
            document.getElementById('tituloColor').value = '#FFFFFF';
            document.getElementById('tituloFondoColor').value = '#000000';
            document.getElementById('colorFondoSolido').value = '#0b2b5e';
            document.querySelector('input[name="tipoContenido"][value="imagen"]').checked = true;
            document.querySelector('input[name="justificacion"][value="center"]').checked = true;
            document.querySelector('input[name="tipoFondo"][value="imagen"]').checked = true;
            document.getElementById('imagenActualPreview').style.display = 'none';
            const ajusteSelect = document.getElementById('ajusteImagen');
            if(ajusteSelect) ajusteSelect.value = 'cover';
            toggleCamposPorTipoFondo();
        }
        
        function cerrarModalAgregarBanner() {
            document.getElementById('modalAgregarBanner').style.display = 'none';
            bannerEditandoIndex = -1;
        }
        
        function guardarBanner() {
            const editandoIndex = parseInt(document.getElementById('editandoBannerIndex').value);
            const tipoFondo = document.querySelector('input[name="tipoFondo"]:checked').value;
            const fileInput = document.getElementById('bannerImagen');
            const file = fileInput.files[0];
            
            const bannerBase = {
                id: editandoIndex >= 0 ? banners[editandoIndex].id : Date.now(),
                tipoContenido: document.querySelector('input[name="tipoContenido"]:checked').value,
                tipoFondo: tipoFondo,
                filtroColor: document.getElementById('filtroColor').value,
                filtroOpacidad: document.getElementById('filtroOpacidad').value,
                titulo: document.getElementById('tituloTexto').value,
                tituloColor: document.getElementById('tituloColor').value,
                tituloFondoColor: document.getElementById('tituloFondoColor').value,
                tituloTipografia: document.getElementById('tituloTipografia').value,
                justificacion: document.querySelector('input[name="justificacion"]:checked').value,
                textoAccesibilidad: document.getElementById('textoAccesibilidad').value,
                enlace: document.getElementById('bannerEnlace').value,
                colorFondoSolido: document.getElementById('colorFondoSolido').value,
                ajusteImagen: document.getElementById('ajusteImagen').value,
                fechaModificacion: new Date().toISOString()
            };
            
            if(tipoFondo === 'color') {
                bannerBase.url = '';
                bannerBase.nombre = '';
                
                if(editandoIndex >= 0) {
                    banners[editandoIndex] = bannerBase;
                    showNotification('✅ Banner actualizado correctamente', 'success');
                } else {
                    banners.push(bannerBase);
                    showNotification('✅ Banner agregado correctamente', 'success');
                }
                renderizarListaBanners();
                cerrarModalAgregarBanner();
                return;
            }
            
            if(editandoIndex >= 0 && banners[editandoIndex] && !file && banners[editandoIndex].url) {
                bannerBase.url = banners[editandoIndex].url;
                bannerBase.nombre = banners[editandoIndex].nombre;
                banners[editandoIndex] = bannerBase;
                renderizarListaBanners();
                cerrarModalAgregarBanner();
                showNotification('✅ Banner actualizado correctamente', 'success');
                return;
            }
            
            if(!file) {
                showNotification('⚠️ Seleccione una imagen para el fondo', 'warning');
                return;
            }
            
            if(!file.type.startsWith('image/')) {
                showNotification('⚠️ El archivo debe ser una imagen', 'error');
                return;
            }
            
            if(file.size > 2 * 1024 * 1024) {
                showNotification('⚠️ La imagen no debe superar los 2MB', 'error');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                bannerBase.url = e.target.result;
                bannerBase.nombre = file.name;
                
                if(editandoIndex >= 0) {
                    banners[editandoIndex] = bannerBase;
                    showNotification('✅ Banner actualizado correctamente', 'success');
                } else {
                    banners.push(bannerBase);
                    showNotification('✅ Banner agregado correctamente', 'success');
                }
                renderizarListaBanners();
                cerrarModalAgregarBanner();
            };
            reader.readAsDataURL(file);
        }
        
        function eliminarBanner(index) {
            if(confirm('¿Eliminar este banner?')) {
                banners.splice(index, 1);
                renderizarListaBanners();
                showNotification('✅ Banner eliminado', 'success');
            }
        }
        
        function guardarBanners() {
            const duracion = document.getElementById('rotacionDuracion').value;
            localStorage.setItem('banners_tv', JSON.stringify(banners));
            localStorage.setItem('rotacion_duracion', duracion);
            showNotification('✅ Banners guardados correctamente para la pantalla TV', 'success');
            renderizarListaBanners();
        }
        
        // ==================== FUNCIONES PARA MOSTRAR/OCULTAR FORMULARIO DE USUARIO ====================
        function mostrarFormularioUsuario() {
            document.getElementById('formUsuarioContainer').classList.add('visible');
            limpiarFormularioUsuario();
        }
        
        function ocultarFormularioUsuario() {
            document.getElementById('formUsuarioContainer').classList.remove('visible');
            limpiarFormularioUsuario();
        }
        
        function limpiarFormularioUsuarioOriginal() {
            document.getElementById('nombresUsuario').value = '';
            document.getElementById('apellidosUsuario').value = '';
            document.getElementById('cedulaUsuario').value = '';
            document.getElementById('passwordUsuarioNuevo').value = '';
            document.getElementById('nuevaPasswordUsuario').value = '';
            document.getElementById('usuarioAsesor').value = '';
            document.getElementById('servicioUsuario').value = '';
            document.getElementById('nivelAccesoUsuario').value = 'admin';
            document.querySelectorAll('#modulosDropdown input[type="checkbox"]').forEach(cb => cb.checked = false);
            actualizarTextoModulosSeleccionados();
            editandoUsuarioId = null;
            editandoUsuarioBDId = null;
            document.getElementById('btnGuardarUsuario').style.display = 'block';
            document.getElementById('btnActualizarUsuario').style.display = 'none';
            document.getElementById('formUsuarioContainer').querySelector('h3').innerHTML = '<i class="fas fa-user-edit"></i> Registrar Usuario';
        }
        
        limpiarFormularioUsuario = limpiarFormularioUsuarioOriginal;
        
        function togglePasswordVisibility(inputId, button) {
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
        
        function toggleModulosDropdown() {
            const dropdown = document.getElementById('modulosDropdown');
            dropdown.classList.toggle('open');
        }
        
        function actualizarTextoModulosSeleccionados() {
            const checkboxes = document.querySelectorAll('#modulosDropdown input[type="checkbox"]:checked');
            const texto = document.getElementById('modulosSelectText');
            if (checkboxes.length === 0) {
                texto.textContent = 'Seleccionar módulos';
            } else {
                const modulosSeleccionados = Array.from(checkboxes).map(cb => cb.value);
                texto.textContent = `${modulosSeleccionados.length} módulo(s) seleccionado(s)`;
            }
        }
        
        function obtenerModulosSeleccionados() {
            return Array.from(document.querySelectorAll('#modulosDropdown input[type="checkbox"]:checked')).map(cb => cb.value);
        }
        
        function marcarModulosSeleccionados(modulosArray) {
            const checkboxes = document.querySelectorAll('#modulosDropdown input[type="checkbox"]');
            checkboxes.forEach(cb => {
                cb.checked = modulosArray.includes(cb.value);
            });
            actualizarTextoModulosSeleccionados();
        }
        
        document.addEventListener('click', function(e) {
            const container = document.getElementById('modulosSelectContainer');
            if (container && !container.contains(e.target)) {
                document.getElementById('modulosDropdown').classList.remove('open');
            }
        });
        
        // ==================== FUNCIONES PARA NIVEL DE ACCESO ====================
        let nivelesAcceso = [];
        
        function cargarNivelesAcceso() {
            fetch('/api/niveles-acceso')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        nivelesAcceso = data.niveles;
                        const selectNivel = document.getElementById('nivelAccesoUsuario');
                        if (selectNivel) {
                            const valorActual = selectNivel.value;
                            selectNivel.innerHTML = '';
                            nivelesAcceso.forEach(nivel => {
                                const option = document.createElement('option');
                                option.value = nivel.nombre;
                                option.textContent = nivel.nombre;
                                selectNivel.appendChild(option);
                            });
                            if (valorActual && nivelesAcceso.some(n => n.nombre === valorActual)) {
                                selectNivel.value = valorActual;
                            }
                        }
                    }
                })
                .catch(error => console.error('Error cargando niveles:', error));
        }
        
        function abrirModalNivelAcceso() {
            document.getElementById('modalNivelAcceso').style.display = 'flex';
            document.getElementById('nuevoNivelAcceso').value = '';
        }
        
        function cerrarModalNivelAcceso() {
            document.getElementById('modalNivelAcceso').style.display = 'none';
        }
        
        function guardarNivelAcceso() {
            const nombre = document.getElementById('nuevoNivelAcceso').value.trim();
            if (!nombre) {
                showNotification('Ingrese un nombre para el nivel de acceso', 'error');
                return;
            }
            
            fetch('/api/niveles-acceso', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ nombre: nombre })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Nivel de acceso agregado correctamente', 'success');
                    cerrarModalNivelAcceso();
                    cargarNivelesAcceso();
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión con el servidor', 'error');
            });
        }
        
        function guardarNivelAccesoSeccion() {
            const nombre = document.getElementById('nuevoNivelAccesoSeccion').value.trim();
            if (!nombre) {
                showNotification('Ingrese un nombre para el nivel de acceso', 'error');
                return;
            }
            
            fetch('/api/niveles-acceso', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ nombre: nombre })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Nivel de acceso agregado correctamente', 'success');
                    document.getElementById('nuevoNivelAccesoSeccion').value = '';
                    cargarNivelesAcceso();
                    mostrarSeccion('inicio');
                } else {
                    showNotification('Error: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión con el servidor', 'error');
            });
        }
        
        document.querySelectorAll('#modulosDropdown input[type="checkbox"]').forEach(cb => {
            cb.addEventListener('change', actualizarTextoModulosSeleccionados);
        });
        
        // ==================== VARIABLES PARA EDICIÓN ====================
        let editandoUsuarioId = null;
        let editandoUsuarioBDId = null;
        let usuariosDB = [];
        
        function editarUsuario(usuarioData, esBD, indiceLocal) {
            mostrarFormularioUsuario();
            
            let nombres = '';
            let apellidos = '';
            
            if(usuarioData.nombres && usuarioData.apellidos) {
                nombres = usuarioData.nombres;
                apellidos = usuarioData.apellidos;
            }
            else if(usuarioData.name) {
                const nombreCompleto = usuarioData.name;
                const partes = nombreCompleto.trim().split(' ');
                
                if(partes.length === 1) {
                    nombres = partes[0];
                    apellidos = '';
                } else if(partes.length === 2) {
                    nombres = partes[0];
                    apellidos = partes[1];
                } else if(partes.length === 3) {
                    nombres = partes[0] + ' ' + partes[1];
                    apellidos = partes[2];
                } else if(partes.length >= 4) {
                    nombres = partes[0] + ' ' + partes[1];
                    apellidos = partes.slice(2).join(' ');
                }
            }
            else if(usuarioData.nombres) {
                const nombreCompleto = usuarioData.nombres;
                const partes = nombreCompleto.trim().split(' ');
                if(partes.length === 1) {
                    nombres = partes[0];
                    apellidos = '';
                } else if(partes.length === 2) {
                    nombres = partes[0];
                    apellidos = partes[1];
                } else {
                    nombres = partes[0] + ' ' + partes[1];
                    apellidos = partes.slice(2).join(' ');
                }
            }
            
            if(nombres === '' && usuarioData.nombres) {
                nombres = usuarioData.nombres;
            }
            if(apellidos === '' && usuarioData.apellidos) {
                apellidos = usuarioData.apellidos;
            }
            
            if(esBD) {
                editandoUsuarioBDId = usuarioData.id;
                document.getElementById('btnGuardarUsuario').style.display = 'none';
                document.getElementById('btnActualizarUsuario').style.display = 'block';
                document.getElementById('formUsuarioContainer').querySelector('h3').innerHTML = '<i class="fas fa-edit"></i> Editar Usuario';
            } else {
                editandoUsuarioId = indiceLocal;
                document.getElementById('btnGuardarUsuario').style.display = 'none';
                document.getElementById('btnActualizarUsuario').style.display = 'block';
                document.getElementById('formUsuarioContainer').querySelector('h3').innerHTML = '<i class="fas fa-edit"></i> Editar Usuario';
            }
            
            document.getElementById('nombresUsuario').value = nombres;
            document.getElementById('apellidosUsuario').value = apellidos;
            document.getElementById('cedulaUsuario').value = usuarioData.identificacion || usuarioData.cedula || '';
            
            if(usuarioData.password && usuarioData.password !== '******') {
                document.getElementById('passwordUsuarioNuevo').value = usuarioData.password;
            } else {
                document.getElementById('passwordUsuarioNuevo').value = '';
            }
            
            document.getElementById('nuevaPasswordUsuario').value = '';
            
            document.getElementById('usuarioAsesor').value = usuarioData.usuario_asesor || usuarioData.username || '';
            document.getElementById('servicioUsuario').value = usuarioData.servicio || '';
            document.getElementById('nivelAccesoUsuario').value = usuarioData.nivel_acceso || 'admin';
            
            if (usuarioData.modulos && usuarioData.modulos.length > 0) {
                marcarModulosSeleccionados(usuarioData.modulos);
            } else {
                marcarModulosSeleccionados([]);
            }
        }
        
        function actualizarUsuario() {
            if(editandoUsuarioBDId !== null) {
                actualizarUsuarioBD();
            } else if(editandoUsuarioId !== null) {
                actualizarUsuarioLocal();
            }
        }
        
        function actualizarUsuarioBD() {
            const nombres = document.getElementById('nombresUsuario').value.trim();
            const apellidos = document.getElementById('apellidosUsuario').value.trim();
            const usuarioAsesor = document.getElementById('usuarioAsesor').value.trim();
            const servicioId = document.getElementById('servicioUsuario').value;
            const nivelAcceso = document.getElementById('nivelAccesoUsuario').value;
            const modulosSeleccionados = obtenerModulosSeleccionados();
            
            const passwordIngresada = document.getElementById('passwordUsuarioNuevo').value.trim();
            const nuevaPassword = document.getElementById('nuevaPasswordUsuario').value.trim();
            
            let password = '';
            if (nuevaPassword !== '') {
                password = nuevaPassword;
            } else if (passwordIngresada !== '') {
                password = passwordIngresada;
            }
            
            if(!nombres || !usuarioAsesor) {
                showNotification('Complete los campos obligatorios (Nombres y Usuario/Asesor)', 'error');
                return;
            }
            
            const servicioSelect = document.getElementById('servicioUsuario');
            const servicioTexto = servicioSelect.options[servicioSelect.selectedIndex]?.text || '';
            const nombreCompleto = `${nombres} ${apellidos}`;
            
            const datosActualizados = {
                name: nombreCompleto,
                nombres: nombres,
                apellidos: apellidos,
                username: usuarioAsesor,
                usuario_asesor: usuarioAsesor,
                servicio: servicioId,
                servicio_nombre: servicioTexto,
                nivel_acceso: nivelAcceso,
                modulos: modulosSeleccionados
            };
            
            if(password) {
                datosActualizados.password = password;
            }
            
            showNotification(`Actualizando usuario...`, 'info');
            
            fetch(`/actualizar-usuario/${editandoUsuarioBDId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(datosActualizados)
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    showNotification(`Usuario actualizado correctamente`, 'success');
                    localStorage.setItem('seccionPendiente', 'usuarios');
                    ocultarFormularioUsuario();
                    location.reload();
                } else {
                    showNotification(`Error: ${data.message}`, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error de conexión con el servidor', 'error');
            });
        }
        
        function actualizarUsuarioLocal() {
            const nombres = document.getElementById('nombresUsuario').value.trim();
            const apellidos = document.getElementById('apellidosUsuario').value.trim();
            const cedula = document.getElementById('cedulaUsuario').value.trim();
            let password = document.getElementById('passwordUsuarioNuevo').value.trim();
            const nuevaPassword = document.getElementById('nuevaPasswordUsuario').value.trim();
            const usuarioAsesor = document.getElementById('usuarioAsesor').value.trim();
            const servicioId = document.getElementById('servicioUsuario').value;
            const nivelAcceso = document.getElementById('nivelAccesoUsuario').value;
            const modulosSeleccionados = obtenerModulosSeleccionados();
            
            if(!nombres || !usuarioAsesor) {
                showNotification('Complete los campos obligatorios', 'error');
                return;
            }
            
            if (nuevaPassword !== '') {
                password = nuevaPassword;
            }
            if (!password) {
                password = usuariosDB[editandoUsuarioId].password;
            }
            
            const servicioObj = serviciosDB.find(s => s.id_servicio == servicioId);
            const usuarioActualizado = { 
                id: usuariosDB[editandoUsuarioId].id,
                nombres, 
                apellidos, 
                cedula: usuarioAsesor, 
                password: password,
                usuario_asesor: usuarioAsesor, 
                servicio: servicioId, 
                servicio_nombre: servicioObj ? servicioObj.nombre_servicio : '', 
                nivel_acceso: nivelAcceso, 
                modulos: modulosSeleccionados 
            };
            
            usuariosDB[editandoUsuarioId] = usuarioActualizado;
            localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB));
            renderizarUsuarios();
            ocultarFormularioUsuario();
            showNotification('Usuario actualizado correctamente', 'success');
        }
        
        function registrarUsuarioNuevoModificado(event) {
            event.preventDefault();
            const nombres = document.getElementById('nombresUsuario').value.trim();
            const apellidos = document.getElementById('apellidosUsuario').value.trim();
            const usuarioAsesor = document.getElementById('usuarioAsesor').value.trim();
            const password = document.getElementById('passwordUsuarioNuevo').value.trim();
            const servicioId = document.getElementById('servicioUsuario').value;
            const nivelAcceso = document.getElementById('nivelAccesoUsuario').value;
            const modulosSeleccionados = obtenerModulosSeleccionados();
            if(!nombres || !usuarioAsesor || !password) { showNotification('Complete los campos obligatorios (Nombres, Usuario y Contraseña)', 'error'); return; }
            const servicioObj = serviciosDB.find(s => s.id_servicio == servicioId);
            const nuevoUsuario = { id: Date.now(), nombres, apellidos, cedula: usuarioAsesor, password, usuario_asesor: usuarioAsesor, servicio: servicioId, servicio_nombre: servicioObj ? servicioObj.nombre_servicio : '', nivel_acceso: nivelAcceso, modulos: modulosSeleccionados };
            usuariosDB.push(nuevoUsuario);
            localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB));
            renderizarUsuarios();
            ocultarFormularioUsuario();
            showNotification('Usuario registrado correctamente', 'success');
        }
        
        function eliminarUsuarioLocal(idx) {
            if(confirm('¿Eliminar este usuario?')) {
                usuariosDB.splice(idx,1);
                localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB));
                renderizarUsuarios();
                showNotification('Usuario eliminado', 'success');
                if(editandoUsuarioId === idx) ocultarFormularioUsuario();
            }
        }
        
        function eliminarUsuarioBD(id, username) {
            if(confirm(`¿Eliminar permanentemente al usuario "${username}" de la base de datos?`)) {
                showNotification(`Eliminando usuario "${username}"...`, 'info');
                fetch(`/eliminar-usuario/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        showNotification(`Usuario "${username}" eliminado correctamente`, 'success');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showNotification(`Error: ${data.message}`, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error de conexión con el servidor', 'error');
                });
            }
        }
        
        function cerrarSesion() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            form.style.display = 'none';
            const token = document.createElement('input');
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(token);
            document.body.appendChild(form);
            form.submit();
        }

        function cargarNombreUsuario() {
            const nombreUsuario = @json(auth()->user()->name ?? 'Usuario');
            const usuarioElement = document.getElementById('usuarioAutenticadoNombre');
            if (usuarioElement) {
                usuarioElement.textContent = nombreUsuario;
            }
        }
        
        function cargarServiciosCompletos() {
            const servicioSelect = document.getElementById('gtServicioFiltro');
            if (!servicioSelect) return;
            servicioSelect.innerHTML = '<option value="">— Todos los servicios —</option>';
            if (serviciosDB.length > 0) {
                serviciosDB.filter(s => s.activo).forEach(servicio => {
                    const option = document.createElement('option');
                    option.value = servicio.id_servicio;
                    option.textContent = servicio.nombre_servicio;
                    servicioSelect.appendChild(option);
                });
            }
            servicioSelect.disabled = false;
        }
        
        function limpiarModulosDropdown() {
            const modulosPermitidosUsuario = @json(auth()->user()->modulos ?? []);
            const moduloDropdown = document.getElementById('moduloDropdownM');
            if (!moduloDropdown) return;
            const moduloOptions = moduloDropdown.querySelectorAll('.modulo-option');
            moduloOptions.forEach(option => {
                const moduloNum = parseInt(option.getAttribute('data-mod'));
                if (modulosPermitidosUsuario.length > 0 && !modulosPermitidosUsuario.includes(String(moduloNum))) {
                    option.style.display = 'none';
                } else {
                    option.style.display = 'flex';
                }
            });
            if (modulosPermitidosUsuario.length > 0) {
                const primerModulo = parseInt(modulosPermitidosUsuario[0]);
                if (!isNaN(primerModulo)) {
                    seleccionarModulo(primerModulo);
                }
            } else {
                moduloOptions.forEach(option => {
                    option.style.display = 'flex';
                });
            }
        }

        const userPermissions = @json($permisos ?? []);
        
        let currentUser = null;
        let selectedSpecialty = null, selectedWindow = null, personaActual = null, voiceEnabled = true;
        let turnCounters = {};
        let reporteFiltrado = [];
        let cedulaActual = '';
        let turnoActivoModal = null;
        let listaTurnosVisible = false;
        let moduloSeleccionado = 1;
        let cicloLlamadaInterval = null;
        let turnoEnCiclo = null;
        let modulosPermitidos = [];
        let currentModuloActivo = null;
        let pendingTurnoData = null;

        const nombreUsuarioAutenticado = @json(auth()->user()->name ?? 'Usuario');
        
        // ==================== FUNCIÓN PARA CALCULAR EDAD ====================
        function calcularEdad(fechaNacimiento) {
            if (!fechaNacimiento || fechaNacimiento === '') return 'No disponible';
            
            const hoy = new Date();
            const nacimiento = new Date(fechaNacimiento);
            
            if (isNaN(nacimiento.getTime())) return 'No disponible';
            
            let edad = hoy.getFullYear() - nacimiento.getFullYear();
            const mesActual = hoy.getMonth();
            const mesNacimiento = nacimiento.getMonth();
            const diaActual = hoy.getDate();
            const diaNacimiento = nacimiento.getDate();
            
            // Si aún no ha cumplido años este año, restar 1
            if (mesActual < mesNacimiento || (mesActual === mesNacimiento && diaActual < diaNacimiento)) {
                edad--;
            }
            
            // Si la edad es negativa (fecha futura), mostrar 0
            if (edad < 0) return '0 años';
            
            return edad + ' años';
        }
        // ==================== FIN FUNCIÓN CALCULAR EDAD ====================

        function esSuperAdministrador() {
            const permisosRequeridos = ['login', 'inicio', 'agregar_paciente', 'usuarios', 'servicios', 'reportes', 'atender_turnos', 'perfil', 'publicidad_tv'];
            for (const permiso of permisosRequeridos) {
                if (userPermissions[permiso] !== 1) {
                    return false;
                }
            }
            return true;
        }
        
        const esAdministrador = esSuperAdministrador();
        
        function getModulosPermitidos() {
            const modulos = [];
            const mapping = {
                'inicio': { seccion: 'inicio', nombre: 'Inicio', icono: 'fas fa-home' },
                'agregar_paciente': { seccion: 'inicio', nombre: 'Agregar Pacientes', icono: 'fas fa-user-plus' },
                'servicios': { seccion: 'servicios', nombre: 'Servicios Médicos', icono: 'fas fa-stethoscope' },
                'usuarios': { seccion: 'usuarios', nombre: 'Gestión de Usuarios', icono: 'fas fa-users' },
                'reportes': { seccion: 'reportes', nombre: 'Reportes', icono: 'fas fa-chart-bar' },
                'atender_turnos': { seccion: 'atender', nombre: 'Gestionar Turnos', icono: 'fas fa-stethoscope' },
                'perfil': { seccion: 'perfil', nombre: 'Mi Perfil', icono: 'fas fa-id-card' },
                'agregar_nivel_acceso': { seccion: 'agregar_nivel', nombre: 'Agregar Nivel de Acceso', icono: 'fas fa-plus-circle' },
                'publicidad_tv': { seccion: 'publicidad_tv', nombre: 'Publicidad TV', icono: 'fas fa-tv' },
                'configuracion': { seccion: 'configuracion', nombre: 'Configuración', icono: 'fas fa-cog' }
            };
            
            for (const [key, value] of Object.entries(userPermissions)) {
                if (value === 1 && mapping[key]) {
                    const existe = modulos.some(m => m.seccion === mapping[key].seccion);
                    if (!existe) {
                        modulos.push({
                            key: key,
                            seccion: mapping[key].seccion,
                            nombre: mapping[key].nombre,
                            icono: mapping[key].icono
                        });
                    }
                }
            }
            return modulos;
        }
        
        function renderizarBarraModulos() {
            const container = document.getElementById('modulosBarContainer');
            if (!container) return;
            modulosPermitidos = getModulosPermitidos();
            if (!esAdministrador) {
                container.style.display = 'block';
                let html = '<div class="modulos-bar">';
                modulosPermitidos.forEach(mod => {
                    const isActive = (currentModuloActivo && currentModuloActivo.seccion === mod.seccion) || 
                                    (!currentModuloActivo && modulosPermitidos[0] && modulosPermitidos[0].seccion === mod.seccion);
                    html += `<button class="modulo-tab ${isActive ? 'active' : ''}" onclick="cambiarModulo('${mod.seccion}', '${mod.key}')">
                        <i class="${mod.icono}"></i> ${mod.nombre}
                    </button>`;
                });
                html += `<button class="btn-cerrar-sesion-modulo" onclick="cerrarSesion()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </button>`;
                html += '</div>';
                container.innerHTML = html;
            } else {
                container.style.display = 'none';
            }
        }
        
        function cambiarModulo(seccion, key) {
            if (currentModuloActivo && currentModuloActivo.seccion === seccion) return;
            const moduloSeleccionado = modulosPermitidos.find(m => m.seccion === seccion);
            if (moduloSeleccionado) {
                currentModuloActivo = moduloSeleccionado;
                renderizarBarraModulos();
                mostrarSeccion(seccion);
                if (seccion === 'atender') {
                    const nombreElement = document.getElementById('usuarioAutenticadoNombre');
                    if (nombreElement) nombreElement.textContent = nombreUsuarioAutenticado;
                    if (typeof turnoActivoModal === 'undefined' || turnoActivoModal === null) {
                        limpiarPanelIzquierdo();
                        actualizarContadoresModal();
                        actualizarConteoDropdown();
                        renderizarListaTurnos();
                        cargarServiciosCompletos();
                        limpiarModulosDropdown();
                    }
                } else if (seccion === 'reportes') {
                    setTimeout(() => generarReporte(), 200);
                } else if (seccion === 'servicios') {
                    cargarServiciosDB();
                } else if (seccion === 'agregar_nivel') {
                    document.getElementById('nuevoNivelAccesoSeccion').value = '';
                } else if (seccion === 'publicidad_tv') {
                    cargarBanners();
                } else if (seccion === 'configuracion') {
                    // No se necesita cargar nada especial
                }
            }
        }
        
        function configurarVistaPorPermisos() {
            const seccionPendiente = localStorage.getItem('seccionPendiente');
            if (seccionPendiente) {
                localStorage.removeItem('seccionPendiente');
                setTimeout(() => {
                    if (seccionPendiente === 'usuarios') {
                        mostrarSeccion('usuarios');
                        cargarUsuarios();
                        poblarSelectServiciosUsuario();
                    } else if (seccionPendiente === 'publicidad_tv') {
                        mostrarSeccion('publicidad_tv');
                        cargarBanners();
                    }
                }, 100);
            }
            
            if (!esAdministrador) {
                const sidebar = document.getElementById('mainSidebar');
                if (sidebar) sidebar.classList.add('sidebar-hidden');
                const mainContent = document.getElementById('mainContent');
                if (mainContent) {
                    mainContent.classList.add('main-content-full');
                    mainContent.style.marginLeft = '0';
                }
                modulosPermitidos = getModulosPermitidos();
                if (modulosPermitidos.length > 0) {
                    document.body.classList.add('modo-trabajador');
                    currentModuloActivo = modulosPermitidos[0];
                    renderizarBarraModulos();
                    mostrarSeccion(currentModuloActivo.seccion);
                    if (currentModuloActivo.seccion === 'atender') {
                        const nombreElement = document.getElementById('usuarioAutenticadoNombre');
                        if (nombreElement) nombreElement.textContent = nombreUsuarioAutenticado;
                        if (typeof turnoActivoModal === 'undefined' || turnoActivoModal === null) {
                            limpiarPanelIzquierdo();
                            actualizarContadoresModal();
                            actualizarConteoDropdown();
                            renderizarListaTurnos();
                            cargarServiciosCompletos();
                            limpiarModulosDropdown();
                        }
                    } else if (currentModuloActivo.seccion === 'reportes') {
                        setTimeout(() => generarReporte(), 200);
                    } else if (currentModuloActivo.seccion === 'servicios') {
                        cargarServiciosDB();
                    } else if (currentModuloActivo.seccion === 'agregar_nivel') {
                        document.getElementById('nuevoNivelAccesoSeccion').value = '';
                    } else if (currentModuloActivo.seccion === 'publicidad_tv') {
                        cargarBanners();
                    } else if (currentModuloActivo.seccion === 'configuracion') {
                        // No se necesita cargar nada especial
                    }
                } else {
                    mostrarAccesoDenegado();
                }
            } else {
                document.body.classList.remove('modo-trabajador');
                const container = document.getElementById('modulosBarContainer');
                if (container) container.style.display = 'none';
            }
        }
        
        function abrirModalServicio() {
            if (!tienePermiso('servicios')) {
                mostrarAccesoDenegado();
                return;
            }
            document.getElementById('modalTitulo').textContent = 'Agregar Servicio';
            document.getElementById('formServicio').reset();
            document.getElementById('servicioId').value = '';
            document.getElementById('activo').value = '1';
            document.getElementById('requiereOrdenMedica').checked = false;
            document.getElementById('modalServicio').classList.add('abierto');
        }

        function cerrarModalServicio() {
            document.getElementById('modalServicio').classList.remove('abierto');
        }

        document.getElementById('modalServicio').addEventListener('click', function(e) {
            if (e.target === this) cerrarModalServicio();
        });

        function tienePermiso(moduloId) {
            const moduloMap = { 
                'inicio': 'inicio',
                'servicios': 'servicios', 
                'usuarios': 'usuarios', 
                'agregar_paciente': 'agregar_paciente', 
                'atender_turnos': 'atender_turnos', 
                'reportes': 'reportes', 
                'perfil': 'perfil',
                'agregar_nivel_acceso': 'agregar_nivel_acceso',
                'publicidad_tv': 'publicidad_tv',
                'configuracion': 'configuracion'
            };
            const key = moduloMap[moduloId];
            if (!key) return false;
            return userPermissions[key] === 1;
        }

        function mostrarAccesoDenegado() {
            const overlay = document.createElement('div');
            overlay.className = 'acceso-denegado-overlay';
            overlay.innerHTML = `<div class="acceso-denegado-card"><i class="fas fa-lock"></i><h2>Acceso Denegado</h2><p>Usted no está autorizado para entrar aquí.</p><p style="font-size: 0.85rem;">Por favor, contacte al administrador del sistema.</p><button onclick="this.closest('.acceso-denegado-overlay').remove()">Aceptar</button></div>`;
            document.body.appendChild(overlay);
            overlay.addEventListener('click', function(e) { if (e.target === overlay) overlay.remove(); });
        }

        function verificarAcceso(modulo) {
            if (!esAdministrador) {
                const moduloObj = modulosPermitidos.find(m => m.key === modulo);
                if (moduloObj) {
                    cambiarModulo(moduloObj.seccion, moduloObj.key);
                } else {
                    mostrarAccesoDenegado();
                }
                return;
            }
            if (tienePermiso(modulo)) {
                if (modulo === 'agregar_paciente') { 
                    abrirModalPersona(); 
                }
                else if (modulo === 'atender_turnos') { 
                    mostrarSeccion('atender');
                    const nombreElement = document.getElementById('usuarioAutenticadoNombre');
                    if (nombreElement) nombreElement.textContent = nombreUsuarioAutenticado;
                    if (typeof turnoActivoModal === 'undefined' || turnoActivoModal === null) {
                        limpiarPanelIzquierdo();
                        actualizarContadoresModal();
                        actualizarConteoDropdown();
                        renderizarListaTurnos();
                        cargarServiciosCompletos();
                        limpiarModulosDropdown();
                    }
                }
                else { 
                    mostrarSeccion(modulo); 
                }
            } else { 
                mostrarAccesoDenegado(); 
            }
        }

        function abrirModalPersona() { 
            const form = document.getElementById('formRegistrarPersona');
            if (form) form.reset();
            document.getElementById('regIdentificacion').value = '';
            document.getElementById('regPrimerNombre').value = '';
            document.getElementById('regSegundoNombre').value = '';
            document.getElementById('regPrimerApellido').value = '';
            document.getElementById('regSegundoApellido').value = '';
            document.getElementById('regZona').value = '';
            document.getElementById('regFechaNacimiento').value = '';
            document.getElementById('modalAgregarPersona').style.display = 'flex';
        }
        
        function abrirModalPersonaYPrecargar() {
            const cedulaBuscada = document.getElementById('cedula').value.trim();
            const form = document.getElementById('formRegistrarPersona');
            if (form) form.reset();
            document.getElementById('regIdentificacion').value = '';
            document.getElementById('regPrimerNombre').value = '';
            document.getElementById('regSegundoNombre').value = '';
            document.getElementById('regPrimerApellido').value = '';
            document.getElementById('regSegundoApellido').value = '';
            document.getElementById('regZona').value = '';
            document.getElementById('regFechaNacimiento').value = '';
            if (cedulaBuscada !== '') {
                document.getElementById('regIdentificacion').value = cedulaBuscada;
            }
            document.getElementById('modalAgregarPersona').style.display = 'flex';
        }
        
        function mostrarSeccion(sec) {
            document.getElementById('seccion-inicio').style.display = 'none';
            document.getElementById('seccion-servicios').style.display = 'none';
            document.getElementById('seccion-usuarios').style.display = 'none';
            document.getElementById('seccion-reportes').style.display = 'none';
            document.getElementById('seccion-perfil').style.display = 'none';
            document.getElementById('seccion-atender').style.display = 'none';
            document.getElementById('seccion-agregar-nivel').style.display = 'none';
            document.getElementById('seccion-publicidad-tv').style.display = 'none';
            document.getElementById('seccion-configuracion').style.display = 'none';
            
            if (esAdministrador) {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            }
            
            if(sec === 'inicio') { 
                document.getElementById('seccion-inicio').style.display = 'block'; 
                if (esAdministrador) document.querySelectorAll('.nav-item')[0].classList.add('active'); 
                actualizarVista(); 
            }
            else if(sec === 'servicios') { 
                document.getElementById('seccion-servicios').style.display = 'block'; 
                if (esAdministrador) document.getElementById('navServicios').classList.add('active'); 
                cargarServiciosDB(); 
            }
            else if(sec === 'usuarios') { 
                document.getElementById('seccion-usuarios').style.display = 'block'; 
                if (esAdministrador) document.getElementById('navUsuarios').classList.add('active'); 
                cargarUsuarios(); 
                poblarSelectServiciosUsuario(); 
            }
            else if(sec === 'perfil') { 
                document.getElementById('seccion-perfil').style.display = 'block'; 
                if (esAdministrador) document.getElementById('navPerfil').classList.add('active'); 
                cargarPermisosDesdeLocalStorage(); 
            }
            else if(sec === 'reportes') { 
                document.getElementById('seccion-reportes').style.display = 'block'; 
                if (esAdministrador) document.getElementById('navReportes').classList.add('active'); 
                generarReporte(); 
            }
            else if(sec === 'atender') { 
                document.getElementById('seccion-atender').style.display = 'block'; 
                if (esAdministrador) document.getElementById('navAtenderTurnos').classList.add('active'); 
                cargarNombreUsuario();
                cargarServiciosCompletos();
                limpiarModulosDropdown();
            }
            else if(sec === 'agregar_nivel') {
                document.getElementById('seccion-agregar-nivel').style.display = 'block';
                document.getElementById('nuevoNivelAccesoSeccion').value = '';
            }
            else if(sec === 'publicidad_tv') {
                document.getElementById('seccion-publicidad-tv').style.display = 'block';
                if (esAdministrador) document.getElementById('navPublicidadTV').classList.add('active');
                cargarBanners();
            }
            else if(sec === 'configuracion') {
                document.getElementById('seccion-configuracion').style.display = 'block';
                if (esAdministrador) {
                    document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                }
            }
        }

        function cargarPermisosDesdeLocalStorage() {
            const permisosGuardados = localStorage.getItem('permisos_sistema');
            if(permisosGuardados) {
                const permisos = JSON.parse(permisosGuardados);
                const set = (name, val) => { const el = document.querySelector(`input[name="${name}"][value="${val ? '1' : '0'}"]`); if(el) el.checked = true; };
                set('permiso_login', permisos.login);
                set('permiso_inicio', permisos.inicio);
                set('permiso_agregar_pacientes', permisos.agregar_paciente);
                set('permiso_usuarios', permisos.usuarios);
                set('permiso_servicios', permisos.servicios);
                set('permiso_reportes', permisos.reportes);
                set('permiso_atender_turnos', permisos.atender_turnos);
                set('permiso_publicidad_tv', permisos.publicidad_tv);
                set('permiso_perfil', permisos.perfil);
            }
        }

        function actualizarPermisos() { showNotification('Permisos cargados desde el sistema', 'info'); }

        function guardarPermisos() {
            const username = document.getElementById('buscarUsuarioPermisos').value.trim();
            if(username === '') { showNotification('⚠️ Primero busque un usuario para modificar sus permisos', 'warning'); return; }
            const permisos = {
                login: document.querySelector('input[name="permiso_login"]:checked').value === '1' ? 1 : 0,
                inicio: document.querySelector('input[name="permiso_inicio"]:checked').value === '1' ? 1 : 0,
                agregar_paciente: document.querySelector('input[name="permiso_agregar_pacientes"]:checked').value === '1' ? 1 : 0,
                usuarios: document.querySelector('input[name="permiso_usuarios"]:checked').value === '1' ? 1 : 0,
                servicios: document.querySelector('input[name="permiso_servicios"]:checked').value === '1' ? 1 : 0,
                reportes: document.querySelector('input[name="permiso_reportes"]:checked').value === '1' ? 1 : 0,
                atender_turnos: document.querySelector('input[name="permiso_atender_turnos"]:checked').value === '1' ? 1 : 0,
                agregar_nivel_acceso: document.querySelector('input[name="permiso_agregar_nivel_acceso"]:checked').value === '1' ? 1 : 0,
                publicidad_tv: document.querySelector('input[name="permiso_publicidad_tv"]:checked').value === '1' ? 1 : 0,
                perfil: document.querySelector('input[name="permiso_perfil"]:checked').value === '1' ? 1 : 0,
                configuracion: document.querySelector('input[name="permiso_configuracion"]:checked')?.value === '1' ? 1 : 0
            };
            showNotification(`💾 Guardando permisos para usuario: ${username}...`, 'info');
            fetch('/guardar-permisos-usuario', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ username, permisos }) })
            .then(r => r.json()).then(data => { if(data.success) { showNotification(`✅ Permisos guardados correctamente para "${username}"`, 'success'); localStorage.setItem('permisos_sistema', JSON.stringify(permisos)); } else { showNotification(`❌ Error: ${data.message}`, 'error'); } })
            .catch(() => showNotification('❌ Error de conexión con el servidor', 'error'));
        }

        function filtrarPermisos() {
            const busqueda = document.getElementById('buscarUsuarioPermisos').value.trim();
            if(busqueda === '') { showNotification('Ingrese un nombre de usuario o número de identificación para buscar', 'info'); return; }
            showNotification(`🔍 Buscando: ${busqueda}...`, 'info');
            fetch('/buscar-usuario-permisos', { 
                method: 'POST', 
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                }, 
                body: JSON.stringify({ username: busqueda, identificacion: busqueda }) 
            })
            .then(r => r.json()).then(data => {
                if(data.success) {
                    const p = data.usuario.permisos;
                    const set = (name, val) => { const el = document.querySelector(`input[name="${name}"][value="${val == 1 ? '1' : '0'}"]`); if(el) el.checked = true; };
                    set('permiso_login', p.login);
                    set('permiso_inicio', p.inicio);
                    set('permiso_agregar_nivel_acceso', p.agregar_nivel_acceso);
                    set('permiso_agregar_pacientes', p.agregar_paciente);
                    set('permiso_usuarios', p.usuarios);
                    set('permiso_servicios', p.servicios);
                    set('permiso_reportes', p.reportes);
                    set('permiso_atender_turnos', p.atender_turnos);
                    set('permiso_publicidad_tv', p.publicidad_tv);
                    set('permiso_perfil', p.perfil);
                    showNotification(`✅ Usuario encontrado: ${data.usuario.name || data.usuario.username}`, 'success');
                } else { showNotification(`❌ Usuario "${busqueda}" no encontrado`, 'error'); }
            }).catch(() => showNotification('❌ Error de conexión con el servidor', 'error'));
        }

        function getNombreModulo(modulo) { return `MÓDULO ${modulo}`; }
        function toggleModuloDropdown() { const btn = document.getElementById('moduloBtnClick'); const dropdown = document.getElementById('moduloDropdownM'); btn.classList.toggle('open'); dropdown.classList.toggle('open'); actualizarConteoDropdown(); }
        function actualizarConteoDropdown() { 
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const servicioFiltro = document.getElementById('gtServicioFiltro') ? document.getElementById('gtServicioFiltro').value : ''; 
            for(let mod = 1; mod <= 6; mod++) { 
                let lista = servicioFiltro ? turnos.filter(t => String(t.especialidad) === String(servicioFiltro)) : turnos.filter(t => parseInt(t.ventanilla) === mod); 
                const activos = lista.filter(t => t.estado === 'pendiente' || t.estado === 'llamado').length; 
                const atendidos = lista.filter(t => t.estado === 'atendido').length; 
                const el = document.getElementById(`countMod${mod}`); 
                if(el) el.textContent = `${activos} activos · ${atendidos} atendidos`; 
            } 
        }
        function detenerCicloLlamada() { if(cicloLlamadaInterval) { clearInterval(cicloLlamadaInterval); cicloLlamadaInterval = null; turnoEnCiclo = null; if(window.speechSynthesis) window.speechSynthesis.cancel(); } }
        
        function iniciarCicloLlamada(numeroTurno, nombrePaciente, moduloTexto) { 
            detenerCicloLlamada(); 
            turnoEnCiclo = numeroTurno; 
            const hablar = () => { 
                if(voiceEnabled && window.speechSynthesis) { 
                    let mensaje = `Turno ${numeroTurno}`;
                    if(nombrePaciente && nombrePaciente !== '') {
                        mensaje += `, paciente ${nombrePaciente}`;
                    }
                    mensaje += `, por favor acérquese al ${moduloTexto}`;
                    const u = new SpeechSynthesisUtterance(mensaje); 
                    u.lang = 'es-ES'; 
                    u.rate = 0.9; 
                    window.speechSynthesis.speak(u); 
                } 
            }; 
            hablar(); 
            let rep = 0; 
            cicloLlamadaInterval = setInterval(() => { 
                rep++; 
                if(rep >= 24) detenerCicloLlamada(); 
                else hablar(); 
            }, 5000); 
        }
        
        function seleccionarModulo(num) { moduloSeleccionado = num; document.getElementById('moduloBtnTexto').textContent = getNombreModulo(num); document.getElementById('gtModuloNombre').textContent = getNombreModulo(num); document.querySelectorAll('.modulo-option').forEach(o => o.classList.toggle('active-mod', parseInt(o.getAttribute('data-mod')) === num)); document.getElementById('moduloBtnClick').classList.remove('open'); document.getElementById('moduloDropdownM').classList.remove('open'); if(turnoActivoModal && parseInt(turnoActivoModal.ventanilla) !== num) limpiarPanelIzquierdo(); actualizarContadoresModal(); renderizarListaTurnos(); showNotification(`📌 ${getNombreModulo(num)} seleccionado`, 'success'); }
        document.addEventListener('click', function(e) { const wrapper = document.getElementById('moduloSelectorWrapper'); if(wrapper && !wrapper.contains(e.target)) { document.getElementById('moduloBtnClick').classList.remove('open'); document.getElementById('moduloDropdownM').classList.remove('open'); } });

        function cerrarModalPersona() { document.getElementById('modalAgregarPersona').style.display = 'none'; document.getElementById('formRegistrarPersona').reset(); }

        // ==================== FUNCIÓN REGISTRAR PERSONA MODIFICADA CON FECHA DE NACIMIENTO ====================
        async function registrarPersona(event) {
            event.preventDefault();
            const identificacion = document.getElementById('regIdentificacion').value.trim();
            const primer_nombre = document.getElementById('regPrimerNombre').value.trim();
            const segundo_nombre = document.getElementById('regSegundoNombre').value.trim();
            const primer_apellido = document.getElementById('regPrimerApellido').value.trim();
            const segundo_apellido = document.getElementById('regSegundoApellido').value.trim();
            const zona = document.getElementById('regZona').value;
            const fecha_nacimiento = document.getElementById('regFechaNacimiento').value;
            
            if(!identificacion || !primer_nombre || !primer_apellido) { showNotification('⚠️ Complete los campos obligatorios', 'error'); return; }
            if(!/^\d+$/.test(identificacion)) { showNotification('⚠️ La identificación solo debe contener números', 'error'); return; }
            if(!zona) { showNotification('⚠️ Seleccione una zona (URBANO o RURAL)', 'error'); return; }
            if(!fecha_nacimiento) { showNotification('⚠️ Seleccione la fecha de nacimiento', 'error'); return; }
            
            const btnGuardar = document.querySelector('#formRegistrarPersona button[type="submit"]');
            const textoOriginal = btnGuardar.innerHTML;
            btnGuardar.disabled = true; btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            try {
                const response = await fetch('/personas', { 
                    method: 'POST', 
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
                        'Accept': 'application/json' 
                    }, 
                    body: JSON.stringify({ 
                        identificacion, 
                        primer_nombre, 
                        segundo_nombre: segundo_nombre || null, 
                        primer_apellido, 
                        segundo_apellido: segundo_apellido || null,
                        zona: zona,
                        fecha_nacimiento: fecha_nacimiento
                    }) 
                });
                const data = await response.json();
                if(response.ok && data.success) { 
                    showNotification('✅ Paciente registrado exitosamente', 'success'); 
                    cerrarModalPersona(); 
                    document.getElementById('cedula').value = identificacion; 
                    await buscarPersona(); 
                }
                else { 
                    let msg = data.message || 'Error al registrar el paciente'; 
                    if(data.errors) msg = Object.values(data.errors).flat().join(', '); 
                    showNotification('❌ ' + msg, 'error'); 
                }
            } catch(e) { showNotification('❌ Error de conexión con el servidor', 'error'); }
            finally { btnGuardar.disabled = false; btnGuardar.innerHTML = textoOriginal; }
        }

        // ==================== FUNCIÓN BUSCAR PERSONA MODIFICADA CON CÁLCULO DE EDAD ====================
        async function buscarPersona() {
            const ced = document.getElementById('cedula').value.trim();
            cedulaActual = ced;
            if(ced.length < 5) { showNotification('⚠️ Cédula inválida (mínimo 5 caracteres)', 'error'); return; }
            const btnBuscar = document.getElementById('btnBuscar');
            const textoOriginal = btnBuscar.innerHTML;
            btnBuscar.disabled = true; btnBuscar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';
            const resultadoDiv = document.getElementById('resultadoBusqueda');
            try {
                const res = await fetch('/buscar-persona', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ identificacion: ced }) });
                const data = await res.json();
                if(data.success) {
                    personaActual = data.persona;
                    document.getElementById('personaNombreCompleto').innerHTML = `${personaActual.primer_nombre} ${personaActual.segundo_nombre || ''} ${personaActual.primer_apellido} ${personaActual.segundo_apellido || ''}`;
                    document.getElementById('primerNombre').innerHTML = personaActual.primer_nombre || '---';
                    document.getElementById('segundoNombre').innerHTML = personaActual.segundo_nombre || '---';
                    document.getElementById('primerApellido').innerHTML = personaActual.primer_apellido || '---';
                    document.getElementById('segundoApellido').innerHTML = personaActual.segundo_apellido || '---';
                    
                    const campoZona = document.getElementById('campoZona');
                    const personaZona = document.getElementById('personaZona');
                    if (personaActual.zona && personaActual.zona !== '') {
                        let zonaTexto = '';
                        let zonaClase = '';
                        if (personaActual.zona === 'U') {
                            zonaTexto = 'URBANO';
                            zonaClase = 'urbano';
                        } else if (personaActual.zona === 'R') {
                            zonaTexto = 'RURAL';
                            zonaClase = 'rural';
                        } else {
                            zonaTexto = personaActual.zona;
                        }
                        personaZona.textContent = zonaTexto;
                        campoZona.className = 'campo ' + zonaClase;
                        campoZona.style.display = 'block';
                    } else {
                        campoZona.style.display = 'none';
                    }
                    
                    // ===== MOSTRAR FECHA DE NACIMIENTO =====
                    const campoFechaNacimiento = document.getElementById('campoFechaNacimiento');
                    const personaFechaNacimiento = document.getElementById('personaFechaNacimiento');
                    if (personaActual.fecha_nacimiento && personaActual.fecha_nacimiento !== '') {
                        personaFechaNacimiento.textContent = personaActual.fecha_nacimiento;
                        campoFechaNacimiento.style.display = 'block';
                    } else {
                        campoFechaNacimiento.style.display = 'none';
                    }
                    // ===== FIN MOSTRAR FECHA DE NACIMIENTO =====
                    
                    // ===== NUEVO: CALCULAR Y MOSTRAR EDAD =====
                    const campoEdad = document.getElementById('campoEdad');
                    const personaEdad = document.getElementById('personaEdad');
                    if (personaActual.fecha_nacimiento && personaActual.fecha_nacimiento !== '') {
                        const edadCalculada = calcularEdad(personaActual.fecha_nacimiento);
                        personaEdad.innerHTML = `<i class="fas fa-cake-candles"></i> ${edadCalculada}`;
                        campoEdad.style.display = 'block';
                    } else {
                        campoEdad.style.display = 'none';
                    }
                    // ===== FIN CALCULAR Y MOSTRAR EDAD =====
                    
                    document.getElementById('personaInfo').style.display = 'block';
                    document.querySelectorAll('.specialty-card').forEach(c => c.classList.remove('disabled'));
                    resultadoDiv.innerHTML = `<div style="background:#d4edda;border:1px solid #28a745;border-radius:12px;padding:12px;margin-bottom:15px;text-align:center;"><i class="fas fa-check-circle" style="color:#28a745;"></i><strong>✅ Paciente encontrado</strong></div>`;
                    showNotification('✅ Paciente encontrado en la base de datos', 'success');
                } else {
                    document.getElementById('personaInfo').style.display = 'none';
                    personaActual = null;
                    document.querySelectorAll('.specialty-card').forEach(c => c.classList.add('disabled'));
                    resultadoDiv.innerHTML = `<div class="no-encontrado-mensaje"><i class="fas fa-user-slash"></i><strong>⚠️ Paciente no encontrado</strong><p>No se encontró ningún paciente con la cédula: <strong>${ced}</strong></p><button class="btn-crear-paciente" onclick="abrirModalPersonaYPrecargar()"><i class="fas fa-user-plus"></i> Crear Paciente</button></div>`;
                    showNotification('⚠️ Paciente no encontrado', 'warning');
                }
            } catch(e) { resultadoDiv.innerHTML = `<div class="no-encontrado-mensaje" style="background:#f8d7da;border-color:#dc3545;"><i class="fas fa-exclamation-triangle" style="color:#dc3545;"></i><strong>❌ Error de conexión</strong></div>`; showNotification('Error al buscar persona', 'error'); }
            finally { btnBuscar.disabled = false; btnBuscar.innerHTML = textoOriginal; }
        }

        async function cargarServiciosDB() {
            try {
                const response = await fetch('/api/servicios');
                const servicios = await response.json();
                serviciosDB = servicios;
                const gridContainer = document.getElementById('serviciosGridContainer');
                if(gridContainer) {
                    if(serviciosDB.length === 0) gridContainer.innerHTML = '<div style="text-align:center;padding:40px;">No hay servicios registrados</div>';
                    else {
                        gridContainer.innerHTML = '';
                        serviciosDB.forEach(servicio => {
                            const card = document.createElement('div');
                            card.className = 'servicio-card';
                            card.innerHTML = `<h3><i class="fas fa-stethoscope" style="color:#ffd966;"></i> ${servicio.nombre_servicio}</h3><div class="descripcion">${servicio.descripcion || 'Sin descripción'}</div><div class="badge-orden ${servicio.requiere_orden_medica ? 'si' : 'no'}">${servicio.requiere_orden_medica ? '✅ Requiere orden médica' : '❌ No requiere orden médica'}</div><div class="estado ${servicio.activo ? 'activo' : 'inactivo'}">${servicio.activo ? 'ACTIVO' : 'INACTIVO'}</div><div class="card-actions"><button class="btn-editar-card" onclick="editarServicioDB(${servicio.id_servicio})"><i class="fas fa-edit"></i> Editar</button><button class="btn-eliminar-card" onclick="eliminarServicioDB(${servicio.id_servicio})"><i class="fas fa-trash-alt"></i> ${servicio.activo ? 'Desactivar' : 'Eliminar'}</button></div>`;
                            gridContainer.appendChild(card);
                        });
                    }
                    actualizarGridServiciosInicio();
                    cargarServiciosCompletos();
                    poblarSelectServiciosUsuario();
                }
            } catch(error) { console.error(error); }
        }

        function poblarSelectServiciosUsuario() { const sel = document.getElementById('servicioUsuario'); if(!sel) return; sel.innerHTML = '<option value="">Seleccionar servicio</option>'; serviciosDB.filter(s=>s.activo).forEach(s=>{ const opt=document.createElement('option'); opt.value=s.id_servicio; opt.textContent=s.nombre_servicio; sel.appendChild(opt); }); }

        function actualizarGridServiciosInicio() {
            const grid = document.getElementById('serviciosGridInicio');
            if(!grid) return;
            grid.innerHTML = '';
            serviciosDB.filter(s => s.activo === true).forEach(servicio => {
                const card = document.createElement('div');
                card.className = 'specialty-card disabled';
                card.setAttribute('data-specialty', servicio.id_servicio);
                card.setAttribute('data-window', 1);
                card.innerHTML = `<div class="specialty-icon">🏥</div><div class="specialty-name">${servicio.nombre_servicio}</div>`;
                grid.appendChild(card);
            });
            document.querySelectorAll('.specialty-card').forEach(card => {
                card.addEventListener('click', () => {
                    if(card.classList.contains('disabled') || !personaActual) { showNotification('⚠️ Primero busque un paciente', 'warning'); return; }
                    document.querySelectorAll('.specialty-card').forEach(c => c.classList.remove('selected'));
                    card.classList.add('selected');
                    selectedSpecialty = card.getAttribute('data-specialty');
                    selectedWindow = card.getAttribute('data-window');
                    document.getElementById('generateBtn').disabled = false;
                });
            });
        }

        async function guardarServicioDB(event) {
            if(event) event.preventDefault();
            const servicioId = document.getElementById('servicioId').value;
            const servicioData = { nombre_servicio: document.getElementById('nombreServicio').value.trim(), descripcion: document.getElementById('descripcion').value.trim(), requiere_orden_medica: document.getElementById('requiereOrdenMedica').checked, activo: document.getElementById('activo').value === '1' };
            if(!servicioData.nombre_servicio) { showNotification('⚠️ Complete el nombre del servicio', 'error'); return; }
            try {
                let url = '/api/servicios', method = 'POST';
                if(servicioId) { url = `/api/servicios/${servicioId}`; method = 'PUT'; }
                const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify(servicioData) });
                const result = await response.json();
                if(response.ok && result.success) { showNotification(servicioId ? '✅ Servicio actualizado' : '✅ Servicio agregado', 'success'); cerrarModalServicio(); cargarServiciosDB(); }
                else { showNotification('Error: ' + (result.message || 'No se pudo guardar'), 'error'); }
            } catch(error) { console.error(error); showNotification('Error al conectar con el servidor', 'error'); }
        }

        function editarServicioDB(id) {
            const servicio = serviciosDB.find(s => s.id_servicio === id);
            if(servicio) {
                document.getElementById('modalTitulo').textContent = 'Editar Servicio';
                document.getElementById('servicioId').value = servicio.id_servicio;
                document.getElementById('nombreServicio').value = servicio.nombre_servicio;
                document.getElementById('descripcion').value = servicio.descripcion || '';
                document.getElementById('requiereOrdenMedica').checked = servicio.requiere_orden_medica;
                document.getElementById('activo').value = servicio.activo ? '1' : '0';
                document.getElementById('modalServicio').classList.add('abierto');
            }
        }

        async function eliminarServicioDB(id) {
            if(confirm('¿Eliminar permanentemente este servicio?')) {
                const response = await fetch(`/api/servicios/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                const result = await response.json();
                if(result.success) { showNotification('Servicio eliminado', 'success'); cargarServiciosDB(); }
                else showNotification('Error al eliminar', 'error');
            }
        }

        function limpiarPanelIzquierdo() { 
            const gtNumeroTurno = document.getElementById('gtNumeroTurno');
            if(gtNumeroTurno) {
                gtNumeroTurno.textContent = 'NÚMERO';
                gtNumeroTurno.classList.add('vacio');
            }
            const gtDocumento = document.getElementById('gtDocumento');
            if(gtDocumento) {
                gtDocumento.textContent = '—';
                gtDocumento.classList.add('vacio');
            }
            const gtPrimerApellido = document.getElementById('gtPrimerApellido');
            if(gtPrimerApellido) {
                gtPrimerApellido.textContent = '—';
                gtPrimerApellido.classList.add('vacio');
            }
            const gtSegundoApellido = document.getElementById('gtSegundoApellido');
            if(gtSegundoApellido) {
                gtSegundoApellido.textContent = '—';
                gtSegundoApellido.classList.add('vacio');
            }
            const gtPrimerNombre = document.getElementById('gtPrimerNombre');
            if(gtPrimerNombre) {
                gtPrimerNombre.textContent = '—';
                gtPrimerNombre.classList.add('vacio');
            }
            turnoActivoModal = null; 
        }
        
        function cargarTurnoEnPanel(turno) { 
            turnoActivoModal = turno; 
            const gtNumeroTurno = document.getElementById('gtNumeroTurno');
            if(gtNumeroTurno) {
                gtNumeroTurno.textContent = turno.numero;
                gtNumeroTurno.classList.remove('vacio');
            }
            const gtDocumento = document.getElementById('gtDocumento');
            if(gtDocumento) {
                gtDocumento.textContent = turno.identificacion || '—';
                gtDocumento.classList.remove('vacio');
            }
            const nombre = turno.nombre_persona || ''; 
            const partes = nombre.trim().split(' '); 
            const gtPrimerNombre = document.getElementById('gtPrimerNombre');
            if(gtPrimerNombre) {
                gtPrimerNombre.textContent = partes[0] || '—';
                gtPrimerNombre.classList.remove('vacio');
            }
            const gtPrimerApellido = document.getElementById('gtPrimerApellido');
            if(gtPrimerApellido) {
                gtPrimerApellido.textContent = partes[partes.length - 2] || partes[1] || '—';
                gtPrimerApellido.classList.remove('vacio');
            }
            const gtSegundoApellido = document.getElementById('gtSegundoApellido');
            if(gtSegundoApellido) {
                gtSegundoApellido.textContent = partes[partes.length - 1] || '—';
                gtSegundoApellido.classList.remove('vacio');
            }
            const gtModuloNombre = document.getElementById('gtModuloNombre');
            if(gtModuloNombre) gtModuloNombre.textContent = getNombreModulo(turno.ventanilla || moduloSeleccionado);
            document.querySelectorAll('.turno-espera-item').forEach(el => el.classList.remove('seleccionado')); 
            const itemEl = document.querySelector(`.turno-espera-item[data-num="${turno.numero}"]`); 
            if(itemEl) itemEl.classList.add('seleccionado'); 
        }
        
        function actualizarContadoresModal() { 
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const servicioFiltro = document.getElementById('gtServicioFiltro') ? document.getElementById('gtServicioFiltro').value : ''; 
            let lista = servicioFiltro ? turnos.filter(t => String(t.especialidad) === String(servicioFiltro)) : turnos.filter(t => parseInt(t.ventanilla) === moduloSeleccionado); 
            const gtContActivos = document.getElementById('gtContActivos');
            if(gtContActivos) gtContActivos.textContent = lista.filter(t => t.estado === 'pendiente' || t.estado === 'llamado').length;
            const gtContAtendidos = document.getElementById('gtContAtendidos');
            if(gtContAtendidos) gtContAtendidos.textContent = lista.filter(t => t.estado === 'atendido').length;
            const gtContTotales = document.getElementById('gtContTotales');
            if(gtContTotales) gtContTotales.textContent = lista.length;
        }
        
        function renderizarListaTurnos() { 
            let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const servicioFiltro = document.getElementById('gtServicioFiltro') ? document.getElementById('gtServicioFiltro').value : ''; 
            let activos = turnos.filter(t => t.estado !== 'atendido');
            let espera = servicioFiltro ? 
                activos.filter(t => String(t.especialidad) === String(servicioFiltro)) : 
                activos.filter(t => parseInt(t.ventanilla) === moduloSeleccionado);
            const turnosUnicos = [];
            const numerosVistos = new Set();
            for (const turno of espera) {
                if (!numerosVistos.has(turno.numero)) {
                    numerosVistos.add(turno.numero);
                    turnosUnicos.push(turno);
                }
            }
            turnosUnicos.sort((a, b) => {
                if (a.estado === 'llamado' && b.estado !== 'llamado') return -1;
                if (a.estado !== 'llamado' && b.estado === 'llamado') return 1;
                return 0;
            });
            const body = document.getElementById('turnosEsperaBody'); 
            if(!body) return;
            if(turnosUnicos.length === 0) { 
                body.innerHTML = `<div style="text-align:center;padding:20px;color:#888;font-size:0.85rem;"><i class="fas fa-inbox" style="font-size:1.5rem;margin-bottom:8px;display:block;"></i>No hay turnos en espera</div>`; 
                return; 
            } 
            body.innerHTML = turnosUnicos.map(t => `<div class="turno-espera-item ${turnoActivoModal && turnoActivoModal.numero === t.numero ? 'seleccionado' : ''}" data-num="${t.numero}">
                <div style="flex:1; cursor:pointer;" onclick='cargarTurnoEnPanel(${JSON.stringify(t)})'>
                    <div class="turno-espera-num">${t.numero}</div>
                    <div class="turno-espera-pac">${t.nombre_persona || 'Paciente'} · ${t.nombreEspecialidad || 'MED'}</div>
                </div>
                <div class="turno-actions-lista">
                    <button class="btn-llamar-lista" onclick="event.stopPropagation(); llamarTurno('${t.numero}')"><i class="fas fa-bell"></i> Llamar</button>
                    <button class="btn-atender-lista" onclick="event.stopPropagation(); atenderTurno('${t.numero}')"><i class="fas fa-check"></i> Atender</button>
                </div>
            </div>`).join(''); 
            if(turnoActivoModal) {
                const item = document.querySelector(`.turno-espera-item[data-num="${turnoActivoModal.numero}"]`);
                if(item) item.classList.add('seleccionado');
            }
        }
        
        function atenderTurno(num) { 
            let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const idx = turnos.findIndex(t => t.numero === num); 
            if(idx !== -1 && turnos[idx].estado !== 'atendido') { 
                turnos[idx].estado = 'atendido'; 
                turnos[idx].salida = new Date().toISOString(); 
                localStorage.setItem('turnos', JSON.stringify(turnos)); 
                showNotification(`✅ Turno ${num} atendido correctamente`, 'success'); 
                if(turnoEnCiclo === num) detenerCicloLlamada(); 
                if(turnoActivoModal && turnoActivoModal.numero === num) limpiarPanelIzquierdo(); 
                actualizarVista(); 
                actualizarContadoresModal(); 
                actualizarConteoDropdown(); 
                if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte(); 
                renderizarListaTurnos();
            } else if(idx !== -1) { 
                showNotification(`⚠️ El turno ${num} ya fue atendido`, 'warning'); 
            } else { 
                showNotification(`⚠️ No se encontró el turno ${num}`, 'warning'); 
            } 
        }
        
        function atenderTurnoDesdeLista(num) {
            atenderTurno(num);
        }
        
        function filtrarPorServicio() { actualizarContadoresModal(); actualizarConteoDropdown(); renderizarListaTurnos(); }
        
        function toggleListaTurnos() { 
            listaTurnosVisible = !listaTurnosVisible; 
            const lista = document.getElementById('turnos-espera-lista'); 
            if(listaTurnosVisible) { 
                lista.classList.add('visible'); 
                renderizarListaTurnos(); 
            } else { 
                lista.classList.remove('visible'); 
            } 
        }
        
        function llamarTurno(num) { 
            let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const idx = turnos.findIndex(t => t.numero === num); 
            if(idx !== -1 && turnos[idx].estado !== 'atendido') { 
                turnos[idx].estado = 'llamado'; 
                turnos[idx].ventanillaAsignada = moduloSeleccionado; 
                localStorage.setItem('turnos', JSON.stringify(turnos)); 
                const nombrePaciente = turnos[idx].nombre_persona || '';
                const moduloTexto = getNombreModulo(moduloSeleccionado);
                iniciarCicloLlamada(num, nombrePaciente, moduloTexto); 
                actualizarVista(); 
                actualizarContadoresModal(); 
                actualizarConteoDropdown(); 
                renderizarListaTurnos(); 
                showNotification(`🔔 Turno ${num} llamado al ${moduloTexto}`, 'success'); 
            } else if(idx !== -1) { 
                showNotification(`⚠️ El turno ${num} ya fue atendido`, 'warning');
            } else {
                showNotification(`⚠️ No se encontró el turno ${num}`, 'warning');
            }
        }

        function generarReporte() { 
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const fechaInicio = document.getElementById('fechaInicio').value; 
            const fechaFin = document.getElementById('fechaFin').value; 
            let datos = turnos.map(t => { 
                let s = serviciosDB.find(s => s.id_servicio == t.especialidad) || { nombre_servicio: t.nombreEspecialidad || 'MEDICINA' }; 
                let estadoTexto = t.estado === 'pendiente' || t.estado === 'llamado' ? 'ACTIVO' : t.estado === 'atendido' ? 'FINALIZADO' : 'CANCELADO'; 
                return { 
                    estado: estadoTexto, 
                    turno: t.numero, 
                    servicio: s.nombre_servicio, 
                    documento: t.identificacion || '---', 
                    nombre: t.nombre_persona || 'Paciente', 
                    ingreso: t.timestamp ? formatearFechaLocal(t.timestamp) : '', 
                    salida: t.salida ? formatearFechaLocal(t.salida) : '', 
                    fechaIngreso: t.timestamp ? t.timestamp.split('T')[0] : '' 
                }; 
            }); 
            if(fechaInicio && fechaFin) {
                datos = datos.filter(d => d.fechaIngreso >= fechaInicio && d.fechaIngreso <= fechaFin);
            }
            reporteFiltrado = datos; 
            const tbody = document.getElementById('reporteBody'); 
            if(!reporteFiltrado.length) { 
                tbody.innerHTML = '<tr><td colspan="8">No hay datos en el rango de fechas seleccionado</td' + '</tr>'; 
                return; 
            } 
            tbody.innerHTML = reporteFiltrado.map(d => `
                <tr>
                    <td class="estado-${d.estado === 'ACTIVO' ? 'activo' : 'finalizado'}">${d.estado}</td>
                    <td><strong>${d.turno}</strong></td>
                    <td>${d.servicio}</td>
                    <td>${d.documento}</td>
                    <td>${d.nombre}</td>
                    <td>${d.ingreso}</td>
                    <td>${d.salida}</td>
                    <td>---</td
                </tr>
            `).join(''); 
        }
        
        function buscarEnReporte() { const busqueda = prompt('Buscar:'); if(!busqueda) return; const resultados = reporteFiltrado.filter(d => d.turno.includes(busqueda) || d.nombre.includes(busqueda) || d.documento.includes(busqueda)); const tbody = document.getElementById('reporteBody'); if(!resultados.length) { tbody.innerHTML = '<tr><td colspan="8">No se encontraron resultados</td' + '</tr>'; return; } tbody.innerHTML = resultados.map(d => `<tr><td class="estado-${d.estado === 'ACTIVO' ? 'activo' : 'finalizado'}">${d.estado}</td>
                    <td><strong>${d.turno}</strong></td>
                    <td>${d.servicio}</td>
                    <td>${d.documento}</td>
                    <td>${d.nombre}</td>
                    <td>${d.ingreso}</td>
                    <td>${d.salida}</td>
                    <td>---</td
                </tr>`).join(''); showNotification(`Encontrados ${resultados.length}`, 'success'); }
        function descargarReporte() { if(!reporteFiltrado.length) { showNotification('No hay datos', 'error'); return; } let csv = "ESTADO,TURNO,SERVICIO,DOCUMENTO,NOMBRE,INGRESO,SALIDA\n"; reporteFiltrado.forEach(d => csv += `"${d.estado}","${d.turno}","${d.servicio}","${d.documento}","${d.nombre}","${d.ingreso}","${d.salida}"\n`); const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv' }); const link = document.createElement('a'); link.href = URL.createObjectURL(blob); link.download = `reporte_${new Date().toISOString().split('T')[0]}.csv`; link.click(); showNotification('Reporte descargado', 'success'); }

        function toggleVoice(e) { voiceEnabled = e; document.getElementById('voiceStatus').innerHTML = e ? '✅ Voz activada' : '🔇 Voz desactivada'; showNotification(e ? 'Voz activada' : 'Voz desactivada', e ? 'success' : 'info'); }
        function showNotification(m, t) { const n = document.getElementById('notification'); n.textContent = m; n.style.background = t === 'success' ? '#28a745' : t === 'error' ? '#dc3545' : '#ffc107'; n.style.display = 'block'; setTimeout(() => n.style.display = 'none', 3000); }
        function cerrarHistorial() { document.getElementById('historyModal').style.display = 'none'; }
        function limpiarHistorial() { if(confirm('¿Limpiar historial? Se conservan turnos activos')) { let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); turnos = turnos.filter(t => t.estado === 'pendiente' || t.estado === 'llamado'); localStorage.setItem('turnos', JSON.stringify(turnos)); actualizarVista(); cerrarHistorial(); if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte(); } }
        
        function actualizarVista() { 
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const ate = turnos.filter(t => t.estado === 'atendido'); 
            const ruralesAtendidos = turnos.filter(t => t.estado === 'atendido' && t.zona === 'R'); 
            const ruralesPendientes = turnos.filter(t => (t.estado === 'pendiente' || t.estado === 'llamado') && t.zona === 'R');
            
            document.getElementById('statsAtendidos').innerHTML = ate.length; 
            document.getElementById('statsZona').innerHTML = ruralesAtendidos.length;
            document.getElementById('statsZonaPendiente').innerHTML = ruralesPendientes.length;
        }
        
        function formatearFechaLocal(f) { if(!f) return ''; const d = new Date(f); return `${d.getDate()}/${d.getMonth()+1}/${d.getFullYear()} ${d.getHours()}:${d.getMinutes()}`; }
        function loadCounters() { const s = localStorage.getItem('turnCounters'); if(s) turnCounters = JSON.parse(s); else saveCounters(); }
        function saveCounters() { localStorage.setItem('turnCounters', JSON.stringify(turnCounters)); }
        function cerrarTurnoGenerado() { document.getElementById('turnoGeneradoModal').style.display = 'none'; }

        function cargarUsuarios() { 
            let stored = localStorage.getItem('usuarios_sistema_v2'); 
            if(stored) {
                usuariosDB = JSON.parse(stored);
            } else { 
                usuariosDB = []; 
                localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB)); 
            }
            renderizarUsuarios(); 
        }
        
        function renderizarUsuarios() { 
            const cont = document.getElementById('usuariosGridContainer'); 
            if(!cont) return;
            
            let todosLosUsuarios = [];
            const usuariosVistos = new Set();
            
            if(usuariosDesdeBD && usuariosDesdeBD.length > 0) {
                usuariosDesdeBD.forEach(usuarioBD => {
                    if(usuarioBD.username === 'andres1') {
                        return;
                    }
                    
                    if(usuariosVistos.has(usuarioBD.username)) return;
                    usuariosVistos.add(usuarioBD.username);
                    
                    let modulosArray = [];
                    if(usuarioBD.modulos) {
                        if(Array.isArray(usuarioBD.modulos)) {
                            modulosArray = usuarioBD.modulos;
                        } else if(typeof usuarioBD.modulos === 'string') {
                            try {
                                let modulosStr = usuarioBD.modulos.replace(/'/g, '"');
                                modulosArray = JSON.parse(modulosStr);
                            } catch(e) {
                                modulosArray = [];
                            }
                        }
                    }
                    
                    let servicioNombre = '';
                    if(usuarioBD.servicio && serviciosDB.length > 0) {
                        const servicioEncontrado = serviciosDB.find(s => s.id_servicio == usuarioBD.servicio);
                        if(servicioEncontrado) {
                            servicioNombre = servicioEncontrado.nombre_servicio;
                        }
                    }
                    
                    todosLosUsuarios.push({
                        id: usuarioBD.id,
                        nombres: usuarioBD.name || usuarioBD.username,
                        apellidos: '',
                        cedula: usuarioBD.username,
                        identificacion: usuarioBD.identificacion,
                        password: usuarioBD.password || '',
                        usuario_asesor: usuarioBD.usuario_asesor || usuarioBD.username,
                        servicio: usuarioBD.servicio || '',
                        servicio_nombre: servicioNombre,
                        nivel_acceso: usuarioBD.nivel_acceso || 'admin',
                        modulos: modulosArray,
                        esBD: true,
                        username: usuarioBD.username,
                        idBD: usuarioBD.id
                    });
                });
            }
            
            if(usuariosDB && usuariosDB.length > 0) {
                usuariosDB.forEach(usuarioLocal => {
                    if(!usuariosVistos.has(usuarioLocal.cedula)) {
                        usuariosVistos.add(usuarioLocal.cedula);
                        todosLosUsuarios.push({
                            ...usuarioLocal,
                            esBD: false
                        });
                    }
                });
            }
            
            if(todosLosUsuarios.length === 0) { 
                cont.innerHTML = '<div style="text-align:center;padding:40px;">No hay usuarios registrados</div>'; 
                return; 
            }
            
            cont.innerHTML = todosLosUsuarios.map((u, idx) => {
                const identificacionMostrar = u.usuario_asesor && u.usuario_asesor !== '' ? u.usuario_asesor : u.cedula;
                
                const modulosHtml = u.modulos && u.modulos.length ? 
                    `<div class="modulos-asignados">${u.modulos.map(m => `<span class="modulo-badge">MÓDULO ${m}</span>`).join('')}</div>` : 
                    '<div class="modulos-asignados"><span class="modulo-badge">Sin módulos asignados</span></div>';
                
                if(u.esBD) {
                    const servicioHtml = u.servicio_nombre ? `<p><i class="fas fa-stethoscope"></i> Servicio: ${u.servicio_nombre}</p>` : '';
                    const nivelHtml = u.nivel_acceso && u.nivel_acceso !== 'admin' ? `<p><i class="fas fa-chart-line"></i> Nivel: ${u.nivel_acceso === 'facturador' ? 'Facturador' : u.nivel_acceso}</p>` : (u.nivel_acceso === 'admin' ? `<p><i class="fas fa-chart-line"></i> Nivel: Administrador</p>` : '');
                    const identificacionMostrarCard = u.identificacion ? `<p><i class="fas fa-id-card"></i> Identificación: ${u.identificacion}</p>` : '';
                    
                    return `<div class="usuario-card">
                        <h4><i class="fas fa-user-circle"></i> ${u.nombres} ${u.apellidos} <span style="font-size:0.7rem; background:#28a745; color:white; padding:2px 8px; border-radius:20px; margin-left:8px;">Sistema</span></h4>
                        <p><i class="fas fa-user"></i> ${identificacionMostrar}</p>
                        ${identificacionMostrarCard}
                        ${servicioHtml}
                        ${nivelHtml}
                        ${modulosHtml}
                        <div>
                            <button class="btn-editar-usuario-card" onclick='editarUsuario(${JSON.stringify(u)}, true, null)'><i class="fas fa-edit"></i> Editar</button>
                            <button class="btn-eliminar-usuario-card" onclick="eliminarUsuarioBD(${u.idBD || u.id}, '${u.cedula}')"><i class="fas fa-trash"></i> Eliminar</button>
                        </div>
                    </div>`;
                } else {
                    return `<div class="usuario-card">
                        <h4><i class="fas fa-user-circle"></i> ${u.nombres} ${u.apellidos}</h4>
                        <p><i class="fas fa-id-card"></i> ${u.cedula}</p>
                        <p><i class="fas fa-stethoscope"></i> Servicio: ${u.servicio_nombre || u.servicio || 'N/A'}</p>
                        <p><i class="fas fa-chart-line"></i> Nivel: ${u.nivel_acceso === 'admin' ? 'Administrador' : u.nivel_acceso === 'facturador' ? 'Facturador' : 'N/A'}</p>
                        ${modulosHtml}
                        <div>
                            <button class="btn-editar-usuario-card" onclick='editarUsuario(${JSON.stringify(u)}, false, ${usuariosDB.findIndex(l => l.id === u.id)})'><i class="fas fa-edit"></i> Editar</button>
                            <button class="btn-eliminar-usuario-card" onclick="eliminarUsuarioLocal(${usuariosDB.findIndex(l => l.id === u.id)})"><i class="fas fa-trash"></i> Eliminar</button>
                        </div>
                    </div>`;
                }
            }).join(''); 
        }
        
        registrarUsuarioNuevo = registrarUsuarioNuevoModificado;

        document.getElementById('cedula').addEventListener('keypress', e => { if(e.key === 'Enter') buscarPersona(); });
        document.getElementById('btnBuscar').addEventListener('click', buscarPersona);
        
        document.getElementById('generateBtn').addEventListener('click', async function() {
            if(!selectedSpecialty || !personaActual) { 
                showNotification('Seleccione especialidad', 'warning'); 
                return; 
            }
            
            if (personaActual.zona === 'R') {
                const nombreCompleto = `${personaActual.primer_nombre} ${personaActual.segundo_nombre || ''} ${personaActual.primer_apellido} ${personaActual.segundo_apellido || ''}`.trim();
                document.getElementById('confirmacionPacienteNombre').textContent = `👤 ${nombreCompleto}`;
                document.getElementById('modalConfirmacionRural').classList.add('active');
                
                const btn = this;
                pendingTurnoData = { btn, personaActual, selectedSpecialty };
                return;
            }
            
            ejecutarGenerarTurno(this);
        });

        document.getElementById('btnConfirmarSi').addEventListener('click', function() {
            document.getElementById('modalConfirmacionRural').classList.remove('active');
            if (pendingTurnoData) {
                ejecutarGenerarTurno(pendingTurnoData.btn);
                pendingTurnoData = null;
            }
        });

        document.getElementById('btnConfirmarNo').addEventListener('click', function() {
            document.getElementById('modalConfirmacionRural').classList.remove('active');
            pendingTurnoData = null;
            showNotification('❌ Generación de turno cancelada', 'error');
            if (pendingTurnoData && pendingTurnoData.btn) {
                pendingTurnoData.btn.disabled = false;
                pendingTurnoData.btn.innerHTML = '<i class="fas fa-ticket-alt"></i> Generar Turno';
            }
        });

        document.getElementById('modalConfirmacionRural').addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                pendingTurnoData = null;
                if (pendingTurnoData && pendingTurnoData.btn) {
                    pendingTurnoData.btn.disabled = false;
                    pendingTurnoData.btn.innerHTML = '<i class="fas fa-ticket-alt"></i> Generar Turno';
                }
            }
        });

        function ejecutarGenerarTurno(btn) {
            const servicioObj = serviciosDB.find(s => s.id_servicio == selectedSpecialty);
            const prefix = servicioObj ? servicioObj.nombre_servicio.substring(0, 3).toUpperCase() : 'MED';
            if(!turnCounters[selectedSpecialty]) turnCounters[selectedSpecialty] = 0;
            turnCounters[selectedSpecialty]++;
            if(turnCounters[selectedSpecialty] > 99) turnCounters[selectedSpecialty] = 1;
            const turnoCompleto = `${prefix}-${String(turnCounters[selectedSpecialty]).padStart(2,'0')}`;
            const nombreCompleto = `${personaActual.primer_nombre} ${personaActual.segundo_nombre || ''} ${personaActual.primer_apellido} ${personaActual.segundo_apellido || ''}`.trim();
            let ventanillaAsignada = 1;
            if(servicioObj) { 
                const n = servicioObj.nombre_servicio.toLowerCase(); 
                if(n.includes('odontologia') || n.includes('odontología')) ventanillaAsignada = 2; 
                else if(n.includes('laboratorio')) ventanillaAsignada = 3; 
                else if(n.includes('rayos') || n.includes('radiologia')) ventanillaAsignada = 4; 
            }
            const turno = { 
                id: Date.now() + '-' + Math.random().toString(36).substr(2,9), 
                numero: turnoCompleto, 
                persona_id: personaActual.id, 
                identificacion: personaActual.identificacion, 
                nombre_persona: nombreCompleto, 
                especialidad: selectedSpecialty, 
                nombreEspecialidad: servicioObj ? servicioObj.nombre_servicio : selectedSpecialty, 
                ventanilla: ventanillaAsignada, 
                timestamp: new Date().toISOString(), 
                estado: 'pendiente',
                zona: personaActual.zona || ''  
            };
            saveCounters();
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]');
            turnos.push(turno);
            localStorage.setItem('turnos', JSON.stringify(turnos));
            document.getElementById('turnoGeneradoNumero').textContent = turnoCompleto;
            document.getElementById('turnoGeneradoPaciente').innerHTML = `<i class="fas fa-user"></i> ${nombreCompleto}`;
            document.getElementById('turnoGeneradoServicio').innerHTML = `<i class="fas fa-stethoscope"></i> ${servicioObj ? servicioObj.nombre_servicio : selectedSpecialty}`;
            document.getElementById('turnoGeneradoVentanilla').innerHTML = `<i class="fas fa-door-open"></i> Diríjase a ${getNombreModulo(ventanillaAsignada)}`;
            document.getElementById('turnoGeneradoModal').style.display = 'block';
            showNotification(`Turno ${turnoCompleto} generado`, 'success');
            document.getElementById('cedula').value = '';
            document.getElementById('resultadoBusqueda').innerHTML = '';
            document.getElementById('personaInfo').style.display = 'none';
            personaActual = null;
            document.querySelectorAll('.specialty-card').forEach(c => { 
                c.classList.remove('selected'); 
                c.classList.add('disabled'); 
            });
            selectedSpecialty = null;
            btn.disabled = true;
            actualizarVista();
            if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte();
        }

        window.addEventListener('storage', () => { actualizarVista(); if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte(); });
        setInterval(actualizarVista, 1000);
        document.getElementById('formRegistrarPersona').addEventListener('submit', registrarPersona);
        const nuevoFormUsuario = document.getElementById('formRegistrarUsuarioNuevo');
        if(nuevoFormUsuario) nuevoFormUsuario.addEventListener('submit', registrarUsuarioNuevo);
        
        const modulosSelectBtn = document.getElementById('modulosSelectBtn');
        if (modulosSelectBtn) {
            modulosSelectBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleModulosDropdown();
            });
        }

        cargarServiciosDB();
        loadCounters();
        actualizarVista();
        toggleVoice(true);
        cargarUsuarios();
        cargarNombreUsuario();
        cargarNivelesAcceso();
        
        configurarVistaPorPermisos();
        
        setTimeout(() => {
            if(document.getElementById('gtServicioFiltro')) {
                cargarServiciosCompletos();
                limpiarModulosDropdown();
            }
        }, 500);
    </script>
</body>
</html>