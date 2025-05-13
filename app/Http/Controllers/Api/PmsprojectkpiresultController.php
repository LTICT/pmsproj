<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectkpiresult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectkpiresultController extends MyController
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
        $query='SELECT kpr_id,kpr_project_id,kpr_project_kpi_id,kpr_year_id,kpr_planned_month_1,kpr_actual_month_1,kpr_planned_month_2,kpr_actual_month_2,kpr_planned_month_3,kpr_actual_month_3,kpr_planned_month_4,kpr_actual_month_4,kpr_planned_month_5,kpr_actual_month_5,kpr_planned_month_6,kpr_actual_month_6,kpr_planned_month_7,kpr_actual_month_7,kpr_planned_month_8,kpr_actual_month_8,kpr_planned_month_9,kpr_actual_month_9,kpr_planned_month_10,kpr_actual_month_10,kpr_planned_month_11,kpr_actual_month_11,kpr_planned_month_12,kpr_actual_month_12,kpr_description,kpr_create_time,kpr_update_time,kpr_delete_time,kpr_created_by,kpr_status FROM pms_project_kpi_result ';       
        
        $query .=' WHERE kpr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_kpi_result_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectkpiresult::findOrFail($id);
        //$data['pms_project_kpi_result_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_kpi_result");
        return view('project_kpi_result.show_pms_project_kpi_result', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $query="SELECT kpr_id,kpr_project_id,kpr_project_kpi_id,kpr_year_id,kpr_planned_month_1,kpr_actual_month_1,kpr_planned_month_2,kpr_actual_month_2,kpr_planned_month_3,kpr_actual_month_3,kpr_planned_month_4,kpr_actual_month_4,kpr_planned_month_5,kpr_actual_month_5,kpr_planned_month_6,kpr_actual_month_6,kpr_planned_month_7,kpr_actual_month_7,kpr_planned_month_8,kpr_actual_month_8,kpr_planned_month_9,kpr_actual_month_9,kpr_planned_month_10,kpr_actual_month_10,kpr_planned_month_11,kpr_actual_month_11,kpr_planned_month_12,kpr_actual_month_12,kpr_description,kpr_create_time,kpr_update_time,kpr_delete_time,kpr_created_by,kpr_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_kpi_result 
     INNER JOIN pms_project ON pms_project.prj_id=pms_project_kpi_result.kpr_project_id";
     
     $query .=' WHERE 1=1';
     $kprid=$request->input('kpr_id');
if(isset($kprid) && isset($kprid)){
$query .=' AND kpr_id="'.$kprid.'"'; 
}
$kprprojectkpiid=$request->input('kpr_project_kpi_id');
if(isset($kprprojectkpiid) && isset($kprprojectkpiid)){
$query .=' AND kpr_project_kpi_id="'.$kprprojectkpiid.'"'; 
}
$kpryearid=$request->input('kpr_year_id');
if(isset($kpryearid) && isset($kpryearid)){
$query .=' AND kpr_year_id="'.$kpryearid.'"'; 
}
$kprstatus=$request->input('kpr_status');
if(isset($kprstatus) && isset($kprstatus)){
$query .=' AND kpr_status="'.$kprstatus.'"'; 
}
//START
$kprprojectid=$request->input('project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($kprprojectid) && isset($kprprojectid)){
$query .= " AND kpr_project_id = '".$kprprojectid."'";
}
}else{
$query=$this->getSearchParam($request,$query);
}

