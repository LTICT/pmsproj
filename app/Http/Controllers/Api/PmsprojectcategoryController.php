<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectcategoryController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_category');
    $dataInfo = Modelpmsprojectcategory::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_category_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_category");
    return view('project_category.list_pms_project_category', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectcategory::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectcategoryController";
        $data= $this->validateEdit($data, $data_info['pct_create_time'], $controllerName);
        $data['pms_project_category_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_category");
$form= view('project_category.form_popup_pms_project_category', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_category'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_category.editable_list_pms_project_category', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_category'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_category");
        $data['action_mode']="create";
        return view('project_category.form_pms_project_category', $data);
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
        'pct_name_or'=> trans('form_lang.pct_name_or'), 
'pct_name_am'=> trans('form_lang.pct_name_am'), 
'pct_name_en'=> trans('form_lang.pct_name_en'), 
'pct_code'=> trans('form_lang.pct_code'), 
'pct_description'=> trans('form_lang.pct_description'), 
'pct_status'=> trans('form_lang.pct_status'), 

    ];
    $rules= [
        'pct_name_or'=> 'max:200', 
'pct_name_am'=> 'max:100', 
'pct_name_en'=> 'max:100', 
'pct_code'=> 'max:10', 
'pct_description'=> 'max:425', 
'pct_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pct_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectcategory::create($requestData);
        return redirect('project_category')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_category/create')
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
        $query='SELECT pct_id,pct_name_or,pct_name_am,pct_name_en,pct_code,pct_description,pct_create_time,pct_update_time,pct_delete_time,pct_created_by,pct_status FROM pms_project_category ';       
        
        $query .=' WHERE pct_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_category_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectcategory::findOrFail($id);
        //$data['pms_project_category_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_category");
        return view('project_category.show_pms_project_category', $data);
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
        
        
        $data_info = Modelpmsprojectcategory::find($id);
        $data['pms_project_category_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_category");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_category.form_pms_project_category', $data);
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
        'pct_name_or'=> trans('form_lang.pct_name_or'), 
'pct_name_am'=> trans('form_lang.pct_name_am'), 
'pct_name_en'=> trans('form_lang.pct_name_en'), 
'pct_code'=> trans('form_lang.pct_code'), 
'pct_description'=> trans('form_lang.pct_description'), 
'pct_status'=> trans('form_lang.pct_status'), 

    ];
    $rules= [
        'pct_name_or'=> 'max:200', 
'pct_name_am'=> 'max:100', 
'pct_name_en'=> 'max:100', 
'pct_code'=> 'max:10', 
'pct_description'=> 'max:425', 
'pct_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectcategory::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_category')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_category/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_category/'.$id.'/edit')
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
        Modelpmsprojectcategory::destroy($id);
        return redirect('project_category')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
        //dd(config('constants.PROJ_INFORMATION'));
        $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
        $pageId=config('constants.LU_CATEGORY');
          $permissionData=$this->getPagePermission($request,$pageId);
          if(isset($permissionData) && !empty($permissionData)){
                $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
             }
     $query="SELECT pct_id,pct_name_or,pct_name_am,pct_name_en,pct_code,pct_description,pct_create_time,pct_update_time,pct_delete_time,pct_created_by,pct_status
       ".$permissionIndex." FROM pms_project_category ";
     $query .=' WHERE 1=1';
     $pctid=$request->input('pct_id');
if(isset($pctid) && isset($pctid)){
$query .=' AND pct_id="'.$pctid.'"'; 
}
$pctnameor=$request->input('pct_name_or');
if(isset($pctnameor) && isset($pctnameor)){
$query .=" AND pct_name_or LIKE '%".$pctnameor."%'"; 
}
$pctnameam=$request->input('pct_name_am');
if(isset($pctnameam) && isset($pctnameam)){
$query .=' AND pct_name_am="'.$pctnameam.'"'; 
}
$pctnameen=$request->input('pct_name_en');
if(isset($pctnameen) && isset($pctnameen)){
$query .=' AND pct_name_en="'.$pctnameen.'"'; 
}
$pctcode=$request->input('pct_code');
if(isset($pctcode) && isset($pctcode)){
$query .=' AND pct_code="'.$pctcode.'"'; 
}
$pctdescription=$request->input('pct_description');
if(isset($pctdescription) && isset($pctdescription)){
$query .=' AND pct_description="'.$pctdescription.'"'; 
}
$pctcreatetime=$request->input('pct_create_time');
if(isset($pctcreatetime) && isset($pctcreatetime)){
$query .=' AND pct_create_time="'.$pctcreatetime.'"'; 
}
$pctupdatetime=$request->input('pct_update_time');
if(isset($pctupdatetime) && isset($pctupdatetime)){
$query .=' AND pct_update_time="'.$pctupdatetime.'"'; 
}
$pctdeletetime=$request->input('pct_delete_time');
if(isset($pctdeletetime) && isset($pctdeletetime)){
$query .=' AND pct_delete_time="'.$pctdeletetime.'"'; 
}
$pctcreatedby=$request->input('pct_created_by');
if(isset($pctcreatedby) && isset($pctcreatedby)){
$query .=' AND pct_created_by="'.$pctcreatedby.'"'; 
}
$pctstatus=$request->input('pct_status');
if(isset($pctstatus) && isset($pctstatus)){
$query .=' AND pct_status="'.$pctstatus.'"'; 
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
"previledge"=>array('is_role_editable'=>$permissionData->pem_edit,'is_role_deletable'=>$permissionData->pem_delete,'is_role_can_add'=>$permissionData->pem_insert));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $attributeNames = [
        'pct_name_or'=> trans('form_lang.pct_name_or'), 
'pct_name_am'=> trans('form_lang.pct_name_am'), 
'pct_name_en'=> trans('form_lang.pct_name_en'), 
'pct_code'=> trans('form_lang.pct_code'), 
'pct_description'=> trans('form_lang.pct_description'), 
'pct_status'=> trans('form_lang.pct_status'), 

    ];
    $rules= [
        'pct_name_or'=> 'max:100', 
'pct_name_am'=> 'max:100', 
'pct_name_en'=> 'max:100', 
'pct_code'=> 'max:20', 
'pct_description'=> 'max:425', 
//'pct_status'=> 'integer', 

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
        $id=$request->get("pct_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pct_status');
        if($status=="true"){
            $requestData['pct_status']=1;
        }else{
            $requestData['pct_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectcategory::findOrFail($id);
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
        //$requestData['pct_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectcategory::create($requestData);
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
        'pct_name_or'=> trans('form_lang.pct_name_or'), 
'pct_name_am'=> trans('form_lang.pct_name_am'), 
'pct_name_en'=> trans('form_lang.pct_name_en'), 
'pct_code'=> trans('form_lang.pct_code'), 
'pct_description'=> trans('form_lang.pct_description'), 
'pct_status'=> trans('form_lang.pct_status'), 

    ];
    $rules= [
 'pct_name_or'=> 'max:100', 
'pct_name_am'=> 'max:100', 
'pct_name_en'=> 'max:100', 
'pct_code'=> 'max:20', 
'pct_description'=> 'max:425',  
//'pct_status'=> 'integer', 

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
        $requestData['pct_created_by']=auth()->user()->usr_id;
        $status= $request->input('pct_status');
        if($status=="true"){
            $requestData['pct_status']=1;
        }else{
            $requestData['pct_status']=0;
        }
        $data_info=Modelpmsprojectcategory::create($requestData);
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
    $id=$request->get("pct_id");
    Modelpmsprojectcategory::destroy($id);
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
    Route::resource('project_category', 'PmsprojectcategoryController');
    Route::post('project_category/listgrid', 'Api\PmsprojectcategoryController@listgrid');
    Route::post('project_category/insertgrid', 'Api\PmsprojectcategoryController@insertgrid');
    Route::post('project_category/updategrid', 'Api\PmsprojectcategoryController@updategrid');
    Route::post('project_category/deletegrid', 'Api\PmsprojectcategoryController@deletegrid');
    Route::post('project_category/search', 'PmsprojectcategoryController@search');
    Route::post('project_category/getform', 'PmsprojectcategoryController@getForm');
    Route::post('project_category/getlistform', 'PmsprojectcategoryController@getListForm');

}
}