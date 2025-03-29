<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprocurementparticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprocurementparticipantController extends MyController
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
        $query='SELECT ppp_id,ppp_name_or,ppp_name_en,ppp_name_am,ppp_tin_number,ppp_participant_phone_number,ppp_participant_email,ppp_participant_address,ppp_description,ppp_create_time,ppp_update_time,ppp_delete_time,ppp_created_by,ppp_status FROM pms_procurement_participant ';       
        
        $query .=' WHERE ppp_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_procurement_participant_data']=$data_info[0];
        }
        //$data_info = Modelpmsprocurementparticipant::findOrFail($id);
        //$data['pms_procurement_participant_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_procurement_participant");
        return view('procurement_participant.show_pms_procurement_participant', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT ppp_id,ppp_name_or,ppp_name_en,ppp_name_am,ppp_tin_number,ppp_participant_phone_number,ppp_participant_email,ppp_participant_address,ppp_description,ppp_create_time,ppp_update_time,ppp_delete_time,ppp_created_by,ppp_status ".$permissionIndex." FROM pms_procurement_participant ";
     
     $query .=' WHERE 1=1';
     $pppid=$request->input('ppp_id');
if(isset($pppid) && isset($pppid)){
$query .=' AND ppp_id="'.$pppid.'"'; 
}
$pppnameor=$request->input('ppp_name_or');
if(isset($pppnameor) && isset($pppnameor)){
    $query .= " AND ppp_name_or LIKE '%" . addslashes($pppnameor) . "%'";
}
$pppnameen=$request->input('ppp_name_en');
if(isset($pppnameen) && isset($pppnameen)){
    $query .= " AND ppp_name_en LIKE '%" . addslashes($pppnameen) . "%'";
}
$pppnameam=$request->input('ppp_name_am');
if(isset($pppnameam) && isset($pppnameam)){
    $query .= " AND ppp_name_am LIKE '%" . addslashes($pppnameam) . "%'";
}
$ppptinnumber=$request->input('ppp_tin_number');
if(isset($ppptinnumber) && isset($ppptinnumber)){
$query .=' AND ppp_tin_number="'.$ppptinnumber.'"'; 
}
$pppparticipantphonenumber=$request->input('ppp_participant_phone_number');
if(isset($pppparticipantphonenumber) && isset($pppparticipantphonenumber)){
$query .=' AND ppp_participant_phone_number="'.$pppparticipantphonenumber.'"'; 
}
$pppparticipantemail=$request->input('ppp_participant_email');
if(isset($pppparticipantemail) && isset($pppparticipantemail)){
$query .=' AND ppp_participant_email="'.$pppparticipantemail.'"'; 
}
$pppparticipantaddress=$request->input('ppp_participant_address');
if(isset($pppparticipantaddress) && isset($pppparticipantaddress)){
$query .=' AND ppp_participant_address="'.$pppparticipantaddress.'"'; 
}
$pppdescription=$request->input('ppp_description');
if(isset($pppdescription) && isset($pppdescription)){
$query .=' AND ppp_description="'.$pppdescription.'"'; 
}
$pppcreatetime=$request->input('ppp_create_time');
if(isset($pppcreatetime) && isset($pppcreatetime)){
$query .=' AND ppp_create_time="'.$pppcreatetime.'"'; 
}
$pppupdatetime=$request->input('ppp_update_time');
if(isset($pppupdatetime) && isset($pppupdatetime)){
$query .=' AND ppp_update_time="'.$pppupdatetime.'"'; 
}
$pppdeletetime=$request->input('ppp_delete_time');
if(isset($pppdeletetime) && isset($pppdeletetime)){
$query .=' AND ppp_delete_time="'.$pppdeletetime.'"'; 
}
$pppcreatedby=$request->input('ppp_created_by');
if(isset($pppcreatedby) && isset($pppcreatedby)){
$query .=' AND ppp_created_by="'.$pppcreatedby.'"'; 
}
$pppstatus=$request->input('ppp_status');
if(isset($pppstatus) && isset($pppstatus)){
$query .=' AND ppp_status="'.$pppstatus.'"'; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit,'is_role_deletable'=>$permissionData->pem_delete,'is_role_can_add'=>$permissionData->pem_insert));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'ppp_name_or'=> trans('form_lang.ppp_name_or'), 
'ppp_name_en'=> trans('form_lang.ppp_name_en'), 
'ppp_name_am'=> trans('form_lang.ppp_name_am'), 
'ppp_tin_number'=> trans('form_lang.ppp_tin_number'), 
'ppp_participant_phone_number'=> trans('form_lang.ppp_participant_phone_number'), 
'ppp_participant_email'=> trans('form_lang.ppp_participant_email'), 
'ppp_participant_address'=> trans('form_lang.ppp_participant_address'), 
'ppp_description'=> trans('form_lang.ppp_description'), 
'ppp_status'=> trans('form_lang.ppp_status'), 

    ];
    $rules= [
        'ppp_name_or'=> 'max:50', 
'ppp_name_en'=> 'max:200', 
'ppp_name_am'=> 'max:50', 
'ppp_tin_number'=> 'max:20', 
'ppp_participant_phone_number'=> 'max:15', 
'ppp_participant_email'=> 'max:50', 
'ppp_participant_address'=> 'max:100', 
'ppp_description'=> 'max:425', 
//'ppp_status'=> 'integer', 

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
        $id=$request->get("ppp_id");
        $requestData = $request->all();            
        $status= $request->input('ppp_status');
        if($status=="true"){
            $requestData['ppp_status']=1;
        }else{
            $requestData['ppp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprocurementparticipant::findOrFail($id);
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
        $data_info=Modelpmsprocurementparticipant::create($requestData);
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
        'ppp_name_or'=> trans('form_lang.ppp_name_or'), 
'ppp_name_en'=> trans('form_lang.ppp_name_en'), 
'ppp_name_am'=> trans('form_lang.ppp_name_am'), 
'ppp_tin_number'=> trans('form_lang.ppp_tin_number'), 
'ppp_participant_phone_number'=> trans('form_lang.ppp_participant_phone_number'), 
'ppp_participant_email'=> trans('form_lang.ppp_participant_email'), 
'ppp_participant_address'=> trans('form_lang.ppp_participant_address'), 
'ppp_description'=> trans('form_lang.ppp_description'), 
'ppp_status'=> trans('form_lang.ppp_status'), 

    ];
    $rules= [
        'ppp_name_or'=> 'max:50', 
'ppp_name_en'=> 'max:200', 
'ppp_name_am'=> 'max:50', 
'ppp_tin_number'=> 'max:20', 
'ppp_participant_phone_number'=> 'max:15', 
'ppp_participant_email'=> 'max:50', 
'ppp_participant_address'=> 'max:100', 
'ppp_description'=> 'max:425', 
//ppp_status'=> 'integer', 

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
        $requestData['ppp_created_by']=auth()->user()->usr_id;
        $status= $request->input('ppp_status');
        if($status=="true"){
            $requestData['ppp_status']=1;
        }else{
            $requestData['ppp_status']=0;
        }
        //$requestData['ppp_created_by']=1;
        $data_info=Modelpmsprocurementparticipant::create($requestData);
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
    $id=$request->get("ppp_id");
    Modelpmsprocurementparticipant::destroy($id);
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
function listRoutes(){
    Route::resource('procurement_participant', 'PmsprocurementparticipantController');
    Route::post('procurement_participant/listgrid', 'Api\PmsprocurementparticipantController@listgrid');
    Route::post('procurement_participant/insertgrid', 'Api\PmsprocurementparticipantController@insertgrid');
    Route::post('procurement_participant/updategrid', 'Api\PmsprocurementparticipantController@updategrid');
    Route::post('procurement_participant/deletegrid', 'Api\PmsprocurementparticipantController@deletegrid');
}
}