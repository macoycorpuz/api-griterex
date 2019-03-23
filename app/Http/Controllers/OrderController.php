<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;
use DB;

class OrderController extends Controller
{
    public function showAllOrders()
    {
        $orders = Order::with('Product')
                    ->with('User')
                    ->get();

        if($orders->count() <= 0) return $this->errorResponse('orderNotFound');
        
        return response()->json([
            'error' => false,
            'orders' => $orders
        ], 200);
    }

    public function showOneOrder($id)
    {
        $order = Order::with('Product')
                    ->with('User')
                    ->find($id);
        if($order->count() <= 0) return $this->errorResponse('orderNotFound');
        
        return response()->json([
                'error' => false,
                'order' => $order
        ], 200);
    }

    public function showOrdersByActive(Request $request)
    {
        if($request->account_id == 1) {
            $orders = Order::with('Product')
                    ->with('User')
                    ->with('Product.User')
                    ->where('user_id', $request->user_id)
                    ->where('active', $request->active === 'true' ? true: false)
                    ->get();
        } else if($request->account_id == 2) {
            $orders = Order::with('Product')
                ->with('User')
                ->with('Product.User')
                ->whereHas('Product.User', function($q) use($request) {
                    $q->where('id', $request->user_id);
                })
                ->where('active', $request->active === 'true' ? true: false)
                ->get();
        }
        else {
            $orders = Order::with('Product')
                    ->with('User')
                    ->with('Product.User')
                    ->get();
        }
        
        if($orders->count() <= 0) return $this->errorResponse('orderNotFound');
        
        return response()->json([
            'error' => false,
            'orders' => $orders
        ], 200);
    }

    public function create(Request $request)
    {
        //Add Order
        $order = Order::create([
            'quantity' => $request->quantity,
            'total' => $request->total,
            'cash' => $request->cash,
            'status' => $request->status,
            'active' => 1,
            'product_id' => $request->product_id,
            'user_id' => $request->user_id
        ]);

        //Add Credit Card
        if($request->number) 
        {
            $credit = Credit::create([
                'number' => $request->number,
                'csv' => $request->csv,
                'expiry' => $request->expiry,
            ]);
            if(!$credit) return $this->errorResponse('failedCredit');
            $order->credit()->associate($credit)->save();
        }
        if(!$order) return $this->errorResponse('createFailed');

        return response()->json([
            'error' => false,
            'message' => "Order Submitted"
        ], 200);
    }

    public function update(Request $request)
    {
        $order = Order::findOrFail($request->id);
        $order->update([
            'status' => $request->status,
            'active' => $request->active === 'true' ? true: false
        ]);
        if(!$order) return $this->errorResponse('updateFailed');
        
        return response()->json([
            'error' => false,
            'message' => 'Order has been updated',
            'order' => $order
        ], 200);
    }

    public function delete($id)
    {
        $order = Order::findOrFail($id)->delete();
        if(!$order) return $this->errorResponse('deleteFailed');

        return response()->json([
            'error' => false,
            'message' => 'Deleted successfully'
        ], 200);
    }

    public function errorResponse($res)
    {
        $data = 
        [ 
            'orderNotFound' => [
                'error' => true,
                'message' => 'No order(s) found'
            ],
            'failedCredit' => [
                'error' => true,
                'message' => 'Unable to create credit card'
            ], 
            'uploadFailed' => [
                'error' => true,
                'message' => 'Unable to upload image'
            ],   
            'createFailed' => [
                'error' => true,
                'message' => 'Unable to create order'
            ],
            'updateFailed' => [
                'error' => true,
                'message' => 'Unable to update order'
            ],
            'deleteFailed' => [
                'error' => true,
                'message' => 'Unable to delete order'
            ],       
        ];

        return response()->json($data[$res], 200);
    }
}