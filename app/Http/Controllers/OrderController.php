<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use Illuminate\Http\Request;

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

    public function create(Request $request)
    {
        $order = Order::create([
            'quantity' => $request->quantity,
            'total' => $request->total,
            'status' => $request->status,
            'active' => $request->active,
            'product_id' => $request->product_id,
            'buyer_id' => $request->buyer_id,
            'credit_id' => $request->credit_id
        ]);
        if(!$order) return $this->errorResponse('createFailed');

        return response()->json([
            'error' => false,
            'message' => 'Order has been placed'
        ], 200);
    }

    public function update($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
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

        return response()->json($data[$res], 500);
    }
}