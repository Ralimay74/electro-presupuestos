<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id', 'number', 'date', 'status', 'notes', 'iva_percent', 'total'
    ];
    
    protected $casts = [
        'date' => 'date',
        'iva_percent' => 'decimal:2',
        'total' => 'decimal:2'
    ];
    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    
    public function lines(): HasMany
    {
        return $this->hasMany(BudgetLine::class);
    }
    
    public function calculateTotal(): float
    {
        $subtotal = $this->lines->sum('subtotal');
        $iva = $subtotal * ($this->iva_percent / 100);
        return round($subtotal + $iva, 2);
    }

    

/**
 * Generar token público único
 */
    public function generatePublicToken(): string
   {
    $this->public_token = \Illuminate\Support\Str::random(32);
    $this->is_public = true;
    $this->public_token_expires_at = now()->addDays(30); // Válido por 30 días
    $this->save();
    
    return $this->public_token;
   }

/**
 * Verificar si el token es válido
 */
    public function isPublicTokenValid(): bool
    {
    return $this->is_public 
        && $this->public_token 
        && (!$this->public_token_expires_at || $this->public_token_expires_at->isFuture());
}

/**
 * Obtener URL pública del presupuesto
 */
    public function getPublicUrl(): string
    {
    return route('public.budget.show', $this->public_token);
    }

}