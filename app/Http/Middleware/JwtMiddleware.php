<?php
namespace App\Http\Middleware;
use Closure;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\Modeltblaccesslog;
class JwtMiddleware 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        try {
           // dd(JWTAuth::getToken());
            //$payload = auth()->payload();
            //dd(auth('api'));
            $headers = $request->headers->all();
            //dd($headers['ownerid'][0]);
            //dd(JWTAuth::getToken());
            $user = JWTAuth::parseToken()->authenticate();
            //dd($user);
            $requestData = $request->all();
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
            $requestData['acl_ip'] = $request->ip();
            $requestData['acl_detail'] = $request->method();
            $requestData['acl_object_name'] = $request->fullUrl();
            $requestData['acl_object_id'] = $request->userAgent();
            //$requestData['acl_remark'] = $request->all();
            $requestData['acl_description'] = json_encode($request->input());
            $requestData['acl_user_id']=$user->usr_id;
            //serialize($request->input()->all());
            //dd($request->input());
        $data_info=Modeltblaccesslog::create($requestData);
        $request->authUser = $user;
        } catch (JWTException $e) {
            //dd($e);
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}