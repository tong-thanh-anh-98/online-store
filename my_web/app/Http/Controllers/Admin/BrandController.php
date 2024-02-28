<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    /**
     * Display a list of brands.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\View\View The view for displaying the list of brands.
     */
    public function index(Request $request)
    {
        $brands = Brand::latest('id');
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%'.$request->get('keyword').'%');
        }
        $brands = $brands->paginate(10);

        return view('admin.brands.list', compact('brands'));
    }

    /**
     * Display the form for creating a new brand.
     *
     * @return \Illuminate\View\View The view for creating a new brand.
     */
    public function create()
    {
        return view('admin.brands.create');
    }

    /**
     * Store a newly created brand.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $brand = new Brand;
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success', 'Brand created successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand created successfully',
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Display the form for editing a brand.
     *
     * @param int $brandId The ID of the brand to edit.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse The view for editing the brand or a redirect response if the brand is not found.
     */
    public function edit($brandId, Request $request)
    {
        $brand = Brand::find($brandId);
        if (empty($brand)) {
            return redirect()->route('brands.index')->with('error', 'Brand not found.');
        }

        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update a brand.
     *
     * @param int $brandId The ID of the brand to update.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function update($brandId, Request $request)
    {
        $brand = brand::find($brandId);
        
        if (empty($brand)) {
            session()->flash('error', 'Brand not found.');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'brand not found.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
            'status' => 'required',
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->update();

            session()->flash('success', 'Brand updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully',
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Delete a brand.
     *
     * @param int $id The ID of the brand to delete.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function destroy($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            session()->flash('error', 'Record not found.');

            return response()->json([
                'status' => false,
                'notFound' => true,
            ]);
        }
        $brand->delete();

        session()->flash('success', 'Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully',
        ]);
    }
}
