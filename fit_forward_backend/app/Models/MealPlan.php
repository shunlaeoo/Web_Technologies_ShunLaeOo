<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = ['bmi_category_id', 'protein', 'carbs', 'fats', 'description'];

    public function bmi_category()
    {
        return $this->belongsTo(BmiCategory::class);
    }
}
