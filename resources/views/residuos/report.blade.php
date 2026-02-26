<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Informe Residuos {{ $year }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f5f5f5; }
        h1 { font-size: 18px; }
    </style>
</head>
<body>
    <h1>Informe de residuos — {{ $year }}</h1>
    <p>Generado: {{ now()->toDateTimeString() }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</body>
</html>
