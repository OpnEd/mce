<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Específico para Droguerías -->
    <title>{{ $title ?? 'Software para Droguerías - Evita Multas INVIMA | Ahorra 15 Horas/Semana' }}</title>
    <meta name="description"
        content="Software especializado para droguerías pequeñas. Cumple Resolución 1403 de 2007, evita multas de la Secreataría de Salud, controla vencimientos. Demo gratis hoy.">
    <meta name="keywords"
        content="software droguería, Secretaría de Salud, Resolución 1403 de 2007, multas, control vencimientos, facturación droguería, inventario farmacia, gestión de calidad">

    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="Evita Multas INVIMA - Software para Droguerías">
    <meta property="og:description"
        content="Software que cumple todas las normas. Controla inventario, evita vencidos, factura correctamente.">
    <meta property="og:image" content="{{ asset('images/og-drogueria.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Structured Data para Google -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "SoftwareApplication",
        "name": "PQM - Pharmaceutical Quality Management",
        "applicationCategory": "BusinessApplication",
        "description": "Software de gestión especializado para droguerías pequeñas y medianas",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "89000",
            "priceCurrency": "COP",
            "priceValidUntil": "2025-12-31"
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.8",
            "ratingCount": "127"
        }
    }
    </script>

    <!-- Fonts optimizadas -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e'
                        },
                        success: {
                            50: '#f0fdf4',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d'
                        },
                        warning: {
                            50: '#fffbeb',
                            500: '#f59e0b',
                            600: '#d97706'
                        },
                        danger: {
                            50: '#fef2f2',
                            500: '#ef4444',
                            600: '#dc2626'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js para interactividad -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles

    <!-- WhatsApp Integration -->
    <style>
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .whatsapp-float:hover {
            text-decoration: none;
            color: #FFF;
            background-color: #128c7e;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/573001234567?text=Hola!%20Quiero%20información%20sobre%20el%20software%20para%20mi%20droguería"
        target="_blank" class="whatsapp-float" title="¿Tienes preguntas? Escríbenos por WhatsApp">
        <i class="fab fa-whatsapp" style="margin-top: 16px;"></i>
        <svg class="w-8 h-8 mx-auto mt-4" fill="currentColor" viewBox="0 0 24 24">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
        </svg>
    </a>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts

    <!-- Analytics y Tracking -->
    <script>
        // Configuración de eventos de conversión
        document.addEventListener('livewire:load', function() {
            Livewire.on('conversion-completed', (data) => {
                console.log('Conversión completada:', data);

                // Google Analytics 4
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'generate_lead', {
                        'currency': 'COP',
                        'value': data.value,
                        'lead_type': 'drogueria',
                        'problema_principal': data.problema
                    });
                }

                // Meta Pixel
                if (typeof fbq !== 'undefined') {
                    fbq('track', 'Lead', {
                        value: data.value,
                        currency: 'COP',
                        content_category: 'drogueria_software'
                    });
                }
            });
        });

        // Tracking de scroll para medir engagement
        let scrolled25 = false,
            scrolled50 = false,
            scrolled75 = false;

        window.addEventListener('scroll', function() {
            const scrollPercent = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;

            if (scrollPercent > 25 && !scrolled25) {
                scrolled25 = true;
                gtag && gtag('event', 'scroll', {
                    percent_scrolled: 25
                });
            }
            if (scrollPercent > 50 && !scrolled50) {
                scrolled50 = true;
                gtag && gtag('event', 'scroll', {
                    percent_scrolled: 50
                });
            }
            if (scrollPercent > 75 && !scrolled75) {
                scrolled75 = true;
                gtag && gtag('event', 'scroll', {
                    percent_scrolled: 75
                });
            }
        });
    </script>

    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>

</html>
