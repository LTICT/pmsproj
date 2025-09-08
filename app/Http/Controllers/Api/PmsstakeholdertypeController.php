<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsstakeholdertype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsstakeholdertypeController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    
    public function listgrid(Request $request){
        $canListData=$this->getSinglePagePermission($request,30,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }
        $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
  $permissionData=$this->getPagePermission($request,30);
  if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT sht_id,sht_type_name_or,sht_type_name_am,sht_type_name_en,sht_description,sht_create_time,sht_update_time,sht_delete_time,sht_created_by,sht_status 
     ".$permissionIndex." FROM pms_stakeholder_type ";       
     
     $query .=' WHERE 1=1';
     $shtid=$request->input('sht_id');
if(isset($shtid) && isset($shtid)){
$query .=' AND sht_id="'.$shtid.'"'; 
}
$shttypenameor=$request->input('sht_type_name_or');
if(isset($shttypenameor) && isset($shttypenameor)){
$query .=" AND sht_type_name_or LIKE '%".$shttypenameor."%'"; 
}
$shttypenameam=$request->input('sht_type_name_am');
if(isset($shttypenameam) && isset($shttypenameam)){
$query .=" AND sht_type_name_am LIKE '%".$shttypenameam."%'"; 
}
$shttypenameen=$request->input('sht_type_name_en');
if(isset($shttypenameen) && isset($shttypenameen)){
$query .=' AND sht_type_name_en="'.$shttypenameen.'"'; 
}
$query.=' ORDER BY sht_type_name_or';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
     $id=$request->get("sht_id");
    $canEditData=$this->getSinglePagePermission($request,30,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
    $attributeNames = [
        'sht_type_name_or'=> trans('form_lang.sht_type_name_or'), 
'sht_type_name_am'=> trans('form_lang.sht_type_name_am'), 
'sht_type_name_en'=> trans('form_lang.sht_type_name_en'), 
'sht_description'=> trans('form_lang.sht_description'), 
'sht_status'=> trans('form_lang.sht_status'), 

    ];
    $rules= [
'sht_type_name_or'=> 'max:100', 
'sht_type_name_am'=> 'max:100', 
'sht_type_name_en'=> 'max:100', 
'sht_description'=> 'max:425', 

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('sht_status');
        if($status=="true"){
            $requestData['sht_status']=1;
        }else{
            $requestData['sht_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsstakeholdertype::findOrFail($id);
            if(!isset($data_info) || empty($data_info)){
             return $this->handleUpdateDataException();
            }
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
        //$requestData['sht_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsstakeholdertype::create($requestData);
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>$data_info,
            "statusCode"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
        return response()->json($resultObject);
  }       
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
public function insertgrid(Request $request)
{
    $canAddData=$this->getSinglePagePermission($request,30,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
    $attributeNames = [
        'sht_type_name_or'=> trans('form_lang.sht_type_name_or'), 
'sht_type_name_am'=> trans('form_lang.sht_type_name_am'), 
'sht_type_name_en'=> trans('form_lang.sht_type_name_en'), 
'sht_description'=> trans('form_lang.sht_description'), 
'sht_status'=> trans('form_lang.sht_status'), 

    ];
    $rules= [
'sht_type_name_or'=> 'max:100', 
'sht_type_name_am'=> 'max:100', 
'sht_type_name_en'=> 'max:100', 
'sht_description'=> 'max:425', 

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        $requestData['sht_created_by']=auth()->user()->usr_id;
        $status= $request->input('sht_status');
        if($status=="true"){
            $requestData['sht_status']=1;
        }else{
            $requestData['sht_status']=0;
        }
        $data_info=Modelpmsstakeholdertype::create($requestData);
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
    $id=$request->get("sht_id");
    Modelpmsstakeholdertype::destroy($id);
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