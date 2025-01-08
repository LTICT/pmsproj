<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request,$id)
    {
        $query='SELECT prj_id,prj_name,prj_code,prj_project_status_id,
        pms_project_category.pct_name_or AS prj_project_category_id,prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,prj_end_date_plan_gc,prj_end_date_plan_et,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number FROM pms_project ';
$query .= ' LEFT JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id';
        $query .=" WHERE prj_id=".$id." ";
        $data_info=DB::select($query);
        if(isset($data_info) && !empty($data_info)){
            $data=$data_info[0];
            $request_role='requester';
            $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
if($userId==9){
    $request_role='approver';
}
            $resultObject= array(
    "data" =>$data,
    "data_available"=>"1",
"request_role"=>$request_role);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }else{
            $resultObject= array(
    "data_available"=>"0");
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }

    }
    public function listgrid(Request $request){

$query='SELECT prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id, prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,
prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,
prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,
prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
prj_end_date_plan_gc,prj_end_date_plan_et,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,
prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable FROM pms_project ';
 $query .=' WHERE 1=1';
 $query=$this->getSearchParam($request,$query);
$prjname=$request->input('prj_name');
if(isset($prjname) && isset($prjname)){
$query .=" AND prj_name='".$prjname."'"; 
}
$prjcode=$request->input('prj_code');
if(isset($prjcode) && isset($prjcode)){
$query .=" AND prj_code='".$prjcode."'"; 
}
$prjprojectstatusid=$request->input('prj_project_status_id');
if(isset($prjprojectstatusid) && isset($prjprojectstatusid)){
$query .=' AND prj_project_status_id="'.$prjprojectstatusid.'"'; 
}
$prjprojectcategoryid=$request->input('prj_project_category_id');
if(isset($prjprojectcategoryid) && isset($prjprojectcategoryid)){
$query .=" AND prj_project_category_id='".$prjprojectcategoryid."'"; 
}
$prjsectorid=$request->input('prj_sector_id');
if(isset($prjsectorid) && isset($prjsectorid)){
$query .=' AND prj_sector_id="'.$prjsectorid.'"'; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}

$prjlocationkebeleid=$request->input('prj_location_kebele_id');
if(isset($prjlocationkebeleid) && isset($prjlocationkebeleid)){
$query .=' AND prj_location_kebele_id="'.$prjlocationkebeleid.'"'; 
}
$prjownerregionid=$request->input('prj_owner_region_id');
if(isset($prjownerregionid) && isset($prjownerregionid)){
//$query .=' AND prj_owner_region_id="'.$prjownerregionid.'"'; 
}
$prjownerzoneid=$request->input('prj_owner_zone_id');
if(isset($prjownerzoneid) && isset($prjownerzoneid)){
//$query .=' AND prj_owner_zone_id="'.$prjownerzoneid.'"'; 
}
$prjownerworedaid=$request->input('prj_owner_woreda_id');
if(isset($prjownerworedaid) && isset($prjownerworedaid)){
//$query .=' AND prj_owner_woreda_id="'.$prjownerworedaid.'"'; 
}
$prjownerkebeleid=$request->input('prj_owner_kebele_id');
if(isset($prjownerkebeleid) && isset($prjownerkebeleid)){
$query .=' AND prj_owner_kebele_id="'.$prjownerkebeleid.'"'; 
}
$prjownerdescription=$request->input('prj_owner_description');
if(isset($prjownerdescription) && isset($prjownerdescription)){
$query .=' AND prj_owner_description="'.$prjownerdescription.'"'; 
}
$prjstartdateet=$request->input('prj_start_date_et');
if(isset($prjstartdateet) && isset($prjstartdateet)){
$query .=' AND prj_start_date_et="'.$prjstartdateet.'"'; 
}
$prjstartdategc=$request->input('prj_start_date_gc');
if(isset($prjstartdategc) && isset($prjstartdategc)){
$query .=' AND prj_start_date_gc="'.$prjstartdategc.'"'; 
}
$prjstartdateplanet=$request->input('prj_start_date_plan_et');
if(isset($prjstartdateplanet) && isset($prjstartdateplanet)){
$query .=' AND prj_start_date_plan_et="'.$prjstartdateplanet.'"'; 
}
$prjstartdateplangc=$request->input('prj_start_date_plan_gc');
if(isset($prjstartdateplangc) && isset($prjstartdateplangc)){
$query .=' AND prj_start_date_plan_gc="'.$prjstartdateplangc.'"'; 
}
$prjenddateactualet=$request->input('prj_end_date_actual_et');
if(isset($prjenddateactualet) && isset($prjenddateactualet)){
$query .=' AND prj_end_date_actual_et="'.$prjenddateactualet.'"'; 
}
$prjenddateactualgc=$request->input('prj_end_date_actual_gc');
if(isset($prjenddateactualgc) && isset($prjenddateactualgc)){
$query .=' AND prj_end_date_actual_gc="'.$prjenddateactualgc.'"'; 
}
$prjenddateplangc=$request->input('prj_end_date_plan_gc');
if(isset($prjenddateplangc) && isset($prjenddateplangc)){
$query .=' AND prj_end_date_plan_gc="'.$prjenddateplangc.'"'; 
}
$prjenddateplanet=$request->input('prj_end_date_plan_et');
if(isset($prjenddateplanet) && isset($prjenddateplanet)){
$query .=' AND prj_end_date_plan_et="'.$prjenddateplanet.'"'; 
}
$query.=' ORDER BY prj_id DESC';
$data_info=DB::select($query);
$authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
/*if($userId==79){
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1),
'allowedTabs'=> [2,4,3,6,7]);
}else{*/
    $resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1),
