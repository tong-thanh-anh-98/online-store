<?php

namespace App\Http\Controllers\Admin;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductSubCategoryController extends Controller
{
    /**
     * Get the subcategories based on the given category ID.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\Response The response containing the subcategories.
     */
    public function index(Request $request)
    {
        if (!empty($request->category_id)) {
            $subCategories = SubCategory::where('category_id', $request->category_id)
            ->orderBy('name', 'ASC')
            ->get();

            return response()->json([
                'status' => true,
                'subCategories' => $subCategories,
            ]);
        } else {
            return response()->json([
                'status' => true,
                'subCategories' => []
            ]);
        }
    }
}
