<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetTemplateLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_template_id',
        'material_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
        'order',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'order' => 'integer',
    ];

    // Relación con material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Relación con plantilla
    public function template()
    {
        return $this->belongsTo(BudgetTemplate::class, 'budget_template_id');
    }
}