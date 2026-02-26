<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Gestión de Residuos</title>
    <style>
        @page {
            margin: 2.5cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
            font-weight: normal;
            margin: 5px 0 0 0;
        }
        .date-section {
            text-align: right;
            margin-bottom: 30px;
        }
        .recipient-section {
            margin-bottom: 30px;
            font-weight: bold;
        }
        .subject-section {
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .content {
            text-align: justify;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }
        td:first-child {
            text-align: left;
        }
        .total-row td {
            font-weight: bold;
            background-color: #e0e0e0;
        }
        .signature-section {
            margin-top: 80px;
        }
        .signature-line {
            width: 250px;
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8pt;
            text-align: center;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INFORME DE GESTIÓN DE RESIDUOS HOSPITALARIOS Y SIMILARES</h1>
        <h2>Periodo: Año {{ $informe->anio }}</h2>
    </div>

    <div class="date-section">
        Bogotá D.C., {{ $fecha_actual }}
    </div>

    <div class="recipient-section">
        Señores<br>
        SECRETARÍA DISTRITAL DE SALUD DE BOGOTÁ<br>
        Subdirección de Vigilancia en Salud Pública<br>
        Ciudad
    </div>

    <div class="subject-section">
        REF: REPORTE CONSOLIDADO DE GENERACIÓN DE RESIDUOS - AÑO {{ $informe->anio }}
    </div>

    <div class="content">
        <p>Cordial saludo,</p>
        
        <p>En cumplimiento de la normativa ambiental y sanitaria vigente, presentamos el informe consolidado de la generación de residuos correspondiente al periodo comprendido entre el 1 de enero y el 31 de diciembre de {{ $informe->anio }}.</p>

        @if($informe->descripcion)
            <p><strong>Observaciones Generales:</strong> {{ $informe->descripcion }}</p>
        @endif

        <p>A continuación, se detalla la cuantificación de los residuos generados clasificados por tipo:</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Clasificación del Residuo</th>
                <th>Total Generado (kg)</th>
                <th>Participación (%)</th>
                <th>Promedio Mensual (kg)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Reciclables (No Peligrosos)</td>
                <td>{{ number_format($informe->total_reciclable, 2, ',', '.') }}</td>
                <td>{{ number_format($porcentajes['reciclable'], 2, ',', '.') }}%</td>
                <td>{{ number_format($promedios['reciclable'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ordinarios e Inertes (No Peligrosos)</td>
                <td>{{ number_format($informe->total_ordinario, 2, ',', '.') }}</td>
                <td>{{ number_format($porcentajes['ordinario'], 2, ',', '.') }}%</td>
                <td>{{ number_format($promedios['ordinario'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biosanitarios / Cortopunzantes (Peligrosos)</td>
                <td>{{ number_format($informe->total_guardian, 2, ',', '.') }}</td>
                <td>{{ number_format($porcentajes['guardian'], 2, ',', '.') }}%</td>
                <td>{{ number_format($promedios['guardian'], 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Anatomopatológicos / Biosanitarios (Peligrosos)</td>
                <td>{{ number_format($informe->total_bolsa_roja, 2, ',', '.') }}</td>
                <td>{{ number_format($porcentajes['bolsa_roja'], 2, ',', '.') }}%</td>
                <td>{{ number_format($promedios['bolsa_roja'], 2, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL GENERAL</td>
                <td>{{ number_format($informe->total_general, 2, ',', '.') }}</td>
                <td>100,00%</td>
                <td>-</td>
            </tr>
        </tbody>
    </table>

    <div class="content">
        <p>El presente informe se expide con base en los registros diarios de generación de residuos consolidados en nuestro sistema de gestión (Informe N° {{ $informe->numero_informe }}).</p>
    </div>

    <div class="signature-section">
        <p>Atentamente,</p>
        <br><br>
        <div class="signature-line"></div>
        <strong>{{ $generado_por }}</strong><br>
        Responsable de Gestión Ambiental / Generador
    </div>

    <div class="footer">
        Generado automáticamente el {{ $fecha_actual }} | Informe N° {{ $informe->numero_informe }}
    </div>
</body>
</html>