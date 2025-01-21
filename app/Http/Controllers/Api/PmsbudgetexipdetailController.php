<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetexipdetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetexipdetailController extends MyController
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
        $query='SELECT bed_id,bed_budget_expenditure_id,bed_budget_expenditure_code_id,bed_amount,bed_description,bed_create_time,bed_update_time,bed_delete_time,bed_created_by,bed_status FROM pms_budget_exip_detail ';       
        $query .=' WHERE bed_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_exip_detail_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetexipdetail::findOrFail($id);
        //$data['pms_budget_exip_detail_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_exip_detail");
        return view('budget_exip_detail.show_pms_budget_exip_detail', $data);
    }
    //Get List
    public function listgrid(Request $request){
       $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
       $permissionData=$this->getPagePermission($request,45);
       if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
    }
    $query="SELECT bed_id,bed_budget_expenditure_id,bed_budget_expenditure_code_id,bed_amount,bed_description,bed_create_time,bed_update_time,bed_delete_time,bed_created_by,bed_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_budget_exip_detail ";
    $query .=' WHERE 1=1';
    $bedid=$request->input('bed_id');
    if(isset($bedid) && isset($bedid)){
        $query .=' AND bed_id="'.$bedid.'"'; 
    }
    $bedbudgetexpenditureid=$request->input('budget_expend_id');
    if(isset($bedbudgetexpenditureid) && isset($bedbudgetexpenditureid)){
        $query .=" AND bed_budget_expenditure_id='".$bedbudgetexpenditureid."'"; 
    }
    $bedbudgetexpenditurecodeid=$request->input('bed_budget_expenditure_code_id');
    if(isset($bedbudgetexpenditurecodeid) && isset($bedbudgetexpenditurecodeid)){
        $query .=' AND bed_budget_expenditure_code_id="'.$bedbudgetexpenditurecodeid.'"'; 
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
        'bed_budget_expenditure_id'=> trans('form_lang.bed_budget_expenditure_id'), 
        'bed_budget_expenditure_code_id'=> trans('form_lang.bed_budget_expenditure_code_id'), 
        'bed_amount'=> trans('form_lang.bed_amount'), 
        'bed_description'=> trans('form_lang.bed_description'), 
        'bed_status'=> trans('form_lang.bed_status'), 
    ];
    $rules= [
        'bed_budget_expenditure_code_id'=> 'max:200', 
        'bed_amount'=> 'max:200', 
        'bed_description'=> 'max:425'
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
        $id=$request->get("bed_id");
        $requestData = $request->all();            
        $status= $request->input('bed_status');
        if($status=="true"){
            $requestData['bed_status']=1;
        }else{
            $requestData['bed_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetexipdetail::findOrFail($id);
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
        $data_info=Modelpmsbudgetexipdetail::create($requestData);
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
        'bed_budget_expenditure_id'=> trans('form_lang.bed_budget_expenditure_id'), 
        'bed_budget_expenditure_code_id'=> trans('form_lang.bed_budget_expenditure_code_id'), 
        'bed_amount'=> trans('form_lang.bed_amount'), 
        'bed_description'=> trans('form_lang.bed_description'), 
        'bed_status'=> trans('form_lang.bed_status'), 
    ];
    $rules= [
        'bed_budget_expenditure_code_id'=> 'max:200', 
        'bed_amount'=> 'max:200', 
        'bed_description'=> 'max:425'
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
        //$requestData['bed_created_by']=auth()->user()->usr_Id;
        $status= $request->input('bed_status');
        if($status=="true"){
            $requestData['bed_status']=1;
        }else{
            $requestData['bed_status']=0;
        }
        $requestData['bed_created_by']=1;
        $data_info=Modelpmsbudgetexipdetail::create($requestData);
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
    $id=$request->get("bed_id");
    Modelpmsbudgetexipdetail::destroy($id);
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