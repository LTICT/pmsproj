<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenrequeststatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenrequeststatusController extends MyController
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
        $query='SELECT rqs_id,rqs_name_or,rqs_name_am,rqs_name_en,rqs_description,rqs_create_time,rqs_update_time,rqs_delete_time,rqs_created_by,rqs_status FROM gen_request_status ';       
        
        $query .=' WHERE rqs_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_request_status_data']=$data_info[0];
        }
        //$data_info = Modelgenrequeststatus::findOrFail($id);
        //$data['gen_request_status_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_request_status");
        return view('request_status.show_gen_request_status', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,55);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT rqs_id,rqs_name_or,rqs_name_am,rqs_name_en,rqs_description,rqs_create_time,rqs_update_time,rqs_delete_time,rqs_created_by,rqs_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM gen_request_status ";
     
     $query .=' WHERE 1=1';
     $rqsid=$request->input('rqs_id');
if(isset($rqsid) && isset($rqsid)){
$query .=' AND rqs_id="'.$rqsid.'"'; 
}
$rqsnameor=$request->input('rqs_name_or');
if(isset($rqsnameor) && isset($rqsnameor)){
$query .=' AND rqs_name_or="'.$rqsnameor.'"'; 
}
$rqsnameam=$request->input('rqs_name_am');
if(isset($rqsnameam) && isset($rqsnameam)){
$query .=' AND rqs_name_am="'.$rqsnameam.'"'; 
}
$rqsnameen=$request->input('rqs_name_en');
if(isset($rqsnameen) && isset($rqsnameen)){
$query .=' AND rqs_name_en="'.$rqsnameen.'"'; 
}
$rqsdescription=$request->input('rqs_description');
if(isset($rqsdescription) && isset($rqsdescription)){
$query .=' AND rqs_description="'.$rqsdescription.'"'; 
}
$rqscreatetime=$request->input('rqs_create_time');
if(isset($rqscreatetime) && isset($rqscreatetime)){
$query .=' AND rqs_create_time="'.$rqscreatetime.'"'; 
}
$rqsupdatetime=$request->input('rqs_update_time');
if(isset($rqsupdatetime) && isset($rqsupdatetime)){
$query .=' AND rqs_update_time="'.$rqsupdatetime.'"'; 
}
$rqsdeletetime=$request->input('rqs_delete_time');
if(isset($rqsdeletetime) && isset($rqsdeletetime)){
$query .=' AND rqs_delete_time="'.$rqsdeletetime.'"'; 
}
$rqscreatedby=$request->input('rqs_created_by');
if(isset($rqscreatedby) && isset($rqscreatedby)){
$query .=' AND rqs_created_by="'.$rqscreatedby.'"'; 
}
$rqsstatus=$request->input('rqs_status');
if(isset($rqsstatus) && isset($rqsstatus)){
$query .=' AND rqs_status="'.$rqsstatus.'"'; 
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
        'rqs_name_or'=> trans('form_lang.rqs_name_or'), 
'rqs_name_am'=> trans('form_lang.rqs_name_am'), 
'rqs_name_en'=> trans('form_lang.rqs_name_en'), 
'rqs_description'=> trans('form_lang.rqs_description'), 
'rqs_status'=> trans('form_lang.rqs_status'), 

    ];
    $rules= [
        'rqs_name_or'=> 'max:200', 
'rqs_name_am'=> 'max:60', 
'rqs_name_en'=> 'max:60', 
'rqs_description'=> 'max:425', 
//'rqs_status'=> 'integer', 

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
        $id=$request->get("rqs_id");
        $requestData = $request->all();            
        $status= $request->input('rqs_status');
        if($status=="true"){
            $requestData['rqs_status']=1;
        }else{
            $requestData['rqs_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgenrequeststatus::findOrFail($id);
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
        $data_info=Modelgenrequeststatus::create($requestData);
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
        'rqs_name_or'=> trans('form_lang.rqs_name_or'), 
'rqs_name_am'=> trans('form_lang.rqs_name_am'), 
'rqs_name_en'=> trans('form_lang.rqs_name_en'), 
'rqs_description'=> trans('form_lang.rqs_description'), 
'rqs_status'=> trans('form_lang.rqs_status'), 

    ];
    $rules= [
        'rqs_name_or'=> 'max:200', 
'rqs_name_am'=> 'max:60', 
'rqs_name_en'=> 'max:60', 
'rqs_description'=> 'max:425', 
//'rqs_status'=> 'integer', 

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
        $requestData['rqs_created_by']=auth()->user()->usr_Id;
        $status= $request->input('rqs_status');
        if($status=="true"){
            $requestData['rqs_status']=1;
        }else{
            $requestData['rqs_status']=0;
        }
        $requestData['rqs_created_by']=1;
        $data_info=Modelgenrequeststatus::create($requestData);
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
    $id=$request->get("rqs_id");
    Modelgenrequeststatus::destroy($id);
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
    Route::resource('request_status', 'GenrequeststatusController');
    Route::post('request_status/listgrid', 'Api\GenrequeststatusController@listgrid');
    Route::post('request_status/insertgrid', 'Api\GenrequeststatusController@insertgrid');
    Route::post('request_status/updategrid', 'Api\GenrequeststatusController@updategrid');
    Route::post('request_status/deletegrid', 'Api\GenrequeststatusController@deletegrid');
}
}