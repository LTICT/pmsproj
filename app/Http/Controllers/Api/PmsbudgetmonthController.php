<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetmonth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetmonthController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_budget_month');
    $dataInfo = Modelpmsbudgetmonth::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_budget_month_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_budget_month");
    return view('budget_month.list_pms_budget_month', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsbudgetmonth::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsbudgetmonthController";
        $data= $this->validateEdit($data, $data_info['bdm_create_time'], $controllerName);
        $data['pms_budget_month_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_budget_month");
$form= view('budget_month.form_popup_pms_budget_month', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_budget_month'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('budget_month.editable_list_pms_budget_month', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_budget_month'));
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
        
        
        $data['page_title']=trans("form_lang.pms_budget_month");
        $data['action_mode']="create";
        return view('budget_month.form_pms_budget_month', $data);
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
        'bdm_month'=> trans('form_lang.bdm_month'), 
'bdm_name_or'=> trans('form_lang.bdm_name_or'), 
'bdm_name_am'=> trans('form_lang.bdm_name_am'), 
'bdm_name_en'=> trans('form_lang.bdm_name_en'), 
'bdm_code'=> trans('form_lang.bdm_code'), 
'bdm_description'=> trans('form_lang.bdm_description'), 
'bdm_status'=> trans('form_lang.bdm_status'), 

    ];
    $rules= [
        'bdm_month'=> 'max:200', 
'bdm_name_or'=> 'max:200', 
'bdm_name_am'=> 'max:200', 
'bdm_name_en'=> 'max:200', 
'bdm_code'=> 'max:200', 
'bdm_description'=> 'max:425', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['bdm_created_by']=auth()->user()->usr_Id;
        Modelpmsbudgetmonth::create($requestData);
        return redirect('budget_month')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('budget_month/create')
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
        $query='SELECT bdm_id,bdm_month,bdm_name_or,bdm_name_am,bdm_name_en,bdm_code,bdm_description,bdm_create_time,bdm_update_time,bdm_delete_time,bdm_created_by,bdm_status FROM pms_budget_month ';       
        
        $query .=' WHERE bdm_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_month_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetmonth::findOrFail($id);
        //$data['pms_budget_month_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_month");
        return view('budget_month.show_pms_budget_month', $data);
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
        
        
        $data_info = Modelpmsbudgetmonth::find($id);
        $data['pms_budget_month_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_month");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('budget_month.form_pms_budget_month', $data);
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
        'bdm_month'=> trans('form_lang.bdm_month'), 
'bdm_name_or'=> trans('form_lang.bdm_name_or'), 
'bdm_name_am'=> trans('form_lang.bdm_name_am'), 
'bdm_name_en'=> trans('form_lang.bdm_name_en'), 
'bdm_code'=> trans('form_lang.bdm_code'), 
'bdm_description'=> trans('form_lang.bdm_description'), 
'bdm_status'=> trans('form_lang.bdm_status'), 

    ];
    $rules= [
'bdm_name_or'=> 'max:100', 
'bdm_name_am'=> 'max:100', 
'bdm_name_en'=> 'max:100', 
'bdm_code'=> 'max:20', 
'bdm_description'=> 'max:425', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsbudgetmonth::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('budget_month')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('budget_month/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('budget_month/'.$id.'/edit')
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
        Modelpmsbudgetmonth::destroy($id);
        return redirect('budget_month')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
        $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,45);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT bdm_id,bdm_month,bdm_name_or,bdm_name_am,bdm_name_en,bdm_code,bdm_description,bdm_create_time,bdm_update_time,bdm_delete_time,bdm_created_by,bdm_status ".$permissionIndex." FROM pms_budget_month ";
     
     $query .=' WHERE 1=1';
     $bdmid=$request->input('bdm_id');
if(isset($bdmid) && isset($bdmid)){
$query .=' AND bdm_id="'.$bdmid.'"'; 
}
$bdmmonth=$request->input('bdm_month');
if(isset($bdmmonth) && isset($bdmmonth)){
$query .=' AND bdm_month="'.$bdmmonth.'"'; 
}
$bdmnameor=$request->input('bdm_name_or');
if(isset($bdmnameor) && isset($bdmnameor)){
$query .=' AND bdm_name_or="'.$bdmnameor.'"'; 
}
$bdmnameam=$request->input('bdm_name_am');
if(isset($bdmnameam) && isset($bdmnameam)){
$query .=' AND bdm_name_am="'.$bdmnameam.'"'; 
}
$bdmnameen=$request->input('bdm_name_en');
if(isset($bdmnameen) && isset($bdmnameen)){
$query .=' AND bdm_name_en="'.$bdmnameen.'"'; 
}
$bdmcode=$request->input('bdm_code');
if(isset($bdmcode) && isset($bdmcode)){
$query .=' AND bdm_code="'.$bdmcode.'"'; 
}
$bdmdescription=$request->input('bdm_description');
if(isset($bdmdescription) && isset($bdmdescription)){
$query .=' AND bdm_description="'.$bdmdescription.'"'; 
}
$bdmcreatetime=$request->input('bdm_create_time');
if(isset($bdmcreatetime) && isset($bdmcreatetime)){
$query .=' AND bdm_create_time="'.$bdmcreatetime.'"'; 
}
$bdmupdatetime=$request->input('bdm_update_time');
if(isset($bdmupdatetime) && isset($bdmupdatetime)){
$query .=' AND bdm_update_time="'.$bdmupdatetime.'"'; 
}
$bdmdeletetime=$request->input('bdm_delete_time');
if(isset($bdmdeletetime) && isset($bdmdeletetime)){
$query .=' AND bdm_delete_time="'.$bdmdeletetime.'"'; 
}
$bdmcreatedby=$request->input('bdm_created_by');
if(isset($bdmcreatedby) && isset($bdmcreatedby)){
$query .=' AND bdm_created_by="'.$bdmcreatedby.'"'; 
}
$bdmstatus=$request->input('bdm_status');
if(isset($bdmstatus) && isset($bdmstatus)){
$query .=' AND bdm_status="'.$bdmstatus.'"'; 
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
    "previledge"=>array('is_role_editable'=>$permissionData->pem_edit,'is_role_deletable'=>$permissionData->pem_delete,'is_role_can_add'=>$permissionData->pem_insert));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'bdm_month'=> trans('form_lang.bdm_month'), 
'bdm_name_or'=> trans('form_lang.bdm_name_or'), 
'bdm_name_am'=> trans('form_lang.bdm_name_am'), 
'bdm_name_en'=> trans('form_lang.bdm_name_en'), 
'bdm_code'=> trans('form_lang.bdm_code'), 
'bdm_description'=> trans('form_lang.bdm_description'), 
'bdm_status'=> trans('form_lang.bdm_status'), 

    ];
    $rules= [
'bdm_month'=> 'required|max:2', 
'bdm_name_or'=> 'required|max:20', 
'bdm_name_am'=> 'required|max:20', 
'bdm_name_en'=> 'required|max:20', 
'bdm_code'=> 'max:20', 
'bdm_description'=> 'max:425',
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
        $id=$request->get("bdm_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bdm_status');
        if($status=="true"){
            $requestData['bdm_status']=1;
        }else{
            $requestData['bdm_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetmonth::findOrFail($id);
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
        //$requestData['bdm_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetmonth::create($requestData);
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
        'bdm_month'=> trans('form_lang.bdm_month'), 
'bdm_name_or'=> trans('form_lang.bdm_name_or'), 
'bdm_name_am'=> trans('form_lang.bdm_name_am'), 
'bdm_name_en'=> trans('form_lang.bdm_name_en'), 
'bdm_code'=> trans('form_lang.bdm_code'), 
'bdm_description'=> trans('form_lang.bdm_description'), 
'bdm_status'=> trans('form_lang.bdm_status'), 

    ];
    $rules= [
'bdm_month'=> 'required|max:2', 
'bdm_name_or'=> 'required|max:20', 
'bdm_name_am'=> 'required|max:20', 
'bdm_name_en'=> 'required|max:20', 
'bdm_code'=> 'max:20', 
'bdm_description'=> 'max:425', 

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
        //$requestData['bdm_created_by']=auth()->user()->usr_Id;
        $requestData['bdm_created_by']=1;
        $status= $request->input('bdm_status');
        if($status=="true"){
            $requestData['bdm_status']=1;
        }else{
            $requestData['bdm_status']=0;
        }
        
        $data_info=Modelpmsbudgetmonth::create($requestData);
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
    $id=$request->get("bdm_id");
    Modelpmsbudgetmonth::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>"",
        "deleted_id"=>$id,
    );
    return response()->json($resultObject);
}
function listRoutes(){
    Route::resource('budget_month', 'PmsbudgetmonthController');
    Route::post('budget_month/listgrid', 'Api\PmsbudgetmonthController@listgrid');
    Route::post('budget_month/insertgrid', 'Api\PmsbudgetmonthController@insertgrid');
    Route::post('budget_month/updategrid', 'Api\PmsbudgetmonthController@updategrid');
    Route::post('budget_month/deletegrid', 'Api\PmsbudgetmonthController@deletegrid');
    Route::post('budget_month/search', 'PmsbudgetmonthController@search');
    Route::post('budget_month/getform', 'PmsbudgetmonthController@getForm');
    Route::post('budget_month/getlistform', 'PmsbudgetmonthController@getListForm');

}
}