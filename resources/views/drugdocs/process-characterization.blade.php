<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Caracterización de {{ $process->name }}</title>

    <style>
        /* Márgenes de página compatibles con Dompdf */
        @page {
            margin: 30px 15px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 0px;
            z-index: 1000;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 50px 50px 50px 50px;
            margin-top: 40px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table,
        th,
        td {
            border: 1px solid black;
            margin: 0;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        td:nth-child(1) {
            width: 10%;
        }

        td:nth-child(2) {
            width: 70%;
        }

        td:nth-child(3) {
            width: 20%;
        }

        .content {
            padding: 20px;
            margin-bottom: 0;
        }

        td.titulo-celda {
            text-align: center;
        }

        h2.titulo-celda {
            color: indigo;
            margin: 0;
        }

        .hijo {
            width: 2cm;
            height: 1cm;
        }

        footer {
            position: fixed;
            bottom: -30px;
            left: 0px;
            right: 0px;
            height: 50px;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            line-height: 35px;
        }

        .textFooter {
            text-align: center;
            width: 100%;
        }

        ul {
            margin: 0;
            padding-left: 18px;
        }

        ul li {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
@php
    // Normalizar datos para evitar warnings
    $data      = $process->data ?? [];
    $version   = $data['version']  ?? '1.0';
    $vigencia  = $data['vigencia'] ?? 'Por definir';

    $records    = is_array($process->records)    ? $process->records    : (array) $process->records;
    $suppliers  = is_array($process->suppliers)  ? $process->suppliers  : (array) $process->suppliers;
    $inputs     = is_array($process->inputs)     ? $process->inputs     : (array) $process->inputs;
    $procedures = is_array($process->procedures) ? $process->procedures : (array) $process->procedures;
    $outputs    = is_array($process->outputs)    ? $process->outputs    : (array) $process->outputs;
    $clients    = is_array($process->clients)    ? $process->clients    : (array) $process->clients;
@endphp

<div class="content">
    <table>
        <thead>
        <tr>
            <th rowspan="3">
                {{-- Ajusta el logo según cómo lo tengas por tenant --}}
                <img src="{{ asset('images/logo.png') }}"
                     alt="Droguería {{ $tenant->name ?? config('app.name') }}"
                     style="max-height: 60px;">
            </th>
            <th colspan="4" rowspan="2" class="titulo-celda">
                <h2 class="titulo-celda">
                    Caracterización del proceso<br>
                    {{ $process->name }}
                </h2>
            </th>
            <th>
                Código:
                {{ $processTypeCode }}-{{ $process->code }}-{{ $process->id }}
            </th>
        </tr>
        <tr>
            <th>
                Versión: {{ $version }}
            </th>
        </tr>
        <tr>
            <th colspan="4" class="titulo-celda">
                {{ $documentType ?? 'Caracterización de proceso' }}
            </th>
            <th class="titulo-celda">
                Vigencia: {{ $vigencia }}
            </th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td class="titulo-celda">Proceso:</td>
            <td colspan="5">
                {{ $process->code }} - {{ $process->name }}
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Tipo:</td>
            <td colspan="5">
                {{ $processType->name ?? 'Por definir' }}
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Responsable:</td>
            <td colspan="5">
                {{ $processResponsible ?? 'Por definir' }}
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Propósito / Descripción:</td>
            <td colspan="5">
                {{ $process->description ?? 'Por definir' }}
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Alcance:</td>
            <td colspan="5">
                {{-- Si luego agregas un campo alcance, úsalo aquí --}}
                Por definir
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Proveedores:</td>
            <td colspan="5">
                @if(!empty($suppliers))
                    <ul>
                        @foreach($suppliers as $supplier)
                            <li>{{ $supplier }}</li>
                        @endforeach
                    </ul>
                @else
                    <em>Por definir</em>
                @endif
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Entradas:</td>
            <td colspan="5">
                @if(!empty($inputs))
                    <ul>
                        @foreach($inputs as $input)
                            <li>{{ $input }}</li>
                        @endforeach
                    </ul>
                @else
                    <em>Por definir</em>
                @endif
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Actividades / Procedimientos:</td>
            <td colspan="5">
                @if(!empty($procedures))
                    <ul>
                        @foreach($procedures as $procedure)
                            <li>{{ $procedure }}</li>
                        @endforeach
                    </ul>
                @else
                    <em>Por definir</em>
                @endif
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Salidas:</td>
            <td colspan="5">
                @if(!empty($outputs))
                    <ul>
                        @foreach($outputs as $output)
                            <li>{{ $output }}</li>
                        @endforeach
                    </ul>
                @else
                    <em>Por definir</em>
                @endif
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Clientes:</td>
            <td colspan="5">
                @if(!empty($clients))
                    <ul>
                        @foreach($clients as $client)
                            <li>{{ $client }}</li>
                        @endforeach
                    </ul>
                @else
                    <em>Por definir</em>
                @endif
            </td>
        </tr>

        <tr>
            <td class="titulo-celda">Registros asociados:</td>
            <td colspan="5">
                @if(!empty($records))
                    <ul>
                        @foreach($records as $record)
                            <li>{{ $record }}</li>
                        @endforeach
                    </ul>
                @else
                    <em>Por definir</em>
                @endif
            </td>
        </tr>

        {{-- Espacio reservado por si luego agregas indicadores, riesgos, etc. --}}
        <tr>
    <td class="titulo-celda">Indicadores:</td>
    <td colspan="5">
        @if(isset($managementIndicators) && $managementIndicators->isNotEmpty())
            <ul>
                @foreach($managementIndicators as $indicator)
                    <li>
                        <strong>{{ $indicator['name'] }}</strong>
                        @if(!empty($indicator['objective']))
                            – Objetivo: {{ $indicator['objective'] }}
                        @endif
                        @if(!empty($indicator['type']))
                            – Tipo: {{ $indicator['type'] }}
                        @endif
                        @if(!empty($indicator['numerator']) && !empty($indicator['denominator']))
                            – Fórmula: {{ $indicator['numerator'] }} / {{ $indicator['denominator'] }}
                        @endif
                        @if(!empty($indicator['goal_name']))
                            – Meta asociada: {{ $indicator['goal_name'] }}
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <em>Por definir</em>
        @endif
    </td>
</tr>


        <tr>
            <td class="titulo-celda">Riesgos / Oportunidades:</td>
            <td colspan="5">
                Por definir
            </td>
        </tr>

        </tbody>
    </table>
</div>

<footer>
    <div class="textFooter">
        {{ $tenant->name ?? config('app.name') }} -
        Proceso: {{ $process->code }} - {{ $process->name }}
    </div>
</footer>

</body>
</html>
