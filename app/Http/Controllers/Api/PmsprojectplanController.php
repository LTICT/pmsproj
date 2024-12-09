<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modelpmsprojectplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectplanController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_plan');
    $dataInfo = Modelpmsprojectplan::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_plan_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_plan");
    return view('project_plan.list_pms_project_plan', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectplan::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectplanController";
        $data= $this->validateEdit($data, $data_info['pld_create_time'], $controllerName);
        $data['pms_project_plan_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_plan");
$form= view('project_plan.form_popup_pms_project_plan', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_plan'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_plan.editable_list_pms_project_plan', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_plan'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_plan");
        $data['action_mode']="create";
        return view('project_plan.form_pms_project_plan', $data);
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
        'pld_name'=> trans('form_lang.pld_name'), 
'pld_project_id'=> trans('form_lang.pld_project_id'), 
'pld_budget_year_id'=> trans('form_lang.pld_budget_year_id'), 
'pld_start_date_ec'=> trans('form_lang.pld_start_date_ec'), 
'pld_start_date_gc'=> trans('form_lang.pld_start_date_gc'), 
'pld_end_date_ec'=> trans('form_lang.pld_end_date_ec'), 
'pld_end_date_gc'=> trans('form_lang.pld_end_date_gc'), 
'pld_description'=> trans('form_lang.pld_description'), 
'pld_status'=> trans('form_lang.pld_status'), 

    ];
    $rules= [
        'pld_name'=> 'max:200', 
'pld_project_id'=> 'max:200', 
'pld_budget_year_id'=> 'max:200', 
'pld_start_date_ec'=> 'max:200', 
'pld_start_date_gc'=> 'max:200', 
'pld_end_date_ec'=> 'max:200', 
'pld_end_date_gc'=> 'max:200', 
'pld_description'=> 'max:425', 
'pld_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pld_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectplan::create($requestData);
        return redirect('project_plan')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_plan/create')
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
        $query='SELECT pld_id,pld_name,pld_project_id,pld_budget_year_id,pld_start_date_ec,pld_start_date_gc,pld_end_date_ec,pld_end_date_gc,pld_description,pld_create_time,pld_update_time,pld_delete_time,pld_created_by,pld_status FROM pms_project_plan ';       
        
        $query .=' WHERE pld_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_plan_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectplan::findOrFail($id);
        //$data['pms_project_plan_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_plan");
        return view('project_plan.show_pms_project_plan', $data);
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
        
        
        $data_info = Modelpmsprojectplan::find($id);
        $data['pms_project_plan_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_plan");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_plan.form_pms_project_plan', $data);
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
        'pld_name'=> trans('form_lang.pld_name'), 
'pld_project_id'=> trans('form_lang.pld_project_id'), 
'pld_budget_year_id'=> trans('form_lang.pld_budget_year_id'), 
'pld_start_date_ec'=> trans('form_lang.pld_start_date_ec'), 
'pld_start_date_gc'=> trans('form_lang.pld_start_date_gc'), 
'pld_end_date_ec'=> trans('form_lang.pld_end_date_ec'), 
'pld_end_date_gc'=> trans('form_lang.pld_end_date_gc'), 
'pld_description'=> trans('form_lang.pld_description'), 
'pld_status'=> trans('form_lang.pld_status'), 

    ];
    $rules= [
        'pld_name'=> 'max:200', 
'pld_project_id'=> 'max:200', 
'pld_budget_year_id'=> 'max:200', 
'pld_start_date_ec'=> 'max:200', 
'pld_start_date_gc'=> 'max:200', 
'pld_end_date_ec'=> 'max:200', 
'pld_end_date_gc'=> 'max:200', 
'pld_description'=> 'max:425', 
'pld_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectplan::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_plan')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_plan/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_plan/'.$id.'/edit')
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
        Modelpmsprojectplan::destroy($id);
        return redirect('project_plan')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT pld_id,pld_name,pld_project_id,pld_budget_year_id,pld_start_date_ec,pld_start_date_gc,pld_end_date_ec,pld_end_date_gc,pld_description,pld_create_time,pld_update_time,pld_delete_time,pld_created_by,pld_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_plan ';       
     
     $query .=' WHERE 1=1';
     $pldid=$request->input('pld_id');
if(isset($pldid) && isset($pldid)){
$query .=' AND pld_id="'.$pldid.'"'; 
}
$pldname=$request->input('pld_name');
if(isset($pldname) && isset($pldname)){
$query .=' AND pld_name="'.$pldname.'"'; 
}
$pldprojectid=$request->input('pld_project_id');
if(isset($pldprojectid) && isset($pldprojectid)){
$query .=' AND pld_project_id="'.$pldprojectid.'"'; 
}
$pldbudgetyearid=$request->input('pld_budget_year_id');
if(isset($pldbudgetyearid) && isset($pldbudgetyearid)){
$query .=' AND pld_budget_year_id="'.$pldbudgetyearid.'"'; 
}
$pldstartdateec=$request->input('pld_start_date_ec');
if(isset($pldstartdateec) && isset($pldstartdateec)){
$query .=' AND pld_start_date_ec="'.$pldstartdateec.'"'; 
}
$pldstartdategc=$request->input('pld_start_date_gc');
if(isset($pldstartdategc) && isset($pldstartdategc)){
$query .=' AND pld_start_date_gc="'.$pldstartdategc.'"'; 
}
$pldenddateec=$request->input('pld_end_date_ec');
if(isset($pldenddateec) && isset($pldenddateec)){
$query .=' AND pld_end_date_ec="'.$pldenddateec.'"'; 
}
$pldenddategc=$request->input('pld_end_date_gc');
if(isset($pldenddategc) && isset($pldenddategc)){
$query .=' AND pld_end_date_gc="'.$pldenddategc.'"'; 
}
$plddescription=$request->input('pld_description');
if(isset($plddescription) && isset($plddescription)){
$query .=' AND pld_description="'.$plddescription.'"'; 
}
$pldcreatetime=$request->input('pld_create_time');
if(isset($pldcreatetime) && isset($pldcreatetime)){
$query .=' AND pld_create_time="'.$pldcreatetime.'"'; 
}
$pldupdatetime=$request->input('pld_update_time');
if(isset($pldupdatetime) && isset($pldupdatetime)){
$query .=' AND pld_update_time="'.$pldupdatetime.'"'; 
}
$plddeletetime=$request->input('pld_delete_time');
if(isset($plddeletetime) && isset($plddeletetime)){
$query .=' AND pld_delete_time="'.$plddeletetime.'"'; 
}
$pldcreatedby=$request->input('pld_created_by');
if(isset($pldcreatedby) && isset($pldcreatedby)){
$query .=' AND pld_created_by="'.$pldcreatedby.'"'; 
}
$pldstatus=$request->input('pld_status');
if(isset($pldstatus) && isset($pldstatus)){
$query .=' AND pld_status="'.$pldstatus.'"'; 
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
        'pld_name'=> trans('form_lang.pld_name'), 
'pld_project_id'=> trans('form_lang.pld_project_id'), 
'pld_budget_year_id'=> trans('form_lang.pld_budget_year_id'), 
'pld_start_date_ec'=> trans('form_lang.pld_start_date_ec'), 
'pld_start_date_gc'=> trans('form_lang.pld_start_date_gc'), 
'pld_end_date_ec'=> trans('form_lang.pld_end_date_ec'), 
'pld_end_date_gc'=> trans('form_lang.pld_end_date_gc'), 
'pld_description'=> trans('form_lang.pld_description'), 
'pld_status'=> trans('form_lang.pld_status'), 

    ];
    $rules= [
        'pld_name'=> 'max:200', 
'pld_project_id'=> 'max:200', 
'pld_budget_year_id'=> 'max:200', 
'pld_start_date_ec'=> 'max:200', 
'pld_start_date_gc'=> 'max:200', 
'pld_end_date_ec'=> 'max:200', 
'pld_end_date_gc'=> 'max:200', 
'pld_description'=> 'max:425',

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
        $id=$request->get("pld_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pld_status');
        if($status=="true"){
            $requestData['pld_status']=1;
        }else{
            $requestData['pld_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectplan::findOrFail($id);
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
        //$requestData['pld_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectplan::create($requestData);
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
        'pld_name'=> trans('form_lang.pld_name'), 
'pld_project_id'=> trans('form_lang.pld_project_id'), 
'pld_budget_year_id'=> trans('form_lang.pld_budget_year_id'), 
'pld_start_date_ec'=> trans('form_lang.pld_start_date_ec'), 
'pld_start_date_gc'=> trans('form_lang.pld_start_date_gc'), 
'pld_end_date_ec'=> trans('form_lang.pld_end_date_ec'), 
'pld_end_date_gc'=> trans('form_lang.pld_end_date_gc'), 
'pld_description'=> trans('form_lang.pld_description'), 

    ];
    $rules= [
        'pld_name'=> 'max:200', 
'pld_project_id'=> 'max:200', 
'pld_budget_year_id'=> 'max:200', 
'pld_start_date_ec'=> 'max:200', 
'pld_start_date_gc'=> 'max:200', 
'pld_end_date_ec'=> 'max:200', 
'pld_end_date_gc'=> 'max:200', 
'pld_description'=> 'max:425'
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
        //$requestData['pld_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pld_status');
        if($status=="true"){
            $requestData['pld_status']=1;
        }else{
            $requestData['pld_status']=0;
        }
        $data_info=Modelpmsprojectplan::create($requestData);
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
    $id=$request->get("pld_id");
    Modelpmsprojectplan::destroy($id);
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
    Route::resource('project_plan', 'PmsprojectplanController');
    Route::post('project_plan/listgrid', 'Api\PmsprojectplanController@listgrid');
    Route::post('project_plan/insertgrid', 'Api\PmsprojectplanController@insertgrid');
    Route::post('project_plan/updategrid', 'Api\PmsprojectplanController@updategrid');
    Route::post('project_plan/deletegrid', 'Api\PmsprojectplanController@deletegrid');
    Route::post('project_plan/search', 'PmsprojectplanController@search');
    Route::post('project_plan/getform', 'PmsprojectplanController@getForm');
    Route::post('project_plan/getlistform', 'PmsprojectplanController@getListForm');

}
}