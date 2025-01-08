<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetrequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetrequestController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
}
 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
 public function index(Request $request)
 {
    $selectedLanguage=app()->getLocale();
    if($selectedLanguage=="or"){
        $filepath = base_path() .'\resources\lang\or\ag_grid.php';
    }else if($selectedLanguage=="en"){
        $filepath = base_path() .'\resources\lang\en\ag_grid.php';
    }else if($selectedLanguage=="am"){
        $filepath = base_path() .'\resources\lang\am\ag_grid.php';
    }
    $filepath = base_path() .'\resources\lang\en\ag_grid.php';
    $txt = file_get_contents($filepath);
    $data['ag_grid_lang']=$txt;
    $searchParams= $this->getSearchSetting('pms_budget_request');
    $dataInfo = Modelpmsbudgetrequest::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_budget_request_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_budget_request");
    return view('budget_request.list_pms_budget_request', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsbudgetrequest::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsbudgetrequestController";
        $data= $this->validateEdit($data, $data_info['bdr_create_time'], $controllerName);
        $data['pms_budget_request_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_budget_request");
$form= view('budget_request.form_popup_pms_budget_request', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_budget_request'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('budget_request.editable_list_pms_budget_request', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_budget_request'));
    return response()->json($resultObject);
    //echo json_encode($resultObject, JSON_NUMERIC_CHECK);
}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        
        $data['page_title']=trans("form_lang.pms_budget_request");
        $data['action_mode']="create";
        return view('budget_request.form_pms_budget_request', $data);
    }
    /**`
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
       $attributeNames = [
        'bdr_budget_year_id'=> trans('form_lang.bdr_budget_year_id'), 
'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 
'bdr_status'=> trans('form_lang.bdr_status'), 

    ];
    $rules= [
        'bdr_budget_year_id'=> 'max:200', 
'bdr_requested_amount'=> 'max:200', 
'bdr_released_amount'=> 'numeric', 
'bdr_project_id'=> 'max:200', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425', 
'bdr_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['bdr_created_by']=auth()->user()->usr_Id;
        Modelpmsbudgetrequest::create($requestData);
        return redirect('budget_request')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('budget_request/create')
        ->withErrors($validator)
        ->withInput();
    }
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
        $query='SELECT bdr_id,bdr_budget_year_id,bdr_requested_amount,bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,bdr_update_time,bdr_delete_time,bdr_created_by,bdr_status FROM pms_budget_request ';       
        
        $query .=' WHERE bdr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_request_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetrequest::findOrFail($id);
        //$data['pms_budget_request_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_request");
        return view('budget_request.show_pms_budget_request', $data);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        
        
        $data_info = Modelpmsbudgetrequest::find($id);
        $data['pms_budget_request_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_request");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('budget_request.form_pms_budget_request', $data);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
     $attributeNames = [
        'bdr_budget_year_id'=> trans('form_lang.bdr_budget_year_id'), 
'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 
'bdr_status'=> trans('form_lang.bdr_status'), 

    ];
    $rules= [
        'bdr_budget_year_id'=> 'max:200', 
'bdr_requested_amount'=> 'max:200', 
'bdr_released_amount'=> 'numeric', 
'bdr_project_id'=> 'max:200', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425', 
'bdr_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsbudgetrequest::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('budget_request')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('budget_request/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('budget_request/'.$id.'/edit')
    ->withErrors($validator)
    ->withInput();
}
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Modelpmsbudgetrequest::destroy($id);
        return redirect('budget_request')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
    $query='SELECT bdy_name,prj_name, prj_code, bdr_request_status, bdr_id,bdr_budget_year_id,bdr_requested_amount,
     bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,
     bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,bdr_update_time,
     bdr_delete_time,bdr_created_by,bdr_status,bdr_action_remark,1 AS is_editable, 1 AS is_deletable 
     FROM pms_budget_request 
     INNER JOIN pms_project ON pms_project.prj_id=pms_budget_request.bdr_project_id
     INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_budget_request.bdr_budget_year_id'; 

     $query .=' WHERE 1=1';
    $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('budget_request_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND bdr_requested_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('budget_request_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND bdr_requested_date_gc <='".$endTime." 23 59 59'"; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
}

$bdrbudgetyearid=$request->input('bdr_budget_year_id');
if(isset($bdrbudgetyearid) && isset($bdrbudgetyearid)){
$query .=" AND bdr_budget_year_id='".$bdrbudgetyearid."'"; 
}
$bdrrequestedamount=$request->input('bdr_requested_amount');
if(isset($bdrrequestedamount) && isset($bdrrequestedamount)){
$query .=' AND bdr_requested_amount="'.$bdrrequestedamount.'"'; 
}
$bdrreleasedamount=$request->input('bdr_released_amount');
if(isset($bdrreleasedamount) && isset($bdrreleasedamount)){
$query .=' AND bdr_released_amount="'.$bdrreleasedamount.'"'; 
}
$bdrprojectid=$request->input('project_id');
if(isset($bdrprojectid) && isset($bdrprojectid)){
$query .= " AND bdr_project_id = '$bdrprojectid'";

}
$bdrrequesteddateec=$request->input('bdr_requested_date_ec');
if(isset($bdrrequesteddateec) && isset($bdrrequesteddateec)){
$query .=' AND bdr_requested_date_ec="'.$bdrrequesteddateec.'"'; 
}
$bdrrequesteddategc=$request->input('bdr_requested_date_gc');
if(isset($bdrrequesteddategc) && isset($bdrrequesteddategc)){
$query .=' AND bdr_requested_date_gc="'.$bdrrequesteddategc.'"'; 
}
$bdrreleaseddateec=$request->input('bdr_released_date_ec');
if(isset($bdrreleaseddateec) && isset($bdrreleaseddateec)){
$query .=' AND bdr_released_date_ec="'.$bdrreleaseddateec.'"'; 
}
$bdrreleaseddategc=$request->input('bdr_released_date_gc');
if(isset($bdrreleaseddategc) && isset($bdrreleaseddategc)){
$query .=' AND bdr_released_date_gc="'.$bdrreleaseddategc.'"'; 
}
$bdrdescription=$request->input('bdr_description');
if(isset($bdrdescription) && isset($bdrdescription)){
$query .=' AND bdr_description="'.$bdrdescription.'"'; 
}
$bdrcreatetime=$request->input('bdr_create_time');
if(isset($bdrcreatetime) && isset($bdrcreatetime)){
$query .=' AND bdr_create_time="'.$bdrcreatetime.'"'; 
}
$bdrupdatetime=$request->input('bdr_update_time');
if(isset($bdrupdatetime) && isset($bdrupdatetime)){
$query .=' AND bdr_update_time="'.$bdrupdatetime.'"'; 
}
$bdrdeletetime=$request->input('bdr_delete_time');
if(isset($bdrdeletetime) && isset($bdrdeletetime)){
$query .=' AND bdr_delete_time="'.$bdrdeletetime.'"'; 
}
$bdrcreatedby=$request->input('bdr_created_by');
if(isset($bdrcreatedby) && isset($bdrcreatedby)){
$query .=' AND bdr_created_by="'.$bdrcreatedby.'"'; 
}
$bdrstatus=$request->input('bdr_status');
if(isset($bdrstatus) && isset($bdrstatus)){
$query .=' AND bdr_status="'.$bdrstatus.'"'; 
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
//$query.=' ORDER BY emp_first_name, emp_middle_name, emp_last_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'bdr_budget_year_id'=> trans('form_lang.bdr_budget_year_id'), 
'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 
'bdr_status'=> trans('form_lang.bdr_status'), 

    ];
    $rules= [
    'bdr_budget_year_id'=> 'max:200', 
'bdr_requested_amount'=> 'max:200', 
//'bdr_released_amount'=> 'numeric', 
'bdr_project_id'=> 'max:200', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425', 
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
        $id=$request->get("bdr_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bdr_status');
        if($status=="true"){
            $requestData['bdr_status']=1;
        }else{
            $requestData['bdr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetrequest::findOrFail($id);
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
        //$requestData['bdr_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetrequest::create($requestData);
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
public function insertgrid(Request $request)
{
    $attributeNames = [
        'bdr_budget_year_id'=> trans('form_lang.bdr_budget_year_id'), 
'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 
'bdr_status'=> trans('form_lang.bdr_status'), 

    ];
    $rules= [
        'bdr_budget_year_id'=> 'max:200', 
'bdr_requested_amount'=> 'max:200', 
//'bdr_released_amount'=> 'numeric', 
'bdr_project_id'=> 'max:200', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425', 
//'bdr_status'=> 'integer', 

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
        //$requestData['bdr_created_by']=auth()->user()->usr_Id;
        $requestData['bdr_created_by']=1;
        $status= $request->input('bdr_status');
        if($status=="true"){
            $requestData['bdr_status']=1;
        }else{
            $requestData['bdr_status']=0;
        }
        $data_info=Modelpmsbudgetrequest::create($requestData);
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
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
public function deletegrid(Request $request)
{
    $id=$request->get("bdr_id");
    Modelpmsbudgetrequest::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}