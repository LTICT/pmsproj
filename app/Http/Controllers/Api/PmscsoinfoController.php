<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmscsoinfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmscsoinfoController extends MyController
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
        $query='SELECT cso_id,cso_name,cso_code,cso_address,cso_phone,cso_email,cso_website,cso_description,cso_create_time,cso_update_time,cso_delete_time,cso_created_by,cso_status FROM pms_cso_info ';

        $query .=' WHERE cso_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_cso_info_data']=$data_info[0];
        }
        //$data_info = Modelpmscsoinfo::findOrFail($id);
        //$data['pms_cso_info_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_cso_info");
        return view('cso_info.show_pms_cso_info', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $query="SELECT cso_contact_person,cso_id,cso_name,cso_code,cso_address,cso_phone,cso_email,cso_website,cso_description,cso_create_time,cso_update_time,cso_delete_time,cso_created_by,cso_status,1 AS is_editable, 1 AS is_deletable FROM pms_cso_info ";
     $query .=' WHERE 1=1';
     $csoid=$request->input('cso_id');
if(isset($csoid) && isset($csoid)){
$query .=" AND cso_id='".$csoid."'";
}
$csoname=$request->input('cso_name');
if(isset($csoname) && isset($csoname)){
$query .=' AND cso_name="'.$csoname.'"';
}
$csocode=$request->input('cso_code');
if(isset($csocode) && isset($csocode)){
$query .=' AND cso_code="'.$csocode.'"';
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
        'cso_name'=> trans('form_lang.cso_name'),
'cso_code'=> trans('form_lang.cso_code'),
'cso_address'=> trans('form_lang.cso_address'),
'cso_phone'=> trans('form_lang.cso_phone'),
'cso_email'=> trans('form_lang.cso_email'),
'cso_website'=> trans('form_lang.cso_website'),
'cso_description'=> trans('form_lang.cso_description'),

    ];
    $rules= [
        'cso_name'=> 'max:200',
'cso_code'=> 'max:200',
'cso_address'=> 'max:250',
'cso_phone'=> 'max:45',
'cso_email'=> 'max:100',
'cso_website'=> 'max:100',
'cso_description'=> 'max:425',

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
        $id=$request->get("cso_id");
        $requestData = $request->all();

        if(isset($id) && !empty($id)){
            $data_info = Modelpmscsoinfo::findOrFail($id);
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
        $data_info=Modelpmscsoinfo::create($requestData);
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
        'cso_name'=> trans('form_lang.cso_name'),
'cso_code'=> trans('form_lang.cso_code'),
'cso_address'=> trans('form_lang.cso_address'),
'cso_phone'=> trans('form_lang.cso_phone'),
'cso_email'=> trans('form_lang.cso_email'),
'cso_website'=> trans('form_lang.cso_website'),
'cso_description'=> trans('form_lang.cso_description'),

    ];
    $rules= [
        'cso_name'=> 'max:200',
'cso_code'=> 'max:200',
'cso_address'=> 'max:250',
'cso_phone'=> 'max:45',
'cso_email'=> 'max:100',
'cso_website'=> 'max:100',
'cso_description'=> 'max:425',

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
        //$requestData['cso_created_by']=auth()->user()->usr_Id;
        $status= $request->input('cso_status');
        if($status=="true"){
            $requestData['cso_status']=1;
        }else{
            $requestData['cso_status']=0;
        }
        $requestData['cso_created_by']=1;
        $data_info=Modelpmscsoinfo::create($requestData);
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
    $id=$request->get("cso_id");
    Modelpmscsoinfo::destroy($id);
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
    Route::resource('cso_info', 'PmscsoinfoController');
    Route::post('cso_info/listgrid', 'Api\PmscsoinfoController@listgrid');
    Route::post('cso_info/insertgrid', 'Api\PmscsoinfoController@insertgrid');
    Route::post('cso_info/updategrid', 'Api\PmscsoinfoController@updategrid');
    Route::post('cso_info/deletegrid', 'Api\PmscsoinfoController@deletegrid');
}
}