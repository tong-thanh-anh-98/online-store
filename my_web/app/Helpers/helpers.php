<?php

use App\Models\Category;

/**
 * Get the categories with their subcategories.
 *
 * @return \Illuminate\Database\Eloquent\Collection The collection of categories with subcategories.
 */
function getCategories()
{
    return Category::orderBy('name','ASC')
        ->with('sub_category')
        ->orderBy('id','DESC')
        ->where('status',1)
        ->where('showHome','Yes')
        ->get();
}