<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelprjsectorcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PrjsectorcategoryController extends MyController
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
    $searchParams= $this->getSearchSetting('prj_sector_category');
    $dataInfo = Modelprjsectorcategory::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['prj_sector_category_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.prj_sector_category");
    return view('sector_category.list_prj_sector_category', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelprjsectorcategory::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PrjsectorcategoryController";
        $data= $this->validateEdit($data, $data_info['psc_create_time'], $controllerName);
        $data['prj_sector_category_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.prj_sector_category");
$form= view('sector_category.form_popup_prj_sector_category', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.prj_sector_category'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('sector_category.editable_list_prj_sector_category', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.prj_sector_category'));
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
        
        
        $data['page_title']=trans("form_lang.prj_sector_category");
        $data['action_mode']="create";
        return view('sector_category.form_prj_sector_category', $data);
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
        'psc_status'=> trans('form_lang.psc_status'), 
'psc_id'=> trans('form_lang.psc_id'), 
'psc_name'=> trans('form_lang.psc_name'), 
'psc_code'=> trans('form_lang.psc_code'), 
'psc_sector_id'=> trans('form_lang.psc_sector_id'), 
'psc_description'=> trans('form_lang.psc_description'), 

    ];
    $rules= [
'psc_id'=> 'max:100', 
'psc_name'=> 'max:100', 
'psc_code'=> 'max:20', 
'psc_description'=> 'max:425', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['psc_created_by']=auth()->user()->usr_Id;
        Modelprjsectorcategory::create($requestData);
        return redirect('sector_category')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('sector_category/create')
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
        $query='SELECT psc_delete_time,psc_created_by,psc_status,psc_id,psc_name,psc_code,psc_sector_id,psc_description,psc_create_time,psc_update_time FROM prj_sector_category ';       
        
        $query .=' WHERE psc_delete_time='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['prj_sector_category_data']=$data_info[0];
        }
        //$data_info = Modelprjsectorcategory::findOrFail($id);
        //$data['prj_sector_category_data']=$data_info;
        $data['page_title']=trans("form_lang.prj_sector_category");
        return view('sector_category.show_prj_sector_category', $data);
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
        
        
        $data_info = Modelprjsectorcategory::find($id);
        $data['prj_sector_category_data']=$data_info;
        $data['page_title']=trans("form_lang.prj_sector_category");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('sector_category.form_prj_sector_category', $data);
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
        'psc_status'=> trans('form_lang.psc_status'), 
'psc_id'=> trans('form_lang.psc_id'), 
'psc_name'=> trans('form_lang.psc_name'), 
'psc_code'=> trans('form_lang.psc_code'), 
'psc_sector_id'=> trans('form_lang.psc_sector_id'), 
'psc_description'=> trans('form_lang.psc_description'), 

    ];
    $rules= [
        'psc_status'=> 'integer', 
'psc_id'=> 'max:200', 
'psc_name'=> 'max:200', 
'psc_code'=> 'max:30', 
'psc_sector_id'=> 'max:200', 
'psc_description'=> 'max:425', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelprjsectorcategory::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('sector_category')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('sector_category/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('sector_category/'.$id.'/edit')
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
        Modelprjsectorcategory::destroy($id);
        return redirect('sector_category')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT psc_delete_time,psc_created_by,psc_status,psc_id,psc_name,psc_code,psc_description,psc_create_time,psc_update_time,1 AS is_editable, 1 AS is_deletable FROM prj_sector_category ';       
     
     $query .=' WHERE 1=1';
     $pscdeletetime=$request->input('psc_delete_time');
if(isset($pscdeletetime) && isset($pscdeletetime)){
$query .=' AND psc_delete_time="'.$pscdeletetime.'"'; 
}
$psccreatedby=$request->input('psc_created_by');
if(isset($psccreatedby) && isset($psccreatedby)){
$query .=' AND psc_created_by="'.$psccreatedby.'"'; 
}
$pscstatus=$request->input('psc_status');
if(isset($pscstatus) && isset($pscstatus)){
$query .=' AND psc_status="'.$pscstatus.'"'; 
}
$pscid=$request->input('psc_id');
if(isset($pscid) && isset($pscid)){
$query .=' AND psc_id="'.$pscid.'"'; 
}
$pscname=$request->input('psc_name');
if(isset($pscname) && isset($pscname)){
$query .=" AND psc_name LIKE '%".$pscname."%'"; 
}
$psccode=$request->input('psc_code');
if(isset($psccode) && isset($psccode)){
$query .=' AND psc_code="'.$psccode.'"'; 
}
$pscsectorid=$request->input('psc_sector_id');
if(isset($pscsectorid) && isset($pscsectorid)){
$query .=' AND psc_sector_id="'.$pscsectorid.'"'; 
}
$pscdescription=$request->input('psc_description');
if(isset($pscdescription) && isset($pscdescription)){
$query .=' AND psc_description="'.$pscdescription.'"'; 
}
$psccreatetime=$request->input('psc_create_time');
if(isset($psccreatetime) && isset($psccreatetime)){
$query .=' AND psc_create_time="'.$psccreatetime.'"'; 
}
$pscupdatetime=$request->input('psc_update_time');
if(isset($pscupdatetime) && isset($pscupdatetime)){
$query .=' AND psc_update_time="'.$pscupdatetime.'"'; 
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
        'psc_status'=> trans('form_lang.psc_status'), 
'psc_id'=> trans('form_lang.psc_id'), 
'psc_name'=> trans('form_lang.psc_name'), 
'psc_code'=> trans('form_lang.psc_code'), 
'psc_sector_id'=> trans('form_lang.psc_sector_id'), 
'psc_description'=> trans('form_lang.psc_description'), 

    ];
    $rules= [
'psc_id'=> 'max:100', 
'psc_name'=> 'max:100', 
'psc_code'=> 'max:20', 
'psc_description'=> 'max:425', 

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
        $id=$request->get("psc_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('psc_status');
        if($status=="true"){
            $requestData['psc_status']=1;
        }else{
            $requestData['psc_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelprjsectorcategory::findOrFail($id);
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
        //$requestData['psc_created_by']=auth()->user()->usr_Id;
        $data_info=Modelprjsectorcategory::create($requestData);
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
        'psc_status'=> trans('form_lang.psc_status'), 
'psc_id'=> trans('form_lang.psc_id'), 
'psc_name'=> trans('form_lang.psc_name'), 
'psc_code'=> trans('form_lang.psc_code'), 
'psc_sector_id'=> trans('form_lang.psc_sector_id'), 
'psc_description'=> trans('form_lang.psc_description'), 

    ];
    $rules= [
   'psc_id'=> 'max:100', 
'psc_name'=> 'max:100', 
'psc_code'=> 'max:20', 
'psc_description'=> 'max:425', 

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
        //$requestData['psc_created_by']=auth()->user()->usr_Id;
        $requestData['psc_created_by']=1;
        $status= $request->input('psc_status');
        if($status=="true"){
            $requestData['psc_status']=1;
        }else{
            $requestData['psc_status']=0;
        }
        $data_info=Modelprjsectorcategory::create($requestData);
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
    $id=$request->get("psc_id");
    Modelprjsectorcategory::destroy($id);
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
    Route::resource('sector_category', 'PrjsectorcategoryController');
    Route::post('sector_category/listgrid', 'Api\PrjsectorcategoryController@listgrid');
    Route::post('sector_category/insertgrid', 'Api\PrjsectorcategoryController@insertgrid');
    Route::post('sector_category/updategrid', 'Api\PrjsectorcategoryController@updategrid');
    Route::post('sector_category/deletegrid', 'Api\PrjsectorcategoryController@deletegrid');
    Route::post('sector_category/search', 'PrjsectorcategoryController@search');
    Route::post('sector_category/getform', 'PrjsectorcategoryController@getForm');
    Route::post('sector_category/getlistform', 'PrjsectorcategoryController@getListForm');

}
}