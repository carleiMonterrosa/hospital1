<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pantalla de Turnos - Hospital San Pablo</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #e9eef3;
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
}

.container {
    display: flex;
    width: 100vw; 
    height: 100vh;
}

.left {
    flex: 1.1;
    background: #ffffff;
    padding: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.right {
    flex: 1.3;
    background: #f7f7f7;
    padding: 15px;
    border-left: 3px solid #1aa39a;
    display: flex;
    flex-direction: column;
}

.header {
    background: linear-gradient(135deg, #7FFFD4, #0e7a73);
    color: white;
    padding: 15px 25px;
    font-size: 32px;
    font-weight: bold;
    text-align: center;
    border-radius: 12px;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
}

.header img {
    height: 60px;
    width: auto;
    border-radius: 8px;
    background: white;
    padding: 5px;
}

.screen-box {
    background: linear-gradient(145deg, #f8fafc, #eef2f8);
    border-radius: 20px;
    padding: 25px;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.attention-title {
    background: linear-gradient(135deg, #7FFFD4, #0e7a73);
    display: inline-block;
    padding: 12px 40px;
    border-radius: 50px;
    margin-bottom: 15px;
    align-self: center;
}

.attention-title h2 {
    color: white;
    font-size: 2em;
    margin: 0;
}

.welcome-message {
    font-size: 1.4em;
    color: #0e7a73;
    font-weight: 600;
    margin-bottom: 10px;
}

.banner-wrapper {
    position: relative;
    margin-top: 10px;
    flex-grow: 1;
}

.banner-container {
    border-radius: 16px;
    overflow: hidden;
    height: 100%;
    min-height: 420px; 
    transition: all 0.5s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.banner-contenido {
    position: relative;
    background-size: cover;
    background-position: center;
    padding: 40px;
    height: 100%;
    min-height: 420px;
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
    margin-bottom: 50px;
}

.banner-titulo {
    font-size: 1.8em;
    font-weight: 600;
    padding: 10px 20px;
    border-radius: 50px;
    margin-bottom: 8px;
    display: inline-block;
    letter-spacing: 1px;
    font-family: 'Poppins', sans-serif;
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.banner-subtitulo {
    font-size: 1.2em;
    font-weight: 500;
    padding: 8px 18px;
    border-radius: 40px;
    margin-bottom: 6px;
    display: inline-block;
    letter-spacing: 0.5px;
    font-family: 'Poppins', sans-serif;
    background: rgba(0, 0, 0, 0.65);
    border: 1px solid rgba(255,255,255,0.3);
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}

.banner-accesibilidad {
    font-size: 0.9em;
    font-weight: 400;
    padding: 5px 12px;
    border-radius: 30px;
    display: inline-block;
    font-family: 'Poppins', sans-serif;
    background: rgba(0, 0, 0, 0.6);
    border: 1px solid rgba(255,255,255,0.25);
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.banner-flecha {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.6);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 1.5rem;
    z-index: 10;
    transition: all 0.3s;
}

.banner-flecha:hover {
    background: rgba(0,0,0,0.8);
    transform: translateY(-50%) scale(1.05);
}

.banner-flecha-izquierda { left: 10px; }
.banner-flecha-derecha { right: 10px; }

.banner-indicador {
    display: flex;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
}

.banner-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #ccc;
    cursor: pointer;
    transition: all 0.3s;
}

.banner-dot.activo {
    background: #1aa39a;
    width: 25px;
    border-radius: 10px;
}

.footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 18px;
    font-weight: bold;
    color: #333;
    padding: 15px;
    background: #f0f2f5;
    border-radius: 10px;
    margin-top: 10px;
}

.turn-header {
    display: flex;
    justify-content: space-between;
    background: linear-gradient(135deg, #1aa39a, #0e7a73);
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    font-size: 22px;
}

.turn-list { flex-grow: 1; overflow-y: auto; }

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
}

.parpadeando { animation: parpadeo 0.8s ease-in-out infinite; }

@keyframes parpadeo {
    0%, 100% { background: #fff3cd; }
    50% { background: #ffe69b; }
}
</style>
</head>
<body>

<div class="container">
    <div class="left">
        <div style="display: flex; flex-direction: column; height: 100%;">
            <div class="header">
                <img src="images/logo.jpg" alt="Logo HSP">
                BIENVENIDO A HOSPITAL SAN PABLO
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

// ===== OBTENER TURNOS DEL SERVIDOR =====
async function obtenerTurnosDelServidor() {
    try {
       const response = await fetch('/tv/turnos');
        if (!response.ok) throw new Error('Error al obtener turnos');
        const data = await response.json();
        return data.turnos || [];
    } catch (error) {
        console.error('Error obteniendo turnos del servidor:', error);
        try {
            const data = localStorage.getItem('turnos');
            const turnos = data ? JSON.parse(data) : [];
            return turnos.filter(t => t.estado === 'llamado');
        } catch(e) { return []; }
    }
}

// ===== CARGAR TURNOS =====
async function cargarTurnos() {
    try {
        const llamados = await obtenerTurnosDelServidor();
        const lista = document.getElementById("turnsList");
        
        if(llamados.length === 0) {
            lista.innerHTML = "<div style='text-align:center; padding:40px; color:#999;'>📭 No hay turnos</div>";
            return;
        }
        
        const turnosMostrar = llamados.slice(0, 10);
        lista.innerHTML = turnosMostrar.map(t => `
            <div class="turn-row parpadeando">
                <span style="color:#1aa39a;">${t.numero}</span>
                <span style="font-size:18px;">👤 ${t.nombre_persona || 'Paciente'}</span>
                <span style="background:#eee; padding:5px 15px; border-radius:20px;">MÓDULO ${t.id_modulo || 1}span>
            </div>
        `).join('');
    } catch(e) { console.error(e); }
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
                document.getElementById('bannerRotativo').innerHTML = '<div style="display:flex; align-items:center; justify-content:center; height:100%; color:#999;">📺 Sin banners</div>';
                detenerAutoRotacion();
            } else {
                if (indiceActual >= banners.length) indiceActual = 0;
                mostrarBanner(indiceActual);
                iniciarAutoRotacion();
            }
        }
    } catch(e) { console.error(e); }
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