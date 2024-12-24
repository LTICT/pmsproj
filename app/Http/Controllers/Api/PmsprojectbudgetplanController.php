<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectbudgetplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectbudgetplanController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_budget_plan');
    $dataInfo = Modelpmsprojectbudgetplan::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_budget_plan_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_budget_plan");
    return view('project_budget_plan.list_pms_project_budget_plan', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectbudgetplan::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectbudgetplanController";
        $data= $this->validateEdit($data, $data_info['bpl_create_time'], $controllerName);
        $data['pms_project_budget_plan_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_budget_plan");
$form= view('project_budget_plan.form_popup_pms_project_budget_plan', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_budget_plan'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_budget_plan.editable_list_pms_project_budget_plan', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_budget_plan'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_budget_plan");
        $data['action_mode']="create";
        return view('project_budget_plan.form_pms_project_budget_plan', $data);
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
        'bpl_project_id'=> trans('form_lang.bpl_project_id'), 
'bpl_budget_year_id'=> trans('form_lang.bpl_budget_year_id'), 
'bpl_budget_code_id'=> trans('form_lang.bpl_budget_code_id'), 
'bpl_amount'=> trans('form_lang.bpl_amount'), 
'bpl_description'=> trans('form_lang.bpl_description'), 
'bpl_status'=> trans('form_lang.bpl_status'), 
'bpl_created_date'=> trans('form_lang.bpl_created_date'), 

    ];
    $rules= [
        'bpl_project_id'=> 'max:200', 
'bpl_budget_year_id'=> 'max:200', 
'bpl_budget_code_id'=> 'max:200', 
'bpl_amount'=> 'numeric', 
'bpl_description'=> 'max:425', 
'bpl_status'=> 'integer', 
//'bpl_created_date'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['bpl_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectbudgetplan::create($requestData);
        return redirect('project_budget_plan')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_budget_plan/create')
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
        $query='SELECT bpl_id,bpl_project_id,bpl_budget_year_id,bpl_budget_code_id,bpl_amount,bpl_description,bpl_status,bpl_created_by,bpl_created_date,bpl_create_time,bpl_update_time FROM pms_project_budget_plan ';       
        
        $query .=' WHERE bpl_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_budget_plan_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectbudgetplan::findOrFail($id);
        //$data['pms_project_budget_plan_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_budget_plan");
        return view('project_budget_plan.show_pms_project_budget_plan', $data);
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
        
        
        $data_info = Modelpmsprojectbudgetplan::find($id);
        $data['pms_project_budget_plan_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_budget_plan");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_budget_plan.form_pms_project_budget_plan', $data);
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
        'bpl_project_id'=> trans('form_lang.bpl_project_id'), 
'bpl_budget_year_id'=> trans('form_lang.bpl_budget_year_id'), 
'bpl_budget_code_id'=> trans('form_lang.bpl_budget_code_id'), 
'bpl_amount'=> trans('form_lang.bpl_amount'), 
'bpl_description'=> trans('form_lang.bpl_description'), 
'bpl_status'=> trans('form_lang.bpl_status'), 
'bpl_created_date'=> trans('form_lang.bpl_created_date'), 

    ];
    $rules= [
        'bpl_project_id'=> 'max:200', 
'bpl_budget_year_id'=> 'max:200', 
'bpl_budget_code_id'=> 'max:200', 
'bpl_amount'=> 'numeric', 
'bpl_description'=> 'max:425', 
'bpl_status'=> 'integer', 
'bpl_created_date'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectbudgetplan::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_budget_plan')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_budget_plan/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_budget_plan/'.$id.'/edit')
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
        Modelpmsprojectbudgetplan::destroy($id);
        return redirect('project_budget_plan')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT bpl_id,bpl_project_id,prj_name,prj_code,pms_budget_year.bdy_name As bpl_budget_year, bpl_budget_year_id,pms_expenditure_code.pec_name  As bpl_budget_code, bpl_budget_code_id,bpl_amount,bpl_description,bpl_status,bpl_created_by,bpl_create_time,bpl_update_time,1 AS is_editable, 1 AS is_deletable FROM pms_project_budget_plan ';
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_plan.bpl_project_id';
     $query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_plan.bpl_budget_code_id';
       $query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_plan.bpl_budget_year_id';
     $query .=' WHERE 1=1';

 $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('employee_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND emp_start_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('employee_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND emp_start_date_gc <='".$endTime." 23 59 59'"; 
}
$bplbudgetyearid=$request->input('bpl_budget_year_id');
if(isset($bplbudgetyearid) && isset($bplbudgetyearid)){
$query .=" AND bpl_budget_year_id='".$bplbudgetyearid."'"; 
}

     $bplid=$request->input('bpl_id');
if(isset($bplid) && isset($bplid)){
$query .=' AND bpl_id="'.$bplid.'"'; 
}
$bplprojectid=$request->input('bpl_project_id');
if(isset($bplprojectid) && isset($bplprojectid)){
$query .=" AND bpl_project_id='".$bplprojectid."'"; 
}

$bplbudgetcodeid=$request->input('bpl_budget_code_id');
if(isset($bplbudgetcodeid) && isset($bplbudgetcodeid)){
$query .=' AND bpl_budget_code_id="'.$bplbudgetcodeid.'"'; 
}
$bplamount=$request->input('bpl_amount');
if(isset($bplamount) && isset($bplamount)){
$query .=' AND bpl_amount="'.$bplamount.'"'; 
}
$bpldescription=$request->input('bpl_description');
if(isset($bpldescription) && isset($bpldescription)){
$query .=' AND bpl_description="'.$bpldescription.'"'; 
}
$bplstatus=$request->input('bpl_status');
if(isset($bplstatus) && isset($bplstatus)){
$query .=' AND bpl_status="'.$bplstatus.'"'; 
}
$bplcreatedby=$request->input('bpl_created_by');
if(isset($bplcreatedby) && isset($bplcreatedby)){
$query .=' AND bpl_created_by="'.$bplcreatedby.'"'; 
}
$bplcreateddate=$request->input('bpl_created_date');
if(isset($bplcreateddate) && isset($bplcreateddate)){
$query .=' AND bpl_created_date="'.$bplcreateddate.'"'; 
}
$bplcreatetime=$request->input('bpl_create_time');
if(isset($bplcreatetime) && isset($bplcreatetime)){
$query .=' AND bpl_create_time="'.$bplcreatetime.'"'; 
}
$bplupdatetime=$request->input('bpl_update_time');
if(isset($bplupdatetime) && isset($bplupdatetime)){
$query .=' AND bpl_update_time="'.$bplupdatetime.'"'; 
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
        'bpl_project_id'=> trans('form_lang.bpl_project_id'), 
'bpl_budget_year_id'=> trans('form_lang.bpl_budget_year_id'), 
'bpl_budget_code_id'=> trans('form_lang.bpl_budget_code_id'), 
'bpl_amount'=> trans('form_lang.bpl_amount'), 
'bpl_description'=> trans('form_lang.bpl_description'), 
'bpl_status'=> trans('form_lang.bpl_status'), 
'bpl_created_date'=> trans('form_lang.bpl_created_date'), 

    ];
    $rules= [
'bpl_amount'=> 'numeric', 
'bpl_description'=> 'max:425', 

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
        $id=$request->get("bpl_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bpl_status');
        if($status=="true"){
            $requestData['bpl_status']=1;
        }else{
            $requestData['bpl_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectbudgetplan::findOrFail($id);
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
        //$requestData['bpl_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectbudgetplan::create($requestData);
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
        'bpl_project_id'=> trans('form_lang.bpl_project_id'), 
'bpl_budget_year_id'=> trans('form_lang.bpl_budget_year_id'), 
'bpl_budget_code_id'=> trans('form_lang.bpl_budget_code_id'), 
'bpl_amount'=> trans('form_lang.bpl_amount'), 
'bpl_description'=> trans('form_lang.bpl_description'), 
'bpl_status'=> trans('form_lang.bpl_status'), 
'bpl_created_date'=> trans('form_lang.bpl_created_date'), 

    ];
    $rules= [ 
'bpl_amount'=> 'numeric', 
'bpl_description'=> 'max:425', 

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
        //$requestData['bpl_created_by']=auth()->user()->usr_Id;
        $requestData['bpl_created_by']=2;
        $status= $request->input('bpl_status');
        if($status=="true"){
            $requestData['bpl_status']=1;
        }else{
            $requestData['bpl_status']=0;
        }
        $data_info=Modelpmsprojectbudgetplan::create($requestData);
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
    $id=$request->get("bpl_id");
    Modelpmsprojectbudgetplan::destroy($id);
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
    Route::resource('project_budget_plan', 'PmsprojectbudgetplanController');
    Route::post('project_budget_plan/listgrid', 'Api\PmsprojectbudgetplanController@listgrid');
    Route::post('project_budget_plan/insertgrid', 'Api\PmsprojectbudgetplanController@insertgrid');
    Route::post('project_budget_plan/updategrid', 'Api\PmsprojectbudgetplanController@updategrid');
    Route::post('project_budget_plan/deletegrid', 'Api\PmsprojectbudgetplanController@deletegrid');
    Route::post('project_budget_plan/search', 'PmsprojectbudgetplanController@search');
    Route::post('project_budget_plan/getform', 'PmsprojectbudgetplanController@getForm');
    Route::post('project_budget_plan/getlistform', 'PmsprojectbudgetplanController@getListForm');

}
}