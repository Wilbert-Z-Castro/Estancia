<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Egresados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            word-wrap: break-word;
            white-space: normal;
            font-size: 9px; /* Reducir tamaño de fuente */
        }
        th {
            background-color: #f2f2f2;
        }
        th:nth-child(1), td:nth-child(1) { /* Nombre */
            width: 13%;
        }
        th:nth-child(2), td:nth-child(2) { /* Medios de contacto */
            width: 20%;
        }
        th:nth-child(3), td:nth-child(3) { /* Empresa Actual */
            width: 10%;
        }
        th:nth-child(4), td:nth-child(4) { /* Año de Egreso */
            width: 8%;
        }
        th:nth-child(5), td:nth-child(5) { /* Sector Empresarial */
            width: 15%;
        }
        th:nth-child(6), td:nth-child(6) { /* Puesto de Trabajo */
            width: 13%;
        }
        th:nth-child(7), td:nth-child(7) { /* Carrera */
            width: 9%;
        }
        th:nth-child(8), td:nth-child(8) { /* Carrera */
            width: 7%;
        }
        tr {
            page-break-inside: avoid; /* Evita cortes entre páginas */
        }
        /* Estilos para la imagen */
        .responsive-img {
            max-width: 100%; /* Asegura que la imagen no exceda el ancho del contenedor */
            height: auto; /* Mantiene la relación de aspecto de la imagen */
            display: block; /* Elimina el espacio extra en la parte inferior */
            margin: 0 auto; /* Centra la imagen */
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <!--Div that will hold the pie chart-->
    @foreach ($grupoEgresados as $nombreCarrera => $grupoEgresados)
        <h3>Carrera: {{ $nombreCarrera }}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Género</th>
                    <th>Empresa Actual</th>
                    <th>Puesto de Trabajo</th>
                    <th>Sector Empresarial</th>
                    <th>Año de Egreso</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grupoEgresados as $egresado)
                    <tr>
                        <td>{{ $egresado->user->name }} {{ $egresado->user->ApellidoP }} {{ $egresado->user->ApellidoM }}</td>
                        <td>{{ $egresado->user->email }}</td>
                        <td>{{ $egresado->user->Telefono }}</td>
                        <td>{{ $egresado->user->Sexo }}</td>
                        <td>{{ $egresado->EmpresaActual }}</td>
                        <td>{{ $egresado->PuestoTrabajo }}</td>
                        <td>{{ $egresado->SectorEmpresaria }}</td>
                        <td>{{ $egresado->AnioEgreso }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="page-break"></div>
    <img src="{{ $imgSrc }}" class="responsive-img" >
    <img src="{{ $imgSrc2 }}" class="responsive-img" >
    <img src="{{ $imgSrc3 }}" class="responsive-img" >
    <img src="{{ $imgSrc4 }}" class="responsive-img" >
</body>
</html>
