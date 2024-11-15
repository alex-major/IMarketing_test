<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);
 
    return ['token' => $token->plainTextToken];
});

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);
 
    $user = User::where('email', $request->email)->first();
 
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
 
    return $user->createToken($request->device_name)->plainTextToken;
});

Route::prefix('items')->group(function(){
	Route::get('/category-tree', [ItemController::class, 'categoryTree']);
	Route::get('/', [ItemController::class, 'getItems']);
	Route::get('/{slug}', [ItemController::class, 'getItem']);
});

Route::prefix('orders')->group(function(){
	Route::get('/', [OrderController::class, 'ordersList'])->middleware('auth:sanctum');
	Route::post('/', [OrderController::class, 'addOrder']);
	Route::post('/{id}/items', [OrderController::class, 'addOrderItems']);
	Route::patch('/{id}', [OrderController::class, 'editOrderItems']);
	Route::delete('/{id}', [OrderController::class, 'deleteOrderItems']);
});
