<?php

namespace App\Http\Controllers;

use App\Models\BlogSubCategory;
use Illuminate\Http\Request;

class BlogSubCategoryController extends Controller
{
    public function index()
    {
        try {
            $sub = BlogSubCategory::orderBy('id', 'desc')->with('category')->get();

            if($sub){
                return response()->json([
                    'message' => 'All blogs subcategories Retrieved Succesfully',
                    'data' => $sub
                ], 200);
            }
        }
        catch(Exception $e){
            return response()->json([
                'message' => 'false',
                'error' => $e.getMessage(),
            ]);

        }
        $sub = BlogSubCategory::orderBy('id', 'asc')->get();
    }

    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:blogs',
            'category_id' => 'required'
           
        ]);
        $sub = BlogSubCategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            
        ]);
        
        $res = [
            'data' => $sub
        ];
        return Response()->json($res, 200);
    }

    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:blogs',
            'category_id' => 'required'
           
        ]);
        $sub = BlogSubCategory::findOrFail($id);
       
        $sub->name = $request->name;
        $sub->category_id = $request->category_id;
        $sub->save();

        return response()->json([
            'message' => 'subcategory updated successfully',
            'data' => $sub
        ], 200);
        
    }

    public function destroy($id)
    {
        //
        $sub = BlogSubCategory::findOrFail($id);
        $sub->delete();
        return response()->json([
            'message' => 'subcategory deleted Successfully',
            'data' => $sub
        ], 200);
    }

    public function search($search){
        try {
            $sub = BlogSubCategory::where('name', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            if($sub){
                return response()->json([
                    'success' => 'true',
                    'data' => $sub
                ]);
            }

        }
        catch (Exception $e) 
        {
            return response()->json([
                'success' => 'false',
                'data' => $e->getMessage(),
            ]);

        }
    }

    public function show($id)
    {
        try{

            $one = BlogSubCategory::findOrFail($id)->with('category')->get();
            if($one){
                return response()->json([
                    'message' => 'success',
                    'blog' => $one
                ], 200);

            }
            

        }
        catch(Exception $e){
            return response()->json([
                'message' => 'false',
                'error' => $e.getMessage(),
            ]);

        }
        
    }


}
