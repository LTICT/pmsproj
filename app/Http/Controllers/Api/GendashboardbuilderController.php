<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modeltblpermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GendashboardbuilderController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
         $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
        //$userId=1;
         //$userId=13;
     /*    if(1==1){
     $query="SELECT r.rol_name AS role, JSON_ARRAYAGG(JSON_OBJECT( 'name', rd.rod_name,
     'gridArea',rd.rod_display_area, 'width', rd.rod_width, 'height', rd.rod_height,'class_name',
     rd.rod_class,'dashboard_type',rd.rod_dashboard_type, 'end_point',rd.rod_end_point, 'column_list',rd.rod_column_list)) AS components 
     FROM tbl_roles r 
     INNER JOIN tbl_role_dashboard rd ON r.rol_id = rd.rod_role_id
     INNER JOIN tbl_user_role ON r.rol_id=tbl_user_role.url_role_id
     WHERE url_user_id=".$userId." GROUP BY r.rol_id";
         }else{
              $query="SELECT r.rol_name AS role, JSON_ARRAYAGG(JSON_OBJECT( 'name', rd.rod_name,
     'gridArea',rd.rod_display_area, 'width', rd.rod_width, 'height', rd.rod_height,'class_name',
     rd.rod_class,'dashboard_type',rd.rod_dashboard_type, 'end_point',rd.rod_end_point, 'column_list',rd.rod_column_list)) AS components 
     FROM tbl_roles r 
     INNER JOIN tbl_role_dashboard rd ON r.rol_id = rd.rod_role_id
     INNER JOIN tbl_user_role ON r.rol_id=tbl_user_role.url_role_id
     WHERE url_user_id=8 GROUP BY r.rol_id";
         }
$query.=' ORDER BY rod_order_number';
$data_info=DB::select(DB::raw($query));*/
//$resultObject= array("data" =>$data_info);
//return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
//START EXPERIMENT
$query="SELECT rod_group_by,rod_aggregate,rod_grouped_param,rod_class, rod_column_list, rod_class,rod_name,rod_dashboard_type,rod_table_name
     FROM tbl_roles r 
     INNER JOIN tbl_role_dashboard rd ON r.rol_id = rd.rod_role_id
     INNER JOIN tbl_user_role ON r.rol_id=tbl_user_role.url_role_id
     WHERE url_user_id=".$userId."";
     $query.=' ORDER BY rod_order_number';
     $data_info=DB::select($query);
     $combinedArray = [];
foreach($data_info as $dashboard){
//START PROJECT
$objectName=$dashboard->rod_name;
$tableName=$dashboard->rod_table_name;
$dashboardType=$dashboard->rod_dashboard_type;
$className=$dashboard->rod_class;
if($dashboardType=='table'){
   $query="SELECT ".$dashboard->rod_column_list." FROM ".$tableName."";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'chart',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"table","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}else if($dashboardType=='total_count'){
    $countableVar=explode(",",$dashboard->rod_column_list)[0];
     $query="SELECT COUNT(".$countableVar.") AS count_result FROM ".$tableName."";
     
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'total_count',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"total_count","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}else if($dashboardType=='chart'){
    $countableVar=explode(",",$dashboard->rod_column_list);
    $query="SELECT ".$countableVar[0]." AS value, ".$countableVar[1]." AS name FROM ".$tableName."";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'chart',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"chart","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}else if($dashboardType=='group_count'){
    $columnArray=explode(",",$dashboard->rod_column_list);
    $groupBy=$dashboard->rod_group_by;
    $groupedParameter=$dashboard->rod_grouped_param;
    $aggregateType=$dashboard->rod_aggregate;
    $countableVar=explode(",",$dashboard->rod_column_list)[0];
     $query="SELECT ".$groupBy.", ".$aggregateType."(".$groupedParameter.") AS ".$columnArray[1]." FROM ".$tableName." GROUP BY ".$groupBy."";
     
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'group_count',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"group_count","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}
//END PROJECT
if(1==2){
//START PROJECT DOCUMENT
$query2="SELECT prd_id AS value,prd_name AS name FROM pms_project_document";
$data_info2=DB::select(DB::raw($query2));
$resultObject2= array("data" =>$data_info2,"name"=>"project_document", "type"=>'chart',"column_list"=>"prd_id,prd_name","dashboard_type"=>"chart","class_name"=>"col-sm-6");
$combinedArray[] = $resultObject2;
//END PROJECT DOCUMENT
}
}
$resultObject= array("data" =>$combinedArray);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
//END EXPERIMENT
}
public function dashboardData(Request $request){
         $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
$combinedArray = [];
//START PROJECT
$query="SELECT COUNT(prj_id) as count_result FROM pms_project 
INNER JOIN pms_sector_information ON pms_project.prj_sector_id=pms_sector_information.sci_id WHERE 1=1 ";
$query=$this->getSearchParam($request,$query);
//$query .=" GROUP BY sci_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_count", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"total_count","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1) && $data_info1 !=="" && $data_info1[0]->count_result !==null){
$combinedArray[] = $resultObject1; 
}

$query="SELECT sci_name_or, COUNT(prj_id) as count_result FROM pms_project INNER JOIN pms_sector_information ON pms_project.prj_sector_id=pms_sector_information.sci_id WHERE 1=1";
$query=$this->getSearchParam($request,$query);
$query .=" GROUP BY sci_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_by_sector", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"table","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1)){
$combinedArray[] = $resultObject1; 
}

$query="SELECT COUNT(prj_id) as value, add_name_or AS name FROM pms_project INNER JOIN gen_address_structure 
ON pms_project.prj_location_zone_id=gen_address_structure.add_id WHERE 1=1";
$query=$this->getSearchParam($request,$query);
$query .=" GROUP BY add_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_by_address", "type"=>'chart',"column_list"=>"","dashboard_type"=>"chart","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1)){
$combinedArray[] = $resultObject1; 
}

$query="SELECT pyc_name_or AS prp_type, SUM(prp_payment_amount) AS prp_payment_amount
FROM pms_project 
INNER JOIN pms_project_payment ON pms_project_payment.prp_project_id = pms_project.prj_id 
 INNER JOIN pms_payment_category ON pms_payment_category.pyc_id=pms_project_payment.prp_project_id WHERE 1=1 ";
$query=$this->getSearchParam($request,$query);
$query .=" GROUP BY pyc_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_payment", "type"=>'group_count',"column_list"=>"prp_type,prp_payment_amount","dashboard_type"=>"group_count","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1)){
$combinedArray[] = $resultObject1; 
}

if(1==2){
$query="SELECT COUNT(usr_id) as count_result FROM tbl_users ";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_users_count", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"total_count","class_name"=>"col-sm-4");
$combinedArray[] = $resultObject1; 

$query="SELECT SUM(bdr_released_amount) AS count_result
FROM pms_budget_request
INNER JOIN pms_project ON pms_budget_request.bdr_project_id = pms_project.prj_id WHERE 1=1 ";
$query=$this->getSearchParam($request,$query);
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_released_budget", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"total_count","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1) && $data_info1 !=="" && $data_info1[0]->count_result !==null){
$combinedArray[] = $resultObject1; 
}
}
$resultObject= array("data" =>$combinedArray);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
//END EXPERIMENT
}
}