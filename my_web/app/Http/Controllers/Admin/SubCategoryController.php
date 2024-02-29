<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{

    /**
     * Display a list of subcategories.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\View\View The view for displaying the list of subcategories.
     */
    public function index(Request $request)
    {
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
                        ->latest('sub_categories.id')->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orwhere('categories.name', 'like', '%'.$request->get('keyword').'%');
        }

        $subCategories = $subCategories->paginate(10);

        return view('admin.sub_category.list', compact('subCategories'));
    }    
    
    /**
     * Display the form for creating a new subcategory.
     *
     * @return \Illuminate\View\View The view for creating a new subcategory.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;

        return view('admin.sub_category.create', $data);
    }

    /**
     * Store a newly created subcategory.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'status' => 'required',
            'showHome' => 'required',
        ]);

        if ($validator->passes()) {
            $subCategory = new SubCategory;
            $subCategory->category_id = $request->category;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->save();

            session()->flash('success', 'Sub category created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub category created successfully',
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Display the form for editing a subcategory.
     *
     * @param int $subcategoryId The ID of the subcategory to edit.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse The view for editing the subcategory or a redirect response if the subcategory is not found.
     */
    public function edit($subCategoryId, Request $request)
    {
        $subCategory = SubCategory::find($subCategoryId);
        if (empty($subCategory)) {
            return redirect()->route('sub-categories.index')->with('error', 'Sub category not found.');
        } 

        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;

        return view('admin.sub_category.edit', $data);
    }

    /**
     * Update a subcategory.
     *
     * @param int $subCategoryId The ID of the subcategory to update.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function update($subCategoryId, Request $request)
    {
        $subCategory = SubCategory::find($subCategoryId);

        if (empty($subCategory)) {
            session()->flash('error', 'Record not found.');

            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        } 

        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'status' => 'required',
            'showHome' => 'required',
        ]);

        if ($validator->passes()) {
            $subCategory->category_id = $request->category;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->update();

            session()->flash('success', 'Sub category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Sub category updated successfully',
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Delete a subcategory.
     *
     * @param int $subCategoryId The ID of the subcategory to delete.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function destroy($subCategoryId, Request $request)
    {
        $subCategory = SubCategory::find($subCategoryId);

        if (empty($subCategory)) {
            session()->flash('error', 'Record not found.');

            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $subCategory->delete();

        session()->flash('success', 'Sub category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Sub category deleted successfully',
        ]);
    }
}
