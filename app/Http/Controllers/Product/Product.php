<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\ProductModel;
use Illuminate\Http\Request;
use Validator;

class Product extends Controller
{
  function __construct(){
    $this->productModel = new ProductModel();
  }

  function index(Request $request){
    return response()->json($this->productModel->getData('pagination', $request->all(), 'pagination'), 200);
  }

  function insert(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'show' => 'required|boolean',
      'productCategoryId' => 'required|exists:product_category_ms,product_category_id',
      'price' => 'required|integer'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    if($this->productModel->insertData('insertData', $request->all()) === false){
      return response()->json(['messages' => 'Insert failed'], 400);
    }
    return response()->json(['messages' => 'Insert success'], 200);
  }

  function detail($productId = ''){
    $validator = Validator::make(['productId' => $productId], [
      'productId' => 'required|exists:product_ms,product_id'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    return response()->json($this->productModel->getData('detail', ['productId' => $productId], 'detail'), 200);
  }

  function update(Request $request, $productId = ''){
    $reqData = $request->all();
    $reqData['productId'] = $productId;
    $validator = Validator::make($reqData, [
      'name' => 'required',
      'show' => 'required|boolean',
      'productCategoryId' => 'required|exists:product_category_ms,product_category_id',
      'price' => 'required|integer',
      'productId' => 'required|exists:product_ms,product_id'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    if($this->productModel->updateData('updateData', $reqData) === false){
      return response()->json(['messages' => 'Update failed'], 400);
    }
    return response()->json(['messages' => 'Update success'], 200);
  } 

  function delete($productId = ''){
    $validator = Validator::make(['productId' => $productId], [
      'productId' => 'required|exists:product_ms,product_id'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    if($this->productModel->deleteData('deleteData', ['productId' => $productId]) === false){
      return response()->json(['messages' => 'Delete failed'], 400);
    }
    return response()->json(['messages' => 'Delete success'], 200);
  }

  function paymentGateway($productId = ''){
    $validator = Validator::make(['productId' => $productId], [
      'productId' => 'required|exists:product_ms,product_id'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    $product = $this->productModel->getData('detail', ['productId' => $productId]);

    $data = [
      'transaction_details' => [
        'order_id' => 'order-id',
        'gross_amount' => $product->product_price
      ],
      'credit_card' => [
        'secure' => true
      ]
    ];
    $basic = 'Basic ' . base64_encode('SB-Mid-server-xyiz6iEYQTroTcHRQHScVGFz');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://app.sandbox.midtrans.com/snap/v1/transactions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'accept: application/json',
        'authorization:' . $basic,
        'content-type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, true));

    $response = curl_exec($ch);

    curl_close($ch);
    return response()->json(json_decode($response, true), 200);
  }
}
