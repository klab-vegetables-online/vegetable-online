<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::all();
        if ($products) {
            return response()->json([
                'message' => 'true',
                'products' => $products
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No Products Found'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [

            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'image' => 'required',
            'description' => 'required|string|max:255',
            'subcategoryId' => 'required|string|max:255',

        ]);
        $imagePath = $request->image->store('/uploads', 'public');
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'description' => $request->description,
            'subcategoryId' => $request->subcategoryId,
            'status' => 'Available',

        ]);
        $res = [
            'message' => 'Product Created Successfully',
            'data' => $product
        ];
        return Response()->json($res, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::find($id);
        if ($product) {
            return response()->json([
                'message' => 'true',
                'product' => $product
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Product Not Found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'subcategory' => 'required|string|max:255',
            'status' => 'required|string|max:255',

        ]);

        $imagePath = $request->image->store('/uploads', 'public');
        $product = Product::find($id);
        if ($product) {
            $product->name = $request->name;
            $product->price = $request->price;
            if ($imagePath) {
                $product->image = $imagePath;
            }
            $product->description = $request->description;
            $product->subcategory = $request->subcategory;
            $product->save();
            $res = [
                'message' => 'Product Updated Successfully',
                'data' => $product
            ];
            return Response()->json($res, 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Product Not Found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            $res = [
                'message' => 'Product Deleted Successfully'
            ];
            return Response()->json($res, 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Product Not Found'
            ], 404);
        }
    }

    public function getProductBySubCategory($id)
    {
        $subcategory = Subcategory::find($id);
        if ($subcategory) {
            $products = Product::where('subcategoryId', $id)->get();
            if ($products) {
                return response()->json([
                    'message' => 'true',
                    'products' => $products
                ], 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'No Products Found'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No SubCategory Found'
            ], 404);
        }
    }

    public function getProductByCategory($id)
    {
        $category = ProductCategory::find($id);
        if ($category) {
            $products = $category->subCategories()->products();
            // $products = $subcategory->products()->get();
            if ($products) {
                return response()->json([
                    'message' => 'true',
                    'products' => $products
                ], 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'No Products Found'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Invalid category'
            ], 404);
        }
    }
}
