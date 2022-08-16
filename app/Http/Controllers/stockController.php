<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class stockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $stocks = Stock::all();
        if ($stocks) {
            return response()->json([
                'message' => 'true',
                'stocks' => $stocks
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No Stocks Found'
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
        if (auth()->user()->role === 'admin') {

            $this->validate($request, [

                'productId' => 'required|string|max:255',
                'quantity' => 'required',
                'note' => 'string|max:255',
            ]);
            $stock = Stock::where('product_id', $request->productId)->first();
            if ($stock) {
                $stock->quantity = $stock->quantity + $request->quantity;
                if ($request->note) {
                    $stock->note = $request->note;
                }
                $stock->save();
                $product = Product::find($request->productId);
                $product->status = 'Available';
                $product->save();
                $res = [
                    'message' => 'Stock Updated Successfully',
                    'data' => $stock
                ];
                return Response()->json($res, 200);
            } else {
                $stock = Stock::create([
                    'product_id' => $request->productId,
                    'quantity' => $request->quantity,
                    'note' => $request->note,
                ]);
                $res = [
                    'message' => 'Stock Created Successfully',
                    'data' => $stock
                ];
                return Response()->json($res, 200);
            }
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
    public function stockOut(Request $request)
    {
        //
        $this->validate($request, [

            'productId' => 'required|string|max:255',
            'quantity' => 'required',
            'note' => 'string|max:255',
        ]);
        $stock = Stock::where('product_id', $request->productId)->first();
        if ($stock) {
            if ($stock->quantity >= $request->quantity) {
                $stock->quantity = $stock->quantity - $request->quantity;
                if ($request->note) {
                    $stock->note = $request->note;
                }
                $stock->save();
                if ($stock->quantity == 0) {
                    $product = Product::find($stock->product_id);
                    $product->status = 'out of stock';
                    $product->save();
                }
                $res = [
                    'message' => 'Stock Updated Successfully',
                    'data' => $stock
                ];
                return Response()->json($res, 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'Stock Not Enough'
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Stock Not Found'
            ], 404);
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
        $stock = Stock::where('product_id', $id)->first();
        if ($stock) {
            return response()->json([
                'message' => 'true',
                'stock' => $stock
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No Stock Found'
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
        $stock = Stock::where('product_id', $id)->first();
        if ($stock) {
            $stock->delete();
            return response()->json([
                'message' => 'true',
                'stock' => $stock
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'No Stock Found'
            ], 404);
        }
    }
}
