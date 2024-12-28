<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectstatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectstatusController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_status');
    $dataInfo = Modelpmsprojectstatus::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_status_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_status");
    return view('project_status.list_pms_project_status', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectstatus::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectstatusController";
        $data= $this->validateEdit($data, $data_info['prs_create_time'], $controllerName);
        $data['pms_project_status_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_status");
$form= view('project_status.form_popup_pms_project_status', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_status'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_status.editable_list_pms_project_status', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_status'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_status");
        $data['action_mode']="create";
        return view('project_status.form_pms_project_status', $data);
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
        'prs_status_name_or'=> trans('form_lang.prs_status_name_or'), 
'prs_status_name_am'=> trans('form_lang.prs_status_name_am'), 
'prs_status_name_en'=> trans('form_lang.prs_status_name_en'), 
'prs_color_code'=> trans('form_lang.prs_color_code'), 
'prs_order_number'=> trans('form_lang.prs_order_number'), 
'prs_description'=> trans('form_lang.prs_description'), 
'prs_status'=> trans('form_lang.prs_status'), 
'prs_spare_column'=> trans('form_lang.prs_spare_column'), 

    ];
    $rules= [
        'prs_status_name_or'=> 'max:200', 
'prs_status_name_am'=> 'max:200', 
'prs_status_name_en'=> 'max:200', 
'prs_color_code'=> 'max:10', 
'prs_order_number'=> 'max:200', 
'prs_description'=> 'max:425', 
'prs_status'=> 'integer', 
'prs_spare_column'=> 'max:50', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prs_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectstatus::create($requestData);
        return redirect('project_status')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_status/create')
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
        $query='SELECT prs_id,prs_status_name_or,prs_status_name_am,prs_status_name_en,prs_color_code,prs_order_number,prs_description,prs_create_time,prs_update_time,prs_delete_time,prs_created_by,prs_status,prs_spare_column FROM pms_project_status ';       
        
        $query .=' WHERE prs_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_status_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectstatus::findOrFail($id);
        //$data['pms_project_status_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_status");
        return view('project_status.show_pms_project_status', $data);
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
        
        
        $data_info = Modelpmsprojectstatus::find($id);
        $data['pms_project_status_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_status");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_status.form_pms_project_status', $data);
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
        'prs_status_name_or'=> trans('form_lang.prs_status_name_or'), 
'prs_status_name_am'=> trans('form_lang.prs_status_name_am'), 
'prs_status_name_en'=> trans('form_lang.prs_status_name_en'), 
'prs_color_code'=> trans('form_lang.prs_color_code'), 
'prs_order_number'=> trans('form_lang.prs_order_number'), 
'prs_description'=> trans('form_lang.prs_description'), 
'prs_status'=> trans('form_lang.prs_status'), 
'prs_spare_column'=> trans('form_lang.prs_spare_column'), 

    ];
    $rules= [
        'prs_status_name_or'=> 'max:200', 
'prs_status_name_am'=> 'max:200', 
'prs_status_name_en'=> 'max:200', 
'prs_color_code'=> 'max:10', 
'prs_order_number'=> 'max:200', 
'prs_description'=> 'max:425', 
'prs_status'=> 'integer', 
'prs_spare_column'=> 'max:50', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectstatus::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_status')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_status/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_status/'.$id.'/edit')
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
        Modelpmsprojectstatus::destroy($id);
        return redirect('project_status')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT prs_id,prs_status_name_or,prs_status_name_am,prs_status_name_en,prs_color_code,prs_order_number,prs_description,prs_create_time,prs_update_time,prs_delete_time,prs_created_by,prs_status,prs_spare_column,1 AS is_editable, 1 AS is_deletable FROM pms_project_status ';       
     
     $query .=' WHERE 1=1';
     $prsid=$request->input('prs_id');
if(isset($prsid) && isset($prsid)){
$query .=' AND prs_id="'.$prsid.'"'; 
}
$prsstatusnameor=$request->input('prs_status_name_or');
if(isset($prsstatusnameor) && isset($prsstatusnameor)){
$query .=" AND prs_status_name_or LIKE '%".$prsstatusnameor."%'"; 

}
$prsstatusnameam=$request->input('prs_status_name_am');
if(isset($prsstatusnameam) && isset($prsstatusnameam)){
$query .=' AND prs_status_name_am="'.$prsstatusnameam.'"'; 
}
$prsstatusnameen=$request->input('prs_status_name_en');
if(isset($prsstatusnameen) && isset($prsstatusnameen)){
$query .=' AND prs_status_name_en="'.$prsstatusnameen.'"'; 
}
$prscolorcode=$request->input('prs_color_code');
if(isset($prscolorcode) && isset($prscolorcode)){
$query .=' AND prs_color_code="'.$prscolorcode.'"'; 
}
$prsordernumber=$request->input('prs_order_number');
if(isset($prsordernumber) && isset($prsordernumber)){
$query .=' AND prs_order_number="'.$prsordernumber.'"'; 
}
$prsdescription=$request->input('prs_description');
if(isset($prsdescription) && isset($prsdescription)){
$query .=' AND prs_description="'.$prsdescription.'"'; 
}
$prscreatetime=$request->input('prs_create_time');
if(isset($prscreatetime) && isset($prscreatetime)){
$query .=' AND prs_create_time="'.$prscreatetime.'"'; 
}
$prsupdatetime=$request->input('prs_update_time');
if(isset($prsupdatetime) && isset($prsupdatetime)){
$query .=' AND prs_update_time="'.$prsupdatetime.'"'; 
}
$prsdeletetime=$request->input('prs_delete_time');
if(isset($prsdeletetime) && isset($prsdeletetime)){
$query .=' AND prs_delete_time="'.$prsdeletetime.'"'; 
}
$prscreatedby=$request->input('prs_created_by');
if(isset($prscreatedby) && isset($prscreatedby)){
$query .=' AND prs_created_by="'.$prscreatedby.'"'; 
}
$prsstatus=$request->input('prs_status');
if(isset($prsstatus) && isset($prsstatus)){
$query .=' AND prs_status="'.$prsstatus.'"'; 
}
$prssparecolumn=$request->input('prs_spare_column');
if(isset($prssparecolumn) && isset($prssparecolumn)){
$query .=' AND prs_spare_column="'.$prssparecolumn.'"'; 
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
        'prs_status_name_or'=> trans('form_lang.prs_status_name_or'), 
'prs_status_name_am'=> trans('form_lang.prs_status_name_am'), 
'prs_status_name_en'=> trans('form_lang.prs_status_name_en'), 
'prs_color_code'=> trans('form_lang.prs_color_code'), 
'prs_order_number'=> trans('form_lang.prs_order_number'), 
'prs_description'=> trans('form_lang.prs_description'), 
'prs_status'=> trans('form_lang.prs_status'), 
'prs_spare_column'=> trans('form_lang.prs_spare_column'), 

    ];
    $rules= [
        'prs_status_name_or'=> 'max:100', 
'prs_status_name_am'=> 'max:100', 
'prs_status_name_en'=> 'max:100', 
'prs_color_code'=> 'max:20', 
'prs_order_number'=> 'max:20', 
'prs_description'=> 'max:425', 

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
        $id=$request->get("prs_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prs_status');
        if($status=="true"){
            $requestData['prs_status']=1;
        }else{
            $requestData['prs_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectstatus::findOrFail($id);
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
        //$requestData['prs_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectstatus::create($requestData);
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
        'prs_status_name_or'=> trans('form_lang.prs_status_name_or'), 
'prs_status_name_am'=> trans('form_lang.prs_status_name_am'), 
'prs_status_name_en'=> trans('form_lang.prs_status_name_en'), 
'prs_color_code'=> trans('form_lang.prs_color_code'), 
'prs_order_number'=> trans('form_lang.prs_order_number'), 
'prs_description'=> trans('form_lang.prs_description'), 
'prs_status'=> trans('form_lang.prs_status'), 
'prs_spare_column'=> trans('form_lang.prs_spare_column'), 

    ];
    $rules= [
 'prs_status_name_or'=> 'max:100', 
'prs_status_name_am'=> 'max:100', 
'prs_status_name_en'=> 'max:100', 
'prs_color_code'=> 'max:20', 
'prs_order_number'=> 'max:20', 
'prs_description'=> 'max:425', 

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
        //$requestData['prs_created_by']=auth()->user()->usr_Id;
        $requestData['prs_created_by']=1;
        $status= $request->input('prs_status');
        if($status=="true"){
            $requestData['prs_status']=1;
        }else{
            $requestData['prs_status']=0;
        }
        $data_info=Modelpmsprojectstatus::create($requestData);
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
    $id=$request->get("prs_id");
    Modelpmsprojectstatus::destroy($id);
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
    Route::resource('project_status', 'PmsprojectstatusController');
    Route::post('project_status/listgrid', 'Api\PmsprojectstatusController@listgrid');
    Route::post('project_status/insertgrid', 'Api\PmsprojectstatusController@insertgrid');
    Route::post('project_status/updategrid', 'Api\PmsprojectstatusController@updategrid');
    Route::post('project_status/deletegrid', 'Api\PmsprojectstatusController@deletegrid');
    Route::post('project_status/search', 'PmsprojectstatusController@search');
    Route::post('project_status/getform', 'PmsprojectstatusController@getForm');
    Route::post('project_status/getlistform', 'PmsprojectstatusController@getListForm');

}
}