<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltbluserrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TbluserroleController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
     $pageId=22;
$canListData=$this->getSinglePagePermission($request,$pageId,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    } 
    $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,22);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT rol_name, url_id, url_role_id,url_user_id,url_description,url_create_time,
     url_update_time,url_delete_time,url_created_by,url_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM tbl_user_role ";       
     $query .= " INNER JOIN tbl_roles ON tbl_user_role.url_role_id = tbl_roles.rol_id";
     $query .=' WHERE 1=1';
     $urlid=$request->input('url_id');
if(isset($urlid) && isset($urlid)){
$query .=' AND url_id="'.$urlid.'"'; 
}
$urlroleid=$request->input('url_role_id');
if(isset($urlroleid) && isset($urlroleid)){
$query .=' AND url_role_id="'.$urlroleid.'"'; 
}
$urluserid=$request->input('user_id');
if(isset($urluserid) && isset($urluserid)){
$query .=" AND url_user_id='".$urluserid."'"; 
}

$urldescription=$request->input('url_description');
if(isset($urldescription) && isset($urldescription)){
$query .=' AND url_description="'.$urldescription.'"'; 
}
$urlcreatetime=$request->input('url_create_time');
if(isset($urlcreatetime) && isset($urlcreatetime)){
$query .=' AND url_create_time="'.$urlcreatetime.'"'; 
}
$urlupdatetime=$request->input('url_update_time');
if(isset($urlupdatetime) && isset($urlupdatetime)){
$query .=' AND url_update_time="'.$urlupdatetime.'"'; 
}
$urldeletetime=$request->input('url_delete_time');
if(isset($urldeletetime) && isset($urldeletetime)){
$query .=' AND url_delete_time="'.$urldeletetime.'"'; 
}
$urlcreatedby=$request->input('url_created_by');
if(isset($urlcreatedby) && isset($urlcreatedby)){
$query .=' AND url_created_by="'.$urlcreatedby.'"'; 
}
$urlstatus=$request->input('url_status');
if(isset($urlstatus) && isset($urlstatus)){
$query .=' AND url_status="'.$urlstatus.'"'; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
 $id=$request->get("url_id");
 if(isset($id) && !empty($id)){
    $canEditData=$this->getSinglePagePermission($request,22,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
 }else{
    $canAddData=$this->getSinglePagePermission($request,22,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
 }
    

    $attributeNames = [
'url_role_id'=> trans('form_lang.url_role_id'), 
'url_user_id'=> trans('form_lang.url_user_id'), 
'url_description'=> trans('form_lang.url_description'), 
'url_status'=> trans('form_lang.url_status'), 

    ];
    $rules= [
//'url_role_id'=> 'max:200', 
//'url_user_id'=> 'max:200', 
'url_description'=> 'max:425', 
//'url_status'=> 'integer',
    ];
$validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
$requestData = $request->all();
        $status= $request->input('url_status');
        if($status=="true"){
            $requestData['url_status']=1;
        }else{
            $requestData['url_status']=0;
        }
        if(isset($id) && !empty($id)){
             try{
            $data_info = Modeltbluserrole::findOrFail($id);
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
        }catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}

    }else{
        //Parent Id Assigment
        //$requestData['ins_vehicle_id']=$request->get('master_id');
        //$requestData['url_created_by']=auth()->user()->usr_Id;
        try{
        $data_info=Modeltbluserrole::create($requestData);
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>$data_info,
            "statusCode"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
        return response()->json($resultObject);
        }catch (QueryException $e) {
  return $this->handleDatabaseException($e,"save");
}
    }        
}

public function insertgrid(Request $request)
{
     $canAddData=$this->getSinglePagePermission($request,22,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }

    $attributeNames = [
        'url_role_id'=> trans('form_lang.url_role_id'), 
'url_user_id'=> trans('form_lang.url_user_id'), 
'url_description'=> trans('form_lang.url_description'), 
'url_status'=> trans('form_lang.url_status'), 

    ];
    $rules= [
        //'url_role_id'=> 'max:100', 
//'url_user_id'=> 'max:200', 
'url_description'=> 'max:425', 
//'url_status'=> 'integer', 
    ];
     $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        //$requestData['url_created_by']=auth()->user()->usr_Id;
        $status= $request->input('url_status');
        if($status=="true"){
            $requestData['url_status']=1;
        }else{
            $requestData['url_status']=0;
        }
        $data_info=Modeltbluserrole::create($requestData);
       $data_info["rol_name"]= $request->get("rol_name");
       $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
        return response()->json([
        "data" => $data_info,
        "previledge" => [
            'is_role_editable' => 1,
            'is_role_deletable' => 1
        ],
        "status_code" => 200,
        "type" => "save",
        "errorMsg" => ""
    ]);
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"save");
}
}
public function deletegrid(Request $request)
{
    $id=$request->get("url_id");
    Modeltbluserrole::destroy($id);
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