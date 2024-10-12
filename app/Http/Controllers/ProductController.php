<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        $products = Product::query()->with('section')->latest()->paginate(250);
        $total = $products->total();
        $sections = Section::all();
        return view('product.index', compact('products', 'total', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {

        $productValidated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'section_id' => 'required|exists:sections,id',
        ]);

        try {
            if (Product::query()->where('name', $productValidated['name'])->exists()) {
                return redirect()->back()->withErrors(['error' => 'This product already exists']);
            }

            Product::query()->create($productValidated);

            return redirect()->back()->with('success', 'Product created successfully');
        } catch (\Exception $e) {
//            \Log::error($e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Failed to create the product']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
//    public function destroy(Product $product): \Illuminate\Http\RedirectResponse
//    {
//        try {
//            $product = Product::query()->findOrFail($product->id);
//            if ($product->delete()) {
//                return redirect()->back()->with('success', 'the product deleted success');
//            }else{
//                return redirect()->back()->withErrors('error' , 'failed deleted');
//            }
//        }catch (\Exception $e){
//            return redirect()->back()->withErrors('error' , $e);
//        }
//
//    }
//}
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        Product::query()->findOrFail($request->id)->delete();
        return redirect()->back()->with('success' , 'the product deleted success');
    }
}
