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
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /* Login API */
    public function login(Request $request)
    {
        $this->validateLogin($request);
        $credentials = $request->only('email', 'password');
        $credentials['usr_status'] = 1;

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['status' => 'error', 'message' => 'Incorrect email/Password'], 401);
        }

        $user = auth('api')->user();
        $this->updateLastLogin($user);
        unset($user->email);
        unset($user->password);
        unset($user->usr_password);
        $user->user_info = $this->getUserInfo($user);
        $user->user_sector = $this->getUserSectors($user);

        return $this->respondWithToken($token, $user);
    }

    protected function validateLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    protected function updateLastLogin($user)
    {
        $user->update(['usr_last_logged_in' => now()]);
    }

    protected function getUserInfo($user)
    {
        $query = 'SELECT sci_name_or AS sector_name, zone.add_name_or AS zone_name, woreda.add_name_or AS woreda_name
                  FROM tbl_users
                  LEFT JOIN gen_address_structure zone ON tbl_users.usr_zone_id = zone.add_id
                  LEFT JOIN gen_address_structure woreda ON tbl_users.usr_woreda_id = woreda.add_id
                  LEFT JOIN pms_sector_information ON tbl_users.usr_sector_id = pms_sector_information.sci_id
                  WHERE usr_id = ?';

        return DB::select($query, [$user->usr_id])[0] ?? null;
    }

    protected function getUserSectors($user)
    {
        $query = 'SELECT STRING_AGG(usc_sector_id::TEXT, \',\') AS sector_ids
                  FROM tbl_user_sector
                  WHERE usc_status = 1 AND usc_user_id = ?';

        return DB::select($query, [$user->usr_id]);
    }

    protected function respondWithToken($token, $user = null)
    {
        $refreshToken = JWTAuth::customClaims(['exp' => now()->addDays(14)->timestamp])->fromUser($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => 1800,
                'refresh_token' => $refreshToken
            ]
        ]);
    }

    public function refreshToken()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $newToken = auth('api')->refresh();

        return $this->respondWithToken($newToken, $user);
    }

    /* Register API */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

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
            'message' => 'User Registered Successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer'
            ]
        ]);
    }

    /* User Detail API */
    public function userDetails()
    {
        return response()->json(auth()->user());
    }

    public function me()
    {
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
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'Successfully logged out',
            ],
            'data' => [],
        ]);
    }
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|max:10',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['is_updated' => false, 'status_code' => 200, 'type' => 'update', 'errorMsg' => '']);
        }
        $user = Modeltblusers::findOrFail($request->get('user_id'));
        $user->update([
            'password' => bcrypt($request->get('password')),
            'usr_password_changed' => 1
        ]);
        unset($user->email);
        unset($user->password);
        unset($user->usr_password);
        return response()->json([
            'data' => $user,
            'previledge' => ['is_role_editable' => 1, 'is_role_deletable' => 1],
            'is_updated' => true,
            'status_code' => 200,
            'type' => 'update',
            'errorMsg' => ''
        ]);
    }
}
