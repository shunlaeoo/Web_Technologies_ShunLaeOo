<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [ 'title', 'description', 'bmi_category_id' ];

    public function bmi_category()
    {
        return $this->belongsTo(BmiCategory::class);
    }
}
