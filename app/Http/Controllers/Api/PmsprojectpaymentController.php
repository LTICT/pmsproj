<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectpayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectpaymentController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 
    public function listgrid(Request $request){
     $query='SELECT pyc_name_or AS payment_category, prj_name,prj_code,prp_id,prp_project_id,prp_type,prp_payment_date_et,prp_payment_date_gc,prp_payment_amount,prp_payment_percentage,prp_description,prp_create_time,prp_update_time,prp_delete_time,prp_created_by,prp_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_payment 
     INNER JOIN pms_project ON pms_project.prj_id=pms_project_payment.prp_project_id
     INNER JOIN pms_payment_category ON pms_payment_category.pyc_id=pms_project_payment.prp_project_id';
     $query .=' WHERE 1=1';
    $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$startTime=$request->input('payment_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prp_payment_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('payment_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prp_payment_date_gc <='".$endTime." 23 59 59'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
//$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
}

$prpprojectid=$request->input('project_id');
if(isset($prpprojectid) && isset($prpprojectid)){
$query .= " AND prp_project_id = '".$prpprojectid."'";

}
$prptype=$request->input('prp_type');
if(isset($prptype) && isset($prptype)){
$query .=" AND prp_type='".$prptype."'"; 
}
$prppaymentdateet=$request->input('prp_payment_date_et');
if(isset($prppaymentdateet) && isset($prppaymentdateet)){
$query .=' AND prp_payment_date_et="'.$prppaymentdateet.'"'; 
}
$prppaymentdategc=$request->input('prp_payment_date_gc');
if(isset($prppaymentdategc) && isset($prppaymentdategc)){
$query .=' AND prp_payment_date_gc="'.$prppaymentdategc.'"'; 
}
$query.=' ORDER BY prp_id DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_type'=> trans('form_lang.prp_type'), 
'prp_payment_date_et'=> trans('form_lang.prp_payment_date_et'), 
'prp_payment_date_gc'=> trans('form_lang.prp_payment_date_gc'), 
'prp_payment_amount'=> trans('form_lang.prp_payment_amount'), 
'prp_payment_percentage'=> trans('form_lang.prp_payment_percentage'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 

    ];
    $rules= [
'prp_type'=> 'max:200', 
'prp_payment_date_et'=> 'max:200', 
'prp_payment_date_gc'=> 'max:200', 
'prp_payment_amount'=> 'numeric', 
'prp_payment_percentage'=> 'numeric', 
'prp_description'=> 'max:425', 
//'prp_status'=> 'integer', 

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
        $id=$request->get("prp_id");
        $requestData = $request->all();            
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectpayment::findOrFail($id);
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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectpayment::create($requestData);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_type'=> trans('form_lang.prp_type'), 
'prp_payment_date_et'=> trans('form_lang.prp_payment_date_et'), 
'prp_payment_date_gc'=> trans('form_lang.prp_payment_date_gc'), 
'prp_payment_amount'=> trans('form_lang.prp_payment_amount'), 
'prp_payment_percentage'=> trans('form_lang.prp_payment_percentage'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 

    ];
    $rules= [
'prp_type'=> 'max:200', 
'prp_payment_date_et'=> 'max:200', 
'prp_payment_date_gc'=> 'max:200', 
'prp_payment_amount'=> 'numeric', 
'prp_payment_percentage'=> 'numeric', 
'prp_description'=> 'max:425', 
//'prp_status'=> 'integer', 

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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $requestData['prp_created_by']=1;
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        $data_info=Modelpmsprojectpayment::create($requestData);
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
    $id=$request->get("prp_id");
    Modelpmsprojectpayment::destroy($id);
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