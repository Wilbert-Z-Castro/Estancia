<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Anuncios y ponencias</title>
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
            width: 15%;
        }
        th:nth-child(2), td:nth-child(2) { /* Medios de contacto */
            width: 6%;
        }
        th:nth-child(3), td:nth-child(3) { /* Empresa Actual */
            width: 9%;
        }
        th:nth-child(4), td:nth-child(4) { /* Año de Egreso */
            width: 15%;
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
        {{$datos['datosDirector']->user->name}} {{$datos['datosDirector']->user->ApellidoP}} {{$datos['datosDirector']->user->ApellidoM}}<br>
        {{$datos['datosDirector']->user->email}}<br>
        {{$datos['datosDirector']->user->Telefono}}<br>
        Fecha de asignación de cargo: {{$datos['datosDirector']->FechaAsignacion}}<br>
        Sexo: {{$datos['datosDirector']->user->Sexo}}<br>


    </p>
    <h2>Datos de ponencias y anuncios anuales anuales</h2>
    <table class="table">
        <thead>
                <tr>
                    <th style="width: 50%;">Número de Anuncio publicados</th>
                    <th style="width: 50%;">Número de Ponencias creadas </th>
                </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{{$datos['numeroDeAnuncios']}}</td>
                    <td>{{$datos['numeroDePonencias']}}</td>
                </tr>
        </tbody>

    </table>
    <h2>Invitaciones a Ponencias:</h2>
    @foreach($datos['informacionPonencias'] as $ponencia)
        <table class="table">
            <thead>
                <tr>
                    <th>Título ponencia</th>
                    <th>Fecha y hora</th>
                    <th>Lugar</th>
                    <th>Invitados</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$ponencia->TituloPonencia}}</td>
                    <td>{{$ponencia->Fecha}}</td>
                    <td>{{$ponencia->Lugar}}</td>
                    <td>
                        @foreach($ponencia->egresados as $invitado)
                            <table >
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Estado</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>

                                            {{$invitado->user->name}}
                                            {{$invitado->user->ApellidoP}}
                                            {{$invitado->user->ApellidoM}}
                                        </td>
                                        <td>{{$invitado->pivot->Estado}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach
    <h2>Egresados con mayor asistencia a ponencias:</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Número de asistencia</th>
                
            </tr>
        </thead>
        @foreach($datos['egresados'] as $egresados)
        <tbody>
                <tr>
                    <td>
                        {{$egresados->name}}
                        {{$egresados->ApellidoP}}
                        {{$egresados->ApellidoM}}
                    </td>
                    <td>
                        {{$egresados->total}}
                    </td>
                    
                </tr>
            </tbody>
        @endforeach
    </table>

    <h2>Anuncios:</h2>
    @foreach($datos['informacionAnuncios'] as $anuncio)
        <table class="table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Fecha de publicación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        {{$anuncio->Titulo}}
                    </td>
                    <td>
                        {{$anuncio->categoria->Nombre}}
                    </td>
                    <td>
                        {{\Carbon\Carbon::parse($anuncio->created_at)->format('Y-m-d')}}
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach





</body>
</html>
