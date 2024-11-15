<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemCharacteristics;

class ItemController extends Controller
{
    public function categoryTree(Request $request)
	{
		$categories = Category::whereNull('parent_id')->all();
		
		if(!categories) {
			return response()->json([
				'status' => false,
				'data' => [
					'message' => 'categories not found'
				]
			]);
		}
		
		$catalog = [];
		foreach($categories as $category) {
			$subcategories = Category::where('parent_id', '=', $category->id)->all();
			
			if(!$subcategories) {
				$catalog[] = $category-name;
			} else {
				foreach($subcategories as $subcategory) {
					$subsubcategories = Category::where('parent_id', '=', $subcategory->id)->all();
					
					if(!$subcategories) {
						$catalog[$category] = $subcategory-name;
					} else {
						foreach($subcategories as $subcategory) {
							$catalog[$category][$subcategory] = $subsubcategory-name;
						}
					}
				}
			}
		}
		
		return response()->json([
			'status' => true,
			'data' => $catalog
		]);
	}
	
	public function getItems(Request $request)
	{
		$input = $request->all();
		$items = Items->with('item_characteristics');
		
		if($input['category']) {
			$items = $items->where(
				'category_id', 
				'=', 
				Category::where('name', '=', $input['category'])->one()->id
			);
		}
		
		if($input['price']) {
			$items = $items->where(
				'price', 
				'=', 
				$input['price']
			);
		}
		
		if($input['length']) {
			$items = $items->where(
				'length', 
				'=', 
				$input['length']
			);
		}
		
		if($input['width']) {
			$items = $items->where(
				'width', 
				'=', 
				$input['width']
			);
		}
		
		if($input['weight']) {
			$items = $items->where(
				'weigth', 
				'=', 
				$input['weight']
			);
		}
		
		$items = $items->all();
		if(!$items) {
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
	
	public function getItem(Request $request)
	{
		$item = Item::with('item_characteristics')->where('slug', '=', $reuqest->slug)->one();
	}
}
