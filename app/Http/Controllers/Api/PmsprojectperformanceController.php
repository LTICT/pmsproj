<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectperformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectperformanceController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
       // $permissionData=$this->getPagePermission($request,37);
        //dd($permissionData);
     $query='SELECT prp_pyhsical_planned_month_1,
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
    prp_status_month_1,
prp_status_month_2,
prp_status_month_3,
prp_status_month_4,
prp_status_month_5,
prp_status_month_6,
prp_status_month_7,
prp_status_month_8,
prp_status_month_9,
prp_status_month_10,
prp_status_month_11,
prp_status_month_12,
    prp_physical_planned,prp_budget_planned,prp_quarter_id,prp_budget_by_region,prp_physical_by_region,prp_budget_baseline, prp_physical_baseline,
prp_region_approved, prj_name,prj_code,prp_id,prp_project_id,prp_project_status_id,prp_record_date_ec,prp_record_date_gc,prp_total_budget_used,prp_physical_performance,prp_description,prp_status,prp_created_by,prp_created_date,prp_create_time,prp_update_time,prp_termination_reason_id,1 AS is_editable, 1 AS is_deletable,prp_budget_year_id,prp_budget_month_id,
     bdy_name AS year_name,bdm_month AS month_name,prs_status_name_or AS status_name FROM pms_project_performance ';       
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_performance.prp_project_id';
     $query .=' LEFT JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_performance.prp_budget_year_id';
     $query .=' LEFT JOIN pms_budget_month ON pms_budget_month.bdm_id=pms_project_performance.prp_budget_month_id';
     $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id=pms_project_performance.prp_project_status_id';
     $query .=' WHERE 1=1';
$budgetmonth=$request->input('budget_month');
    if(isset($budgetmonth) && isset($budgetmonth)){
    $query .=" AND prp_budget_month_id='".$budgetmonth."'"; 
    }
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

