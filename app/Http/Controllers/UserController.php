<?php

namespace App\Http\Controllers;

use App\User;
use App\Credit;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showAllUsers()
    {
        $users = User::with('Credit')
                    ->with('Account')
                    ->get();

        if($users->count() <= 0) return $this->errorResponse('userNotFound');
        
        return response()->json([
                'error' => false,
                'users' => $users
        ], 200);
    }

    public function showUsersByAccount($id)
    {
        $users = User::with('Credit')
                    ->with('Account')
                    ->where('account_id', $id)
                    ->get();

        if($users->count() <= 0) return $this->errorResponse('userNotFound');
        
        return response()->json([
                'error' => false,
                'users' => $users
        ], 200);
    }

    public function showOneUser($id)
    {        
        $user = User::with('Credit')
                    ->with('Account')
                    ->find($id);
        if($user->count() <= 0) return $this->errorResponse('userNotFound');
        
        return response()->json([
                'error' => false,
                'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $user = User::with('Credit')
                    ->with('Account')
                    ->where('email', $request->email)
                    ->where('password', $request->password)
                    ->first();

        if($user->count() <= 0) return $this->errorResponse('invalidUser');
        
        return response()->json([
                'error' => false,
                'user' => $user
        ], 200);
    }

    public function create(Request $request)
    {        
        //Add User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'number' => $request->number,
            'address' => $request->address,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'account_id' => $request->account_id
        ]);

        //Add Credit Card
        if($request->account_id == 2) 
        {
            $credit = Credit::create([
                'number' => $request->credit_number,
                'csv' => $request->csv,
                'expiry' => $request->expiry,
            ]);
            if(!$credit) return $this->errorResponse('failedCredit');
            $user->credit()->associate($credit)->save();
        }

        if(!$user) return $this->errorResponse('createFailed');
        return response()->json([
            'error' => false,
            'message' => 'Registered successfully'
        ], 200);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());
        if(!$user) return $this->errorResponse('updateFailed');
        
        return response()->json([
            'error' => false,
            'message' => 'User has been updated',
            'user' => $user
        ], 200);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id)->delete();
        if(!$user) return $this->errorResponse('deleteFailed');

        return response()->json([
            'error' => false,
            'message' => 'Deleted successfully'
        ], 200);
    }

    public function errorResponse($res)
    {
        $data = 
        [ 
            'invalidUser' => [
                'error' => true,
                'message' => 'Invalid username and password'
            ],
            'userNotFound' => [
                'error' => true,
                'message' => 'No user(s) found'
            ],
            'failedCredit' => [
                'error' => true,
                'message' => 'Unable to create credit card'
            ],   
            'createFailed' => [
                'error' => true,
                'message' => 'Unable to create user'
            ],
            'updateFailed' => [
                'error' => true,
                'message' => 'Unable to update user'
            ],
            'deleteFailed' => [
                'error' => true,
                'message' => 'Unable to delete user'
            ],       
        ];

        return response()->json($data[$res], 500);
    }
}