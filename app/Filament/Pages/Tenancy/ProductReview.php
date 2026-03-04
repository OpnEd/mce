<?php

namespace App\Filament\Pages\Tenancy;

use App\Filament\Pages\BaseSectionPage;

class ProductReview extends  BaseSectionPage
{
    public const NAVIGATION_SORT = 8;
    public const NAVIGATION_LABEL = '8. Revisión de productos';
    public const SLUG = 'revision-productos';
    public const VIEW = 'filament.pages.product-review';
    public const SECTION = 'Revisión de productos';
}
