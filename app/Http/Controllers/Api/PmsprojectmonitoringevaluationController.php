<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectmonitoringevaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectmonitoringevaluationController extends MyController
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
        $query='SELECT mne_id,mne_transaction_type_id,mne_visit_type,mne_project_id,mne_type_id,mne_physical,mne_financial,mne_physical_region,mne_financial_region,mne_team_members,mne_feedback,mne_weakness,mne_challenges,mne_recommendations,mne_purpose,mne_record_date,mne_start_date,mne_end_date,mne_description,mne_create_time,mne_update_time,mne_delete_time,mne_created_by,mne_status FROM pms_project_monitoring_evaluation ';
        $query .=' WHERE mne_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_monitoring_evaluation_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectmonitoringevaluation::findOrFail($id);
        //$data['pms_project_monitoring_evaluation_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_monitoring_evaluation");
        return view('project_monitoring_evaluation.show_pms_project_monitoring_evaluation', $data);
    }
    //Get List
    public function listgrid(Request $request){     
     $query="SELECT mne_physical_zone,mne_financial_zone,mne_strength, prj_name,prj_code,mne_id,mne_transaction_type_id,mne_visit_type,mne_project_id,mne_type_id,mne_physical,mne_financial,mne_physical_region,mne_financial_region,mne_team_members,mne_feedback,mne_weakness,mne_challenges,mne_recommendations,mne_purpose,mne_record_date,mne_start_date,mne_end_date,mne_description,mne_create_time,mne_update_time,mne_delete_time,mne_created_by,mne_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_monitoring_evaluation
     INNER JOIN pms_project ON pms_project.prj_id=pms_project_monitoring_evaluation.mne_project_id ";
     $query .=' WHERE 1=1';
     $mneid=$request->input('mne_id');
if(isset($mneid) && isset($mneid)){
$query .=' AND mne_id="'.$mneid.'"'; 
}
$mnetransactiontypeid=$request->input('mne_transaction_type_id');
if(isset($mnetransactiontypeid) && isset($mnetransactiontypeid)){
$query .=' AND mne_transaction_type_id="'.$mnetransactiontypeid.'"'; 
}
$mnevisittype=$request->input('mne_visit_type');
if(isset($mnevisittype) && isset($mnevisittype)){
$query .=' AND mne_visit_type="'.$mnevisittype.'"'; 
}
$mneprojectid=$request->input('project_id');
if(isset($mneprojectid) && isset($mneprojectid)){
$query .=" AND mne_project_id='".$mneprojectid."'"; 
}
$mnetypeid=$request->input('mne_type_id');
if(isset($mnetypeid) && isset($mnetypeid)){
$query .=' AND mne_type_id="'.$mnetypeid.'"'; 
}
$mnephysical=$request->input('mne_physical');
if(isset($mnephysical) && isset($mnephysical)){
$query .=' AND mne_physical="'.$mnephysical.'"'; 
}
$mnefinancial=$request->input('mne_financial');
if(isset($mnefinancial) && isset($mnefinancial)){
$query .=' AND mne_financial="'.$mnefinancial.'"'; 
}
$mnephysicalregion=$request->input('mne_physical_region');
if(isset($mnephysicalregion) && isset($mnephysicalregion)){
$query .=' AND mne_physical_region="'.$mnephysicalregion.'"'; 
}
$mnefinancialregion=$request->input('mne_financial_region');
if(isset($mnefinancialregion) && isset($mnefinancialregion)){
$query .=' AND mne_financial_region="'.$mnefinancialregion.'"'; 
}
$mnestatus=$request->input('mne_status');
if(isset($mnestatus) && isset($mnestatus)){
$query .=' AND mne_status="'.$mnestatus.'"'; 
}

//$query.=' ORDER BY emp_first_name';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>1,'is_role_deletable'=>0,'is_role_can_add'=>1);
$permission=$this->ownsProject($request,$mneprojectid);
if($permission !=null)
{
   $previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);

return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'mne_transaction_type_id'=> trans('form_lang.mne_transaction_type_id'), 
'mne_visit_type'=> trans('form_lang.mne_visit_type'), 
'mne_project_id'=> trans('form_lang.mne_project_id'), 
'mne_type_id'=> trans('form_lang.mne_type_id'), 
'mne_physical'=> trans('form_lang.mne_physical'), 
'mne_financial'=> trans('form_lang.mne_financial'), 
'mne_physical_region'=> trans('form_lang.mne_physical_region'), 
'mne_financial_region'=> trans('form_lang.mne_financial_region'), 
'mne_team_members'=> trans('form_lang.mne_team_members'), 
'mne_feedback'=> trans('form_lang.mne_feedback'), 
'mne_weakness'=> trans('form_lang.mne_weakness'), 
'mne_challenges'=> trans('form_lang.mne_challenges'), 
'mne_recommendations'=> trans('form_lang.mne_recommendations'), 
'mne_purpose'=> trans('form_lang.mne_purpose'), 
'mne_record_date'=> trans('form_lang.mne_record_date'), 
'mne_start_date'=> trans('form_lang.mne_start_date'), 
'mne_end_date'=> trans('form_lang.mne_end_date'), 
'mne_description'=> trans('form_lang.mne_description'), 
'mne_status'=> trans('form_lang.mne_status'), 

    ];
    $rules= [
        'mne_transaction_type_id'=> 'max:200', 
'mne_visit_type'=> 'max:200', 
'mne_project_id'=> 'max:200', 
'mne_type_id'=> 'max:200', 
'mne_physical'=> 'max:200', 
'mne_financial'=> 'max:200', 
'mne_physical_region'=> 'max:200', 
'mne_financial_region'=> 'max:200', 
'mne_team_members'=> 'max:200', 
'mne_feedback'=> 'max:425', 
'mne_weakness'=> 'max:425', 
'mne_challenges'=> 'max:425', 
'mne_recommendations'=> 'max:425', 
'mne_purpose'=> 'max:425', 
'mne_record_date'=> 'max:200', 
'mne_start_date'=> 'max:200', 
'mne_end_date'=> 'max:200', 
'mne_description'=> 'max:425', 
'mne_status'=> 'integer', 

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
        $id=$request->get("mne_id");
        $requestData = $request->all();            
        $status= $request->input('mne_status');
        if($status=="true"){
            $requestData['mne_status']=1;
        }else{
            $requestData['mne_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectmonitoringevaluation::findOrFail($id);
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
        $data_info=Modelpmsprojectmonitoringevaluation::create($requestData);
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
        'mne_transaction_type_id'=> trans('form_lang.mne_transaction_type_id'), 
'mne_visit_type'=> trans('form_lang.mne_visit_type'), 
'mne_project_id'=> trans('form_lang.mne_project_id'), 
'mne_type_id'=> trans('form_lang.mne_type_id'), 
'mne_physical'=> trans('form_lang.mne_physical'), 
'mne_financial'=> trans('form_lang.mne_financial'), 
'mne_physical_region'=> trans('form_lang.mne_physical_region'), 
'mne_financial_region'=> trans('form_lang.mne_financial_region'), 
'mne_team_members'=> trans('form_lang.mne_team_members'), 
'mne_feedback'=> trans('form_lang.mne_feedback'), 
'mne_weakness'=> trans('form_lang.mne_weakness'), 
'mne_challenges'=> trans('form_lang.mne_challenges'), 
'mne_recommendations'=> trans('form_lang.mne_recommendations'), 
'mne_purpose'=> trans('form_lang.mne_purpose'), 
'mne_record_date'=> trans('form_lang.mne_record_date'), 
'mne_start_date'=> trans('form_lang.mne_start_date'), 
'mne_end_date'=> trans('form_lang.mne_end_date'), 
'mne_description'=> trans('form_lang.mne_description'), 
'mne_status'=> trans('form_lang.mne_status'), 

    ];
    $rules= [
        'mne_transaction_type_id'=> 'max:200', 
'mne_visit_type'=> 'max:200', 
'mne_project_id'=> 'max:200', 
'mne_type_id'=> 'max:200', 
'mne_physical'=> 'max:200', 
'mne_financial'=> 'max:200', 
'mne_physical_region'=> 'max:200', 
'mne_financial_region'=> 'max:200', 
'mne_team_members'=> 'max:200', 
'mne_feedback'=> 'max:425', 
'mne_weakness'=> 'max:425', 
'mne_challenges'=> 'max:425', 
'mne_recommendations'=> 'max:425', 
'mne_purpose'=> 'max:425', 
'mne_record_date'=> 'max:200', 
'mne_start_date'=> 'max:200', 
'mne_end_date'=> 'max:200', 
'mne_description'=> 'max:425', 
'mne_status'=> 'integer', 

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
        //$requestData['mne_created_by']=auth()->user()->usr_Id;
        $status= $request->input('mne_status');
        if($status=="true"){
            $requestData['mne_status']=1;
        }else{
            $requestData['mne_status']=0;
        }
        $requestData['mne_created_by']=1;
        $data_info=Modelpmsprojectmonitoringevaluation::create($requestData);
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
    $id=$request->get("mne_id");
    Modelpmsprojectmonitoringevaluation::destroy($id);
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
    Route::resource('project_monitoring_evaluation', 'PmsprojectmonitoringevaluationController');
    Route::post('project_monitoring_evaluation/listgrid', 'Api\PmsprojectmonitoringevaluationController@listgrid');
    Route::post('project_monitoring_evaluation/insertgrid', 'Api\PmsprojectmonitoringevaluationController@insertgrid');
    Route::post('project_monitoring_evaluation/updategrid', 'Api\PmsprojectmonitoringevaluationController@updategrid');
    Route::post('project_monitoring_evaluation/deletegrid', 'Api\PmsprojectmonitoringevaluationController@deletegrid');
}
}