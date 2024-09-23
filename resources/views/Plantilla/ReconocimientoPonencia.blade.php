<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Participación</title>

	<style>
		/* Estilos generales */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Contenedor del certificado */
.certificate-container {
    background-color: #fff;
    width: 900px;
    height: 600px;
    padding: 0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    position: relative;
    overflow: hidden; /* Para ocultar elementos que se desborden */
}

/* Estilos para las líneas laterales */
.left-side {
    position: absolute;
    top: 0;
    left: 0;
    width: 150px;
    height: 100%;
    background: linear-gradient(to bottom, #FFA500 0%, #FFA500 50%, #FF4C4C 50%, #FF4C4C 100%);
    border-top-right-radius: 50px;
    border-bottom-right-radius: 50px;
}

.right-side {
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 100%;
    background: #4B0082; /* Color morado */
}

/* Estilos para el encabezado (logos) */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #fff;
    padding: 10px 20px;
    position: relative;
    z-index: 10; /* Para asegurarse de que el contenido esté encima de las bandas laterales */
}

.logo-left {
    background-color: #FFA500; /* Naranja */
    padding: 10px;
}

.logo-right {
    padding: 10px;
}

.logo {
    width: 150px;
    height: auto;
}

/* Banner morado */
.purple-banner {
    background-color: #4B0082; /* Morado */
    height: 50px;
    margin-top: 10px;
    position: relative;
    z-index: 10;
}

/* Contenido del certificado */
.certificate-content {
    padding: 20px;
    position: relative;
    z-index: 10;
}

/* Título del certificado */
.certificate-title {
    font-size: 40px;
    font-weight: bold;
    color: #FF6700; /* Naranja */
    margin: 20px 0;
}

.certificate-body {
    font-size: 18px;
    margin: 15px 0;
    color: #555;
}

.participant-name {
    font-size: 32px;
    font-weight: bold;
    color: #4B0082; /* Morado */
    margin: 10px 0;
}

.event-name {
    font-size: 24px;
    font-weight: bold;
    color: #FF6700; /* Naranja */
}

/* Firma */
.signature-container {
    margin-top: 50px;
    display: flex;
    justify-content: center;
}

.signature {
    text-align: center;
    margin: 20px;
    color: #4B0082; /* Morado */
}

	</style>
</head>
<body>
    <div class="certificate-container">
        <!-- Líneas laterales -->
        <div class="left-side"></div>
        <div class="right-side"></div>

        <!-- Contenido del certificado -->
        <div class="header">
            <div class="logo-left">
                <img src="logo_upemor_left.png" alt="Logo UPEMOR" class="logo">
            </div>
            <div class="logo-right">
                <img src="logo_upemor_right.png" alt="Logo UPEMOR" class="logo">
            </div>
        </div>
        <div class="purple-banner"></div>
        <div class="certificate-content">
            <h1 class="certificate-title">Certificado de Participación</h1>
            <p class="certificate-body">Este certificado es otorgado a</p>
            <h2 class="participant-name">[Nombre del Participante]</h2>
            <p class="certificate-body">por su valiosa participación en</p>
            <h3 class="event-name">[Nombre del Evento]</h3>
            <p class="certificate-body">Dado en [Ciudad] el [Fecha]</p>
            <div class="signature-container">
                <div class="signature">
                    <p>______________________</p>
                    <p>Firma del Organizador</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
