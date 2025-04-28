<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectkpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectkpiController extends MyController
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
        $query='SELECT kpi_id,kpi_name_or,kpi_name_am,kpi_name_en,kpi_unit_measurement,kpi_description,kpi_create_time,kpi_update_time,kpi_delete_time,kpi_created_by,kpi_status FROM pms_project_kpi ';       
        
        $query .=' WHERE kpi_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_kpi_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectkpi::findOrFail($id);
        //$data['pms_project_kpi_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_kpi");
        return view('project_kpi.show_pms_project_kpi', $data);
    }
    //Get List
    public function listgrid(Request $request){
  $query="SELECT kpi_id,kpi_name_or,kpi_name_am,kpi_name_en,kpi_unit_measurement,kpi_description,kpi_create_time,kpi_update_time,kpi_delete_time,kpi_created_by,kpi_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_kpi ";
     
     $query .=' WHERE 1=1';
     $kpiid=$request->input('kpi_id');
if(isset($kpiid) && isset($kpiid)){
$query .=' AND kpi_id="'.$kpiid.'"'; 
}
$kpinameor=$request->input('kpi_name_or');
if(isset($kpinameor) && isset($kpinameor)){
$query .=' AND kpi_name_or="'.$kpinameor.'"'; 
}
$kpinameam=$request->input('kpi_name_am');
if(isset($kpinameam) && isset($kpinameam)){
$query .=' AND kpi_name_am="'.$kpinameam.'"'; 
}
$kpinameen=$request->input('kpi_name_en');
if(isset($kpinameen) && isset($kpinameen)){
$query .=' AND kpi_name_en="'.$kpinameen.'"'; 
}
$kpiunitmeasurement=$request->input('kpi_unit_measurement');
if(isset($kpiunitmeasurement) && isset($kpiunitmeasurement)){
$query .=' AND kpi_unit_measurement="'.$kpiunitmeasurement.'"'; 
}
$kpidescription=$request->input('kpi_description');
if(isset($kpidescription) && isset($kpidescription)){
$query .=' AND kpi_description="'.$kpidescription.'"'; 
}
$kpicreatetime=$request->input('kpi_create_time');
if(isset($kpicreatetime) && isset($kpicreatetime)){
$query .=' AND kpi_create_time="'.$kpicreatetime.'"'; 
}
$kpiupdatetime=$request->input('kpi_update_time');
if(isset($kpiupdatetime) && isset($kpiupdatetime)){
$query .=' AND kpi_update_time="'.$kpiupdatetime.'"'; 
}
$kpideletetime=$request->input('kpi_delete_time');
if(isset($kpideletetime) && isset($kpideletetime)){
$query .=' AND kpi_delete_time="'.$kpideletetime.'"'; 
}
$kpicreatedby=$request->input('kpi_created_by');
if(isset($kpicreatedby) && isset($kpicreatedby)){
$query .=' AND kpi_created_by="'.$kpicreatedby.'"'; 
}
$kpistatus=$request->input('kpi_status');
if(isset($kpistatus) && isset($kpistatus)){
$query .=' AND kpi_status="'.$kpistatus.'"'; 
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
        'kpi_name_or'=> trans('form_lang.kpi_name_or'), 
'kpi_name_am'=> trans('form_lang.kpi_name_am'), 
'kpi_name_en'=> trans('form_lang.kpi_name_en'), 
'kpi_unit_measurement'=> trans('form_lang.kpi_unit_measurement'), 
'kpi_description'=> trans('form_lang.kpi_description'), 
'kpi_status'=> trans('form_lang.kpi_status'), 

    ];
    $rules= [
        'kpi_name_or'=> 'max:200', 
'kpi_name_am'=> 'max:200', 
'kpi_name_en'=> 'max:200', 
'kpi_unit_measurement'=> 'max:200', 
'kpi_description'=> 'max:425', 
'kpi_status'=> 'integer', 

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
        $id=$request->get("kpi_id");
        $requestData = $request->all();            
        $status= $request->input('kpi_status');
        if($status=="true"){
            $requestData['kpi_status']=1;
        }else{
            $requestData['kpi_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectkpi::findOrFail($id);
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
        $data_info=Modelpmsprojectkpi::create($requestData);
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
        'kpi_name_or'=> trans('form_lang.kpi_name_or'), 
'kpi_name_am'=> trans('form_lang.kpi_name_am'), 
'kpi_name_en'=> trans('form_lang.kpi_name_en'), 
'kpi_unit_measurement'=> trans('form_lang.kpi_unit_measurement'), 
'kpi_description'=> trans('form_lang.kpi_description'), 
'kpi_status'=> trans('form_lang.kpi_status'), 

    ];
    $rules= [
        'kpi_name_or'=> 'max:200', 
'kpi_name_am'=> 'max:200', 
'kpi_name_en'=> 'max:200', 
'kpi_unit_measurement'=> 'max:200', 
'kpi_description'=> 'max:425', 
'kpi_status'=> 'integer', 

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
        //$requestData['kpi_created_by']=auth()->user()->usr_Id;
        $status= $request->input('kpi_status');
        if($status=="true"){
            $requestData['kpi_status']=1;
        }else{
            $requestData['kpi_status']=0;
        }
        $requestData['kpi_created_by']=1;
        $data_info=Modelpmsprojectkpi::create($requestData);
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
    $id=$request->get("kpi_id");
    Modelpmsprojectkpi::destroy($id);
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
    Route::resource('project_kpi', 'PmsprojectkpiController');
    Route::post('project_kpi/listgrid', 'Api\PmsprojectkpiController@listgrid');
    Route::post('project_kpi/insertgrid', 'Api\PmsprojectkpiController@insertgrid');
    Route::post('project_kpi/updategrid', 'Api\PmsprojectkpiController@updategrid');
    Route::post('project_kpi/deletegrid', 'Api\PmsprojectkpiController@deletegrid');
}
}