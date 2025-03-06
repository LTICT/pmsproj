<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectcategoryController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
        //dd(config('constants.PROJ_INFORMATION'));        
$cacheDuration = 60; // Cache for 60 minutes
$permissionIndex=",0 AS is_editable, 0 AS is_deletable";
        $pageId=config('constants.LU_CATEGORY');
$pctStatus=$request->input('pct_status');
$cacheKey = 'project_category';
if(isset($pctStatus) && !empty($pctStatus) && $pctStatus==0){
    $cacheKey = 'project_category_active';
}
          $permissionData=$this->getPagePermission($request,$pageId);
          //dd($permissionData);
          if(isset($permissionData) && !empty($permissionData)){
                $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
             }
$data_info = Cache::rememberForever($cacheKey, function () use ($permissionIndex,$request) {
     $query="SELECT pct_id,pct_name_or,pct_name_am,pct_name_en,pct_code,pct_description,pct_create_time,pct_update_time,pct_delete_time,pct_created_by,pct_status
       ".$permissionIndex." FROM pms_project_category ";
     $query .=' WHERE 1=1';
     $pctid=$request->input('pct_id');
if(isset($pctid) && isset($pctid)){
$query .=' AND pct_id="'.$pctid.'"'; 
}
$pctnameor=$request->input('pct_name_or');
if(isset($pctnameor) && isset($pctnameor)){
$query .=" AND pct_name_or LIKE '%".$pctnameor."%'"; 
}
$pctnameam=$request->input('pct_name_am');
if(isset($pctnameam) && isset($pctnameam)){
$query .=' AND pct_name_am="'.$pctnameam.'"'; 
}
$pctnameen=$request->input('pct_name_en');
if(isset($pctnameen) && isset($pctnameen)){
$query .=' AND pct_name_en="'.$pctnameen.'"'; 
}
$pctcode=$request->input('pct_code');
if(isset($pctcode) && isset($pctcode)){
$query .=' AND pct_code="'.$pctcode.'"'; 
}
$pctStatus=$request->input('pct_status');
if(isset($pctStatus) && isset($pctStatus)){
$query .=" AND pct_status='".$pctStatus."'"; 
}

$query.=' ORDER BY pct_name_or';
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
        'pct_name_or'=> trans('form_lang.pct_name_or'), 
'pct_name_am'=> trans('form_lang.pct_name_am'), 
'pct_name_en'=> trans('form_lang.pct_name_en'), 
'pct_code'=> trans('form_lang.pct_code'), 
'pct_description'=> trans('form_lang.pct_description'), 
'pct_status'=> trans('form_lang.pct_status'), 

    ];
    $rules= [
        'pct_name_or'=> 'max:100', 
'pct_name_am'=> 'max:100', 
'pct_name_en'=> 'max:100', 
'pct_code'=> 'max:20', 
'pct_description'=> 'max:425', 
//'pct_status'=> 'integer', 

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
        $id=$request->get("pct_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectcategory::findOrFail($id);
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            if($ischanged){
                Cache::forget('project_category');
                Cache::forget('project_category_active');
                
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
        //$requestData['pct_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectcategory::create($requestData);
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
        'pct_name_or'=> trans('form_lang.pct_name_or'), 
'pct_name_am'=> trans('form_lang.pct_name_am'), 
'pct_name_en'=> trans('form_lang.pct_name_en'), 
'pct_code'=> trans('form_lang.pct_code'), 
'pct_description'=> trans('form_lang.pct_description'), 
'pct_status'=> trans('form_lang.pct_status'), 

    ];
    $rules= [
 'pct_name_or'=> 'max:100', 
'pct_name_am'=> 'max:100', 
'pct_name_en'=> 'max:100', 
'pct_code'=> 'max:20', 
'pct_description'=> 'max:425',  
//'pct_status'=> 'integer', 

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
        $requestData['pct_created_by']=auth()->user()->usr_id;
        $data_info=Modelpmsprojectcategory::create($requestData);
        Cache::forget('project_category');
        Cache::forget('project_category_active');
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
    $id=$request->get("pct_id");
    Modelpmsprojectcategory::destroy($id);
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