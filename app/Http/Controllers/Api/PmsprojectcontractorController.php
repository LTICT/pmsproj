<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectcontractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectcontractorController extends MyController
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
        $query='SELECT cni_id,cni_name,cni_tin_num,pms_contractor_type.cnt_type_name_or AS cni_contractor_type_id,cni_vat_num,cni_total_contract_price,cni_contract_start_date_et,cni_contract_start_date_gc,cni_contract_end_date_et,cni_contract_end_date_gc,cni_contact_person,cni_phone_number,cni_address,cni_email,cni_website,cni_project_id,cni_procrument_method,cni_bid_invitation_date,cni_bid_opening_date,cni_bid_evaluation_date,cni_bid_award_date,cni_bid_contract_signing_date,cni_description,cni_create_time,cni_update_time,cni_delete_time,cni_created_by,cni_status FROM pms_project_contractor ';       
        $query .= ' INNER JOIN pms_contractor_type ON pms_project_contractor.cni_contractor_type_id = pms_contractor_type.cnt_id'; 

        $query .=' WHERE cni_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_contractor_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectcontractor::findOrFail($id);
        //$data['pms_project_contractor_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_contractor");
        return view('project_contractor.show_pms_project_contractor', $data);
    }
    
    public function listgrid(Request $request){
     $query='SELECT cni_financial_start,
cni_physical_start,
cni_financial_end,
cni_physical_end,prj_name,prj_code, cni_id,cni_name,cni_tin_num,pms_contractor_type.cnt_type_name_or AS cni_contractor_type,cni_contractor_type_id,cni_vat_num,cni_total_contract_price,cni_contract_start_date_et,cni_contract_start_date_gc,cni_contract_end_date_et,cni_contract_end_date_gc,cni_contact_person,cni_phone_number,cni_address,cni_email,cni_website,cni_project_id,cni_procrument_method,cni_bid_invitation_date,cni_bid_opening_date,cni_bid_evaluation_date,cni_bid_award_date,cni_bid_contract_signing_date,cni_description,cni_create_time,cni_update_time,cni_delete_time,cni_created_by,cni_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_contractor ';       
     $query .= ' INNER JOIN pms_contractor_type ON pms_project_contractor.cni_contractor_type_id = pms_contractor_type.cnt_id'; 
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_contractor.cni_project_id';
     $query .=' WHERE 1=1';
 $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('contractsign_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND cni_bid_contract_signing_date >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('contractsign_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND cni_bid_contract_signing_date <='".$endTime." 23 59 59'"; 
}
     $cniid=$request->input('cni_id');
if(isset($cniid) && isset($cniid)){
$query .=' AND cni_id="'.$cniid.'"'; 
}
$cniname=$request->input('cni_name');
if(isset($cniname) && isset($cniname)){
$query .=' AND cni_name="'.$cniname.'"'; 
}
$cnitinnum=$request->input('cni_tin_num');
if(isset($cnitinnum) && isset($cnitinnum)){
$query .=' AND cni_tin_num="'.$cnitinnum.'"'; 
}
$cnicontractortypeid=$request->input('cni_contractor_type_id');
if(isset($cnicontractortypeid) && isset($cnicontractortypeid)){
$query .=" AND cni_contractor_type_id='".$cnicontractortypeid."'"; 
}
$cnivatnum=$request->input('cni_vat_num');
if(isset($cnivatnum) && isset($cnivatnum)){
$query .=' AND cni_vat_num="'.$cnivatnum.'"'; 
}
$cnitotalcontractprice=$request->input('cni_total_contract_price');
if(isset($cnitotalcontractprice) && isset($cnitotalcontractprice)){
$query .=' AND cni_total_contract_price="'.$cnitotalcontractprice.'"'; 
}
$cnicontractstartdateet=$request->input('cni_contract_start_date_et');
if(isset($cnicontractstartdateet) && isset($cnicontractstartdateet)){
$query .=' AND cni_contract_start_date_et="'.$cnicontractstartdateet.'"'; 
}
$cnicontractstartdategc=$request->input('cni_contract_start_date_gc');
if(isset($cnicontractstartdategc) && isset($cnicontractstartdategc)){
$query .=' AND cni_contract_start_date_gc="'.$cnicontractstartdategc.'"'; 
}
$cnicontractenddateet=$request->input('cni_contract_end_date_et');
if(isset($cnicontractenddateet) && isset($cnicontractenddateet)){
$query .=' AND cni_contract_end_date_et="'.$cnicontractenddateet.'"'; 
}
$cnicontractenddategc=$request->input('cni_contract_end_date_gc');
if(isset($cnicontractenddategc) && isset($cnicontractenddategc)){
$query .=' AND cni_contract_end_date_gc="'.$cnicontractenddategc.'"'; 
}
$cnicontactperson=$request->input('cni_contact_person');
if(isset($cnicontactperson) && isset($cnicontactperson)){
$query .=' AND cni_contact_person="'.$cnicontactperson.'"'; 
}
$cniphonenumber=$request->input('cni_phone_number');
if(isset($cniphonenumber) && isset($cniphonenumber)){
$query .=' AND cni_phone_number="'.$cniphonenumber.'"'; 
}
$cniaddress=$request->input('cni_address');
if(isset($cniaddress) && isset($cniaddress)){
$query .=' AND cni_address="'.$cniaddress.'"'; 
}
$cniemail=$request->input('cni_email');
if(isset($cniemail) && isset($cniemail)){
$query .=' AND cni_email="'.$cniemail.'"'; 
}
$cniwebsite=$request->input('cni_website');
if(isset($cniwebsite) && isset($cniwebsite)){
$query .=' AND cni_website="'.$cniwebsite.'"'; 
}
$cniprojectid=$request->input('cni_project_id');
if(isset($cniprojectid) && isset($cniprojectid)){
$query .= " AND cni_project_id = '".$cniprojectid."'";

}
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>0);
$permission=$this->ownsProject($request,$cniprojectid);
if($permission !=null)
{
   $previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);

