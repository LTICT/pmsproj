<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectcomponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectcomponentController extends MyController
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
        $query='SELECT pcm_id,pcm_project_id,pcm_component_name,pcm_unit_measurement,pcm_amount,pcm_description,pcm_create_time,pcm_update_time,pcm_delete_time,pcm_created_by,pcm_status FROM pms_project_component ';       
        
        $query .=' WHERE pcm_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_component_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectcomponent::findOrFail($id);
        //$data['pms_project_component_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_component");
        return view('project_component.show_pms_project_component', $data);
    }
    //Get List
    public function listgrid(Request $request){
    
     $query="SELECT pcm_id,pcm_project_id,pcm_component_name,pcm_unit_measurement,pcm_amount,pcm_description,pcm_create_time,pcm_update_time,pcm_delete_time,pcm_created_by,pcm_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_component 
     INNER JOIN pms_project ON pms_project.prj_id=pms_project_component.pcm_project_id";
     
     $query .=' WHERE 1=1';
     $pcmid=$request->input('pcm_id');
if(isset($pcmid) && isset($pcmid)){
$query .=' AND pcm_id="'.$pcmid.'"'; 
}

$pcmcomponentname=$request->input('pcm_component_name');
if(isset($pcmcomponentname) && isset($pcmcomponentname)){
$query .=' AND pcm_component_name="'.$pcmcomponentname.'"'; 
}
$pcmunitmeasurement=$request->input('pcm_unit_measurement');
if(isset($pcmunitmeasurement) && isset($pcmunitmeasurement)){
$query .=' AND pcm_unit_measurement="'.$pcmunitmeasurement.'"'; 
}
$pcmamount=$request->input('pcm_amount');
if(isset($pcmamount) && isset($pcmamount)){
$query .=' AND pcm_amount="'.$pcmamount.'"'; 
}
$pcmdescription=$request->input('pcm_description');
if(isset($pcmdescription) && isset($pcmdescription)){
$query .=' AND pcm_description="'.$pcmdescription.'"'; 
}
$pcmcreatetime=$request->input('pcm_create_time');
if(isset($pcmcreatetime) && isset($pcmcreatetime)){
$query .=' AND pcm_create_time="'.$pcmcreatetime.'"'; 
}
$pcmupdatetime=$request->input('pcm_update_time');
if(isset($pcmupdatetime) && isset($pcmupdatetime)){
$query .=' AND pcm_update_time="'.$pcmupdatetime.'"'; 
}
$pcmdeletetime=$request->input('pcm_delete_time');
if(isset($pcmdeletetime) && isset($pcmdeletetime)){
$query .=' AND pcm_delete_time="'.$pcmdeletetime.'"'; 
}
$pcmcreatedby=$request->input('pcm_created_by');
if(isset($pcmcreatedby) && isset($pcmcreatedby)){
$query .=' AND pcm_created_by="'.$pcmcreatedby.'"'; 
}
$pcmstatus=$request->input('pcm_status');
if(isset($pcmstatus) && isset($pcmstatus)){
$query .=' AND pcm_status="'.$pcmstatus.'"'; 
}
//START
$pcmprojectid=$request->input('pcm_project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($pcmprojectid) && isset($pcmprojectid)){
$query .= " AND pcm_project_id = '$pcmprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
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
        'pcm_project_id'=> trans('form_lang.pcm_project_id'), 
'pcm_component_name'=> trans('form_lang.pcm_component_name'), 
'pcm_unit_measurement'=> trans('form_lang.pcm_unit_measurement'), 
'pcm_amount'=> trans('form_lang.pcm_amount'), 
'pcm_description'=> trans('form_lang.pcm_description'), 
'pcm_status'=> trans('form_lang.pcm_status'), 

    ];
    $rules= [
        'pcm_project_id'=> 'max:200', 
'pcm_component_name'=> 'max:200', 
'pcm_unit_measurement'=> 'max:200', 
'pcm_amount'=> 'max:200', 
'pcm_description'=> 'max:425', 
'pcm_status'=> 'integer', 

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
        $id=$request->get("pcm_id");
        $requestData = $request->all();            
        $status= $request->input('pcm_status');
        if($status=="true"){
            $requestData['pcm_status']=1;
        }else{
            $requestData['pcm_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectcomponent::findOrFail($id);
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
        $data_info=Modelpmsprojectcomponent::create($requestData);
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
        'pcm_project_id'=> trans('form_lang.pcm_project_id'), 
'pcm_component_name'=> trans('form_lang.pcm_component_name'), 
'pcm_unit_measurement'=> trans('form_lang.pcm_unit_measurement'), 
'pcm_amount'=> trans('form_lang.pcm_amount'), 
'pcm_description'=> trans('form_lang.pcm_description'), 
'pcm_status'=> trans('form_lang.pcm_status'), 

    ];
    $rules= [
        'pcm_project_id'=> 'max:200', 
'pcm_component_name'=> 'max:200', 
'pcm_unit_measurement'=> 'max:200', 
'pcm_amount'=> 'max:200', 
'pcm_description'=> 'max:425', 
'pcm_status'=> 'integer', 

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
        //$requestData['pcm_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pcm_status');
        if($status=="true"){
            $requestData['pcm_status']=1;
        }else{
            $requestData['pcm_status']=0;
        }
        $requestData['pcm_created_by']=1;
        $data_info=Modelpmsprojectcomponent::create($requestData);
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
    $id=$request->get("pcm_id");
    Modelpmsprojectcomponent::destroy($id);
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
    Route::resource('project_component', 'PmsprojectcomponentController');
    Route::post('project_component/listgrid', 'Api\PmsprojectcomponentController@listgrid');
    Route::post('project_component/insertgrid', 'Api\PmsprojectcomponentController@insertgrid');
    Route::post('project_component/updategrid', 'Api\PmsprojectcomponentController@updategrid');
    Route::post('project_component/deletegrid', 'Api\PmsprojectcomponentController@deletegrid');
}
}