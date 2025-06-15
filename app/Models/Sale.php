<?php

namespace App\Models;

use App\Traits\FilterByUser;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    /** @use HasFactory<\Database\Factories\SaleFactory> */
    use HasFactory, SoftDeletes, FilterByUser;

    protected $fillable = [
        'team_id',
        'customer_id',
        'user_id',
        'total',
        'status',
        'code',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    // Relación inversa con cliente
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación uno a uno con factura
    public function factura(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // Relación uno a muchos con FacturaItem
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function generateCode(): string
    {
        $teamId = Filament::getTenant()->id;
        $lastSale = self::where('team_id', $teamId)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastCode = $lastSale ? (int) substr($lastSale->code, -4) : 0;

        return 'S' . str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
    }
    
    public function save(array $options = []): bool
    {
        if (!$this->code) {
            $this->code = $this->generateCode();
        }

        return parent::save($options);
    }

    public static function generateInvoiceNumber(): string
    {
        $lastInvoice = self::where('team_id', auth()->user()->currentTeam->id)
            ->orderBy('created_at', 'desc')
            ->first();

        $lastNumber = $lastInvoice ? (int) substr($lastInvoice->code, -4) : 0;

        return 'INV' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }
    
    public static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (!$sale->code) {
                $sale->code = $sale->generateCode();
            }
        });
    }
    public function scopeFilterByUser($query, $user)
    {
        return $query->where('user_id', $user->id);
    }
    public function scopeFilterByTeam($query, $team)
    {
        return $query->where('team_id', $team->id);
    }
    public function scopeFilterByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function scopeFilterByCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
    public function scopeFilterByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
    public function scopeFilterByCode($query, $code)
    {
        return $query->where('code', 'like', '%' . $code . '%');
    }
    public function scopeFilterByTotal($query, $minTotal, $maxTotal)
    {
        return $query->whereBetween('total', [$minTotal, $maxTotal]);
    }
    
}
