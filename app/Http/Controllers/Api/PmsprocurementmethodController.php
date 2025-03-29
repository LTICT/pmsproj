<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprocurementmethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprocurementmethodController extends MyController
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
        $query='SELECT prm_id,prm_name_or,prm_name_en,prm_name_am,prm_description,prm_create_time,prm_update_time,prm_delete_time,prm_created_by,prm_status FROM pms_procurement_method ';

        $query .=' WHERE prm_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_procurement_method_data']=$data_info[0];
        }
        //$data_info = Modelpmsprocurementmethod::findOrFail($id);
        //$data['pms_procurement_method_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_procurement_method");
        return view('procurement_method.show_pms_procurement_method', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,8);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT prm_id,prm_name_or,prm_name_en,prm_name_am,prm_description,prm_create_time,prm_update_time,prm_delete_time,prm_created_by,prm_status ".$permissionIndex." FROM pms_procurement_method ";

     $query .=' WHERE 1=1';
     $prmid=$request->input('prm_id');
if(isset($prmid) && isset($prmid)){
$query .=' AND prm_id="'.$prmid.'"';
}
$prmnameor=$request->input('prm_name_or');
if(isset($prmnameor) && isset($prmnameor)){
$query .= " AND prm_name_or LIKE '%" . addslashes($prmnameor) . "%'";
}
$prmnameen=$request->input('prm_name_en');
if(isset($prmnameen) && isset($prmnameen)){
$query .= " AND prm_name_en LIKE '%" . addslashes($prmnameen) . "%'";

}
$prmnameam=$request->input('prm_name_am');
if(isset($prmnameam) && isset($prmnameam)){
$query .= " AND prm_name_am LIKE '%" . addslashes($prmnameam) . "%'";

}
$prmdescription=$request->input('prm_description');
if(isset($prmdescription) && isset($prmdescription)){
$query .=' AND prm_description="'.$prmdescription.'"';
}
$prmcreatetime=$request->input('prm_create_time');
if(isset($prmcreatetime) && isset($prmcreatetime)){
$query .=' AND prm_create_time="'.$prmcreatetime.'"';
}
$prmupdatetime=$request->input('prm_update_time');
if(isset($prmupdatetime) && isset($prmupdatetime)){
$query .=' AND prm_update_time="'.$prmupdatetime.'"';
}
$prmdeletetime=$request->input('prm_delete_time');
if(isset($prmdeletetime) && isset($prmdeletetime)){
$query .=' AND prm_delete_time="'.$prmdeletetime.'"';
}
$prmcreatedby=$request->input('prm_created_by');
if(isset($prmcreatedby) && isset($prmcreatedby)){
$query .=' AND prm_created_by="'.$prmcreatedby.'"';
}
$prmstatus=$request->input('prm_status');
if(isset($prmstatus) && isset($prmstatus)){
$query .=' AND prm_status="'.$prmstatus.'"';
}

//$query.=' ORDER BY emp_first_name';
$data_info=DB::select($query);
$resultObject = [
    "data" => $data_info,
    "previledge" => [
        'is_role_editable' => isset($permissionData) ? $permissionData->pem_edit : 0,
        'is_role_deletable' => isset($permissionData) ? $permissionData->pem_delete : 0,
        'is_role_can_add' => isset($permissionData) ? $permissionData->pem_insert : 0,
    ],
];
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'prm_name_or'=> trans('form_lang.prm_name_or'),
'prm_name_en'=> trans('form_lang.prm_name_en'),
'prm_name_am'=> trans('form_lang.prm_name_am'),
'prm_description'=> trans('form_lang.prm_description'),
'prm_status'=> trans('form_lang.prm_status'),

    ];
    $rules= [
        'prm_name_or'=> 'max:50',
'prm_name_en'=> 'max:200',
'prm_name_am'=> 'max:50',
'prm_description'=> 'max:425',
//'prm_status'=> 'integer',

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
        $id=$request->get("prm_id");
        $requestData = $request->all();
        $status= $request->input('prm_status');
        if($status=="true"){
            $requestData['prm_status']=1;
        }else{
            $requestData['prm_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprocurementmethod::findOrFail($id);
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
        $data_info=Modelpmsprocurementmethod::create($requestData);
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
        'prm_name_or'=> trans('form_lang.prm_name_or'),
'prm_name_en'=> trans('form_lang.prm_name_en'),
'prm_name_am'=> trans('form_lang.prm_name_am'),
'prm_description'=> trans('form_lang.prm_description'),
'prm_status'=> trans('form_lang.prm_status'),

    ];
    $rules= [
        'prm_name_or'=> 'max:50',
'prm_name_en'=> 'max:200',
'prm_name_am'=> 'max:50',
'prm_description'=> 'max:425',
//'prm_status'=> 'integer',

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
        $requestData['prm_created_by']=auth()->user()->usr_id;
        $status= $request->input('prm_status');
        if($status=="true"){
            $requestData['prm_status']=1;
        }else{
            $requestData['prm_status']=0;
        }
        //$requestData['prm_created_by']=1;
        $data_info=Modelpmsprocurementmethod::create($requestData);
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
    $id=$request->get("prm_id");
    Modelpmsprocurementmethod::destroy($id);
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
    Route::resource('procurement_method', 'PmsprocurementmethodController');
    Route::post('procurement_method/listgrid', 'Api\PmsprocurementmethodController@listgrid');
    Route::post('procurement_method/insertgrid', 'Api\PmsprocurementmethodController@insertgrid');
    Route::post('procurement_method/updategrid', 'Api\PmsprocurementmethodController@updategrid');
    Route::post('procurement_method/deletegrid', 'Api\PmsprocurementmethodController@deletegrid');
}
}