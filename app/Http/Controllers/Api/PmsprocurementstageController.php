<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprocurementstage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprocurementstageController extends MyController
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
        $query='SELECT pst_id,pst_name_or,pst_name_en,pst_name_am,pst_description,pst_create_time,pst_update_time,pst_delete_time,pst_created_by,pst_status FROM pms_procurement_stage ';

        $query .=' WHERE pst_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_procurement_stage_data']=$data_info[0];
        }
        //$data_info = Modelpmsprocurementstage::findOrFail($id);
        //$data['pms_procurement_stage_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_procurement_stage");
        return view('procurement_stage.show_pms_procurement_stage', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,5);
      if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT pst_id,pst_name_or,pst_name_en,pst_name_am,pst_description,pst_create_time,pst_update_time,pst_delete_time,pst_created_by,pst_status ".$permissionIndex." FROM pms_procurement_stage ";

     $query .=' WHERE 1=1';
     $pstid=$request->input('pst_id');
if(isset($pstid) && isset($pstid)){
$query .=' AND pst_id="'.$pstid.'"';
}
$pstnameor=$request->input('pst_name_or');
if(isset($pstnameor) && isset($pstnameor)){
$query .= " AND pst_name_or LIKE '%" . addslashes($pstnameor) . "%'";
}
$pstnameen=$request->input('pst_name_en');
if(isset($pstnameen) && isset($pstnameen)){
$query .= " AND pst_name_en LIKE '%" . addslashes($pstnameen) . "%'";
}
$pstnameam=$request->input('pst_name_am');
if(isset($pstnameam) && isset($pstnameam)){
$query .= " AND pst_name_am LIKE '%" . addslashes($pstnameam) . "%'";

}
$pstdescription=$request->input('pst_description');
if(isset($pstdescription) && isset($pstdescription)){
$query .=' AND pst_description="'.$pstdescription.'"';
}
$pstcreatetime=$request->input('pst_create_time');
if(isset($pstcreatetime) && isset($pstcreatetime)){
$query .=' AND pst_create_time="'.$pstcreatetime.'"';
}
$pstupdatetime=$request->input('pst_update_time');
if(isset($pstupdatetime) && isset($pstupdatetime)){
$query .=' AND pst_update_time="'.$pstupdatetime.'"';
}
$pstdeletetime=$request->input('pst_delete_time');
if(isset($pstdeletetime) && isset($pstdeletetime)){
$query .=' AND pst_delete_time="'.$pstdeletetime.'"';
}
$pstcreatedby=$request->input('pst_created_by');
if(isset($pstcreatedby) && isset($pstcreatedby)){
$query .=' AND pst_created_by="'.$pstcreatedby.'"';
}
$pststatus=$request->input('pst_status');
if(isset($pststatus) && isset($pststatus)){
$query .=' AND pst_status="'.$pststatus.'"';
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
        'pst_name_or'=> trans('form_lang.pst_name_or'),
'pst_name_en'=> trans('form_lang.pst_name_en'),
'pst_name_am'=> trans('form_lang.pst_name_am'),
'pst_description'=> trans('form_lang.pst_description'),
'pst_status'=> trans('form_lang.pst_status'),

    ];
    $rules= [
        'pst_name_or'=> 'max:50',
'pst_name_en'=> 'max:50',
'pst_name_am'=> 'max:50',
'pst_description'=> 'max:425',
//'pst_status'=> 'integer',

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
        $id=$request->get("pst_id");
        $requestData = $request->all();
        $status= $request->input('pst_status');
        if($status=="true"){
            $requestData['pst_status']=1;
        }else{
            $requestData['pst_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprocurementstage::findOrFail($id);
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
        $data_info=Modelpmsprocurementstage::create($requestData);
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
        'pst_name_or'=> trans('form_lang.pst_name_or'),
'pst_name_en'=> trans('form_lang.pst_name_en'),
'pst_name_am'=> trans('form_lang.pst_name_am'),
'pst_description'=> trans('form_lang.pst_description'),
'pst_status'=> trans('form_lang.pst_status'),

    ];
    $rules= [
        'pst_name_or'=> 'max:50',
'pst_name_en'=> 'max:50',
'pst_name_am'=> 'max:50',
'pst_description'=> 'max:425',

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
        $requestData['pst_created_by']=auth()->user()->usr_id;
        $status= $request->input('pst_status');
        if($status=="true"){
            $requestData['pst_status']=1;
        }else{
            $requestData['pst_status']=0;
        }
        //$requestData['pst_created_by']=1;
        $data_info=Modelpmsprocurementstage::create($requestData);
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
    $id=$request->get("pst_id");
    Modelpmsprocurementstage::destroy($id);
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
    Route::resource('procurement_stage', 'PmsprocurementstageController');
    Route::post('procurement_stage/listgrid', 'Api\PmsprocurementstageController@listgrid');
    Route::post('procurement_stage/insertgrid', 'Api\PmsprocurementstageController@insertgrid');
    Route::post('procurement_stage/updategrid', 'Api\PmsprocurementstageController@updategrid');
    Route::post('procurement_stage/deletegrid', 'Api\PmsprocurementstageController@deletegrid');
}
}