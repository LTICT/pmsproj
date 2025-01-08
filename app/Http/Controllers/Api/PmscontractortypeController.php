<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmscontractortype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmscontractortypeController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
        $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,28);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT cnt_id,cnt_type_name_or,cnt_type_name_am,cnt_type_name_en,cnt_description,cnt_create_time,cnt_update_time,cnt_delete_time,cnt_created_by ".$permissionIndex."  FROM pms_contractor_type";       
     
     $query .=' WHERE 1=1';
     $cntid=$request->input('cnt_id');
if(isset($cntid) && isset($cntid)){
$query .=' AND cnt_id="'.$cntid.'"'; 
}
$cnttypenameor=$request->input('cnt_type_name_or');
if(isset($cnttypenameor) && isset($cnttypenameor)){
$query .=" AND cnt_type_name_or LIKE '%".$cnttypenameor."%'"; 

}
$cnttypenameam=$request->input('cnt_type_name_am');
if(isset($cnttypenameam) && isset($cnttypenameam)){
$query .=' AND cnt_type_name_am="'.$cnttypenameam.'"'; 
}
$cnttypenameen=$request->input('cnt_type_name_en');
if(isset($cnttypenameen) && isset($cnttypenameen)){
$query .=' AND cnt_type_name_en="'.$cnttypenameen.'"'; 
}
$query.=' ORDER BY cnt_type_name_or';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'cnt_type_name_or'=> trans('form_lang.cnt_type_name_or'), 
'cnt_type_name_am'=> trans('form_lang.cnt_type_name_am'), 
'cnt_type_name_en'=> trans('form_lang.cnt_type_name_en'), 
'cnt_description'=> trans('form_lang.cnt_description'), 
'cnt_status'=> trans('form_lang.cnt_status'), 

    ];
    $rules= [
'cnt_type_name_or'=> 'required|max:100', 
'cnt_type_name_am'=> 'required|max:100', 
'cnt_type_name_en'=> 'required|max:100', 
'cnt_description'=> 'max:425', 
//'cnt_status'=> 'integer', 

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
        $id=$request->get("cnt_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('cnt_status');
        if($status=="true"){
            $requestData['cnt_status']=1;
        }else{
            $requestData['cnt_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmscontractortype::findOrFail($id);
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
        //$requestData['cnt_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmscontractortype::create($requestData);
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
        'cnt_type_name_or'=> trans('form_lang.cnt_type_name_or'), 
'cnt_type_name_am'=> trans('form_lang.cnt_type_name_am'), 
'cnt_type_name_en'=> trans('form_lang.cnt_type_name_en'), 
'cnt_description'=> trans('form_lang.cnt_description'), 
'cnt_status'=> trans('form_lang.cnt_status'), 

    ];
    $rules= [
'cnt_type_name_or'=> 'required|max:100', 
'cnt_type_name_am'=> 'required|max:100', 
'cnt_type_name_en'=> 'required|max:100', 
'cnt_description'=> 'max:425', 
//'cnt_status'=> 'integer', 

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
        $requestData['cnt_created_by']=auth()->user()->usr_id;
        $status= $request->input('cnt_status');
        if($status=="true"){
            $requestData['cnt_status']=1;
        }else{
            $requestData['cnt_status']=0;
        }
        $data_info=Modelpmscontractortype::create($requestData);
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
    $id=$request->get("cnt_id");
    Modelpmscontractortype::destroy($id);
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