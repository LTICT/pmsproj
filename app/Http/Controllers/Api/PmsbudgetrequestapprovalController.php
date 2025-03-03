<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetrequestapprovalController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
    $userInfo=$this->getUserInfo($request);
    //dd($userInfo);
        //if(isset($userInfo)){
    //get user info from logged in user
            $zoneId=$userInfo->usr_zone_id;
            $woredaId=$userInfo->usr_woreda_id;
            $sectorId=$userInfo->usr_sector_id;
            $departmentId=$userInfo->usr_department_id;
            $directorateId=$userInfo->usr_directorate_id;
            $teamId=$userInfo->usr_team_id;
            $officerId=$userInfo->usr_officer_id;

            $query='SELECT rqs_description AS color_code, rqs_name_en AS status_name, bdy_name,prj_name, prj_code, bdr_request_status, bdr_id,bdr_budget_year_id,bdr_requested_amount,
     bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,
     bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,bdr_update_time,
     bdr_delete_time,bdr_created_by,bdr_status,bdr_action_remark,1 AS is_editable, 1 AS is_deletable 
     FROM pms_budget_request 
     INNER JOIN pms_project ON pms_project.prj_id=pms_budget_request.bdr_project_id
     INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_budget_request.bdr_budget_year_id
     INNER JOIN gen_request_status ON gen_request_status.rqs_id=pms_budget_request.bdr_request_status';

if($directorateId>0 && $teamId==0 && $officerId==0){
    $query .=" INNER JOIN gen_request_followup ON gen_request_followup.rqf_request_id=pms_budget_request.bdr_id AND
    rqf_forwarded_to_dep_id=".$directorateId."  ";
}else if($directorateId>0 && $teamId>0 && $officerId==0){
    $query .=" INNER JOIN gen_request_followup ON gen_request_followup.rqf_request_id=pms_budget_request.bdr_id AND
    rqf_forwarded_to_dep_id=".$teamId."  ";
}else if($directorateId>0 && $teamId>0 && $officerId>0){
    $query .=" INNER JOIN gen_request_followup ON gen_request_followup.rqf_request_id=pms_budget_request.bdr_id AND
    rqf_forwarded_to_dep_id=".$officerId."  ";
}    
     $query .=' WHERE 1=1';
     
$requestStatus=$request->input('bdr_request_status');
if(isset($requestStatus) && isset($requestStatus)){
$query .=" AND bdr_request_status='".$requestStatus."'"; 
}

$prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('budget_request_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND bdr_requested_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('budget_request_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND bdr_requested_date_gc <='".$endTime." 23 59 59'"; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
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
//if user is assinged to a department
if($departmentId > 1){
    //$query .=" AND prj_department_id='".$departmentId."'"; 
}
//dd($query);
$query.=' ORDER BY bdr_id DESC';
$data_info=DB::select($query);
$this->getQueryInfo($query);
/*}else{
    $data_info=array();
}*/
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
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
}