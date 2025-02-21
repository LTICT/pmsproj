<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblusersector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblusersectorController extends MyController
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
        $query='SELECT usc_id,usc_sector_id,usc_user_id,usc_description,usc_create_time,usc_update_time,usc_delete_time,usc_created_by,usc_status FROM tbl_user_sector ';       
        
        $query .=' WHERE usc_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_user_sector_data']=$data_info[0];
        }
        //$data_info = Modeltblusersector::findOrFail($id);
        //$data['tbl_user_sector_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_user_sector");
        return view('user_sector.show_tbl_user_sector', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT usc_id,usc_sector_id,usc_user_id,usc_description,usc_create_time,usc_update_time,usc_delete_time,usc_created_by,usc_status,1 AS is_editable, 1 AS is_deletable FROM tbl_user_sector ";
     
     $query .=' WHERE 1=1';
     $uscid=$request->input('usc_id');
if(isset($uscid) && isset($uscid)){
$query .=' AND usc_id="'.$uscid.'"'; 
}
$uscsectorid=$request->input('usc_sector_id');
if(isset($uscsectorid) && isset($uscsectorid)){
$query .=' AND usc_sector_id="'.$uscsectorid.'"'; 
}
$uscuserid=$request->input('usc_user_id');
if(isset($uscuserid) && isset($uscuserid)){
$query .=" AND usc_user_id='".$uscuserid."'"; 
}
$uscstatus=$request->input('usc_status');
if(isset($uscstatus) && isset($uscstatus)){
$query .=' AND usc_status="'.$uscstatus.'"'; 
}
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
        'usc_sector_id'=> trans('form_lang.usc_sector_id'), 
'usc_user_id'=> trans('form_lang.usc_user_id'), 
'usc_description'=> trans('form_lang.usc_description'), 
'usc_status'=> trans('form_lang.usc_status'), 

    ];
    $rules= [
        'usc_sector_id'=> 'max:200', 
'usc_user_id'=> 'max:200', 
'usc_description'=> 'max:425', 
//'usc_status'=> 'integer', 

    ];
    $data = $request->all();
    //dump(count($data));
    $userId=0;
    $data_info=array();
    foreach ($data as $key => $value) {
        $value['usc_created_by']=1;
        $userSectorId=$value['usc_id'];
        $userId=$value['usc_user_id'];
       if(isset($userSectorId) && !empty($userSectorId) && $userSectorId > 0){
            //update
            //$data_info=Modeltblusersector::create($value);
             $data_info = Modeltblusersector::findOrFail($userSectorId);
            $data_info->update($value);
        }else{
            unset($value['usc_id']);
            $data_info=Modeltblusersector::create($value);
        }

        //END RETURN
    }
$resultObject= array(
                "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Insert Data
public function insertgrid(Request $request)
{
    $attributeNames = [
        'usc_sector_id'=> trans('form_lang.usc_sector_id'), 
'usc_user_id'=> trans('form_lang.usc_user_id'), 
'usc_description'=> trans('form_lang.usc_description'), 
'usc_status'=> trans('form_lang.usc_status'), 

    ];
    $rules= [
        'usc_sector_id'=> 'max:200', 
'usc_user_id'=> 'max:200', 
'usc_description'=> 'max:425', 
'usc_status'=> 'integer', 

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
        //$requestData['usc_created_by']=auth()->user()->usr_Id;
        $status= $request->input('usc_status');
        if($status=="true"){
            $requestData['usc_status']=1;
        }else{
            $requestData['usc_status']=0;
        }
        $requestData['usc_created_by']=1;
        $data_info=Modeltblusersector::create($requestData);
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
    $id=$request->get("usc_id");
    Modeltblusersector::destroy($id);
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
    Route::resource('user_sector', 'TblusersectorController');
    Route::post('user_sector/listgrid', 'Api\TblusersectorController@listgrid');
    Route::post('user_sector/insertgrid', 'Api\TblusersectorController@insertgrid');
    Route::post('user_sector/updategrid', 'Api\TblusersectorController@updategrid');
    Route::post('user_sector/deletegrid', 'Api\TblusersectorController@deletegrid');
}
}