<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetrequestController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
    $permissionData=$this->getPagePermission($request,70);
    $query='SELECT sci_name_en AS sector_name, rqs_description AS color_code, rqs_name_en AS status_name, bdy_name,prj_name, prj_code, bdr_request_status, bdr_id,bdr_budget_year_id,bdr_requested_amount,
     bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,
     bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,bdr_update_time,
     bdr_delete_time,bdr_created_by,bdr_status,bdr_action_remark,1 AS is_editable, 1 AS is_deletable 
     FROM pms_budget_request 
     INNER JOIN pms_project ON pms_project.prj_id=pms_budget_request.bdr_project_id
     INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_budget_request.bdr_budget_year_id
     LEFT JOIN gen_request_status ON gen_request_status.rqs_id=pms_budget_request.bdr_request_status';
      $query .=' LEFT JOIN pms_sector_information ON pms_sector_information.sci_id= pms_project.prj_sector_id';
     $query .=' WHERE 1=1';
     
$requestStatus=$request->input('bdr_request_status');
if(isset($requestStatus) && isset($requestStatus)){
$query .=" AND bdr_request_status='".$requestStatus."'"; 
}
$startTime=$request->input('budget_request_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND bdr_requested_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('budget_request_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND bdr_requested_date_gc <='".$endTime." 23 59 59'"; 
}
$bdrbudgetyearid=$request->input('bdr_budget_year_id');
if(isset($bdrbudgetyearid) && isset($bdrbudgetyearid)){
$query .=" AND bdr_budget_year_id='".$bdrbudgetyearid."'"; 
}
$bdrrequestedamount=$request->input('bdr_requested_amount');
if(isset($bdrrequestedamount) && isset($bdrrequestedamount)){
$query .=' AND bdr_requested_amount="'.$bdrrequestedamount.'"'; 
}
$bdrreleasedamount=$request->input('bdr_released_amount');
if(isset($bdrreleasedamount) && isset($bdrreleasedamount)){
$query .=' AND bdr_released_amount="'.$bdrreleasedamount.'"'; 
}
$bdrprojectid=$request->input('project_id');
if(isset($bdrprojectid) && isset($bdrprojectid)){
$query .= " AND bdr_project_id = '$bdrprojectid'";
}
$bdrrequesteddateec=$request->input('bdr_requested_date_ec');
if(isset($bdrrequesteddateec) && isset($bdrrequesteddateec)){
$query .=' AND bdr_requested_date_ec="'.$bdrrequesteddateec.'"'; 
}
$bdrrequesteddategc=$request->input('bdr_requested_date_gc');
if(isset($bdrrequesteddategc) && isset($bdrrequesteddategc)){
$query .=' AND bdr_requested_date_gc="'.$bdrrequesteddategc.'"'; 
}
$bdrreleaseddateec=$request->input('bdr_released_date_ec');
if(isset($bdrreleaseddateec) && isset($bdrreleaseddateec)){
$query .=' AND bdr_released_date_ec="'.$bdrreleaseddateec.'"'; 
}
$bdrreleaseddategc=$request->input('bdr_released_date_gc');
if(isset($bdrreleaseddategc) && isset($bdrreleaseddategc)){
$query .=' AND bdr_released_date_gc="'.$bdrreleaseddategc.'"'; 
}
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
$bdrprojectid=$request->input('project_id');
if(isset($bdrprojectid) && isset($bdrprojectid)){
$query .= " AND bdr_project_id = '$bdrprojectid'";
}
}else{
    $userInfo=$this->getUserInfo($request);
        if(isset($userInfo)){
            if($userInfo->usr_owner_id > 0){
                $query .=" AND prj_owner_id='".$userInfo->usr_owner_id."'";
            }else{
                $query=$this->getSearchParamCSO($request,$query);
            }
        }
}
//
//$this->getQueryInfo($query);
$query.=' ORDER BY bdr_id DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 2,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'bdr_budget_year_id'=> trans('form_lang.bdr_budget_year_id'), 
'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 
'bdr_status'=> trans('form_lang.bdr_status'), 

    ];
    $rules= [
    'bdr_budget_year_id'=> 'max:200', 
'bdr_requested_amount'=> 'max:200', 
//'bdr_released_amount'=> 'numeric', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425', 
    ];
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if($validator->fails()) {
        $errorString = implode(",",$validator->messages()->all());
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>"",
            "statusCode"=>"error",
            "type"=>"update",
            "errorMsg"=>$errorString
        );
        return response()->json($resultObject);
    }else{
        $id=$request->get("bdr_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bdr_status');
        if($status=="true"){
            $requestData['bdr_status']=1;
        }else{
            $requestData['bdr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetrequest::findOrFail($id);
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
    }else{
        //Parent Id Assigment
        //$requestData['ins_vehicle_id']=$request->get('master_id');
        //$requestData['bdr_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetrequest::create($requestData);
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>$data_info,
            "statusCode"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
        return response()->json($resultObject);
    }        
}
}
public function insertgrid(Request $request)
{
    $attributeNames = [
        'bdr_budget_year_id'=> trans('form_lang.bdr_budget_year_id'), 
'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 
'bdr_status'=> trans('form_lang.bdr_status'), 

    ];
    $rules= [
        'bdr_budget_year_id'=> 'max:200', 
'bdr_requested_amount'=> 'max:200', 
//'bdr_released_amount'=> 'numeric', 
'bdr_project_id'=> 'max:200', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425', 
//'bdr_status'=> 'integer', 

    ];
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if($validator->fails()) {
        $errorString = implode(",",$validator->messages()->all());
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>"",
            "statusCode"=>"error",
            "type"=>"update",
            "errorMsg"=>$errorString
        );
        return response()->json($resultObject);
    }else{
        $requestData = $request->all();
        //$requestData['bdr_created_by']=auth()->user()->usr_Id;
        $requestData['bdr_created_by']=1;
        $status= $request->input('bdr_status');
        if($status=="true"){
            $requestData['bdr_status']=1;
        }else{
            $requestData['bdr_status']=0;
        }
        $data_info=Modelpmsbudgetrequest::create($requestData);
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
        $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "status_code"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
    }  
    return response()->json($resultObject);
}
public function deletegrid(Request $request)
{
    $id=$request->get("bdr_id");
    Modelpmsbudgetrequest::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}