'allowedTabs'=> $this->getTabPermission($request));
//}
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'prj_name'=> trans('form_lang.prj_name'), 
'prj_code'=> trans('form_lang.prj_code'), 
'prj_project_status_id'=> trans('form_lang.prj_project_status_id'), 
'prj_project_category_id'=> trans('form_lang.prj_project_category_id'), 
'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'), 
'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'), 
'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'), 
'prj_geo_location'=> trans('form_lang.prj_geo_location'), 
'prj_sector_id'=> trans('form_lang.prj_sector_id'), 
'prj_location_region_id'=> trans('form_lang.prj_location_region_id'), 
'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'), 
'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'), 
'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'), 
'prj_location_description'=> trans('form_lang.prj_location_description'), 
'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'), 
'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'), 
'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'), 
'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'), 
'prj_owner_description'=> trans('form_lang.prj_owner_description'), 
'prj_start_date_et'=> trans('form_lang.prj_start_date_et'), 
'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'), 
'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'), 
'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'), 
'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'), 
'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'), 
'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'), 
'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'), 
'prj_outcome'=> trans('form_lang.prj_outcome'), 
'prj_deleted'=> trans('form_lang.prj_deleted'), 
'prj_remark'=> trans('form_lang.prj_remark'), 
'prj_created_date'=> trans('form_lang.prj_created_date'), 
'prj_owner_id'=> trans('form_lang.prj_owner_id'), 
'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'), 
'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'), 

    ];
    $rules= [
        'prj_name'=> 'max:200',
'prj_name_am'=> 'max:200',
'prj_name_en'=> 'max:200', 
'prj_code'=> 'max:20', 
'prj_project_status_id'=> 'max:200', 
'prj_project_category_id'=> 'max:200', 
'prj_project_budget_source_id'=> 'max:200', 
'prj_total_estimate_budget'=> 'max:200', 
'prj_total_actual_budget'=> 'max:200', 
'prj_geo_location'=> 'max:200', 
'prj_sector_id'=> 'integer', 
'prj_location_region_id'=> 'integer', 
'prj_location_zone_id'=> 'integer', 
'prj_location_woreda_id'=> 'integer', 
//'prj_location_kebele_id'=> 'integer', 
'prj_location_description'=> 'max:250', 
'prj_start_date_et'=> 'max:15', 
'prj_start_date_gc'=> 'max:15', 
'prj_start_date_plan_et'=> 'max:15', 
'prj_start_date_plan_gc'=> 'max:15', 
'prj_end_date_actual_et'=> 'max:15', 
'prj_end_date_actual_gc'=> 'max:15', 
'prj_end_date_plan_gc'=> 'max:15', 
'prj_end_date_plan_et'=> 'max:15', 
'prj_outcome'=> 'max:425', 
'prj_remark'=> 'max:100',

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
        $id=$request->get("prj_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prj_status');
        if($status=="true"){
            $requestData['prj_status']=1;
        }else{
            $requestData['prj_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsproject::findOrFail($id);
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
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsproject::create($requestData);
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
        'prj_name'=> trans('form_lang.prj_name'), 
'prj_code'=> trans('form_lang.prj_code'), 
'prj_project_status_id'=> trans('form_lang.prj_project_status_id'), 
'prj_project_category_id'=> trans('form_lang.prj_project_category_id'), 
'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'), 
'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'), 
'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'), 
'prj_geo_location'=> trans('form_lang.prj_geo_location'), 
'prj_sector_id'=> trans('form_lang.prj_sector_id'), 
'prj_location_region_id'=> trans('form_lang.prj_location_region_id'), 
'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'), 
'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'), 
'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'), 
'prj_location_description'=> trans('form_lang.prj_location_description'), 
'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'), 
'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'), 
'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'), 
'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'), 
'prj_owner_description'=> trans('form_lang.prj_owner_description'), 
'prj_start_date_et'=> trans('form_lang.prj_start_date_et'), 
'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'), 
'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'), 
'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'), 
'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'), 
'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'), 
'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'), 
'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'), 
'prj_outcome'=> trans('form_lang.prj_outcome'), 
'prj_deleted'=> trans('form_lang.prj_deleted'), 
'prj_remark'=> trans('form_lang.prj_remark'), 
'prj_created_date'=> trans('form_lang.prj_created_date'), 
'prj_owner_id'=> trans('form_lang.prj_owner_id'), 
'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'), 
'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'), 

    ];
    $rules= [
'prj_name'=> 'max:200',
'prj_name_am'=> 'max:200',
'prj_name_en'=> 'max:200', 
'prj_code'=> 'max:20', 
'prj_project_status_id'=> 'max:200', 
'prj_project_category_id'=> 'max:200', 
'prj_project_budget_source_id'=> 'max:200', 
'prj_total_estimate_budget'=> 'max:200', 
'prj_total_actual_budget'=> 'max:200', 
'prj_geo_location'=> 'max:200', 
'prj_sector_id'=> 'integer', 
'prj_location_region_id'=> 'integer', 
'prj_location_zone_id'=> 'integer', 
'prj_location_woreda_id'=> 'integer', 
//'prj_location_kebele_id'=> 'integer', 
'prj_location_description'=> 'max:200', 
'prj_start_date_et'=> 'max:15', 
'prj_start_date_gc'=> 'max:15', 
'prj_start_date_plan_et'=> 'max:15', 
'prj_start_date_plan_gc'=> 'max:15', 
'prj_end_date_actual_et'=> 'max:15', 
'prj_end_date_actual_gc'=> 'max:15', 
'prj_end_date_plan_gc'=> 'max:15', 
'prj_end_date_plan_et'=> 'max:15', 
'prj_outcome'=> 'max:425', 
'prj_remark'=> 'max:100'
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
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
        $requestData['prj_created_by']=1;
        $status= $request->input('prj_status');
        if($status=="true"){
            $requestData['prj_status']=1;
        }else{
            $requestData['prj_status']=0;
        }
        $userInfo=$this->getUserInfo($request);
        if(isset($userInfo)){
            $requestData['prj_owner_region_id']=1;
            $requestData['prj_owner_zone_id']=$userInfo->usr_zone_id;
            $requestData['prj_owner_woreda_id']=$userInfo->usr_woreda_id;
            $requestData['prj_sector_id']=$userInfo->usr_sector_id;
        }
        $data_info=Modelpmsproject::create($requestData);
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
    $id=$request->get("prj_id");
    Modelpmsproject::destroy($id);
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