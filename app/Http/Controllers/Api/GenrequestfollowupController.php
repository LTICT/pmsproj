<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenrequestfollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenrequestfollowupController extends MyController
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
        $query='SELECT rqf_id,rqf_request_id,rqf_forwarding_dep_id,rqf_forwarded_to_dep_id,rqf_forwarding_date,rqf_received_date,rqf_description,rqf_create_time,rqf_update_time,rqf_delete_time,rqf_created_by,rqf_status FROM gen_request_followup ';       
        
        $query .=' WHERE rqf_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_request_followup_data']=$data_info[0];
        }
        //$data_info = Modelgenrequestfollowup::findOrFail($id);
        //$data['gen_request_followup_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_request_followup");
        return view('request_followup.show_gen_request_followup', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $query="SELECT rqf_recommended_amount,rqf_recommendation,rqf_recommended_by,rqf_recommended_date,rqf_current_status, rqf_id,rqf_request_id,rqf_forwarding_dep_id,rqf_forwarded_to_dep_id,rqf_forwarding_date,rqf_received_date,rqf_description,rqf_create_time,rqf_update_time,rqf_delete_time,rqf_created_by,rqf_status,1 AS is_editable, 1 AS is_deletable FROM gen_request_followup ";
     
     $query .=' WHERE 1=1';
$rqfrequestid=$request->input('rqf_request_id');
if(isset($rqfrequestid) && isset($rqfrequestid)){
$query .=" AND rqf_request_id='".$rqfrequestid."'"; 
}
$rqfforwardingdepid=$request->input('rqf_forwarding_dep_id');
if(isset($rqfforwardingdepid) && isset($rqfforwardingdepid)){
$query .=' AND rqf_forwarding_dep_id="'.$rqfforwardingdepid.'"'; 
}
$rqfforwardedtodepid=$request->input('rqf_forwarded_to_dep_id');
if(isset($rqfforwardedtodepid) && isset($rqfforwardedtodepid)){
$query .=' AND rqf_forwarded_to_dep_id="'.$rqfforwardedtodepid.'"'; 
}
$rqfforwardingdate=$request->input('rqf_forwarding_date');
if(isset($rqfforwardingdate) && isset($rqfforwardingdate)){
$query .=' AND rqf_forwarding_date="'.$rqfforwardingdate.'"'; 
}
$rqfreceiveddate=$request->input('rqf_received_date');
if(isset($rqfreceiveddate) && isset($rqfreceiveddate)){
$query .=' AND rqf_received_date="'.$rqfreceiveddate.'"'; 
}
//$query.=' ORDER BY emp_first_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'rqf_request_id'=> trans('form_lang.rqf_request_id'), 
'rqf_forwarding_dep_id'=> trans('form_lang.rqf_forwarding_dep_id'), 
'rqf_forwarded_to_dep_id'=> trans('form_lang.rqf_forwarded_to_dep_id'), 
'rqf_forwarding_date'=> trans('form_lang.rqf_forwarding_date'), 
'rqf_received_date'=> trans('form_lang.rqf_received_date'), 
'rqf_description'=> trans('form_lang.rqf_description'), 
'rqf_status'=> trans('form_lang.rqf_status'), 

    ];
    $rules= [
'rqf_forwarding_date'=> 'max:200', 
'rqf_received_date'=> 'max:20', 
'rqf_description'=> 'max:425', 
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
        $id=$request->get("rqf_id");
        $requestData = $request->all();            
        $status= $request->input('rqf_status');
        if($status=="true"){
            $requestData['rqf_status']=1;
        }else{
            $requestData['rqf_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgenrequestfollowup::findOrFail($id);
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
        $data_info=Modelgenrequestfollowup::create($requestData);
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
        'rqf_request_id'=> trans('form_lang.rqf_request_id'), 
'rqf_forwarding_dep_id'=> trans('form_lang.rqf_forwarding_dep_id'), 
'rqf_forwarded_to_dep_id'=> trans('form_lang.rqf_forwarded_to_dep_id'), 
'rqf_forwarding_date'=> trans('form_lang.rqf_forwarding_date'), 
'rqf_received_date'=> trans('form_lang.rqf_received_date'), 
'rqf_description'=> trans('form_lang.rqf_description'), 
'rqf_status'=> trans('form_lang.rqf_status'), 

    ];
    $rules= [
'rqf_forwarding_date'=> 'max:200', 
'rqf_received_date'=> 'max:20', 
'rqf_description'=> 'max:425',

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
        $requestData['rqf_created_by']=auth()->user()->usr_Id;
        $status= $request->input('rqf_status');
        if($status=="true"){
            $requestData['rqf_status']=1;
        }else{
            $requestData['rqf_status']=0;
        }
        $requestData['rqf_created_by']=1;
        $data_info=Modelgenrequestfollowup::create($requestData);
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
    $id=$request->get("rqf_id");
    Modelgenrequestfollowup::destroy($id);
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
    Route::resource('request_followup', 'GenrequestfollowupController');
    Route::post('request_followup/listgrid', 'Api\GenrequestfollowupController@listgrid');
    Route::post('request_followup/insertgrid', 'Api\GenrequestfollowupController@insertgrid');
    Route::post('request_followup/updategrid', 'Api\GenrequestfollowupController@updategrid');
    Route::post('request_followup/deletegrid', 'Api\GenrequestfollowupController@deletegrid');
}
}