<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgendatesetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GendatesettingController extends MyController
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
        $query='SELECT dts_id,dts_parameter_name,dts_parameter_code,dts_start_date,dts_end_date,dts_description,dts_create_time,dts_update_time,dts_delete_time,dts_created_by,dts_status FROM gen_date_setting ';       
        
        $query .=' WHERE dts_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_date_setting_data']=$data_info[0];
        }
        //$data_info = Modelgendatesetting::findOrFail($id);
        //$data['gen_date_setting_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_date_setting");
        return view('date_setting.show_gen_date_setting', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $query="SELECT dts_id,dts_parameter_name,dts_parameter_code,dts_start_date,dts_end_date,dts_description,dts_create_time,dts_update_time,dts_delete_time,dts_created_by,dts_status,1 AS is_editable, 1 AS is_deletable FROM gen_date_setting ";
     $query .=' WHERE 1=1';
     $dtsid=$request->input('dts_id');
if(isset($dtsid) && isset($dtsid)){
$query .=' AND dts_id="'.$dtsid.'"'; 
}
$dtsparametername=$request->input('dts_parameter_name');
if(isset($dtsparametername) && isset($dtsparametername)){
$query .=' AND dts_parameter_name="'.$dtsparametername.'"'; 
}
$dtsparametercode=$request->input('dts_parameter_code');
if(isset($dtsparametercode) && isset($dtsparametercode)){
$query .=" AND dts_parameter_code='".$dtsparametercode."'"; 
}
$dtsstartdate=$request->input('dts_start_date');
if(isset($dtsstartdate) && isset($dtsstartdate)){
$query .=' AND dts_start_date="'.$dtsstartdate.'"'; 
}
$dtsenddate=$request->input('dts_end_date');
if(isset($dtsenddate) && isset($dtsenddate)){
$query .=' AND dts_end_date="'.$dtsenddate.'"'; 
}
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
        'dts_parameter_name'=> trans('form_lang.dts_parameter_name'), 
'dts_parameter_code'=> trans('form_lang.dts_parameter_code'), 
'dts_start_date'=> trans('form_lang.dts_start_date'), 
'dts_end_date'=> trans('form_lang.dts_end_date'), 
'dts_description'=> trans('form_lang.dts_description'), 
'dts_status'=> trans('form_lang.dts_status'), 

    ];
    $rules= [
        'dts_parameter_name'=> 'max:200', 
'dts_parameter_code'=> 'max:200', 
'dts_start_date'=> 'max:200', 
'dts_end_date'=> 'max:200', 
'dts_description'=> 'max:425', 

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
        $id=$request->get("dts_id");
        $requestData = $request->all();            
        $status= $request->input('dts_status');
        if($status=="true"){
            $requestData['dts_status']=1;
        }else{
            $requestData['dts_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgendatesetting::findOrFail($id);
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
        $data_info=Modelgendatesetting::create($requestData);
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
        'dts_parameter_name'=> trans('form_lang.dts_parameter_name'), 
'dts_parameter_code'=> trans('form_lang.dts_parameter_code'), 
'dts_start_date'=> trans('form_lang.dts_start_date'), 
'dts_end_date'=> trans('form_lang.dts_end_date'), 
'dts_description'=> trans('form_lang.dts_description'), 
'dts_status'=> trans('form_lang.dts_status'), 

    ];
    $rules= [
        'dts_parameter_name'=> 'max:200', 
'dts_parameter_code'=> 'max:200', 
'dts_start_date'=> 'max:200', 
'dts_end_date'=> 'max:200', 
'dts_description'=> 'max:425',

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
        $requestData['dts_created_by']=auth()->user()->usr_Id;
        $status= $request->input('dts_status');
        if($status=="true"){
            $requestData['dts_status']=1;
        }else{
            $requestData['dts_status']=0;
        }
        $requestData['dts_created_by']=1;
        $data_info=Modelgendatesetting::create($requestData);
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
    $id=$request->get("dts_id");
    Modelgendatesetting::destroy($id);
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
    Route::resource('date_setting', 'GendatesettingController');
    Route::post('date_setting/listgrid', 'Api\GendatesettingController@listgrid');
    Route::post('date_setting/insertgrid', 'Api\GendatesettingController@insertgrid');
    Route::post('date_setting/updategrid', 'Api\GendatesettingController@updategrid');
    Route::post('date_setting/deletegrid', 'Api\GendatesettingController@deletegrid');
}
}