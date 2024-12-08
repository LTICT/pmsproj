<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetsource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetsourceController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_budget_source');
    $dataInfo = Modelpmsbudgetsource::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_budget_source_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_budget_source");
    return view('budget_source.list_pms_budget_source', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsbudgetsource::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsbudgetsourceController";
        $data= $this->validateEdit($data, $data_info['pbs_create_time'], $controllerName);
        $data['pms_budget_source_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_budget_source");
$form= view('budget_source.form_popup_pms_budget_source', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_budget_source'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('budget_source.editable_list_pms_budget_source', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_budget_source'));
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
        
        
        $data['page_title']=trans("form_lang.pms_budget_source");
        $data['action_mode']="create";
        return view('budget_source.form_pms_budget_source', $data);
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
        'pbs_name_or'=> trans('form_lang.pbs_name_or'), 
'pbs_name_am'=> trans('form_lang.pbs_name_am'), 
'pbs_name_en'=> trans('form_lang.pbs_name_en'), 
'pbs_code'=> trans('form_lang.pbs_code'), 
'pbs_description'=> trans('form_lang.pbs_description'), 
'pbs_status'=> trans('form_lang.pbs_status'), 

    ];
    $rules= [
        'pbs_name_or'=> 'max:200', 
'pbs_name_am'=> 'max:100', 
'pbs_name_en'=> 'max:100', 
'pbs_code'=> 'max:10', 
'pbs_description'=> 'max:425', 
'pbs_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pbs_created_by']=auth()->user()->usr_Id;
        Modelpmsbudgetsource::create($requestData);
        return redirect('budget_source')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('budget_source/create')
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
        $query='SELECT pbs_id,pbs_name_or,pbs_name_am,pbs_name_en,pbs_code,pbs_description,pbs_create_time,pbs_update_time,pbs_delete_time,pbs_created_by,pbs_status FROM pms_budget_source ';       
        
        $query .=' WHERE pbs_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_source_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetsource::findOrFail($id);
        //$data['pms_budget_source_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_source");
        return view('budget_source.show_pms_budget_source', $data);
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
        
        
        $data_info = Modelpmsbudgetsource::find($id);
        $data['pms_budget_source_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_source");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('budget_source.form_pms_budget_source', $data);
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
        'pbs_name_or'=> trans('form_lang.pbs_name_or'), 
'pbs_name_am'=> trans('form_lang.pbs_name_am'), 
'pbs_name_en'=> trans('form_lang.pbs_name_en'), 
'pbs_code'=> trans('form_lang.pbs_code'), 
'pbs_description'=> trans('form_lang.pbs_description'), 
'pbs_status'=> trans('form_lang.pbs_status'), 

    ];
    $rules= [
        'pbs_name_or'=> 'max:200', 
'pbs_name_am'=> 'max:100', 
'pbs_name_en'=> 'max:100', 
'pbs_code'=> 'max:10', 
'pbs_description'=> 'max:425', 
'pbs_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsbudgetsource::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('budget_source')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('budget_source/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('budget_source/'.$id.'/edit')
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
        Modelpmsbudgetsource::destroy($id);
        return redirect('budget_source')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT pbs_id,pbs_name_or,pbs_name_am,pbs_name_en,pbs_code,pbs_description,pbs_create_time,pbs_update_time,pbs_delete_time,pbs_created_by,pbs_status,1 AS is_editable, 1 AS is_deletable FROM pms_budget_source ';       
     
     $query .=' WHERE 1=1';
     $pbsid=$request->input('pbs_id');
if(isset($pbsid) && isset($pbsid)){
$query .=' AND pbs_id="'.$pbsid.'"'; 
}
$pbsnameor=$request->input('pbs_name_or');
if(isset($pbsnameor) && isset($pbsnameor)){
$query .=' AND pbs_name_or="'.$pbsnameor.'"'; 
}
$pbsnameam=$request->input('pbs_name_am');
if(isset($pbsnameam) && isset($pbsnameam)){
$query .=' AND pbs_name_am="'.$pbsnameam.'"'; 
}
$pbsnameen=$request->input('pbs_name_en');
if(isset($pbsnameen) && isset($pbsnameen)){
$query .=' AND pbs_name_en="'.$pbsnameen.'"'; 
}
$pbscode=$request->input('pbs_code');
if(isset($pbscode) && isset($pbscode)){
$query .=' AND pbs_code="'.$pbscode.'"'; 
}
$pbsdescription=$request->input('pbs_description');
if(isset($pbsdescription) && isset($pbsdescription)){
$query .=' AND pbs_description="'.$pbsdescription.'"'; 
}
$pbscreatetime=$request->input('pbs_create_time');
if(isset($pbscreatetime) && isset($pbscreatetime)){
$query .=' AND pbs_create_time="'.$pbscreatetime.'"'; 
}
$pbsupdatetime=$request->input('pbs_update_time');
if(isset($pbsupdatetime) && isset($pbsupdatetime)){
$query .=' AND pbs_update_time="'.$pbsupdatetime.'"'; 
}
$pbsdeletetime=$request->input('pbs_delete_time');
if(isset($pbsdeletetime) && isset($pbsdeletetime)){
$query .=' AND pbs_delete_time="'.$pbsdeletetime.'"'; 
}
$pbscreatedby=$request->input('pbs_created_by');
if(isset($pbscreatedby) && isset($pbscreatedby)){
$query .=' AND pbs_created_by="'.$pbscreatedby.'"'; 
}
$pbsstatus=$request->input('pbs_status');
if(isset($pbsstatus) && isset($pbsstatus)){
$query .=' AND pbs_status="'.$pbsstatus.'"'; 
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
        'pbs_name_or'=> trans('form_lang.pbs_name_or'), 
'pbs_name_am'=> trans('form_lang.pbs_name_am'), 
'pbs_name_en'=> trans('form_lang.pbs_name_en'), 
'pbs_code'=> trans('form_lang.pbs_code'), 
'pbs_description'=> trans('form_lang.pbs_description'), 
'pbs_status'=> trans('form_lang.pbs_status'), 

    ];
    $rules= [
        'pbs_name_or'=> 'max:200', 
'pbs_name_am'=> 'max:100', 
'pbs_name_en'=> 'max:100', 
'pbs_code'=> 'max:10', 
'pbs_description'=> 'max:425', 
//'pbs_status'=> 'integer', 

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
        $id=$request->get("pbs_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pbs_status');
        if($status=="true"){
            $requestData['pbs_status']=1;
        }else{
            $requestData['pbs_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetsource::findOrFail($id);
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
        //$requestData['pbs_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetsource::create($requestData);
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
        'pbs_name_or'=> trans('form_lang.pbs_name_or'), 
'pbs_name_am'=> trans('form_lang.pbs_name_am'), 
'pbs_name_en'=> trans('form_lang.pbs_name_en'), 
'pbs_code'=> trans('form_lang.pbs_code'), 
'pbs_description'=> trans('form_lang.pbs_description'), 
'pbs_status'=> trans('form_lang.pbs_status'), 

    ];
    $rules= [
        'pbs_name_or'=> 'max:200', 
'pbs_name_am'=> 'max:100', 
'pbs_name_en'=> 'max:100', 
'pbs_code'=> 'max:10', 
'pbs_description'=> 'max:425', 
//'pbs_status'=> 'integer', 

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
        //$requestData['pbs_created_by']=auth()->user()->usr_Id;
        $requestData['pbs_created_by']=2;
        $status= $request->input('pbs_status');
        if($status=="true"){
            $requestData['pbs_status']=1;
        }else{
            $requestData['pbs_status']=0;
        }
        $data_info=Modelpmsbudgetsource::create($requestData);
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
    $id=$request->get("pbs_id");
    Modelpmsbudgetsource::destroy($id);
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
    Route::resource('budget_source', 'PmsbudgetsourceController');
    Route::post('budget_source/listgrid', 'Api\PmsbudgetsourceController@listgrid');
    Route::post('budget_source/insertgrid', 'Api\PmsbudgetsourceController@insertgrid');
    Route::post('budget_source/updategrid', 'Api\PmsbudgetsourceController@updategrid');
    Route::post('budget_source/deletegrid', 'Api\PmsbudgetsourceController@deletegrid');
    Route::post('budget_source/search', 'PmsbudgetsourceController@search');
    Route::post('budget_source/getform', 'PmsbudgetsourceController@getForm');
    Route::post('budget_source/getlistform', 'PmsbudgetsourceController@getListForm');

}
}