<?php

namespace App\Models;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * Get the product images associated with this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany The relationship between the current model and the ProductImage model.
     */
    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
