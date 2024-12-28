<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectvariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectvariationController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_variation');
    $dataInfo = Modelpmsprojectvariation::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_variation_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_variation");
    return view('project_variation.list_pms_project_variation', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectvariation::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectvariationController";
        $data= $this->validateEdit($data, $data_info['prv_create_time'], $controllerName);
        $data['pms_project_variation_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_variation");
$form= view('project_variation.form_popup_pms_project_variation', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_variation'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_variation.editable_list_pms_project_variation', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_variation'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_variation");
        $data['action_mode']="create";
        return view('project_variation.form_pms_project_variation', $data);
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
        'prv_requested_amount'=> trans('form_lang.prv_requested_amount'), 
'prv_released_amount'=> trans('form_lang.prv_released_amount'), 
'prv_project_id'=> trans('form_lang.prv_project_id'), 
'prv_requested_date_ec'=> trans('form_lang.prv_requested_date_ec'), 
'prv_requested_date_gc'=> trans('form_lang.prv_requested_date_gc'), 
'prv_released_date_ec'=> trans('form_lang.prv_released_date_ec'), 
'prv_released_date_gc'=> trans('form_lang.prv_released_date_gc'), 
'prv_description'=> trans('form_lang.prv_description'), 
'prv_status'=> trans('form_lang.prv_status'), 

    ];
    $rules= [
        'prv_requested_amount'=> 'max:200', 
'prv_released_amount'=> 'numeric', 
'prv_project_id'=> 'max:200', 
'prv_requested_date_ec'=> 'max:200', 
'prv_requested_date_gc'=> 'max:200', 
'prv_released_date_ec'=> 'max:10', 
'prv_released_date_gc'=> 'max:10', 
'prv_description'=> 'max:425', 
'prv_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prv_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectvariation::create($requestData);
        return redirect('project_variation')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_variation/create')
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
        $query='SELECT prv_id,prv_requested_amount,prv_released_amount,prv_project_id,prv_requested_date_ec,prv_requested_date_gc,prv_released_date_ec,prv_released_date_gc,prv_description,prv_create_time,prv_update_time,prv_delete_time,prv_created_by,prv_status FROM pms_project_variation ';       
        
        $query .=' WHERE prv_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_variation_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectvariation::findOrFail($id);
        //$data['pms_project_variation_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_variation");
        return view('project_variation.show_pms_project_variation', $data);
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
        
        
        $data_info = Modelpmsprojectvariation::find($id);
        $data['pms_project_variation_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_variation");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_variation.form_pms_project_variation', $data);
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
        'prv_requested_amount'=> trans('form_lang.prv_requested_amount'), 
'prv_released_amount'=> trans('form_lang.prv_released_amount'), 
'prv_project_id'=> trans('form_lang.prv_project_id'), 
'prv_requested_date_ec'=> trans('form_lang.prv_requested_date_ec'), 
'prv_requested_date_gc'=> trans('form_lang.prv_requested_date_gc'), 
'prv_released_date_ec'=> trans('form_lang.prv_released_date_ec'), 
'prv_released_date_gc'=> trans('form_lang.prv_released_date_gc'), 
'prv_description'=> trans('form_lang.prv_description'), 
'prv_status'=> trans('form_lang.prv_status'), 

    ];
    $rules= [
        'prv_requested_amount'=> 'max:200', 
'prv_released_amount'=> 'numeric', 
'prv_project_id'=> 'max:200', 
'prv_requested_date_ec'=> 'max:200', 
'prv_requested_date_gc'=> 'max:200', 
'prv_released_date_ec'=> 'max:10', 
'prv_released_date_gc'=> 'max:10', 
'prv_description'=> 'max:425', 
'prv_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectvariation::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_variation')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_variation/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_variation/'.$id.'/edit')
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
        Modelpmsprojectvariation::destroy($id);
        return redirect('project_variation')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT prj_name,prj_code,prv_id,prv_requested_amount,prv_released_amount,prv_project_id,prv_requested_date_ec,prv_requested_date_gc,prv_released_date_ec,prv_released_date_gc,prv_description,prv_create_time,prv_update_time,prv_delete_time,prv_created_by,prv_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_variation ';       
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_variation.prv_project_id';      
     $query .=' WHERE 1=1';
$prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$startTime=$request->input('variation_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prv_released_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('variation_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prv_released_date_gc <='".$endTime." 23 59 59'"; 
}
$prvprojectid=$request->input('prv_project_id');
if(isset($prvprojectid) && isset($prvprojectid)){
$query .=" AND prv_project_id='".$prvprojectid."'"; 
}
$prvrequesteddateec=$request->input('prv_requested_date_ec');
if(isset($prvrequesteddateec) && isset($prvrequesteddateec)){
$query .=' AND prv_requested_date_ec="'.$prvrequesteddateec.'"'; 
}
$prvrequesteddategc=$request->input('prv_requested_date_gc');
if(isset($prvrequesteddategc) && isset($prvrequesteddategc)){
$query .=' AND prv_requested_date_gc="'.$prvrequesteddategc.'"'; 
}
$prvreleaseddateec=$request->input('prv_released_date_ec');
if(isset($prvreleaseddateec) && isset($prvreleaseddateec)){
$query .=' AND prv_released_date_ec="'.$prvreleaseddateec.'"'; 
}
$prvreleaseddategc=$request->input('prv_released_date_gc');
if(isset($prvreleaseddategc) && isset($prvreleaseddategc)){
$query .=' AND prv_released_date_gc="'.$prvreleaseddategc.'"'; 
}
$prvdescription=$request->input('prv_description');
if(isset($prvdescription) && isset($prvdescription)){
$query .=' AND prv_description="'.$prvdescription.'"'; 
}
$prvcreatetime=$request->input('prv_create_time');
if(isset($prvcreatetime) && isset($prvcreatetime)){
$query .=' AND prv_create_time="'.$prvcreatetime.'"'; 
}
$prvupdatetime=$request->input('prv_update_time');
if(isset($prvupdatetime) && isset($prvupdatetime)){
$query .=' AND prv_update_time="'.$prvupdatetime.'"'; 
}
$prvdeletetime=$request->input('prv_delete_time');
if(isset($prvdeletetime) && isset($prvdeletetime)){
$query .=' AND prv_delete_time="'.$prvdeletetime.'"'; 
}
$prvcreatedby=$request->input('prv_created_by');
if(isset($prvcreatedby) && isset($prvcreatedby)){
$query .=' AND prv_created_by="'.$prvcreatedby.'"'; 
}
$prvstatus=$request->input('prv_status');
if(isset($prvstatus) && isset($prvstatus)){
$query .=' AND prv_status="'.$prvstatus.'"'; 
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
        'prv_requested_amount'=> trans('form_lang.prv_requested_amount'), 
'prv_released_amount'=> trans('form_lang.prv_released_amount'), 
'prv_project_id'=> trans('form_lang.prv_project_id'), 
'prv_requested_date_ec'=> trans('form_lang.prv_requested_date_ec'), 
'prv_requested_date_gc'=> trans('form_lang.prv_requested_date_gc'), 
'prv_released_date_ec'=> trans('form_lang.prv_released_date_ec'), 
'prv_released_date_gc'=> trans('form_lang.prv_released_date_gc'), 
'prv_description'=> trans('form_lang.prv_description')
    ];
    $rules= [
        'prv_requested_amount'=> 'max:200', 
'prv_released_amount'=> 'numeric', 
'prv_project_id'=> 'max:200', 
'prv_requested_date_ec'=> 'max:200', 
'prv_requested_date_gc'=> 'max:200', 
'prv_released_date_ec'=> 'max:10', 
'prv_released_date_gc'=> 'max:10', 
'prv_description'=> 'max:425', 
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
        $id=$request->get("prv_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prv_status');
        if($status=="true"){
            $requestData['prv_status']=1;
        }else{
            $requestData['prv_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectvariation::findOrFail($id);
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
        //$requestData['prv_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectvariation::create($requestData);
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
        'prv_requested_amount'=> trans('form_lang.prv_requested_amount'), 
'prv_released_amount'=> trans('form_lang.prv_released_amount'), 
'prv_project_id'=> trans('form_lang.prv_project_id'), 
'prv_requested_date_ec'=> trans('form_lang.prv_requested_date_ec'), 
'prv_requested_date_gc'=> trans('form_lang.prv_requested_date_gc'), 
'prv_released_date_ec'=> trans('form_lang.prv_released_date_ec'), 
'prv_released_date_gc'=> trans('form_lang.prv_released_date_gc'), 
'prv_description'=> trans('form_lang.prv_description'), 

    ];
    $rules= [
        'prv_requested_amount'=> 'max:200', 
'prv_released_amount'=> 'numeric', 
'prv_project_id'=> 'max:200', 
'prv_requested_date_ec'=> 'max:200', 
'prv_requested_date_gc'=> 'max:200', 
'prv_released_date_ec'=> 'max:10', 
'prv_released_date_gc'=> 'max:10', 
'prv_description'=> 'max:425'
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
        //$requestData['prv_created_by']=auth()->user()->usr_Id;
        $status= $request->input('prv_status');
        if($status=="true"){
            $requestData['prv_status']=1;
        }else{
            $requestData['prv_status']=0;
        }
        $data_info=Modelpmsprojectvariation::create($requestData);
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
    $id=$request->get("prv_id");
    Modelpmsprojectvariation::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "deleted_id"=>$id,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
function listRoutes(){
    Route::resource('project_variation', 'PmsprojectvariationController');
    Route::post('project_variation/listgrid', 'Api\PmsprojectvariationController@listgrid');
    Route::post('project_variation/insertgrid', 'Api\PmsprojectvariationController@insertgrid');
    Route::post('project_variation/updategrid', 'Api\PmsprojectvariationController@updategrid');
    Route::post('project_variation/deletegrid', 'Api\PmsprojectvariationController@deletegrid');
    Route::post('project_variation/search', 'PmsprojectvariationController@search');
    Route::post('project_variation/getform', 'PmsprojectvariationController@getForm');
    Route::post('project_variation/getlistform', 'PmsprojectvariationController@getListForm');

}
}