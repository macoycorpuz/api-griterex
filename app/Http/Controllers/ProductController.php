<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showAllProducts()
    {
        $products = Product::with('User')
                    ->with('Category')
                    ->get();

        if($products->count() <= 0) return $this->errorResponse('productNotFound');
        
        return response()->json([
            'error' => false,
            'products' => $products
        ], 200);
    }

    public function showProductsByCategory($id)
    {
        $products = Product::with('User')
                    ->with('Category')
                    ->where('category_id', $id)
                    ->get();

        if($products->count() <= 0) return $this->errorResponse('productNotFound');
        
        return response()->json([
            'error' => false,
            'products' => $products
        ], 200);
    }

    public function showOneProduct($id)
    {
        $product = Product::with('User')
                    ->with('Category')
                    ->find($id);
        if($product->count() <= 0) return $this->errorResponse('productNotFound');
        
        return response()->json([
                'error' => false,
                'product' => $product
        ], 200);
    }

    public function create(Request $request)
    {
        //Add Picture Here
        $filenameWithExt = $request->file('image')->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $extension = $request->file('image')->guessClientExtension();
        $fileNameToStore= $filename.'_'.time().'.'.$extension;
        $path = $request->file('image')->storeAs('public/products', $fileNameToStore);

        //Add Product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'url' => $request->url,
            'supplier_id' => $request->supplier_id,
            'category_id' => $request->category_id
        ]);
        if(!$product) return $this->errorResponse('createFailed');

        return response()->json([
            'error' => false,
            'message' => 'Product has been posted'
        ], 200);
    }

    public function update($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        if(!$product) return $this->errorResponse('updateFailed');
        
        return response()->json([
            'error' => false,
            'message' => 'Product has been updated',
            'product' => $product
        ], 200);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id)->delete();
        if(!$product) return $this->errorResponse('deleteFailed');

        return response()->json([
            'error' => false,
            'message' => 'Deleted successfully'
        ], 200);
    }

    public function errorResponse($res)
    {
        $data = 
        [ 
            'productNotFound' => [
                'error' => true,
                'message' => 'No product(s) found'
            ],
            'uploadFailed' => [
                'error' => true,
                'message' => 'Unable to upload image'
            ],   
            'createFailed' => [
                'error' => true,
                'message' => 'Unable to create product'
            ],
            'updateFailed' => [
                'error' => true,
                'message' => 'Unable to update product'
            ],
            'deleteFailed' => [
                'error' => true,
                'message' => 'Unable to delete product'
            ],       
        ];

        return response()->json($data[$res], 500);
    }
}