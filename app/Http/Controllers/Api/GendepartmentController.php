<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgendepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GendepartmentController extends MyController
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
    $searchParams= $this->getSearchSetting('gen_department');
    $dataInfo = Modelgendepartment::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['gen_department_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.gen_department");
    return view('department.list_gen_department', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelgendepartment::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="GendepartmentController";
        $data= $this->validateEdit($data, $data_info['dep_create_time'], $controllerName);
        $data['gen_department_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.gen_department");
$form= view('department.form_popup_gen_department', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.gen_department'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('department.editable_list_gen_department', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.gen_department'));
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
        
        
        $data['page_title']=trans("form_lang.gen_department");
        $data['action_mode']="create";
        return view('department.form_gen_department', $data);
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
        'dep_name_or'=> trans('form_lang.dep_name_or'), 
'dep_name_am'=> trans('form_lang.dep_name_am'), 
'dep_name_en'=> trans('form_lang.dep_name_en'), 
'dep_code'=> trans('form_lang.dep_code'), 
'dep_available_at_region'=> trans('form_lang.dep_available_at_region'), 
'dep_available_at_zone'=> trans('form_lang.dep_available_at_zone'), 
'dep_available_at_woreda'=> trans('form_lang.dep_available_at_woreda'), 
'dep_description'=> trans('form_lang.dep_description'), 
'dep_status'=> trans('form_lang.dep_status'), 

    ];
    $rules= [
        'dep_name_or'=> 'max:200', 
'dep_name_am'=> 'max:100', 
'dep_name_en'=> 'max:100', 
'dep_code'=> 'max:10', 
'dep_available_at_region'=> 'integer', 
'dep_available_at_zone'=> 'integer', 
'dep_available_at_woreda'=> 'integer', 
'dep_description'=> 'max:425', 
'dep_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['dep_created_by']=auth()->user()->usr_Id;
        Modelgendepartment::create($requestData);
        return redirect('department')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('department/create')
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
        $query='SELECT dep_id,dep_name_or,dep_name_am,dep_name_en,dep_code,dep_available_at_region,dep_available_at_zone,dep_available_at_woreda,dep_description,dep_create_time,dep_update_time,dep_delete_time,dep_created_by,dep_status FROM gen_department ';       
        
        $query .=' WHERE dep_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['gen_department_data']=$data_info[0];
        }
        //$data_info = Modelgendepartment::findOrFail($id);
        //$data['gen_department_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_department");
        return view('department.show_gen_department', $data);
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
        
        
        $data_info = Modelgendepartment::find($id);
        $data['gen_department_data']=$data_info;
        $data['page_title']=trans("form_lang.gen_department");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('department.form_gen_department', $data);
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
        'dep_name_or'=> trans('form_lang.dep_name_or'), 
'dep_name_am'=> trans('form_lang.dep_name_am'), 
'dep_name_en'=> trans('form_lang.dep_name_en'), 
'dep_code'=> trans('form_lang.dep_code'), 
'dep_available_at_region'=> trans('form_lang.dep_available_at_region'), 
'dep_available_at_zone'=> trans('form_lang.dep_available_at_zone'), 
'dep_available_at_woreda'=> trans('form_lang.dep_available_at_woreda'), 
'dep_description'=> trans('form_lang.dep_description'), 
'dep_status'=> trans('form_lang.dep_status'), 

    ];
    $rules= [
        'dep_name_or'=> 'max:200', 
'dep_name_am'=> 'max:100', 
'dep_name_en'=> 'max:100', 
'dep_code'=> 'max:10', 
'dep_available_at_region'=> 'integer', 
'dep_available_at_zone'=> 'integer', 
'dep_available_at_woreda'=> 'integer', 
'dep_description'=> 'max:425', 
'dep_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelgendepartment::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('department')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('department/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('department/'.$id.'/edit')
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
        Modelgendepartment::destroy($id);
        return redirect('department')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT dep_id,dep_name_or,dep_name_am,dep_name_en,dep_code,dep_available_at_region,dep_available_at_zone,dep_available_at_woreda,dep_description,dep_create_time,dep_update_time,dep_delete_time,dep_created_by,dep_status,1 AS is_editable, 1 AS is_deletable,COUNT(*) OVER () AS total_count FROM gen_department ';       
     
     $query .=' WHERE 1=1';
     $depid=$request->input('dep_id');
if(isset($depid) && isset($depid)){
$query .=' AND dep_id="'.$depid.'"'; 
}
$depnameor=$request->input('dep_name_or');
if(isset($depnameor) && isset($depnameor)){
$query .=' AND dep_name_or="'.$depnameor.'"'; 
}
$depnameam=$request->input('dep_name_am');
if(isset($depnameam) && isset($depnameam)){
$query .=' AND dep_name_am="'.$depnameam.'"'; 
}
$depnameen=$request->input('dep_name_en');
if(isset($depnameen) && isset($depnameen)){
$query .=' AND dep_name_en="'.$depnameen.'"'; 
}
$depcode=$request->input('dep_code');
if(isset($depcode) && isset($depcode)){
$query .=' AND dep_code="'.$depcode.'"'; 
}
$depavailableatregion=$request->input('dep_available_at_region');
if(isset($depavailableatregion) && isset($depavailableatregion)){
$query .=' AND dep_available_at_region="'.$depavailableatregion.'"'; 
}
$depavailableatzone=$request->input('dep_available_at_zone');
if(isset($depavailableatzone) && isset($depavailableatzone)){
$query .=' AND dep_available_at_zone="'.$depavailableatzone.'"'; 
}
$depavailableatworeda=$request->input('dep_available_at_woreda');
if(isset($depavailableatworeda) && isset($depavailableatworeda)){
$query .=' AND dep_available_at_woreda="'.$depavailableatworeda.'"'; 
}
$depdescription=$request->input('dep_description');
if(isset($depdescription) && isset($depdescription)){
$query .=' AND dep_description="'.$depdescription.'"'; 
}
$depcreatetime=$request->input('dep_create_time');
if(isset($depcreatetime) && isset($depcreatetime)){
$query .=' AND dep_create_time="'.$depcreatetime.'"'; 
}
$depupdatetime=$request->input('dep_update_time');
if(isset($depupdatetime) && isset($depupdatetime)){
$query .=' AND dep_update_time="'.$depupdatetime.'"'; 
}
$depdeletetime=$request->input('dep_delete_time');
if(isset($depdeletetime) && isset($depdeletetime)){
$query .=' AND dep_delete_time="'.$depdeletetime.'"'; 
}
$depcreatedby=$request->input('dep_created_by');
if(isset($depcreatedby) && isset($depcreatedby)){
$query .=' AND dep_created_by="'.$depcreatedby.'"'; 
}
$depstatus=$request->input('dep_status');
if(isset($depstatus) && isset($depstatus)){
$query .=' AND dep_status="'.$depstatus.'"'; 
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
        'dep_name_or'=> trans('form_lang.dep_name_or'), 
'dep_name_am'=> trans('form_lang.dep_name_am'), 
'dep_name_en'=> trans('form_lang.dep_name_en'), 
'dep_code'=> trans('form_lang.dep_code'), 
'dep_available_at_region'=> trans('form_lang.dep_available_at_region'), 
'dep_available_at_zone'=> trans('form_lang.dep_available_at_zone'), 
'dep_available_at_woreda'=> trans('form_lang.dep_available_at_woreda'), 
'dep_description'=> trans('form_lang.dep_description'), 
'dep_status'=> trans('form_lang.dep_status'), 

    ];
    $rules= [
        'dep_name_or'=> 'max:200', 
        'dep_name_am'=> 'max:100', 
        'dep_name_en'=> 'max:100', 
        'dep_code'=> 'max:10', 
        'dep_available_at_region'=> 'integer', 
        'dep_available_at_zone'=> 'integer', 
        'dep_available_at_woreda'=> 'integer', 
        'dep_description'=> 'max:425', 
       // 'dep_status'=> 'integer', 

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
        $id=$request->get("dep_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('dep_status');
        if($status=="true"){
            $requestData['dep_status']=1;
        }else{
            $requestData['dep_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelgendepartment::findOrFail($id);
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
        //$requestData['dep_created_by']=auth()->user()->usr_Id;
        $data_info=Modelgendepartment::create($requestData);
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
        'dep_name_or'=> trans('form_lang.dep_name_or'), 
'dep_name_am'=> trans('form_lang.dep_name_am'), 
'dep_name_en'=> trans('form_lang.dep_name_en'), 
'dep_code'=> trans('form_lang.dep_code'), 
'dep_available_at_region'=> trans('form_lang.dep_available_at_region'), 
'dep_available_at_zone'=> trans('form_lang.dep_available_at_zone'), 
'dep_available_at_woreda'=> trans('form_lang.dep_available_at_woreda'), 
'dep_description'=> trans('form_lang.dep_description'), 
'dep_status'=> trans('form_lang.dep_status'), 

    ];
    $rules= [
        'dep_name_or'=> 'max:200', 
        'dep_name_am'=> 'max:100', 
        'dep_name_en'=> 'max:100', 
        'dep_code'=> 'max:10', 
        'dep_available_at_region'=> 'integer', 
        'dep_available_at_zone'=> 'integer', 
        'dep_available_at_woreda'=> 'integer', 
        'dep_description'=> 'max:425', 
        //'dep_status'=> 'integer', 

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
        //$requestData['dep_created_by']=auth()->user()->usr_Id;
        $requestData['dep_created_by']=1;
        $status= $request->input('dep_status');
        if($status=="true"){
            $requestData['dep_status']=1;
        }else{
            $requestData['dep_status']=0;
        }
        $data_info=Modelgendepartment::create($requestData);
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
    $id=$request->get("dep_id");
    Modelgendepartment::destroy($id);
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
    Route::resource('department', 'GendepartmentController');
    Route::post('department/listgrid', 'Api\GendepartmentController@listgrid');
    Route::post('department/insertgrid', 'Api\GendepartmentController@insertgrid');
    Route::post('department/updategrid', 'Api\GendepartmentController@updategrid');
    Route::post('department/deletegrid', 'Api\GendepartmentController@deletegrid');
    Route::post('department/search', 'GendepartmentController@search');
    Route::post('department/getform', 'GendepartmentController@getForm');
    Route::post('department/getlistform', 'GendepartmentController@getListForm');

}
}