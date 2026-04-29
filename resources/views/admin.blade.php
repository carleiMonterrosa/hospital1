{{-- resources/views/admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrador Turnos - Hospital San Pablo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* ===== TODOS TUS ESTILOS EXISTENTES ===== */
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
        .sidebar-header { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,215,0,0.3); margin-bottom: 15px; }
        .sidebar-header h2 { font-size: 1.4em; color: #ffd966; }
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
        .sidebar-turns { padding: 0 15px 15px; margin-top: 10px; }
        .sidebar-turns h3 { color: #ffd966; margin-bottom: 12px; font-size: 1em; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid rgba(255,215,0,0.3); padding-bottom: 8px; }
        .turnos-side-list { max-height: 250px; overflow-y: auto; }
        .turno-side-item { background: rgba(255,255,255,0.1); border-radius: 12px; padding: 10px; margin-bottom: 8px; border-left: 3px solid #ffd966; cursor: pointer; }
        .turno-side-item:hover { background: rgba(255,215,0,0.2); }
        .turno-side-num { font-weight: bold; color: #ffd966; }
        .turno-side-pac { font-size: 0.7em; color: #e0e7ff; margin-top: 3px; }
        .sidebar-footer { padding: 12px; border-top: 1px solid rgba(255,215,0,0.3); margin-top: auto; }
        .btn-clear-history { width: 100%; background: #dc3545; color: white; border: none; padding: 8px; border-radius: 30px; cursor: pointer; font-weight: bold; }
        .main-content { flex: 1; margin-left: 320px; padding: 20px; }
        .header-hospital { background: white; border-radius: 20px; padding: 15px 25px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); border-left: 5px solid #ffd966; }
        .header-hospital h1 { color: #0b2b5e; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; }
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
        .btn-finalizar-grande { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 8px; margin-top: 20px; border: 3px solid #dc3545; border-radius: 12px; padding: 16px; cursor: pointer; background: white; transition: all 0.2s; width: 100%; }
        .btn-finalizar-grande:hover { background: #fff5f5; transform: scale(1.02); }
        .btn-finalizar-grande .circulo-stop { width: 54px; height: 54px; border: 4px solid #dc3545; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .btn-finalizar-grande .circulo-stop i { font-size: 1.8rem; color: #dc3545; }
        .btn-finalizar-grande span { font-size: 1.1rem; font-weight: 800; color: #dc3545; letter-spacing: 2px; }
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
        .modulos-check-group { display: flex; gap: 15px; flex-wrap: wrap; margin-top: 8px; }
        .modulo-check { display: flex; align-items: center; gap: 8px; background: white; padding: 8px 16px; border-radius: 30px; border: 1px solid #e2e8f0; cursor: pointer; }
        .modulo-check input { width: auto; margin: 0; transform: scale(1.1); }
        .modulo-check.selected { background: #ffd966; border-color: #ffd966; }
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

    <!-- MODAL AGREGAR PERSONA (PACIENTE) -->
    <div id="modalAgregarPersona" class="modal-persona">
        <div class="modal-content">
            <div class="modal-header"><h2><i class="fas fa-user-plus"></i> Registrar Nuevo Paciente</h2><button class="close-modal-persona" onclick="cerrarModalPersona()">&times;</button></div>
            <form id="formRegistrarPersona">
                <div class="form-persona-group"><label><i class="fas fa-id-card"></i> Identificación *</label><input type="text" id="regIdentificacion" placeholder="Ej: 12345678" required></div>
                <div class="form-persona-group"><label><i class="fas fa-user"></i> Primer Nombre *</label><input type="text" id="regPrimerNombre" placeholder="Nombre" required></div>
                <div class="form-persona-group"><label><i class="fas fa-user"></i> Segundo Nombre</label><input type="text" id="regSegundoNombre" placeholder="Segundo nombre (opcional)"></div>
                <div class="form-persona-group"><label><i class="fas fa-user-tag"></i> Primer Apellido *</label><input type="text" id="regPrimerApellido" placeholder="Apellido paterno" required></div>
                <div class="form-persona-group"><label><i class="fas fa-user-tag"></i> Segundo Apellido</label><input type="text" id="regSegundoApellido" placeholder="Apellido materno (opcional)"></div>
                <div class="modal-persona-actions"><button type="button" class="btn-cancelar-persona" onclick="cerrarModalPersona()">Cancelar</button><button type="submit" class="btn-guardar-persona"><i class="fas fa-save"></i> Guardar Paciente</button></div>
            </form>
        </div>
    </div>

    <div class="app-layout">
        <div class="sidebar">
            <div class="sidebar-header"><h2><i class="fas fa-hospital-user"></i> HOSPITAL LOCAL SAN PABLO</h2></div>
            <div class="nav-menu">
                <div class="nav-section"><div class="nav-section-title">INICIO</div><div class="nav-item active" onclick="mostrarSeccion('inicio')"><i class="fas fa-home"></i><span>Inicio</span></div></div>
                <div class="nav-section"><div class="nav-section-title">SERVICIOS</div><div class="nav-item" id="navServicios" onclick="verificarAcceso('servicios')"><i class="fas fa-stethoscope"></i><span>Servicios</span></div></div>
                <div class="nav-section"><div class="nav-section-title">USUARIOS</div><div class="nav-item" id="navUsuarios" onclick="verificarAcceso('usuarios')"><i class="fas fa-users"></i><span>Usuarios</span></div></div>
                <div class="nav-section"><div class="nav-section-title">PERFIL</div><div class="nav-item" id="navPerfil" onclick="verificarAcceso('perfil')"><i class="fas fa-id-card"></i><span>Perfil</span></div></div>
                <div class="nav-section"><div class="nav-section-title">GESTION TURNOS</div><div class="nav-item" id="navAgregarPaciente" onclick="verificarAcceso('agregar_paciente')"><i class="fas fa-user-plus"></i><span>Agregar paciente</span></div><div class="nav-item" id="navAtenderTurnos" onclick="verificarAcceso('atender_turnos')"><i class="fas fa-users"></i><span>Atender Turnos</span></div></div>
                <div class="nav-section"><div class="nav-section-title">REPORTES</div><div class="nav-item" id="navReportes" onclick="verificarAcceso('reportes')"><i class="fas fa-chart-bar"></i><span>Reportes</span></div></div>
            </div>
            <div class="voice-control"><h3><i class="fas fa-volume-up"></i> Control de Voz</h3><div class="voice-buttons"><button class="voice-btn" id="voiceOnBtn" onclick="toggleVoice(true)">Activar</button><button class="voice-btn" id="voiceOffBtn" onclick="toggleVoice(false)">Desactivar</button></div><div id="voiceStatus">✅ Voz activada</div></div>
            <div class="sidebar-turns"><h3><i class="fas fa-clock"></i> Turnos Pendientes</h3><div class="turnos-side-list" id="turnosSideList">No hay turnos</div></div>
            <div class="sidebar-footer"><button class="btn-clear-history" onclick="limpiarHistorial()">Limpiar historial</button></div>
        </div>

        <div class="main-content">
            <div id="seccion-inicio">
                <div class="header-hospital"><h1><i class="fas fa-ticket-alt"></i> Gestión de Turnos</h1><p>Administre los turnos de los pacientes - Llamado en voz alta</p></div>
                <div class="stats-grid">
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-clock"></i></div><div class="stat-info"><h3>Pendientes</h3><div class="value" id="statsPendientes">0</div></div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-bell"></i></div><div class="stat-info"><h3>Llamados</h3><div class="value" id="statsLlamados">0</div></div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3>Atendidos</h3><div class="value" id="statsAtendidos">0</div></div></div>
                    <div class="stat-card"><div class="stat-icon"><i class="fas fa-trash-alt"></i></div><div class="stat-info"><h3>Eliminados</h3><div class="value" id="statsEliminados">0</div></div></div>
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
                        <button class="btn-agregar-servicio" onclick="limpiarFormularioUsuario()"><i class="fas fa-user-plus"></i> Agregar Usuario</button>
                    </div>
                    <div class="form-usuario-container" id="formUsuarioContainer">
                        <h3><i class="fas fa-user-edit"></i> Registrar Usuario</h3>
                        <form id="formRegistrarUsuarioNuevo">
                            <div class="form-usuario-grid">
                                <div class="form-usuario-field"><label>NOMBRES</label><input type="text" id="nombresUsuario" required></div>
                                <div class="form-usuario-field"><label>APELLIDOS</label><input type="text" id="apellidosUsuario" required></div>
                                <div class="form-usuario-field"><label>CÉDULA</label><input type="text" id="cedulaUsuario" value="" required></div>
                                <div class="form-usuario-field"><label>CONTRASEÑA</label><input type="password" id="passwordUsuarioNuevo" value="" required></div>
                                <div class="form-usuario-field"><label>USUARIO / ASESOR</label><input type="text" id="usuarioAsesor"></div>
                                <div class="form-usuario-field"><label>SERVICIO</label><select id="servicioUsuario"><option value="">Seleccionar servicio</option></select></div>
                                <div class="form-usuario-field"><label>MÓDULOS DE ACCESO</label><div class="modulos-check-group" id="modulosCheckGroup"><label class="modulo-check"><input type="checkbox" value="1"> MÓDULO 1</label><label class="modulo-check"><input type="checkbox" value="2"> MÓDULO 2</label><label class="modulo-check"><input type="checkbox" value="3"> MÓDULO 3</label><label class="modulo-check"><input type="checkbox" value="4"> MÓDULO 4</label></div></div>
                                <div class="form-usuario-field"><label>NIVEL ACCESO</label><select id="nivelAccesoUsuario"><option value="admin">Administrador</option><option value="facturador">Facturador</option></select></div>
                            </div>
                            <div class="form-usuario-actions">
                                <button type="button" class="btn-cancelar-usuario-form" onclick="limpiarFormularioUsuario()">Cancelar</button>
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
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-user-plus"></i> Agregar Pacientes</h4><p>Registrar nuevos pacientes</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_agregar_pacientes" value="1"> Sí</label><label><input type="radio" name="permiso_agregar_pacientes" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-users"></i> Usuarios</h4><p>Gestionar usuarios del sistema</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_usuarios" value="1"> Sí</label><label><input type="radio" name="permiso_usuarios" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-stethoscope"></i> Servicios</h4><p>Gestionar servicios médicos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_servicios" value="1"> Sí</label><label><input type="radio" name="permiso_servicios" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-chart-bar"></i> Reportes</h4><p>Ver reportes de turnos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_reportes" value="1"> Sí</label><label><input type="radio" name="permiso_reportes" value="0" checked> No</label></div></div>
                        <div class="permiso-item"><div class="permiso-info"><h4><i class="fas fa-users"></i> Atender Turnos</h4><p>Gestionar y atender turnos</p></div><div class="permiso-buttons"><label><input type="radio" name="permiso_atender_turnos" value="1"> Sí</label><label><input type="radio" name="permiso_atender_turnos" value="0" checked> No</label></div></div>
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
                        <tbody id="reporteBody"><tr><td colspan="8">Seleccione fechas y genere reporte</td></tr></tbody>
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
                            <button class="btn-finalizar-grande" id="btnFinalizarTurno" onclick="finalizarTurnoActual()" disabled>
                                <div class="circulo-stop"><i class="fas fa-stop"></i></div>
                                <span>FINALIZAR</span>
                            </button>
                        </div>
                        <div class="gestionar-derecha">
                            <div class="datos-usuario-titulo">Datos del Usuario</div>
                            <div class="usuario-avatar"><div class="avatar-circulo"><i class="fas fa-user"></i></div></div>
                            <div id="usuarioAutenticadoNombre" style="text-align: center; margin-top: 5px; font-weight: 600; color: #0b2b5e; font-size: 1rem;"></div>
                            <div class="selector-servicio"><label>Servicio:</label><select id="gtServicioFiltro" onchange="filtrarPorServicio()"><option value="">— Todos los servicios —</option></select></div>
                            <div class="modulo-selector-wrapper" id="moduloSelectorWrapper">
                                <button type="button" class="modulo-btn-click" id="moduloBtnClick" onclick="toggleModuloDropdown()"><span class="modulo-nombre-text" id="moduloBtnTexto">MÓDULO 1</span><i class="fas fa-chevron-down modulo-chevron"></i></button>
                                <div class="modulo-dropdown" id="moduloDropdown">
                                    <div class="modulo-option active-mod" data-mod="1" onclick="seleccionarModulo(1)"><div class="mod-icon">M1</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 1</span><span class="mod-info-count" id="countMod1">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="2" onclick="seleccionarModulo(2)"><div class="mod-icon">M2</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 2</span><span class="mod-info-count" id="countMod2">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="3" onclick="seleccionarModulo(3)"><div class="mod-icon">M3</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 3</span><span class="mod-info-count" id="countMod3">Cargando...</span></div></div>
                                    <div class="modulo-option" data-mod="4" onclick="seleccionarModulo(4)"><div class="mod-icon">M4</div><div class="mod-info-text"><span class="mod-info-name">MÓDULO 4</span><span class="mod-info-count" id="countMod4">Cargando...</span></div></div>
                                </div>
                            </div>
                            <div class="modulo-info-box"><div class="modulo-nombre" id="gtModuloNombre">MÓDULO 1</div><div class="modulo-contadores"><span class="contador-badge activos"><i class="fas fa-clock"></i> ACTIVOS: <span id="gtContActivos">0</span></span><span class="contador-badge atendidos"><i class="fas fa-check"></i> ATENDIDOS: <span id="gtContAtendidos">0</span></span><span class="contador-badge totales"><i class="fas fa-list"></i> TOTALES: <span id="gtContTotales">0</span></span></div></div>
                            <button class="btn-ver-turnos-modal" onclick="toggleListaTurnos()"><i class="fas fa-eye"></i> Ver Turnos</button>
                            <div class="turnos-espera-lista" id="turnos-espera-lista"><div id="turnosEsperaBody"></div></div>
                        </div>
                    </div>
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

    <script>
        // ==================== RECIBIR PERMISOS DESDE PHP ====================
        const userPermissions = @json($permisos ?? []);
        
        // ==================== VARIABLES GLOBALES ====================
        let currentUser = null;
        let selectedSpecialty = null, selectedWindow = null, personaActual = null, voiceEnabled = true;
        let turnCounters = {};
        let reporteFiltrado = [];
        let serviciosDB = [];
        let cedulaActual = '';
        let turnoActivoModal = null;
        let listaTurnosVisible = false;
        let moduloSeleccionado = 1;
        let usuariosDB = [];
        let editandoUsuarioId = null;
        let cicloLlamadaInterval = null;
        let turnoEnCiclo = null;

        // ==================== MOSTRAR NOMBRE DEL USUARIO EN ATENDER TURNOS ====================
        const nombreUsuarioAutenticado = @json(auth()->user()->name ?? 'Usuario');
        
        // ==================== MODAL SERVICIO ====================
        function abrirModalServicio() {
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

        // ==================== FUNCIONES DE PERMISOS ====================
        function tienePermiso(moduloId) {
            const moduloMap = { 'servicios': 'servicios', 'usuarios': 'usuarios', 'agregar_paciente': 'agregar_paciente', 'atender_turnos': 'atender_turnos', 'reportes': 'reportes', 'perfil': 'perfil' };
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
            if (tienePermiso(modulo)) {
                if (modulo === 'agregar_paciente') { abrirModalPersona(); }
                else if (modulo === 'atender_turnos') { 
                    mostrarSeccion('atender');
                    const nombreElement = document.getElementById('usuarioAutenticadoNombre');
                    if (nombreElement) nombreElement.textContent = nombreUsuarioAutenticado;
                    if (typeof turnoActivoModal === 'undefined' || turnoActivoModal === null) {
                        limpiarPanelIzquierdo();
                        actualizarContadoresModal();
                        actualizarConteoDropdown();
                        renderizarListaTurnos();
                        poblarSelectServiciosModal();
                    }
                }
                else { mostrarSeccion(modulo); }
            } else { mostrarAccesoDenegado(); }
        }

        // ==================== FUNCIONES ORIGINALES ====================
        function abrirModalPersona() { 
            const form = document.getElementById('formRegistrarPersona');
            if (form) form.reset();
            document.getElementById('regIdentificacion').value = '';
            document.getElementById('regPrimerNombre').value = '';
            document.getElementById('regSegundoNombre').value = '';
            document.getElementById('regPrimerApellido').value = '';
            document.getElementById('regSegundoApellido').value = '';
            document.getElementById('modalAgregarPersona').style.display = 'flex';
        }
        
        function mostrarSeccion(sec) {
            document.getElementById('seccion-inicio').style.display = 'none';
            document.getElementById('seccion-servicios').style.display = 'none';
            document.getElementById('seccion-usuarios').style.display = 'none';
            document.getElementById('seccion-reportes').style.display = 'none';
            document.getElementById('seccion-perfil').style.display = 'none';
            document.getElementById('seccion-atender').style.display = 'none';
            document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
            if(sec === 'inicio') { document.getElementById('seccion-inicio').style.display = 'block'; document.querySelectorAll('.nav-item')[0].classList.add('active'); actualizarVista(); }
            else if(sec === 'servicios') { document.getElementById('seccion-servicios').style.display = 'block'; document.getElementById('navServicios').classList.add('active'); cargarServiciosDB(); }
            else if(sec === 'usuarios') { document.getElementById('seccion-usuarios').style.display = 'block'; document.getElementById('navUsuarios').classList.add('active'); cargarUsuarios(); poblarSelectServiciosUsuario(); }
            else if(sec === 'perfil') { document.getElementById('seccion-perfil').style.display = 'block'; document.getElementById('navPerfil').classList.add('active'); cargarPermisosDesdeLocalStorage(); }
            else if(sec === 'reportes') { document.getElementById('seccion-reportes').style.display = 'block'; document.getElementById('navReportes').classList.add('active'); generarReporte(); }
            else if(sec === 'atender') { document.getElementById('seccion-atender').style.display = 'block'; document.getElementById('navAtenderTurnos').classList.add('active'); }
        }

        // ==================== PERFIL ====================
        function cargarPermisosDesdeLocalStorage() {
            const permisosGuardados = localStorage.getItem('permisos_sistema');
            if(permisosGuardados) {
                const permisos = JSON.parse(permisosGuardados);
                const set = (name, val) => { const el = document.querySelector(`input[name="${name}"][value="${val ? '1' : '0'}"]`); if(el) el.checked = true; };
                set('permiso_login', permisos.login); set('permiso_agregar_pacientes', permisos.agregar_paciente); set('permiso_usuarios', permisos.usuarios); set('permiso_servicios', permisos.servicios); set('permiso_reportes', permisos.reportes); set('permiso_atender_turnos', permisos.atender_turnos); set('permiso_perfil', permisos.perfil);
            }
        }

        function actualizarPermisos() { showNotification('Permisos cargados desde el sistema', 'info'); }

        function guardarPermisos() {
            const username = document.getElementById('buscarUsuarioPermisos').value.trim();
            if(username === '') { showNotification('⚠️ Primero busque un usuario para modificar sus permisos', 'warning'); return; }
            const permisos = {
                login: document.querySelector('input[name="permiso_login"]:checked').value === '1' ? 1 : 0,
                agregar_paciente: document.querySelector('input[name="permiso_agregar_pacientes"]:checked').value === '1' ? 1 : 0,
                usuarios: document.querySelector('input[name="permiso_usuarios"]:checked').value === '1' ? 1 : 0,
                servicios: document.querySelector('input[name="permiso_servicios"]:checked').value === '1' ? 1 : 0,
                reportes: document.querySelector('input[name="permiso_reportes"]:checked').value === '1' ? 1 : 0,
                atender_turnos: document.querySelector('input[name="permiso_atender_turnos"]:checked').value === '1' ? 1 : 0,
                perfil: document.querySelector('input[name="permiso_perfil"]:checked').value === '1' ? 1 : 0
            };
            showNotification(`💾 Guardando permisos para usuario: ${username}...`, 'info');
            fetch('/guardar-permisos-usuario', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ username, permisos }) })
            .then(r => r.json()).then(data => { if(data.success) { showNotification(`✅ Permisos guardados correctamente para "${username}"`, 'success'); localStorage.setItem('permisos_sistema', JSON.stringify(permisos)); } else { showNotification(`❌ Error: ${data.message}`, 'error'); } })
            .catch(() => showNotification('❌ Error de conexión con el servidor', 'error'));
        }

        function filtrarPermisos() {
            const busqueda = document.getElementById('buscarUsuarioPermisos').value.trim();
            if(busqueda === '') { showNotification('Ingrese un nombre de usuario para buscar', 'info'); return; }
            showNotification(`🔍 Buscando usuario: ${busqueda}...`, 'info');
            fetch('/buscar-usuario-permisos', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ username: busqueda }) })
            .then(r => r.json()).then(data => {
                if(data.success) {
                    const p = data.usuario.permisos;
                    const set = (name, val) => { const el = document.querySelector(`input[name="${name}"][value="${val == 1 ? '1' : '0'}"]`); if(el) el.checked = true; };
                    set('permiso_login', p.login); set('permiso_agregar_pacientes', p.agregar_paciente); set('permiso_usuarios', p.usuarios); set('permiso_servicios', p.servicios); set('permiso_reportes', p.reportes); set('permiso_atender_turnos', p.atender_turnos); set('permiso_perfil', p.perfil);
                    showNotification(`✅ Usuario encontrado: ${data.usuario.name || data.usuario.username}`, 'success');
                } else { showNotification(`❌ Usuario "${busqueda}" no encontrado`, 'error'); }
            }).catch(() => showNotification('❌ Error de conexión con el servidor', 'error'));
        }

        // ==================== MÓDULOS ====================
        function getNombreModulo(modulo) { return `MÓDULO ${modulo}`; }
        function toggleModuloDropdown() { const btn = document.getElementById('moduloBtnClick'); const dropdown = document.getElementById('moduloDropdown'); btn.classList.toggle('open'); dropdown.classList.toggle('open'); actualizarConteoDropdown(); }
        function actualizarConteoDropdown() { const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); const servicioFiltro = document.getElementById('gtServicioFiltro') ? document.getElementById('gtServicioFiltro').value : ''; [1,2,3,4].forEach(mod => { let lista = servicioFiltro ? turnos.filter(t => String(t.especialidad) === String(servicioFiltro)) : turnos.filter(t => parseInt(t.ventanilla) === mod); const activos = lista.filter(t => t.estado === 'pendiente' || t.estado === 'llamado').length; const atendidos = lista.filter(t => t.estado === 'atendido').length; const el = document.getElementById(`countMod${mod}`); if(el) el.textContent = `${activos} activos · ${atendidos} atendidos`; }); }
        function detenerCicloLlamada() { if(cicloLlamadaInterval) { clearInterval(cicloLlamadaInterval); cicloLlamadaInterval = null; turnoEnCiclo = null; if(window.speechSynthesis) window.speechSynthesis.cancel(); } }
        function iniciarCicloLlamada(numeroTurno, moduloTexto) { detenerCicloLlamada(); turnoEnCiclo = numeroTurno; const hablar = () => { if(voiceEnabled && window.speechSynthesis) { const u = new SpeechSynthesisUtterance(`Turno ${numeroTurno}, por favor acérquese al ${moduloTexto}`); u.lang = 'es-ES'; u.rate = 0.9; window.speechSynthesis.speak(u); } }; hablar(); let rep = 0; cicloLlamadaInterval = setInterval(() => { rep++; if(rep >= 24) detenerCicloLlamada(); else hablar(); }, 5000); }
        function seleccionarModulo(num) { moduloSeleccionado = num; document.getElementById('moduloBtnTexto').textContent = getNombreModulo(num); document.getElementById('gtModuloNombre').textContent = getNombreModulo(num); document.querySelectorAll('.modulo-option').forEach(o => o.classList.toggle('active-mod', parseInt(o.getAttribute('data-mod')) === num)); document.getElementById('moduloBtnClick').classList.remove('open'); document.getElementById('moduloDropdown').classList.remove('open'); if(turnoActivoModal && parseInt(turnoActivoModal.ventanilla) !== num) limpiarPanelIzquierdo(); actualizarContadoresModal(); renderizarListaTurnos(); showNotification(`📌 ${getNombreModulo(num)} seleccionado`, 'success'); }
        document.addEventListener('click', function(e) { const wrapper = document.getElementById('moduloSelectorWrapper'); if(wrapper && !wrapper.contains(e.target)) { document.getElementById('moduloBtnClick').classList.remove('open'); document.getElementById('moduloDropdown').classList.remove('open'); } });

        // ==================== PERSONA ====================
        function cerrarModalPersona() { document.getElementById('modalAgregarPersona').style.display = 'none'; document.getElementById('formRegistrarPersona').reset(); }

        async function registrarPersona(event) {
            event.preventDefault();
            const identificacion = document.getElementById('regIdentificacion').value.trim();
            const primer_nombre = document.getElementById('regPrimerNombre').value.trim();
            const segundo_nombre = document.getElementById('regSegundoNombre').value.trim();
            const primer_apellido = document.getElementById('regPrimerApellido').value.trim();
            const segundo_apellido = document.getElementById('regSegundoApellido').value.trim();
            if(!identificacion || !primer_nombre || !primer_apellido) { showNotification('⚠️ Complete los campos obligatorios', 'error'); return; }
            if(!/^\d+$/.test(identificacion)) { showNotification('⚠️ La identificación solo debe contener números', 'error'); return; }
            const btnGuardar = document.querySelector('#formRegistrarPersona button[type="submit"]');
            const textoOriginal = btnGuardar.innerHTML;
            btnGuardar.disabled = true; btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            try {
                const response = await fetch('/personas', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }, body: JSON.stringify({ identificacion, primer_nombre, segundo_nombre: segundo_nombre || null, primer_apellido, segundo_apellido: segundo_apellido || null }) });
                const data = await response.json();
                if(response.ok && data.success) { showNotification('✅ Paciente registrado exitosamente', 'success'); cerrarModalPersona(); document.getElementById('cedula').value = identificacion; await buscarPersona(); }
                else { let msg = data.message || 'Error al registrar el paciente'; if(data.errors) msg = Object.values(data.errors).flat().join(', '); showNotification('❌ ' + msg, 'error'); }
            } catch(e) { showNotification('❌ Error de conexión con el servidor', 'error'); }
            finally { btnGuardar.disabled = false; btnGuardar.innerHTML = textoOriginal; }
        }

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

        function abrirModalPersonaYPrecargar() { abrirModalPersona(); }

        // ==================== SERVICIOS DB ====================
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
                    poblarSelectServiciosModal();
                    poblarSelectServiciosUsuario();
                }
            } catch(error) { console.error(error); }
        }

        function poblarSelectServiciosUsuario() { const sel = document.getElementById('servicioUsuario'); if(!sel) return; sel.innerHTML = '<option value="">Seleccionar servicio</option>'; serviciosDB.filter(s=>s.activo).forEach(s=>{ const opt=document.createElement('option'); opt.value=s.id_servicio; opt.textContent=s.nombre_servicio; sel.appendChild(opt); }); }
        function poblarSelectServiciosModal() { const sel = document.getElementById('gtServicioFiltro'); if(sel) { sel.innerHTML = '<option value="">— Todos los servicios —</option>'; serviciosDB.filter(s=>s.activo).forEach(s=>{ const opt=document.createElement('option'); opt.value=s.id_servicio; opt.textContent=s.nombre_servicio; sel.appendChild(opt); }); } }

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

        // ==================== ATENDER SECCIÓN - FUNCIONES CORREGIDAS ====================
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
            const btnFinalizar = document.getElementById('btnFinalizarTurno');
            if(btnFinalizar) btnFinalizar.disabled = true;
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
            const btnFinalizar = document.getElementById('btnFinalizarTurno');
            if(btnFinalizar) btnFinalizar.disabled = false;
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
        
        // ========== FUNCIÓN CORREGIDA: Solo muestra turnos no atendidos ==========
        function renderizarListaTurnos() { 
            let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const servicioFiltro = document.getElementById('gtServicioFiltro') ? document.getElementById('gtServicioFiltro').value : ''; 
            
            // FILTRO IMPORTANTE: SOLO turnos que NO están atendidos (pendiente o llamado)
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
        
        // ========== FUNCIÓN CORREGIDA: Atender turno ==========
        function atenderTurno(num) { 
            let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const idx = turnos.findIndex(t => t.numero === num); 
            if(idx !== -1) { 
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
                // REFRESCAR LISTA INMEDIATAMENTE
                renderizarListaTurnos();
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
                iniciarCicloLlamada(num, getNombreModulo(moduloSeleccionado)); 
                actualizarVista(); 
                actualizarContadoresModal(); 
                actualizarConteoDropdown(); 
                renderizarListaTurnos(); 
                showNotification(`🔔 Turno ${num} llamado al ${getNombreModulo(moduloSeleccionado)}`, 'success'); 
            } else if(idx !== -1) { 
                showNotification(`⚠️ El turno ${num} ya fue atendido`, 'warning');
            } else {
                showNotification(`⚠️ No se encontró el turno ${num}`, 'warning');
            }
        }
        
        function finalizarTurnoActual() { 
            if(!turnoActivoModal) { 
                showNotification('⚠️ Seleccione un turno de la lista', 'warning'); 
                return; 
            } 
            atenderTurno(turnoActivoModal.numero); 
        }

        // ==================== REPORTES ====================
        function generarReporte() { 
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const fechaInicio = document.getElementById('fechaInicio').value; 
            const fechaFin = document.getElementById('fechaFin').value; 
            let datos = turnos.map(t => { 
                let s = serviciosDB.find(s => s.id_servicio == t.especialidad) || { nombre_servicio: t.nombreEspecialidad || 'MEDICINA' }; 
                let estado = t.estado === 'pendiente' || t.estado === 'llamado' ? 'ACTIVO' : t.estado === 'atendido' ? 'FINALIZADO' : 'CANCELADO'; 
                return { estado, turno: t.numero, servicio: s.nombre_servicio, documento: t.identificacion || '---', nombre: t.nombre_persona || 'Paciente', ingreso: t.timestamp ? formatearFechaLocal(t.timestamp) : '', salida: t.salida ? formatearFechaLocal(t.salida) : '', fechaIngreso: t.timestamp ? t.timestamp.split('T')[0] : '' }; 
            }); 
            if(fechaInicio && fechaFin) datos = datos.filter(d => d.fechaIngreso >= fechaInicio && d.fechaIngreso <= fechaFin); 
            reporteFiltrado = datos; 
            const tbody = document.getElementById('reporteBody'); 
            if(!reporteFiltrado.length) { 
                tbody.innerHTML = '<tr><td colspan="8">No hay datos</td>' + '</tr>'; 
                return; 
            } 
            tbody.innerHTML = reporteFiltrado.map(d => `<tr><td class="estado-${d.estado === 'ACTIVO' ? 'activo' : 'finalizado'}">${d.estado}</td><td><strong>${d.turno}</strong></td><td>${d.servicio}</td><td>${d.documento}</td><td>${d.nombre}</td><td>${d.ingreso}</td><td>${d.salida}</td><td>---</td></tr>`).join(''); 
        }
        
        function buscarEnReporte() { const busqueda = prompt('Buscar:'); if(!busqueda) return; const resultados = reporteFiltrado.filter(d => d.turno.includes(busqueda) || d.nombre.includes(busqueda) || d.documento.includes(busqueda)); const tbody = document.getElementById('reporteBody'); if(!resultados.length) { tbody.innerHTML = '<tr><td colspan="8">No se encontraron</td>' + '</tr>'; return; } tbody.innerHTML = resultados.map(d => `<tr><td class="estado-${d.estado === 'ACTIVO' ? 'activo' : 'finalizado'}">${d.estado}</td><td>${d.turno}</td><td>${d.servicio}</td><td>${d.documento}</td><td>${d.nombre}</td><td>${d.ingreso}</td><td>${d.salida}</td><td>---</td></tr>`).join(''); showNotification(`Encontrados ${resultados.length}`, 'success'); }
        function descargarReporte() { if(!reporteFiltrado.length) { showNotification('No hay datos', 'error'); return; } let csv = "ESTADO,TURNO,SERVICIO,DOCUMENTO,NOMBRE,INGRESO,SALIDA\n"; reporteFiltrado.forEach(d => csv += `"${d.estado}","${d.turno}","${d.servicio}","${d.documento}","${d.nombre}","${d.ingreso}","${d.salida}"\n`); const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv' }); const link = document.createElement('a'); link.href = URL.createObjectURL(blob); link.download = `reporte_${new Date().toISOString().split('T')[0]}.csv`; link.click(); showNotification('Reporte descargado', 'success'); }

        // ==================== UTILIDADES ====================
        function toggleVoice(e) { voiceEnabled = e; document.getElementById('voiceStatus').innerHTML = e ? '✅ Voz activada' : '🔇 Voz desactivada'; showNotification(e ? 'Voz activada' : 'Voz desactivada', e ? 'success' : 'info'); }
        function showNotification(m, t) { const n = document.getElementById('notification'); n.textContent = m; n.style.background = t === 'success' ? '#28a745' : t === 'error' ? '#dc3545' : '#ffc107'; n.style.display = 'block'; setTimeout(() => n.style.display = 'none', 3000); }
        function cerrarHistorial() { document.getElementById('historyModal').style.display = 'none'; }
        function limpiarHistorial() { if(confirm('¿Limpiar historial? Se conservan turnos activos')) { let turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); turnos = turnos.filter(t => t.estado === 'pendiente' || t.estado === 'llamado'); localStorage.setItem('turnos', JSON.stringify(turnos)); actualizarVista(); cerrarHistorial(); if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte(); } }
        function actualizarVista() { 
            const turnos = JSON.parse(localStorage.getItem('turnos') || '[]'); 
            const pend = turnos.filter(t => t.estado === 'pendiente'); 
            const llam = turnos.filter(t => t.estado === 'llamado'); 
            const ate = turnos.filter(t => t.estado === 'atendido'); 
            const elim = turnos.filter(t => t.estado === 'eliminado'); 
            document.getElementById('statsPendientes').innerHTML = pend.length; 
            document.getElementById('statsLlamados').innerHTML = llam.length; 
            document.getElementById('statsAtendidos').innerHTML = ate.length; 
            document.getElementById('statsEliminados').innerHTML = elim.length; 
            const side = document.getElementById('turnosSideList'); 
            if(!pend.length && !llam.length) side.innerHTML = '<div>No hay turnos</div>'; 
            else { 
                const todos = [...llam, ...pend]; 
                const unicos = new Map(); 
                todos.forEach(t => { if(!unicos.has(t.numero)) unicos.set(t.numero, t); }); 
                side.innerHTML = Array.from(unicos.values()).map(t => `<div class="turno-side-item"><div class="turno-side-num">${t.numero}</div><div class="turno-side-pac">${t.nombre_persona || 'Paciente'}</div><div class="turno-side-pac">${getNombreModulo(t.ventanilla)}</div></div>`).join(''); 
            } 
        }
        function formatearFechaLocal(f) { if(!f) return ''; const d = new Date(f); return `${d.getDate()}/${d.getMonth()+1}/${d.getFullYear()} ${d.getHours()}:${d.getMinutes()}`; }
        function loadCounters() { const s = localStorage.getItem('turnCounters'); if(s) turnCounters = JSON.parse(s); else saveCounters(); }
        function saveCounters() { localStorage.setItem('turnCounters', JSON.stringify(turnCounters)); }
        function cerrarTurnoGenerado() { document.getElementById('turnoGeneradoModal').style.display = 'none'; }

        // ==================== USUARIOS ====================
        function cargarUsuarios() { let stored = localStorage.getItem('usuarios_sistema_v2'); if(stored) usuariosDB = JSON.parse(stored); else { usuariosDB = []; localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB)); } renderizarUsuarios(); }
        function renderizarUsuarios() { const cont = document.getElementById('usuariosGridContainer'); if(!cont) return; if(usuariosDB.length === 0) { cont.innerHTML = '<div style="text-align:center;padding:40px;">No hay usuarios registrados</div>'; return; } cont.innerHTML = usuariosDB.map((u,idx) => { const modulosHtml = u.modulos && u.modulos.length ? `<div class="modulos-asignados">${u.modulos.map(m => `<span class="modulo-badge">MÓDULO ${m}</span>`).join('')}</div>` : ''; return `<div class="usuario-card"><h4><i class="fas fa-user-circle"></i> ${u.nombres} ${u.apellidos}</h4><p><i class="fas fa-id-card"></i> ${u.cedula}</p><p><i class="fas fa-stethoscope"></i> Servicio: ${u.servicio_nombre || u.servicio || 'N/A'}</p><p><i class="fas fa-chart-line"></i> Nivel: ${u.nivel_acceso === 'admin' ? 'Administrador' : u.nivel_acceso === 'facturador' ? 'Facturador' : 'N/A'}</p>${modulosHtml}<div><button class="btn-editar-usuario-card" onclick="editarUsuario(${idx})"><i class="fas fa-edit"></i> Editar</button><button class="btn-eliminar-usuario-card" onclick="eliminarUsuario(${idx})"><i class="fas fa-trash"></i> Eliminar</button></div></div>`; }).join(''); }
        function limpiarFormularioUsuario() { document.getElementById('nombresUsuario').value = ''; document.getElementById('apellidosUsuario').value = ''; document.getElementById('cedulaUsuario').value = ''; document.getElementById('passwordUsuarioNuevo').value = ''; document.getElementById('usuarioAsesor').value = ''; document.getElementById('servicioUsuario').value = ''; document.getElementById('nivelAccesoUsuario').value = 'admin'; document.querySelectorAll('#modulosCheckGroup input').forEach(cb => cb.checked = false); editandoUsuarioId = null; document.getElementById('btnGuardarUsuario').style.display = 'block'; document.getElementById('btnActualizarUsuario').style.display = 'none'; document.getElementById('formUsuarioContainer').querySelector('h3').innerHTML = '<i class="fas fa-user-edit"></i> Registrar Usuario'; }
        function registrarUsuarioNuevo(event) { event.preventDefault(); const nombres = document.getElementById('nombresUsuario').value.trim(); const apellidos = document.getElementById('apellidosUsuario').value.trim(); const cedula = document.getElementById('cedulaUsuario').value.trim(); const password = document.getElementById('passwordUsuarioNuevo').value.trim(); const usuarioAsesor = document.getElementById('usuarioAsesor').value.trim(); const servicioId = document.getElementById('servicioUsuario').value; const nivelAcceso = document.getElementById('nivelAccesoUsuario').value; const modulosSeleccionados = Array.from(document.querySelectorAll('#modulosCheckGroup input:checked')).map(cb => cb.value); if(!nombres || !apellidos || !cedula || !password) { showNotification('Complete los campos obligatorios', 'error'); return; } const servicioObj = serviciosDB.find(s => s.id_servicio == servicioId); const nuevoUsuario = { id: Date.now(), nombres, apellidos, cedula, password, usuario_asesor: usuarioAsesor, servicio: servicioId, servicio_nombre: servicioObj ? servicioObj.nombre_servicio : '', nivel_acceso: nivelAcceso, modulos: modulosSeleccionados }; if(editandoUsuarioId !== null) { usuariosDB[editandoUsuarioId] = nuevoUsuario; showNotification('Usuario actualizado correctamente', 'success'); } else { usuariosDB.push(nuevoUsuario); showNotification('Usuario registrado correctamente', 'success'); } localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB)); renderizarUsuarios(); limpiarFormularioUsuario(); }
        function editarUsuario(idx) { const u = usuariosDB[idx]; if(!u) return; document.getElementById('nombresUsuario').value = u.nombres || ''; document.getElementById('apellidosUsuario').value = u.apellidos || ''; document.getElementById('cedulaUsuario').value = u.cedula || ''; document.getElementById('passwordUsuarioNuevo').value = u.password || ''; document.getElementById('usuarioAsesor').value = u.usuario_asesor || ''; document.getElementById('servicioUsuario').value = u.servicio || ''; document.getElementById('nivelAccesoUsuario').value = u.nivel_acceso || 'admin'; document.querySelectorAll('#modulosCheckGroup input').forEach(cb => { cb.checked = u.modulos && u.modulos.includes(cb.value); }); editandoUsuarioId = idx; document.getElementById('btnGuardarUsuario').style.display = 'none'; document.getElementById('btnActualizarUsuario').style.display = 'block'; document.getElementById('formUsuarioContainer').querySelector('h3').innerHTML = '<i class="fas fa-edit"></i> Actualizar Cliente'; document.getElementById('formUsuarioContainer').scrollIntoView({ behavior: 'smooth' }); }
        function actualizarUsuario() { if(editandoUsuarioId !== null) registrarUsuarioNuevo(new Event('submit')); }
        function eliminarUsuario(idx) { if(confirm('¿Eliminar este usuario?')) { usuariosDB.splice(idx,1); localStorage.setItem('usuarios_sistema_v2', JSON.stringify(usuariosDB)); renderizarUsuarios(); showNotification('Usuario eliminado', 'success'); if(editandoUsuarioId === idx) limpiarFormularioUsuario(); } }

        // ==================== EVENT LISTENERS ====================
        document.getElementById('cedula').addEventListener('keypress', e => { if(e.key === 'Enter') buscarPersona(); });
        document.getElementById('btnBuscar').addEventListener('click', buscarPersona);
        document.getElementById('generateBtn').addEventListener('click', async () => {
            if(!selectedSpecialty || !personaActual) { showNotification('Seleccione especialidad', 'warning'); return; }
            const btn = document.getElementById('generateBtn');
            btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
            try {
                const servicioObj = serviciosDB.find(s => s.id_servicio == selectedSpecialty);
                const prefix = servicioObj ? servicioObj.nombre_servicio.substring(0, 3).toUpperCase() : 'MED';
                if(!turnCounters[selectedSpecialty]) turnCounters[selectedSpecialty] = 0;
                turnCounters[selectedSpecialty]++;
                if(turnCounters[selectedSpecialty] > 99) turnCounters[selectedSpecialty] = 1;
                const turnoCompleto = `${prefix}-${String(turnCounters[selectedSpecialty]).padStart(2,'0')}`;
                const nombreCompleto = `${personaActual.primer_nombre} ${personaActual.segundo_nombre || ''} ${personaActual.primer_apellido} ${personaActual.segundo_apellido || ''}`.trim();
                let ventanillaAsignada = 1;
                if(servicioObj) { const n = servicioObj.nombre_servicio.toLowerCase(); if(n.includes('odontologia') || n.includes('odontología')) ventanillaAsignada = 2; else if(n.includes('laboratorio')) ventanillaAsignada = 3; else if(n.includes('rayos') || n.includes('radiologia')) ventanillaAsignada = 4; }
                const turno = { id: Date.now() + '-' + Math.random().toString(36).substr(2,9), numero: turnoCompleto, persona_id: personaActual.id, identificacion: personaActual.identificacion, nombre_persona: nombreCompleto, especialidad: selectedSpecialty, nombreEspecialidad: servicioObj ? servicioObj.nombre_servicio : selectedSpecialty, ventanilla: ventanillaAsignada, timestamp: new Date().toISOString(), estado: 'pendiente' };
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
                document.querySelectorAll('.specialty-card').forEach(c => { c.classList.remove('selected'); c.classList.add('disabled'); });
                selectedSpecialty = null;
                btn.disabled = true;
                actualizarVista();
                if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte();
            } catch(error) { showNotification('Error al generar turno', 'error'); }
            finally { btn.disabled = false; btn.innerHTML = '<i class="fas fa-ticket-alt"></i> Generar Turno'; }
        });

        window.addEventListener('storage', () => { actualizarVista(); if(document.getElementById('seccion-reportes').style.display !== 'none') generarReporte(); });
        setInterval(actualizarVista, 1000);
        document.getElementById('formRegistrarPersona').addEventListener('submit', registrarPersona);
        const nuevoFormUsuario = document.getElementById('formRegistrarUsuarioNuevo');
        if(nuevoFormUsuario) nuevoFormUsuario.addEventListener('submit', registrarUsuarioNuevo);

        // ==================== INICIALIZACIÓN ====================
        cargarServiciosDB();
        loadCounters();
        actualizarVista();
        toggleVoice(true);
        cargarUsuarios();
        mostrarSeccion('inicio');
        
        setTimeout(() => {
            if(document.getElementById('gtServicioFiltro')) {
                poblarSelectServiciosModal();
            }
        }, 500);
    </script>
</body>
</html>