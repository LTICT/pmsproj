<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblaccesslog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblaccesslogController extends MyController
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
    $searchParams= $this->getSearchSetting('tbl_access_log');
    $dataInfo = Modeltblaccesslog::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['tbl_access_log_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.tbl_access_log");
    return view('access_log.list_tbl_access_log', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modeltblaccesslog::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="TblaccesslogController";
        $data= $this->validateEdit($data, $data_info['acl_create_time'], $controllerName);
        $data['tbl_access_log_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.tbl_access_log");
$form= view('access_log.form_popup_tbl_access_log', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.tbl_access_log'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('access_log.editable_list_tbl_access_log', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.tbl_access_log'));
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
        
        
        $data['page_title']=trans("form_lang.tbl_access_log");
        $data['action_mode']="create";
        return view('access_log.form_tbl_access_log', $data);
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
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['acl_created_by']=auth()->user()->usr_Id;
        Modeltblaccesslog::create($requestData);
        return redirect('access_log')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('access_log/create')
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
        $query='SELECT acl_id,acl_ip,acl_user_id,acl_role_id,acl_object_name,acl_object_id,acl_remark,acl_detail,acl_object_action,acl_description,acl_create_time,acl_update_time,acl_delete_time,acl_created_by,acl_status FROM tbl_access_log ';       
        
        $query .=' WHERE acl_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_access_log_data']=$data_info[0];
        }
        //$data_info = Modeltblaccesslog::findOrFail($id);
        //$data['tbl_access_log_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_access_log");
        return view('access_log.show_tbl_access_log', $data);
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
        
        
        $data_info = Modeltblaccesslog::find($id);
        $data['tbl_access_log_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_access_log");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('access_log.form_tbl_access_log', $data);
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
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modeltblaccesslog::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('access_log')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('access_log/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('access_log/'.$id.'/edit')
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
        Modeltblaccesslog::destroy($id);
        return redirect('access_log')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT acl_id,acl_ip,acl_user_id,acl_role_id,acl_object_name,acl_object_id,acl_remark,acl_detail,acl_object_action,acl_description,acl_create_time,acl_update_time,acl_delete_time,acl_created_by,acl_status,1 AS is_editable, 1 AS is_deletable FROM tbl_access_log ';       
     
     $query .=' WHERE 1=1';
     $aclid=$request->input('acl_id');
if(isset($aclid) && isset($aclid)){
$query .=' AND acl_id="'.$aclid.'"'; 
}
$aclip=$request->input('acl_ip');
if(isset($aclip) && isset($aclip)){
$query .=' AND acl_ip="'.$aclip.'"'; 
}
$acluserid=$request->input('acl_user_id');
if(isset($acluserid) && isset($acluserid)){
$query .=' AND acl_user_id="'.$acluserid.'"'; 
}
$aclroleid=$request->input('acl_role_id');
if(isset($aclroleid) && isset($aclroleid)){
$query .=' AND acl_role_id="'.$aclroleid.'"'; 
}
$aclobjectname=$request->input('acl_object_name');
if(isset($aclobjectname) && isset($aclobjectname)){
$query .=' AND acl_object_name="'.$aclobjectname.'"'; 
}
$aclobjectid=$request->input('acl_object_id');
if(isset($aclobjectid) && isset($aclobjectid)){
$query .=' AND acl_object_id="'.$aclobjectid.'"'; 
}
$aclremark=$request->input('acl_remark');
if(isset($aclremark) && isset($aclremark)){
$query .=' AND acl_remark="'.$aclremark.'"'; 
}
$acldetail=$request->input('acl_detail');
if(isset($acldetail) && isset($acldetail)){
$query .=' AND acl_detail="'.$acldetail.'"'; 
}
$aclobjectaction=$request->input('acl_object_action');
if(isset($aclobjectaction) && isset($aclobjectaction)){
$query .=' AND acl_object_action="'.$aclobjectaction.'"'; 
}
$acldescription=$request->input('acl_description');
if(isset($acldescription) && isset($acldescription)){
$query .=' AND acl_description="'.$acldescription.'"'; 
}
$aclcreatetime=$request->input('acl_create_time');
if(isset($aclcreatetime) && isset($aclcreatetime)){
$query .=' AND acl_create_time="'.$aclcreatetime.'"'; 
}
$aclupdatetime=$request->input('acl_update_time');
if(isset($aclupdatetime) && isset($aclupdatetime)){
$query .=' AND acl_update_time="'.$aclupdatetime.'"'; 
}
$acldeletetime=$request->input('acl_delete_time');
if(isset($acldeletetime) && isset($acldeletetime)){
$query .=' AND acl_delete_time="'.$acldeletetime.'"'; 
}
$aclcreatedby=$request->input('acl_created_by');
if(isset($aclcreatedby) && isset($aclcreatedby)){
$query .=' AND acl_created_by="'.$aclcreatedby.'"'; 
}
$aclstatus=$request->input('acl_status');
if(isset($aclstatus) && isset($aclstatus)){
$query .=' AND acl_status="'.$aclstatus.'"'; 
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
$data_info=DB::select(DB::raw($query));
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

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
        $id=$request->get("acl_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('acl_status');
        if($status=="true"){
            $requestData['acl_status']=1;
        }else{
            $requestData['acl_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblaccesslog::findOrFail($id);
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
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblaccesslog::create($requestData);
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
        'acl_ip'=> trans('form_lang.acl_ip'), 
'acl_user_id'=> trans('form_lang.acl_user_id'), 
'acl_role_id'=> trans('form_lang.acl_role_id'), 
'acl_object_name'=> trans('form_lang.acl_object_name'), 
'acl_object_id'=> trans('form_lang.acl_object_id'), 
'acl_remark'=> trans('form_lang.acl_remark'), 
'acl_detail'=> trans('form_lang.acl_detail'), 
'acl_object_action'=> trans('form_lang.acl_object_action'), 
'acl_description'=> trans('form_lang.acl_description'), 
'acl_status'=> trans('form_lang.acl_status'), 

    ];
    $rules= [
        'acl_ip'=> 'max:200', 
'acl_user_id'=> 'max:200', 
'acl_role_id'=> 'max:200', 
'acl_object_name'=> 'max:200', 
'acl_object_id'=> 'max:15', 
'acl_remark'=> 'max:45', 
'acl_detail'=> 'max:45', 
'acl_object_action'=> 'max:200', 
'acl_description'=> 'max:425', 
'acl_status'=> 'integer', 

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
        //$requestData['acl_created_by']=auth()->user()->usr_Id;
        $status= $request->input('acl_status');
        if($status=="true"){
            $requestData['acl_status']=1;
        }else{
            $requestData['acl_status']=0;
        }
        $data_info=Modeltblaccesslog::create($requestData);
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
    $id=$request->get("acl_id");
    Modeltblaccesslog::destroy($id);
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
    Route::resource('access_log', 'TblaccesslogController');
    Route::post('access_log/listgrid', 'Api\TblaccesslogController@listgrid');
    Route::post('access_log/insertgrid', 'Api\TblaccesslogController@insertgrid');
    Route::post('access_log/updategrid', 'Api\TblaccesslogController@updategrid');
    Route::post('access_log/deletegrid', 'Api\TblaccesslogController@deletegrid');
    Route::post('access_log/search', 'TblaccesslogController@search');
    Route::post('access_log/getform', 'TblaccesslogController@getForm');
    Route::post('access_log/getlistform', 'TblaccesslogController@getListForm');

}
}