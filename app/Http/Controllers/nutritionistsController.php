<?php

namespace App\Http\Controllers;

use App\Models\nutritionists;
use Illuminate\Http\Request;

class nutritionistsController extends Controller
{
    public function getall()
    {
        try {
            $nutritionists = nutritionists::all();
            if ($nutritionists) {
                return response()->json([
                    'message' => 'Success',
                    'data' => $nutritionists
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'false',
                'error' => $e . getMessage(),
            ]);
        }
    }
    public function store(Request $request)
    {
        //
        $this->validate($request, [

            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'address' => 'required|string|max:255',

        ]);
        $data = nutritionists::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'address' => $request->address

        ]);
        $res = [
            'message' => 'Order sent successfully',
            'data' => $data
        ];
        return Response()->json($res, 200);
    }
    public function show($id)
    {
        try {
            $one = nutritionists::findOrFail($id);
            if ($one) {
                return response()->json([
                    'message' => 'success',
                    'category' => $one
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'false',
                'error' => $e . getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        //
        $data = nutritionists::findOrFail($id);
        $data->delete();
        return response()->json([
            'message' => 'Order deleted Successfully',
            'data' => $data
        ], 200);
    }
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'address' => 'required|string|max:255',

        ]);
        $data = nutritionists::findOrFail($id);

        $data->firstname = $request->firstname;
        $data->lastname = $request->lastname;
        $data->email = $request->email;
        $data->telephone = $request->telephone;
        $data->address = $request->address;
        $data->save();

        return response()->json([
            'message' => 'Order updated successfully',
            'data' => $data
        ], 200);
    }
}
