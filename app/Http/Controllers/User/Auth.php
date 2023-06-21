<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserModel;
use App\Models\User\UserTokenModel;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class Auth extends Controller
{
  public function __construct(){
    // parent::__construct();
    $this->userModel = new UserModel();
    $this->userTokenModel = new UserTokenModel();
  }

  function register(Request $request){
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|unique:users_ms,users_email',
      'password' => 'required',
      'passwordConfirmation' => 'required|same:password'
    ]);
    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }

    if($this->userModel->insertData('insertData', $request->all()) === false){
      return response()->json(['messages' => 'Failed to register'], 400);
    }
    return response()->json(['messages' => 'Success to register'], 200);
  }

  function login(Request $request){
    $validator = Validator::make($request->all(), [
      'email' => 'required|exists:users_ms,users_email',
      'password' => 'required',
    ],
    [
      'email.exists' => 'Email or password is wrong'
    ]);

    if($validator->fails()){
      return response()->json($validator->errors(), 400);
    }
    // get user detail
    $user = $this->userModel->getData('getByEmail', $request->all());
    // check user password
    if(Hash::check($request->password, $user->users_password) === false){
      return response()->json(['messages' => 'Email or password is wrong'], 400);
    }

    // Create User Token
    $token = $this->userTokenModel->insertData('insertData', ['userId' => $user->users_id]);
    return response()->json(['messages' => 'Login success', 'token' => $token->users_token], 200);
  }
}
