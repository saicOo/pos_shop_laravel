<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Product;
use App\Category;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class ProductController extends Controller
{

    public function index(Request $request)
    {
        $categories = Category::all();
        $products = Product::when($request->search,function ($query) use ($request){
            return $query->whereTranslationLike('name', '%'.$request->search.'%');
        })->when($request->category_id,function ($q) use ($request){
            return $q->where('category_id', $request->category_id);
        })->latest()->paginate(2);
        return view('dashboard.products.index',compact('categories','products'));
    }


    public function create()
    {
        $categories = Category::all();
        return view('dashboard.products.create',compact('categories'));
    }


    public function store(Request $request)
    {
        // start validation
        $rules= [
            'category_id' => 'required'
        ];
        foreach ( config('translatable.locales') as $locale) {
            $rules += [$locale . '.name' => 'required|unique:product_translations,name'];
            $rules += [$locale . '.description' => 'required'];
        }
        $rules += [
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ];
        $request->validate($rules);
    // end validation

        $request_data = $request->all();
        if($request->image){
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/product_images/'.$request->image->hashName()));

            $request_data['image'] = $request->image->hashName();
        }

        Product::create($request_data);
        session()->flash('success', __('site.added_successfully'));
        return redirect()->route('dashboard.products.index');
    }


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('dashboard.products.edit',compact('categories','product'));

    }


    public function update(Request $request, Product $product)
    {
        // start validation
        $rules= [
            'category_id' => 'required'
        ];
        foreach ( config('translatable.locales') as $locale) {
            $rules += [$locale . '.name' => ['required',Rule::unique('product_translations','name')->ignore($product->id,'product_id')]];
            $rules += [$locale . '.description' => 'required'];
        }
        $rules += [
            'purchase_price' => 'required',
            'sale_price' => 'required',
            'stock' => 'required',
        ];
        $request->validate($rules);
    // end validation
    $request_data =  $request->all();

    if ($request->image) {
        if ($product->image != 'default.png') {

            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        }//end of inner if

        Image::make($request->image)
            ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/product_images/' . $request->image->hashName()));

        $request_data['image'] = $request->image->hashName();

    }//end of external if

    $product->update($request_data);
    session()->flash('success', __('site.updated_successfully'));
        return redirect()->route('dashboard.products.index');
    }


    public function destroy(Product $product)
    {
        if($product->image != 'default.png'){
            Storage::disk('public_uploads')->delete('/product_images/'.$product->image);
        }
        $product->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.products.index');
    }
}
