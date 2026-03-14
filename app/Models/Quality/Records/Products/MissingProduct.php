<?php

namespace App\Models\Quality\Records\Products;

use App\Models\Product;
use App\Models\PurchaseItem;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissingProduct extends Model
{
    use HasFactory;

    protected $table = 'missing_products';

    protected $fillable = [
        'team_id',
        'user_id',
        'product_id',
        'is_selected',
        'requested_by_user',
        'stock_status',
        'purchase_item_id',
    ];

    protected $casts = [
        'is_selected' => 'boolean',
        'requested_by_user' => 'boolean',
    ];

    public const STOCK_STATUS_IN_STOCK = 'in_stock';
    public const STOCK_STATUS_OUT_OF_STOCK = 'out_of_stock';
    public const STOCK_STATUS_UNKNOWN = 'unknown';

    public static function getStockStatuses(): array
    {
        return [
            self::STOCK_STATUS_IN_STOCK => 'Con existencias',
            self::STOCK_STATUS_OUT_OF_STOCK => 'Sin existencias',
            self::STOCK_STATUS_UNKNOWN => 'No definido',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function producto(): BelongsTo
    {
        return $this->product();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function purchaseItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseItem::class);
    }

    public function scopeClassA($query)
    {
        return $query->where('is_selected', true);
    }

    public function scopeClassB($query)
    {
        return $query->where('is_selected', false)
            ->where('requested_by_user', true);
    }

    public function scopeForSelectionIndicator($query)
    {
        return $query->where('requested_by_user', true)
            ->where('stock_status', self::STOCK_STATUS_OUT_OF_STOCK)
            ->where('is_selected', false);
    }

    public function scopeForAcquisitionIndicator($query)
    {
        return $query->where('requested_by_user', true)
            ->where('stock_status', self::STOCK_STATUS_OUT_OF_STOCK)
            ->where('is_selected', true);
    }

    public function scopeOpen($query)
    {
        return $query->whereNull('purchase_item_id');
    }

    public function getMissingClassAttribute(): ?string
    {
        if ($this->is_selected) {
            return 'A';
        }

        if ($this->requested_by_user) {
            return 'B';
        }

        return null;
    }
}
