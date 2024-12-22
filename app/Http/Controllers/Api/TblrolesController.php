<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblroles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblrolesController extends MyController
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
    $searchParams= $this->getSearchSetting('tbl_roles');
    $dataInfo = Modeltblroles::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['tbl_roles_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.tbl_roles");
    return view('roles.list_tbl_roles', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modeltblroles::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="TblrolesController";
        $data= $this->validateEdit($data, $data_info['rol_create_time'], $controllerName);
        $data['tbl_roles_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.tbl_roles");
$form= view('roles.form_popup_tbl_roles', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.tbl_roles'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('roles.editable_list_tbl_roles', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.tbl_roles'));
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
        
        
        $data['page_title']=trans("form_lang.tbl_roles");
        $data['action_mode']="create";
        return view('roles.form_tbl_roles', $data);
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
        'rol_name'=> trans('form_lang.rol_name'), 
'rol_description'=> trans('form_lang.rol_description'), 
'rol_status'=> trans('form_lang.rol_status'), 

    ];
    $rules= [
        'rol_name'=> 'max:200', 
'rol_description'=> 'max:425', 
'rol_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['rol_created_by']=auth()->user()->usr_Id;
        Modeltblroles::create($requestData);
        return redirect('roles')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('roles/create')
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
        $query='SELECT rol_id,rol_name,rol_description,rol_create_time,rol_update_time,rol_delete_time,rol_created_by,rol_status FROM tbl_roles ';       
        
        $query .=' WHERE rol_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_roles_data']=$data_info[0];
        }
        //$data_info = Modeltblroles::findOrFail($id);
        //$data['tbl_roles_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_roles");
        return view('roles.show_tbl_roles', $data);
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
        
        
        $data_info = Modeltblroles::find($id);
        $data['tbl_roles_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_roles");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('roles.form_tbl_roles', $data);
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
        'rol_name'=> trans('form_lang.rol_name'), 
'rol_description'=> trans('form_lang.rol_description'), 
'rol_status'=> trans('form_lang.rol_status'), 

    ];
    $rules= [
        'rol_name'=> 'max:200', 
'rol_description'=> 'max:425', 
'rol_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modeltblroles::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('roles')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('roles/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('roles/'.$id.'/edit')
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
        Modeltblroles::destroy($id);
        return redirect('roles')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
        //$authenticatedUser = $request->authUser;
        //$userId=$authenticatedUser->usr_id;
     $query='SELECT rol_id,rol_name,rol_description,rol_create_time,rol_update_time,rol_delete_time,rol_created_by,rol_status,1 AS is_editable, 0 AS is_deletable,COUNT(*) OVER () AS total_count FROM tbl_roles ';       
    
     $query .=' WHERE 1=1';
     $rolid=$request->input('rol_id');
if(isset($rolid) && isset($rolid)){
$query .=' AND rol_id="'.$rolid.'"'; 
}
$rolname=$request->input('rol_name');
if(isset($rolname) && isset($rolname)){
$query .=' AND rol_name="'.$rolname.'"'; 
}
$roldescription=$request->input('rol_description');
if(isset($roldescription) && isset($roldescription)){
$query .=' AND rol_description="'.$roldescription.'"'; 
}
$rolcreatetime=$request->input('rol_create_time');
if(isset($rolcreatetime) && isset($rolcreatetime)){
$query .=' AND rol_create_time="'.$rolcreatetime.'"'; 
}
$rolupdatetime=$request->input('rol_update_time');
if(isset($rolupdatetime) && isset($rolupdatetime)){
$query .=' AND rol_update_time="'.$rolupdatetime.'"'; 
}
$roldeletetime=$request->input('rol_delete_time');
if(isset($roldeletetime) && isset($roldeletetime)){
$query .=' AND rol_delete_time="'.$roldeletetime.'"'; 
}
$rolcreatedby=$request->input('rol_created_by');
if(isset($rolcreatedby) && isset($rolcreatedby)){
$query .=' AND rol_created_by="'.$rolcreatedby.'"'; 
}
$rolstatus=$request->input('rol_status');
if(isset($rolstatus) && isset($rolstatus)){
$query .=' AND rol_status="'.$rolstatus.'"'; 
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
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>0,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'rol_name'=> trans('form_lang.rol_name'), 
'rol_description'=> trans('form_lang.rol_description'), 
'rol_status'=> trans('form_lang.rol_status'), 

    ];
    $rules= [
        'rol_name'=> 'max:200', 
'rol_description'=> 'max:425', 
//'rol_status'=> 'integer', 

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
        $id=$request->get("rol_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('rol_status');
        if($status=="true"){
            $requestData['rol_status']=1;
        }else{
            $requestData['rol_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblroles::findOrFail($id);
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
        //$requestData['rol_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblroles::create($requestData);
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
        'rol_name'=> trans('form_lang.rol_name'), 
'rol_description'=> trans('form_lang.rol_description'), 
'rol_status'=> trans('form_lang.rol_status'), 

    ];
    $rules= [
        'rol_name'=> 'max:200', 
'rol_description'=> 'max:425', 
//'rol_status'=> 'integer', 

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
        //$requestData['rol_created_by']=auth()->user()->usr_Id;
        $requestData['rol_created_by']=1;
        $status= $request->input('rol_status');
        if($status=="true"){
            $requestData['rol_status']=1;
        }else{
            $requestData['rol_status']=0;
        }
        $data_info=Modeltblroles::create($requestData);
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
    $id=$request->get("rol_id");
    Modeltblroles::destroy($id);
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
    Route::resource('roles', 'TblrolesController');
    Route::post('roles/listgrid', 'Api\TblrolesController@listgrid');
    Route::post('roles/insertgrid', 'Api\TblrolesController@insertgrid');
    Route::post('roles/updategrid', 'Api\TblrolesController@updategrid');
    Route::post('roles/deletegrid', 'Api\TblrolesController@deletegrid');
    Route::post('roles/search', 'TblrolesController@search');
    Route::post('roles/getform', 'TblrolesController@getForm');
    Route::post('roles/getlistform', 'TblrolesController@getListForm');

}
}