<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>No Autorizado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.1.2/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-red-600 mb-4">403 - No Autorizado</h1>
            <p class="text-lg text-gray-700 mb-8">No tienes permiso para acceder a esta página.</p>
            <a href="{{ url('dashboard') }}" class="text-blue-500 hover:underline">Volver a la página de inicio</a>
        </div>
    </div>
</body>
</html>
