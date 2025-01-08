<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblpermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenmenubuilderController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
        $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
         //$userId=13;
        if(1==2){
     $query='SELECT pag_link_name AS link_name,pag_description AS link_icon,pag_controller AS link_url,pag_parent AS parent_menu 
     FROM tbl_pages ORDER BY pag_parent DESC'; 
        }else{
           $query='SELECT DISTINCT pag_link_name AS link_name,pag_description AS link_icon,pag_controller AS link_url,
           pag_parent AS parent_menu,pag_order_number
     FROM tbl_pages 
     INNER JOIN tbl_permission ON tbl_permission.pem_page_id=tbl_pages.pag_id
     INNER JOIN tbl_user_role ON tbl_permission.pem_role_id=tbl_user_role.url_role_id WHERE url_user_id='.$userId.' ORDER BY pag_parent,pag_order_number ASC';  
        }
     $pemid=$request->input('pem_id');
if(isset($pemid) && isset($pemid)){
$query .=' AND pem_id="'.$pemid.'"'; 
}
$pempageid=$request->input('pem_page_id');
if(isset($pempageid) && isset($pempageid)){
$query .=' AND pem_page_id="'.$pempageid.'"'; 
}
$pemroleid=$request->input('pem_role_id');
if(isset($pemroleid) && isset($pemroleid)){
$query .=' AND pem_role_id="'.$pemroleid.'"'; 
}
$pemenabled=$request->input('pem_enabled');
if(isset($pemenabled) && isset($pemenabled)){
$query .=' AND pem_enabled="'.$pemenabled.'"'; 
}
$pemedit=$request->input('pem_edit');
if(isset($pemedit) && isset($pemedit)){
$query .=' AND pem_edit="'.$pemedit.'"'; 
}
$peminsert=$request->input('pem_insert');
if(isset($peminsert) && isset($peminsert)){
$query .=' AND pem_insert="'.$peminsert.'"'; 
}
$pemview=$request->input('pem_view');
if(isset($pemview) && isset($pemview)){
$query .=' AND pem_view="'.$pemview.'"'; 
}
$pemdelete=$request->input('pem_delete');
if(isset($pemdelete) && isset($pemdelete)){
$query .=' AND pem_delete="'.$pemdelete.'"'; 
}
$pemshow=$request->input('pem_show');
if(isset($pemshow) && isset($pemshow)){
$query .=' AND pem_show="'.$pemshow.'"'; 
}
$pemsearch=$request->input('pem_search');
if(isset($pemsearch) && isset($pemsearch)){
$query .=' AND pem_search="'.$pemsearch.'"'; 
}
$pemdescription=$request->input('pem_description');
if(isset($pemdescription) && isset($pemdescription)){
$query .=' AND pem_description="'.$pemdescription.'"'; 
}
$pemcreatetime=$request->input('pem_create_time');
if(isset($pemcreatetime) && isset($pemcreatetime)){
$query .=' AND pem_create_time="'.$pemcreatetime.'"'; 
}
$pemupdatetime=$request->input('pem_update_time');
if(isset($pemupdatetime) && isset($pemupdatetime)){
$query .=' AND pem_update_time="'.$pemupdatetime.'"'; 
}
$pemdeletetime=$request->input('pem_delete_time');
if(isset($pemdeletetime) && isset($pemdeletetime)){
$query .=' AND pem_delete_time="'.$pemdeletetime.'"'; 
}
$pemcreatedby=$request->input('pem_created_by');
if(isset($pemcreatedby) && isset($pemcreatedby)){
$query .=' AND pem_created_by="'.$pemcreatedby.'"'; 
}
$pemstatus=$request->input('pem_status');
if(isset($pemstatus) && isset($pemstatus)){
$query .=' AND pem_status="'.$pemstatus.'"'; 
}

     $masterId=$request->input('master_id');
     if(isset($masterId) && !empty($masterId)){
        //set foreign key field name
        //$query .=' AND add_name="'.$masterId.'"'; 
     }
     $search=$request->input('search');
     if(isset($search) && !empty($search)){
       $advanced= $request->input('adva-search');
       if(isset($advanced) && $advanced =='on'){
           $query.=' AND (add_name SOUNDS LIKE "%'.$search.'%" )  ';
       }else{
        $query.=' AND (add_name LIKE "%'.$search.'%")  ';
    }
}
//$query.=' ORDER BY emp_first_name, emp_middle_name, emp_last_name';
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
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}