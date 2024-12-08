<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltbluserrole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TbluserroleController extends MyController
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
    $searchParams= $this->getSearchSetting('tbl_user_role');
    $dataInfo = Modeltbluserrole::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['tbl_user_role_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.tbl_user_role");
    return view('user_role.list_tbl_user_role', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    $tbl_roles_set=\App\Modeltblroles::latest()->get();

    $data['related_tbl_roles']= $tbl_roles_set ;

    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modeltbluserrole::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="TbluserroleController";
        $data= $this->validateEdit($data, $data_info['url_create_time'], $controllerName);
        $data['tbl_user_role_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.tbl_user_role");
$form= view('user_role.form_popup_tbl_user_role', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.tbl_user_role'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('user_role.editable_list_tbl_user_role', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.tbl_user_role'));
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
        $tbl_roles_set=\App\Modeltblroles::latest()->get();

        $data['related_tbl_roles']= $tbl_roles_set ;

        $data['page_title']=trans("form_lang.tbl_user_role");
        $data['action_mode']="create";
        return view('user_role.form_tbl_user_role', $data);
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
        'url_role_id'=> trans('form_lang.url_role_id'), 
'url_user_id'=> trans('form_lang.url_user_id'), 
'url_description'=> trans('form_lang.url_description'), 
'url_status'=> trans('form_lang.url_status'), 

    ];
    $rules= [
        'url_role_id'=> 'max:200', 
'url_user_id'=> 'max:200', 
'url_description'=> 'max:425', 
'url_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['url_created_by']=auth()->user()->usr_Id;
        Modeltbluserrole::create($requestData);
        return redirect('user_role')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('user_role/create')
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
        $query='SELECT url_id,tbl_roles.rol_name AS url_role_id,url_user_id,url_description,url_create_time,url_update_time,url_delete_time,url_created_by,url_status FROM tbl_user_role ';       
        $query .= ' INNER JOIN tbl_roles ON tbl_user_role.url_role_id = tbl_roles.rol_id'; 

        $query .=' WHERE url_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_user_role_data']=$data_info[0];
        }
        //$data_info = Modeltbluserrole::findOrFail($id);
        //$data['tbl_user_role_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_user_role");
        return view('user_role.show_tbl_user_role', $data);
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
        $tbl_roles_set=\App\Modeltblroles::latest()->get();

        $data['related_tbl_roles']= $tbl_roles_set ;

        $data_info = Modeltbluserrole::find($id);
        $data['tbl_user_role_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_user_role");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('user_role.form_tbl_user_role', $data);
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
        'url_role_id'=> trans('form_lang.url_role_id'), 
'url_user_id'=> trans('form_lang.url_user_id'), 
'url_description'=> trans('form_lang.url_description'), 
'url_status'=> trans('form_lang.url_status'), 

    ];
    $rules= [
        'url_role_id'=> 'max:200', 
'url_user_id'=> 'max:200', 
'url_description'=> 'max:425', 
'url_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modeltbluserrole::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('user_role')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('user_role/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('user_role/'.$id.'/edit')
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
        Modeltbluserrole::destroy($id);
        return redirect('user_role')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT rol_name, url_id, url_role_id,url_user_id,url_description,url_create_time,
     url_update_time,url_delete_time,url_created_by,url_status,1 AS is_editable, 1 AS is_deletable FROM tbl_user_role ';       
     $query .= ' INNER JOIN tbl_roles ON tbl_user_role.url_role_id = tbl_roles.rol_id'; 

     $query .=' WHERE 1=1';
     $urlid=$request->input('url_id');
if(isset($urlid) && isset($urlid)){
$query .=' AND url_id="'.$urlid.'"'; 
}
$urlroleid=$request->input('url_role_id');
if(isset($urlroleid) && isset($urlroleid)){
$query .=' AND url_role_id="'.$urlroleid.'"'; 
}
/*$urluserid=$request->input('user_id');
if(isset($urluserid) && isset($urluserid)){
$query .=' AND url_user_id="'.$urluserid.'"'; 
}*/

$urldescription=$request->input('url_description');
if(isset($urldescription) && isset($urldescription)){
$query .=' AND url_description="'.$urldescription.'"'; 
}
$urlcreatetime=$request->input('url_create_time');
if(isset($urlcreatetime) && isset($urlcreatetime)){
$query .=' AND url_create_time="'.$urlcreatetime.'"'; 
}
$urlupdatetime=$request->input('url_update_time');
if(isset($urlupdatetime) && isset($urlupdatetime)){
$query .=' AND url_update_time="'.$urlupdatetime.'"'; 
}
$urldeletetime=$request->input('url_delete_time');
if(isset($urldeletetime) && isset($urldeletetime)){
$query .=' AND url_delete_time="'.$urldeletetime.'"'; 
}
$urlcreatedby=$request->input('url_created_by');
if(isset($urlcreatedby) && isset($urlcreatedby)){
$query .=' AND url_created_by="'.$urlcreatedby.'"'; 
}
$urlstatus=$request->input('url_status');
if(isset($urlstatus) && isset($urlstatus)){
$query .=' AND url_status="'.$urlstatus.'"'; 
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
        'url_role_id'=> trans('form_lang.url_role_id'), 
'url_user_id'=> trans('form_lang.url_user_id'), 
'url_description'=> trans('form_lang.url_description'), 
'url_status'=> trans('form_lang.url_status'), 

    ];
    $rules= [
        'url_role_id'=> 'max:200', 
'url_user_id'=> 'max:200', 
'url_description'=> 'max:425', 
'url_status'=> 'integer', 

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
        $id=$request->get("url_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('url_status');
        if($status=="true"){
            $requestData['url_status']=1;
        }else{
            $requestData['url_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltbluserrole::findOrFail($id);
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
        //$requestData['url_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltbluserrole::create($requestData);
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
        'url_role_id'=> trans('form_lang.url_role_id'), 
'url_user_id'=> trans('form_lang.url_user_id'), 
'url_description'=> trans('form_lang.url_description'), 
'url_status'=> trans('form_lang.url_status'), 

    ];
    $rules= [
        'url_role_id'=> 'max:200', 
'url_user_id'=> 'max:200', 
'url_description'=> 'max:425', 
'url_status'=> 'integer', 

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
        //$requestData['url_created_by']=auth()->user()->usr_Id;
        $status= $request->input('url_status');
        if($status=="true"){
            $requestData['url_status']=1;
        }else{
            $requestData['url_status']=0;
        }
        $data_info=Modeltbluserrole::create($requestData);
       $data_info["rol_name"]= $request->get("rol_name");
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
    $id=$request->get("url_id");
    Modeltbluserrole::destroy($id);
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
    Route::resource('user_role', 'TbluserroleController');
    Route::post('user_role/listgrid', 'Api\TbluserroleController@listgrid');
    Route::post('user_role/insertgrid', 'Api\TbluserroleController@insertgrid');
    Route::post('user_role/updategrid', 'Api\TbluserroleController@updategrid');
    Route::post('user_role/deletegrid', 'Api\TbluserroleController@deletegrid');
    Route::post('user_role/search', 'TbluserroleController@search');
    Route::post('user_role/getform', 'TbluserroleController@getForm');
    Route::post('user_role/getlistform', 'TbluserroleController@getListForm');

}
}