$prpprojectstatusid=$request->input('prp_project_status_id');
if(isset($prpprojectstatusid) && isset($prpprojectstatusid)){
$query .=" AND prp_project_status_id='".$prpprojectstatusid."'"; 
}
//START
$prpprojectid=$request->input('prp_project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($prpprojectid) && isset($prpprojectid)){
$query .= " AND prp_project_id = '$prpprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
$query.=' ORDER BY prp_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>1);
$permission=$this->ownsProject($request,$prpprojectid);
if($permission !=null)
{
   $previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

function getLastStatusold($request){
$var_1 =$request->get("prp_status_month_11");
$var_2 = $request->get("prp_status_month_12");
$var_3 =$request->get("prp_status_month_1");
$var_4 = $request->get("prp_status_month_2");
$var_5 = $request->get("prp_status_month_3");
$var_6 = $request->get("prp_status_month_4");
$var_7 = $request->get("prp_status_month_5");
$var_8 = $request->get("prp_status_month_6");
$var_9 = $request->get("prp_status_month_7");
$var_10 = $request->get("prp_status_month_8");
$var_11 = $request->get("prp_status_month_9");
$var_12 = $request->get("prp_status_month_10");

$maxValue = 0;
$maxVar = '';

for ($i = 1; $i <= 12; $i++) {
    $varName = "var_$i";
    if ($$varName > $maxValue) {
        $maxValue = $$varName;
        $maxVar = $varName;
    }
}

if ($maxValue > 0) {
    //echo "The correct variable is \${$lastValidVar[0]} with value {$lastValidVar[1]}";
   return $maxValue;
} else {
    return 0;
}
}
function getLastStatus($request) {
    // Collect all 12 months into an array
    $months = [
        $request->get("prp_status_month_11"),
        $request->get("prp_status_month_12"),
        $request->get("prp_status_month_1"),
        $request->get("prp_status_month_2"),
        $request->get("prp_status_month_3"),
        $request->get("prp_status_month_4"),
        $request->get("prp_status_month_5"),
        $request->get("prp_status_month_6"),
        $request->get("prp_status_month_7"),
        $request->get("prp_status_month_8"),
        $request->get("prp_status_month_9"),
        $request->get("prp_status_month_10"),
    ];

    // Find the max value
    $maxValue = max($months);

    return $maxValue > 0 ? $maxValue : 0;
}

public function updategrid(Request $request)
{
    $attributeNames = [
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_project_status_id'=> trans('form_lang.prp_project_status_id'), 
'prp_record_date_ec'=> trans('form_lang.prp_record_date_ec'), 
'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'), 
'prp_physical_performance'=> trans('form_lang.prp_physical_performance'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 
'prp_created_date'=> trans('form_lang.prp_created_date'), 
'prp_termination_reason_id'=> trans('form_lang.prp_termination_reason_id'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_project_status_id'=> 'max:200', 
'prp_record_date_ec'=> 'max:200', 
'prp_record_date_gc'=> 'max:200', 
'prp_total_budget_used'=> 'max:200', 
'prp_physical_performance'=> 'max:200', 
'prp_description'=> 'max:100'

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
            $data_info = Modelpmsprojectperformance::findOrFail($id);
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();

            if($ischanged){
                //START PROJECT INFO UPDATE
                //$project_id=$request->get("prp_project_id");
                $project_id=$data_info->prp_project_id;
                    $data_info_project = \App\Models\Modelpmsproject::findOrFail($project_id);
                    if(isset($data_info_project) && !empty($data_info_project)){
                        //for governmental
                        if($data_info_project->prj_owner_type == 1){
                        $status_id=$this->getLastStatus($request);
                        if($status_id > 0){
                            $actualStartDate=$request->input('prp_record_date_gc');
                            if(isset($actualStartDate) && !empty($actualStartDate)){
                            $project_data['prj_start_date_gc']=$actualStartDate;
                            }
                            //$project_data['prj_project_status_id']=$status_id;
                            $project_data['prj_project_status_id']=$status_id;
                            $data_info_project->update($project_data);                        
                        }
                        //for cso
                    }else if($data_info_project->prj_owner_type ==2 ){                       
                            $project_data['prj_project_status_id']=$request->input('prp_project_status_id');
                            $project_data['prj_start_date_gc']=$request->input('prp_record_date_gc');
                            //$data_info_project->update($project_data);
                    }
                }
                //END PROJECT INFO UPDATE
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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectperformance::create($requestData);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_project_status_id'=> trans('form_lang.prp_project_status_id'), 
'prp_record_date_ec'=> trans('form_lang.prp_record_date_ec'), 
'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'), 
'prp_physical_performance'=> trans('form_lang.prp_physical_performance'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 
'prp_created_date'=> trans('form_lang.prp_created_date'), 
'prp_termination_reason_id'=> trans('form_lang.prp_termination_reason_id'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_project_status_id'=> 'max:200', 
'prp_record_date_ec'=> 'max:200', 
'prp_record_date_gc'=> 'max:200', 
'prp_total_budget_used'=> 'max:200', 
'prp_physical_performance'=> 'max:200', 
'prp_description'=> 'max:100', 
//'prp_created_date'=> 'integer',

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
        $data_info=Modelpmsprojectperformance::create($requestData);
        if(isset($data_info)){
            //START PROJECT INFO UPDATE
                $project_id=$request->get("prp_project_id");
//project_id=$data_info->prp_project_id;
                    $data_info_project = \App\Models\Modelpmsproject::findOrFail($project_id);
                    if(isset($data_info_project) && !empty($data_info_project)){
                        //for governmental
                        if($data_info_project->prj_owner_type == 1){
                        $status_id=$this->getLastStatus($request);
                        if(1==1){
                            $actualStartDate=$request->input('prp_record_date_gc');
                            if(isset($actualStartDate) && !empty($actualStartDate)){
                            //$project_data['prj_project_status_id']=$status_id;
                            $project_data['prj_project_status_id']=5;
                            $project_data['prj_start_date_gc']=$actualStartDate;
                            $data_info_project->update($project_data);
                        }
                        }
                        //for cso
                    }else if($data_info_project->prj_owner_type ==2 ){                       
                            $project_data['prj_project_status_id']=$request->input('prp_project_status_id');
                            $project_data['prj_start_date_gc']=$request->input('prp_record_date_gc');
                            $data_info_project->update($project_data);
                    }
                }
                //END PROJECT INFO UPDATE
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
    Modelpmsprojectperformance::destroy($id);
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
    Route::resource('project_performance', 'PmsprojectperformanceController');
    Route::post('project_performance/listgrid', 'Api\PmsprojectperformanceController@listgrid');
    Route::post('project_performance/insertgrid', 'Api\PmsprojectperformanceController@insertgrid');
    Route::post('project_performance/updategrid', 'Api\PmsprojectperformanceController@updategrid');
    Route::post('project_performance/deletegrid', 'Api\PmsprojectperformanceController@deletegrid');
    Route::post('project_performance/search', 'PmsprojectperformanceController@search');
    Route::post('project_performance/getform', 'PmsprojectperformanceController@getForm');
    Route::post('project_performance/getlistform', 'PmsprojectperformanceController@getListForm');

}
}