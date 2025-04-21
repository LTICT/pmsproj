<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsmonitoringevaluationtype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsmonitoringevaluationtypeController extends MyController
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
        $query='SELECT met_id,met_name_or,met_name_am,met_name_en,met_code,met_description,met_create_time,met_update_time,met_delete_time,met_created_by,met_status,met_gov_active,met_cso_active,met_monitoring_active,met_evaluation_active FROM pms_monitoring_evaluation_type ';       
        
        $query .=' WHERE met_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_monitoring_evaluation_type_data']=$data_info[0];
        }
        //$data_info = Modelpmsmonitoringevaluationtype::findOrFail($id);
        //$data['pms_monitoring_evaluation_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_monitoring_evaluation_type");
        return view('monitoring_evaluation_type.show_pms_monitoring_evaluation_type', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT met_id,met_name_or,met_name_am,met_name_en,met_code,met_description,met_create_time,met_update_time,met_delete_time,met_created_by,met_status,met_gov_active,met_cso_active,met_monitoring_active,met_evaluation_active,1 AS is_editable, 1 AS is_deletable ".$permissionIndex." FROM pms_monitoring_evaluation_type ";
     
     $query .=' WHERE 1=1';
     $metid=$request->input('met_id');
if(isset($metid) && isset($metid)){
$query .=' AND met_id="'.$metid.'"'; 
}
$metnameor=$request->input('met_name_or');
if(isset($metnameor) && isset($metnameor)){
$query .=' AND met_name_or="'.$metnameor.'"'; 
}
$metnameam=$request->input('met_name_am');
if(isset($metnameam) && isset($metnameam)){
$query .=' AND met_name_am="'.$metnameam.'"'; 
}
$metnameen=$request->input('met_name_en');
if(isset($metnameen) && isset($metnameen)){
$query .=' AND met_name_en="'.$metnameen.'"'; 
}
$metcode=$request->input('met_code');
if(isset($metcode) && isset($metcode)){
$query .=' AND met_code="'.$metcode.'"'; 
}
$metdescription=$request->input('met_description');
if(isset($metdescription) && isset($metdescription)){
$query .=' AND met_description="'.$metdescription.'"'; 
}
$metcreatetime=$request->input('met_create_time');
if(isset($metcreatetime) && isset($metcreatetime)){
$query .=' AND met_create_time="'.$metcreatetime.'"'; 
}
$metupdatetime=$request->input('met_update_time');
if(isset($metupdatetime) && isset($metupdatetime)){
$query .=' AND met_update_time="'.$metupdatetime.'"'; 
}
$metdeletetime=$request->input('met_delete_time');
if(isset($metdeletetime) && isset($metdeletetime)){
$query .=' AND met_delete_time="'.$metdeletetime.'"'; 
}
$metcreatedby=$request->input('met_created_by');
if(isset($metcreatedby) && isset($metcreatedby)){
$query .=' AND met_created_by="'.$metcreatedby.'"'; 
}
$metstatus=$request->input('met_status');
if(isset($metstatus) && isset($metstatus)){
$query .=' AND met_status="'.$metstatus.'"'; 
}
$metgovactive=$request->input('met_gov_active');
if(isset($metgovactive) && isset($metgovactive)){
$query .=' AND met_gov_active="'.$metgovactive.'"'; 
}
$metcsoactive=$request->input('met_cso_active');
if(isset($metcsoactive) && isset($metcsoactive)){
$query .=' AND met_cso_active="'.$metcsoactive.'"'; 
}
$metmonitoringactive=$request->input('met_monitoring_active');
if(isset($metmonitoringactive) && isset($metmonitoringactive)){
$query .=' AND met_monitoring_active="'.$metmonitoringactive.'"'; 
}
$metevaluationactive=$request->input('met_evaluation_active');
if(isset($metevaluationactive) && isset($metevaluationactive)){
$query .=' AND met_evaluation_active="'.$metevaluationactive.'"'; 
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
        'met_name_or'=> trans('form_lang.met_name_or'), 
'met_name_am'=> trans('form_lang.met_name_am'), 
'met_name_en'=> trans('form_lang.met_name_en'), 
'met_code'=> trans('form_lang.met_code'), 
'met_description'=> trans('form_lang.met_description'), 
'met_status'=> trans('form_lang.met_status'), 
'met_gov_active'=> trans('form_lang.met_gov_active'), 
'met_cso_active'=> trans('form_lang.met_cso_active'), 
'met_monitoring_active'=> trans('form_lang.met_monitoring_active'), 
'met_evaluation_active'=> trans('form_lang.met_evaluation_active'), 

    ];
    $rules= [
        'met_name_or'=> 'max:200', 
'met_name_am'=> 'max:60', 
'met_name_en'=> 'max:60', 
'met_code'=> 'max:200', 
'met_description'=> 'max:425', 
'met_status'=> 'integer', 
'met_gov_active'=> 'integer', 
'met_cso_active'=> 'integer', 
'met_monitoring_active'=> 'integer', 
'met_evaluation_active'=> 'integer', 

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
        $id=$request->get("met_id");
        $requestData = $request->all();
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsmonitoringevaluationtype::findOrFail($id);
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
        $data_info=Modelpmsmonitoringevaluationtype::create($requestData);
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
        'met_name_or'=> trans('form_lang.met_name_or'), 
'met_name_am'=> trans('form_lang.met_name_am'), 
'met_name_en'=> trans('form_lang.met_name_en'), 
'met_code'=> trans('form_lang.met_code'), 
'met_description'=> trans('form_lang.met_description'), 
'met_status'=> trans('form_lang.met_status'), 
'met_gov_active'=> trans('form_lang.met_gov_active'), 
'met_cso_active'=> trans('form_lang.met_cso_active'), 
'met_monitoring_active'=> trans('form_lang.met_monitoring_active'), 
'met_evaluation_active'=> trans('form_lang.met_evaluation_active'), 

    ];
    $rules= [
        'met_name_or'=> 'max:200', 
'met_name_am'=> 'max:60', 
'met_name_en'=> 'max:60', 
'met_code'=> 'max:200', 
'met_description'=> 'max:425', 
'met_status'=> 'integer', 
'met_gov_active'=> 'integer', 
'met_cso_active'=> 'integer', 
'met_monitoring_active'=> 'integer', 
'met_evaluation_active'=> 'integer', 

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
        //$requestData['met_created_by']=auth()->user()->usr_Id;
        $requestData['met_created_by']=1;
        $data_info=Modelpmsmonitoringevaluationtype::create($requestData);
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
    $id=$request->get("met_id");
    Modelpmsmonitoringevaluationtype::destroy($id);
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
    Route::resource('monitoring_evaluation_type', 'PmsmonitoringevaluationtypeController');
    Route::post('monitoring_evaluation_type/listgrid', 'Api\PmsmonitoringevaluationtypeController@listgrid');
    Route::post('monitoring_evaluation_type/insertgrid', 'Api\PmsmonitoringevaluationtypeController@insertgrid');
    Route::post('monitoring_evaluation_type/updategrid', 'Api\PmsmonitoringevaluationtypeController@updategrid');
    Route::post('monitoring_evaluation_type/deletegrid', 'Api\PmsmonitoringevaluationtypeController@deletegrid');
}
}