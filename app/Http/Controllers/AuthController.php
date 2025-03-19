<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Modeltblusers;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
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
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $cridentials = $request->only('email', 'password');
        $cridentials=array('email'=>$request->input('email'),'password'=>$request->input('password'),'usr_status'=>1);
       // dd($cridentials);
        //$token = Auth::attempt($cridentials);
        try{
            $data_info['is_editable']=1;
            $data_info['is_deletable']=1;
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
        //START USER INFO
         $user['user_info']="";
         if($user){
        $query='SELECT sci_name_or AS sector_name,
        zone.add_name_or AS zone_name,
        woreda.add_name_or AS woreda_name
        FROM tbl_users ';   
        $query .= ' LEFT JOIN gen_address_structure zone ON tbl_users.usr_zone_id = zone.add_id';
        $query .= ' LEFT JOIN gen_address_structure woreda ON tbl_users.usr_woreda_id = woreda.add_id'; 
        $query .= ' LEFT JOIN pms_sector_information ON tbl_users.usr_sector_id = pms_sector_information.sci_id';
        $query .=" WHERE usr_id=".$user->usr_id." ";
        $user_detail_data=DB::select($query);
        $text="";
        if( isset($user_detail_data) && !empty($user_detail_data)){
            /*$text .=(isset($user_detail_data[0]->sector_name) && !empty($user_detail_data[0]->sector_name)) ? "Sector - ".  $user_detail_data[0]->sector_name : '';
            $text .=(isset($user_detail_data[0]->zone_name) && !empty($user_detail_data[0]->zone_name)) ? " : Zone - ".  $user_detail_data[0]->zone_name : '';
            $text .=(isset($user_detail_data[0]->dep_name) && !empty($user_detail_data[0]->dep_name)) ? " : Department - ".  $user_detail_data[0]->dep_name : '';*/
            //Update last login data 
            $user['user_info']=$user_detail_data[0];
            $data_info = Modeltblusers::findOrFail($user->usr_id);
            $data['usr_last_logged_in']=date('Y-m-d H:i:s');
            $data_info->update($data);
        }
        
    }
        //START GET USER SECTORS
        $query ="SELECT STRING_AGG(usc_sector_id::TEXT, ',') AS sector_ids FROM tbl_user_sector WHERE usc_status=1 AND usc_user_id=".$user->usr_id."";
$user_sector_data=DB::select($query);
$user['user_sector']=$user_sector_data;
        //END GET USER SECTORS
        //END USER INFO
        unset($user->email);
        unset($user->password);
        unset($user->usr_password);
       return $this->respondWithToken($token, $user);
    }
protected function respondWithToken($token, $user = null)
{
    $refreshToken = JWTAuth::customClaims(['exp' => now()->addDays(14)->timestamp])->fromUser($user);
    $payload = JWTAuth::setToken($refreshToken)->getPayload();
        // Extract the expiration time
       //$expirationTime = $payload['exp'];
        // Convert to a readable timestamp
        //$expirationDate = date('Y-m-d H:i:s', $expirationTime);
        //dd($expirationDate);
    return response()->json([
        'status' => 'success',
        'user' => $user,
        'authorization' => [
            'token' => $token,
            'type' => 'bearer',
            //'expires_in' => auth('api')->factory()->getTTL() * 60,
            'expires_in' => 60, // Expiration time (applies to access token)
            'refresh_token' => $refreshToken
        ]
    ]);
}
public function refreshToken()
{
      $user = JWTAuth::parseToken()->authenticate();
      unset($user->email);
        unset($user->password);
        unset($user->usr_password);
        // Generate new token
        $newToken = auth('api')->refresh();

    return $this->respondWithToken($newToken, $user);
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
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
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