<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprocurementinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprocurementinformationController extends MyController
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
     $query="SELECT pri_id,pri_total_procurement_amount,pri_bid_announced_date,pri_bid_invitation_date,pri_bid_opening_date,pri_bid_closing_date,pri_bid_evaluation_date,pri_bid_award_date,pri_project_id,pst_name_or AS pri_procurement_stage_id,prm_name_or AS pri_procurement_method_id,pri_description,pri_create_time,pri_update_time,pri_delete_time,pri_created_by,pri_status FROM pms_procurement_information ";
     $query .=' LEFT JOIN pms_procurement_stage ON pms_procurement_stage.pst_id= pms_procurement_information.pri_procurement_stage_id';
     $query .=' LEFT JOIN pms_procurement_method ON pms_procurement_method.prm_id= pms_procurement_information.pri_procurement_method_id';

        $query .=' WHERE pri_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_procurement_information_data']=$data_info[0];
        }
        //$data_info = Modelpmsprocurementinformation::findOrFail($id);
        //$data['pms_procurement_information_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_procurement_information");
        return view('procurement_information.show_pms_procurement_information', $data);
    }
    //Get List
    public function listgrid(Request $request){

     $query="SELECT prj_name, prj_code,pri_id,pri_total_procurement_amount,pri_bid_announced_date,
     pri_bid_invitation_date,pri_bid_opening_date,pri_bid_closing_date,pri_bid_evaluation_date,
     pri_bid_award_date,pri_project_id, pri_procurement_stage_id, pri_procurement_method_id,pri_description,pri_create_time,
     pri_update_time,pri_delete_time,pri_created_by,pri_status,'1' AS is_editable
     FROM pms_procurement_information ";
     $query .=" INNER JOIN pms_project ON pms_project.prj_id=pms_procurement_information.pri_project_id";

     $query .=' WHERE 1=1';
     $priid=$request->input('pri_id');
if(isset($priid) && isset($priid)){
$query .=' AND pri_id="'.$priid.'"';
}
$pritotalprocurementamount=$request->input('pri_total_procurement_amount');
if(isset($pritotalprocurementamount) && isset($pritotalprocurementamount)){
$query .=' AND pri_total_procurement_amount="'.$pritotalprocurementamount.'"';
}
$pribidannounceddate=$request->input('pri_bid_announced_date');
if(isset($pribidannounceddate) && isset($pribidannounceddate)){
$query .=' AND pri_bid_announced_date="'.$pribidannounceddate.'"';
}
$pribidinvitationdate=$request->input('pri_bid_invitation_date');
if(isset($pribidinvitationdate) && isset($pribidinvitationdate)){
$query .=' AND pri_bid_invitation_date="'.$pribidinvitationdate.'"';
}
$pribidopeningdate=$request->input('pri_bid_opening_date');
if(isset($pribidopeningdate) && isset($pribidopeningdate)){
$query .=' AND pri_bid_opening_date="'.$pribidopeningdate.'"';
}
$pribidclosingdate=$request->input('pri_bid_closing_date');
if(isset($pribidclosingdate) && isset($pribidclosingdate)){
$query .=' AND pri_bid_closing_date="'.$pribidclosingdate.'"';
}
$pribidevaluationdate=$request->input('pri_bid_evaluation_date');
if(isset($pribidevaluationdate) && isset($pribidevaluationdate)){
$query .=' AND pri_bid_evaluation_date="'.$pribidevaluationdate.'"';
}
$pribidawarddate=$request->input('pri_bid_award_date');
if(isset($pribidawarddate) && isset($pribidawarddate)){
$query .=' AND pri_bid_award_date="'.$pribidawarddate.'"';
}
$priprocurementstageid=$request->input('pri_procurement_stage_id');
if(isset($priprocurementstageid) && isset($priprocurementstageid)){
    $query .= " AND pri_procurement_stage_id = '" . $priprocurementstageid . "'";
}
$priprocurementmethodid=$request->input('pri_procurement_method_id');
if(isset($priprocurementmethodid) && isset($priprocurementmethodid)){
    $query .= " AND pri_procurement_method_id = '" . $priprocurementmethodid . "'";
}
//START
$requesttype=$request->input('request_type');
$priprojectid=$request->input('pri_project_id');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($priprojectid) && isset($priprojectid)){
$query .= " AND pri_project_id = '$priprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
$query.=' ORDER BY pri_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>1,'is_role_deletable'=>0,'is_role_can_add'=>1);
$permission=$this->ownsProject($request,$priprojectid);
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
        'pri_total_procurement_amount'=> trans('form_lang.pri_total_procurement_amount'),
        'pri_bid_announced_date'=> trans('form_lang.pri_bid_announced_date'),
        'pri_bid_invitation_date'=> trans('form_lang.pri_bid_invitation_date'),
        'pri_bid_opening_date'=> trans('form_lang.pri_bid_opening_date'),
        'pri_bid_closing_date'=> trans('form_lang.pri_bid_closing_date'),
        'pri_bid_evaluation_date'=> trans('form_lang.pri_bid_evaluation_date'),
        'pri_bid_award_date'=> trans('form_lang.pri_bid_award_date'),
        'pri_project_id'=> trans('form_lang.pri_project_id'),
        'pri_procurement_stage_id'=> trans('form_lang.pri_procurement_stage_id'),
        'pri_procurement_method_id'=> trans('form_lang.pri_procurement_method_id'),
        'pri_description'=> trans('form_lang.pri_description'),
        'pri_status'=> trans('form_lang.pri_status'),

    ];
    $rules= [
        'pri_total_procurement_amount'=> 'max:200',
        'pri_bid_announced_date'=> 'max:15',
        'pri_bid_invitation_date'=> 'max:15',
        'pri_bid_opening_date'=> 'max:15',
        'pri_bid_closing_date'=> 'max:15',
        'pri_bid_evaluation_date'=> 'max:15',
        'pri_bid_award_date'=> 'max:15',
        'pri_procurement_stage_id'=> 'max:200',
        'pri_procurement_method_id'=> 'max:200',
        'pri_description'=> 'max:425',
//'pri_status'=> 'integer',

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
        $id=$request->get("pri_id");
        $requestData = $request->all();
        $status= $request->input('pri_status');
        if($status=="true"){
            $requestData['pri_status']=1;
        }else{
            $requestData['pri_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprocurementinformation::findOrFail($id);
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
        $data_info=Modelpmsprocurementinformation::create($requestData);
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
        'pri_total_procurement_amount'=> trans('form_lang.pri_total_procurement_amount'),
        'pri_bid_announced_date'=> trans('form_lang.pri_bid_announced_date'),
        'pri_bid_invitation_date'=> trans('form_lang.pri_bid_invitation_date'),
        'pri_bid_opening_date'=> trans('form_lang.pri_bid_opening_date'),
        'pri_bid_closing_date'=> trans('form_lang.pri_bid_closing_date'),
        'pri_bid_evaluation_date'=> trans('form_lang.pri_bid_evaluation_date'),
        'pri_bid_award_date'=> trans('form_lang.pri_bid_award_date'),
        'pri_project_id'=> trans('form_lang.pri_project_id'),
        'pri_procurement_stage_id'=> trans('form_lang.pri_procurement_stage_id'),
        'pri_procurement_method_id'=> trans('form_lang.pri_procurement_method_id'),
        'pri_description'=> trans('form_lang.pri_description'),
        'pri_status'=> trans('form_lang.pri_status'),

    ];
    $rules= [
        'pri_total_procurement_amount'=> 'max:200',
        'pri_bid_announced_date'=> 'max:15',
        'pri_bid_invitation_date'=> 'max:15',
        'pri_bid_opening_date'=> 'max:15',
        'pri_bid_closing_date'=> 'max:15',
        'pri_bid_evaluation_date'=> 'max:15',
        'pri_bid_award_date'=> 'max:15',
        'pri_procurement_stage_id'=> 'max:200',
        'pri_procurement_method_id'=> 'max:200',
        'pri_description'=> 'max:425',

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
        $requestData['pri_created_by']=auth()->user()->usr_id;
        $status= $request->input('pri_status');
        if($status=="true"){
            $requestData['pri_status']=1;
        }else{
            $requestData['pri_status']=0;
        }
        //$requestData['pri_created_by']=1;
        $data_info=Modelpmsprocurementinformation::create($requestData);
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
    $id=$request->get("pri_id");
    Modelpmsprocurementinformation::destroy($id);
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
    Route::resource('procurement_information', 'PmsprocurementinformationController');
    Route::post('procurement_information/listgrid', 'Api\PmsprocurementinformationController@listgrid');
    Route::post('procurement_information/insertgrid', 'Api\PmsprocurementinformationController@insertgrid');
    Route::post('procurement_information/updategrid', 'Api\PmsprocurementinformationController@updategrid');
    Route::post('procurement_information/deletegrid', 'Api\PmsprocurementinformationController@deletegrid');
}
}