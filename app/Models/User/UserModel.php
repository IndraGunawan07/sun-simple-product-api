<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'users_ms';

  protected $fillable = [
    'users_id',
    'users_email',
    'users_name',
    'users_password',
    'created_at',
    'updated_at'
  ];

  function insertData($flag = '', $data){
    switch($flag){
      case 'insertData':
        return UserModel::create([
          'users_id' => uniqid('U'),
          'users_email' => $data['email'],
          'users_name' => $data['name'],
          'users_password' => Hash::make($data['password'])
        ]);
        break;
    }
  }

  function getData($flag = '', $data = [], $convertLabel = ''){
    $result = [];
    switch($flag){
      case 'getByEmail':
        $result = UserModel::where('users_email', $data['email'])->first();
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

    }
  }

  function updateData($flag = '', $data){
    switch($flag){
      case 'updateData':

    }
  }
}
