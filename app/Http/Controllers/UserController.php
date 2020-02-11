<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
   public function login(){
       $credentials=[
           'email'=> request('email'),
           'password'=>request('password')
       ];
       if(Auth::attempt($credentials)){
           $success['token']=Auth::user()->createToken('MyApp')->accessToken;
           return response()->json(['success'=> $success]);
       }
       return response()->json(['error'=>'Unauthorised'],401);
   }
   public function register(Request $request){
        $validator=Validator::make($request->all(),
        [     'name' => 'required',
              'email' => 'required|email',
              'password' => 'required',
              'c_password' => 'required|same:password'
            ]);
        if($validator->fails())
        {
            return response()->json(['error'=>$validator->errors()],401);
        }
        $input=$request->all();
        $input['password']=bcrypt($input['password']);

        $user=User::create($input);
        $success['token']=$user->createToken('MyApp')->accessToken;
        $success['name']=$user->name;
        return response()->json(['success'=>$success]);
   }
   public function logout(){
       $accessToken= auth()->user()->token();
       $refreshToken = DB::table('oauth_refresh_tokens')
           ->where('access_token_id', $accessToken->id)
           ->update([
               'revoked' => true
           ]);
       $accessToken->revoke();
       return response()->json(['status'=>200]);
   }
}
