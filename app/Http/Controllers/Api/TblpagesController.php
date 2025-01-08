<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modeltblpages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class TblpagesController extends MyController
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
    $searchParams= $this->getSearchSetting('tbl_pages');
    $dataInfo = Modeltblpages::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['tbl_pages_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.tbl_pages");
    return view('pages.list_tbl_pages', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modeltblpages::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="TblpagesController";
        $data= $this->validateEdit($data, $data_info['pag_create_time'], $controllerName);
        $data['tbl_pages_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.tbl_pages");
$form= view('pages.form_popup_tbl_pages', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.tbl_pages'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('pages.editable_list_tbl_pages', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.tbl_pages'));
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
        
        
        $data['page_title']=trans("form_lang.tbl_pages");
        $data['action_mode']="create";
        return view('pages.form_tbl_pages', $data);
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
        'pag_name'=> trans('form_lang.pag_name'), 
'pag_controller'=> trans('form_lang.pag_controller'), 
'pag_modifying_days'=> trans('form_lang.pag_modifying_days'), 
'pag_is_deletable'=> trans('form_lang.pag_is_deletable'), 
'pag_display_record_no'=> trans('form_lang.pag_display_record_no'), 
'pag_system_module'=> trans('form_lang.pag_system_module'), 
'pag_header'=> trans('form_lang.pag_header'), 
'pag_footer'=> trans('form_lang.pag_footer'), 
'pag_rule'=> trans('form_lang.pag_rule'), 
'pag_description'=> trans('form_lang.pag_description'), 
'pag_status'=> trans('form_lang.pag_status'), 

    ];
    $rules= [
        'pag_name'=> 'max:200', 
'pag_controller'=> 'max:200', 
'pag_modifying_days'=> 'integer', 
'pag_is_deletable'=> 'integer', 
'pag_display_record_no'=> 'integer', 
'pag_system_module'=> 'max:200', 
'pag_header'=> 'max:425', 
'pag_footer'=> 'max:425', 
'pag_rule'=> 'max:425', 
'pag_description'=> 'max:425', 
'pag_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pag_created_by']=auth()->user()->usr_Id;
        Modeltblpages::create($requestData);
        return redirect('pages')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('pages/create')
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
        $query='SELECT pag_id,pag_name,pag_controller,pag_modifying_days,pag_is_deletable,pag_display_record_no,pag_system_module,pag_header,pag_footer,pag_rule,pag_description,pag_create_time,pag_update_time,pag_delete_time,pag_created_by,pag_status FROM tbl_pages ';       
        
        $query .=' WHERE pag_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['tbl_pages_data']=$data_info[0];
        }
        //$data_info = Modeltblpages::findOrFail($id);
        //$data['tbl_pages_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_pages");
        return view('pages.show_tbl_pages', $data);
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
        
        
        $data_info = Modeltblpages::find($id);
        $data['tbl_pages_data']=$data_info;
        $data['page_title']=trans("form_lang.tbl_pages");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('pages.form_tbl_pages', $data);
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
        'pag_name'=> trans('form_lang.pag_name'), 
