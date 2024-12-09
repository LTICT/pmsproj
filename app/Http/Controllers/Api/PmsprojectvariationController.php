<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modelpmsprojectvariation;
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
        $data= $this->validateEdit($data, $data_info['bdr_create_time'], $controllerName);
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
        $query='SELECT bdr_id,bdr_requested_amount,bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,bdr_update_time,bdr_delete_time,bdr_created_by,bdr_status FROM pms_project_variation ';       
        
        $query .=' WHERE bdr_id='.$id.' ';
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
     $query='SELECT bdr_id,bdr_requested_amount,bdr_released_amount,bdr_project_id,bdr_requested_date_ec,bdr_requested_date_gc,bdr_released_date_ec,bdr_released_date_gc,bdr_description,bdr_create_time,bdr_update_time,bdr_delete_time,bdr_created_by,bdr_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_variation ';       
     
     $query .=' WHERE 1=1';
     $bdrid=$request->input('bdr_id');
if(isset($bdrid) && isset($bdrid)){
$query .=' AND bdr_id="'.$bdrid.'"'; 
}
$bdrrequestedamount=$request->input('bdr_requested_amount');
if(isset($bdrrequestedamount) && isset($bdrrequestedamount)){
$query .=' AND bdr_requested_amount="'.$bdrrequestedamount.'"'; 
}
$bdrreleasedamount=$request->input('bdr_released_amount');
if(isset($bdrreleasedamount) && isset($bdrreleasedamount)){
$query .=' AND bdr_released_amount="'.$bdrreleasedamount.'"'; 
}
$bdrprojectid=$request->input('bdr_project_id');
if(isset($bdrprojectid) && isset($bdrprojectid)){
$query .=' AND bdr_project_id="'.$bdrprojectid.'"'; 
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
        'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description')
    ];
    $rules= [
        'bdr_requested_amount'=> 'max:200', 
'bdr_released_amount'=> 'numeric', 
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
        //$requestData['bdr_created_by']=auth()->user()->usr_Id;
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
        'bdr_requested_amount'=> trans('form_lang.bdr_requested_amount'), 
'bdr_released_amount'=> trans('form_lang.bdr_released_amount'), 
'bdr_project_id'=> trans('form_lang.bdr_project_id'), 
'bdr_requested_date_ec'=> trans('form_lang.bdr_requested_date_ec'), 
'bdr_requested_date_gc'=> trans('form_lang.bdr_requested_date_gc'), 
'bdr_released_date_ec'=> trans('form_lang.bdr_released_date_ec'), 
'bdr_released_date_gc'=> trans('form_lang.bdr_released_date_gc'), 
'bdr_description'=> trans('form_lang.bdr_description'), 

    ];
    $rules= [
        'bdr_requested_amount'=> 'max:200', 
'bdr_released_amount'=> 'numeric', 
'bdr_project_id'=> 'max:200', 
'bdr_requested_date_ec'=> 'max:200', 
'bdr_requested_date_gc'=> 'max:200', 
'bdr_released_date_ec'=> 'max:10', 
'bdr_released_date_gc'=> 'max:10', 
'bdr_description'=> 'max:425'
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
        $status= $request->input('bdr_status');
        if($status=="true"){
            $requestData['bdr_status']=1;
        }else{
            $requestData['bdr_status']=0;
        }
        $data_info=Modelpmsprojectvariation::create($requestData);
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
    Modelpmsprojectvariation::destroy($id);
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