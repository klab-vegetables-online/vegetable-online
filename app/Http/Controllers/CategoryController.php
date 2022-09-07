<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        
        try{
          $data = Categories::all();
         
          if($data){
            return response()->json([
                'message' => 'All categories Retrieved successfully',
                'data' => $data
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
    

    public function store(Request $request)
    {
        //
        $this->validate($request, [

            'name' => 'required|string|max:255',

        ]);
        $category = Categories::create([
            'name' => $request->name,

        ]);
        $res = [

            'data' => $category
        ];
        return Response()->json($res, 200);
    }

    public function update(Request $request, $id)
    {
        //
        $this->validate(
            $request,
            [
                'name' => 'required|string|max:255',

            ]
        );
        $category = Categories::findOrFail($id);

        $category->name = $request->name;
        $category->save();
        
        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
    }

    public function destroy($id)
    {
        //
        $category = Categories::findOrFail($id);
        $category->delete();
        return response()->json([
            'message' => 'Category deleted Successfully',
            'data' => $category
        ], 200);
    }



    public function show($id)
    {
        try {
            $one = Categories::findOrFail($id);
            if ($one) {
                return response()->json([
                    'message' => 'success',
                    'category' => $one
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'false',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function search($search)
    {
        try {
            $category = Categories::where('name', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            if ($category) {
                return response()->json([
                    'success' => 'true',
                    'data' => $category
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => 'false',
                'data' => $e->getMessage(),
            ]);
        }
    }
}
