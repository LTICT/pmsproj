<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblroles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblrolesController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
 
    public function listgrid(Request $request){
        //$authenticatedUser = $request->authUser;
        //$userId=$authenticatedUser->usr_id;
     $query='SELECT rol_id,rol_name,rol_description,rol_create_time,rol_update_time,rol_delete_time,rol_created_by,rol_status,1 AS is_editable, 0 AS is_deletable,COUNT(*) OVER () AS total_count FROM tbl_roles ';
     $query .=' WHERE 1=1';
     $rolid=$request->input('rol_id');
if(isset($rolid) && isset($rolid)){
$query .=' AND rol_id="'.$rolid.'"'; 
}
$rolname=$request->input('rol_name');
if(isset($rolname) && isset($rolname)){
$query .=' AND rol_name="'.$rolname.'"'; 
}
$roldescription=$request->input('rol_description');
if(isset($roldescription) && isset($roldescription)){
$query .=' AND rol_description="'.$roldescription.'"'; 
}
$rolcreatetime=$request->input('rol_create_time');
if(isset($rolcreatetime) && isset($rolcreatetime)){
$query .=' AND rol_create_time="'.$rolcreatetime.'"'; 
}
$rolupdatetime=$request->input('rol_update_time');
if(isset($rolupdatetime) && isset($rolupdatetime)){
$query .=' AND rol_update_time="'.$rolupdatetime.'"'; 
}
$roldeletetime=$request->input('rol_delete_time');
if(isset($roldeletetime) && isset($roldeletetime)){
$query .=' AND rol_delete_time="'.$roldeletetime.'"'; 
}
$rolcreatedby=$request->input('rol_created_by');
if(isset($rolcreatedby) && isset($rolcreatedby)){
$query .=' AND rol_created_by="'.$rolcreatedby.'"'; 
}
$rolstatus=$request->input('rol_status');
if(isset($rolstatus) && isset($rolstatus)){
$query .=' AND rol_status="'.$rolstatus.'"'; 
}
$query.=' ORDER BY rol_name ASC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>0,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'rol_name'=> trans('form_lang.rol_name'), 
'rol_description'=> trans('form_lang.rol_description'), 
'rol_status'=> trans('form_lang.rol_status'), 

    ];
    $rules= [
        'rol_name'=> 'max:200', 
'rol_description'=> 'max:425', 
//'rol_status'=> 'integer', 

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
        $id=$request->get("rol_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('rol_status');
        if($status=="true"){
            $requestData['rol_status']=1;
        }else{
            $requestData['rol_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblroles::findOrFail($id);
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
        //$requestData['rol_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblroles::create($requestData);
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
        'rol_name'=> trans('form_lang.rol_name'), 
'rol_description'=> trans('form_lang.rol_description'), 
'rol_status'=> trans('form_lang.rol_status'), 

    ];
    $rules= [
        'rol_name'=> 'max:200', 
'rol_description'=> 'max:425', 
//'rol_status'=> 'integer', 

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
        //$requestData['rol_created_by']=auth()->user()->usr_Id;
        $requestData['rol_created_by']=1;
        $status= $request->input('rol_status');
        if($status=="true"){
            $requestData['rol_status']=1;
        }else{
            $requestData['rol_status']=0;
        }
        $data_info=Modeltblroles::create($requestData);
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
    $id=$request->get("rol_id");
    Modeltblroles::destroy($id);
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