return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'cni_name'=> trans('form_lang.cni_name'), 
'cni_tin_num'=> trans('form_lang.cni_tin_num'), 
'cni_contractor_type_id'=> trans('form_lang.cni_contractor_type_id'), 
'cni_vat_num'=> trans('form_lang.cni_vat_num'), 
'cni_total_contract_price'=> trans('form_lang.cni_total_contract_price'), 
'cni_contract_start_date_et'=> trans('form_lang.cni_contract_start_date_et'), 
'cni_contract_start_date_gc'=> trans('form_lang.cni_contract_start_date_gc'), 
'cni_contract_end_date_et'=> trans('form_lang.cni_contract_end_date_et'), 
'cni_contract_end_date_gc'=> trans('form_lang.cni_contract_end_date_gc'), 
'cni_contact_person'=> trans('form_lang.cni_contact_person'), 
'cni_phone_number'=> trans('form_lang.cni_phone_number'), 
'cni_address'=> trans('form_lang.cni_address'), 
'cni_email'=> trans('form_lang.cni_email'), 
'cni_website'=> trans('form_lang.cni_website'), 
'cni_project_id'=> trans('form_lang.cni_project_id'), 
'cni_bid_invitation_date'=> trans('form_lang.cni_bid_invitation_date'), 
'cni_bid_opening_date'=> trans('form_lang.cni_bid_opening_date'), 
'cni_bid_evaluation_date'=> trans('form_lang.cni_bid_evaluation_date'), 
'cni_bid_award_date'=> trans('form_lang.cni_bid_award_date'), 
'cni_bid_contract_signing_date'=> trans('form_lang.cni_bid_contract_signing_date'), 
'cni_description'=> trans('form_lang.cni_description'), 
'cni_status'=> trans('form_lang.cni_status'), 

    ];
    $rules= [
'cni_name'=> 'max:100', 
'cni_tin_num'=> 'max:16', 
'cni_contractor_type_id'=> 'max:100', 
'cni_vat_num'=> 'max:45', 
'cni_contract_end_date_gc'=> 'max:10', 
'cni_contact_person'=> 'max:100', 
'cni_phone_number'=> 'max:15', 
'cni_address'=> 'max:425', 
'cni_email'=> 'max:50', 
'cni_website'=> 'max:50', 
'cni_bid_invitation_date'=> 'max:10', 
'cni_bid_opening_date'=> 'max:10', 
'cni_bid_evaluation_date'=> 'max:10', 
'cni_bid_award_date'=> 'max:10', 
'cni_bid_contract_signing_date'=> 'max:10', 
'cni_description'=> 'max:425', 

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
        $id=$request->get("cni_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('cni_status');
        if($status=="true"){
            $requestData['cni_status']=1;
        }else{
            $requestData['cni_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectcontractor::findOrFail($id);
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
        //$requestData['cni_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectcontractor::create($requestData);
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
        'cni_name'=> trans('form_lang.cni_name'), 
'cni_tin_num'=> trans('form_lang.cni_tin_num'), 
'cni_contractor_type_id'=> trans('form_lang.cni_contractor_type_id'), 
'cni_vat_num'=> trans('form_lang.cni_vat_num'), 
'cni_total_contract_price'=> trans('form_lang.cni_total_contract_price'), 
'cni_contract_start_date_et'=> trans('form_lang.cni_contract_start_date_et'), 
'cni_contract_start_date_gc'=> trans('form_lang.cni_contract_start_date_gc'), 
'cni_contract_end_date_et'=> trans('form_lang.cni_contract_end_date_et'), 
'cni_contract_end_date_gc'=> trans('form_lang.cni_contract_end_date_gc'), 
'cni_contact_person'=> trans('form_lang.cni_contact_person'), 
'cni_phone_number'=> trans('form_lang.cni_phone_number'), 
'cni_address'=> trans('form_lang.cni_address'), 
'cni_email'=> trans('form_lang.cni_email'), 
'cni_website'=> trans('form_lang.cni_website'), 
'cni_project_id'=> trans('form_lang.cni_project_id'), 
'cni_bid_invitation_date'=> trans('form_lang.cni_bid_invitation_date'), 
'cni_bid_opening_date'=> trans('form_lang.cni_bid_opening_date'), 
'cni_bid_evaluation_date'=> trans('form_lang.cni_bid_evaluation_date'), 
'cni_bid_award_date'=> trans('form_lang.cni_bid_award_date'), 
'cni_bid_contract_signing_date'=> trans('form_lang.cni_bid_contract_signing_date'), 
'cni_description'=> trans('form_lang.cni_description'), 
'cni_status'=> trans('form_lang.cni_status'), 

    ];
    $rules= [
    'cni_name'=> 'max:100', 
    'cni_tin_num'=> 'max:20', 
    'cni_contractor_type_id'=> 'max:100', 
    'cni_vat_num'=> 'max:45', 
    'cni_contract_end_date_gc'=> 'max:10', 
    'cni_contact_person'=> 'max:100', 
    'cni_phone_number'=> 'max:15', 
    'cni_address'=> 'max:425', 
    'cni_email'=> 'max:100', 
    'cni_website'=> 'max:100', 
    'cni_bid_invitation_date'=> 'max:10', 
    'cni_bid_opening_date'=> 'max:10', 
    'cni_bid_evaluation_date'=> 'max:10', 
    'cni_bid_award_date'=> 'max:10', 
    'cni_bid_contract_signing_date'=> 'max:10', 
    'cni_description'=> 'max:425', 

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
        //$requestData['cni_created_by']=auth()->user()->usr_Id;
        $requestData['cni_created_by']=1;
        $status= $request->input('cni_status');
        if($status=="true"){
            $requestData['cni_status']=1;
        }else{
            $requestData['cni_status']=0;
        }
        $data_info=Modelpmsprojectcontractor::create($requestData);
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
    $id=$request->get("cni_id");
    Modelpmsprojectcontractor::destroy($id);
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