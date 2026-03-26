<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Matriz de Riesgos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1f2937; }
        h1 { font-size: 18px; margin: 0 0 4px 0; }
        h2 { font-size: 12px; margin: 0; font-weight: normal; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 12px; font-size: 10px; font-weight: 600; }
        .bajo { background: #d1fae5; color: #065f46; }
        .medio { background: #fef3c7; color: #92400e; }
        .alto { background: #fee2e2; color: #991b1b; }
        .critico { background: #dbeafe; color: #1e3a8a; }
        .meta { margin-top: 6px; font-size: 10px; color: #6b7280; }
    </style>
</head>
<body>
    <h1>Matriz de riesgos</h1>
    <h2>{{ $team->name }} @if($process) - {{ $process->name }} @endif</h2>
    <div class="meta">
        Generado: {{ $generated_at }} | Por: {{ $generated_by }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 14%;">Proceso</th>
                <th style="width: 12%;">Actividad</th>
                <th style="width: 18%;">Riesgo</th>
                <th style="width: 16%;">Controles</th>
                <th style="width: 6%;">P</th>
                <th style="width: 6%;">I</th>
                <th style="width: 6%;">P x I</th>
                <th style="width: 8%;">Nivel</th>
                <th style="width: 7%;">Residual</th>
                <th style="width: 7%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($risks as $risk)
                <tr>
                    <td>{{ $risk->process?->name ?? '-' }}</td>
                    <td>{{ $risk->activity ?? '-' }}</td>
                    <td>{{ $risk->title }}</td>
                    <td>{{ $risk->existing_controls ?? '-' }}</td>
                    <td>{{ $risk->probability ?? '-' }}</td>
                    <td>{{ $risk->impact ?? '-' }}</td>
                    <td>{{ $risk->risk_score ?? '-' }}</td>
                    <td>
                        @if($risk->risk_level)
                            <span class="badge {{ $risk->risk_level }}">
                                {{ $risk->riskLevelLabel($risk->risk_level) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($risk->residual_score)
                            {{ $risk->residual_score }} ({{ $risk->riskLevelLabel($risk->residual_level) }})
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $risk->status)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; color: #6b7280;">No hay riesgos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
