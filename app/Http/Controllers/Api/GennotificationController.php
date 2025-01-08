<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblpermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GennotificationController extends MyController
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
    $searchParams= $this->getSearchSetting('tbl_permission');
    $dataInfo = Modeltblpermission::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['tbl_permission_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.tbl_permission");
    return view('permission.list_tbl_permission', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modeltblpermission::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="TblpermissionController";
        $data= $this->validateEdit($data, $data_info['pem_create_time'], $controllerName);
        $data['tbl_permission_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.tbl_permission");
$form= view('permission.form_popup_tbl_permission', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.tbl_permission'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('permission.editable_list_tbl_permission', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.tbl_permission'));
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
        
        
        $data['page_title']=trans("form_lang.tbl_permission");
        $data['action_mode']="create";
        return view('permission.form_tbl_permission', $data);
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
        'pem_page_id'=> trans('form_lang.pem_page_id'), 
'pem_role_id'=> trans('form_lang.pem_role_id'), 
'pem_enabled'=> trans('form_lang.pem_enabled'), 
'pem_edit'=> trans('form_lang.pem_edit'), 
'pem_insert'=> trans('form_lang.pem_insert'), 
'pem_view'=> trans('form_lang.pem_view'), 
'pem_delete'=> trans('form_lang.pem_delete'), 
'pem_show'=> trans('form_lang.pem_show'), 
'pem_search'=> trans('form_lang.pem_search'), 
'pem_description'=> trans('form_lang.pem_description'), 
'pem_status'=> trans('form_lang.pem_status'), 

    ];
    $rules= [
        'pem_page_id'=> 'max:200', 
'pem_role_id'=> 'max:200', 
'pem_enabled'=> 'max:2', 
'pem_edit'=> 'max:2', 
'pem_insert'=> 'max:2', 
'pem_view'=> 'max:2', 
'pem_delete'=> 'max:2', 
'pem_show'=> 'max:2', 
'pem_search'=> 'max:2', 
'pem_description'=> 'max:425', 
'pem_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pem_created_by']=auth()->user()->usr_Id;
        Modeltblpermission::create($requestData);
        return redirect('permission')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('permission/create')
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
        $query='SELECT pem_id,pem_page_id,pem_role_id,pem_enabled,pem_edit,pem_insert,pem_view,pem_delete,pem_show,pem_search,pem_description,pem_create_time,pem_update_time,pem_delete_time,pem_created_by,pem_status FROM tbl_permission ';       
        
        $query .=' WHERE pem_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_permission_data']=$data_info[0];
        }
        //$data_info = Modeltblpermission::findOrFail($id);
        //$data['tbl_permission_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_permission");
        return view('permission.show_tbl_permission', $data);
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
        
        
        $data_info = Modeltblpermission::find($id);
        $data['tbl_permission_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_permission");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('permission.form_tbl_permission', $data);
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
        'pem_page_id'=> trans('form_lang.pem_page_id'), 
'pem_role_id'=> trans('form_lang.pem_role_id'), 
'pem_enabled'=> trans('form_lang.pem_enabled'), 
'pem_edit'=> trans('form_lang.pem_edit'), 
'pem_insert'=> trans('form_lang.pem_insert'), 
'pem_view'=> trans('form_lang.pem_view'), 
'pem_delete'=> trans('form_lang.pem_delete'), 
'pem_show'=> trans('form_lang.pem_show'), 
'pem_search'=> trans('form_lang.pem_search'), 
'pem_description'=> trans('form_lang.pem_description'), 
'pem_status'=> trans('form_lang.pem_status'), 

    ];
    $rules= [
        'pem_page_id'=> 'max:200', 
'pem_role_id'=> 'max:200', 
'pem_enabled'=> 'max:2', 
'pem_edit'=> 'max:2', 
'pem_insert'=> 'max:2', 
'pem_view'=> 'max:2', 
'pem_delete'=> 'max:2', 
'pem_show'=> 'max:2', 
'pem_search'=> 'max:2', 
'pem_description'=> 'max:425', 
'pem_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modeltblpermission::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('permission')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('permission/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('permission/'.$id.'/edit')
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
        Modeltblpermission::destroy($id);
        return redirect('permission')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
    $query='SELECT id AS not_id, notifiable_type AS not_type,data AS not_detail,created_at AS not_date,is_read AS not_is_read FROM notifications ORDER BY id DESC';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'pem_page_id'=> trans('form_lang.pem_page_id'), 
'pem_role_id'=> trans('form_lang.pem_role_id'), 
'pem_enabled'=> trans('form_lang.pem_enabled'), 
'pem_edit'=> trans('form_lang.pem_edit'), 
'pem_insert'=> trans('form_lang.pem_insert'), 
'pem_view'=> trans('form_lang.pem_view'), 
'pem_delete'=> trans('form_lang.pem_delete'), 
'pem_show'=> trans('form_lang.pem_show'), 
'pem_search'=> trans('form_lang.pem_search'), 
'pem_description'=> trans('form_lang.pem_description'), 
'pem_status'=> trans('form_lang.pem_status'), 

    ];
    $rules= [
        'pem_page_id'=> 'max:200'
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
      
           //START QUERY
           $notIds=$request->get('notification_ids');
           $currentTime = date('Y-m-d H:i:s');
           $departQuery = 'UPDATE notifications  SET is_read=1, read_at ="'.$currentTime.'"
  WHERE id IN ('.$notIds.') ';
  $dataInfo=DB::update(DB::raw($departQuery));
           //END QUERY
            $resultObject= array(
                "data" =>$notIds,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "is_updated"=>true,
                "status_code"=>200,
                "type"=>"update",
                "errorMsg"=>""
            );
        return response()->json($resultObject);
}
}
public function insertgrid(Request $request)
{
    $attributeNames = [
        'pem_page_id'=> trans('form_lang.pem_page_id'), 
'pem_role_id'=> trans('form_lang.pem_role_id'), 
'pem_enabled'=> trans('form_lang.pem_enabled'), 
'pem_edit'=> trans('form_lang.pem_edit'), 
'pem_insert'=> trans('form_lang.pem_insert'), 
'pem_view'=> trans('form_lang.pem_view'), 
'pem_delete'=> trans('form_lang.pem_delete'), 
'pem_show'=> trans('form_lang.pem_show'), 
'pem_search'=> trans('form_lang.pem_search'), 
'pem_description'=> trans('form_lang.pem_description'), 
'pem_status'=> trans('form_lang.pem_status'), 

    ];
    $rules= [
        'pem_page_id'=> 'max:200', 
'pem_role_id'=> 'max:200', 
'pem_enabled'=> 'max:2', 
'pem_edit'=> 'max:2', 
'pem_insert'=> 'max:2', 
'pem_view'=> 'max:2', 
'pem_delete'=> 'max:2', 
'pem_show'=> 'max:2', 
'pem_search'=> 'max:2', 
'pem_description'=> 'max:425', 
//'pem_status'=> 'integer', 

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
        //$requestData['pem_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pem_status');
        if($status=="true"){
            $requestData['pem_status']=1;
        }else{
            $requestData['pem_status']=0;
        }
        $data_info=Modeltblpermission::create($requestData);
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
    $id=$request->get("pem_id");
    Modeltblpermission::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
}