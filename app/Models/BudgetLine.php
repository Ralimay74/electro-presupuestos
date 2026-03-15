<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetLine extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'budget_id', 'material_id', 'description', 'quantity', 'unit_price', 'subtotal'
    ];
    
    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];
    
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }
    
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
    
    // Calcular subtotal automáticamente antes de guardar
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($line) {
            $line->subtotal = round($line->quantity * $line->unit_price, 2);
        });
    }
}