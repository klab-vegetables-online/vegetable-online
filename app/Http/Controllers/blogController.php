<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogSubCategory;
use Illuminate\Http\Request;


class blogController extends Controller
{
    public function getAllBlog()
    {
        
        try{
          $blog = Blog::orderBy('id', 'desc')->with('category')->get();
          
          if($blog){
            return response()->json([
                'message' => 'All blog Retrieved Succesfully',
                'data' => $blog
            ], 200);
          }
        }
        catch(Exception $e){
            return response()->json([
                'message' => 'false',
                'error' => $e.getMessage(),
            ]);

        }
        
        
        //
        $blog = Blog::orderBy('id', 'desc')->get();
        $blog = Categories::orderBy('id', 'desc')->get();
        
    }

    

    public function addBlog(Request $request)
    {
        //
        $this->validate($request, [
            'title' => 'required|string|max:255|unique:blogs',
            'description' => 'required|string|max:255',
            'requirements' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'image' => 'required',
            'sub_category_id' => 'required'
           
        ]);
        // uploading image direct to cloudinary
        $imagePath = cloudinary()->uploadFile($request->file('image')->getRealPath())->getSecurePath();
        // $imagePath = $request->image->store('/uploads', 'public');
        $blog = Blog::create([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'link' => $request->link,
            'image' => $imagePath,
            'sub_category_id' => $request->sub_category_id,
            
        ]);
        
        $res = [
            'data' => $blog
        ];
        return Response()->json($res, 200);
    }

    public function getBlogBysubCategory($id) 
    {
        $subcategory = BlogSubCategory::find($id);
        $blog = Blog::where('sub_category_id', $id)->get();
        if  ($blog) {
            return response()->json([
                'message' => 'true',
                'blogs' => $blog
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No blogs found'
            ]);
        }
        
    }

    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'title' => 'required|string|max:255|unique:blogs',
            'description' => 'required|string|max:255',
            'requirements' => 'required|string|max:255',
            'link' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'sub_category_id' => 'required'
           
        ]);
        $imagePath = cloudinary()->uploadFile($request->file('image')->getRealPath())->getSecurePath();
        $blog = Blog::findOrFail($id);
        if($blog) {
            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->requirements = $request->requirements;
            $blog->link = $request->link;
            if($imagePath) {
                $blog->imagePath = $imagePath;
            }
            $blog->sub_category_id = $request->sub_category_id;
            $blog->save();
            $res = [
                'message' => 'Blog Updated Successfully',
                'data' => $blog
            ];
            return Response()->json($res, 200);

        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Blog Not Found'
            ], 404);

        }
        

        
    }

    public function destroy($id)
    {
        //
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json([
            'message' => 'Blog deleted Successfully',
            'data' => $blog
        ], 200);
    }

    public function search($search){
        try {
            $blog = Blog::where('title', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->get();
            if($blog){
                return response()->json([
                    'success' => 'true',
                    'data' => $blog
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

            $one = Blog::findOrFail($id)->with('category')->get();
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
