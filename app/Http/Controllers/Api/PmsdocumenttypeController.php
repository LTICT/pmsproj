<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsdocumenttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsdocumenttypeController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    public function listgrid(Request $request){
    $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,18);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }

     $query="SELECT pdt_id,pdt_doc_name_or,pdt_doc_name_am,pdt_doc_name_en,pdt_code,pdt_description,pdt_create_time,pdt_update_time,pdt_delete_time,pdt_created_by
            ".$permissionIndex."  FROM pms_document_type ";       
     
     $query .=' WHERE 1=1';
     $pdtid=$request->input('pdt_id');
if(isset($pdtid) && isset($pdtid)){
$query .=' AND pdt_id="'.$pdtid.'"'; 
}
$pdtdocnameor=$request->input('pdt_doc_name_or');
if(isset($pdtdocnameor) && isset($pdtdocnameor)){
$query .=" AND pdt_doc_name_or LIKE '%".$pdtdocnameor."%'"; 
}
$pdtdocnameam=$request->input('pdt_doc_name_am');
if(isset($pdtdocnameam) && isset($pdtdocnameam)){
$query .=' AND pdt_doc_name_am="'.$pdtdocnameam.'"'; 
}
$pdtdocnameen=$request->input('pdt_doc_name_en');
if(isset($pdtdocnameen) && isset($pdtdocnameen)){
$query .=' AND pdt_doc_name_en="'.$pdtdocnameen.'"'; 
}
$pdtcode=$request->input('pdt_code');
if(isset($pdtcode) && isset($pdtcode)){
$query .=' AND pdt_code="'.$pdtcode.'"'; 
}
$query.=' ORDER BY pdt_doc_name_or';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'pdt_doc_name_or'=> trans('form_lang.pdt_doc_name_or'), 
'pdt_doc_name_am'=> trans('form_lang.pdt_doc_name_am'), 
'pdt_doc_name_en'=> trans('form_lang.pdt_doc_name_en'), 
'pdt_code'=> trans('form_lang.pdt_code'), 
'pdt_description'=> trans('form_lang.pdt_description'), 
'pdt_status'=> trans('form_lang.pdt_status'), 

    ];
    $rules= [
        'pdt_doc_name_or'=> 'max:100', 
'pdt_doc_name_am'=> 'max:100', 
'pdt_doc_name_en'=> 'max:100', 
'pdt_code'=> 'max:20', 
'pdt_description'=> 'max:425', 
//'pdt_status'=> 'integer', 

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
        $id=$request->get("pdt_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pdt_status');
        if($status=="true"){
            $requestData['pdt_status']=1;
        }else{
            $requestData['pdt_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsdocumenttype::findOrFail($id);
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
        //$requestData['pdt_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsdocumenttype::create($requestData);
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
        'pdt_doc_name_or'=> trans('form_lang.pdt_doc_name_or'), 
'pdt_doc_name_am'=> trans('form_lang.pdt_doc_name_am'), 
'pdt_doc_name_en'=> trans('form_lang.pdt_doc_name_en'), 
'pdt_code'=> trans('form_lang.pdt_code'), 
'pdt_description'=> trans('form_lang.pdt_description'), 
'pdt_status'=> trans('form_lang.pdt_status'), 

    ];
    $rules= [
    'pdt_doc_name_or'=> 'max:100', 
'pdt_doc_name_am'=> 'max:100', 
'pdt_doc_name_en'=> 'max:100', 
'pdt_code'=> 'max:20', 
'pdt_description'=> 'max:425', 
//'pdt_status'=> 'integer', 

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
        $requestData['pdt_created_by']=auth()->user()->usr_id;
        $status= $request->input('pdt_status');
        if($status=="true"){
            $requestData['pdt_status']=1;
        }else{
            $requestData['pdt_status']=0;
        }
        $data_info=Modelpmsdocumenttype::create($requestData);
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
    $id=$request->get("pdt_id");
    Modelpmsdocumenttype::destroy($id);
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