<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojecthandover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojecthandoverController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}

    public function listgrid(Request $request){
     $query='SELECT prh_budget_year_id, prj_name,prj_code, prh_id,prh_project_id,prh_handover_date_ec,prh_handover_date_gc,prh_description,prh_create_time,prh_update_time,prh_delete_time,prh_created_by,prh_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_handover ';       
      $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_handover.prh_project_id';
     $query .=' WHERE 1=1';
$startTime=$request->input('handover_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prh_handover_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('handover_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prh_handover_date_gc <='".$endTime." 23 59 59'"; 
}

$prhhandoverdateec=$request->input('prh_handover_date_ec');
if(isset($prhhandoverdateec) && isset($prhhandoverdateec)){
$query .=' AND prh_handover_date_ec="'.$prhhandoverdateec.'"'; 
}
$prhhandoverdategc=$request->input('prh_handover_date_gc');
if(isset($prhhandoverdategc) && isset($prhhandoverdategc)){
$query .=' AND prh_handover_date_gc="'.$prhhandoverdategc.'"'; 
}
$prhdescription=$request->input('prh_description');
if(isset($prhdescription) && isset($prhdescription)){
$query .=' AND prh_description="'.$prhdescription.'"'; 
}
//START
$prhprojectid=$request->input('prh_project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($prhprojectid) && isset($prhprojectid)){
$query .= " AND prh_project_id = '$prhprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END

$query.=' ORDER BY prh_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>0);
$permission=$this->ownsProject($request,$prhprojectid);
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
        'prh_project_id'=> trans('form_lang.prh_project_id'), 
'prh_handover_date_ec'=> trans('form_lang.prh_handover_date_ec'), 
'prh_handover_date_gc'=> trans('form_lang.prh_handover_date_gc'), 
'prh_description'=> trans('form_lang.prh_description'), 

    ];
    $rules= [
        'prh_project_id'=> 'max:200', 
'prh_handover_date_ec'=> 'max:200', 
'prh_handover_date_gc'=> 'max:200', 
'prh_description'=> 'max:425'
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
        $id=$request->get("prh_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prh_status');
        if($status=="true"){
            $requestData['prh_status']=1;
        }else{
            $requestData['prh_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojecthandover::findOrFail($id);
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
        //$requestData['prh_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojecthandover::create($requestData);
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
        'prh_project_id'=> trans('form_lang.prh_project_id'), 
'prh_handover_date_ec'=> trans('form_lang.prh_handover_date_ec'), 
'prh_handover_date_gc'=> trans('form_lang.prh_handover_date_gc'), 
'prh_description'=> trans('form_lang.prh_description'), 

    ];
    $rules= [
        'prh_project_id'=> 'max:200', 
'prh_handover_date_ec'=> 'max:200', 
'prh_handover_date_gc'=> 'max:200', 
'prh_description'=> 'max:425', 

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
        //$requestData['prh_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prh_status');
        if($status=="true"){
            $requestData['prh_status']=1;
        }else{
            $requestData['prh_status']=0;
        }
        $data_info=Modelpmsprojecthandover::create($requestData);
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
    $id=$request->get("prh_id");
    Modelpmsprojecthandover::destroy($id);
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