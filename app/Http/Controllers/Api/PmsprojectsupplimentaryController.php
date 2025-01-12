<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectsupplimentary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectsupplimentaryController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
     $query='SELECT prj_name,prj_code,prs_id,prs_requested_amount,prs_released_amount,prs_project_id,prs_requested_date_ec,prs_requested_date_gc,prs_released_date_ec,prs_released_date_gc,prs_description,prs_create_time,prs_update_time,prs_delete_time,prs_created_by,prs_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_supplimentary '; 
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_supplimentary.prs_project_id';    
     $query .=' WHERE 1=1';
    $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('supplimentary_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prs_released_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('supplimentary_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prs_released_date_gc <='".$endTime." 23 59 59'"; 
}
$prsprojectid=$request->input('prs_project_id');
if(isset($prsprojectid) && isset($prsprojectid)){
$query .=" AND prs_project_id='".$prsprojectid."'"; 
}
$prsrequesteddateec=$request->input('prs_requested_date_ec');
if(isset($prsrequesteddateec) && isset($prsrequesteddateec)){
$query .=' AND prs_requested_date_ec="'.$prsrequesteddateec.'"'; 
}
$prsrequesteddategc=$request->input('prs_requested_date_gc');
if(isset($prsrequesteddategc) && isset($prsrequesteddategc)){
$query .=' AND prs_requested_date_gc="'.$prsrequesteddategc.'"'; 
}
$prsreleaseddateec=$request->input('prs_released_date_ec');
if(isset($prsreleaseddateec) && isset($prsreleaseddateec)){
$query .=' AND prs_released_date_ec="'.$prsreleaseddateec.'"'; 
}
$prsreleaseddategc=$request->input('prs_released_date_gc');
if(isset($prsreleaseddategc) && isset($prsreleaseddategc)){
$query .=' AND prs_released_date_gc="'.$prsreleaseddategc.'"'; 
}
$query.=' ORDER BY prs_id DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'prs_requested_amount'=> trans('form_lang.prs_requested_amount'), 
'prs_released_amount'=> trans('form_lang.prs_released_amount'), 
'prs_project_id'=> trans('form_lang.prs_project_id'), 
'prs_requested_date_ec'=> trans('form_lang.prs_requested_date_ec'), 
'prs_requested_date_gc'=> trans('form_lang.prs_requested_date_gc'), 
'prs_released_date_ec'=> trans('form_lang.prs_released_date_ec'), 
'prs_released_date_gc'=> trans('form_lang.prs_released_date_gc'), 
'prs_description'=> trans('form_lang.prs_description'), 
    ];
    $rules= [
        'prs_requested_amount'=> 'max:200', 
'prs_released_amount'=> 'numeric', 
'prs_requested_date_ec'=> 'max:200', 
'prs_requested_date_gc'=> 'max:200', 
'prs_released_date_ec'=> 'max:10', 
'prs_released_date_gc'=> 'max:10', 
'prs_description'=> 'max:425',

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
        $id=$request->get("prs_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prs_status');
        if($status=="true"){
            $requestData['prs_status']=1;
        }else{
            $requestData['prs_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectsupplimentary::findOrFail($id);
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
        //$requestData['prs_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectsupplimentary::create($requestData);
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
        'prs_requested_amount'=> trans('form_lang.prs_requested_amount'), 
'prs_released_amount'=> trans('form_lang.prs_released_amount'), 
'prs_project_id'=> trans('form_lang.prs_project_id'), 
'prs_requested_date_ec'=> trans('form_lang.prs_requested_date_ec'), 
'prs_requested_date_gc'=> trans('form_lang.prs_requested_date_gc'), 
'prs_released_date_ec'=> trans('form_lang.prs_released_date_ec'), 
'prs_released_date_gc'=> trans('form_lang.prs_released_date_gc'), 
'prs_description'=> trans('form_lang.prs_description'), 
    ];
    $rules= [
        'prs_requested_amount'=> 'max:200', 
'prs_released_amount'=> 'numeric',
'prs_requested_date_ec'=> 'max:200', 
'prs_requested_date_gc'=> 'max:200', 
'prs_released_date_ec'=> 'max:10', 
'prs_released_date_gc'=> 'max:10', 
'prs_description'=> 'max:425',

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
        //$requestData['prs_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prs_status');
        if($status=="true"){
            $requestData['prs_status']=1;
        }else{
            $requestData['prs_status']=0;
        }
        $data_info=Modelpmsprojectsupplimentary::create($requestData);
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
    $id=$request->get("prs_id");
    Modelpmsprojectsupplimentary::destroy($id);
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