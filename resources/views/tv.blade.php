<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>Pantalla de Turnos - Hospital San Pablo</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ===== RESET Y BASE ===== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #e9eef3;
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
    font-size: 16px;
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.container {
    display: flex;
    width: 100vw;
    height: 100vh;
    gap: 10px;
    padding: 10px;
    background: #e9eef3;
}

/* ===== LADO IZQUIERDO ===== */
.left {
    flex: 1.1;
    background: #ffffff;
    padding: 12px;
    border-radius: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    min-width: 0;
}

/* ===== LADO DERECHO (TURNOS) ===== */
.right {
    flex: 1.3;
    background: #f7f7f7;
    padding: 12px;
    border-radius: 18px;
    border-left: 4px solid #1aa39a;
    display: flex;
    flex-direction: column;
    min-width: 0;
}

/* ===== HEADER ===== */
.header {
    background: linear-gradient(135deg, #7FFFD4, #0e7a73);
    color: white;
    padding: clamp(10px, 1.5vh, 18px) clamp(15px, 2vw, 25px);
    font-size: clamp(18px, 2.5vw, 36px);
    font-weight: 700;
    text-align: center;
    border-radius: 12px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    flex-shrink: 0;
    min-height: clamp(50px, 8vh, 80px);
}

.header img {
    height: clamp(35px, 5vh, 60px);
    width: auto;
    border-radius: 8px;
    background: white;
    padding: 4px;
}

.header span {
    font-size: inherit;
    font-weight: 700;
    line-height: 1.2;
}

/* ===== CAJA DE ATENCIÓN ===== */
.screen-box {
    background: linear-gradient(145deg, #f8fafc, #eef2f8);
    border-radius: 16px;
    padding: clamp(10px, 1.5vh, 20px);
    text-align: center;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    min-height: 0;
    overflow: hidden;
}

.attention-title {
    background: linear-gradient(135deg, #7FFFD4, #0e7a73);
    display: inline-block;
    padding: clamp(6px, 1vh, 12px) clamp(20px, 3vw, 40px);
    border-radius: 50px;
    margin-bottom: clamp(6px, 1vh, 12px);
    align-self: center;
    flex-shrink: 0;
}

.attention-title h2 {
    color: white;
    font-size: clamp(16px, 2.2vw, 32px);
    margin: 0;
    font-weight: 600;
}

.welcome-message {
    font-size: clamp(14px, 1.8vw, 26px);
    color: #0e7a73;
    font-weight: 600;
    margin-bottom: clamp(6px, 1vh, 12px);
    flex-shrink: 0;
}

/* ===== BANNER ===== */
.banner-wrapper {
    position: relative;
    flex: 1;
    min-height: 0;
    margin-top: 4px;
}

.banner-container {
    border-radius: 14px;
    overflow: hidden;
    height: 100%;
    width: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    min-height: clamp(120px, 25vh, 350px);
}

.banner-contenido {
    position: relative;
    background-size: cover;
    background-position: center;
    padding: clamp(15px, 2vh, 35px);
    height: 100%;
    width: 100%;
    min-height: clamp(120px, 25vh, 350px);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    text-align: center;
}

.banner-texto-inferior {
    position: relative;
    z-index: 2;
    width: 100%;
    text-align: center;
    margin-bottom: clamp(15px, 3vh, 45px);
}

.banner-titulo {
    font-size: clamp(18px, 2.5vw, 38px);
    font-weight: 700;
    padding: clamp(6px, 1vh, 14px) clamp(12px, 2vw, 28px);
    border-radius: 40px;
    margin-bottom: clamp(4px, 0.5vh, 8px);
    display: inline-block;
    letter-spacing: 0.5px;
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    color: #FFFFFF;
    max-width: 95%;
    word-wrap: break-word;
}

.banner-subtitulo {
    font-size: clamp(14px, 1.8vw, 26px);
    font-weight: 500;
    padding: clamp(4px, 0.5vh, 10px) clamp(10px, 1.5vw, 22px);
    border-radius: 30px;
    margin-bottom: clamp(4px, 0.5vh, 6px);
    display: inline-block;
    background: rgba(0, 0, 0, 0.65);
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    color: #FFFFFF;
    max-width: 95%;
    word-wrap: break-word;
}

.banner-accesibilidad {
    font-size: clamp(11px, 1.2vw, 18px);
    font-weight: 400;
    padding: clamp(3px, 0.3vh, 8px) clamp(8px, 1vw, 16px);
    border-radius: 30px;
    display: inline-block;
    background: rgba(0, 0, 0, 0.6);
    border: 1px solid rgba(255,255,255,0.25);
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    color: #eeeeee;
    max-width: 95%;
    word-wrap: break-word;
}

.banner-flecha {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.5);
    color: white;
    border: none;
    width: clamp(30px, 4vw, 50px);
    height: clamp(30px, 4vw, 50px);
    border-radius: 50%;
    cursor: pointer;
    font-size: clamp(16px, 2vw, 26px);
    z-index: 10;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.banner-flecha:hover {
    background: rgba(0,0,0,0.8);
    transform: translateY(-50%) scale(1.05);
}

.banner-flecha-izquierda { left: 8px; }
.banner-flecha-derecha { right: 8px; }

.banner-indicador {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: clamp(6px, 0.8vh, 10px);
    flex-shrink: 0;
}

.banner-dot {
    width: clamp(8px, 1vw, 12px);
    height: clamp(8px, 1vw, 12px);
    border-radius: 50%;
    background: #ccc;
    cursor: pointer;
    transition: all 0.3s;
}

.banner-dot.activo {
    background: #1aa39a;
    width: clamp(20px, 2.5vw, 30px);
    border-radius: 10px;
}

/* ===== FOOTER ===== */
.footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: clamp(12px, 1.4vw, 20px);
    font-weight: 600;
    color: #333;
    padding: clamp(8px, 1vh, 15px) clamp(12px, 1.5vw, 20px);
    background: #f0f2f5;
    border-radius: 10px;
    margin-top: 8px;
    flex-shrink: 0;
    gap: 10px;
    flex-wrap: wrap;
    min-height: clamp(35px, 5vh, 55px);
}

.footer span {
    white-space: nowrap;
}

/* ===== LISTA DE TURNOS ===== */
.turn-header {
    display: flex;
    justify-content: space-between;
    background: linear-gradient(135deg, #1aa39a, #0e7a73);
    color: white;
    padding: clamp(10px, 1.5vh, 18px) clamp(12px, 1.5vw, 22px);
    border-radius: 10px 10px 0 0;
    font-size: clamp(14px, 1.6vw, 24px);
    font-weight: 600;
    flex-shrink: 0;
}

.turn-header span {
    flex: 1;
    text-align: center;
}

.turn-header span:first-child { text-align: left; }
.turn-header span:last-child { text-align: right; }

.turn-list {
    flex: 1;
    overflow-y: auto;
    padding: clamp(4px, 0.5vh, 10px) 0;
    min-height: 0;
}

.turn-list::-webkit-scrollbar {
    width: 4px;
}

.turn-list::-webkit-scrollbar-thumb {
    background: #1aa39a;
    border-radius: 4px;
}

.turn-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: clamp(8px, 1.2vh, 16px) clamp(10px, 1.5vw, 20px);
    margin-bottom: clamp(4px, 0.5vh, 10px);
    background: white;
    border-left: clamp(4px, 0.6vw, 8px) solid #1aa39a;
    font-size: clamp(16px, 2vw, 30px);
    font-weight: 600;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: all 0.3s;
    min-height: clamp(40px, 6vh, 70px);
}

.turn-row .turno-numero {
    color: #1aa39a;
    font-weight: 700;
    flex: 1;
    text-align: left;
    font-size: clamp(16px, 2vw, 30px);
}

.turn-row .turno-paciente {
    font-size: clamp(13px, 1.5vw, 22px);
    flex: 1.5;
    text-align: center;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.turn-row .turno-modulo {
    background: #eee;
    padding: clamp(3px, 0.4vh, 8px) clamp(8px, 1vw, 18px);
    border-radius: 20px;
    font-weight: 700;
    font-size: clamp(11px, 1.2vw, 18px);
    flex: 0.8;
    text-align: center;
}

/* ===== PARPADEO PARA TURNOS LLAMADOS ===== */
.parpadeando {
    animation: parpadeo 0.8s ease-in-out infinite;
}

@keyframes parpadeo {
    0%, 100% { background: #fff3cd; }
    50% { background: #ffe69b; }
}

/* ============================================================
   🔥 ESTILOS RESPONSIVE PARA TV Y PANTALLAS PEQUEÑAS
   ============================================================ */

/* Para tablets y pantallas medianas (entre 768px y 1024px) */
@media (max-width: 1024px) {
    .container {
        gap: 6px;
        padding: 6px;
    }
    .left {
        padding: 8px;
        border-radius: 12px;
    }
    .right {
        padding: 8px;
        border-radius: 12px;
    }
    .header {
        padding: 8px 12px;
        font-size: clamp(14px, 2vw, 22px);
        min-height: 40px;
        gap: 10px;
    }
    .header img {
        height: 30px;
    }
    .attention-title h2 {
        font-size: clamp(13px, 1.8vw, 20px);
    }
    .welcome-message {
        font-size: clamp(11px, 1.4vw, 18px);
    }
    .banner-container {
        min-height: 80px;
    }
    .banner-contenido {
        min-height: 80px;
        padding: 10px;
    }
    .banner-titulo {
        font-size: clamp(14px, 2vw, 24px);
        padding: 4px 12px;
    }
    .banner-subtitulo {
        font-size: clamp(11px, 1.5vw, 18px);
        padding: 3px 10px;
    }
    .banner-accesibilidad {
        font-size: clamp(9px, 1vw, 13px);
        padding: 2px 8px;
    }
    .banner-flecha {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }
    .banner-flecha-izquierda { left: 4px; }
    .banner-flecha-derecha { right: 4px; }
    .banner-indicador {
        gap: 4px;
        margin-top: 4px;
    }
    .banner-dot {
        width: 6px;
        height: 6px;
    }
    .banner-dot.activo {
        width: 16px;
    }
    .footer {
        font-size: clamp(10px, 1.2vw, 14px);
        padding: 6px 10px;
        min-height: 28px;
        margin-top: 4px;
    }
    .turn-header {
        font-size: clamp(11px, 1.3vw, 16px);
        padding: 8px 12px;
    }
    .turn-row {
        padding: 6px 10px;
        font-size: clamp(13px, 1.6vw, 20px);
        min-height: 32px;
        margin-bottom: 4px;
        border-left-width: 4px;
    }
    .turn-row .turno-numero {
        font-size: clamp(13px, 1.6vw, 20px);
    }
    .turn-row .turno-paciente {
        font-size: clamp(10px, 1.2vw, 16px);
    }
    .turn-row .turno-modulo {
        font-size: clamp(9px, 1vw, 13px);
        padding: 2px 8px;
    }
    .screen-box {
        padding: 8px;
        border-radius: 12px;
    }
    .attention-title {
        padding: 4px 16px;
        margin-bottom: 4px;
    }
}

/* Para pantallas muy pequeñas (teléfonos) */
@media (max-width: 768px) {
    .container {
        flex-direction: column !important;
        gap: 4px;
        padding: 4px;
    }
    .left {
        flex: 1.5;
        padding: 6px;
    }
    .right {
        flex: 2;
        padding: 6px;
        border-left: none;
        border-top: 3px solid #1aa39a;
    }
    .header {
        padding: 6px 10px;
        font-size: clamp(12px, 2.5vw, 18px);
        min-height: 32px;
        gap: 6px;
        margin-bottom: 4px;
    }
    .header img {
        height: 22px;
        padding: 2px;
    }
    .attention-title h2 {
        font-size: clamp(11px, 2vw, 16px);
    }
    .welcome-message {
        font-size: clamp(10px, 1.8vw, 14px);
        margin-bottom: 4px;
    }
    .banner-container {
        min-height: 60px;
    }
    .banner-contenido {
        min-height: 60px;
        padding: 8px;
    }
    .banner-titulo {
        font-size: clamp(12px, 2.2vw, 18px);
        padding: 3px 8px;
    }
    .banner-subtitulo {
        font-size: clamp(10px, 1.8vw, 14px);
        padding: 2px 8px;
    }
    .banner-accesibilidad {
        font-size: clamp(8px, 1.2vw, 11px);
        padding: 2px 6px;
    }
    .banner-flecha {
        width: 20px;
        height: 20px;
        font-size: 10px;
    }
    .banner-texto-inferior {
        margin-bottom: 10px;
    }
    .turn-header {
        font-size: clamp(10px, 1.5vw, 14px);
        padding: 6px 10px;
    }
    .turn-row {
        padding: 4px 8px;
        font-size: clamp(11px, 1.8vw, 16px);
        min-height: 28px;
        margin-bottom: 3px;
        border-left-width: 3px;
    }
    .turn-row .turno-numero {
        font-size: clamp(11px, 1.8vw, 16px);
    }
    .turn-row .turno-paciente {
        font-size: clamp(9px, 1.4vw, 13px);
    }
    .turn-row .turno-modulo {
        font-size: clamp(8px, 1.2vw, 11px);
        padding: 2px 6px;
    }
    .footer {
        font-size: clamp(9px, 1.5vw, 12px);
        padding: 4px 8px;
        min-height: 24px;
        margin-top: 4px;
    }
    .banner-indicador {
        margin-top: 4px;
        gap: 4px;
    }
    .banner-dot {
        width: 5px;
        height: 5px;
    }
    .banner-dot.activo {
        width: 12px;
    }
    .screen-box {
        padding: 6px;
    }
    .attention-title {
        padding: 3px 12px;
        margin-bottom: 3px;
    }
}

/* Para pantallas muy grandes (TVs grandes) */
@media (min-width: 1920px) {
    .header {
        font-size: clamp(32px, 3vw, 48px);
        min-height: 100px;
    }
    .header img {
        height: 80px;
    }
    .attention-title h2 {
        font-size: clamp(32px, 2.8vw, 48px);
    }
    .welcome-message {
        font-size: clamp(26px, 2.2vw, 38px);
    }
    .banner-titulo {
        font-size: clamp(38px, 3vw, 56px);
    }
    .banner-subtitulo {
        font-size: clamp(26px, 2.2vw, 38px);
    }
    .turn-row {
        font-size: clamp(30px, 2.5vw, 42px);
        min-height: 80px;
        padding: 15px 25px;
    }
    .turn-row .turno-numero {
        font-size: clamp(30px, 2.5vw, 42px);
    }
    .turn-row .turno-paciente {
        font-size: clamp(22px, 1.8vw, 32px);
    }
    .turn-row .turno-modulo {
        font-size: clamp(18px, 1.5vw, 28px);
        padding: 8px 20px;
    }
    .turn-header {
        font-size: clamp(24px, 2vw, 34px);
        padding: 20px 30px;
    }
    .footer {
        font-size: clamp(20px, 1.6vw, 30px);
        padding: 15px 25px;
        min-height: 60px;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="left">
        <div style="display: flex; flex-direction: column; height: 100%;">
            <div class="header">
                <img src="images/logo.jpg" alt="Logo HSP">
                <span>BIENVENIDO A HOSPITAL SAN PABLO</span>
            </div>
            
            <div class="screen-box">
                <div class="attention-title">
                    <h2>📢 ATENCIÓN PACIENTES</h2>
                </div>
                <div class="welcome-message">
                    ✨ Por favor, espere a ser llamado ✨
                </div>
                
                <div class="banner-wrapper">
                    <button class="banner-flecha banner-flecha-izquierda" onclick="cambiarBanner(-1)">←</button>
                    <div id="bannerRotativo" class="banner-container"></div>
                    <button class="banner-flecha banner-flecha-derecha" onclick="cambiarBanner(1)">→</button>
                    <div id="bannerIndicadores" class="banner-indicador"></div>
                </div>
            </div>
            
            <div class="footer">
                <span id="fecha"></span>
                <span id="hora"></span>
                <span style="color: #28a745;">🟢 Conectado</span>
            </div>
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
let banners = [];
let indiceActual = 0;
let intervaloRotacion = null;
let duracionSegundos = 10;

// ===== 🔥 OBTENER TURNOS DEL SERVIDOR =====
async function obtenerTurnosDelServidor() {
    try {
        const response = await fetch('/tv/turnos');
        if (!response.ok) throw new Error('Error al obtener turnos');
        const data = await response.json();
        return data.turnos || [];
    } catch (error) {
        console.error('Error obteniendo turnos del servidor:', error);
        return [];
    }
}

// ===== CARGAR TURNOS =====
async function cargarTurnos() {
    try {
        const data = await obtenerTurnosDelServidor();
        const lista = document.getElementById("turnsList");
        
        // Mostrar TODOS los turnos activos (espera y llamado)
        const turnosActivos = data.filter(t => t.estado !== 'atendido' && t.estado !== 'eliminado');
        
        if(turnosActivos.length === 0) {
            lista.innerHTML = "<div style='text-align:center; padding:clamp(20px, 4vh, 50px); color:#999; font-size:clamp(16px, 2vw, 28px);'>📭 No hay turnos</div>";
            return;
        }
        
        // Ordenar: primero los llamados (parpadean), luego los en espera
        turnosActivos.sort((a, b) => {
            if (a.estado === 'llamado' && b.estado !== 'llamado') return -1;
            if (a.estado !== 'llamado' && b.estado === 'llamado') return 1;
            return 0;
        });
        
        const turnosMostrar = turnosActivos.slice(0, 10);
        lista.innerHTML = turnosMostrar.map(t => {
            const nombrePaciente = t.nombre_persona || 'Paciente';
            const modulo = t.id_modulo || 1;
            const numero = t.numero || '---';
            const estadoClass = t.estado === 'llamado' ? 'parpadeando' : '';
            
            return `
                <div class="turn-row ${estadoClass}">
                    <span class="turno-numero">${numero}</span>
                    <span class="turno-paciente">👤 ${nombrePaciente}</span>
                    <span class="turno-modulo">MÓDULO ${modulo}</span>
                </div>
            `;
        }).join('');
    } catch(e) { 
        console.error('Error cargando turnos:', e);
        document.getElementById("turnsList").innerHTML = "<div style='text-align:center; padding:clamp(20px, 4vh, 50px); color:#dc3545; font-size:clamp(16px, 2vw, 28px);'>⚠️ Error al cargar turnos</div>";
    }
}

// ===== FUNCIONES PARA BANNERS =====
function cargarDatosBanners() {
    try {
        const dataStr = localStorage.getItem('banners_tv');
        const nuevaLista = dataStr ? JSON.parse(dataStr) : [];
        const duracionData = localStorage.getItem('rotacion_duracion');
        const nuevaDuracion = duracionData ? parseInt(duracionData) : 10;

        if (JSON.stringify(nuevaLista) !== JSON.stringify(banners) || nuevaDuracion !== duracionSegundos) {
            banners = nuevaLista;
            duracionSegundos = nuevaDuracion;
            if (banners.length === 0) {
                document.getElementById('bannerRotativo').innerHTML = '<div style="display:flex; align-items:center; justify-content:center; height:100%; color:#999; font-size:clamp(16px, 2vw, 28px);">📺 Sin banners</div>';
                detenerAutoRotacion();
            } else {
                if (indiceActual >= banners.length) indiceActual = 0;
                mostrarBanner(indiceActual);
                iniciarAutoRotacion();
            }
        }
    } catch(e) { console.error('Error cargando banners:', e); }
}

function mostrarBanner(index) {
    const container = document.getElementById('bannerRotativo');
    const banner = banners[index];
    if (!banner) return;
    
    let estiloFondo = '';
    if (banner.tipoFondo === 'color' && banner.colorFondoSolido) {
        estiloFondo = `background: ${banner.colorFondoSolido};`;
    } else if (banner.url) {
        estiloFondo = `background-image: url('${banner.url}'); background-size: cover; background-position: center;`;
    } else {
        estiloFondo = `background: linear-gradient(135deg, #1aa39a, #0e7a73);`;
    }
    
    let textoHtml = '';
    let hayTexto = false;
    
    if (banner.titulo && banner.titulo.trim() !== '') {
        textoHtml += `<div class="banner-titulo" style="color: ${banner.tituloColor || '#FFFFFF'};">✨ ${banner.titulo}</div>`;
        hayTexto = true;
    }
    
    if (banner.subtitulo && banner.subtitulo.trim() !== '') {
        textoHtml += `<div class="banner-subtitulo" style="color: ${banner.subtituloColor || '#FFFFFF'};">⭐ ${banner.subtitulo}</div>`;
        hayTexto = true;
    }
    
    if (banner.textoAccesibilidad && banner.textoAccesibilidad.trim() !== '') {
        textoHtml += `<div class="banner-accesibilidad" style="color: #eeeeee;">💬 ${banner.textoAccesibilidad}</div>`;
        hayTexto = true;
    }
    
    if (!hayTexto) {
        textoHtml = '<div class="banner-titulo" style="color:white;">🏥 HOSPITAL SAN PABLO</div>';
    }
    
    let justificacion = 'center';
    if (banner.justificacion === 'left') justificacion = 'flex-start';
    if (banner.justificacion === 'right') justificacion = 'flex-end';
    
    container.innerHTML = `
        <div class="banner-contenido" style="${estiloFondo}">
            <div class="banner-texto-inferior" style="text-align: ${banner.justificacion || 'center'}; display: flex; flex-direction: column; align-items: ${justificacion}; width: 100%;">
                ${textoHtml}
            </div>
        </div>
    `;
    actualizarIndicadores(index);
}

function iniciarAutoRotacion() {
    detenerAutoRotacion();
    if (banners.length >= 2) {
        intervaloRotacion = setInterval(() => {
            indiceActual = (indiceActual + 1) % banners.length;
            mostrarBanner(indiceActual);
        }, duracionSegundos * 1000);
    }
}

function detenerAutoRotacion() {
    if (intervaloRotacion) clearInterval(intervaloRotacion);
}

function actualizarIndicadores(activo) {
    const container = document.getElementById('bannerIndicadores');
    if (banners.length <= 1) { container.innerHTML = ''; return; }
    let html = '';
    for (let i = 0; i < banners.length; i++) {
        html += `<div class="banner-dot ${i === activo ? 'activo' : ''}" onclick="irABanner(${i})"></div>`;
    }
    container.innerHTML = html;
}

function cambiarBanner(direccion) {
    if (banners.length === 0) return;
    indiceActual = (indiceActual + direccion + banners.length) % banners.length;
    mostrarBanner(indiceActual);
    iniciarAutoRotacion();
}

function irABanner(index) {
    indiceActual = index;
    mostrarBanner(indiceActual);
    iniciarAutoRotacion();
}

function actualizarReloj(){
    const ahora = new Date();
    const dias = ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'];
    const meses = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
    document.getElementById("fecha").innerText = `${dias[ahora.getDay()]}, ${ahora.getDate()} DE ${meses[ahora.getMonth()]} DE ${ahora.getFullYear()}`;
    document.getElementById("hora").innerText = ahora.toLocaleTimeString();
}

// ===== FUNCIÓN PARA RECARGAR LA PÁGINA CADA CIERTO TIEMPO (OPCIONAL) =====
// Útil si la TV se queda colgada después de mucho tiempo
// setInterval(() => location.reload(), 3600000); // Recarga cada hora

// ===== INICIALIZAR =====
actualizarReloj();
cargarDatosBanners();
cargarTurnos();
setInterval(actualizarReloj, 1000);
setInterval(cargarTurnos, 3000);
setInterval(cargarDatosBanners, 5000);
</script>
</body>
</html>