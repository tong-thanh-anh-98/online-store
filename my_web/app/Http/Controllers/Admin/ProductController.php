<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\TempImage;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    /**
     * Display a listing of products.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\View\View The view for displaying the list of products.
     */
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if (!empty($request->get('keyword'))) {
            $products = $products->where('title', 'like', '%'.$request->get('keyword').'%');
        }
        $products= $products->paginate(10);

        return view('admin.products.list', compact('products'));
    }


    /**
     * Display the form for creating a new product.
     *
     * @return \Illuminate\View\View The view for creating a new product.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }
    
    /**
     * Store a newly created product.
     *
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status and message.
     */
    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'status' => 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->save();

            // save gallery section
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray); // like png, jpg, etc...

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();
                    
                    $imageName =$product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    /* generate product thumbnail
                     *large image
                     */
                    $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    $destPath = public_path().'/uploads/product/large/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image->save($destPath);

                    /* small image */
                    $destPath = public_path().'/uploads/product/small/'.$imageName;
                    $image = Image::make($sourcePath);
                    $image->fit(300, 300);
                    $image->save($destPath);
                }
            }

            session()->flash('success', 'Product created successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product created successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    /**
     * Display the form for editing a product.
     * The response for editing the product.
     * @param int $productID The ID of the product to edit.
     * @param Request $request The HTTP request object.
     */
    public function edit($productID, Request $request)
    {
        $product = Product::find($productID);

        if (empty($product)) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        /* Fetch product image */
        $productImages = ProductImage::where('product_id', $product->id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();

        $data = [];
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.products.edit', $data);
    }


    /**
     * Update the specified product in storage.
     *
     * @param int $productID The ID of the product to update.
     * @param Request $request The HTTP request object.
     *
     * @return \Illuminate\Http\Response The response for updating the product.
     */
    public function update($productID, Request $request)
    {
        $product = Product::find($productID);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'status' => 'required',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->update();

            session()->flash('success', 'Product updated successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully!',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}
