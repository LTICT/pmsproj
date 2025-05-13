<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblusers;
use App\Models\ModelUserProfile;
use App\Models\Modeltblupdateusers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Modelpmscsoinfo;
//PROPERTY OF LT ICT SOLUTION PLC
class GenSignupController extends MyController
{
 public function __construct()
 {
    parent::__construct();
    //$this->middleware('auth');
}

public function signup(Request $request)
{
    $attributeNames = [
        'usr_email'=> trans('form_lang.usr_email'),
        'usr_password'=> trans('form_lang.usr_password'),
        'usr_full_name'=> trans('form_lang.usr_full_name'),
        'usr_status'=> trans('form_lang.usr_status')
    ];
    $rules= [
        'usr_email' => 'required|max:200|unique:tbl_users',
        'usr_password'=> 'max:200',
        'usr_full_name'=> 'max:128',
        'usr_phone_number'=> 'max:20',
//'usr_picture'=> 'max:100',
        'usr_last_logged_in'=> 'max:30',
        'usr_ip'=> 'max:15',
        'usr_remember_token'=> 'max:100',
        'usr_description'=> 'max:425'
    ];
  $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        $status= $request->input('usr_status');
        if($status=="true"){
            $requestData['usr_status']=1;
        }else{
            $requestData['usr_status']=0;
        }
        $email=$request->input('usr_email');
        $name=$request->input('cso_name');
        $requestData['cso_email']=$email;
        $requestData['cso_name']=$name;
        $requestData['cso_code']="-";
        $requestData['cso_phone']=$request->input('usr_phone');
        $requestData['cso_website']=$request->input('cso_website');
        $requestData['cso_description']=$request->input('cso_description');
        $requestData['cso_created_by']=1;
        $requestData['cso_status']=0;
        //$requestData['cso_contact_person']=0;
        
        $cso_info=Modelpmscsoinfo::create($requestData);
        if(isset($cso_info)){
        $requestData['usr_owner_id']=$cso_info->cso_id;
        $requestData['usr_user_type']=2;
        $requestData['usr_status']=1;
        $requestData['usr_full_name']=$name;
        $requestData['email']=$email;
        $requestData['usr_email']=$email;
        $requestData['password']=bcrypt($request->get('usr_password'));
        $requestData['usr_password']=bcrypt($request->get('usr_password'));
        $requestData['usr_created_by']=1;
        $requestData['usr_department_id']=1;
        $requestData['usr_sector_id']=1;
        $requestData['usr_region_id']=1;
        $requestData['usr_zone_id']=1;
        $requestData['usr_woreda_id']=1;
        $requestData['usr_phone_number']=$request->input('usr_phone');
        $data_info=Modeltblusers::create($requestData);
        //START ADD DEFAULT ROLE
        if(isset($data_info) && !empty($data_info)){
            $role_usr_data['url_role_id']=68;
            $role_usr_data['url_user_id']=$data_info->usr_id;
            \App\Models\Modeltbluserrole::create($role_usr_data);
        }
        //START ADD DEFAULT ROLE
        }
        
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
    return response()->json([
        "data" => $data_info,
        "previledge" => [
            'is_role_editable' => 1,
            'is_role_deletable' => 1
        ],
        "status_code" => 200,
        "type" => "save",
        "errorMsg" => ""
    ]);
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"save");
}
}
}