<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Modeltblusers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegisteredEmail;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
      return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'mobile' => ['required', 'string', 'min:10'],

      ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
      return view('auth.register', "");
    }
    public function store(Request $request)
    {  
        //dd($request);
     $attributeNames = [
      'email'=> trans('form_lang.email'), 
      'password'=> trans('form_lang.password'), 
      'name'=> trans('form_lang.name'), 
      'mobile'=> trans('form_lang.mobile')
    ];
    $rules= [
      'email'=> 'required|max:128|unique:tbl_users', 
      'password'=> 'required|max:10|confirmed', 
      'name'=> 'required|max:128', 
      'mobile'=> 'required|max:128',
    ];
    $validator = Validator::make ( $request->all(), $rules);
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
      $requestData = $request->all();
      $requestData['roleId']=3;
      $requestData['password']=bcrypt($request->get('password'));
      $userInfo=Modeltblusers::create($requestData);
      if(isset($userInfo) && !empty($userInfo)){
       //return redirect('users')->with('flash_message',  trans('form_lang.insert_success'));
        auth()->loginUsingId($userInfo->usr_Id);
        //START SEND EMAIL
        $userEmail=$userInfo->email;
        $email_data['user_name']=$userEmail;
        $email_data['expiry_date']=date('d-m-Y', strtotime("+30 days"));
        /*Mail::queue('email/user_registered_email',$email_data,function ($message) use ($userInfo){
          $message->to($userInfo->email)->subject('Welcome To Yenehisab');
        });*/
        //Mail::queue('email/user_registered_email',$email_data,function ($message) use ($userInfo){
          //$message->to($userInfo->email)->subject('Welcome To Yenehisab');
        //});
        Mail::to($userInfo->email)->queue(new RegisteredEmail($userInfo));
        //END SEND MAIL
      }
      return redirect()->intended('');
    }else{
      return redirect('register')
      ->withErrors($validator)
      ->withInput();
    }
  }
}
