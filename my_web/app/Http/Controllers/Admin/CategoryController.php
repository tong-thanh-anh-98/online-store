<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display the list of categories in the admin panel.
     *
     * @param  \Illuminate\Http\Request  $request The request object.
     * @return \Illuminate\Contracts\View\View The admin category list view.
     */
    public function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%'.$request->get('keyword').'%');
        }
        $categories = $categories->paginate(10);

        return view('admin.category.list', compact('categories'));
    }
    
    /**
     * Display the category creation form in the admin panel.
     *
     * @return \Illuminate\Contracts\View\View The admin category creation form view.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $category = new Category;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            session()->flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
