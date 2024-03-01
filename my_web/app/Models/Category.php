<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Get the subcategories associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany The relationship between the current model and the SubCategory model.
     */
    public function sub_category()
    {
        return $this->hasMany(SubCategory::class);
    }
}
