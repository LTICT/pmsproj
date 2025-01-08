<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgensmsinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GensmsinformationController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $query='SELECT smi_id,smi_sms_template_id,smi_sent_to,smi_sent_date,smi_sms_content,smi_description,smi_create_time,smi_update_time,smi_delete_time,smi_created_by,smi_status FROM gen_sms_information ';
        $query .=' WHERE smi_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_sms_information_data']=$data_info[0];
        }
        //$data_info = Modelgensmsinformation::findOrFail($id);
        //$data['gen_sms_information_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_sms_information");
        return view('sms_information.show_gen_sms_information', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT smi_id,smi_sms_template_id,smi_sent_to,smi_sent_date,smi_sms_content,smi_description,smi_create_time,smi_update_time,smi_delete_time,smi_created_by,smi_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM gen_sms_information ";
     
     $query .=' WHERE 1=1';
     $smiid=$request->input('smi_id');
if(isset($smiid) && isset($smiid)){
$query .=' AND smi_id="'.$smiid.'"'; 
}
$smismstemplateid=$request->input('smi_sms_template_id');
if(isset($smismstemplateid) && isset($smismstemplateid)){
$query .=' AND smi_sms_template_id="'.$smismstemplateid.'"'; 
}
$smisentto=$request->input('smi_sent_to');
if(isset($smisentto) && isset($smisentto)){
$query .=" AND smi_sent_to LIKE '%".$smisentto."%'"; 
}
$smisentdate=$request->input('smi_sent_date');
if(isset($smisentdate) && isset($smisentdate)){
$query .=' AND smi_sent_date="'.$smisentdate.'"'; 
}
$smicreatedby=$request->input('smi_created_by');
if(isset($smicreatedby) && isset($smicreatedby)){
$query .=' AND smi_created_by="'.$smicreatedby.'"'; 
}
$smistatus=$request->input('smi_status');
if(isset($smistatus) && isset($smistatus)){
$query .=' AND smi_status="'.$smistatus.'"'; 
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
//$query.=' ORDER BY emp_first_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'smi_sms_template_id'=> trans('form_lang.smi_sms_template_id'), 
'smi_sent_to'=> trans('form_lang.smi_sent_to'), 
'smi_sent_date'=> trans('form_lang.smi_sent_date'), 
'smi_sms_content'=> trans('form_lang.smi_sms_content'), 
'smi_description'=> trans('form_lang.smi_description'), 
'smi_status'=> trans('form_lang.smi_status'), 

    ];
    $rules= [
'smi_sent_to'=> 'max:200', 
'smi_sent_date'=> 'max:200', 
'smi_sms_content'=> 'max:200', 
'smi_description'=> 'max:425'

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
        $id=$request->get("smi_id");
        $requestData = $request->all();            
        $status= $request->input('smi_status');
        if($status=="true"){
            $requestData['smi_status']=1;
        }else{
            $requestData['smi_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgensmsinformation::findOrFail($id);
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
        $data_info=Modelgensmsinformation::create($requestData);
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
//Insert Data
public function insertgrid(Request $request)
{
    $attributeNames = [
        'smi_sms_template_id'=> trans('form_lang.smi_sms_template_id'), 
'smi_sent_to'=> trans('form_lang.smi_sent_to'), 
'smi_sent_date'=> trans('form_lang.smi_sent_date'), 
'smi_sms_content'=> trans('form_lang.smi_sms_content'), 
'smi_description'=> trans('form_lang.smi_description'), 
'smi_status'=> trans('form_lang.smi_status'), 
    ];
    $rules= [
'smi_sent_to'=> 'max:200', 
'smi_sent_date'=> 'max:200', 
'smi_sms_content'=> 'max:200', 
'smi_description'=> 'max:425',

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
        //$requestData['smi_created_by']=auth()->user()->usr_Id;
        $status= $request->input('smi_status');
        if($status=="true"){
            $requestData['smi_status']=1;
        }else{
            $requestData['smi_status']=0;
        }
        $requestData['smi_created_by']=1;
        $data_info=Modelgensmsinformation::create($requestData);
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
//Delete Data
public function deletegrid(Request $request)
{
    $id=$request->get("smi_id");
    Modelgensmsinformation::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "deleted_id"=>$id,
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}