<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetexsource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetexsourceController extends MyController
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
        $query='SELECT bes_id,bes_budget_request_id,bes_organ_code,bes_org_name,bes_support_amount,bes_credit_amount,bes_description,bes_create_time,bes_update_time,bes_delete_time,bes_created_by,bes_status FROM pms_budget_ex_source ';       
        $query .=' WHERE bes_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_ex_source_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetexsource::findOrFail($id);
        //$data['pms_budget_ex_source_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_ex_source");
        return view('budget_ex_source.show_pms_budget_ex_source', $data);
    }
    //Get List
    public function listgrid(Request $request){
       $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
       $permissionData=$this->getPagePermission($request,45);
       if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
    }
    $query="SELECT bes_id,bes_budget_request_id,bes_organ_code,bes_org_name,bes_support_amount,bes_credit_amount,bes_description,bes_create_time,bes_update_time,bes_delete_time,bes_created_by,bes_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_budget_ex_source ";
    $query .=' WHERE 1=1';
    $besid=$request->input('bes_id');
    if(isset($besid) && isset($besid)){
        $query .=' AND bes_id="'.$besid.'"'; 
    }
    $besbudgetrequestid=$request->input('budget_request_id');
    if(isset($besbudgetrequestid) && isset($besbudgetrequestid)){
        $query .=" AND bes_budget_request_id='".$besbudgetrequestid."'"; 
    }
    $besorgancode=$request->input('bes_organ_code');
    if(isset($besorgancode) && isset($besorgancode)){
        $query .=' AND bes_organ_code="'.$besorgancode.'"'; 
    }
    $besorgname=$request->input('bes_org_name');
    if(isset($besorgname) && isset($besorgname)){
        $query .=' AND bes_org_name="'.$besorgname.'"'; 
    }
    $bessupportamount=$request->input('bes_support_amount');
    if(isset($bessupportamount) && isset($bessupportamount)){
        $query .=' AND bes_support_amount="'.$bessupportamount.'"'; 
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
        'bes_budget_request_id'=> trans('form_lang.bes_budget_request_id'), 
        'bes_organ_code'=> trans('form_lang.bes_organ_code'), 
        'bes_org_name'=> trans('form_lang.bes_org_name'), 
        'bes_support_amount'=> trans('form_lang.bes_support_amount'), 
        'bes_credit_amount'=> trans('form_lang.bes_credit_amount'), 
        'bes_description'=> trans('form_lang.bes_description'), 
        'bes_status'=> trans('form_lang.bes_status'), 
    ];
    $rules= [
        'bes_organ_code'=> 'max:200', 
        'bes_org_name'=> 'max:200', 
        'bes_description'=> 'max:425',  
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
        $id=$request->get("bes_id");
        $requestData = $request->all();            
        $status= $request->input('bes_status');
        if($status=="true"){
            $requestData['bes_status']=1;
        }else{
            $requestData['bes_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetexsource::findOrFail($id);
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
        $data_info=Modelpmsbudgetexsource::create($requestData);
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
        'bes_budget_request_id'=> trans('form_lang.bes_budget_request_id'), 
        'bes_organ_code'=> trans('form_lang.bes_organ_code'), 
        'bes_org_name'=> trans('form_lang.bes_org_name'), 
        'bes_support_amount'=> trans('form_lang.bes_support_amount'), 
        'bes_credit_amount'=> trans('form_lang.bes_credit_amount'), 
        'bes_description'=> trans('form_lang.bes_description'), 
        'bes_status'=> trans('form_lang.bes_status')
    ];
    $rules= [
      'bes_organ_code'=> 'max:200', 
      'bes_org_name'=> 'max:200', 
      'bes_description'=> 'max:425',
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
        //$requestData['bes_created_by']=auth()->user()->usr_Id;
    $status= $request->input('bes_status');
    if($status=="true"){
        $requestData['bes_status']=1;
    }else{
        $requestData['bes_status']=0;
    }
    $requestData['bes_created_by']=1;
    $data_info=Modelpmsbudgetexsource::create($requestData);
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
    $id=$request->get("bes_id");
    Modelpmsbudgetexsource::destroy($id);
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