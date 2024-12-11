<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modelpmsprojectemployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectemployeeController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_employee');
    $dataInfo = Modelpmsprojectemployee::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_employee_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_employee");
    return view('project_employee.list_pms_project_employee', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectemployee::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectemployeeController";
        $data= $this->validateEdit($data, $data_info['emp_create_time'], $controllerName);
        $data['pms_project_employee_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_employee");
$form= view('project_employee.form_popup_pms_project_employee', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_employee'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_employee.editable_list_pms_project_employee', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_employee'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_employee");
        $data['action_mode']="create";
        return view('project_employee.form_pms_project_employee', $data);
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
        'emp_id_no'=> trans('form_lang.emp_id_no'), 
'emp_full_name'=> trans('form_lang.emp_full_name'), 
'emp_email'=> trans('form_lang.emp_email'), 
'emp_phone_num'=> trans('form_lang.emp_phone_num'), 
'emp_role'=> trans('form_lang.emp_role'), 
'emp_project_id'=> trans('form_lang.emp_project_id'), 
'emp_start_date_ec'=> trans('form_lang.emp_start_date_ec'), 
'emp_start_date_gc'=> trans('form_lang.emp_start_date_gc'), 
'emp_end_date_ec'=> trans('form_lang.emp_end_date_ec'), 
'emp_end_date_gc'=> trans('form_lang.emp_end_date_gc'), 
'emp_address'=> trans('form_lang.emp_address'), 
'emp_description'=> trans('form_lang.emp_description'), 

    ];
    $rules= [
        'emp_id_no'=> 'max:200', 
'emp_full_name'=> 'max:200', 
'emp_email'=> 'max:50', 
'emp_phone_num'=> 'max:200', 
'emp_role'=> 'max:200', 
'emp_project_id'=> 'max:200', 
'emp_start_date_ec'=> 'max:200', 
'emp_start_date_gc'=> 'max:200', 
'emp_end_date_ec'=> 'max:10', 
'emp_end_date_gc'=> 'max:10', 
'emp_address'=> 'max:50', 
'emp_description'=> 'max:425', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['emp_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectemployee::create($requestData);
        return redirect('project_employee')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_employee/create')
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
        $query='SELECT emp_id,emp_id_no,emp_full_name,emp_email,emp_phone_num,emp_role,emp_project_id,emp_start_date_ec,emp_start_date_gc,emp_end_date_ec,emp_end_date_gc,emp_address,emp_description,emp_create_time,emp_update_time,emp_delete_time,emp_created_by,emp_current_status FROM pms_project_employee ';       
        
        $query .=' WHERE emp_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_employee_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectemployee::findOrFail($id);
        //$data['pms_project_employee_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_employee");
        return view('project_employee.show_pms_project_employee', $data);
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
        
        
        $data_info = Modelpmsprojectemployee::find($id);
        $data['pms_project_employee_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_employee");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_employee.form_pms_project_employee', $data);
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
        'emp_id_no'=> trans('form_lang.emp_id_no'), 
'emp_full_name'=> trans('form_lang.emp_full_name'), 
'emp_email'=> trans('form_lang.emp_email'), 
'emp_phone_num'=> trans('form_lang.emp_phone_num'), 
'emp_role'=> trans('form_lang.emp_role'), 
'emp_project_id'=> trans('form_lang.emp_project_id'), 
'emp_start_date_ec'=> trans('form_lang.emp_start_date_ec'), 
'emp_start_date_gc'=> trans('form_lang.emp_start_date_gc'), 
'emp_end_date_ec'=> trans('form_lang.emp_end_date_ec'), 
'emp_end_date_gc'=> trans('form_lang.emp_end_date_gc'), 
'emp_address'=> trans('form_lang.emp_address'), 
'emp_description'=> trans('form_lang.emp_description'), 

    ];
    $rules= [
        'emp_id_no'=> 'max:200', 
'emp_full_name'=> 'max:200', 
'emp_email'=> 'max:50', 
'emp_phone_num'=> 'max:200', 
'emp_role'=> 'max:200', 
'emp_project_id'=> 'max:200', 
'emp_start_date_ec'=> 'max:200', 
'emp_start_date_gc'=> 'max:200', 
'emp_end_date_ec'=> 'max:10', 
'emp_end_date_gc'=> 'max:10', 
'emp_address'=> 'max:50', 
'emp_description'=> 'max:425', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectemployee::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_employee')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_employee/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_employee/'.$id.'/edit')
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
        Modelpmsprojectemployee::destroy($id);
        return redirect('project_employee')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT emp_id,emp_id_no,emp_full_name,emp_email,emp_phone_num,emp_role,emp_project_id,emp_start_date_ec,emp_start_date_gc,emp_end_date_ec,emp_end_date_gc,emp_address,emp_description,emp_create_time,emp_update_time,emp_delete_time,emp_created_by,emp_current_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_employee ';       
     
     $query .=' WHERE 1=1';
     $empid=$request->input('emp_id');
if(isset($empid) && isset($empid)){
$query .=' AND emp_id="'.$empid.'"'; 
}
$empidno=$request->input('emp_id_no');
if(isset($empidno) && isset($empidno)){
$query .=' AND emp_id_no="'.$empidno.'"'; 
}
$empfullname=$request->input('emp_full_name');
if(isset($empfullname) && isset($empfullname)){
$query .=' AND emp_full_name="'.$empfullname.'"'; 
}
$empemail=$request->input('emp_email');
if(isset($empemail) && isset($empemail)){
$query .=' AND emp_email="'.$empemail.'"'; 
}
$empphonenum=$request->input('emp_phone_num');
if(isset($empphonenum) && isset($empphonenum)){
$query .=' AND emp_phone_num="'.$empphonenum.'"'; 
}
$emprole=$request->input('emp_role');
if(isset($emprole) && isset($emprole)){
$query .=' AND emp_role="'.$emprole.'"'; 
}
$empprojectid=$request->input('emp_project_id');
if(isset($empprojectid) && isset($empprojectid)){
$query .=" AND emp_project_id='".$empprojectid."'"; 
}
$empstartdateec=$request->input('emp_start_date_ec');
if(isset($empstartdateec) && isset($empstartdateec)){
$query .=' AND emp_start_date_ec="'.$empstartdateec.'"'; 
}
$empstartdategc=$request->input('emp_start_date_gc');
if(isset($empstartdategc) && isset($empstartdategc)){
$query .=' AND emp_start_date_gc="'.$empstartdategc.'"'; 
}
$empenddateec=$request->input('emp_end_date_ec');
if(isset($empenddateec) && isset($empenddateec)){
$query .=' AND emp_end_date_ec="'.$empenddateec.'"'; 
}
$empenddategc=$request->input('emp_end_date_gc');
if(isset($empenddategc) && isset($empenddategc)){
$query .=' AND emp_end_date_gc="'.$empenddategc.'"'; 
}
$empaddress=$request->input('emp_address');
if(isset($empaddress) && isset($empaddress)){
$query .=' AND emp_address="'.$empaddress.'"'; 
}
$empdescription=$request->input('emp_description');
if(isset($empdescription) && isset($empdescription)){
$query .=' AND emp_description="'.$empdescription.'"'; 
}
$empcreatetime=$request->input('emp_create_time');
if(isset($empcreatetime) && isset($empcreatetime)){
$query .=' AND emp_create_time="'.$empcreatetime.'"'; 
}
$empupdatetime=$request->input('emp_update_time');
if(isset($empupdatetime) && isset($empupdatetime)){
$query .=' AND emp_update_time="'.$empupdatetime.'"'; 
}
$empdeletetime=$request->input('emp_delete_time');
if(isset($empdeletetime) && isset($empdeletetime)){
$query .=' AND emp_delete_time="'.$empdeletetime.'"'; 
}
$empcreatedby=$request->input('emp_created_by');
if(isset($empcreatedby) && isset($empcreatedby)){
$query .=' AND emp_created_by="'.$empcreatedby.'"'; 
}
$empcurrentstatus=$request->input('emp_current_status');
if(isset($empcurrentstatus) && isset($empcurrentstatus)){
$query .=' AND emp_current_status="'.$empcurrentstatus.'"'; 
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
        'emp_id_no'=> trans('form_lang.emp_id_no'), 
'emp_full_name'=> trans('form_lang.emp_full_name'), 
'emp_email'=> trans('form_lang.emp_email'), 
'emp_phone_num'=> trans('form_lang.emp_phone_num'), 
'emp_role'=> trans('form_lang.emp_role'), 
'emp_project_id'=> trans('form_lang.emp_project_id'), 
'emp_start_date_ec'=> trans('form_lang.emp_start_date_ec'), 
'emp_start_date_gc'=> trans('form_lang.emp_start_date_gc'), 
'emp_end_date_ec'=> trans('form_lang.emp_end_date_ec'), 
'emp_end_date_gc'=> trans('form_lang.emp_end_date_gc'), 
'emp_address'=> trans('form_lang.emp_address'), 
'emp_description'=> trans('form_lang.emp_description'), 

    ];
    $rules= [
        'emp_id_no'=> 'max:200', 
'emp_full_name'=> 'max:200', 
'emp_email'=> 'max:50', 
'emp_phone_num'=> 'max:200', 
'emp_role'=> 'max:200', 
'emp_project_id'=> 'max:200', 
'emp_start_date_ec'=> 'max:200', 
'emp_start_date_gc'=> 'max:200', 
'emp_end_date_ec'=> 'max:10', 
'emp_end_date_gc'=> 'max:10', 
'emp_address'=> 'max:50', 
'emp_description'=> 'max:425', 

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
        $id=$request->get("emp_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('emp_status');
        if($status=="true"){
            $requestData['emp_status']=1;
        }else{
            $requestData['emp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectemployee::findOrFail($id);
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
        //$requestData['emp_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectemployee::create($requestData);
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
        'emp_id_no'=> trans('form_lang.emp_id_no'), 
'emp_full_name'=> trans('form_lang.emp_full_name'), 
'emp_email'=> trans('form_lang.emp_email'), 
'emp_phone_num'=> trans('form_lang.emp_phone_num'), 
'emp_role'=> trans('form_lang.emp_role'), 
'emp_project_id'=> trans('form_lang.emp_project_id'), 
'emp_start_date_ec'=> trans('form_lang.emp_start_date_ec'), 
'emp_start_date_gc'=> trans('form_lang.emp_start_date_gc'), 
'emp_end_date_ec'=> trans('form_lang.emp_end_date_ec'), 
'emp_end_date_gc'=> trans('form_lang.emp_end_date_gc'), 
'emp_address'=> trans('form_lang.emp_address'), 
'emp_description'=> trans('form_lang.emp_description'), 

    ];
    $rules= [
        'emp_id_no'=> 'max:200', 
'emp_full_name'=> 'max:200', 
'emp_email'=> 'max:50', 
'emp_phone_num'=> 'max:200', 
'emp_role'=> 'max:200', 
'emp_project_id'=> 'max:200', 
'emp_start_date_ec'=> 'max:200', 
'emp_start_date_gc'=> 'max:200', 
'emp_end_date_ec'=> 'max:10', 
'emp_end_date_gc'=> 'max:10', 
'emp_address'=> 'max:50', 
'emp_description'=> 'max:425', 

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
        //$requestData['emp_created_by']=auth()->user()->usr_Id;
        $status= $request->input('emp_status');
        if($status=="true"){
            $requestData['emp_status']=1;
        }else{
            $requestData['emp_status']=0;
        }
        $data_info=Modelpmsprojectemployee::create($requestData);
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
    $id=$request->get("emp_id");
    Modelpmsprojectemployee::destroy($id);
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
    Route::resource('project_employee', 'PmsprojectemployeeController');
    Route::post('project_employee/listgrid', 'Api\PmsprojectemployeeController@listgrid');
    Route::post('project_employee/insertgrid', 'Api\PmsprojectemployeeController@insertgrid');
    Route::post('project_employee/updategrid', 'Api\PmsprojectemployeeController@updategrid');
    Route::post('project_employee/deletegrid', 'Api\PmsprojectemployeeController@deletegrid');
    Route::post('project_employee/search', 'PmsprojectemployeeController@search');
    Route::post('project_employee/getform', 'PmsprojectemployeeController@getForm');
    Route::post('project_employee/getlistform', 'PmsprojectemployeeController@getListForm');

}
}