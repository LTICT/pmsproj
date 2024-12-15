<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectbudgetsource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectbudgetsourceController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_budget_source');
    $dataInfo = Modelpmsprojectbudgetsource::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_budget_source_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_budget_source");
    return view('project_budget_source.list_pms_project_budget_source', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectbudgetsource::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectbudgetsourceController";
        $data= $this->validateEdit($data, $data_info['bsr_create_time'], $controllerName);
        $data['pms_project_budget_source_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_budget_source");
$form= view('project_budget_source.form_popup_pms_project_budget_source', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_budget_source'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_budget_source.editable_list_pms_project_budget_source', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_budget_source'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_budget_source");
        $data['action_mode']="create";
        return view('project_budget_source.form_pms_project_budget_source', $data);
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
        'bsr_name'=> trans('form_lang.bsr_name'), 
'bsr_project_id'=> trans('form_lang.bsr_project_id'), 
'bsr_budget_source_id'=> trans('form_lang.bsr_budget_source_id'), 
'bsr_amount'=> trans('form_lang.bsr_amount'), 
'bsr_status'=> trans('form_lang.bsr_status'), 
'bsr_description'=> trans('form_lang.bsr_description'), 
'bsr_created_date'=> trans('form_lang.bsr_created_date'), 

    ];
    $rules= [
        'bsr_name'=> 'max:200', 
'bsr_project_id'=> 'max:200', 
'bsr_budget_source_id'=> 'max:200', 
'bsr_amount'=> 'numeric', 
'bsr_status'=> 'integer', 
'bsr_description'=> 'max:100', 
'bsr_created_date'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['bsr_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectbudgetsource::create($requestData);
        return redirect('project_budget_source')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_budget_source/create')
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
        $query='SELECT bsr_id,bsr_name,bsr_project_id,bsr_budget_source_id,bsr_amount,bsr_status,bsr_description,bsr_created_by,bsr_created_date,bsr_create_time,bsr_update_time FROM pms_project_budget_source ';       
        
        $query .=' WHERE bsr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_budget_source_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectbudgetsource::findOrFail($id);
        //$data['pms_project_budget_source_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_budget_source");
        return view('project_budget_source.show_pms_project_budget_source', $data);
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
        
        
        $data_info = Modelpmsprojectbudgetsource::find($id);
        $data['pms_project_budget_source_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_budget_source");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_budget_source.form_pms_project_budget_source', $data);
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
        'bsr_name'=> trans('form_lang.bsr_name'), 
'bsr_project_id'=> trans('form_lang.bsr_project_id'), 
'bsr_budget_source_id'=> trans('form_lang.bsr_budget_source_id'), 
'bsr_amount'=> trans('form_lang.bsr_amount'), 
'bsr_status'=> trans('form_lang.bsr_status'), 
'bsr_description'=> trans('form_lang.bsr_description'), 
'bsr_created_date'=> trans('form_lang.bsr_created_date'), 

    ];
    $rules= [
        'bsr_name'=> 'max:200', 
'bsr_project_id'=> 'max:200', 
'bsr_budget_source_id'=> 'max:200', 
'bsr_amount'=> 'numeric', 
'bsr_status'=> 'integer', 
'bsr_description'=> 'max:100', 
'bsr_created_date'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectbudgetsource::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_budget_source')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_budget_source/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_budget_source/'.$id.'/edit')
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
        Modelpmsprojectbudgetsource::destroy($id);
        return redirect('project_budget_source')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT bsr_id,bsr_name,bsr_project_id,bsr_budget_source_id,bsr_amount,bsr_status,bsr_description,bsr_created_by,bsr_created_date,bsr_create_time,bsr_update_time,1 AS is_editable, 1 AS is_deletable FROM pms_project_budget_source ';
     $query .='INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_source.bsr_project_id';    
     
     $query .=' WHERE 1=1';
     $bsrid=$request->input('bsr_id');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}

$bsrname=$request->input('bsr_name');
if(isset($bsrname) && isset($bsrname)){
$query .=' AND bsr_name="'.$bsrname.'"'; 
}
$bsrprojectid=$request->input('bsr_project_id');
if(isset($bsrprojectid) && isset($bsrprojectid)){
$query .=" AND bsr_project_id='".$bsrprojectid."'"; 
}
$bsrbudgetsourceid=$request->input('bsr_budget_source_id');
if(isset($bsrbudgetsourceid) && isset($bsrbudgetsourceid)){
$query .=" AND bsr_budget_source_id='".$bsrbudgetsourceid."'"; 
}
$bsramount=$request->input('bsr_amount');
if(isset($bsramount) && isset($bsramount)){
$query .=' AND bsr_amount="'.$bsramount.'"'; 
}
$bsrstatus=$request->input('bsr_status');
if(isset($bsrstatus) && isset($bsrstatus)){
$query .=' AND bsr_status="'.$bsrstatus.'"'; 
}
$bsrdescription=$request->input('bsr_description');
if(isset($bsrdescription) && isset($bsrdescription)){
$query .=' AND bsr_description="'.$bsrdescription.'"'; 
}
$bsrcreatedby=$request->input('bsr_created_by');
if(isset($bsrcreatedby) && isset($bsrcreatedby)){
$query .=' AND bsr_created_by="'.$bsrcreatedby.'"'; 
}
$bsrcreateddate=$request->input('bsr_created_date');
if(isset($bsrcreateddate) && isset($bsrcreateddate)){
$query .=' AND bsr_created_date="'.$bsrcreateddate.'"'; 
}
$bsrcreatetime=$request->input('bsr_create_time');
if(isset($bsrcreatetime) && isset($bsrcreatetime)){
$query .=' AND bsr_create_time="'.$bsrcreatetime.'"'; 
}
$bsrupdatetime=$request->input('bsr_update_time');
if(isset($bsrupdatetime) && isset($bsrupdatetime)){
$query .=' AND bsr_update_time="'.$bsrupdatetime.'"'; 
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
        'bsr_name'=> trans('form_lang.bsr_name'), 
'bsr_project_id'=> trans('form_lang.bsr_project_id'), 
'bsr_budget_source_id'=> trans('form_lang.bsr_budget_source_id'), 
'bsr_amount'=> trans('form_lang.bsr_amount'), 
'bsr_status'=> trans('form_lang.bsr_status'), 
'bsr_description'=> trans('form_lang.bsr_description'), 
'bsr_created_date'=> trans('form_lang.bsr_created_date'), 

    ];
    $rules= [
         'bsr_name'=> 'max:200', 
//'bsr_project_id'=> 'max:200', 
'bsr_budget_source_id'=> 'max:200', 
'bsr_amount'=> 'numeric', 
//'bsr_status'=> 'integer', 
'bsr_description'=> 'max:100',
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
        $id=$request->get("bsr_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bsr_status');
        if($status=="true"){
            $requestData['bsr_status']=1;
        }else{
            $requestData['bsr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectbudgetsource::findOrFail($id);
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
        //$requestData['bsr_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectbudgetsource::create($requestData);
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
        'bsr_name'=> trans('form_lang.bsr_name'), 
'bsr_project_id'=> trans('form_lang.bsr_project_id'), 
'bsr_budget_source_id'=> trans('form_lang.bsr_budget_source_id'), 
'bsr_amount'=> trans('form_lang.bsr_amount'), 
'bsr_status'=> trans('form_lang.bsr_status'), 
'bsr_description'=> trans('form_lang.bsr_description'), 
'bsr_created_date'=> trans('form_lang.bsr_created_date'), 

    ];
    $rules= [
        'bsr_name'=> 'max:200', 
//'bsr_project_id'=> 'max:200', 
'bsr_budget_source_id'=> 'max:200', 
'bsr_amount'=> 'numeric', 
//'bsr_status'=> 'integer', 
'bsr_description'=> 'max:100',

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
        //$requestData['bsr_created_by']=auth()->user()->usr_Id;
        $status= $request->input('bsr_status');
        if($status=="true"){
            $requestData['bsr_status']=1;
        }else{
            $requestData['bsr_status']=0;
        }
        $data_info=Modelpmsprojectbudgetsource::create($requestData);
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
    $id=$request->get("bsr_id");
    Modelpmsprojectbudgetsource::destroy($id);
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
    Route::resource('project_budget_source', 'PmsprojectbudgetsourceController');
    Route::post('project_budget_source/listgrid', 'Api\PmsprojectbudgetsourceController@listgrid');
    Route::post('project_budget_source/insertgrid', 'Api\PmsprojectbudgetsourceController@insertgrid');
    Route::post('project_budget_source/updategrid', 'Api\PmsprojectbudgetsourceController@updategrid');
    Route::post('project_budget_source/deletegrid', 'Api\PmsprojectbudgetsourceController@deletegrid');
    Route::post('project_budget_source/search', 'PmsprojectbudgetsourceController@search');
    Route::post('project_budget_source/getform', 'PmsprojectbudgetsourceController@getForm');
    Route::post('project_budget_source/getlistform', 'PmsprojectbudgetsourceController@getListForm');

}
}