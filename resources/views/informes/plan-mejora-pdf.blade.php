<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Plan de mejora</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 4px 0; }
        h2 { font-size: 12px; margin: 0; font-weight: normal; color: #6b7280; }
        h3 { font-size: 12px; margin: 14px 0 6px; color: #111827; }
        .meta { margin-top: 6px; font-size: 10px; color: #6b7280; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .grid td { padding: 6px 8px; border: 1px solid #e5e7eb; vertical-align: top; }
        .label { font-weight: 600; color: #374151; width: 22%; }
        table.list { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.list th, table.list td { border: 1px solid #e5e7eb; padding: 6px 8px; }
        table.list th { background: #f3f4f6; text-align: left; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .danger { background: #fee2e2; color: #991b1b; }
        .warning { background: #fef3c7; color: #92400e; }
        .info { background: #e0f2fe; color: #075985; }
        .primary { background: #dbeafe; color: #1e3a8a; }
        .success { background: #d1fae5; color: #065f46; }
        .gray { background: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <h1>Plan de mejora</h1>
    <h2>{{ $plan->title }}</h2>
    <div class="meta">
        Generado: {{ $generated_at }} | Por: {{ $generated_by }}
    </div>

    <table class="grid">
        <tr>
            <td class="label">Equipo</td>
            <td>{{ $plan->team?->name ?? '-' }}</td>
            <td class="label">Estado</td>
            @php
                $statusValue = $plan->status instanceof \App\Enums\ImprovementPlanStatus
                    ? $plan->status->value
                    : (string) $plan->status;
                $statusColor = \App\Enums\ImprovementPlanStatus::tryFrom($statusValue)?->color() ?? 'gray';
            @endphp
            <td>
                <span class="badge {{ $statusColor }}">{{ $statusLabel ?? $statusValue }}</span>
            </td>
        </tr>
        <tr>
            <td class="label">Proceso</td>
            <td>{{ $plan->checklistItemAnswer?->checklistItem?->checklist?->process?->name ?? '-' }}</td>
            <td class="label">Fecha limite</td>
            <td>{{ $plan->ends_at?->format('d/m/Y') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Plan de auditoria</td>
            <td>{{ $plan->checklistItemAnswer?->checklistItem?->checklist?->title ?? '-' }}</td>
            <td class="label">Responsable</td>
            <td>{{ $plan->checklistItemAnswer?->user?->name ?? '-' }}</td>
        </tr>
    </table>

    <h3>Objetivo</h3>
    <div>{{ $plan->objective }}</div>

    <h3>Descripcion</h3>
    <div>{{ $plan->descripcion }}</div>

    <h3>Hallazgo que origina el plan</h3>
    <table class="grid">
        <tr>
            <td class="label">Requerimiento</td>
            <td>{{ $plan->checklistItemAnswer?->checklistItem?->description ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Observaciones</td>
            <td>{{ $plan->checklistItemAnswer?->observations ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Cumple</td>
            <td>{{ $plan->checklistItemAnswer?->meets ? 'Si' : 'No' }}</td>
        </tr>
    </table>

    <h3>Tareas</h3>
    <table class="list">
        <thead>
            <tr>
                <th>Descripcion</th>
                <th>Responsable</th>
                <th>Fecha limite</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plan->tasks as $task)
                <tr>
                    <td>{{ $task->description ?? '-' }}</td>
                    <td>{{ $task->user?->name ?? '-' }}</td>
                    <td>{{ $task->ends_at?->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ $taskStatusLabels[$task->status?->value ?? (string) $task->status] ?? (string) $task->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#6b7280;">No hay tareas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
