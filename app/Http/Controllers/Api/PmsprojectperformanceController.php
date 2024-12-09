<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modelpmsprojectperformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectperformanceController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_performance');
    $dataInfo = Modelpmsprojectperformance::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_performance_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_performance");
    return view('project_performance.list_pms_project_performance', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectperformance::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectperformanceController";
        $data= $this->validateEdit($data, $data_info['prp_create_time'], $controllerName);
        $data['pms_project_performance_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_performance");
$form= view('project_performance.form_popup_pms_project_performance', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_performance'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_performance.editable_list_pms_project_performance', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_performance'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_performance");
        $data['action_mode']="create";
        return view('project_performance.form_pms_project_performance', $data);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_project_status_id'=> trans('form_lang.prp_project_status_id'), 
'prp_record_date_ec'=> trans('form_lang.prp_record_date_ec'), 
'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'), 
'prp_physical_performance'=> trans('form_lang.prp_physical_performance'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 
'prp_created_date'=> trans('form_lang.prp_created_date'), 
'prp_termination_reason_id'=> trans('form_lang.prp_termination_reason_id'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_project_status_id'=> 'max:200', 
'prp_record_date_ec'=> 'max:200', 
'prp_record_date_gc'=> 'max:200', 
'prp_total_budget_used'=> 'max:200', 
'prp_physical_performance'=> 'max:200', 
'prp_description'=> 'max:100', 
'prp_status'=> 'integer', 
'prp_created_date'=> 'integer', 
'prp_termination_reason_id'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prp_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectperformance::create($requestData);
        return redirect('project_performance')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_performance/create')
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
        $query='SELECT prp_id,prp_project_id,prp_project_status_id,prp_record_date_ec,prp_record_date_gc,prp_total_budget_used,prp_physical_performance,prp_description,prp_status,prp_created_by,prp_created_date,prp_create_time,prp_update_time,prp_termination_reason_id FROM pms_project_performance ';       
        
        $query .=' WHERE prp_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_performance_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectperformance::findOrFail($id);
        //$data['pms_project_performance_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_performance");
        return view('project_performance.show_pms_project_performance', $data);
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
        
        
        $data_info = Modelpmsprojectperformance::find($id);
        $data['pms_project_performance_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_performance");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_performance.form_pms_project_performance', $data);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_project_status_id'=> trans('form_lang.prp_project_status_id'), 
'prp_record_date_ec'=> trans('form_lang.prp_record_date_ec'), 
'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'), 
'prp_physical_performance'=> trans('form_lang.prp_physical_performance'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 
'prp_created_date'=> trans('form_lang.prp_created_date'), 
'prp_termination_reason_id'=> trans('form_lang.prp_termination_reason_id'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_project_status_id'=> 'max:200', 
'prp_record_date_ec'=> 'max:200', 
'prp_record_date_gc'=> 'max:200', 
'prp_total_budget_used'=> 'max:200', 
'prp_physical_performance'=> 'max:200', 
'prp_description'=> 'max:100', 
'prp_status'=> 'integer', 
'prp_created_date'=> 'integer', 
'prp_termination_reason_id'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectperformance::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_performance')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_performance/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_performance/'.$id.'/edit')
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
        Modelpmsprojectperformance::destroy($id);
        return redirect('project_performance')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT prp_id,prp_project_id,prp_project_status_id,prp_record_date_ec,prp_record_date_gc,prp_total_budget_used,prp_physical_performance,prp_description,prp_status,prp_created_by,prp_created_date,prp_create_time,prp_update_time,prp_termination_reason_id,1 AS is_editable, 1 AS is_deletable FROM pms_project_performance ';       
     
     $query .=' WHERE 1=1';
     $prpid=$request->input('prp_id');
if(isset($prpid) && isset($prpid)){
$query .=' AND prp_id="'.$prpid.'"'; 
}
$prpprojectid=$request->input('prp_project_id');
if(isset($prpprojectid) && isset($prpprojectid)){
$query .=' AND prp_project_id="'.$prpprojectid.'"'; 
}
$prpprojectstatusid=$request->input('prp_project_status_id');
if(isset($prpprojectstatusid) && isset($prpprojectstatusid)){
$query .=' AND prp_project_status_id="'.$prpprojectstatusid.'"'; 
}
$prprecorddateec=$request->input('prp_record_date_ec');
if(isset($prprecorddateec) && isset($prprecorddateec)){
$query .=' AND prp_record_date_ec="'.$prprecorddateec.'"'; 
}
$prprecorddategc=$request->input('prp_record_date_gc');
if(isset($prprecorddategc) && isset($prprecorddategc)){
$query .=' AND prp_record_date_gc="'.$prprecorddategc.'"'; 
}
$prptotalbudgetused=$request->input('prp_total_budget_used');
if(isset($prptotalbudgetused) && isset($prptotalbudgetused)){
$query .=' AND prp_total_budget_used="'.$prptotalbudgetused.'"'; 
}
$prpphysicalperformance=$request->input('prp_physical_performance');
if(isset($prpphysicalperformance) && isset($prpphysicalperformance)){
$query .=' AND prp_physical_performance="'.$prpphysicalperformance.'"'; 
}
$prpdescription=$request->input('prp_description');
if(isset($prpdescription) && isset($prpdescription)){
$query .=' AND prp_description="'.$prpdescription.'"'; 
}
$prpstatus=$request->input('prp_status');
if(isset($prpstatus) && isset($prpstatus)){
$query .=' AND prp_status="'.$prpstatus.'"'; 
}
$prpcreatedby=$request->input('prp_created_by');
if(isset($prpcreatedby) && isset($prpcreatedby)){
$query .=' AND prp_created_by="'.$prpcreatedby.'"'; 
}
$prpcreateddate=$request->input('prp_created_date');
if(isset($prpcreateddate) && isset($prpcreateddate)){
$query .=' AND prp_created_date="'.$prpcreateddate.'"'; 
}
$prpcreatetime=$request->input('prp_create_time');
if(isset($prpcreatetime) && isset($prpcreatetime)){
$query .=' AND prp_create_time="'.$prpcreatetime.'"'; 
}
$prpupdatetime=$request->input('prp_update_time');
if(isset($prpupdatetime) && isset($prpupdatetime)){
$query .=' AND prp_update_time="'.$prpupdatetime.'"'; 
}
$prpterminationreasonid=$request->input('prp_termination_reason_id');
if(isset($prpterminationreasonid) && isset($prpterminationreasonid)){
$query .=' AND prp_termination_reason_id="'.$prpterminationreasonid.'"'; 
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_project_status_id'=> trans('form_lang.prp_project_status_id'), 
'prp_record_date_ec'=> trans('form_lang.prp_record_date_ec'), 
'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'), 
'prp_physical_performance'=> trans('form_lang.prp_physical_performance'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 
'prp_created_date'=> trans('form_lang.prp_created_date'), 
'prp_termination_reason_id'=> trans('form_lang.prp_termination_reason_id'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_project_status_id'=> 'max:200', 
'prp_record_date_ec'=> 'max:200', 
'prp_record_date_gc'=> 'max:200', 
'prp_total_budget_used'=> 'max:200', 
'prp_physical_performance'=> 'max:200', 
'prp_description'=> 'max:100', 
'prp_status'=> 'integer', 
'prp_created_date'=> 'integer',

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
        $id=$request->get("prp_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectperformance::findOrFail($id);
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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectperformance::create($requestData);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_project_status_id'=> trans('form_lang.prp_project_status_id'), 
'prp_record_date_ec'=> trans('form_lang.prp_record_date_ec'), 
'prp_record_date_gc'=> trans('form_lang.prp_record_date_gc'), 
'prp_total_budget_used'=> trans('form_lang.prp_total_budget_used'), 
'prp_physical_performance'=> trans('form_lang.prp_physical_performance'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 
'prp_created_date'=> trans('form_lang.prp_created_date'), 
'prp_termination_reason_id'=> trans('form_lang.prp_termination_reason_id'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_project_status_id'=> 'max:200', 
'prp_record_date_ec'=> 'max:200', 
'prp_record_date_gc'=> 'max:200', 
'prp_total_budget_used'=> 'max:200', 
'prp_physical_performance'=> 'max:200', 
'prp_description'=> 'max:100', 
'prp_status'=> 'integer', 
'prp_created_date'=> 'integer',

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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        $data_info=Modelpmsprojectperformance::create($requestData);
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
    $id=$request->get("prp_id");
    Modelpmsprojectperformance::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
function listRoutes(){
    Route::resource('project_performance', 'PmsprojectperformanceController');
    Route::post('project_performance/listgrid', 'Api\PmsprojectperformanceController@listgrid');
    Route::post('project_performance/insertgrid', 'Api\PmsprojectperformanceController@insertgrid');
    Route::post('project_performance/updategrid', 'Api\PmsprojectperformanceController@updategrid');
    Route::post('project_performance/deletegrid', 'Api\PmsprojectperformanceController@deletegrid');
    Route::post('project_performance/search', 'PmsprojectperformanceController@search');
    Route::post('project_performance/getform', 'PmsprojectperformanceController@getForm');
    Route::post('project_performance/getlistform', 'PmsprojectperformanceController@getListForm');

}
}