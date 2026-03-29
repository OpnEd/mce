<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $certificateNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', serif;
            background: white;
            width: 100%;
            height: 100%;
        }

        .certificate {
            position: relative;
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 40px;
        }

        .certificate-content {
            position: relative;
            width: 100%;
            height: 600px;
            background: white;
            border: 3px solid #2c3e50;
            border-radius: 20px;
            padding: 60px 80px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-image: 
                linear-gradient(90deg, transparent 1px, rgba(44, 62, 80, 0.03) 1px),
                linear-gradient(transparent 1px, rgba(44, 62, 80, 0.03) 1px);
            background-size: 50px 50px;
        }

        .certificate-header {
            margin-bottom: 30px;
        }

        .logo-area {
            font-size: 14px;
            color: #7f8c8d;
            letter-spacing: 3px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .certificate-title {
            font-size: 48px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 20px;
            font-style: italic;
        }

        .divider {
            width: 150px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #3498db, transparent);
            margin: 30px auto;
        }

        .certificate-body {
            margin: 40px 0;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .recipient-label {
            font-size: 16px;
            color: #7f8c8d;
            margin-bottom: 15px;
            letter-spacing: 2px;
            font-weight: 600;
        }

        .recipient-name {
            font-size: 42px;
            color: #2980b9;
            font-weight: bold;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .achievement-text {
            font-size: 16px;
            color: #34495e;
            line-height: 1.8;
            margin-bottom: 30px;
            font-style: italic;
        }

        .course-title {
            font-size: 28px;
            color: #2c3e50;
            font-weight: bold;
            margin-top: 20px;
        }

        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            margin-top: 50px;
            border-top: 1px solid #ecf0f1;
            padding-top: 30px;
        }

        .signature-area {
            width: 200px;
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #2c3e50;
            margin-bottom: 10px;
        }

        .signature-name {
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 600;
        }

        .date-area {
            text-align: center;
        }

        .certificate-number {
            font-size: 11px;
            color: #95a5a6;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .seal-area {
            width: 150px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
        }

        .seal {
            width: 120px;
            height: 120px;
            border: 3px solid #e74c3c;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .seal-text {
            font-size: 10px;
            color: #7f8c8d;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .score-badge {
            display: inline-block;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0;
        }

        @media print {
            body {
                background: none;
            }

            .certificate {
                background: white;
                padding: 0;
            }

            .certificate-content {
                box-shadow: none;
                border: 2px solid #2c3e50;
            }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="certificate-content">
            <!-- Header -->
            <div class="certificate-header">
                <div class="logo-area">{{ $issuer }}</div>
                <div class="certificate-title">CERTIFICADO DE FINALIZACIÓN</div>
                <div class="divider"></div>
            </div>

            <!-- Body -->
            <div class="certificate-body">
                <div class="recipient-label">Otorgado a:</div>
                <div class="recipient-name">{{ strtoupper($recipientName) }}</div>

                <div class="achievement-text">
                    Por haber completado exitosamente el programa de capacitación
                </div>

                <div class="course-title">{{ $courseName }}</div>

                @if ($finalScore)
                    <div class="score-badge">
                        Puntuación Final: {{ number_format($finalScore, 2) }}%
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="certificate-footer">
                <!-- Signature -->
                <div class="signature-area">
                    <div class="signature-line"></div>
                    <div class="signature-name">Director Académico</div>
                </div>

                <!-- Date -->
                <div class="date-area">
                    <div style="font-size: 14px; color: #2c3e50; margin-bottom: 15px;">
                        <strong>{{ $issuedDate->locale('es')->isoFormat('dddd, D MMMM YYYY') }}</strong>
                    </div>
                    <div class="certificate-number">
                        Certificado No. {{ $certificateNumber }}
                    </div>
                </div>

                <!-- Seal -->
                <div class="seal-area">
                    <div class="seal">✓</div>
                    <div class="seal-text">CERTIFIED</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
