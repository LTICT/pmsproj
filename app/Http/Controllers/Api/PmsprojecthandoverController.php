<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojecthandover;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojecthandoverController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_handover');
    $dataInfo = Modelpmsprojecthandover::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_handover_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_handover");
    return view('project_handover.list_pms_project_handover', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojecthandover::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojecthandoverController";
        $data= $this->validateEdit($data, $data_info['prh_create_time'], $controllerName);
        $data['pms_project_handover_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_handover");
$form= view('project_handover.form_popup_pms_project_handover', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_handover'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_handover.editable_list_pms_project_handover', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_handover'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_handover");
        $data['action_mode']="create";
        return view('project_handover.form_pms_project_handover', $data);
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
        'prh_project_id'=> trans('form_lang.prh_project_id'), 
'prh_handover_date_ec'=> trans('form_lang.prh_handover_date_ec'), 
'prh_handover_date_gc'=> trans('form_lang.prh_handover_date_gc'), 
'prh_description'=> trans('form_lang.prh_description'), 
'prh_status'=> trans('form_lang.prh_status'), 

    ];
    $rules= [
        'prh_project_id'=> 'max:200', 
'prh_handover_date_ec'=> 'max:200', 
'prh_handover_date_gc'=> 'max:200', 
'prh_description'=> 'max:425', 
'prh_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prh_created_by']=auth()->user()->usr_Id;
        Modelpmsprojecthandover::create($requestData);
        return redirect('project_handover')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_handover/create')
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
        $query='SELECT prh_id,prh_project_id,prh_handover_date_ec,prh_handover_date_gc,prh_description,prh_create_time,prh_update_time,prh_delete_time,prh_created_by,prh_status FROM pms_project_handover ';       
        
        $query .=' WHERE prh_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_handover_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojecthandover::findOrFail($id);
        //$data['pms_project_handover_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_handover");
        return view('project_handover.show_pms_project_handover', $data);
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
        
        
        $data_info = Modelpmsprojecthandover::find($id);
        $data['pms_project_handover_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_handover");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_handover.form_pms_project_handover', $data);
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
        'prh_project_id'=> trans('form_lang.prh_project_id'), 
'prh_handover_date_ec'=> trans('form_lang.prh_handover_date_ec'), 
'prh_handover_date_gc'=> trans('form_lang.prh_handover_date_gc'), 
'prh_description'=> trans('form_lang.prh_description'), 
'prh_status'=> trans('form_lang.prh_status'), 

    ];
    $rules= [
        'prh_project_id'=> 'max:200', 
'prh_handover_date_ec'=> 'max:200', 
'prh_handover_date_gc'=> 'max:200', 
'prh_description'=> 'max:425', 
'prh_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojecthandover::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_handover')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_handover/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_handover/'.$id.'/edit')
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
        Modelpmsprojecthandover::destroy($id);
        return redirect('project_handover')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT prj_name,prj_code, prh_id,prh_project_id,prh_handover_date_ec,prh_handover_date_gc,prh_description,prh_create_time,prh_update_time,prh_delete_time,prh_created_by,prh_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_handover ';       
      $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_handover.prh_project_id';
     $query .=' WHERE 1=1';
 $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('handover_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prh_handover_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('handover_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prh_handover_date_gc <='".$endTime." 23 59 59'"; 
}
$prhprojectid=$request->input('prh_project_id');
if(isset($prhprojectid) && isset($prhprojectid)){
$query .=" AND prh_project_id='".$prhprojectid."'"; 
}
$prhhandoverdateec=$request->input('prh_handover_date_ec');
if(isset($prhhandoverdateec) && isset($prhhandoverdateec)){
$query .=' AND prh_handover_date_ec="'.$prhhandoverdateec.'"'; 
}
$prhhandoverdategc=$request->input('prh_handover_date_gc');
if(isset($prhhandoverdategc) && isset($prhhandoverdategc)){
$query .=' AND prh_handover_date_gc="'.$prhhandoverdategc.'"'; 
}
$prhdescription=$request->input('prh_description');
if(isset($prhdescription) && isset($prhdescription)){
$query .=' AND prh_description="'.$prhdescription.'"'; 
}
$prhcreatetime=$request->input('prh_create_time');
if(isset($prhcreatetime) && isset($prhcreatetime)){
$query .=' AND prh_create_time="'.$prhcreatetime.'"'; 
}
$prhupdatetime=$request->input('prh_update_time');
if(isset($prhupdatetime) && isset($prhupdatetime)){
$query .=' AND prh_update_time="'.$prhupdatetime.'"'; 
}
$prhdeletetime=$request->input('prh_delete_time');
if(isset($prhdeletetime) && isset($prhdeletetime)){
$query .=' AND prh_delete_time="'.$prhdeletetime.'"'; 
}
$prhcreatedby=$request->input('prh_created_by');
if(isset($prhcreatedby) && isset($prhcreatedby)){
$query .=' AND prh_created_by="'.$prhcreatedby.'"'; 
}
$prhstatus=$request->input('prh_status');
if(isset($prhstatus) && isset($prhstatus)){
$query .=' AND prh_status="'.$prhstatus.'"'; 
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
        'prh_project_id'=> trans('form_lang.prh_project_id'), 
'prh_handover_date_ec'=> trans('form_lang.prh_handover_date_ec'), 
'prh_handover_date_gc'=> trans('form_lang.prh_handover_date_gc'), 
'prh_description'=> trans('form_lang.prh_description'), 

    ];
    $rules= [
        'prh_project_id'=> 'max:200', 
'prh_handover_date_ec'=> 'max:200', 
'prh_handover_date_gc'=> 'max:200', 
'prh_description'=> 'max:425'
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
        $id=$request->get("prh_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prh_status');
        if($status=="true"){
            $requestData['prh_status']=1;
        }else{
            $requestData['prh_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojecthandover::findOrFail($id);
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
        //$requestData['prh_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojecthandover::create($requestData);
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
        'prh_project_id'=> trans('form_lang.prh_project_id'), 
'prh_handover_date_ec'=> trans('form_lang.prh_handover_date_ec'), 
'prh_handover_date_gc'=> trans('form_lang.prh_handover_date_gc'), 
'prh_description'=> trans('form_lang.prh_description'), 

    ];
    $rules= [
        'prh_project_id'=> 'max:200', 
'prh_handover_date_ec'=> 'max:200', 
'prh_handover_date_gc'=> 'max:200', 
'prh_description'=> 'max:425', 

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
        //$requestData['prh_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prh_status');
        if($status=="true"){
            $requestData['prh_status']=1;
        }else{
            $requestData['prh_status']=0;
        }
        $data_info=Modelpmsprojecthandover::create($requestData);
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
    $id=$request->get("prh_id");
    Modelpmsprojecthandover::destroy($id);
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
    Route::resource('project_handover', 'PmsprojecthandoverController');
    Route::post('project_handover/listgrid', 'Api\PmsprojecthandoverController@listgrid');
    Route::post('project_handover/insertgrid', 'Api\PmsprojecthandoverController@insertgrid');
    Route::post('project_handover/updategrid', 'Api\PmsprojecthandoverController@updategrid');
    Route::post('project_handover/deletegrid', 'Api\PmsprojecthandoverController@deletegrid');
    Route::post('project_handover/search', 'PmsprojecthandoverController@search');
    Route::post('project_handover/getform', 'PmsprojecthandoverController@getForm');
    Route::post('project_handover/getlistform', 'PmsprojecthandoverController@getListForm');

}
}