<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTokenModel extends Model
{
  use HasFactory;

  protected $table = 'users_token_tr';

  protected $fillable = [
    'users_token_id',
    'users_id',
    'users_token',
    'created_at',
    'updated_at'
  ];

  function insertData($flag = '', $data){
    switch($flag){
      case 'insertData':
        return UserTokenModel::create([
          'users_token_id' => uniqid('U'),
          'users_id' => $data['userId'],
          'users_token' => md5(rand(1, 10) . microtime())
        ]);
        break;
    }
  }

  function getData($flag = '', $data = [], $convertLabel = ''){
    $result = [];
    switch($flag){
      case 'checkToken':
        $result = $this->where('users_token', $data['token'])->first();
        break;
    }
    return $this->convertData($convertLabel, $result);
  }

  function convertData($flag = '', $data){
    $result = [];
    switch($flag){
      default:
        $result = $data;
        break;
    }
    return $result;
  }

  function deleteData($flag = '', $data){
    switch($flag){
      case 'deleteData':
        return $this->where('users_token', $data['token'])->delete();
        break;
    }
  }

  function updateData($flag = '', $data){
    switch($flag){
      case 'updateData':

    }
  }
}
