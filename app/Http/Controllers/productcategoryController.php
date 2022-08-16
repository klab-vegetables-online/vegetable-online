<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class productcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $productcategories = ProductCategory::all();
        if ($productcategories) {
            return response()->json([
                'message' => 'true',
                'productcategories' => $productcategories
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No Product Categories Found'
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
        if (auth()->user()->role == 'admin') {
            $this->validate($request, [

                'name' => 'required|string|max:255|unique:product_categories',

            ]);
            $productcategory = ProductCategory::create([
                'name' => $request->name,

            ]);
            $res = [
                'message' => 'Product Category Created Successfully',
                'data' => $productcategory
            ];
            return Response()->json($res, 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'You are not authorized to perform this action'
            ], 401);
        }
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
        $productcategory = ProductCategory::find($id);
        if ($productcategory) {
            return response()->json([
                'message' => 'true',
                'productcategory' => $productcategory
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Product Category Not Found'
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
        if (auth()->user()->role == 'admin') {
            $this->validate($request, [

                'name' => 'required|string|max:255|unique:product_categories',

            ]);
            $productcategory = ProductCategory::find($id);
            if ($productcategory) {
                $productcategory->name = $request->name;
                $productcategory->save();
                return response()->json([
                    'message' => 'true',
                    'productcategory' => $productcategory
                ], 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'Product Category Not Found'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'You are not authorized to perform this action'
            ], 401);
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
        if (auth()->user()->role == 'admin') {
            $productcategory = ProductCategory::find($id);
            if ($productcategory) {
                $productcategory->delete();
                return response()->json([
                    'message' => 'true',
                    'productcategory' => $productcategory
                ], 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'Product Category Not Found'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'You are not authorized to perform this action'
            ], 401);
        }
    }
}
