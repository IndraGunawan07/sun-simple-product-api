<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductCategoryModel extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'product_category_ms';

  protected $fillable = [
    'product_category_id',
    'product_category_name',
    'product_category_show',
    'created_at',
    'updated_at'
  ];

  function insertData($flag = '', $data){
    switch($flag){
      case 'insertData':
        return ProductCategoryModel::create([
          'product_category_id' => uniqid('PC'),
          'product_category_name' => $data['name'],
          'product_category_show' => $data['show'],
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
                      DB::raw("IFNULL((SELECT sum(product_price) FROM product_ms p WHERE p.product_category_id = product_category_ms.product_category_id GROUP BY product_category_ms.product_category_id LIMIT 1), 0) AS total_price,
                               IFNULL((SELECT count(*) FROM product_ms p WHERE p.product_category_id = product_category_ms.product_category_id GROUP BY product_category_ms.product_category_id LIMIT 1), 0) AS total_product"))->get();
        
        break;
      case 'detail':
        $result = $this->where('product_category_id', $data['productCategoryId'])->first();
        break;
    }
    return $this->convertData($convertLabel, $result);
  }

  function convertData($flag = '', $data){
    $result = [];
    switch($flag){
      case 'pagination':
        if(!empty($data)){
          foreach($data as $dataRow){
            array_push($result, [
              'id' => $dataRow->product_category_id,
              'name' => $dataRow->product_category_name,
              'show' => $dataRow->product_category_show,
              'totalPrice' => $dataRow->total_price,
              'totalPriceLabel' => 'Rp. ' . number_format($dataRow->total_price, 0, ',', '.'),
              'totalProduct' => $dataRow->total_product
            ]);
          }
        }
        break;
      case 'detail':
        if(!empty($data)){
          $result = [
            'id' => $data->product_category_id,
            'name' => $data->product_category_name,
            'show' => $data->product_category_show,
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
        return $this->where('product_category_id', $data['productCategoryId'])->delete();
        break;
    }
  }

  function updateData($flag = '', $data){
    switch($flag){
      case 'updateData':
        return $this->where('product_category_id', $data['productCategoryId'])->update([
          'product_category_name' => $data['name'],
          'product_category_show' => $data['show'],
        ]);
        break;
    }
  }
}
