<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenemailinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenemailinformationController extends MyController
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
        $query='SELECT emi_id,emi_email_template_id,emi_sent_to,emi_sent_date,emi_email_content,emi_description,emi_create_time,emi_update_time,emi_delete_time,emi_created_by,emi_status FROM gen_email_information ';       
        $query .=' WHERE emi_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_email_information_data']=$data_info[0];
        }
        //$data_info = Modelgenemailinformation::findOrFail($id);
        //$data['gen_email_information_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_email_information");
        return view('email_information.show_gen_email_information', $data);
    }
    //Get List
    public function listgrid(Request $request){
       $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
       $permissionData=$this->getPagePermission($request,45);
       if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
    }
    $query="SELECT emi_id,emi_email_template_id,emi_sent_to,emi_sent_date,emi_email_content,emi_description,emi_create_time,emi_update_time,emi_delete_time,emi_created_by,emi_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM gen_email_information ";
    $query .=' WHERE 1=1';
    $emiid=$request->input('emi_id');
    if(isset($emiid) && isset($emiid)){
        $query .=' AND emi_id="'.$emiid.'"'; 
    }
    $emiemailtemplateid=$request->input('emi_email_template_id');
    if(isset($emiemailtemplateid) && isset($emiemailtemplateid)){
        $query .=' AND emi_email_template_id="'.$emiemailtemplateid.'"'; 
    }
    $emisentto=$request->input('emi_sent_to');
    if(isset($emisentto) && isset($emisentto)){
        $query .=" AND emi_sent_to='".$emisentto."'"; 
    }
    $emisentdate=$request->input('emi_sent_date');
    if(isset($emisentdate) && isset($emisentdate)){
        $query .=" AND emi_sent_date LIKE '".$emisentdate."'"; 
    }
    $emistatus=$request->input('emi_status');
    if(isset($emistatus) && isset($emistatus)){
        $query .=' AND emi_status="'.$emistatus.'"'; 
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
        'emi_email_template_id'=> trans('form_lang.emi_email_template_id'), 
        'emi_sent_to'=> trans('form_lang.emi_sent_to'), 
        'emi_sent_date'=> trans('form_lang.emi_sent_date'), 
        'emi_email_content'=> trans('form_lang.emi_email_content'), 
        'emi_description'=> trans('form_lang.emi_description'),
    ];
    $rules= [
        'emi_email_template_id'=> 'max:200', 
        'emi_sent_to'=> 'max:200', 
        'emi_sent_date'=> 'max:200', 
        'emi_email_content'=> 'max:200', 
        'emi_description'=> 'max:425'
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
        $id=$request->get("emi_id");
        $requestData = $request->all();            
        $status= $request->input('emi_status');
        if($status=="true"){
            $requestData['emi_status']=1;
        }else{
            $requestData['emi_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgenemailinformation::findOrFail($id);
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
        $data_info=Modelgenemailinformation::create($requestData);
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
        'emi_email_template_id'=> trans('form_lang.emi_email_template_id'), 
        'emi_sent_to'=> trans('form_lang.emi_sent_to'), 
        'emi_sent_date'=> trans('form_lang.emi_sent_date'), 
        'emi_email_content'=> trans('form_lang.emi_email_content'), 
        'emi_description'=> trans('form_lang.emi_description')
    ];
    $rules= [
        'emi_email_template_id'=> 'max:200', 
        'emi_sent_to'=> 'max:200', 
        'emi_sent_date'=> 'max:200', 
        'emi_email_content'=> 'max:200', 
        'emi_description'=> 'max:425',
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
        //$requestData['emi_created_by']=auth()->user()->usr_Id;
        $status= $request->input('emi_status');
        if($status=="true"){
            $requestData['emi_status']=1;
        }else{
            $requestData['emi_status']=0;
        }
        $requestData['emi_created_by']=1;
        $data_info=Modelgenemailinformation::create($requestData);
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
    $id=$request->get("emi_id");
    Modelgenemailinformation::destroy($id);
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