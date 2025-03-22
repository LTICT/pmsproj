<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblaccesslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblaccesslogController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
     $query='SELECT acl_user_id AS user_id, acl_id,acl_ip,usr_email AS acl_user_id,acl_role_id,acl_object_name,acl_object_id,acl_remark,acl_detail,
     acl_object_action,acl_description,acl_create_time,acl_update_time,acl_delete_time,
     acl_created_by,acl_status,1 AS is_editable, 1 AS is_deletable FROM tbl_access_log ';      
     $query .=' LEFT JOIN tbl_users ON tbl_users.usr_id=tbl_access_log.acl_user_id';
     $query .=' LEFT JOIN tbl_pages ON tbl_pages.pag_id=tbl_access_log.acl_role_id';
     $query .=' WHERE 1=1';
          $startTime=$request->input('log_timeStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND acl_create_time >='".$startTime." 00 00 00'"; 
}
     $endTime=$request->input('log_timeEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND acl_create_time <='".$endTime." 23 59 59'"; 
}
     $aclid=$request->input('acl_id');
if(isset($aclid) && isset($aclid)){
$query .=' AND acl_id="'.$aclid.'"'; 
}
$aclip=$request->input('acl_ip');
if(isset($aclip) && isset($aclip)){
$query .=' AND acl_ip="'.$aclip.'"'; 
}
$acluserid=$request->input('acl_user_id');
if(isset($acluserid) && isset($acluserid)){
$query .=" AND usr_email='".$acluserid."'"; 
}
$aclroleid=$request->input('acl_role_id');
if(isset($aclroleid) && isset($aclroleid)){
$query .=" AND acl_role_id='".$aclroleid."'"; 
}
$aclobjectname=$request->input('acl_object_name');
if(isset($aclobjectname) && isset($aclobjectname)){
$query .=' AND acl_object_name="'.$aclobjectname.'"'; 
}
$userId=$request->input('user_id');
if(isset($userId) && isset($userId)){
$query .=" AND acl_user_id='".$userId."'"; 
}
$query.=' ORDER BY acl_id DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

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
        $id=$request->get("acl_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('acl_status');
        if($status=="true"){
            $requestData['acl_status']=1;
        }else{
            $requestData['acl_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblaccesslog::findOrFail($id);
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
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblaccesslog::create($requestData);
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
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

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
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
        $status= $request->input('acl_status');
        if($status=="true"){
            $requestData['acl_status']=1;
        }else{
            $requestData['acl_status']=0;
        }
        $data_info=Modeltblaccesslog::create($requestData);
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
    $id=$request->get("acl_id");
    Modeltblaccesslog::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}