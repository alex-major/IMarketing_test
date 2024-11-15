<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function ordersList(Request $request)
	{
		$orders = Order::with('users')
			->where('user_id', '=', $request->user()->id)
			->all();
		
		if(!$orders) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'items not found'
				]
			]);
		}
		
		return response()->json([
			'status' => true,
			'data' => $items
		]);
	}
	
	public function addOrder(Request $request)
	{
		if($request->user()) {
			$order = Order::create([
				'user_id' => $request->user()->id,
				'closed' => false
			]);
			
			foreach($request->items as $item) {
				OrderItem::create([
					'order_id' => $order->id,
					'item_id' => $item['id'],
					'quantity' => $item['quantity']
				]);
			}
			
			return response()->json([
				'status' => true,
				'data' => [
					'message' => 'order appended'
				]
			]);
		}
		
		if(!$request->email) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'email is required'
				]
			]);
		}
		
		$order = Order::create([
			'email' => $request->email,
			'closed' => false
		]);
		
		foreach($request->items as $item) {
			OrderItem::create([
				'order_id' => $order->id,
				'item_id' => $item['id'],
				'quantity' => $item['quantity']
			]);
		}
		
		return response()->json([
			'status' => true,
			'data' => [
				'message' => 'order appended'
			]
		]);
	}
	
	public function addOrderItems(Request $request)
	{
		if(!$request->order_id) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'order_id is required'
				]
			]);
		}
		
		foreach($request->items as $item) {
			OrderItem::create([
				'order_id' => $request->order_id,
				'item_id' => $item['id'],
				'quantity' => $item['quantity']
			]);
		}
		
		return response()->json([
			'status' => true,
			'data' => [
				'message' => 'order items appended'
			]
		]);
	}
	
	public function editOrderItems(Request $request)
	{
		if(!$request->order_id) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'order_id is required'
				]
			]);
		}
		
		foreach($request->items as $item) {
			$orderItem = OrderItem::where('order_id', '=', $request->order_id)
				->where('item_id', '=', $item['id'])->one();
				
			$orderItem->quantity = $item['quantity'];
			$orderItem->save();
		}
		
		return response()->json([
			'status' => true,
			'data' => [
				'message' => 'order items modified'
			]
		]);
	}
	
	public function deleteOrderItems(Request $request)
	{
		if(!$request->order_id) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'order_id is required'
				]
			]);
		}
		
		if(!$request->item_id) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'item_id is required'
				]
			]);
		}
		
		$orderItem = OrderItem::where('order_id', '=', $request->order_id)
			->where('item_id', '=', $request->item_id);
		
		$orderItem->delete();
		
		return response()->json([
			'status' => true,
			'data' => [
				'message' => 'order item deleted'
			]
		]);
	}
}
