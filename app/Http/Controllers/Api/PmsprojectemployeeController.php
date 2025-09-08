<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectemployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectemployeeController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
        $canListData=$this->getSinglePagePermission($request,43,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }
     $query='SELECT emp_nationality,emp_sex,prj_name,prj_code,emp_id,emp_id_no,emp_full_name,emp_email,emp_phone_num,emp_role,emp_project_id,emp_start_date_ec,emp_start_date_gc,emp_end_date_ec,emp_end_date_gc,emp_address,emp_description,emp_create_time,emp_update_time,emp_delete_time,emp_created_by,emp_current_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_employee ';       
     
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_employee.emp_project_id';
     $query .=' WHERE 1=1';
$startTime=$request->input('employee_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND emp_start_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('employee_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND emp_start_date_gc <='".$endTime." 23 59 59'"; 
}

$empidno=$request->input('emp_id_no');
if(isset($empidno) && isset($empidno)){
$query .=" AND emp_id_no LIKE '%".$empidno."%'"; 
}
$empemail=$request->input('emp_email');
if(isset($empemail) && isset($empemail)){
$query .=" AND emp_email LIKE '%".$empemail."%'"; 
}
$empphonenum=$request->input('emp_phone_num');
if(isset($empphonenum) && isset($empphonenum)){
$query .=" AND emp_phone_num LIKE '%".$empphonenum."%'"; 
}
$empid=$request->input('emp_id');
if(isset($empid) && isset($empid)){
$query .=' AND emp_id="'.$empid.'"'; 
}
$empfullname=$request->input('emp_full_name');
if(isset($empfullname) && isset($empfullname)){
$query .=' AND emp_full_name="'.$empfullname.'"'; 
}

$emprole=$request->input('emp_role');
if(isset($emprole) && isset($emprole)){
$query .=' AND emp_role="'.$emprole.'"'; 
}
$empstartdateec=$request->input('emp_start_date_ec');
if(isset($empstartdateec) && isset($empstartdateec)){
$query .=' AND emp_start_date_ec="'.$empstartdateec.'"'; 
}
$empstartdategc=$request->input('emp_start_date_gc');
if(isset($empstartdategc) && isset($empstartdategc)){
$query .=' AND emp_start_date_gc="'.$empstartdategc.'"'; 
}
$empenddateec=$request->input('emp_end_date_ec');
if(isset($empenddateec) && isset($empenddateec)){
$query .=' AND emp_end_date_ec="'.$empenddateec.'"'; 
}
$empenddategc=$request->input('emp_end_date_gc');
if(isset($empenddategc) && isset($empenddategc)){
$query .=' AND emp_end_date_gc="'.$empenddategc.'"'; 
}
//START
$empprojectid=$request->input('emp_project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($empprojectid) && isset($empprojectid)){
$query .= " AND emp_project_id = '$empprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
$query.=' ORDER BY emp_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>0);
$permission=$this->ownsProject($request,$empprojectid);
if($permission !=null)
{
   $previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $id=$request->get("emp_id");
    $canEditData=$this->getSinglePagePermission($request,43,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
    $attributeNames = [
        'emp_id_no'=> trans('form_lang.emp_id_no'), 
'emp_full_name'=> trans('form_lang.emp_full_name'), 
'emp_email'=> trans('form_lang.emp_email'), 
'emp_phone_num'=> trans('form_lang.emp_phone_num'), 
'emp_role'=> trans('form_lang.emp_role'), 
'emp_project_id'=> trans('form_lang.emp_project_id'), 
'emp_start_date_ec'=> trans('form_lang.emp_start_date_ec'), 
'emp_start_date_gc'=> trans('form_lang.emp_start_date_gc'), 
'emp_end_date_ec'=> trans('form_lang.emp_end_date_ec'), 
'emp_end_date_gc'=> trans('form_lang.emp_end_date_gc'), 
'emp_address'=> trans('form_lang.emp_address'), 
'emp_description'=> trans('form_lang.emp_description'), 

    ];
    $rules= [
        'emp_id_no'=> 'max:200', 
'emp_full_name'=> 'max:200', 
'emp_email'=> 'max:50', 
'emp_phone_num'=> 'max:200', 
'emp_role'=> 'max:200',
'emp_start_date_ec'=> 'max:200', 
'emp_start_date_gc'=> 'max:200', 
'emp_end_date_ec'=> 'max:10', 
'emp_end_date_gc'=> 'max:10', 
'emp_address'=> 'max:50', 
'emp_description'=> 'max:425', 

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        $requestData = $request->all();            
        $status= $request->input('emp_status');
        if($status=="true"){
            $requestData['emp_status']=1;
        }else{
            $requestData['emp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectemployee::find($id);
             if(!isset($data_info) || empty($data_info)){
             return $this->handleUpdateDataException();
            }
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            if($ischanged){
               $resultObject= array(
                "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
           }else{
            $resultObject= array(
                "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
        }
        return response()->json($resultObject);
    }       
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
public function insertgrid(Request $request)
{
    $canAddData=$this->getSinglePagePermission($request,43,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
    $attributeNames = [
        'emp_id_no'=> trans('form_lang.emp_id_no'), 
'emp_full_name'=> trans('form_lang.emp_full_name'), 
'emp_email'=> trans('form_lang.emp_email'), 
'emp_phone_num'=> trans('form_lang.emp_phone_num'), 
'emp_role'=> trans('form_lang.emp_role'), 
'emp_project_id'=> trans('form_lang.emp_project_id'), 
'emp_start_date_ec'=> trans('form_lang.emp_start_date_ec'), 
'emp_start_date_gc'=> trans('form_lang.emp_start_date_gc'), 
'emp_end_date_ec'=> trans('form_lang.emp_end_date_ec'), 
'emp_end_date_gc'=> trans('form_lang.emp_end_date_gc'), 
'emp_address'=> trans('form_lang.emp_address'), 
'emp_description'=> trans('form_lang.emp_description'), 
    ];
    $rules= [
        'emp_id_no'=> 'max:200', 
'emp_full_name'=> 'max:200', 
'emp_email'=> 'max:50', 
'emp_phone_num'=> 'max:200', 
'emp_role'=> 'max:200',
'emp_start_date_ec'=> 'max:200', 
'emp_start_date_gc'=> 'max:200', 
'emp_end_date_ec'=> 'max:10', 
'emp_end_date_gc'=> 'max:10', 
'emp_address'=> 'max:50', 
'emp_description'=> 'max:425', 
    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        //$requestData['emp_created_by']=auth()->user()->usr_Id;
        $status= $request->input('emp_status');
        if($status=="true"){
            $requestData['emp_status']=1;
        }else{
            $requestData['emp_status']=0;
        }
        $data_info=Modelpmsprojectemployee::create($requestData);
        $data_info['is_editable'] = 1;
    $data_info['is_deletable'] = 1;    
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
public function deletegrid(Request $request)
{
    $id=$request->get("emp_id");
    Modelpmsprojectemployee::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "deleted_id"=>$id,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}