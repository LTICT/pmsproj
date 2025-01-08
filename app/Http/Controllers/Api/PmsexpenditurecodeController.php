<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsexpenditurecode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsexpenditurecodeController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,32);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT pec_id,pec_name,pec_code,pec_status,pec_description,pec_created_by,pec_created_date,pec_create_time,pec_update_time ".$permissionIndex."  FROM pms_expenditure_code ";       
     
     $query .=' WHERE 1=1';
     $pecid=$request->input('pec_id');
if(isset($pecid) && isset($pecid)){
$query .=' AND pec_id="'.$pecid.'"'; 
}
$pecname=$request->input('pec_name');
if(isset($pecname) && isset($pecname)){
$query .=" AND pec_name LIKE '%".$pecname."%'";
}
$peccode=$request->input('pec_code');
if(isset($peccode) && isset($peccode)){
$query .=" AND pec_code LIKE '%".$peccode."%'"; 

}
$query.=' ORDER BY pec_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'pec_name'=> trans('form_lang.pec_name'), 
'pec_code'=> trans('form_lang.pec_code'), 
'pec_status'=> trans('form_lang.pec_status'), 
'pec_description'=> trans('form_lang.pec_description'), 
'pec_created_date'=> trans('form_lang.pec_created_date'), 
    ];
    $rules= [
'pec_name'=> 'max:100', 
'pec_code'=> 'max:20', 
'pec_description'=> 'max:425', 

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
        $id=$request->get("pec_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pec_status');
        if($status=="true"){
            $requestData['pec_status']=1;
        }else{
            $requestData['pec_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsexpenditurecode::findOrFail($id);
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
        //$requestData['pec_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsexpenditurecode::create($requestData);
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
        'pec_name'=> trans('form_lang.pec_name'), 
'pec_code'=> trans('form_lang.pec_code'), 
'pec_status'=> trans('form_lang.pec_status'), 
'pec_description'=> trans('form_lang.pec_description'), 
'pec_created_date'=> trans('form_lang.pec_created_date'), 

    ];
    $rules= [
'pec_name'=> 'max:100', 
'pec_code'=> 'max:20', 
'pec_description'=> 'max:425',
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
        $requestData['pec_created_by']=auth()->user()->usr_id;
        $status= $request->input('pec_status');
        if($status=="true"){
            $requestData['pec_status']=1;
        }else{
            $requestData['pec_status']=0;
        }
        $data_info=Modelpmsexpenditurecode::create($requestData);
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
    $id=$request->get("pec_id");
    Modelpmsexpenditurecode::destroy($id);
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