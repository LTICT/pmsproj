<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectvariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectvariationController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
     $query='SELECT prj_name,prj_code,prv_id,prv_requested_amount,prv_released_amount,prv_project_id,prv_requested_date_ec,prv_requested_date_gc,prv_released_date_ec,prv_released_date_gc,prv_description,prv_create_time,prv_update_time,prv_delete_time,prv_created_by,prv_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_variation ';       
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_variation.prv_project_id';      
     $query .=' WHERE 1=1';
$startTime=$request->input('variation_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prv_released_date_gc >='".$startTime."'"; 
}
$endTime=$request->input('variation_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prv_released_date_gc <='".$endTime." 23 59 59'"; 
}
$prvprojectid=$request->input('prv_project_id');
if(isset($prvprojectid) && isset($prvprojectid)){
$query .=" AND prv_project_id='".$prvprojectid."'"; 
}
$prvrequesteddateec=$request->input('prv_requested_date_ec');
if(isset($prvrequesteddateec) && isset($prvrequesteddateec)){
$query .=' AND prv_requested_date_ec="'.$prvrequesteddateec.'"'; 
}
$prvrequesteddategc=$request->input('prv_requested_date_gc');
if(isset($prvrequesteddategc) && isset($prvrequesteddategc)){
$query .=' AND prv_requested_date_gc="'.$prvrequesteddategc.'"'; 
}
$prvreleaseddateec=$request->input('prv_released_date_ec');
if(isset($prvreleaseddateec) && isset($prvreleaseddateec)){
$query .=' AND prv_released_date_ec="'.$prvreleaseddateec.'"'; 
}
$prvreleaseddategc=$request->input('prv_released_date_gc');
if(isset($prvreleaseddategc) && isset($prvreleaseddategc)){
$query .=' AND prv_released_date_gc="'.$prvreleaseddategc.'"'; 
}

$query=$this->getSearchParam($request,$query);
$query.=' ORDER BY prv_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>0);
$permission=$this->ownsProject($request,$prvprojectid);
if($permission !=null)
{
   $previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'prv_requested_amount'=> trans('form_lang.prv_requested_amount'), 
'prv_released_amount'=> trans('form_lang.prv_released_amount'), 
'prv_project_id'=> trans('form_lang.prv_project_id'), 
'prv_requested_date_ec'=> trans('form_lang.prv_requested_date_ec'), 
'prv_requested_date_gc'=> trans('form_lang.prv_requested_date_gc'), 
'prv_released_date_ec'=> trans('form_lang.prv_released_date_ec'), 
'prv_released_date_gc'=> trans('form_lang.prv_released_date_gc'), 
'prv_description'=> trans('form_lang.prv_description')
    ];
    $rules= [
        'prv_requested_amount'=> 'max:200', 
'prv_released_amount'=> 'numeric', 
'prv_requested_date_ec'=> 'max:200', 
'prv_requested_date_gc'=> 'max:200', 
'prv_released_date_ec'=> 'max:10', 
'prv_released_date_gc'=> 'max:10', 
'prv_description'=> 'max:425', 
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
        $id=$request->get("prv_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prv_status');
        if($status=="true"){
            $requestData['prv_status']=1;
        }else{
            $requestData['prv_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectvariation::findOrFail($id);
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
        //$requestData['prv_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectvariation::create($requestData);
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
        'prv_requested_amount'=> trans('form_lang.prv_requested_amount'), 
'prv_released_amount'=> trans('form_lang.prv_released_amount'), 
'prv_project_id'=> trans('form_lang.prv_project_id'), 
'prv_requested_date_ec'=> trans('form_lang.prv_requested_date_ec'), 
'prv_requested_date_gc'=> trans('form_lang.prv_requested_date_gc'), 
'prv_released_date_ec'=> trans('form_lang.prv_released_date_ec'), 
'prv_released_date_gc'=> trans('form_lang.prv_released_date_gc'), 
'prv_description'=> trans('form_lang.prv_description'), 

    ];
    $rules= [
        'prv_requested_amount'=> 'max:200', 
'prv_released_amount'=> 'numeric', 
'prv_requested_date_ec'=> 'max:200', 
'prv_requested_date_gc'=> 'max:200', 
'prv_released_date_ec'=> 'max:10', 
'prv_released_date_gc'=> 'max:10', 
'prv_description'=> 'max:425'
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
        //$requestData['prv_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prv_status');
        if($status=="true"){
            $requestData['prv_status']=1;
        }else{
            $requestData['prv_status']=0;
        }
        $data_info=Modelpmsprojectvariation::create($requestData);
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
    $id=$request->get("prv_id");
    Modelpmsprojectvariation::destroy($id);
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