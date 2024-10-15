<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Carreras</title>
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
            width: 23%;
        }
        th:nth-child(2), td:nth-child(2) { /* Medios de contacto */
            width: 10%;
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
    <center>
        <img src="{{public_path() . '/img/UPE_vertical.jpg'}}" width="150" height="100" >
    </center>
    <h2>Datos del director</h2>
    <p>
        {{$datosDirector->user->name}} {{$datosDirector->user->ApellidoP}} {{$datosDirector->user->ApellidoM}}<br>
        {{$datosDirector->user->email}}<br>
        {{$datosDirector->user->Telefono}}<br>
        {{$datosDirector->user->Direccion}}<br>
        Fecha de asginacion de cargo: {{$datosDirector->FechaAsignacion}}<br>
        Sexo: {{$datosDirector->user->Sexo}}<br>
    </p>
    <h2>Carreras:</h2>
    @foreach ($carreras as $nombreCarrera => $grupoCarreras)
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre de la carrera</th>
                    <th>Siglas </th>
                    <th>UbicacionOficinas</th>
                    <th>Numero de egresado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grupoCarreras as $carrera)
                    <tr>
                        <td>{{ $carrera->Descripcion }}</td>
                        <td>{{ $nombreCarrera }}</td>
                        <td>{{ $carrera->UbicacionOficinas }}</td>
                        <td>{{ $carrera->egresados_count }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <img src="{{ $imgSrc }}" class="responsive-img" >
    <img src="{{ $imgSrc2 }}" class="responsive-img" >
    <img src="{{ $imgSrc4 }}" class="responsive-img" >





</body>
</html>
