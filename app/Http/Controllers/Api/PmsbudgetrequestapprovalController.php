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

public function show(Request $request,$id)
    {
        $query='SELECT bdr_additional_days,bdr_before_previous_year_physical,
            bdr_before_previous_year_financial,
            bdr_previous_year_physical,
            bdr_previous_year_financial, bdr_source_government_requested, 
bdr_source_government_approved, bdr_source_internal_requested, 
bdr_source_support_requested, bdr_source_support_approved, 
bdr_source_credit_requested, bdr_source_credit_approved, 
bdr_source_other_requested, bdr_source_other_approved,prs_color_code AS project_status_color,prs_status_name_en AS request_type,rqs_name_en AS status_name, bdy_name as budget_year,bdr_id,bdr_budget_year_id,bdr_requested_amount,
    bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,
    bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,
    bdr_update_time,bdr_delete_time,bdr_created_by,bdr_status,bdr_request_status,bdr_action_remark,bdr_request_category_id,bdr_request_type,bdr_physical_baseline,bdr_financial_baseline,bdr_physical_planned,bdr_physical_approved,bdr_physical_recommended,bdr_financial_recommended FROM pms_budget_request '; 
    $query .= ' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_budget_request.bdr_budget_year_id
     LEFT JOIN gen_request_status ON gen_request_status.rqs_id=pms_budget_request.bdr_request_status 
     LEFT JOIN pms_project_status ON pms_project_status.prs_id=pms_budget_request.bdr_request_type';
        $query .=" WHERE bdr_id=".$id." ";
        $data_info=DB::select($query);
        if(isset($data_info) && !empty($data_info)){
            $data=$data_info[0];
            $resultObject= array(
                "data" =>$data,
                "data_available"=>"1",);
            return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }else{
            $resultObject= array(
                "data_available"=>"0");
            return response()->json($resultObject,404, [], JSON_NUMERIC_CHECK);
        }
    }

    public function listgrid(Request $request){
    $userInfo=$this->getUserInfo($request);
    //dd($userInfo);
        //if(isset($userInfo)){
    //get user info from logged in user
            $userId=$userInfo->usr_id;
            $zoneId=$userInfo->usr_zone_id;
            $woredaId=$userInfo->usr_woreda_id;
            $sectorId=$userInfo->usr_sector_id;
            $departmentId=$userInfo->usr_department_id;
            $directorateId=$userInfo->usr_directorate_id;
            $teamId=$userInfo->usr_team_id;
            $officerId=$userInfo->usr_officer_id;
            $query='SELECT bdr_physical_recommended, bdr_financial_recommended,bdr_physical_approved,bdr_physical_baseline,bdr_financial_baseline,bdr_physical_planned,bdr_request_type, bdr_request_category_id, rqs_description AS color_code, rqs_name_en AS status_name,
            bdy_name,prj_name, prj_code, bdr_request_status, bdr_id,bdr_budget_year_id,bdr_requested_amount,
     bdr_released_amount,bdr_project_id,bdr_requested_date_gc,
     bdr_released_date_gc,bdr_description,bdr_created_by,bdr_status,bdr_action_remark,1 AS is_editable, 1 AS is_deletable
     FROM pms_budget_request
     INNER JOIN pms_project ON pms_project.prj_id=pms_budget_request.bdr_project_id
     INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_budget_request.bdr_budget_year_id
     INNER JOIN gen_request_status ON gen_request_status.rqs_id=pms_budget_request.bdr_request_status';
if($directorateId>0 && $teamId==0 && $officerId==0){
    //$query .=" INNER JOIN gen_request_followup ON gen_request_followup.rqf_request_id=pms_budget_request.bdr_id AND
    //rqf_forwarded_to_dep_id=".$directorateId."  ";
}else if($directorateId>0 && $teamId>0 && $officerId==0){
    $query .=" INNER JOIN gen_request_followup ON gen_request_followup.rqf_request_id=pms_budget_request.bdr_id AND
    rqf_forwarded_to_dep_id=".$teamId."  ";
}else if($directorateId>0 && $teamId>0 && $officerId>0){
    $query .=" INNER JOIN gen_request_followup ON gen_request_followup.rqf_request_id=pms_budget_request.bdr_id AND
    rqf_forwarded_to_dep_id=".$officerId."  ";
}
     $query .=' WHERE prj_owner_type=1';
if($directorateId>0 && $teamId==0 && $officerId==0){
    //Directorates only view projects that are owned by sectors they are assigned to
    $query .=" AND prj_sector_id IN (SELECT usc_sector_id FROM tbl_user_sector WHERE usc_status=1 AND usc_user_id=".$userId." )";
}

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
$requestCategory=$request->input('bdr_request_category_id');
if(isset($requestCategory) && isset($requestCategory)){
$query .=" AND bdr_request_category_id='".$requestCategory."'";
}
$requestType=$request->input('bdr_request_type');
if(isset($requestType) && isset($requestType)){
$query .=" AND bdr_request_type='".$requestType."'";
}

$sectorId=$request->input('prj_sector_id');
if(isset($sectorId) && isset($sectorId)){
$query .=" AND prj_sector_id='".$sectorId."'";
}

//if user is assinged to a department
if($departmentId > 1){
    //$query .=" AND prj_department_id='".$departmentId."'";
}
//dd($query);
$query.=' ORDER BY bdr_id DESC';
$data_info=DB::select($query);
//$this->getQueryInfo($query);
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
            //START STATUS CHANGE

            $requeststatus=$request->get('bdr_request_status');
            if($requeststatus==3){
                $project_id=$data_info->bdr_project_id;
                $data_info_project = \App\Models\Modelpmsproject::findOrFail($project_id);
                $project_data['prj_project_status_id']=5;
                $project_data['prj_start_date_gc']=$request->input('bdr_released_date_gc');
                $data_info_project->update($project_data);
            }
            //END STATUS CHANGE
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

public function takeAction(Request $request)
{
    $attributeNames = [
        'usc_sector_id'=> trans('form_lang.usc_sector_id')
    ];
    $rules= [
        'usc_sector_id'=> 'max:200'
    ];    
$status = $request->get('request_status');
$requestList = $request->get('request_list');

// Convert to integer array
//$ids = array_map('intval', explode(',', $requestList));
$ids = array_map('intval', (array) $requestList);
// Perform the update securely
$data_info=DB::table('pms_budget_request')
    ->whereIn('bdr_id', $ids)
    ->update(['bdr_request_status' => $status]);

$resultObject= array(
                "changed_data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
}