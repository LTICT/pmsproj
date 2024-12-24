<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Modeltblusers;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login','register']]);
    }
    /* Login API */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email'=>'required|string|email',
                'password'=>'required|string'
            ]
        );
//dd($request);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $cridentials = $request->only('email', 'password');
        $cridentials=array('email'=>$request->input('email'),'password'=>$request->input('password'));
       // dd($cridentials);
        //$token = Auth::attempt($cridentials);
        try{
        $token = auth('api')->attempt($cridentials);
        if(!$token){
            return response()->json([
                'status'=>'error',
                'message'=>'Incorrect email/Password'
            ], 401);
        }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
//STAR TTEST
//$refreshToken = JWTAuth::refresh(); 
        // Set the refresh token in an HTTP-only cookie
  //      $cookie = cookie('refresh-token', $refreshToken, 60 * 60 * 24 * 7, null, null, null, true); // 7 days expiration

       // return response()->json(['access_token' => $token])->withCookie($cookie);
        //END TEST
        $user = auth('api')->user();
        return response()->json([
            'status'=> 'success',
            'user'=> $user,
            'authorization'=> [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);
    }
    /* Register API */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'name'=>'required|string|max:255',
                'email'=>'required|string|email|max:255|unique:users',
                'password'=>'required|string|min:6'
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message'=>'User Registered Successfully',
            'user'=>$user,
            'authorisation'=> [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);

    }

    /*User Detail API */
    public function userDetails()
    {
        return response()->json(auth()->user());
    }
    
 public function me() 
    {
        // use auth()->user() to get authenticated user data

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => auth()->user(),
            ],
        ]);
    }

     public function logout()
    {
        // get token
        $token = JWTAuth::getToken();
        // invalidate token
        $invalidate = JWTAuth::invalidate($token);
        if($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
        }
    }
    public function changePassword(Request $request)
    {
     $attributeNames = [ 
      'password'=> trans('form_lang.password'), 
      'name'=> trans('form_lang.name'), 
      'mobile'=> trans('form_lang.mobile'), 
      'roleId'=> trans('form_lang.roleId')
    ];
    $rules= [       
      'password'=> 'required|max:10',
      'user_id'=> 'required'
    ];
    $userId=$request->get('user_id');
    $request_data=['password'=>bcrypt($request->get('password'))]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
      $data_info = Modeltblusers::findOrFail($userId);
      $data_info->update($request_data);
     $resultObject= array(
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
        return response()->json($resultObject);
     
    }else{
     $resultObject= array(
            "is_updated"=>false,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
        return response()->json($resultObject);
    }
  }
}