<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;

class orderController extends Controller
{

    public function index()
    {
        //
        if (auth()->user()->role != 'admin') {
            $orders = auth()->user()->orders;
            $res = [
                'orders' => $orders
            ];
            return response()->json($res, 200);
        }
        $orders = Order::all();
        $res = [
            'orders' => $orders
        ];
        return response()->json($res, 200);
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
        if (auth()->user()->role != 'admin') {
            $this->validate($request, [
                'productId' => 'required|integer',
                'quantity' => 'required|integer',
            ]);
            $stock = Stock::where('product_id', $request->productId)->first();
            if ($stock->quantity < $request->quantity) {
                return response()->json([
                    'message' => 'false',
                    'error' => 'Not enough quantity in stock'
                ], 404);
            }
            $order = Order::create([
                'userId' => auth()->user()->id,
                'productId' => $request->productId,
                'quantity' => $request->quantity,
                'status' => 'pending',
            ]);
            $res = [
                'message' => 'Order Created Successfully',
                'data' => $order
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
        $order = Order::find($id);
        if ($order) {
            return response()->json([
                'message' => 'true',
                'order' => $order
            ], 200);
        } else {
            return response()->json([
                'message' => 'false',
                'error' => 'Order Not Found'
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
        if (auth()->user()->role != 'admin') {
            $this->validate($request, [
                'status' => 'required|string|max:255',
            ]);
            $order = Order::find($id);
            if ($order) {
                $order->status = $request->status;
                $order->save();
                $res = [
                    'message' => 'Order Updated Successfully',
                    'data' => $order
                ];
                return Response()->json($res, 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'Order Not Found'
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
        if (auth()->user()->role != 'admin') {
            $order = Order::find($id);
            if ($order) {
                $order->delete();
                $res = [
                    'message' => 'Order Deleted Successfully'
                ];
                return Response()->json($res, 200);
            } else {
                return response()->json([
                    'message' => 'false',
                    'error' => 'Order Not Found'
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
