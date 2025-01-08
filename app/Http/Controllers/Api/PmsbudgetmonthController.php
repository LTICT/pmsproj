<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetmonth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetmonthController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT bdm_id,bdm_month,bdm_name_or,bdm_name_am,bdm_name_en,bdm_code,bdm_description,bdm_create_time,bdm_update_time,bdm_delete_time,bdm_created_by,bdm_status ".$permissionIndex." FROM pms_budget_month ";
     
     $query .=' WHERE 1=1';
     $bdmid=$request->input('bdm_id');
if(isset($bdmid) && isset($bdmid)){
$query .=' AND bdm_id="'.$bdmid.'"'; 
}
$bdmmonth=$request->input('bdm_month');
if(isset($bdmmonth) && isset($bdmmonth)){
$query .=' AND bdm_month="'.$bdmmonth.'"'; 
}
$bdmnameor=$request->input('bdm_name_or');
if(isset($bdmnameor) && isset($bdmnameor)){
$query .=' AND bdm_name_or="'.$bdmnameor.'"'; 
}
$bdmnameam=$request->input('bdm_name_am');
if(isset($bdmnameam) && isset($bdmnameam)){
$query .=' AND bdm_name_am="'.$bdmnameam.'"'; 
}
$bdmnameen=$request->input('bdm_name_en');
if(isset($bdmnameen) && isset($bdmnameen)){
$query .=' AND bdm_name_en="'.$bdmnameen.'"'; 
}
$bdmcode=$request->input('bdm_code');
if(isset($bdmcode) && isset($bdmcode)){
$query .=' AND bdm_code="'.$bdmcode.'"'; 
}
$query.=' ORDER BY bdm_month';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'bdm_month'=> trans('form_lang.bdm_month'), 
'bdm_name_or'=> trans('form_lang.bdm_name_or'), 
'bdm_name_am'=> trans('form_lang.bdm_name_am'), 
'bdm_name_en'=> trans('form_lang.bdm_name_en'), 
'bdm_code'=> trans('form_lang.bdm_code'), 
'bdm_description'=> trans('form_lang.bdm_description'), 
'bdm_status'=> trans('form_lang.bdm_status'), 

    ];
    $rules= [
'bdm_month'=> 'required|max:2', 
'bdm_name_or'=> 'required|max:20', 
'bdm_name_am'=> 'required|max:20', 
'bdm_name_en'=> 'required|max:20', 
'bdm_code'=> 'max:20', 
'bdm_description'=> 'max:425',
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
        $id=$request->get("bdm_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();     
        //$requestData['bdm_created_by']=auth()->user()->usr_id;       
        $status= $request->input('bdm_status');
        if($status=="true"){
            $requestData['bdm_status']=1;
        }else{
            $requestData['bdm_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetmonth::findOrFail($id);
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
        //$requestData['bdm_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetmonth::create($requestData);
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
        'bdm_month'=> trans('form_lang.bdm_month'), 
'bdm_name_or'=> trans('form_lang.bdm_name_or'), 
'bdm_name_am'=> trans('form_lang.bdm_name_am'), 
'bdm_name_en'=> trans('form_lang.bdm_name_en'), 
'bdm_code'=> trans('form_lang.bdm_code'), 
'bdm_description'=> trans('form_lang.bdm_description'), 
'bdm_status'=> trans('form_lang.bdm_status'), 

    ];
    $rules= [
'bdm_month'=> 'required|max:2', 
'bdm_name_or'=> 'required|max:20', 
'bdm_name_am'=> 'required|max:20', 
'bdm_name_en'=> 'required|max:20', 
'bdm_code'=> 'max:20', 
'bdm_description'=> 'max:425', 

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
        $requestData['bdm_created_by']=auth()->user()->usr_id;
        $status= $request->input('bdm_status');
        if($status=="true"){
            $requestData['bdm_status']=1;
        }else{
            $requestData['bdm_status']=0;
        }
        
        $data_info=Modelpmsbudgetmonth::create($requestData);
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
    $id=$request->get("bdm_id");
    Modelpmsbudgetmonth::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>"",
        "deleted_id"=>$id,
    );
    return response()->json($resultObject);
}
}