<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductModel extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'product_ms';

  protected $fillable = [
    'product_id',
    'product_category_id',
    'product_name',
    'product_price',
    'product_show',
    'created_at',
    'updated_at'
  ];

  function insertData($flag = '', $data){
    switch($flag){
      case 'insertData':
        return ProductModel::create([
          'product_id' => uniqid('P'),
          'product_category_id' => $data['productCategoryId'],
          'product_name' => $data['name'],
          'product_price' => $data['price'],
          'product_show' => $data['show'],
        ]);
        break;
    }
  }

  function getData($flag = '', $data = [], $convertLabel = ''){
    $result = [];
    switch($flag){
      case 'pagination':
        // $result = $this->paginate($data['limit']);
        $result = $this->select("*", 
                                DB::raw("(SELECT product_category_name FROM product_category_ms pc WHERE pc.product_category_id = product_ms.product_category_id) AS product_category_name"))->get();
        break;
      case 'detail':
        $result = $this->where('product_id', $data['productId'])->select("*", 
                      DB::raw("(SELECT product_category_name FROM product_category_ms pc WHERE pc.product_category_id = product_ms.product_category_id) AS product_category_name"))->first();
        break;
      case 'getByProductCategoryId':
        $result = $this->where('product_category_id', $data['productCategoryId'])->get();
        break;
    }
    return $this->convertData($convertLabel, $result);
  }

  function convertData($flag = '', $data){
    $result = [];
    switch($flag){
      case 'pagination':
        foreach($data as $dataRow){
          array_push($result, [
            'id' => $dataRow->product_id,
            'category' => $dataRow->product_category_name,
            'categoryId' => $dataRow->product_category_id,
            'name' => $dataRow->product_name,
            'show' => $dataRow->product_show,
            'price' => $dataRow->product_price,
            'priceLabel' => 'Rp. ' . number_format($dataRow->product_price, 0, ',', '.'),
          ]);
        }
        break;
      case 'detail':
        if(!empty($data)){
          $result = [
            'id' => $data->product_id,
            'category' => $data->product_category_name,
            'categoryId' => $data->product_category_id,
            'name' => $data->product_name,
            'show' => $data->product_show,
            'price' => $data->product_price,
            'priceLabel' => 'Rp. ' . number_format($data->product_price, 0, ',', '.'),
          ];
        }
        break;
      default:
        $result = $data;
        break;
    }
    return $result;
  }

  function deleteData($flag = '', $data){
    switch($flag){
      case 'deleteData':
        return $this->where('product_id', $data['productId'])->delete();
        break;
    }
  }

  function updateData($flag = '', $data){
    switch($flag){
      case 'updateData':
        return $this->where('product_id', $data['productId'])->update([
          'product_name' => $data['name'],
          'product_category_id' => $data['productCategoryId'],
          'product_show' => $data['show'],
          'product_price' => $data['price'],
        ]);
        break;
    }
  }
}
