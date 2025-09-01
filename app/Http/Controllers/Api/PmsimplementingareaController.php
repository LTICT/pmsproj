<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsimplementingarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsimplementingareaController extends MyController
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
        $query='SELECT pia_id,pia_project_id,pia_region_id,pia_zone_id_id,pia_woreda_id,pia_sector_id,pia_budget_amount,pia_site,pia_geo_location,pia_description,pia_create_time,pia_update_time,pia_delete_time,pia_created_by,pia_status FROM pms_implementing_area ';       
        
        $query .=' WHERE pia_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_implementing_area_data']=$data_info[0];
        }
        //$data_info = Modelpmsimplementingarea::findOrFail($id);
        //$data['pms_implementing_area_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_implementing_area");
        return view('implementing_area.show_pms_implementing_area', $data);
    }
    //Get List
    public function listgrid(Request $request){
    
     $query="SELECT pia_is_other_region,pia_id,pia_project_id,pia_region_id,pia_zone_id_id,pia_woreda_id,pia_sector_id,pia_budget_amount,pia_site,pia_geo_location,pia_description,pia_create_time,pia_update_time,pia_delete_time,pia_created_by,pia_status,1 AS is_editable, 1 AS is_deletable FROM pms_implementing_area ";
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_implementing_area.pia_project_id';
     $query .=' WHERE 1=1';
     $piaid=$request->input('pia_id');
if(isset($piaid) && isset($piaid)){
$query .=' AND pia_id="'.$piaid.'"'; 
}
$piaregionid=$request->input('pia_region_id');
if(isset($piaregionid) && isset($piaregionid)){
$query .=' AND pia_region_id="'.$piaregionid.'"'; 
}
$piazoneidid=$request->input('pia_zone_id_id');
if(isset($piazoneidid) && isset($piazoneidid)){
$query .=' AND pia_zone_id_id="'.$piazoneidid.'"'; 
}
$piaworedaid=$request->input('pia_woreda_id');
if(isset($piaworedaid) && isset($piaworedaid)){
$query .=' AND pia_woreda_id="'.$piaworedaid.'"'; 
}
$piasectorid=$request->input('pia_sector_id');
if(isset($piasectorid) && isset($piasectorid)){
$query .=' AND pia_sector_id="'.$piasectorid.'"'; 
}
$piabudgetamount=$request->input('pia_budget_amount');
if(isset($piabudgetamount) && isset($piabudgetamount)){
$query .=' AND pia_budget_amount="'.$piabudgetamount.'"'; 
}
$piasite=$request->input('pia_site');
if(isset($piasite) && isset($piasite)){
$query .=' AND pia_site="'.$piasite.'"'; 
}
//START
$piaprojectid=$request->input('pia_project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($piaprojectid) && isset($piaprojectid)){
$query .= " AND pia_project_id = '$piaprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
//$query.=' ORDER BY emp_first_name';
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
        'pia_project_id'=> trans('form_lang.pia_project_id'), 
'pia_region_id'=> trans('form_lang.pia_region_id'), 
'pia_zone_id_id'=> trans('form_lang.pia_zone_id_id'), 
'pia_woreda_id'=> trans('form_lang.pia_woreda_id'), 
'pia_sector_id'=> trans('form_lang.pia_sector_id'), 
'pia_budget_amount'=> trans('form_lang.pia_budget_amount'), 
'pia_site'=> trans('form_lang.pia_site'), 
'pia_geo_location'=> trans('form_lang.pia_geo_location'), 
'pia_description'=> trans('form_lang.pia_description'), 
'pia_status'=> trans('form_lang.pia_status'), 

    ];
    $rules= [
      'pia_region_id'=> 'max:200',
'pia_geo_location'=> 'max:425', 
'pia_description'=> 'max:425'
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
        $id=$request->get("pia_id");
        $requestData = $request->all();            
        $status= $request->input('pia_status');
        if($status=="true"){
            $requestData['pia_status']=1;
        }else{
            $requestData['pia_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsimplementingarea::findOrFail($id);
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
        $data_info=Modelpmsimplementingarea::create($requestData);
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
        'pia_project_id'=> trans('form_lang.pia_project_id'), 
'pia_region_id'=> trans('form_lang.pia_region_id'), 
'pia_zone_id_id'=> trans('form_lang.pia_zone_id_id'), 
'pia_woreda_id'=> trans('form_lang.pia_woreda_id'), 
'pia_sector_id'=> trans('form_lang.pia_sector_id'), 
'pia_budget_amount'=> trans('form_lang.pia_budget_amount'), 
'pia_site'=> trans('form_lang.pia_site'), 
'pia_geo_location'=> trans('form_lang.pia_geo_location'), 
'pia_description'=> trans('form_lang.pia_description'), 
'pia_status'=> trans('form_lang.pia_status'), 

    ];
    $rules= [
'pia_region_id'=> 'max:200',
'pia_geo_location'=> 'max:425', 
'pia_description'=> 'max:425'
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
        //$requestData['pia_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pia_status');
        if($status=="true"){
            $requestData['pia_status']=1;
        }else{
            $requestData['pia_status']=0;
        }
        $requestData['pia_created_by']=1;
        $data_info=Modelpmsimplementingarea::create($requestData);
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
    $id=$request->get("pia_id");
    Modelpmsimplementingarea::destroy($id);
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
    Route::resource('implementing_area', 'PmsimplementingareaController');
    Route::post('implementing_area/listgrid', 'Api\PmsimplementingareaController@listgrid');
    Route::post('implementing_area/insertgrid', 'Api\PmsimplementingareaController@insertgrid');
    Route::post('implementing_area/updategrid', 'Api\PmsimplementingareaController@updategrid');
    Route::post('implementing_area/deletegrid', 'Api\PmsimplementingareaController@deletegrid');
}
}