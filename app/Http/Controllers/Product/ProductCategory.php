<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductCategoryModel;
use App\Models\Product\ProductModel;
use Illuminate\Http\Request;
use Validator;

class ProductCategory extends Controller
{
  function __construct(){
    // parent::__construct();
    $this->productCategoryModel = new ProductCategoryModel();
    $this->productModel = new ProductModel();
  }

  function index(Request $request){
    return response()->json($this->productCategoryModel->getData('pagination', $request->all(), 'pagination'), 200);
  }

  function insert(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'show' => 'required|boolean'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    if($this->productCategoryModel->insertData('insertData', $request->all()) === false){
      return response()->json(['messages' => 'Insert failed'], 400);
    }
    return response()->json(['messages' => 'Insert success'], 200);
  }

  function detail($productCategoryId = ''){
    $validator = Validator::make(['productCategoryId' => $productCategoryId], [
      'productCategoryId' => 'required|exists:product_category_ms,product_category_id'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    return response()->json($this->productCategoryModel->getData('detail', ['productCategoryId' => $productCategoryId], 'detail'), 200);
  }

  function update(Request $request, $productCategoryId = ''){
    $reqData = $request->all();
    $reqData['productCategoryId'] = $productCategoryId;
    $validator = Validator::make($reqData, [
      'productCategoryId' => 'required|exists:product_category_ms,product_category_id',
      'name' => 'required',
      'show' => 'required|boolean'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    if($this->productCategoryModel->updateData('updateData', $reqData) === false){
      return response()->json(['messages' => 'Update failed'], 400);
    }
    return response()->json(['messages' => 'Update success'], 200);
  }

  function delete($productCategoryId = ''){
    $validator = Validator::make(['productCategoryId' => $productCategoryId], [
      'productCategoryId' => 'required|exists:product_category_ms,product_category_id'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    // Check if product category have any product
    $product = $this->productModel->getData('getByProductCategoryId', ['productCategoryId' => $productCategoryId]);
    if($product->isEmpty()){
      // Delete product category
      if($this->productCategoryModel->deleteData('deleteData', ['productCategoryId' => $productCategoryId]) === false){
        return response()->json(['messages' => 'Delete failed'], 400);
      }
      return response()->json(['messages' => 'Delete success'], 200);
    }
    // Product category have product
    return response()->json(['messages' => 'Delete failed, product category have product'], 400);
  }
}
