<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetrequestamount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetrequestamountController extends MyController
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
        $query='SELECT bra_id,bra_expenditure_code_id,bra_budget_request_id,bra_current_year_expense,bra_requested_amount,bra_approved_amount,bra_source_government_requested,bra_source_government_approved,bra_source_internal_requested,bra_source_internal_approved,bra_source_support_requested,bra_source_support_approved,bra_source_support_code,bra_source_credit_requested,bra_source_credit_approved,bra_source_credit_code,bra_source_other_requested,bra_source_other_approved,bra_source_other_code,bra_requested_date,bra_approved_date,bra_description,bra_create_time,bra_update_time,bra_delete_time,bra_created_by,bra_status FROM pms_budget_request_amount ';       
        
        $query .=' WHERE bra_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_request_amount_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetrequestamount::findOrFail($id);
        //$data['pms_budget_request_amount_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_request_amount");
        return view('budget_request_amount.show_pms_budget_request_amount', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT bra_id,bra_expenditure_code_id,bra_budget_request_id,bra_current_year_expense,bra_requested_amount,bra_approved_amount,bra_source_government_requested,bra_source_government_approved,bra_source_internal_requested,bra_source_internal_approved,bra_source_support_requested,bra_source_support_approved,bra_source_support_code,bra_source_credit_requested,bra_source_credit_approved,bra_source_credit_code,bra_source_other_requested,bra_source_other_approved,bra_source_other_code,bra_requested_date,bra_approved_date,bra_description,bra_create_time,bra_update_time,bra_delete_time,bra_created_by,bra_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_budget_request_amount ";
     
     $query .=' WHERE 1=1';
     $braid=$request->input('bra_id');
if(isset($braid) && isset($braid)){
$query .=' AND bra_id="'.$braid.'"'; 
}
$braexpenditurecodeid=$request->input('bra_expenditure_code_id');
if(isset($braexpenditurecodeid) && isset($braexpenditurecodeid)){
$query .=' AND bra_expenditure_code_id="'.$braexpenditurecodeid.'"'; 
}
$brabudgetrequestid=$request->input('budget_request_id');
if(isset($brabudgetrequestid) && isset($brabudgetrequestid)){
$query .=" AND bra_budget_request_id='".$brabudgetrequestid."'"; 
}
$bracurrentyearexpense=$request->input('bra_current_year_expense');
if(isset($bracurrentyearexpense) && isset($bracurrentyearexpense)){
$query .=' AND bra_current_year_expense="'.$bracurrentyearexpense.'"'; 
}
$brasourceinternalrequested=$request->input('bra_source_internal_requested');
if(isset($brasourceinternalrequested) && isset($brasourceinternalrequested)){
$query .=' AND bra_source_internal_requested="'.$brasourceinternalrequested.'"'; 
}
$brasourceinternalapproved=$request->input('bra_source_internal_approved');
if(isset($brasourceinternalapproved) && isset($brasourceinternalapproved)){
$query .=' AND bra_source_internal_approved="'.$brasourceinternalapproved.'"'; 
}
$brasourcesupportrequested=$request->input('bra_source_support_requested');
if(isset($brasourcesupportrequested) && isset($brasourcesupportrequested)){
$query .=' AND bra_source_support_requested="'.$brasourcesupportrequested.'"'; 
}
$brasourcesupportapproved=$request->input('bra_source_support_approved');
if(isset($brasourcesupportapproved) && isset($brasourcesupportapproved)){
$query .=' AND bra_source_support_approved="'.$brasourcesupportapproved.'"'; 
}
$brasourcesupportcode=$request->input('bra_source_support_code');
if(isset($brasourcesupportcode) && isset($brasourcesupportcode)){
$query .=' AND bra_source_support_code="'.$brasourcesupportcode.'"'; 
}
$brasourcecreditrequested=$request->input('bra_source_credit_requested');
if(isset($brasourcecreditrequested) && isset($brasourcecreditrequested)){
$query .=' AND bra_source_credit_requested="'.$brasourcecreditrequested.'"'; 
}
$brasourcecreditapproved=$request->input('bra_source_credit_approved');
if(isset($brasourcecreditapproved) && isset($brasourcecreditapproved)){
$query .=' AND bra_source_credit_approved="'.$brasourcecreditapproved.'"'; 
}
$brasourcecreditcode=$request->input('bra_source_credit_code');
if(isset($brasourcecreditcode) && isset($brasourcecreditcode)){
$query .=' AND bra_source_credit_code="'.$brasourcecreditcode.'"'; 
}
$brasourceotherrequested=$request->input('bra_source_other_requested');
if(isset($brasourceotherrequested) && isset($brasourceotherrequested)){
$query .=' AND bra_source_other_requested="'.$brasourceotherrequested.'"'; 
}
$brasourceotherapproved=$request->input('bra_source_other_approved');
if(isset($brasourceotherapproved) && isset($brasourceotherapproved)){
$query .=' AND bra_source_other_approved="'.$brasourceotherapproved.'"'; 
}
$brasourceothercode=$request->input('bra_source_other_code');
if(isset($brasourceothercode) && isset($brasourceothercode)){
$query .=' AND bra_source_other_code="'.$brasourceothercode.'"'; 
}
$brarequesteddate=$request->input('bra_requested_date');
if(isset($brarequesteddate) && isset($brarequesteddate)){
$query .=' AND bra_requested_date="'.$brarequesteddate.'"'; 
}
$braapproveddate=$request->input('bra_approved_date');
if(isset($braapproveddate) && isset($braapproveddate)){
$query .=' AND bra_approved_date="'.$braapproveddate.'"'; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'bra_expenditure_code_id'=> trans('form_lang.bra_expenditure_code_id'), 
'bra_budget_request_id'=> trans('form_lang.bra_budget_request_id'), 
'bra_current_year_expense'=> trans('form_lang.bra_current_year_expense'), 
'bra_requested_amount'=> trans('form_lang.bra_requested_amount'), 
'bra_approved_amount'=> trans('form_lang.bra_approved_amount'), 
'bra_source_government_requested'=> trans('form_lang.bra_source_government_requested'), 
'bra_source_government_approved'=> trans('form_lang.bra_source_government_approved'), 
'bra_source_internal_requested'=> trans('form_lang.bra_source_internal_requested'), 
'bra_source_internal_approved'=> trans('form_lang.bra_source_internal_approved'), 
'bra_source_support_requested'=> trans('form_lang.bra_source_support_requested'), 
'bra_source_support_approved'=> trans('form_lang.bra_source_support_approved'), 
'bra_source_support_code'=> trans('form_lang.bra_source_support_code'), 
'bra_source_credit_requested'=> trans('form_lang.bra_source_credit_requested'), 
'bra_source_credit_approved'=> trans('form_lang.bra_source_credit_approved'), 
'bra_source_credit_code'=> trans('form_lang.bra_source_credit_code'), 
'bra_source_other_requested'=> trans('form_lang.bra_source_other_requested'), 
'bra_source_other_approved'=> trans('form_lang.bra_source_other_approved'), 
'bra_source_other_code'=> trans('form_lang.bra_source_other_code'), 
'bra_requested_date'=> trans('form_lang.bra_requested_date'), 
'bra_approved_date'=> trans('form_lang.bra_approved_date'), 
'bra_description'=> trans('form_lang.bra_description'), 
'bra_status'=> trans('form_lang.bra_status'), 

    ];
    $rules= [
        'bra_expenditure_code_id'=> 'max:200',
'bra_source_credit_code'=> 'max:10', 
'bra_requested_date'=> 'max:200', 
'bra_approved_date'=> 'max:10', 
'bra_description'=> 'max:425'
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
        $id=$request->get("bra_id");
        $requestData = $request->all();            
        $status= $request->input('bra_status');
        if($status=="true"){
            $requestData['bra_status']=1;
        }else{
            $requestData['bra_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetrequestamount::findOrFail($id);
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
        $data_info=Modelpmsbudgetrequestamount::create($requestData);
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
        'bra_expenditure_code_id'=> trans('form_lang.bra_expenditure_code_id'), 
'bra_budget_request_id'=> trans('form_lang.bra_budget_request_id'), 
'bra_current_year_expense'=> trans('form_lang.bra_current_year_expense'), 
'bra_requested_amount'=> trans('form_lang.bra_requested_amount'), 
'bra_approved_amount'=> trans('form_lang.bra_approved_amount'), 
'bra_source_government_requested'=> trans('form_lang.bra_source_government_requested'), 
'bra_source_government_approved'=> trans('form_lang.bra_source_government_approved'), 
'bra_source_internal_requested'=> trans('form_lang.bra_source_internal_requested'), 
'bra_source_internal_approved'=> trans('form_lang.bra_source_internal_approved'), 
'bra_source_support_requested'=> trans('form_lang.bra_source_support_requested'), 
'bra_source_support_approved'=> trans('form_lang.bra_source_support_approved'), 
'bra_source_support_code'=> trans('form_lang.bra_source_support_code'), 
'bra_source_credit_requested'=> trans('form_lang.bra_source_credit_requested'), 
'bra_source_credit_approved'=> trans('form_lang.bra_source_credit_approved'), 
'bra_source_credit_code'=> trans('form_lang.bra_source_credit_code'), 
'bra_source_other_requested'=> trans('form_lang.bra_source_other_requested'), 
'bra_source_other_approved'=> trans('form_lang.bra_source_other_approved'), 
'bra_source_other_code'=> trans('form_lang.bra_source_other_code'), 
'bra_requested_date'=> trans('form_lang.bra_requested_date'), 
'bra_approved_date'=> trans('form_lang.bra_approved_date'), 
'bra_description'=> trans('form_lang.bra_description'), 

    ];
    $rules= [
        'bra_expenditure_code_id'=> 'max:200',
'bra_source_credit_code'=> 'max:10', 
'bra_requested_date'=> 'max:200', 
'bra_approved_date'=> 'max:10', 
'bra_description'=> 'max:425'
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
        //$requestData['bra_created_by']=auth()->user()->usr_Id;
        $status= $request->input('bra_status');
        if($status=="true"){
            $requestData['bra_status']=1;
        }else{
            $requestData['bra_status']=0;
        }
        $requestData['bra_created_by']=1;
        $data_info=Modelpmsbudgetrequestamount::create($requestData);
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
    $id=$request->get("bra_id");
    Modelpmsbudgetrequestamount::destroy($id);
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
}