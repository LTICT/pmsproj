<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetyear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetyearController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
    $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,10);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT bdy_id,bdy_name,bdy_code,bdy_description,bdy_create_time,bdy_update_time,bdy_delete_time,bdy_created_by,bdy_status ".$permissionIndex."  FROM pms_budget_year";       
     $query .=' WHERE 1=1';
$bdyname=$request->input('bdy_name');
if(isset($bdyname) && isset($bdyname)){
$query .=" AND bdy_name LIKE '%".$bdyname."%'"; 

}
$bdycode=$request->input('bdy_code');
if(isset($bdycode) && isset($bdycode)){
$query .=" AND bdy_code LIKE '%".$bdycode."%'"; 
}
$query.=' ORDER BY bdy_name DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));

return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
      'bdy_name'=> 'required|max:4', 
'bdy_code'=> 'max:10', 
'bdy_description'=> 'max:425'
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
        $id=$request->get("bdy_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bdy_status');
      /*  if($status=="true"){
            $requestData['bdy_status']=1;
        }else{
            $requestData['bdy_status']=0;
        }*/
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetyear::findOrFail($id);
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
        //$requestData['bdy_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetyear::create($requestData);
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
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
        'bdy_name'=> 'required|max:4', 
'bdy_code'=> 'max:10', 
'bdy_description'=> 'max:425', 
//'bdy_status'=> 'integer', 

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
        $requestData['bdy_created_by']=auth()->user()->usr_id;
        $status= $request->input('bdy_status');
        $data_info=Modelpmsbudgetyear::create($requestData);
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
    $id=$request->get("bdy_id");
    Modelpmsbudgetyear::destroy($id);
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