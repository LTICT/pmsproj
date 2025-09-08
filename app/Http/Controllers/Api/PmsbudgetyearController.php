<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetyear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetyearController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
    $canListData=$this->getSinglePagePermission($request,10,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }
    $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,10);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
$cacheKey = 'all_budget_year';
$data_info = Cache::rememberForever($cacheKey, function () use ($permissionIndex,$request) {
     $query="SELECT bdy_id,bdy_name,bdy_code,bdy_description,bdy_create_time,bdy_update_time,bdy_delete_time,bdy_created_by,bdy_status ".$permissionIndex."  FROM pms_budget_year";       
     $query .=' WHERE 1=1';
$bdyname=$request->input('bdy_name');
if(isset($bdyname) && isset($bdyname)){
$query .=" AND bdy_name LIKE '%".$bdyname."%'";
}
$bdycode=$request->input('bdy_code');
if(isset($bdycode) && isset($bdycode)){
$query .=" AND bdy_code LIKE '%".$bdycode."%'"; 
}
$query.=' ORDER BY bdy_name DESC';
return DB::select($query);
});
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//to populate dropdowns
public function listdropdown(Request $request){
$cacheKey = 'active_budget_year';
$cacheDuration = 60; // Cache for 60 minutes
$data_info = Cache::rememberForever($cacheKey, function () {
$query="SELECT bdy_id,bdy_name,bdy_code FROM pms_budget_year";  
$query .=' WHERE bdy_status=0';
$query.=' ORDER BY bdy_name DESC';
 return DB::select($query);
});
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array());
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

public function updategrid(Request $request)
{
    $id=$request->get("bdy_id");
    $canEditData=$this->getSinglePagePermission($request,10,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }

    $attributeNames = [
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
      'bdy_name'=> 'required|max:4', 
'bdy_code'=> 'max:10', 
'bdy_description'=> 'max:425'
    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();  
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetyear::find($id);
            if(!isset($data_info) || empty($data_info)){
             return $this->handleUpdateDataException();
            }
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            if($ischanged){
        Cache::forget('all_budget_year');
        Cache::forget('active_budget_year');
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
    $canAddData=$this->getSinglePagePermission($request,45,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
    $attributeNames = [
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
        'bdy_name'=> 'required|max:4', 
'bdy_code'=> 'max:10', 
'bdy_description'=> 'max:425', 
//'bdy_status'=> 'integer', 

    ];
$validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        $requestData['bdy_created_by']=auth()->user()->usr_id;
        $status= $request->input('bdy_status');
        $data_info=Modelpmsbudgetyear::create($requestData);
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
        Cache::forget('all_budget_year');
        Cache::forget('active_budget_year');
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
    $id=$request->get("bdy_id");
    Modelpmsbudgetyear::destroy($id);
    Cache::forget('budget_years');
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