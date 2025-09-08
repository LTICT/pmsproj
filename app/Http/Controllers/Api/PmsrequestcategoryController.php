<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsrequestcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsrequestcategoryController extends MyController
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
        $query='SELECT rqc_id,rqc_name_or,rqc_name_am,rqc_name_en,rqc_description,rqc_create_time,rqc_update_time,rqc_delete_time,rqc_created_by,rqc_status FROM pms_request_category ';
        $query .=' WHERE rqc_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_request_category_data']=$data_info[0];
        }
        //$data_info = Modelpmsrequestcategory::findOrFail($id);
        //$data['pms_request_category_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_request_category");
        return view('request_category.show_pms_request_category', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $canListData=$this->getSinglePagePermission($request,56,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,56);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT rqc_gov_active,rqc_cso_active,rqc_id,rqc_name_or,rqc_name_am,rqc_name_en,rqc_description,
     rqc_create_time,rqc_update_time,rqc_delete_time,rqc_created_by,rqc_status,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_request_category ";

     $query .=' WHERE 1=1';
     $isGovActive=$request->input('gov_active');
if(isset($isGovActive) && isset($isGovActive)){
$query .=" AND rqc_gov_active='".$isGovActive."'"; 
}

     $rqcid=$request->input('rqc_id');
if(isset($rqcid) && isset($rqcid)){
$query .=' AND rqc_id="'.$rqcid.'"';
}
$rqcnameor=$request->input('rqc_name_or');
if(isset($rqcnameor) && isset($rqcnameor)){
$query .=' AND rqc_name_or="'.$rqcnameor.'"';
}
$rqcnameam=$request->input('rqc_name_am');
if(isset($rqcnameam) && isset($rqcnameam)){
$query .=' AND rqc_name_am="'.$rqcnameam.'"';
}
$rqcnameen=$request->input('rqc_name_en');
if(isset($rqcnameen) && isset($rqcnameen)){
$query .=' AND rqc_name_en="'.$rqcnameen.'"';
}
$rqcdescription=$request->input('rqc_description');
if(isset($rqcdescription) && isset($rqcdescription)){
$query .=' AND rqc_description="'.$rqcdescription.'"';
}
$rqccreatetime=$request->input('rqc_create_time');
if(isset($rqccreatetime) && isset($rqccreatetime)){
$query .=' AND rqc_create_time="'.$rqccreatetime.'"';
}
$rqcupdatetime=$request->input('rqc_update_time');
if(isset($rqcupdatetime) && isset($rqcupdatetime)){
$query .=' AND rqc_update_time="'.$rqcupdatetime.'"';
}
$rqcdeletetime=$request->input('rqc_delete_time');
if(isset($rqcdeletetime) && isset($rqcdeletetime)){
$query .=' AND rqc_delete_time="'.$rqcdeletetime.'"';
}
$rqccreatedby=$request->input('rqc_created_by');
if(isset($rqccreatedby) && isset($rqccreatedby)){
$query .=' AND rqc_created_by="'.$rqccreatedby.'"';
}
$rqcstatus=$request->input('rqc_status');
if(isset($rqcstatus) && isset($rqcstatus)){
$query .=' AND rqc_status="'.$rqcstatus.'"';
}
$rqcstatus=$request->input('rqc_gov_active');
if(isset($rqcstatus) && isset($rqcstatus)){
$query .=" AND rqc_gov_active='".$rqcstatus."'";
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
$id=$request->get("rqc_id");
    $canEditData=$this->getSinglePagePermission($request,56,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
$attributeNames = [
        'rqc_name_or'=> trans('form_lang.rqc_name_or'),
'rqc_name_am'=> trans('form_lang.rqc_name_am'),
'rqc_name_en'=> trans('form_lang.rqc_name_en'),
'rqc_description'=> trans('form_lang.rqc_description'),
'rqc_status'=> trans('form_lang.rqc_status'),

    ];
    $rules= [
        'rqc_name_or'=> 'max:200',
'rqc_name_am'=> 'max:60',
'rqc_name_en'=> 'max:60',
'rqc_description'=> 'max:425',
//'rqc_status'=> 'integer',

    ];
     $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        $requestData = $request->all();
        $status= $request->input('rqc_status');
        if($status=="true"){
            $requestData['rqc_status']=1;
        }else{
            $requestData['rqc_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsrequestcategory::find($id);
            if(!isset($data_info) || empty($data_info)){
             return $this->handleUpdateDataException();
            }
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
        $data_info=Modelpmsrequestcategory::create($requestData);
        $resultObject= array(
            "odata.metadata"=>"",
            "value" =>$data_info,
            "statusCode"=>200,
            "type"=>"save",
            "errorMsg"=>""
        );
        return response()->json($resultObject);
       }       
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
//Insert Data
public function insertgrid(Request $request)
{
    $canAddData=$this->getSinglePagePermission($request,56,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
    $attributeNames = [
        'rqc_name_or'=> trans('form_lang.rqc_name_or'),
'rqc_name_am'=> trans('form_lang.rqc_name_am'),
'rqc_name_en'=> trans('form_lang.rqc_name_en'),
'rqc_description'=> trans('form_lang.rqc_description'),
'rqc_status'=> trans('form_lang.rqc_status'),

    ];
    $rules= [
        'rqc_name_or'=> 'max:200',
'rqc_name_am'=> 'max:60',
'rqc_name_en'=> 'max:60',
'rqc_description'=> 'max:425',
//'rqc_status'=> 'integer',

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        $requestData['rqc_created_by']=auth()->user()->usr_Id;
        $status= $request->input('rqc_status');
        if($status=="true"){
            $requestData['rqc_status']=1;
        }else{
            $requestData['rqc_status']=0;
        }
        $requestData['rqc_created_by']=1;
        $data_info=Modelpmsrequestcategory::create($requestData);
        $data_info['is_editable'] = 1;
    $data_info['is_deletable'] = 1;    
    return response()->json([
        "data" => $data_info,
        "previledge" => [
            'is_role_editable' => 1,
            'is_role_deletable' => 1
        ],
        "status_code" => 200,
        "type" => "save",
        "errorMsg" => ""
    ]);
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"save");
}
}
//Delete Data
public function deletegrid(Request $request)
{
    $id=$request->get("rqc_id");
    Modelpmsrequestcategory::destroy($id);
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
    Route::resource('request_category', 'PmsrequestcategoryController');
    Route::post('request_category/listgrid', 'Api\PmsrequestcategoryController@listgrid');
    Route::post('request_category/insertgrid', 'Api\PmsrequestcategoryController@insertgrid');
    Route::post('request_category/updategrid', 'Api\PmsrequestcategoryController@updategrid');
    Route::post('request_category/deletegrid', 'Api\PmsrequestcategoryController@deletegrid');
}
}