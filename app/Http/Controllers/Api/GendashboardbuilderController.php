<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Modeltblpermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class GendashboardbuilderController extends MyController
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
         $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
        //$userId=1;
         //$userId=13;
     /*    if(1==1){
     $query="SELECT r.rol_name AS role, JSON_ARRAYAGG(JSON_OBJECT( 'name', rd.rod_name,
     'gridArea',rd.rod_display_area, 'width', rd.rod_width, 'height', rd.rod_height,'class_name',
     rd.rod_class,'dashboard_type',rd.rod_dashboard_type, 'end_point',rd.rod_end_point, 'column_list',rd.rod_column_list)) AS components 
     FROM tbl_roles r 
     INNER JOIN tbl_role_dashboard rd ON r.rol_id = rd.rod_role_id
     INNER JOIN tbl_user_role ON r.rol_id=tbl_user_role.url_role_id
     WHERE url_user_id=".$userId." GROUP BY r.rol_id";
         }else{
              $query="SELECT r.rol_name AS role, JSON_ARRAYAGG(JSON_OBJECT( 'name', rd.rod_name,
     'gridArea',rd.rod_display_area, 'width', rd.rod_width, 'height', rd.rod_height,'class_name',
     rd.rod_class,'dashboard_type',rd.rod_dashboard_type, 'end_point',rd.rod_end_point, 'column_list',rd.rod_column_list)) AS components 
     FROM tbl_roles r 
     INNER JOIN tbl_role_dashboard rd ON r.rol_id = rd.rod_role_id
     INNER JOIN tbl_user_role ON r.rol_id=tbl_user_role.url_role_id
     WHERE url_user_id=8 GROUP BY r.rol_id";
         }
$query.=' ORDER BY rod_order_number';
$data_info=DB::select(DB::raw($query));*/
//$resultObject= array("data" =>$data_info);
//return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
//START EXPERIMENT
$query="SELECT rod_group_by,rod_aggregate,rod_grouped_param,rod_class, rod_column_list, rod_class,rod_name,rod_dashboard_type,rod_table_name
     FROM tbl_roles r 
     INNER JOIN tbl_role_dashboard rd ON r.rol_id = rd.rod_role_id
     INNER JOIN tbl_user_role ON r.rol_id=tbl_user_role.url_role_id
     WHERE url_user_id=".$userId."";
     $query.=' ORDER BY rod_order_number';
     $data_info=DB::select($query);
     $combinedArray = [];
