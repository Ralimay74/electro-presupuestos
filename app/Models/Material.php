<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name', 'description', 'price', 'category', 'stock'
    ];
    
    public function budgetLines(): HasMany
    {
        return $this->hasMany(BudgetLine::class);
    }
}