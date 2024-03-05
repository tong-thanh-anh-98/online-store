<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display the store page with filtered products based on category, subcategory, brand, price range, and sorting.
     *
     * @param Request $request The HTTP request object.
     * @param string|null $categorySlug The slug of the category (optional).
     * @param string|null $subCategorySlug The slug of the subcategory (optional).
     *
     * @return \Illuminate\Contracts\View\View The view for the store page.
     */
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];

        $categories = Category::orderBy('name','ASC')->with('sub_category')->where('status',1)->get();
        $brands = Brand::orderBy('name','ASC')->where('status',1)->get();
        $products = Product::where('status',1);

        /*Apply filters here */
        if (!empty($categorySlug)) {
            $category = Category::where('slug',$categorySlug)->first();
            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;
        }
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug',$subCategorySlug)->first();
            $products = $products->where('sub_category_id',$subCategory->id);
            $subCategorySelected = $subCategory->id;
        }
        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandsArray);
        }
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000 ) {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),1000000]);
            } else {
                $products = $products->whereBetween('price',[intval($request->get('price_min')),intval($request->get('price_max'))]);
            }
        }
        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id','DESC');
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price','ASC');
            } else {
                $products = $products->orderBy('price','DESC');
            }
        } else {
            $products = $products->orderBy('id','DESC');
        }

        $products = $products->paginate(6);

        $data = [];
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['brandsArray'] = $brandsArray;
        $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $data['priceMin'] = intval($request->get('price_min'));
        $data['sort'] = $request->get('sort');

        return view('front.store',$data);
    }

    /**
     * Display the product page for a specific product.
     *
     * @param string $slug The slug of the product.
     *
     * @return \Illuminate\Contracts\View\View The view for the product page.
     */
    public function Product($slug)
    {
        $product = Product::where('slug',$slug)->with('product_images')->first();

        if ($product == null) {
            abort(404);
        }

        /* Fetch related products */
        $relatedProducts =[];
        if ($product->related_products != '') {
            $productArray = explode(',',$product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        $data = [];
        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;

        return view('front.product', $data);
    }
}
