<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetrequesttask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetrequesttaskController extends MyController
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
        $query='SELECT brt_id,brt_task_name,brt_measurement,brt_budget_request_id,brt_previous_year_physical,brt_previous_year_financial,brt_current_year_physical,brt_current_year_financial,brt_next_year_physical,brt_next_year_financial,brt_description,brt_create_time,brt_update_time,brt_delete_time,brt_created_by,brt_status FROM pms_budget_request_task ';       
        
        $query .=' WHERE brt_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_request_task_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetrequesttask::findOrFail($id);
        //$data['pms_budget_request_task_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_request_task");
        return view('budget_request_task.show_pms_budget_request_task', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT brt_id,brt_task_name,brt_measurement,brt_budget_request_id,brt_previous_year_physical,brt_previous_year_financial,brt_current_year_physical,brt_current_year_financial,brt_next_year_physical,brt_next_year_financial,brt_description,brt_create_time,brt_update_time,brt_delete_time,brt_created_by,brt_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_budget_request_task ";
     $query .=' WHERE 1=1';
     $brtid=$request->input('brt_id');
if(isset($brtid) && isset($brtid)){
$query .=' AND brt_id="'.$brtid.'"'; 
}
$brttaskname=$request->input('brt_task_name');
if(isset($brttaskname) && isset($brttaskname)){
$query .=" AND brt_task_name='".$brttaskname."'"; 
}
$brtmeasurement=$request->input('brt_measurement');
if(isset($brtmeasurement) && isset($brtmeasurement)){
$query .=' AND brt_measurement="'.$brtmeasurement.'"'; 
}
$brtbudgetrequestid=$request->input('budget_request_id');
if(isset($brtbudgetrequestid) && isset($brtbudgetrequestid)){
$query .=" AND brt_budget_request_id='".$brtbudgetrequestid."'"; 
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
        'brt_task_name'=> trans('form_lang.brt_task_name'), 
'brt_measurement'=> trans('form_lang.brt_measurement'), 
'brt_budget_request_id'=> trans('form_lang.brt_budget_request_id'), 
'brt_previous_year_physical'=> trans('form_lang.brt_previous_year_physical'), 
'brt_previous_year_financial'=> trans('form_lang.brt_previous_year_financial'), 
'brt_current_year_physical'=> trans('form_lang.brt_current_year_physical'), 
'brt_current_year_financial'=> trans('form_lang.brt_current_year_financial'), 
'brt_next_year_physical'=> trans('form_lang.brt_next_year_physical'), 
'brt_next_year_financial'=> trans('form_lang.brt_next_year_financial'), 
'brt_description'=> trans('form_lang.brt_description'),
    ];
    $rules= [
        'brt_task_name'=> 'max:200', 
'brt_measurement'=> 'max:200',
'brt_previous_year_physical'=> 'max:200', 
'brt_previous_year_financial'=> 'max:200', 
'brt_current_year_physical'=> 'max:200', 
'brt_current_year_financial'=> 'max:200', 
'brt_next_year_physical'=> 'max:200', 
'brt_next_year_financial'=> 'max:200', 
'brt_description'=> 'max:425',

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
        $id=$request->get("brt_id");
        $requestData = $request->all();            
        $status= $request->input('brt_status');
        if($status=="true"){
            $requestData['brt_status']=1;
        }else{
            $requestData['brt_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetrequesttask::findOrFail($id);
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
        $data_info=Modelpmsbudgetrequesttask::create($requestData);
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
        'brt_task_name'=> trans('form_lang.brt_task_name'), 
'brt_measurement'=> trans('form_lang.brt_measurement'), 
'brt_budget_request_id'=> trans('form_lang.brt_budget_request_id'), 
'brt_previous_year_physical'=> trans('form_lang.brt_previous_year_physical'), 
'brt_previous_year_financial'=> trans('form_lang.brt_previous_year_financial'), 
'brt_current_year_physical'=> trans('form_lang.brt_current_year_physical'), 
'brt_current_year_financial'=> trans('form_lang.brt_current_year_financial'), 
'brt_next_year_physical'=> trans('form_lang.brt_next_year_physical'), 
'brt_next_year_financial'=> trans('form_lang.brt_next_year_financial'), 
'brt_description'=> trans('form_lang.brt_description'), 

    ];
    $rules= [
        'brt_task_name'=> 'max:200', 
'brt_measurement'=> 'max:200', 
'brt_previous_year_physical'=> 'max:200', 
'brt_previous_year_financial'=> 'max:200', 
'brt_current_year_physical'=> 'max:200', 
'brt_current_year_financial'=> 'max:200', 
'brt_next_year_physical'=> 'max:200', 
'brt_next_year_financial'=> 'max:200', 
'brt_description'=> 'max:425',

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
        //$requestData['brt_created_by']=auth()->user()->usr_Id;
        $status= $request->input('brt_status');
        if($status=="true"){
            $requestData['brt_status']=1;
        }else{
            $requestData['brt_status']=0;
        }
        $requestData['brt_created_by']=1;
        $data_info=Modelpmsbudgetrequesttask::create($requestData);
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
    $id=$request->get("brt_id");
    Modelpmsbudgetrequesttask::destroy($id);
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
    Route::resource('budget_request_task', 'PmsbudgetrequesttaskController');
    Route::post('budget_request_task/listgrid', 'Api\PmsbudgetrequesttaskController@listgrid');
    Route::post('budget_request_task/insertgrid', 'Api\PmsbudgetrequesttaskController@insertgrid');
    Route::post('budget_request_task/updategrid', 'Api\PmsbudgetrequesttaskController@updategrid');
    Route::post('budget_request_task/deletegrid', 'Api\PmsbudgetrequesttaskController@deletegrid');
}
}