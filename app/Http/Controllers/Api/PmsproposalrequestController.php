<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproposalrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsproposalrequestController extends MyController
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
        $query='SELECT prr_id,prr_title,prr_project_id,prr_request_status_id,prr_request_category_id,prr_request_date_et,prr_request_date_gc,prr_description,prr_create_time,prr_update_time,prr_delete_time,prr_created_by,prr_status FROM pms_proposal_request ';       
        
        $query .=' WHERE prr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_proposal_request_data']=$data_info[0];
        }
        //$data_info = Modelpmsproposalrequest::findOrFail($id);
        //$data['pms_proposal_request_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_proposal_request");
        return view('proposal_request.show_pms_proposal_request', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,58);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT prr_id,prr_title,prr_project_id,prr_request_status_id,prr_request_category_id,prr_request_date_et,prr_request_date_gc,prr_description,prr_create_time,prr_update_time,prr_delete_time,prr_created_by,prr_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_proposal_request ";
     
     $query .=' WHERE 1=1';
     $prrid=$request->input('prr_id');
if(isset($prrid) && isset($prrid)){
$query .=' AND prr_id="'.$prrid.'"'; 
}
$prrtitle=$request->input('prr_title');
if(isset($prrtitle) && isset($prrtitle)){
$query .=' AND prr_title="'.$prrtitle.'"'; 
}
$prrprojectid=$request->input('project_id');
if(isset($prrprojectid) && isset($prrprojectid)){
$query .=" AND prr_project_id='".$prrprojectid."'"; 
}
$prrrequeststatusid=$request->input('prr_request_status_id');
if(isset($prrrequeststatusid) && isset($prrrequeststatusid)){
$query .=' AND prr_request_status_id="'.$prrrequeststatusid.'"'; 
}
$prrrequestcategoryid=$request->input('prr_request_category_id');
if(isset($prrrequestcategoryid) && isset($prrrequestcategoryid)){
$query .=' AND prr_request_category_id="'.$prrrequestcategoryid.'"'; 
}
$prrrequestdateet=$request->input('prr_request_date_et');
if(isset($prrrequestdateet) && isset($prrrequestdateet)){
$query .=' AND prr_request_date_et="'.$prrrequestdateet.'"'; 
}
$prrrequestdategc=$request->input('prr_request_date_gc');
if(isset($prrrequestdategc) && isset($prrrequestdategc)){
$query .=' AND prr_request_date_gc="'.$prrrequestdategc.'"'; 
}
$prrdescription=$request->input('prr_description');
if(isset($prrdescription) && isset($prrdescription)){
$query .=' AND prr_description="'.$prrdescription.'"'; 
}
$prrcreatetime=$request->input('prr_create_time');
if(isset($prrcreatetime) && isset($prrcreatetime)){
$query .=' AND prr_create_time="'.$prrcreatetime.'"'; 
}
$prrupdatetime=$request->input('prr_update_time');
if(isset($prrupdatetime) && isset($prrupdatetime)){
$query .=' AND prr_update_time="'.$prrupdatetime.'"'; 
}
$prrdeletetime=$request->input('prr_delete_time');
if(isset($prrdeletetime) && isset($prrdeletetime)){
$query .=' AND prr_delete_time="'.$prrdeletetime.'"'; 
}
$prrcreatedby=$request->input('prr_created_by');
if(isset($prrcreatedby) && isset($prrcreatedby)){
$query .=' AND prr_created_by="'.$prrcreatedby.'"'; 
}
$prrstatus=$request->input('prr_status');
if(isset($prrstatus) && isset($prrstatus)){
$query .=' AND prr_status="'.$prrstatus.'"'; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit,'is_role_deletable'=>$permissionData->pem_delete,'is_role_can_add'=>$permissionData->pem_insert));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'prr_title'=> trans('form_lang.prr_title'), 
'prr_project_id'=> trans('form_lang.prr_project_id'), 
'prr_request_status_id'=> trans('form_lang.prr_request_status_id'), 
'prr_request_category_id'=> trans('form_lang.prr_request_category_id'), 
'prr_request_date_et'=> trans('form_lang.prr_request_date_et'), 
'prr_request_date_gc'=> trans('form_lang.prr_request_date_gc'), 
'prr_description'=> trans('form_lang.prr_description'), 
'prr_status'=> trans('form_lang.prr_status'), 

    ];
    $rules= [
        'prr_title'=> 'max:200',
'prr_request_status_id'=> 'max:200', 
'prr_request_category_id'=> 'max:200', 
'prr_request_date_et'=> 'max:10', 
'prr_request_date_gc'=> 'max:200', 
'prr_description'=> 'max:425', 
//'prr_status'=> 'integer', 

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
        $id=$request->get("prr_id");
        $requestData = $request->all();            
        $status= $request->input('prr_status');
        if($status=="true"){
            $requestData['prr_status']=1;
        }else{
            $requestData['prr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsproposalrequest::findOrFail($id);
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
        $data_info=Modelpmsproposalrequest::create($requestData);
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
        'prr_title'=> trans('form_lang.prr_title'), 
'prr_project_id'=> trans('form_lang.prr_project_id'), 
'prr_request_status_id'=> trans('form_lang.prr_request_status_id'), 
'prr_request_category_id'=> trans('form_lang.prr_request_category_id'), 
'prr_request_date_et'=> trans('form_lang.prr_request_date_et'), 
'prr_request_date_gc'=> trans('form_lang.prr_request_date_gc'), 
'prr_description'=> trans('form_lang.prr_description'), 
'prr_status'=> trans('form_lang.prr_status'), 

    ];
    $rules= [
        'prr_title'=> 'max:200',
'prr_request_status_id'=> 'max:200', 
'prr_request_category_id'=> 'max:200', 
'prr_request_date_et'=> 'max:10', 
'prr_request_date_gc'=> 'max:200', 
'prr_description'=> 'max:425', 
//'prr_status'=> 'integer', 

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
        $requestData['prr_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prr_status');
        if($status=="true"){
            $requestData['prr_status']=1;
        }else{
            $requestData['prr_status']=0;
        }
        $requestData['prr_created_by']=1;
        $data_info=Modelpmsproposalrequest::create($requestData);
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
    $id=$request->get("prr_id");
    Modelpmsproposalrequest::destroy($id);
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
    Route::resource('proposal_request', 'PmsproposalrequestController');
    Route::post('proposal_request/listgrid', 'Api\PmsproposalrequestController@listgrid');
    Route::post('proposal_request/insertgrid', 'Api\PmsproposalrequestController@insertgrid');
    Route::post('proposal_request/updategrid', 'Api\PmsproposalrequestController@updategrid');
    Route::post('proposal_request/deletegrid', 'Api\PmsproposalrequestController@deletegrid');
}
}