<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display the list of categories in the admin panel.
     *
     * @param  \Illuminate\Http\Request  $request
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

    /**
     * Store a new category.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
            'status' => 'required',
            'showHome' => 'required',
        ]);

        if ($validator->passes()) {
            $category = new Category;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            // save image section
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/uploads/template/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                // generate image thumb section
                $dPath = public_path().'/uploads/category/thumbnail/'.$newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }

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

    /**
     * Edit a category.
     *
     * @param int $categoryId
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View The redirect response or the view for editing the category.
     */
    public function edit($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        
        if (empty($category)) {
            return redirect()->route('categories.index')->with('error', 'Category not found.');
        }
        
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Update a category.
     *
     * @param int $categoryId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        
        if (empty($category)) {

            session()->flash('error', 'Category not found.');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
            'status' => 'required',
            'showHome' => 'required',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->update();

            $oldImage = $category->image;

            // save image section
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/uploads//template/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                // generate image thumb section
                $dPath = public_path().'/uploads/category/thumbnail/'.$newImageName;
                $img = Image::make($sPath);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->update();

                // delete old image here
                File::delete(public_path().'/uploads/category/thumbnail/'.$oldImage);
                File::delete(public_path().'/uploads/category/'.$oldImage);
            }

            session()->flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ]);
            
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Delete a category.
     *
     * @param int $categoryId
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function destroy($categoryId, Request $request)
    {
        $category = Category::find($categoryId);

        if (empty($category)) {
            session()->flash('error', 'Category not found.');

            return response()->json([
                'status' => true,
                'message' => 'Category not found.',
            ]);
        }

        File::delete(public_path().'/uploads/category/thumbnail/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        session()->flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
