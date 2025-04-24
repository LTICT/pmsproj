<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectplanController extends MyController
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
        $query='SELECT pld_id,pld_name,pld_project_id,pld_budget_year_id,pld_start_date_ec,pld_start_date_gc,pld_end_date_ec,pld_end_date_gc,pld_description,pld_create_time,pld_update_time,pld_delete_time,pld_created_by,pld_status FROM pms_project_plan ';       
        
        $query .=' WHERE pld_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_plan_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectplan::findOrFail($id);
        //$data['pms_project_plan_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_plan");
        return view('project_plan.show_pms_project_plan', $data);
    }
    public function listgrid(Request $request){
    //$permissionData=$this->getPagePermission($request,61);
     $query='SELECT prj_name,prj_code,bdy_name, pld_id,pld_name,pld_project_id,pld_budget_year_id,pld_start_date_ec,pld_start_date_gc,pld_end_date_ec,pld_end_date_gc,pld_description,pld_create_time,pld_update_time,pld_delete_time,pld_created_by,pld_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_plan ';       
     $query .= ' INNER JOIN pms_budget_year ON pms_project_plan.pld_budget_year_id = pms_budget_year.bdy_id';
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_plan.pld_project_id';
$query .=' WHERE 1=1';
 $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('pld_start_date_gcStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND pld_start_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('pld_start_date_gcEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND pld_start_date_gc <='".$endTime." 23 59 59'"; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
}
$pldname=$request->input('pld_name');
if(isset($pldname) && isset($pldname)){
$query .=' AND pld_name="'.$pldname.'"'; 
}
$pldbudgetyearid=$request->input('pld_budget_year_id');
if(isset($pldbudgetyearid) && isset($pldbudgetyearid)){
$query .=' AND pld_budget_year_id="'.$pldbudgetyearid.'"'; 
}
$pldstartdateec=$request->input('pld_start_date_ec');
if(isset($pldstartdateec) && isset($pldstartdateec)){
$query .=' AND pld_start_date_ec="'.$pldstartdateec.'"'; 
}
$pldstartdategc=$request->input('pld_start_date_gc');
if(isset($pldstartdategc) && isset($pldstartdategc)){
$query .=' AND pld_start_date_gc="'.$pldstartdategc.'"'; 
}
$pldenddateec=$request->input('pld_end_date_ec');
if(isset($pldenddateec) && isset($pldenddateec)){
$query .=' AND pld_end_date_ec="'.$pldenddateec.'"'; 
}
$pldenddategc=$request->input('pld_end_date_gc');
if(isset($pldenddategc) && isset($pldenddategc)){
$query .=' AND pld_end_date_gc="'.$pldenddategc.'"'; 
}
//START
$requesttype=$request->input('request_type');
$pldprojectid=$request->input('pld_project_id');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($pldprojectid) && isset($pldprojectid)){
$query .= " AND pld_project_id = '$pldprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END

$query.=' ORDER BY pld_id';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>0);
$permission=$this->ownsProject($request,$pldprojectid);
if($permission !=null)
{
   $previledge=array('is_role_can_add'=>$this->getDateParameter(4) ? 1 : 0,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);

return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'pld_name'=> trans('form_lang.pld_name'), 
'pld_project_id'=> trans('form_lang.pld_project_id'), 
'pld_budget_year_id'=> trans('form_lang.pld_budget_year_id'), 
'pld_start_date_ec'=> trans('form_lang.pld_start_date_ec'), 
'pld_start_date_gc'=> trans('form_lang.pld_start_date_gc'), 
'pld_end_date_ec'=> trans('form_lang.pld_end_date_ec'), 
'pld_end_date_gc'=> trans('form_lang.pld_end_date_gc'), 
'pld_description'=> trans('form_lang.pld_description'), 
'pld_status'=> trans('form_lang.pld_status'), 

    ];
    $rules= [
        'pld_name'=> 'max:200', 
'pld_project_id'=> 'max:200', 
'pld_budget_year_id'=> 'max:200', 
'pld_start_date_ec'=> 'max:200', 
'pld_start_date_gc'=> 'max:200', 
'pld_end_date_ec'=> 'max:200', 
'pld_end_date_gc'=> 'max:200', 
'pld_description'=> 'max:425',

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
        $id=$request->get("pld_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pld_status');
        if($status=="true"){
            $requestData['pld_status']=1;
        }else{
            $requestData['pld_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectplan::findOrFail($id);
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
        //$requestData['pld_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectplan::create($requestData);
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
public function insertgrid(Request $request)
{
    $attributeNames = [
        'pld_name'=> trans('form_lang.pld_name'), 
'pld_project_id'=> trans('form_lang.pld_project_id'), 
'pld_budget_year_id'=> trans('form_lang.pld_budget_year_id'), 
'pld_start_date_ec'=> trans('form_lang.pld_start_date_ec'), 
'pld_start_date_gc'=> trans('form_lang.pld_start_date_gc'), 
'pld_end_date_ec'=> trans('form_lang.pld_end_date_ec'), 
'pld_end_date_gc'=> trans('form_lang.pld_end_date_gc'), 
'pld_description'=> trans('form_lang.pld_description'), 

    ];
    $rules= [
        'pld_name'=> 'max:200', 
'pld_project_id'=> 'max:200', 
'pld_budget_year_id'=> 'max:200', 
'pld_start_date_ec'=> 'max:200', 
'pld_start_date_gc'=> 'max:200', 
'pld_end_date_ec'=> 'max:200', 
'pld_end_date_gc'=> 'max:200', 
'pld_description'=> 'max:425'
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
        //$requestData['pld_created_by']=auth()->user()->usr_Id;
        $requestData['pld_created_by']=1;
        $status= $request->input('pld_status');
        if($status=="true"){
            $requestData['pld_status']=1;
        }else{
            $requestData['pld_status']=0;
        }
        $data_info=Modelpmsprojectplan::create($requestData);
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
public function deletegrid(Request $request)
{
    $id=$request->get("pld_id");
    Modelpmsprojectplan::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "deleted_id"=>$id,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}