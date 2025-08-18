<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $document->title }}</title>
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
            /* Margen en las cuatro direcciones */
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
            padding: 5;
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
    </style>

</head>

<body>
    <div id="header">
        <table>
            <tbody>
                <tr>
                    <!-- Spanning the first column across three rows -->
                    <td rowspan="3"><img src="{{ Storage::url('logo-pqm.png') }}" alt="Droguería {{ $company->name }}">
                    </td>
                    <td rowspan="2" class="titulo-celda">
                        <p>{{ $company->name }}</p>
                        <p>{{ $label }}</p>
                        <h3>{{ $document->title }}</h3>
                    </td>
                    <td>
                        Código: <b>{{ $documentCode }}</b>
                    </td>
                </tr>
                <tr>
                    <td>Versión: <b>{{ $document->data['version'] }}</b></td>
                </tr>
                <tr>
                    <td class="titulo-celda">Perteneciente al <b>Proceso</b> de: <b>{{ $document->process->name }}</b>
                    </td>
                    <td>Vigencia: <b>{{ $document->vigencia_formatted ?? 'N/A' }}</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <footer>
        <div class="textFooter">
            @if ($company != null)
                {{ 'Droguería ' . $company->name . ' | ' . $company->adress . ' | ' . $company->email }}
            @else
                <p>Servicio a la comunidad...!</p>
            @endif
        </div>
    </footer>
    <main>
        <div class="content">
            <h2>1. Objetivo</h2>
            <p>{{ $document->objective }}</p>
            <h2>2. Alcance</h2>
            <p>{{ $document->scope }}</p>
            <h2>3. Rererencias Normativas</h2>
            <ul>
                @foreach ($document->references as $ref)
                    <li>{{ $ref['title'] }}</li>
                @endforeach
            </ul>
            <h2>4. Términos y Definiciones</h2>
            <ul>
                @foreach ($document->terms as $term)
                    <li>{{ $term['definition'] }}</li>
                @endforeach
            </ul>
            <h2>5. Responsabilidades</h2>
            <ul>
                @foreach ($document->responsibilities as $resp)
                    <li>{{ $resp['responsibility'] }}</li>
                @endforeach
            </ul>
            <h2>6. Registros</h2>
            <ul>
                @foreach ($document->records as $rec)
                    <li>{{ $rec['record'] }}</li>
                @endforeach
            </ul>
            <h2>7. Procedimiento</h2>

            <table>
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th>Descripción</th>
                        <th>Responsable</th>
                        <th>Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($document->procedure as $step)
                        <tr>
                            <td>{{ $step['activity'] }}</td>
                            <td>{{ $step['description'] }}</td>
                            <td>{{ $step['responsible'] }}</td>
                            <td>{{ $step['records'] }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="titulo-celda">
                            <h3>Fin del procedimiento</h3>
                        </td>
                </tbody>
            </table>

            <h2>8. Anexos</h2>
            <ul>
                @foreach ($document->annexes as $annexe)
                    <li>{{ $annexe['annexe'] }}</li>
                @endforeach
            </ul><h2>9. Firmas</h2>
            <table>
                <thead>
                    <tr>
                        <th><strong>Preparado por:</strong></th>
                        <th><strong>Revisado por:</strong></th>
                        <th><strong>Aprobado por:</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @if (!empty($preparerSignature))
                                <div class="signature">
                                    <img src="{{ $preparerSignature }}" alt="Firma de elaborador"
                                        style="max-width: 200px; height: auto;">
                                </div>
                            @else
                                <p><em>Firma no disponible</em></p>
                            @endif
                        </td>
                        <td>
                            @if (!empty($reviewerSignature))
                                <div class="signature">
                                    <img src="{{ $reviewerSignature }}" alt="Firma de revisor"
                                        style="max-width: 200px; height: auto;">
                                </div>
                            @else
                                <p><em>Firma no disponible</em></p>
                            @endif
                        </td>
                        <td>
                            @if (!empty($approverSignature))
                                <div class="signature">
                                    <img src="{{ $approverSignature }}" alt="Firma de aprobador"
                                        style="max-width: 200px; height: auto;">
                                </div>
                            @else
                                <p><em>Firma no disponible</em></p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>{{ $preparer }}</td>
                        <td>{{ $reviewer }}</td>
                        <td>{{ $approver }}</td>
                    </tr>
                    <tr>
                        <td><em>
                                (Cargo:
                                {{ $preparerRole }})
                            </em></td>
                        <td><em>
                                (Cargo:
                                {{ $reviewerRole }})
                            </em></td>
                        <td><em>
                                (Cargo:
                                {{ $approverRole }})
                            </em></td>
                    </tr>
                </tbody>
            </table>

            <h2>10. Historial de cambios</h2>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cambios</th>
                        <th>Responsable</th>
                        <th>Comentario</th>
                        <th>Versión</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Fila inicial fija --}}
                    <tr>
                        <td>0</td>
                        <td>{{ $document->created_at->format('d-m-Y H:i') }}</td>
                        <td>Documento original</td>
                        <td>N.A.</td>
                        <td>N.A.</td>
                        <td>{{ $document->data['version'] ?? '1.0' }}</td>
                    </tr>

                    {{-- Itera sobre cada versión registrada --}}
                    @foreach ($versions as $i => $version)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $version->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                @foreach ($version->changes as $field => $change)
                                    @php
                                        // Preparamos old y new
                                        $old = $change['old'];
                                        $newRaw = $change['new'];

                                        // Si es el campo records, el new viene como JSON string → decodifícalo
                                        if ($field === 'records' && is_string($newRaw)) {
                                            $new = json_decode($newRaw, true);
                                        } else {
                                            $new = $newRaw;
                                        }
                                    @endphp

                                    <strong>{{ ucfirst($field) }}:</strong>
                                    <div style="margin-left: 1em;">
                                        <em>Anterior:</em>
                                        @if (is_array($old))
                                            <ul>
                                                @foreach ($old as $item)
                                                    <li>{{ $item['record'] ?? json_encode($item) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ $old }}
                                        @endif

                                        <em>Nuevo:</em>
                                        @if (is_array($new))
                                            <ul>
                                                @foreach ($new as $item)
                                                    <li>{{ $item['record'] ?? json_encode($item) }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            {{ $new }}
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ $version->user->name ?? '–' }}</td>
                            <td>{{ $version->comment ?: '–' }}</td>
                            <td>{{ $version->document->data['version'] ?? '–' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
<hr>
            
        </div>
    </main>
    <script type="text/php">
    if(isset($pdf)){
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(500, 20, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }
</script>
</body>

</html>