//END
//$query.=' ORDER BY emp_first_name';
$data_info=DB::select($query);
//dd($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'kpr_project_id'=> trans('form_lang.kpr_project_id'), 
'kpr_project_kpi_id'=> trans('form_lang.kpr_project_kpi_id'), 
'kpr_year_id'=> trans('form_lang.kpr_year_id'), 
'kpr_planned_month_1'=> trans('form_lang.kpr_planned_month_1'), 
'kpr_actual_month_1'=> trans('form_lang.kpr_actual_month_1'), 
'kpr_planned_month_2'=> trans('form_lang.kpr_planned_month_2'), 
'kpr_actual_month_2'=> trans('form_lang.kpr_actual_month_2'), 
'kpr_planned_month_3'=> trans('form_lang.kpr_planned_month_3'), 
'kpr_actual_month_3'=> trans('form_lang.kpr_actual_month_3'), 
'kpr_planned_month_4'=> trans('form_lang.kpr_planned_month_4'), 
'kpr_actual_month_4'=> trans('form_lang.kpr_actual_month_4'), 
'kpr_planned_month_5'=> trans('form_lang.kpr_planned_month_5'), 
'kpr_actual_month_5'=> trans('form_lang.kpr_actual_month_5'), 
'kpr_planned_month_6'=> trans('form_lang.kpr_planned_month_6'), 
'kpr_actual_month_6'=> trans('form_lang.kpr_actual_month_6'), 
'kpr_planned_month_7'=> trans('form_lang.kpr_planned_month_7'), 
'kpr_actual_month_7'=> trans('form_lang.kpr_actual_month_7'), 
'kpr_planned_month_8'=> trans('form_lang.kpr_planned_month_8'), 
'kpr_actual_month_8'=> trans('form_lang.kpr_actual_month_8'), 
'kpr_planned_month_9'=> trans('form_lang.kpr_planned_month_9'), 
'kpr_actual_month_9'=> trans('form_lang.kpr_actual_month_9'), 
'kpr_planned_month_10'=> trans('form_lang.kpr_planned_month_10'), 
'kpr_actual_month_10'=> trans('form_lang.kpr_actual_month_10'), 
'kpr_planned_month_11'=> trans('form_lang.kpr_planned_month_11'), 
'kpr_actual_month_11'=> trans('form_lang.kpr_actual_month_11'), 
'kpr_planned_month_12'=> trans('form_lang.kpr_planned_month_12'), 
'kpr_actual_month_12'=> trans('form_lang.kpr_actual_month_12'), 
'kpr_description'=> trans('form_lang.kpr_description'), 
'kpr_status'=> trans('form_lang.kpr_status'), 

    ];
    $rules= [
        'kpr_project_id'=> 'max:200', 
'kpr_project_kpi_id'=> 'max:200', 
'kpr_year_id'=> 'max:200', 
'kpr_planned_month_1'=> 'numeric', 
'kpr_actual_month_1'=> 'numeric', 
'kpr_planned_month_2'=> 'numeric', 
'kpr_actual_month_2'=> 'numeric', 
'kpr_planned_month_3'=> 'numeric', 
'kpr_actual_month_3'=> 'numeric', 
'kpr_planned_month_4'=> 'numeric', 
'kpr_actual_month_4'=> 'numeric', 
'kpr_planned_month_5'=> 'numeric', 
'kpr_actual_month_5'=> 'numeric', 
'kpr_planned_month_6'=> 'numeric', 
'kpr_actual_month_6'=> 'numeric', 
'kpr_planned_month_7'=> 'numeric', 
'kpr_actual_month_7'=> 'numeric', 
'kpr_planned_month_8'=> 'numeric', 
'kpr_actual_month_8'=> 'numeric', 
'kpr_planned_month_9'=> 'numeric', 
'kpr_actual_month_9'=> 'numeric', 
'kpr_planned_month_10'=> 'numeric', 
'kpr_actual_month_10'=> 'numeric', 
'kpr_planned_month_11'=> 'numeric', 
'kpr_actual_month_11'=> 'numeric', 
'kpr_planned_month_12'=> 'numeric', 
'kpr_actual_month_12'=> 'numeric', 
'kpr_description'=> 'max:425', 
'kpr_status'=> 'integer', 

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
        $id=$request->get("kpr_id");
        $requestData = $request->all();            
        $status= $request->input('kpr_status');
        if($status=="true"){
            $requestData['kpr_status']=1;
        }else{
            $requestData['kpr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectkpiresult::findOrFail($id);
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
        $data_info=Modelpmsprojectkpiresult::create($requestData);
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
        'kpr_project_id'=> trans('form_lang.kpr_project_id'), 
'kpr_project_kpi_id'=> trans('form_lang.kpr_project_kpi_id'), 
'kpr_year_id'=> trans('form_lang.kpr_year_id'), 
'kpr_planned_month_1'=> trans('form_lang.kpr_planned_month_1'), 
'kpr_actual_month_1'=> trans('form_lang.kpr_actual_month_1'), 
'kpr_planned_month_2'=> trans('form_lang.kpr_planned_month_2'), 
'kpr_actual_month_2'=> trans('form_lang.kpr_actual_month_2'), 
'kpr_planned_month_3'=> trans('form_lang.kpr_planned_month_3'), 
'kpr_actual_month_3'=> trans('form_lang.kpr_actual_month_3'), 
'kpr_planned_month_4'=> trans('form_lang.kpr_planned_month_4'), 
'kpr_actual_month_4'=> trans('form_lang.kpr_actual_month_4'), 
'kpr_planned_month_5'=> trans('form_lang.kpr_planned_month_5'), 
'kpr_actual_month_5'=> trans('form_lang.kpr_actual_month_5'), 
'kpr_planned_month_6'=> trans('form_lang.kpr_planned_month_6'), 
'kpr_actual_month_6'=> trans('form_lang.kpr_actual_month_6'), 
'kpr_planned_month_7'=> trans('form_lang.kpr_planned_month_7'), 
'kpr_actual_month_7'=> trans('form_lang.kpr_actual_month_7'), 
'kpr_planned_month_8'=> trans('form_lang.kpr_planned_month_8'), 
'kpr_actual_month_8'=> trans('form_lang.kpr_actual_month_8'), 
'kpr_planned_month_9'=> trans('form_lang.kpr_planned_month_9'), 
'kpr_actual_month_9'=> trans('form_lang.kpr_actual_month_9'), 
'kpr_planned_month_10'=> trans('form_lang.kpr_planned_month_10'), 
'kpr_actual_month_10'=> trans('form_lang.kpr_actual_month_10'), 
'kpr_planned_month_11'=> trans('form_lang.kpr_planned_month_11'), 
'kpr_actual_month_11'=> trans('form_lang.kpr_actual_month_11'), 
'kpr_planned_month_12'=> trans('form_lang.kpr_planned_month_12'), 
'kpr_actual_month_12'=> trans('form_lang.kpr_actual_month_12'), 
'kpr_description'=> trans('form_lang.kpr_description'), 
'kpr_status'=> trans('form_lang.kpr_status'), 

    ];
    $rules= [
        'kpr_project_id'=> 'max:200', 
'kpr_project_kpi_id'=> 'max:200', 
'kpr_year_id'=> 'max:200', 
'kpr_planned_month_1'=> 'numeric', 
'kpr_actual_month_1'=> 'numeric', 
'kpr_planned_month_2'=> 'numeric', 
'kpr_actual_month_2'=> 'numeric', 
'kpr_planned_month_3'=> 'numeric', 
'kpr_actual_month_3'=> 'numeric', 
'kpr_planned_month_4'=> 'numeric', 
'kpr_actual_month_4'=> 'numeric', 
'kpr_planned_month_5'=> 'numeric', 
'kpr_actual_month_5'=> 'numeric', 
'kpr_planned_month_6'=> 'numeric', 
'kpr_actual_month_6'=> 'numeric', 
'kpr_planned_month_7'=> 'numeric', 
'kpr_actual_month_7'=> 'numeric', 
'kpr_planned_month_8'=> 'numeric', 
'kpr_actual_month_8'=> 'numeric', 
'kpr_planned_month_9'=> 'numeric', 
'kpr_actual_month_9'=> 'numeric', 
'kpr_planned_month_10'=> 'numeric', 
'kpr_actual_month_10'=> 'numeric', 
'kpr_planned_month_11'=> 'numeric', 
'kpr_actual_month_11'=> 'numeric', 
'kpr_planned_month_12'=> 'numeric', 
'kpr_actual_month_12'=> 'numeric', 
'kpr_description'=> 'max:425', 
'kpr_status'=> 'integer', 

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
        //$requestData['kpr_created_by']=auth()->user()->usr_Id;
        $status= $request->input('kpr_status');
        if($status=="true"){
            $requestData['kpr_status']=1;
        }else{
            $requestData['kpr_status']=0;
        }
        $requestData['kpr_created_by']=1;
        $data_info=Modelpmsprojectkpiresult::create($requestData);
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
    $id=$request->get("kpr_id");
    Modelpmsprojectkpiresult::destroy($id);
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
    Route::resource('project_kpi_result', 'PmsprojectkpiresultController');
    Route::post('project_kpi_result/listgrid', 'Api\PmsprojectkpiresultController@listgrid');
    Route::post('project_kpi_result/insertgrid', 'Api\PmsprojectkpiresultController@insertgrid');
    Route::post('project_kpi_result/updategrid', 'Api\PmsprojectkpiresultController@updategrid');
    Route::post('project_kpi_result/deletegrid', 'Api\PmsprojectkpiresultController@deletegrid');
}
}