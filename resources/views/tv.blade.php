<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pantalla de Turnos - Hospital San Pablo</title>

<style>
/* AJUSTE PARA PANTALLA COMPLETA */
html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow: hidden; /* Evita scrolls innecesarios en la TV */
    background: #e9eef3;
    font-family: 'Segoe UI', Arial, sans-serif;
}

/* CONTENEDOR GENERAL AL 100% */
.container {
    display: flex;
    width: 100vw; 
    height: 100vh;
    margin: 0;
    padding: 0;
}

/* PANEL IZQUIERDO - Reducido para correr la línea hacia la izquierda */
.left {
    flex: 1.1;
    background: #ffffff;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-sizing: border-box;
}

/* PANEL DERECHO - Aumentado */
.right {
    flex: 1.3;
    background: #f7f7f7;
    padding: 15px;
    border-left: 3px solid #1aa39a;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
}

.header {
    background: linear-gradient(135deg, #7FFFD4, #0e7a73);
    color: white;
    padding: 15px 25px; /* Ajuste para el logo */
    font-size: 32px;
    font-weight: bold;
    text-align: center;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px; /* Espacio entre logo y texto */
}

/* ESTILO PARA EL LOGO */
.header img {
    height: 60px; /* Tamaño del logo */
    width: auto;
    border-radius: 8px;
    background: white; /* Fondo blanco para que resalte el logo verde */
    padding: 5px;
}

.screen-box {
    background: linear-gradient(145deg, #f8fafc, #eef2f8);
    border-radius: 20px;
    padding: 40px;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    border: 1px solid rgba(26,163,154,0.2);
}

.attention-title {
    background: linear-gradient(135deg, #7FFFD4, #0e7a73);
    display: inline-block;
    padding: 15px 40px;
    border-radius: 50px;
    margin-bottom: 25px;
    align-self: center;
}

.attention-title h2 {
    color: white;
    font-size: 2.2em;
    margin: 0;
}

.footer {
    display: flex;
    justify-content: space-between;
    font-size: 16px;
    color: #555;
    padding: 15px;
    background: #f0f2f5;
    border-radius: 10px;
    margin-top: 10px;
}

.turn-header {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
    background: linear-gradient(135deg, #1aa39a, #0e7a73);
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    font-size: 22px;
}

.turn-list {
    flex-grow: 1;
    overflow-y: auto;
}

.turn-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    margin-bottom: 10px;
    background: white;
    border-left: 8px solid #1aa39a;
    font-size: 24px;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.turn-row.parpadeando {
    animation: parpadeo 0.8s ease-in-out infinite;
    background: #fff3cd;
    border-left: 8px solid #ffc107;
    box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
}

@keyframes parpadeo {
    0% { background: #fff3cd; transform: scale(1); }
    50% { background: #ffe69b; transform: scale(1.01); box-shadow: 0 0 25px rgba(255, 193, 7, 0.8); }
    100% { background: #fff3cd; transform: scale(1); }
}

.turn-patient-name {
    font-size: 18px;
    flex: 1;
    text-align: center;
}
</style>
</head>
<body>

<div class="container">
    <div class="left">
        <div>
            <div class="header">
                <img src="images/logo.jpg" alt="Logo HSP">
                BIENVENIDO A HOSPITAL SAN PABLO
            </div>
            
            <div class="screen-box">
                <div class="attention-title">
                    <h2>📢 ATENCIÓN PACIENTES</h2>
                </div>
                <div class="welcome-message" style="font-size: 1.5em; color: #0e7a73; font-weight: 600;">
                    ✨ Por favor, espere a ser llamado ✨
                </div>
                <div class="register" style="background:#dff3f1; padding:20px; border-radius:12px; color:#0e7a73; font-weight:bold; border-left:4px solid #1aa39a; font-size: 1.2em; margin-top:20px;">
                    📝 1. Regístrate diligenciando tus datos en el módulo de atención
                </div>
            </div>
        </div>
        <div class="footer">
            <span id="fecha"></span>
            <span id="hora"></span>
            <span>🟢 Conectado</span>
        </div>
    </div>

    <div class="right">
        <div class="turn-header">
            <span>🎫 TURNO</span>
            <span>👤 PACIENTE</span>
            <span>📍 MÓDULO</span>
        </div>
        <div class="turn-list" id="turnsList"></div>
    </div>
</div>

<script>
const turnsList = document.getElementById("turnsList");

function getSpecialtyCode(especialidadNombre) {
    if (!especialidadNombre) return 'CON';
    const nombre = especialidadNombre.toLowerCase();
    if (nombre.includes('consulta') || nombre.includes('extern')) return 'CON';
    if (nombre.includes('odontologia') || nombre.includes('odontología')) return 'ODO';
    if (nombre.includes('laboratorio')) return 'LAB';
    if (nombre.includes('rayos') || nombre.includes('radiologia')) return 'RAY';
    return 'CON';
}

function getSpecialtyClass(especialidadNombre) {
    if (!especialidadNombre) return 'con';
    const nombre = especialidadNombre.toLowerCase();
    if (nombre.includes('consulta') || nombre.includes('extern')) return 'con';
    if (nombre.includes('odontologia') || nombre.includes('odontología')) return 'odo';
    if (nombre.includes('laboratorio')) return 'lab';
    if (nombre.includes('rayos') || nombre.includes('radiologia')) return 'ray';
    return 'con';
}

function getModuloMostrar(turno) {
    if (turno.estado === 'llamado' && turno.ventanillaAsignada) return turno.ventanillaAsignada;
    return turno.ventanilla;
}

function actualizarFechaHora(){
    const ahora = new Date();
    const dias = ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'];
    const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    const fechaStr = `${dias[ahora.getDay()]}, ${ahora.getDate()} DE ${meses[ahora.getMonth()]} DE ${ahora.getFullYear()}`;
    document.getElementById("fecha").innerText = fechaStr;
    document.getElementById("hora").innerText = ahora.toLocaleTimeString();
}

function renderizarTurnos(turnos){
    if(!turnos || turnos.length === 0){
        turnsList.innerHTML = "<div style='text-align:center; padding:40px; color:#999; font-size:20px;'>📭 No hay turnos en espera</div>";
        return;
    }
    const turnosOrdenados = [...turnos].sort((a,b) => new Date(a.timestamp) - new Date(b.timestamp));
    const turnosMostrar = turnosOrdenados.slice(0, 10);

    turnsList.innerHTML = turnosMostrar.map(turno => {
        const specialtyCode = getSpecialtyCode(turno.nombreEspecialidad);
        const numeroTurno = turno.numero || `${specialtyCode}-00`;
        const codigoTurno = (numeroTurno.split('-')[0] || specialtyCode).substring(0,3);
        const numero = String(numeroTurno.split('-')[1] || '00').padStart(2, '0');
        const specialtyClass = getSpecialtyClass(turno.nombreEspecialidad);
        const moduloMostrar = getModuloMostrar(turno);
        const nombreCompleto = turno.nombre_persona || "Paciente sin nombre";
        
        const claseParpadeo = turno.estado === 'llamado' ? 'parpadeando' : '';
        
        return `
        <div class="turn-row ${specialtyClass} ${claseParpadeo}" data-turno-numero="${turno.numero}">
            <span class="turn-code" style="color:#1aa39a; font-family:monospace;">${codigoTurno}-${numero}</span>
            <span class="turn-patient-name" title="${nombreCompleto}">👤 ${nombreCompleto}</span>
            <span class="destination" style="background:#f0f0f0; padding:4px 12px; border-radius:20px;">MÓDULO ${moduloMostrar}</span>
        </div>`;
    }).join('');
}

function cargarTurnos() {
    try {
        const turnosGuardados = localStorage.getItem('turnos');
        let todosLosTurnos = turnosGuardados ? JSON.parse(turnosGuardados) : [];
        const turnosActivos = todosLosTurnos.filter(t => t.estado === 'llamado');
        renderizarTurnos(turnosActivos);
    } catch (e) { console.error(e); }
}

setInterval(() => { actualizarFechaHora(); cargarTurnos(); }, 1000);
actualizarFechaHora();
cargarTurnos();
</script>
</body>
</html>