foreach($data_info as $dashboard){
//START PROJECT
$objectName=$dashboard->rod_name;
$tableName=$dashboard->rod_table_name;
$dashboardType=$dashboard->rod_dashboard_type;
$className=$dashboard->rod_class;
if($dashboardType=='table'){
   $query="SELECT ".$dashboard->rod_column_list." FROM ".$tableName."";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'chart',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"table","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}else if($dashboardType=='total_count'){
    $countableVar=explode(",",$dashboard->rod_column_list)[0];
     $query="SELECT COUNT(".$countableVar.") AS count_result FROM ".$tableName."";
     
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'total_count',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"total_count","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}else if($dashboardType=='chart'){
    $countableVar=explode(",",$dashboard->rod_column_list);
    $query="SELECT ".$countableVar[0]." AS value, ".$countableVar[1]." AS name FROM ".$tableName."";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'chart',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"chart","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}else if($dashboardType=='group_count'){
    $columnArray=explode(",",$dashboard->rod_column_list);
    $groupBy=$dashboard->rod_group_by;
    $groupedParameter=$dashboard->rod_grouped_param;
    $aggregateType=$dashboard->rod_aggregate;
    $countableVar=explode(",",$dashboard->rod_column_list)[0];
     $query="SELECT ".$groupBy.", ".$aggregateType."(".$groupedParameter.") AS ".$columnArray[1]." FROM ".$tableName." GROUP BY ".$groupBy."";
     
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"".$objectName."", "type"=>'group_count',"column_list"=>"".$dashboard->rod_column_list."","dashboard_type"=>"group_count","class_name"=>"".$className."");
$combinedArray[] = $resultObject1; 
}
//END PROJECT
if(1==2){
//START PROJECT DOCUMENT
$query2="SELECT prd_id AS value,prd_name AS name FROM pms_project_document";
$data_info2=DB::select(DB::raw($query2));
$resultObject2= array("data" =>$data_info2,"name"=>"project_document", "type"=>'chart',"column_list"=>"prd_id,prd_name","dashboard_type"=>"chart","class_name"=>"col-sm-6");
$combinedArray[] = $resultObject2;
//END PROJECT DOCUMENT
}
}
$resultObject= array("data" =>$combinedArray);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
//END EXPERIMENT
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
        $id=$request->get("pem_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pem_status');
        if($status=="true"){
            $requestData['pem_status']=1;
        }else{
            $requestData['pem_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modeltblpermission::findOrFail($id);
            $data_info->update($requestData);
            $ischanged=$data_info->wasChanged();
            $data_info['pag_id']=$request->get('pag_id');
            $data_info['pag_name']=$request->get('pag_name');
            $data_info['is_editable']=1;
            $data_info['is_deletable']=1;
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
        //$requestData['pem_created_by']=auth()->user()->usr_Id;
        $data_info=Modeltblpermission::create($requestData);
        $data_info['pag_id']=$request->get('pag_id');
        $data_info['pag_name']=$request->get('pag_name');
        
         $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1),
            "status_code"=>200,
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
function listRoutes(){
    Route::resource('permission', 'TblpermissionController');
    Route::post('permission/listgrid', 'Api\TblpermissionController@listgrid');
    Route::post('permission/insertgrid', 'Api\TblpermissionController@insertgrid');
    Route::post('permission/updategrid', 'Api\TblpermissionController@updategrid');
    Route::post('permission/deletegrid', 'Api\TblpermissionController@deletegrid');
    Route::post('permission/search', 'TblpermissionController@search');
    Route::post('permission/getform', 'TblpermissionController@getForm');
    Route::post('permission/getlistform', 'TblpermissionController@getListForm');

}

public function dashboardData(Request $request){
         $authenticatedUser = $request->authUser;
        $userId=$authenticatedUser->usr_id;
$combinedArray = [];
//START PROJECT
$query="SELECT COUNT(prj_id) as count_result FROM pms_project 
INNER JOIN pms_sector_information ON pms_project.prj_sector_id=pms_sector_information.sci_id WHERE 1=1 ";
$query=$this->getSearchParam($request,$query);
//$query .=" GROUP BY sci_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_count", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"total_count","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1) && $data_info1 !=="" && $data_info1[0]->count_result !==null){
$combinedArray[] = $resultObject1; 
}

$query="SELECT sci_name_or, COUNT(prj_id) as count_result FROM pms_project INNER JOIN pms_sector_information ON pms_project.prj_sector_id=pms_sector_information.sci_id WHERE 1=1";
$query=$this->getSearchParam($request,$query);
$query .=" GROUP BY sci_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_by_sector", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"table","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1)){
$combinedArray[] = $resultObject1; 
}

$query="SELECT COUNT(prj_id) as value, add_name_or AS name FROM pms_project INNER JOIN gen_address_structure 
ON pms_project.prj_location_zone_id=gen_address_structure.add_id WHERE 1=1";
$query=$this->getSearchParam($request,$query);
$query .=" GROUP BY add_name_or";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_by_address", "type"=>'chart',"column_list"=>"","dashboard_type"=>"chart","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1)){
$combinedArray[] = $resultObject1; 
}

$query="SELECT prp_type, SUM(prp_payment_amount) AS prp_payment_amount
FROM pms_project 
INNER JOIN pms_project_payment ON pms_project_payment.prp_project_id = pms_project.prj_id WHERE 1=1 ";
$query=$this->getSearchParam($request,$query);
$query .=" GROUP BY prp_type";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_project_payment", "type"=>'group_count',"column_list"=>"prp_type,prp_payment_amount","dashboard_type"=>"group_count","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1)){
$combinedArray[] = $resultObject1; 
}

$query="SELECT COUNT(usr_id) as count_result FROM tbl_users ";
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_users_count", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"total_count","class_name"=>"col-sm-4");
$combinedArray[] = $resultObject1; 

$query="SELECT SUM(bdr_released_amount) AS count_result
FROM pms_budget_request
INNER JOIN pms_project ON pms_budget_request.bdr_project_id = pms_project.prj_id WHERE 1=1 ";
$query=$this->getSearchParam($request,$query);
$data_info1=DB::select($query);
$resultObject1= array("data" =>$data_info1,"name"=>"dash_released_budget", "type"=>'chart',"column_list"=>"sci_name_or,count_result","dashboard_type"=>"total_count","class_name"=>"col-sm-4");
if(isset($data_info1) && !empty($data_info1) && $data_info1 !=="" && $data_info1[0]->count_result !==null){
$combinedArray[] = $resultObject1; 
}
$resultObject= array("data" =>$combinedArray);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
//END EXPERIMENT
}
}