<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelprjsectorcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PrjsectorcategoryController extends MyController
{
 public function __construct()
 {
    parent::__construct();
    //$this->middleware('auth');
}
public function listgrid(Request $request){
   $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
   $permissionData=$this->getPagePermission($request,29);
   if(isset($permissionData) && !empty($permissionData)){
    $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
}
$query="SELECT psc_gov_active,psc_cso_active,psc_citizenship_active,psc_delete_time,psc_created_by,psc_status,psc_id,psc_name,psc_code,psc_description,psc_create_time,psc_update_time ".$permissionIndex." FROM prj_sector_category ";
$query .=' WHERE 1=1';
$pscname=$request->input('psc_name');
if(isset($pscname) && isset($pscname)){
    $query .=" AND psc_name LIKE '%".$pscname."%'"; 
}
$psccode=$request->input('psc_code');
if(isset($psccode) && isset($psccode)){
    $query .=' AND psc_code="'.$psccode.'"'; 
}
$pscsectorid=$request->input('psc_sector_id');
if(isset($pscsectorid) && isset($pscsectorid)){
    $query .=' AND psc_sector_id="'.$pscsectorid.'"'; 
}
$query.=' ORDER BY psc_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'psc_status'=> trans('form_lang.psc_status'), 
        'psc_id'=> trans('form_lang.psc_id'), 
        'psc_name'=> trans('form_lang.psc_name'), 
        'psc_code'=> trans('form_lang.psc_code'), 
        'psc_sector_id'=> trans('form_lang.psc_sector_id'), 
        'psc_description'=> trans('form_lang.psc_description'), 
    ];
    $rules= [
        'psc_id'=> 'max:100', 
        'psc_name'=> 'max:100', 
        'psc_code'=> 'max:20', 
        'psc_description'=> 'max:425', 
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
        $id=$request->get("psc_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('psc_status');
        if($status=="true"){
            $requestData['psc_status']=1;
        }else{
            $requestData['psc_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelprjsectorcategory::findOrFail($id);
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
        //$requestData['psc_created_by']=auth()->user()->usr_Id;
        $data_info=Modelprjsectorcategory::create($requestData);
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
        'psc_status'=> trans('form_lang.psc_status'), 
        'psc_id'=> trans('form_lang.psc_id'), 
        'psc_name'=> trans('form_lang.psc_name'), 
        'psc_code'=> trans('form_lang.psc_code'), 
        'psc_sector_id'=> trans('form_lang.psc_sector_id'), 
        'psc_description'=> trans('form_lang.psc_description'), 
    ];
    $rules= [
     'psc_id'=> 'max:100', 
     'psc_name'=> 'max:100', 
     'psc_code'=> 'max:20', 
     'psc_description'=> 'max:425', 
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
    $requestData['psc_created_by']=auth()->user()->usr_id;
    $status= $request->input('psc_status');
    if($status=="true"){
        $requestData['psc_status']=1;
    }else{
        $requestData['psc_status']=0;
    }
    $data_info=Modelprjsectorcategory::create($requestData);
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
    $id=$request->get("psc_id");
    Modelprjsectorcategory::destroy($id);
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