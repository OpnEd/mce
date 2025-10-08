<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Cleaning extends Cluster
{    
    protected static ?string $navigationGroup = 'Configuración de plataforma';
    protected static ?string $navigationLabel = 'Limpieza y Desinfección';
    protected static ?string $clusterBreadcrumb = 'Limpieza';
}
