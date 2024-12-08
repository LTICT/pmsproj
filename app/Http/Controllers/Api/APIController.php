<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modelpmsprojectperformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//PROPERTY OF LT ICT SOLUTION PLC
class APIController extends MyController
{
 public function __construct()
 {
  parent::__construct();
}
//loginuser
public function loginUser(Request $request){
  $token= $request->input('remember');
  if($token=="on"){
    $requestData=1;
  }else{
    $requestData=0;
  }
  $email=$request->input('email');
  $password=$request->input('password');
  $credentials=array('email'=>$email, 'password'=>$password, 'isDeleted'=>0);
  /*$this->validate($request, [
      'email' => 'required',
      'password' => 'required|min:6'
    ]);*/
    if (Auth::attempt($credentials, $requestData)) {
     $userInfo=auth()->user();
     $newUserInfo['user_id']=$userInfo['usr_Id'];
     $newUserInfo['email']=$userInfo['email'];
     $newUserInfo['user_name']=$userInfo['name'];
     $newUserInfo['owner_id']=$userInfo['usr_department_id'];
     $newUserInfo['is_inactive']=$userInfo['isDeleted'];
     $resultObject= array(
      "metadata"=>"",
      "value" =>$newUserInfo1,
      "success"=>1
    );
     return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
   }
   else{
    $resultObject= array(
      "metadata"=>"",
      "value" =>"",
      "success"=>0
    );
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK); 
  }
}
//list projects
public function listProjects(Request $request){
  $ownerId=$request->input('owner_id');
  $query='Select prj_name AS project_name, prj_code AS project_code, prj_status AS project_status, 
  prj_total_budget AS project_budget FROM pms_project ';       
 //$query .= ' INNER JOIN pms_project ON pms_project_performance.prp_project_id = pms_project.prj_id';
  $query .=' WHERE 1=1';
  if(isset($ownerId) && isset($ownerId)){
    $query .=' AND prj_procrument_unit="'.$ownerId.'"'; 
  }
  $query.=' ORDER BY prj_name';
  $data_info=DB::select(DB::raw($query));
  if(isset($data_info) && !empty($data_info)){
    $resultObject= array(
      "metadata"=>"",
      "value" =>$data_info,
      "success"=>1
    );
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
  }else{
    $resultObject= array(
      "metadata"=>"",
      "value" =>"",
      "success"=>0
    );
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK); 
  }
}
//Dashboard Data
public function projectDashboard(Request $request){
  $ownerId=$request->input('owner_id');
  $query='Select COUNT(prj_id) as project_count, prj_status AS project_status FROM pms_project ';       
 //$query .= ' INNER JOIN pms_project ON pms_project_performance.prp_project_id = pms_project.prj_id';
  $query .=' WHERE 1=1';
  if(isset($ownerId) && isset($ownerId)){
    $query .=' AND prj_procrument_unit="'.$ownerId.'"'; 
  }
  $query.=' GROUP BY prj_status';
  $data_info=DB::select(DB::raw($query));
  if(isset($data_info) && !empty($data_info)){
    $resultObject= array(
      "metadata"=>"",
      "value" =>$data_info,
      "success"=>1
    );
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
  }else{
    $resultObject= array(
      "metadata"=>"",
      "value" =>"",
      "success"=>0
    );
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK); 
  }
}
//list performance
public function listPerformance(Request $request){
  $projectId=$request->input('project_id');
  $query='Select prp_financial_percentage AS financial_percent, prp_used_budget AS used_budget, 
  prp_physical_percentage AS physical_percentage FROM pms_project_performance ';       
 //$query .= ' INNER JOIN pms_project ON pms_project_performance.prp_project_id = pms_project.prj_id';
  $query .=' WHERE 1=1';
  if(isset($projectId) && isset($projectId)){
    $query .=' AND prp_project_id="'.$projectId.'"'; 
  }    
  $data_info=DB::select(DB::raw($query));
  if(isset($data_info) && !empty($data_info)){
    $resultObject= array(
      "metadata"=>"",
      "value" =>$data_info,
      "success"=>1
    );
    return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
  }else{
   $resultObject= array(
    "metadata"=>"",
    "value" =>"",
    "success"=>0
  );
   return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
 }
}
//Save Performance
public function savePerformance(Request $request)
{
  $requestData['prp_project_id']=$request->get('project_id');
  $requestData['prp_financial_percentage']=$request->get('financial_percent');
  $requestData['prp_used_budget']=$request->get('used_budget');
  $requestData['prp_physical_percentage']=$request->get('physical_percentage');
  $data_info=Modelpmsprojectperformance::create($requestData);
  if(isset($data_info) && !empty($data_info)){
    $resultObject= array(
      "metadata"=>"",
      "value" =>$data_info,
      "statusCode"=>200,
      "type"=>"save",
      "errorMsg"=>"",
      "success"=>1
    );
    return response()->json($resultObject);
  }else{
   $resultObject= array(
    "metadata"=>"",
    "value" =>"",
    "statusCode"=>200,
    "type"=>"save",
    "errorMsg"=>"",
    "success"=>1
  );
   return response()->json($resultObject);
 }
}
//list projects
public function showProjectDetail(Request $request){
    $projectId=$request->input('project_id');
    $query='Select prj_name AS project_name, prj_code AS project_code, prj_status AS project_status, 
    prj_total_budget AS project_budget FROM pms_project ';
    $query .=' WHERE 1=1';
    if(isset($ownerId) && isset($ownerId)){
        $query .=' AND prj_id="'.$projectId.'"'; 
    }
    //$query.=' ORDER BY prj_name';
    $data_info=DB::select(DB::raw($query));
    if(isset($data_info) && !empty($data_info)){
        $resultObject= array(
            "metadata"=>"",
            "value" =>$data_info[0],
            "success"=>1
        );
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }else{
        $resultObject= array(
            "metadata"=>"",
            "value" =>"",
            "success"=>0
        );
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK); 
    }
}
}