'pag_controller'=> trans('form_lang.pag_controller'), 
'pag_modifying_days'=> trans('form_lang.pag_modifying_days'), 
'pag_is_deletable'=> trans('form_lang.pag_is_deletable'), 
'pag_display_record_no'=> trans('form_lang.pag_display_record_no'), 
'pag_system_module'=> trans('form_lang.pag_system_module'), 
'pag_header'=> trans('form_lang.pag_header'), 
'pag_footer'=> trans('form_lang.pag_footer'), 
'pag_rule'=> trans('form_lang.pag_rule'), 
'pag_description'=> trans('form_lang.pag_description'), 
'pag_status'=> trans('form_lang.pag_status'), 

    ];
    $rules= [
        'pag_name'=> 'max:200', 
'pag_controller'=> 'max:200', 
'pag_modifying_days'=> 'integer', 
'pag_is_deletable'=> 'integer', 
'pag_display_record_no'=> 'integer', 
'pag_system_module'=> 'max:200', 
'pag_header'=> 'max:425', 
'pag_footer'=> 'max:425', 
'pag_rule'=> 'max:425', 
'pag_description'=> 'max:425', 
'pag_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modeltblpages::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('pages')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('pages/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('pages/'.$id.'/edit')
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
        Modeltblpages::destroy($id);
        return redirect('pages')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT pag_id,pag_name,pag_controller,pag_modifying_days,pag_is_deletable,pag_display_record_no,pag_system_module,pag_header,pag_footer,
     pag_rule,pag_description,pag_create_time,pag_update_time,pag_delete_time,pag_created_by,pag_status,1 AS is_editable, 1 AS is_deletable,COUNT(*) OVER () AS total_count FROM tbl_pages ';       
     $query .=' WHERE 1=1';
     $pagid=$request->input('pag_id');
if(isset($pagid) && isset($pagid)){
$query .=' AND pag_id="'.$pagid.'"'; 
}
$pagname=$request->input('pag_name');
if(isset($pagname) && isset($pagname)){
$query .=' AND pag_name="'.$pagname.'"'; 
}
$pagcontroller=$request->input('pag_controller');
if(isset($pagcontroller) && isset($pagcontroller)){
$query .=' AND pag_controller="'.$pagcontroller.'"'; 
}
$pagmodifyingdays=$request->input('pag_modifying_days');
if(isset($pagmodifyingdays) && isset($pagmodifyingdays)){
$query .=' AND pag_modifying_days="'.$pagmodifyingdays.'"'; 
}
$pagisdeletable=$request->input('pag_is_deletable');
if(isset($pagisdeletable) && isset($pagisdeletable)){
$query .=' AND pag_is_deletable="'.$pagisdeletable.'"'; 
}
$pagdisplayrecordno=$request->input('pag_display_record_no');
if(isset($pagdisplayrecordno) && isset($pagdisplayrecordno)){
$query .=' AND pag_display_record_no="'.$pagdisplayrecordno.'"'; 
}
$pagsystemmodule=$request->input('pag_system_module');
if(isset($pagsystemmodule) && isset($pagsystemmodule)){
$query .=' AND pag_system_module="'.$pagsystemmodule.'"'; 
}
$pagheader=$request->input('pag_header');
if(isset($pagheader) && isset($pagheader)){
$query .=' AND pag_header="'.$pagheader.'"'; 
}
$pagfooter=$request->input('pag_footer');
if(isset($pagfooter) && isset($pagfooter)){
$query .=' AND pag_footer="'.$pagfooter.'"'; 
}
$pagrule=$request->input('pag_rule');
if(isset($pagrule) && isset($pagrule)){
$query .=' AND pag_rule="'.$pagrule.'"'; 
}
$pagdescription=$request->input('pag_description');
if(isset($pagdescription) && isset($pagdescription)){
$query .=' AND pag_description="'.$pagdescription.'"'; 
}
$pagcreatetime=$request->input('pag_create_time');
if(isset($pagcreatetime) && isset($pagcreatetime)){
$query .=' AND pag_create_time="'.$pagcreatetime.'"'; 
}
$pagupdatetime=$request->input('pag_update_time');
if(isset($pagupdatetime) && isset($pagupdatetime)){
$query .=' AND pag_update_time="'.$pagupdatetime.'"'; 
}
$pagdeletetime=$request->input('pag_delete_time');
if(isset($pagdeletetime) && isset($pagdeletetime)){
$query .=' AND pag_delete_time="'.$pagdeletetime.'"'; 
}
$pagcreatedby=$request->input('pag_created_by');
if(isset($pagcreatedby) && isset($pagcreatedby)){
$query .=' AND pag_created_by="'.$pagcreatedby.'"'; 
}
$pagstatus=$request->input('pag_status');
if(isset($pagstatus) && isset($pagstatus)){
$query .=' AND pag_status="'.$pagstatus.'"'; 
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
        'pag_name'=> trans('form_lang.pag_name'), 
'pag_controller'=> trans('form_lang.pag_controller'), 
'pag_modifying_days'=> trans('form_lang.pag_modifying_days'), 
'pag_is_deletable'=> trans('form_lang.pag_is_deletable'), 
'pag_display_record_no'=> trans('form_lang.pag_display_record_no'), 
'pag_system_module'=> trans('form_lang.pag_system_module'), 
'pag_header'=> trans('form_lang.pag_header'), 
'pag_footer'=> trans('form_lang.pag_footer'), 
'pag_rule'=> trans('form_lang.pag_rule'), 
'pag_description'=> trans('form_lang.pag_description'), 
'pag_status'=> trans('form_lang.pag_status'), 

    ];
    $rules= [
        'pag_name'=> 'max:200', 
'pag_controller'=> 'max:200', 
'pag_modifying_days'=> 'integer', 
'pag_is_deletable'=> 'integer', 
'pag_display_record_no'=> 'integer', 
'pag_system_module'=> 'max:200', 
'pag_header'=> 'max:425', 
'pag_footer'=> 'max:425', 
'pag_rule'=> 'max:425', 
'pag_description'=> 'max:425', 
'pag_status'=> 'integer', 

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
        $id=$request->get("pag_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pag_status');
        if($status=="true"){
            $requestData['pag_status']=1;
        }else{
            $requestData['pag_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblpages::findOrFail($id);
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
        //$requestData['pag_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblpages::create($requestData);
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
        'pag_name'=> trans('form_lang.pag_name'), 
'pag_controller'=> trans('form_lang.pag_controller'), 
'pag_modifying_days'=> trans('form_lang.pag_modifying_days'), 
'pag_is_deletable'=> trans('form_lang.pag_is_deletable'), 
'pag_display_record_no'=> trans('form_lang.pag_display_record_no'), 
'pag_system_module'=> trans('form_lang.pag_system_module'), 
'pag_header'=> trans('form_lang.pag_header'), 
'pag_footer'=> trans('form_lang.pag_footer'), 
'pag_rule'=> trans('form_lang.pag_rule'), 
'pag_description'=> trans('form_lang.pag_description'), 
'pag_status'=> trans('form_lang.pag_status'), 

    ];
    $rules= [
        'pag_name'=> 'max:200', 
'pag_controller'=> 'max:200', 
'pag_modifying_days'=> 'integer', 
'pag_is_deletable'=> 'integer', 
'pag_display_record_no'=> 'integer', 
'pag_system_module'=> 'max:200', 
'pag_header'=> 'max:425', 
'pag_footer'=> 'max:425', 
'pag_rule'=> 'max:425', 
'pag_description'=> 'max:425', 
'pag_status'=> 'integer', 

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
        //$requestData['pag_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pag_status');
        if($status=="true"){
            $requestData['pag_status']=1;
        }else{
            $requestData['pag_status']=0;
        }
        $data_info=Modeltblpages::create($requestData);
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
    $id=$request->get("pag_id");
    Modeltblpages::destroy($id);
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
}