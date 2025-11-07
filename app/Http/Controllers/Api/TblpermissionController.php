<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblpermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
//PROPERTY OF LT ICT SOLUTION PLC
class TblpermissionController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}  
    public function listgrid(Request $request){
        $pemroleid=$request->input('pem_role_id');
     $query='SELECT pag_system_module AS page_category, pag_id,pag_name,pem_page_id,pem_id,pem_role_id,pem_enabled,pem_edit,pem_insert,pem_view,pem_delete,pem_show,pem_search,pem_description,
     pem_create_time,pem_update_time,pem_delete_time,pem_created_by,pem_status,1 AS is_editable, 1 AS is_deletable FROM tbl_pages
     LEFT JOIN tbl_permission ON tbl_pages.pag_id=tbl_permission.pem_page_id AND pem_role_id='.$pemroleid.'';       
     
     $pemid=$request->input('pem_id');
if(isset($pemid) && isset($pemid)){
$query .=' AND pem_id="'.$pemid.'"'; 
}
if(isset($pempageid) && isset($pempageid)){
//$query .=' AND pem_page_id="'.$pempageid.'"'; 
}
$pemroleid=$request->input('pem_role_id');
if(isset($pemroleid) && isset($pemroleid)){
$query .=" AND pem_role_id='".$pemroleid."'"; 
}
$pemenabled=$request->input('pem_enabled');
if(isset($pemenabled) && isset($pemenabled)){
$query .=' AND pem_enabled="'.$pemenabled.'"'; 
}
//$query .=' WHERE pag_status=1';
$query.=' ORDER BY pem_id,pag_name ASC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

public function listroleassignedpermission(Request $request){
        $pemroleid=$request->input('pem_role_id');
     $query='SELECT pag_id,pag_name,pem_page_id,pem_id,pem_role_id,pem_enabled,pem_edit,pem_insert,pem_view,pem_delete,pem_show,pem_search,pem_description,
     1 AS is_editable, 1 AS is_deletable FROM tbl_pages
     INNER JOIN tbl_permission ON tbl_pages.pag_id=tbl_permission.pem_page_id 
     WHERE pem_role_id='.$pemroleid.'';       
     //$query .=' WHERE 1=1';
     $pemid=$request->input('pem_id');
if(isset($pemid) && isset($pemid)){
$query .=' AND pem_id="'.$pemid.'"'; 
}

if(isset($pempageid) && isset($pempageid)){
//$query .=' AND pem_page_id="'.$pempageid.'"'; 
}
$pemroleid=$request->input('pem_role_id');
if(isset($pemroleid) && isset($pemroleid)){
$query .=" AND pem_role_id='".$pemroleid."'"; 
}
$pemenabled=$request->input('pem_enabled');
if(isset($pemenabled) && isset($pemenabled)){
$query .=' AND pem_enabled="'.$pemenabled.'"'; 
}
$query.=' ORDER BY pem_id';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

public function listuserassignedpermission(Request $request){
        $pemroleid=$request->input('pem_role_id');
     $query='SELECT pag_id,pag_name,pem_page_id,pem_id,pem_role_id,pem_enabled,pem_edit,pem_insert,pem_view,pem_delete,pem_show,pem_search,pem_description,
     1 AS is_editable, 1 AS is_deletable 
     FROM tbl_pages
     INNER JOIN tbl_permission ON tbl_pages.pag_id=tbl_permission.pem_page_id 
     INNER JOIN tbl_roles ON tbl_roles.rol_id=tbl_permission.pem_role_id 
     INNER JOIN tbl_roles ON tbl_roles.rol_id=tbl_permission.pem_role_id 
     WHERE pem_role_id='.$pemroleid.'';       
     //$query .=' WHERE 1=1';
     $pemid=$request->input('pem_id');
if(isset($pemid) && isset($pemid)){
$query .=' AND pem_id="'.$pemid.'"'; 
}

if(isset($pempageid) && isset($pempageid)){
//$query .=' AND pem_page_id="'.$pempageid.'"'; 
}
$pemroleid=$request->input('pem_role_id');
if(isset($pemroleid) && isset($pemroleid)){
$query .=" AND pem_role_id='".$pemroleid."'"; 
}
$pemenabled=$request->input('pem_enabled');
if(isset($pemenabled) && isset($pemenabled)){
$query .=' AND pem_enabled="'.$pemenabled.'"'; 
}
$query.=' ORDER BY pem_id';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

public function updategrid(Request $request)
{
    $attributeNames = [
        'pem_page_id'=> trans('form_lang.pem_page_id'), 
'pem_role_id'=> trans('form_lang.pem_role_id'), 
'pem_enabled'=> trans('form_lang.pem_enabled'), 
'pem_edit'=> trans('form_lang.pem_edit'), 
'pem_insert'=> trans('form_lang.pem_insert'), 
'pem_view'=> trans('form_lang.pem_view'), 
'pem_delete'=> trans('form_lang.pem_delete'), 
'pem_show'=> trans('form_lang.pem_show'), 
'pem_search'=> trans('form_lang.pem_search'), 
'pem_description'=> trans('form_lang.pem_description'), 
'pem_status'=> trans('form_lang.pem_status'), 

    ];
    $rules= [
        'pem_page_id'=> 'max:200', 
'pem_role_id'=> 'max:200', 
'pem_enabled'=> 'max:2', 
'pem_edit'=> 'max:2', 
'pem_insert'=> 'max:2', 
'pem_view'=> 'max:2', 
'pem_delete'=> 'max:2', 
'pem_show'=> 'max:2', 
'pem_search'=> 'max:2', 
'pem_description'=> 'max:425', 
//'pem_status'=> 'integer', 

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
        $id=$request->get("pem_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pem_status');
        if($status=="true"){
            $requestData['pem_status']=1;
        }else{
            $requestData['pem_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblpermission::findOrFail($id);
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            $data_info['pag_id']=$request->get('pag_id');
            $data_info['pag_name']=$request->get('pag_name');
            $data_info['is_editable']=1;
            $data_info['is_deletable']=1;
            if($ischanged){
                Cache::flush();
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
        //$requestData['pem_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblpermission::create($requestData);
        Cache::flush();
        $data_info['pag_id']=$request->get('pag_id');
        $data_info['pag_name']=$request->get('pag_name');
        $data_info['is_editable']=1;
            $data_info['is_deletable']=1;
         $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "status_code"=>200,
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
        'pem_page_id'=> trans('form_lang.pem_page_id'), 
'pem_role_id'=> trans('form_lang.pem_role_id'), 
'pem_enabled'=> trans('form_lang.pem_enabled'), 
'pem_edit'=> trans('form_lang.pem_edit'), 
'pem_insert'=> trans('form_lang.pem_insert'), 
'pem_view'=> trans('form_lang.pem_view'), 
'pem_delete'=> trans('form_lang.pem_delete'), 
'pem_show'=> trans('form_lang.pem_show'), 
'pem_search'=> trans('form_lang.pem_search'), 
'pem_description'=> trans('form_lang.pem_description'), 
'pem_status'=> trans('form_lang.pem_status'), 

    ];
    $rules= [
        'pem_page_id'=> 'max:200', 
'pem_role_id'=> 'max:200', 
'pem_enabled'=> 'max:2', 
'pem_edit'=> 'max:2', 
'pem_insert'=> 'max:2', 
'pem_view'=> 'max:2', 
'pem_delete'=> 'max:2', 
'pem_show'=> 'max:2', 
'pem_search'=> 'max:2', 
'pem_description'=> 'max:425', 
//'pem_status'=> 'integer', 

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
        //$requestData['pem_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pem_status');
        if($status=="true"){
            $requestData['pem_status']=1;
        }else{
            $requestData['pem_status']=0;
        }
        $data_info=Modeltblpermission::create($requestData);
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
    $id=$request->get("pem_id");
    Modeltblpermission::destroy($id);
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