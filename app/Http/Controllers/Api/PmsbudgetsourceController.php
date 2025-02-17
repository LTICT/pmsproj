<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetsource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetsourceController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
        $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,13);
     //dd($permissionData);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }

     $query="SELECT pbs_id,pbs_name_or,pbs_name_am,pbs_name_en,pbs_code,pbs_description,pbs_create_time,pbs_update_time,pbs_delete_time,pbs_created_by
      ".$permissionIndex."  FROM pms_budget_source ";       
     
     $query .=' WHERE 1=1';
     $pbsid=$request->input('pbs_id');
if(isset($pbsid) && isset($pbsid)){
$query .=' AND pbs_id="'.$pbsid.'"'; 
}
$pbsnameor=$request->input('pbs_name_or');
if(isset($pbsnameor) && isset($pbsnameor)){
$query .=" AND pbs_name_or LIKE '%".$pbsnameor."%'"; 
}
$pbsnameam=$request->input('pbs_name_am');
if(isset($pbsnameam) && isset($pbsnameam)){
$query .=' AND pbs_name_am="'.$pbsnameam.'"'; 
}
$pbsnameen=$request->input('pbs_name_en');
if(isset($pbsnameen) && isset($pbsnameen)){
$query .=' AND pbs_name_en="'.$pbsnameen.'"'; 
}
$pbscode=$request->input('pbs_code');
if(isset($pbscode) && isset($pbscode)){
$query .=' AND pbs_code="'.$pbscode.'"'; 
}
$query.=' ORDER BY pbs_name_or';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'pbs_name_or'=> trans('form_lang.pbs_name_or'), 
'pbs_name_am'=> trans('form_lang.pbs_name_am'), 
'pbs_name_en'=> trans('form_lang.pbs_name_en'), 
'pbs_code'=> trans('form_lang.pbs_code'), 
'pbs_description'=> trans('form_lang.pbs_description'), 
'pbs_status'=> trans('form_lang.pbs_status'), 

    ];
    $rules= [
'pbs_name_or'=> 'max:100', 
'pbs_name_am'=> 'max:100', 
'pbs_name_en'=> 'max:100', 
'pbs_code'=> 'max:20', 
'pbs_description'=> 'max:425', 
//'pbs_status'=> 'integer', 

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
        $id=$request->get("pbs_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pbs_status');
        if($status=="true"){
            $requestData['pbs_status']=1;
        }else{
            $requestData['pbs_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetsource::findOrFail($id);
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
        //$requestData['pbs_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetsource::create($requestData);
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
        'pbs_name_or'=> trans('form_lang.pbs_name_or'), 
'pbs_name_am'=> trans('form_lang.pbs_name_am'), 
'pbs_name_en'=> trans('form_lang.pbs_name_en'), 
'pbs_code'=> trans('form_lang.pbs_code'), 
'pbs_description'=> trans('form_lang.pbs_description'), 
'pbs_status'=> trans('form_lang.pbs_status'), 

    ];
    $rules= [
'pbs_name_or'=> 'max:100', 
'pbs_name_am'=> 'max:100', 
'pbs_name_en'=> 'max:100', 
'pbs_code'=> 'max:20', 
'pbs_description'=> 'max:425', 
//'pbs_status'=> 'integer', 

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
        $requestData['pbs_created_by']=auth()->user()->usr_id;
        $status= $request->input('pbs_status');
        if($status=="true"){
            $requestData['pbs_status']=1;
        }else{
            $requestData['pbs_status']=0;
        }
        $data_info=Modelpmsbudgetsource::create($requestData);
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
    $id=$request->get("pbs_id");
    Modelpmsbudgetsource::destroy($id);
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