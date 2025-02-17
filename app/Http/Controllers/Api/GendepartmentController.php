<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgendepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
//PROPERTY OF LT ICT SOLUTION PLC
class GendepartmentController extends MyController
{
 public function __construct()
 {
    parent::__construct();
    //$this->middleware('auth');
}
public function listgrid(Request $request){
   $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
   $permissionData=$this->getPagePermission($request,12);
   //dd($permissionData);
   if(isset($permissionData) && !empty($permissionData)){
    $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
}
$cacheKey = 'department';
$data_info = Cache::rememberForever($cacheKey, function () use ($permissionIndex,$request) {
$query="SELECT dep_id,dep_name_or,dep_name_am,dep_name_en,dep_code,dep_available_at_region,dep_available_at_zone,dep_available_at_woreda,dep_description,dep_create_time,dep_update_time,dep_delete_time,dep_created_by,dep_status ".$permissionIndex." FROM gen_department ";
$query .=' WHERE 1=1';
$depid=$request->input('dep_id');
if(isset($depid) && isset($depid)){
    $query .=' AND dep_id="'.$depid.'"'; 
}
$depnameor=$request->input('dep_name_or');
if(isset($depnameor) && isset($depnameor)){
    $query .=" AND dep_name_or LIKE '%".$depnameor."%'"; 
}
$depnameam=$request->input('dep_name_am');
if(isset($depnameam) && isset($depnameam)){
    $query .=" AND dep_name_am LIKE '%".$depnameam."%'"; 
}
$depnameen=$request->input('dep_name_en');
if(isset($depnameen) && isset($depnameen)){
    $query .=" AND dep_name_en LIKE '%".$depnameen."%'"; 
}
$depcode=$request->input('dep_code');
if(isset($depcode) && isset($depcode)){
    $query .=' AND dep_code="'.$depcode.'"'; 
}
$query.=' ORDER BY dep_name_or';
return DB::select($query);
});
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'dep_name_or'=> trans('form_lang.dep_name_or'), 
        'dep_name_am'=> trans('form_lang.dep_name_am'), 
        'dep_name_en'=> trans('form_lang.dep_name_en'), 
        'dep_code'=> trans('form_lang.dep_code'), 
        'dep_available_at_region'=> trans('form_lang.dep_available_at_region'), 
        'dep_available_at_zone'=> trans('form_lang.dep_available_at_zone'), 
        'dep_available_at_woreda'=> trans('form_lang.dep_available_at_woreda'), 
        'dep_description'=> trans('form_lang.dep_description'), 
        'dep_status'=> trans('form_lang.dep_status'), 
    ];
    $rules= [
        'dep_name_or'=> 'max:100', 
        'dep_name_am'=> 'max:100', 
        'dep_name_en'=> 'max:100', 
        'dep_code'=> 'max:20', 
        'dep_available_at_region'=> 'integer', 
        'dep_available_at_zone'=> 'integer', 
        'dep_available_at_woreda'=> 'integer', 
        'dep_description'=> 'max:425', 
       // 'dep_status'=> 'integer', 
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
        $id=$request->get("dep_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('dep_status');
        if($status=="true"){
            $requestData['dep_status']=1;
        }else{
            $requestData['dep_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgendepartment::findOrFail($id);
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            if($ischanged){
                Cache::forget('department');
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
        //$requestData['dep_created_by']=auth()->user()->usr_Id;
        $data_info=Modelgendepartment::create($requestData);
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
        'dep_name_or'=> trans('form_lang.dep_name_or'), 
        'dep_name_am'=> trans('form_lang.dep_name_am'), 
        'dep_name_en'=> trans('form_lang.dep_name_en'), 
        'dep_code'=> trans('form_lang.dep_code'), 
        'dep_available_at_region'=> trans('form_lang.dep_available_at_region'), 
        'dep_available_at_zone'=> trans('form_lang.dep_available_at_zone'), 
        'dep_available_at_woreda'=> trans('form_lang.dep_available_at_woreda'), 
        'dep_description'=> trans('form_lang.dep_description'), 
        'dep_status'=> trans('form_lang.dep_status'), 
    ];
    $rules= [
      'dep_name_or'=> 'max:100', 
      'dep_name_am'=> 'max:100', 
      'dep_name_en'=> 'max:100', 
      'dep_code'=> 'max:20', 
      'dep_available_at_region'=> 'integer', 
      'dep_available_at_zone'=> 'integer', 
      'dep_available_at_woreda'=> 'integer', 
      'dep_description'=> 'max:425',
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
        //$requestData['dep_created_by']=auth()->user()->usr_Id;
    $requestData['dep_created_by']=1;
    $status= $request->input('dep_status');
    if($status=="true"){
        $requestData['dep_status']=1;
    }else{
        $requestData['dep_status']=0;
    }
    $data_info=Modelgendepartment::create($requestData);
    Cache::forget('department');
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
    $id=$request->get("dep_id");
    Modelgendepartment::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "deleted_id"=>$id,
        "type"=>"delete",
        "errorMsg"=>"",
    );
    return response()->json($resultObject);
}
}