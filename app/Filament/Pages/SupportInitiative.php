<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class SupportInitiative extends Page
{
    protected static ?string $navigationGroup = 'Configuración de plataforma';

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Sostener esta iniciativa';

    protected static ?string $title = 'Sostener esta iniciativa';

    protected static ?int $navigationSort = 99;

    protected static ?string $slug = 'sostener-iniciativa';

    protected static string $view = 'filament.pages.support-initiative';

    public function getSubheading(): ?string
    {
        return 'La app sigue siendo gratuita. Si te está ayudando, puedes apoyarla para que continúe creciendo.';
    }

    public function getMaxContentWidth(): MaxWidth | string | null
    {
        return MaxWidth::SevenExtraLarge;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('website')
                ->label('Ver opciones en la web')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url($this->websiteUrl())
                ->openUrlInNewTab(),
            Action::make('whatsapp')
                ->label('Apoyar por WhatsApp')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('success')
                ->url($this->whatsAppUrl('Hola, quiero apoyar la iniciativa de Droguería Digital. ¿Qué opciones me recomiendas para mi droguería?'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'websiteUrl' => $this->websiteUrl(),
            'whatsAppUrl' => $this->whatsAppUrl('Hola, quiero apoyar la iniciativa de Droguería Digital. ¿Qué opciones me recomiendas para mi droguería?'),
            'impactItems' => [
                [
                    'title' => 'Mejoras continuas',
                    'description' => 'Nuevas funciones y ajustes que hacen la operación diaria más simple.',
                    'icon' => 'heroicon-o-sparkles',
                ],
                [
                    'title' => 'Soporte cercano',
                    'description' => 'Tiempo real para acompañar dudas, resolver errores y orientar mejor el uso.',
                    'icon' => 'heroicon-o-lifebuoy',
                ],
                [
                    'title' => 'Estabilidad',
                    'description' => 'Mantener la plataforma disponible, usable y lista para más droguerías.',
                    'icon' => 'heroicon-o-shield-check',
                ],
                [
                    'title' => 'Capacitación',
                    'description' => 'Espacios de formación para que tu equipo aproveche mejor la herramienta.',
                    'icon' => 'heroicon-o-academic-cap',
                ],
            ],
            'supportOptions' => [
                [
                    'title' => 'Acompañamiento de implementación',
                    'description' => 'Te ayudamos a dejar la plataforma organizada para tu droguería desde el principio.',
                    'audience' => 'Ideal para equipos que quieren empezar con más claridad.',
                    'cta' => 'Quiero acompañamiento',
                    'url' => $this->whatsAppUrl('Hola, quiero apoyar la iniciativa con acompañamiento de implementación para mi droguería.'),
                ],
                [
                    'title' => 'Capacitación del equipo',
                    'description' => 'Entrenamos a tu personal para que use mejor la plataforma y sus procesos.',
                    'audience' => 'Ideal para droguerías que quieren ordenar el uso interno.',
                    'cta' => 'Quiero capacitación',
                    'url' => $this->whatsAppUrl('Hola, quiero apoyar la iniciativa con una capacitación para mi equipo.'),
                ],
                [
                    'title' => 'Soporte prioritario',
                    'description' => 'Recibe un acompañamiento más cercano mientras ayudas a sostener el proyecto.',
                    'audience' => 'Ideal para quienes quieren respuestas más rápidas y continuidad.',
                    'cta' => 'Quiero soporte',
                    'url' => $this->whatsAppUrl('Hola, quiero apoyar la iniciativa con soporte prioritario para mi droguería.'),
                ],
                [
                    'title' => 'Aporte solidario',
                    'description' => 'Si no necesitas un servicio ahora, también puedes apoyar la continuidad de la app.',
                    'audience' => 'Ideal para usuarios que quieren sostener la iniciativa de forma voluntaria.',
                    'cta' => 'Quiero aportar',
                    'url' => $this->whatsAppUrl('Hola, quiero hacer un aporte solidario para apoyar la iniciativa de Droguería Digital.'),
                ],
            ],
            'trustPoints' => [
                'La app puede seguir usándose gratis.',
                'Apoyar es completamente voluntario.',
                'No bloqueamos funciones base para forzar compras.',
                'Tu compra ayuda a sostener mejoras, soporte y continuidad.',
            ],
        ];
    }

    protected function websiteUrl(): string
    {
        return 'https://drogueriadigital.net.co';
    }

    protected function whatsAppUrl(string $message): string
    {
        return 'https://wa.me/573143095251?text=' . urlencode($message);
    }
}
