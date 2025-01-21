<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmssectorinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmssectorinformationController extends MyController
{
 public function __construct()
 {
    parent::__construct();
    //$this->middleware('auth');
}
public function listgrid(Request $request){
   $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
   $permissionData=$this->getPagePermission($request,20);
   if(isset($permissionData) && !empty($permissionData)){
    $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
}
$query="SELECT sci_id,sci_name_or,sci_name_am,sci_name_en,sci_code,prj_sector_category.psc_name AS sector_name, sci_sector_category_id, sci_available_at_region, sci_available_at_zone,sci_available_at_woreda,sci_description,sci_create_time,sci_update_time,sci_delete_time,sci_created_by,sci_status ".$permissionIndex." FROM pms_sector_information ";   
$query .= ' LEFT JOIN prj_sector_category ON pms_sector_information.sci_sector_category_id = prj_sector_category.psc_id'; 
$query .=' WHERE 1=1';
$sciid=$request->input('sci_id');
if(isset($sciid) && isset($sciid)){
    $query .=' AND sci_id="'.$sciid.'"'; 
}
$scinameor=$request->input('sci_name_or');
if(isset($scinameor) && isset($scinameor)){
    $query .=" AND sci_name_or LIKE '%".$scinameor."%'"; 
}
$scinameam=$request->input('sci_name_am');
if(isset($scinameam) && isset($scinameam)){
    $query .=" AND sci_name_am LIKE '%".$scinameam."%'"; 
}
$scinameen=$request->input('sci_name_en');
if(isset($scinameen) && isset($scinameen)){
    $query .=" AND sci_name_en LIKE '%".$scinameen."%'"; 
}
$scicode=$request->input('sci_code');
if(isset($scicode) && isset($scicode)){
    $query .=' AND sci_code="'.$scicode.'"'; 
}
$scisectorcategoryid=$request->input('sci_sector_category_id');
if(isset($scisectorcategoryid) && isset($scisectorcategoryid)){
    $query .=' AND sci_sector_category_id="'.$scisectorcategoryid.'"'; 
}
$query.=' ORDER BY sci_name_or';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'sci_name_or'=> trans('form_lang.sci_name_or'), 
        'sci_name_am'=> trans('form_lang.sci_name_am'), 
        'sci_name_en'=> trans('form_lang.sci_name_en'), 
        'sci_code'=> trans('form_lang.sci_code'), 
        'sci_sector_category_id'=> trans('form_lang.sci_sector_category_id'), 
        'sci_available_at_region'=> trans('form_lang.sci_available_at_region'), 
        'sci_available_at_zone'=> trans('form_lang.sci_available_at_zone'), 
        'sci_available_at_woreda'=> trans('form_lang.sci_available_at_woreda'), 
        'sci_description'=> trans('form_lang.sci_description'), 
        'sci_status'=> trans('form_lang.sci_status'), 
    ];
    $rules= [
        'sci_name_or'=> 'max:200', 
        'sci_name_am'=> 'max:100', 
        'sci_name_en'=> 'max:100', 
        'sci_code'=> 'max:20', 
//'sci_sector_category_id'=> 'max:200', 
        'sci_available_at_region'=> 'integer', 
        'sci_available_at_zone'=> 'integer', 
        'sci_available_at_woreda'=> 'integer', 
        'sci_description'=> 'max:425', 
//'sci_status'=> 'integer', 
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
        $id=$request->get("sci_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('sci_status');
        if($status=="true"){
            $requestData['sci_status']=1;
        }else{
            $requestData['sci_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmssectorinformation::findOrFail($id);
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
        //$requestData['sci_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmssectorinformation::create($requestData);
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
        'sci_name_or'=> trans('form_lang.sci_name_or'), 
        'sci_name_am'=> trans('form_lang.sci_name_am'), 
        'sci_name_en'=> trans('form_lang.sci_name_en'), 
        'sci_code'=> trans('form_lang.sci_code'), 
        'sci_sector_category_id'=> trans('form_lang.sci_sector_category_id'), 
        'sci_available_at_region'=> trans('form_lang.sci_available_at_region'), 
        'sci_available_at_zone'=> trans('form_lang.sci_available_at_zone'), 
        'sci_available_at_woreda'=> trans('form_lang.sci_available_at_woreda'), 
        'sci_description'=> trans('form_lang.sci_description'), 
        'sci_status'=> trans('form_lang.sci_status'), 
    ];
    $rules= [
        'sci_name_or'=> 'max:200', 
        'sci_name_am'=> 'max:100', 
        'sci_name_en'=> 'max:100', 
        'sci_code'=> 'max:20', 
//'sci_sector_category_id'=> 'max:200', 
        'sci_available_at_region'=> 'integer', 
        'sci_available_at_zone'=> 'integer', 
        'sci_available_at_woreda'=> 'integer', 
        'sci_description'=> 'max:425', 
//'sci_status'=> 'integer', 
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
        $requestData['sci_created_by']=auth()->user()->usr_id;
        $status= $request->input('sci_status');
        if($status=="true"){
            $requestData['sci_status']=1;
        }else{
            $requestData['sci_status']=0;
        }
        $data_info=Modelpmssectorinformation::create($requestData);
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
    $id=$request->get("sci_id");
    Modelpmssectorinformation::destroy($id);
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