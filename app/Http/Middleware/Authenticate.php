<?php
namespace App\Http\Middleware;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
      if (! $request->expectsJson()) {
        return route('login');
      }else{
           // var_dump(request()->route()->getAction()["controller"]);
      }
    }
    /*public function handle($request, Closure $next, $guard = "auth")
    {
        //dd($request);
        $requestPath=$request->path();
        dd($requestPath);
        if (auth()->check()) {
            if($requestPath != 'company/create'){
               $userId=auth()->user()->usr_Id;
               $userCompanies = \App\Modelgencompanyuser::where('cmu_user_id', '=', $userId)->get();
               if(isset($userCompanies) && !empty($userCompanies) && count($userCompanies) > 0){
                return $next($request);
            }
            else{
                return redirect('/company/create');
            }
        }else{
         return $next($request);
     }
 }else{
  return redirect('/login');
}
}*/ public function handle($request, Closure $next, $guard = "auth"){
   // dd($request->server()['PATH']);
  $requestType=$request->method();
  //if($requestType=="GET"){
  $requestPath=$request->path();
  if (auth()->check()) {
    if($requestPath != 'company/create' && $requestPath != 'company/store' ){
      $userId=auth()->user()->usr_Id;
      $adminPages=array('roles','/','users', 'access_log', 'address/listgrid','education_program/listgrid','position/listgrid','profession_type/listgrid','deduction_type/listgrid', 'company_account/listgrid','subuser');
      $taxPages=array("documents/purchases", "documents/sales", "viewdocument","vatdeclaration","withhold","company",'/');
      $hrmsPages=array("employee", "payroll_detail/list","lookup","company","changePasswordScreen",'/', 'employee_overtime');
      $payrollPages=array("payroll_detail/list","company",'/', 'employeepayroll','settings','employeetax');
      if (str_contains($requestPath, 'project_progress') || str_contains($requestPath, 'project')  
        || str_contains($requestPath, 'statisticalreport') || str_contains($requestPath, 'statistics') || in_array($requestPath, $adminPages) 
        || str_contains($requestPath, 'company') || str_contains($requestPath, 'subuser')
        || str_contains($requestPath, 'roles') || str_contains($requestPath, 'project_followup') || str_contains($requestPath, 'budget_expenditure') 
        || str_contains($requestPath, 'monitoring') || str_contains($requestPath, 'key_challenges') || str_contains($requestPath, 'project_performance')  || str_contains($requestPath, 'address')
        || str_contains($requestPath, 'property_file')
        || str_contains($requestPath, 'file')
        || str_contains($requestPath, 'changePasswordScreen')
        || str_contains($requestPath, 'changePassword')
        || str_contains($requestPath, 'resetPasswordScreen')
        || str_contains($requestPath, 'resetPassword')
        || str_contains($requestPath, 'map')
        || str_contains($requestPath, 'bureau')
        || str_contains($requestPath, 'sector_category')
        || str_contains($requestPath, 'project_status')
        || str_contains($requestPath, 'report_schedule')
        || str_contains($requestPath,'purchase_detail')
        || str_contains($requestPath,'bidder')
        || str_contains($requestPath,'contract_extension')
        || str_contains($requestPath,'purchase_followup')
        || str_contains($requestPath,'searchobj')
        || str_contains($requestPath,'activity')
        || str_contains($requestPath,'project_report')
        || str_contains($requestPath,'employee')
        || str_contains($requestPath,'stakeholder')
        || str_contains($requestPath,'contractor')
        || str_contains($requestPath,'payment_information')
        || str_contains($requestPath,'project_report')
        || str_contains($requestPath,'budget_request')
        || str_contains($requestPath,'cost_breakdown')
        || str_contains($requestPath,'project_status')
        
      ){
          return $next($request);
        }
        else{
          return redirect('/errorpage');
        }
      }else{
        return redirect('/errorpage');
      }
    }else{
     return redirect('/login');
   }
/*}else{
  return redirect('/login');
}*/
}
}