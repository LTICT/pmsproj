<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Modeltblusers;
use Carbon\Carbon;
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
      $usrIp=$request->ip();
      /*$allowedIps = \App\Modeltblallowedmachine::where('alm_ip', '=', $usrIp)->where('alm_status', '=', 1)->get();
      if(isset($allowedIps) &&!empty($allowedIps) && count($allowedIps) > 0){ */
        $token= $request->input('remember');
        if($token=="on"){
          $requestData=1;
        }else{
          $requestData=0;
        }
        $email=$request->input('email');
        $password=$request->input('password');
        $email=$request->input('email');
        $credentials=array('email'=>$email, 'password'=>$password, 'isDeleted'=>0);
        //$credentials = $request->only('email', 'password');
        //$credentials = $request->only(userInfo);
        $this->validate($request, [
          'email' => 'required',
          'password' => 'required|min:6'
        ]);
        if (Auth::attempt($credentials, $requestData)) {
         $userInfo=auth()->user();
         $userId=$userInfo->usr_Id;
         $this->logLastLogin($userId);

         session()->put('current_zone', $userInfo->usr_zone_id);
         session()->put('current_woreda', $userInfo->usr_woreda_id);
         session()->put('current_bureau', $userInfo->usr_department_id);
         session()->put('role', $userInfo->roleId);
         session()->put('user_id', $userInfo->usr_Id);
         session()->put('role_name', $userInfo->roles->role);
           //dd($userCompanies[0]);
            /*session()->put('currentCompany',$userCompanies[0]->cmu_company_id);
            session()->put('currentCompanyName', $userCompanies[0]->company->cmp_name);
            session()->put('companyTaxActive', $userCompanies[0]->cmu_tax_active);
            session()->put('companyHrmsActive', $userCompanies[0]->cmu_hrms_active);
            session()->put('companyPayrollActive', $userCompanies[0]->cmu_payroll_active);
            session()->put('companyCalendar', $userCompanies[0]->company->cmp_calendar);*/
            //$calendar=$userCompanies[0]->company->cmp_calendar;
            return redirect()->intended('');
          }else{
            return redirect()->back()->withInput()->withErrors(['email' => 'invalid username or password']);
          }
     /* }else{
        return redirect()->back()->withInput()->withErrors(['email' => 'IP number of this machine is not allowed']);
      }*/
    }
    function logLastLogin($userId){
      //$userId=auth()->user()->usr_Id;      
      $data_info = Modeltblusers::findOrFail($userId);
      $updateData['usr_last_logged_in']=Carbon::now();
      $data_info->update($updateData);
      //$this->loggedInUser($userId);
    }
    function loggedInUser($userId){
      $userCompanies = \App\Modelgencompanyuser::where('cmu_user_id', '=', $userId)->get();
      //dd(count($userCompanies));
      if(isset($userCompanies) && !empty($userCompanies) && count($userCompanies) > 0){
        session()->put('currentCompany',$userCompanies[0]->cmu_company_id);
        return redirect()->intended('');
      }
      else{ 
     // dd(count($userCompanies));
        return redirect('/company/create');
      }
    }
  }