<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmscontractterminationreason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmscontractterminationreasonController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){

    $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,27);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query=" SELECT ctr_id,ctr_reason_name_or,ctr_reason_name_am,ctr_reason_name_en,ctr_description,ctr_create_time,ctr_update_time,ctr_delete_time,ctr_created_by 
         ".$permissionIndex." FROM pms_contract_termination_reason ";       
     
     $query .=' WHERE 1=1';
     $ctrid=$request->input('ctr_id');
if(isset($ctrid) && isset($ctrid)){
$query .=' AND ctr_id="'.$ctrid.'"'; 
}
$ctrreasonnameor=$request->input('ctr_reason_name_or');
if(isset($ctrreasonnameor) && isset($ctrreasonnameor)){
$query .=" AND ctr_reason_name_or LIKE '%".$ctrreasonnameor."%'"; 
}
$ctrreasonnameam=$request->input('ctr_reason_name_am');
if(isset($ctrreasonnameam) && isset($ctrreasonnameam)){
$query .=' AND ctr_reason_name_am="'.$ctrreasonnameam.'"'; 
}
$ctrreasonnameen=$request->input('ctr_reason_name_en');
if(isset($ctrreasonnameen) && isset($ctrreasonnameen)){
$query .=' AND ctr_reason_name_en="'.$ctrreasonnameen.'"'; 
}
$query.=' ORDER BY ctr_reason_name_or';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'ctr_reason_name_or'=> trans('form_lang.ctr_reason_name_or'), 
'ctr_reason_name_am'=> trans('form_lang.ctr_reason_name_am'), 
'ctr_reason_name_en'=> trans('form_lang.ctr_reason_name_en'), 
'ctr_description'=> trans('form_lang.ctr_description'), 
'ctr_status'=> trans('form_lang.ctr_status'), 

    ];
    $rules= [
'ctr_reason_name_or'=> 'max:100', 
'ctr_reason_name_am'=> 'max:100', 
'ctr_reason_name_en'=> 'max:100', 
'ctr_description'=> 'max:425'
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
        $id=$request->get("ctr_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('ctr_status');
        if($status=="true"){
            $requestData['ctr_status']=1;
        }else{
            $requestData['ctr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmscontractterminationreason::findOrFail($id);
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
        //$requestData['ctr_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmscontractterminationreason::create($requestData);
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
        'ctr_reason_name_or'=> trans('form_lang.ctr_reason_name_or'), 
'ctr_reason_name_am'=> trans('form_lang.ctr_reason_name_am'), 
'ctr_reason_name_en'=> trans('form_lang.ctr_reason_name_en'), 
'ctr_description'=> trans('form_lang.ctr_description'), 
'ctr_status'=> trans('form_lang.ctr_status'), 

    ];
    $rules= [
'ctr_reason_name_or'=> 'max:100', 
'ctr_reason_name_am'=> 'max:100', 
'ctr_reason_name_en'=> 'max:100', 
'ctr_description'=> 'max:425'

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
        $requestData['ctr_created_by']=auth()->user()->usr_id;
        $status= $request->input('ctr_status');
        if($status=="true"){
            $requestData['ctr_status']=1;
        }else{
            $requestData['ctr_status']=0;
        }
        $data_info=Modelpmscontractterminationreason::create($requestData);
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
    $id=$request->get("ctr_id");
    Modelpmscontractterminationreason::destroy($id);
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