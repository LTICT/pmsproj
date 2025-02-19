<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenrequestinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenrequestinformationController extends MyController
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
        $query='SELECT rqi_id,rqi_title,rqi_object_id,rqi_request_status_id,rqi_request_category_id,rqi_request_date_et,rqi_request_date_gc,rqi_description,rqi_create_time,rqi_update_time,rqi_delete_time,rqi_created_by,rqi_status FROM gen_request_information ';       
        
        $query .=' WHERE rqi_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_request_information_data']=$data_info[0];
        }
        //$data_info = Modelgenrequestinformation::findOrFail($id);
        //$data['gen_request_information_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_request_information");
        return view('request_information.show_gen_request_information', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,59);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT rqi_id,rqi_title,rqi_object_id,rqi_request_status_id,rqi_request_category_id,rqi_request_date_et,rqi_request_date_gc,rqi_description,rqi_create_time,rqi_update_time,rqi_delete_time,rqi_created_by,rqi_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM gen_request_information ";
     
     $query .=' WHERE 1=1';
     $rqiid=$request->input('rqi_id');
if(isset($rqiid) && isset($rqiid)){
$query .=' AND rqi_id="'.$rqiid.'"'; 
}
$rqititle=$request->input('rqi_title');
if(isset($rqititle) && isset($rqititle)){
$query .=' AND rqi_title="'.$rqititle.'"'; 
}
$rqiobjectid=$request->input('project_id');
if(isset($rqiobjectid) && isset($rqiobjectid)){
$query .=" AND rqi_object_id='".$rqiobjectid."'"; 
}
$rqirequeststatusid=$request->input('rqi_request_status_id');
if(isset($rqirequeststatusid) && isset($rqirequeststatusid)){
$query .=' AND rqi_request_status_id="'.$rqirequeststatusid.'"'; 
}
$rqirequestcategoryid=$request->input('rqi_request_category_id');
if(isset($rqirequestcategoryid) && isset($rqirequestcategoryid)){
$query .=' AND rqi_request_category_id="'.$rqirequestcategoryid.'"'; 
}
$rqirequestdateet=$request->input('rqi_request_date_et');
if(isset($rqirequestdateet) && isset($rqirequestdateet)){
$query .=' AND rqi_request_date_et="'.$rqirequestdateet.'"'; 
}
$rqirequestdategc=$request->input('rqi_request_date_gc');
if(isset($rqirequestdategc) && isset($rqirequestdategc)){
$query .=' AND rqi_request_date_gc="'.$rqirequestdategc.'"'; 
}
$rqidescription=$request->input('rqi_description');
if(isset($rqidescription) && isset($rqidescription)){
$query .=' AND rqi_description="'.$rqidescription.'"'; 
}
$rqicreatetime=$request->input('rqi_create_time');
if(isset($rqicreatetime) && isset($rqicreatetime)){
$query .=' AND rqi_create_time="'.$rqicreatetime.'"'; 
}
$rqiupdatetime=$request->input('rqi_update_time');
if(isset($rqiupdatetime) && isset($rqiupdatetime)){
$query .=' AND rqi_update_time="'.$rqiupdatetime.'"'; 
}
$rqideletetime=$request->input('rqi_delete_time');
if(isset($rqideletetime) && isset($rqideletetime)){
$query .=' AND rqi_delete_time="'.$rqideletetime.'"'; 
}
$rqicreatedby=$request->input('rqi_created_by');
if(isset($rqicreatedby) && isset($rqicreatedby)){
$query .=' AND rqi_created_by="'.$rqicreatedby.'"'; 
}
$rqistatus=$request->input('rqi_status');
if(isset($rqistatus) && isset($rqistatus)){
$query .=' AND rqi_status="'.$rqistatus.'"'; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 2,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'rqi_title'=> trans('form_lang.rqi_title'), 
'rqi_object_id'=> trans('form_lang.rqi_object_id'), 
'rqi_request_status_id'=> trans('form_lang.rqi_request_status_id'), 
'rqi_request_category_id'=> trans('form_lang.rqi_request_category_id'), 
'rqi_request_date_et'=> trans('form_lang.rqi_request_date_et'), 
'rqi_request_date_gc'=> trans('form_lang.rqi_request_date_gc'), 
'rqi_description'=> trans('form_lang.rqi_description'), 
'rqi_status'=> trans('form_lang.rqi_status'), 

    ];
    $rules= [
        'rqi_title'=> 'max:200', 
'rqi_request_status_id'=> 'max:200', 
'rqi_request_category_id'=> 'max:200', 
'rqi_request_date_et'=> 'max:10', 
'rqi_request_date_gc'=> 'max:200', 
'rqi_description'=> 'max:425', 
'rqi_status'=> 'integer', 

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
        $id=$request->get("rqi_id");
        $requestData = $request->all();            
        $status= $request->input('rqi_status');
        if($status=="true"){
            $requestData['rqi_status']=1;
        }else{
            $requestData['rqi_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgenrequestinformation::findOrFail($id);
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
        $data_info=Modelgenrequestinformation::create($requestData);
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
        'rqi_title'=> trans('form_lang.rqi_title'), 
'rqi_object_id'=> trans('form_lang.rqi_object_id'), 
'rqi_request_status_id'=> trans('form_lang.rqi_request_status_id'), 
'rqi_request_category_id'=> trans('form_lang.rqi_request_category_id'), 
'rqi_request_date_et'=> trans('form_lang.rqi_request_date_et'), 
'rqi_request_date_gc'=> trans('form_lang.rqi_request_date_gc'), 
'rqi_description'=> trans('form_lang.rqi_description'), 
'rqi_status'=> trans('form_lang.rqi_status'), 

    ];
    $rules= [
        'rqi_title'=> 'max:200',
'rqi_request_status_id'=> 'max:200', 
'rqi_request_category_id'=> 'max:200', 
'rqi_request_date_et'=> 'max:10', 
'rqi_request_date_gc'=> 'max:200', 
'rqi_description'=> 'max:425', 
//'rqi_status'=> 'integer', 

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
        $requestData['rqi_created_by']=auth()->user()->usr_Id;
        $status= $request->input('rqi_status');
        if($status=="true"){
            $requestData['rqi_status']=1;
        }else{
            $requestData['rqi_status']=0;
        }
        $requestData['rqi_created_by']=1;
        $data_info=Modelgenrequestinformation::create($requestData);
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
    $id=$request->get("rqi_id");
    Modelgenrequestinformation::destroy($id);
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
    Route::resource('request_information', 'GenrequestinformationController');
    Route::post('request_information/listgrid', 'Api\GenrequestinformationController@listgrid');
    Route::post('request_information/insertgrid', 'Api\GenrequestinformationController@insertgrid');
    Route::post('request_information/updategrid', 'Api\GenrequestinformationController@updategrid');
    Route::post('request_information/deletegrid', 'Api\GenrequestinformationController@deletegrid');
}
}