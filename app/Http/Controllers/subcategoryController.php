<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;

class subcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $subcategories = Subcategory::all();
        return response()->json([
            'message' => 'All Subcategories Retrieved Succesfully',
            'subcategories' => $subcategories
        ], 200);
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
            'categoryId' => 'required|string|max:255',

        ]);
        $subcategory = Subcategory::create([
            'name' => $request->name,
            'product_category_id' => $request->categoryId,

        ]);
        $res = [
            'message' => 'Subcategory Created Successfully',
            'data' => $subcategory
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
        $subcategory = Subcategory::find($id);
        if ($subcategory) {
            return response()->json([
                'message' => 'true',
                'subcategory' => $subcategory
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Subcategory Not Found'
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
            'categoryId' => 'required|string|max:255',
        ]);
        $subcategory = Subcategory::find($id);
        if ($subcategory) {
            $subcategory->name = $request->name;
            $subcategory->categoryId = $request->categoryId;
            $subcategory->save();
            $res = [
                'message' => 'Subcategory Updated Successfully',
                'data' => $subcategory
            ];
            return Response()->json($res, 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Subcategory Not Found'
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
        $subcategory = Subcategory::find($id);
        if ($subcategory) {
            $subcategory->delete();
            $res = [
                'message' => 'Subcategory Deleted Successfully',
                'data' => $subcategory
            ];
            return Response()->json($res, 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Subcategory Not Found'
            ], 404);
        }
    }
}
