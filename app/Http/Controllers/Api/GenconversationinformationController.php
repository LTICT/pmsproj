<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenconversationinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GenconversationinformationController extends MyController
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
        $query='SELECT cvi_id,cvi_title,cvi_object_id,cvi_object_type_id,cvi_request_date_et,cvi_request_date_gc,cvi_description,cvi_create_time,cvi_update_time,cvi_delete_time,cvi_created_by,cvi_status FROM gen_conversation_information ';

        $query .=' WHERE cvi_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_conversation_information_data']=$data_info[0];
        }
        //$data_info = Modelgenconversationinformation::findOrFail($id);
        //$data['gen_conversation_information_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_conversation_information");
        return view('conversation_information.show_gen_conversation_information', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     /*$permissionData=$this->getPagePermission($request,57);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }*/
     $query="SELECT usr_full_name AS created_by,cvi_id,cvi_title,cvi_object_id,cvi_object_type_id,cvi_request_date_et,cvi_request_date_gc,cvi_description,cvi_create_time,cvi_update_time,cvi_delete_time,cvi_created_by,cvi_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM gen_conversation_information ";
     $query .=' INNER JOIN tbl_users ON tbl_users.usr_id=gen_conversation_information.cvi_created_by';     
     $query .=' WHERE 1=1';
     $cviid=$request->input('cvi_id');
if(isset($cviid) && isset($cviid)){
$query .=' AND cvi_id="'.$cviid.'"';
}
$cvititle=$request->input('cvi_title');
if(isset($cvititle) && isset($cvititle)){
$query .=' AND cvi_title="'.$cvititle.'"';
}
$cviobjectid=$request->input('cvi_object_id');
if(isset($cviobjectid) && isset($cviobjectid)){
$query .=" AND cvi_object_id='".$cviobjectid."'";
}
$cviobjecttypeid=$request->input('cvi_object_type_id');
if(isset($cviobjecttypeid) && isset($cviobjecttypeid)){
$query .=" AND cvi_object_type_id='".$cviobjecttypeid."'";
}
$cvirequestdategc=$request->input('cvi_request_date_gc');
if(isset($cvirequestdategc) && isset($cvirequestdategc)){
$query .=' AND cvi_request_date_gc="'.$cvirequestdategc.'"';
}
$cvidescription=$request->input('cvi_description');
if(isset($cvidescription) && isset($cvidescription)){
$query .=' AND cvi_description="'.$cvidescription.'"';
}
$query.=' ORDER BY cvi_id DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'cvi_title'=> trans('form_lang.cvi_title'),
'cvi_object_id'=> trans('form_lang.cvi_object_id'),
'cvi_object_type_id'=> trans('form_lang.cvi_object_type_id'),
'cvi_request_date_et'=> trans('form_lang.cvi_request_date_et'),
'cvi_request_date_gc'=> trans('form_lang.cvi_request_date_gc'),
'cvi_description'=> trans('form_lang.cvi_description'),
'cvi_status'=> trans('form_lang.cvi_status'),

    ];
    $rules= [
        'cvi_title'=> 'max:425',
'cvi_object_type_id'=> 'max:200',
'cvi_request_date_et'=> 'max:200',
'cvi_request_date_gc'=> 'max:200',
'cvi_description'=> 'max:425',
//'cvi_status'=> 'integer',

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
        $id=$request->get("cvi_id");
        $requestData = $request->all();
        if(isset($id) && !empty($id)){
            $data_info = Modelgenconversationinformation::findOrFail($id);
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
        $data_info=Modelgenconversationinformation::create($requestData);
        $data_info['created_by']=$this->getUserInfo($request)?->usr_full_name;
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
        'cvi_title'=> trans('form_lang.cvi_title'),
'cvi_object_id'=> trans('form_lang.cvi_object_id'),
'cvi_object_type_id'=> trans('form_lang.cvi_object_type_id'),
'cvi_request_date_et'=> trans('form_lang.cvi_request_date_et'),
'cvi_request_date_gc'=> trans('form_lang.cvi_request_date_gc'),
'cvi_description'=> trans('form_lang.cvi_description'),
'cvi_status'=> trans('form_lang.cvi_status'),

    ];
    $rules= [
        'cvi_title'=> 'max:425',
'cvi_object_type_id'=> 'max:200',
'cvi_request_date_et'=> 'max:200',
'cvi_request_date_gc'=> 'max:200',
'cvi_description'=> 'max:425',
//'cvi_status'=> 'integer',

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
        //$requestData['cvi_created_by']=auth()->user()->usr_Id;
        $requestData['cvi_created_by']=1;
        $requestData['cvi_created_by']=auth()->user()->usr_id;
        $data_info=Modelgenconversationinformation::create($requestData);
        $data_info['created_by']=$this->getUserInfo($request)?->usr_full_name;
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
    $id=$request->get("cvi_id");
    Modelgenconversationinformation::destroy($id);
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
    Route::resource('conversation_information', 'GenconversationinformationController');
    Route::post('conversation_information/listgrid', 'Api\GenconversationinformationController@listgrid');
    Route::post('conversation_information/insertgrid', 'Api\GenconversationinformationController@insertgrid');
    Route::post('conversation_information/updategrid', 'Api\GenconversationinformationController@updategrid');
    Route::post('conversation_information/deletegrid', 'Api\GenconversationinformationController@deletegrid');
}
}