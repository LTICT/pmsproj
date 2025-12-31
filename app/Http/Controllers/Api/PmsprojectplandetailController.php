<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectplandetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectplandetailController extends MyController
{
 public function __construct()
 {
    parent::__construct();
}
public function listgrid(Request $request){
       // $permissionData=$this->getPagePermission($request,37);
        //dd($permissionData);
   $query='SELECT prp_id,
   prp_plan_id,
   prp_activity_name,
   prp_budget_year_id,
   prp_physical_planned,
   prp_budget_planned,
   prp_pyhsical_planned_month_1,
   prp_pyhsical_actual_month_1,
   prp_pyhsical_planned_month_2,
   prp_pyhsical_actual_month_2,
   prp_pyhsical_planned_month_3,
   prp_pyhsical_actual_month_3,
   prp_pyhsical_planned_month_4,
   prp_pyhsical_actual_month_4,
   prp_pyhsical_planned_month_5,
   prp_pyhsical_actual_month_5,
   prp_pyhsical_planned_month_6,
   prp_pyhsical_actual_month_6,
   prp_pyhsical_planned_month_7,
   prp_pyhsical_actual_month_7,
   prp_pyhsical_planned_month_8,
   prp_pyhsical_actual_month_8,
   prp_pyhsical_planned_month_9,
   prp_pyhsical_actual_month_9,
   prp_pyhsical_planned_month_10,
   prp_pyhsical_actual_month_10,
   prp_pyhsical_planned_month_11,
   prp_pyhsical_actual_month_11,
   prp_pyhsical_planned_month_12,
   prp_pyhsical_actual_month_12,
   prp_finan_planned_month_1,
   prp_finan_actual_month_1,
   prp_finan_planned_month_2,
   prp_finan_actual_month_2,
   prp_finan_planned_month_3,
   prp_finan_actual_month_3,
   prp_finan_planned_month_4,
   prp_finan_actual_month_4,
   prp_finan_planned_month_5,
   prp_finan_actual_month_5,
   prp_finan_planned_month_6,
   prp_finan_actual_month_6,
   prp_finan_planned_month_7,
   prp_finan_actual_month_7,
   prp_finan_planned_month_8,
   prp_finan_actual_month_8,
   prp_finan_planned_month_9,
   prp_finan_actual_month_9,
   prp_finan_planned_month_10,
   prp_finan_actual_month_10,
   prp_finan_planned_month_11,
   prp_finan_actual_month_11,
   prp_finan_planned_month_12,
   prp_finan_actual_month_12,
   prp_record_date_gc,
   prp_description,
   prp_status,
   prp_created_by,
   prp_created_date,
   prp_create_time,
   prp_update_time,
   1 AS is_editable, 1 AS is_deletable,prp_budget_year_id,
   bdy_name AS year_name FROM pms_project_plan_detail ';
   $query .=' INNER JOIN pms_project_plan ON pms_project_plan.pld_id=pms_project_plan_detail.prp_plan_id';
   $query .=' LEFT JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_plan_detail.prp_budget_year_id';
   $query .=' WHERE 1=1';
   $budgetyear=$request->input('budget_year');
   if(isset($budgetyear) && isset($budgetyear)){
    $query .=" AND prp_budget_year_id='".$budgetyear."'"; 
}
$startTime=$request->input('performance_dateStart');
if(isset($startTime) && isset($startTime)){
    $query .=" AND prp_record_date_gc >='".$startTime."'"; 
}
$endTime=$request->input('performance_dateEnd');
if(isset($endTime) && isset($endTime)){
    $query .=" AND prp_record_date_gc <='".$endTime." 23 59 59'"; 
}
//START
$projectplanid=$request->input('prp_plan_id');
$requesttype=$request->input('request_type');
if(isset($projectplanid) && isset($projectplanid)){
    $query .= " AND prp_plan_id = '$projectplanid'";
}
//END
$query.=' ORDER BY prp_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'prp_plan_id'=> trans('form_lang.prp_plan_id'), 
        'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
        'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'),
        'prp_description'=> trans('form_lang.prp_description'), 
        'prp_status'=> trans('form_lang.prp_status'), 
        'prp_created_date'=> trans('form_lang.prp_created_date'),
    ];
    $rules= [
        'prp_plan_id'=> 'required'
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
        $id=$request->get("prp_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectplandetail::findOrFail($id);
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
        $data_info=Modelpmsprojectplandetail::create($requestData);
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
        'prp_plan_id'=> trans('form_lang.prp_plan_id'), 
        'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
        'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'),
        'prp_description'=> trans('form_lang.prp_description'), 
        'prp_status'=> trans('form_lang.prp_status'), 
        'prp_created_date'=> trans('form_lang.prp_created_date')
    ];
    $rules= [
        'prp_plan_id'=> 'required'
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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        $data_info=Modelpmsprojectplandetail::create($requestData);
        if(isset($data_info)){
        }
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
    $id=$request->get("prp_id");
    Modelpmsprojectplandetail::destroy($id);
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
function listRoutes(){
    Route::resource('project_plan_detail', 'PmsprojectplandetailController');
    Route::post('project_plan_detail/listgrid', 'Api\PmsprojectplandetailController@listgrid');
    Route::post('project_plan_detail/insertgrid', 'Api\PmsprojectplandetailController@insertgrid');
    Route::post('project_plan_detail/updategrid', 'Api\PmsprojectplandetailController@updategrid');
    Route::post('project_plan_detail/deletegrid', 'Api\PmsprojectplandetailController@deletegrid');
}
}