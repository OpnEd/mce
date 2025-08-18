<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Caracterización de {{ $processName }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pdf/process-charact.css') }}">
</head>
<body>
<table class="border-all">
    <thead class="border-all">
        <tr>
            <th class="border-all" rowspan="3"><img src="{{ asset('images/logo.png') }}" alt="Droguería {{ config('app.name') }}"></th>
            <th class="border-all" colspan="4" rowspan="2">{{ $processName }}</th>
            <th class="border-all">Código: {{ $processTypeCode .'-'. $processCode .'-'. $processId }}</th>
        </tr>
        <tr>
            <th class="border-all">Versión: 1</th>
        </tr>
        <tr>
            <th class="border-all" colspan="4">{{ $documentType }}</th>
            <th class="border-all">Vigencia: {{ $processValidity }}</th>
        </tr>
    </thead>
        <tbody>
            <tr class="border-all">
                <td class="border-all td-label">Responsable:</td>
                <td class="border-all" colspan="5">{{ $processResponsible }}</td>
            </tr>
            <tr class="border-all">
                <td class="border-all td-label">Alcance:</td>
                <td class="border-all" colspan="5">{{ $processDescription }}</td>
            </tr>
            <tr class="border-all">
                <td class="border-all td-label">Proveedor</td>
                <td class="border-all td-label">Entradas</td>
                <td class="border-all td-label" colspan="2">Procedimientos</td>
                <td class="border-all td-label">Salidas</td>
                <td class="border-all td-label">Clientes</td>
            </tr>
            <tr class="border-all">
                <td class="border-all">{{ $processSuppliers }}</td>
                <td class="border-all">{{ $processInputs }}</td>
                <td class="border-all" colspan="2">{{ $processProcedures }}</td>
                <td class="border-all">{{ $processOutputs }}</td>
                <td class="border-all">{{ $processClients }}</td>
            </tr>
            <tr class="border-all">
                <td class="border-all">Registros:</td>
                <td class="border-all" colspan="5">
                    <ul>
                    @foreach ($processRecords as $processRecord)
                        <li>{{ $processRecord }}</li>
                    @endforeach
                    </ul>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="border-all td-label" colspan="2">Elaborado por: </td>
                <td class="border-all td-label" colspan="2">Revisado por: </td>
                <td class="border-all td-label" colspan="2">Aprobado por: </td>
            </tr>
            <tr>
                <td class="border-all" colspan="2">Nombre: </td>
                <td class="border-all" colspan="2">Nombre:  </td>
                <td class="border-all" colspan="2">Nombre: </td>
            </tr>
            <tr>
                <td class="border-all" colspan="2">Cargo: {{ $processResponsible }}</td>
                <td class="border-all" colspan="2">Cargo: {{ $processResponsible }}</td>
                <td class="border-all" colspan="2">Cargo: {{ $processResponsible }}</td>
            </tr>
            <tr>
                <td class="border-all" colspan="2">Firma </td>
                <td class="border-all" colspan="2">Firma  </td>
                <td class="border-all" colspan="2">Firma</td>
            </tr>
        </tfoot>
</table>
