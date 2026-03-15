<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'iva_percent',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'iva_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relación con líneas
    public function lines()
    {
        return $this->hasMany(BudgetTemplateLine::class)->orderBy('order');
    }

    // Método para aplicar la plantilla a un presupuesto
    public function applyToBudget($budget)
    {
        $budget->iva_percent = $this->iva_percent;
        $budget->notes = $this->notes;
        $budget->save();

        // Crear líneas
        foreach ($this->lines as $line) {
            $budget->lines()->create([
                'material_id' => $line->material_id,
                'description' => $line->description,
                'quantity' => $line->quantity,
                'unit_price' => $line->unit_price,
                'subtotal' => $line->subtotal,
            ]);
        }

        return $budget;
    }
}