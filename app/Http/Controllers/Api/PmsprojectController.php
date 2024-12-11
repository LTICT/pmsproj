<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project');
    $dataInfo = Modelpmsproject::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project");
    return view('project.list_pms_project', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    $pms_project_status_set=\App\Modelpmsprojectstatus::latest()->get();
$pms_project_category_set=\App\Modelpmsprojectcategory::latest()->get();
$pms_budget_source_set=\App\Modelpmsbudgetsource::latest()->get();

$data['related_pms_project_status']= $pms_project_status_set ;
$data['related_pms_project_category']= $pms_project_category_set ;
$data['related_pms_budget_source']= $pms_budget_source_set ;

    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsproject::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectController";
        $data= $this->validateEdit($data, $data_info['prj_create_time'], $controllerName);
        $data['pms_project_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project");
$form= view('project.form_popup_pms_project', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project.editable_list_pms_project', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project'));
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
        $pms_project_status_set=\App\Modelpmsprojectstatus::latest()->get();
$pms_project_category_set=\App\Modelpmsprojectcategory::latest()->get();
$pms_budget_source_set=\App\Modelpmsbudgetsource::latest()->get();

        $data['related_pms_project_status']= $pms_project_status_set ;
$data['related_pms_project_category']= $pms_project_category_set ;
$data['related_pms_budget_source']= $pms_budget_source_set ;

        $data['page_title']=trans("form_lang.pms_project");
        $data['action_mode']="create";
        return view('project.form_pms_project', $data);
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
        'prj_name'=> trans('form_lang.prj_name'), 
'prj_code'=> trans('form_lang.prj_code'), 
'prj_project_status_id'=> trans('form_lang.prj_project_status_id'), 
'prj_project_category_id'=> trans('form_lang.prj_project_category_id'), 
'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'), 
'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'), 
'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'), 
'prj_geo_location'=> trans('form_lang.prj_geo_location'), 
'prj_sector_id'=> trans('form_lang.prj_sector_id'), 
'prj_location_region_id'=> trans('form_lang.prj_location_region_id'), 
'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'), 
'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'), 
'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'), 
'prj_location_description'=> trans('form_lang.prj_location_description'), 
'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'), 
'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'), 
'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'), 
'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'), 
'prj_owner_description'=> trans('form_lang.prj_owner_description'), 
'prj_start_date_et'=> trans('form_lang.prj_start_date_et'), 
'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'), 
'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'), 
'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'), 
'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'), 
'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'), 
'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'), 
'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'), 
'prj_outcome'=> trans('form_lang.prj_outcome'), 
'prj_deleted'=> trans('form_lang.prj_deleted'), 
'prj_remark'=> trans('form_lang.prj_remark'), 
'prj_created_date'=> trans('form_lang.prj_created_date'), 
'prj_owner_id'=> trans('form_lang.prj_owner_id'), 
'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'), 
'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'), 

    ];
    $rules= [
        'prj_name'=> 'max:200', 
'prj_code'=> 'max:10', 
'prj_project_status_id'=> 'max:200', 
'prj_project_category_id'=> 'max:200', 
'prj_project_budget_source_id'=> 'max:200', 
'prj_total_estimate_budget'=> 'max:200', 
'prj_total_actual_budget'=> 'max:200', 
'prj_geo_location'=> 'max:200', 
'prj_sector_id'=> 'integer', 
'prj_location_region_id'=> 'integer', 
'prj_location_zone_id'=> 'integer', 
'prj_location_woreda_id'=> 'integer', 
'prj_location_kebele_id'=> 'integer', 
'prj_location_description'=> 'max:200', 
'prj_owner_region_id'=> 'integer', 
'prj_owner_zone_id'=> 'integer', 
'prj_owner_woreda_id'=> 'integer', 
'prj_owner_kebele_id'=> 'integer', 
'prj_owner_description'=> 'max:200', 
'prj_start_date_et'=> 'max:15', 
'prj_start_date_gc'=> 'max:15', 
'prj_start_date_plan_et'=> 'max:15', 
'prj_start_date_plan_gc'=> 'max:15', 
'prj_end_date_actual_et'=> 'max:15', 
'prj_end_date_actual_gc'=> 'max:15', 
'prj_end_date_plan_gc'=> 'max:15', 
'prj_end_date_plan_et'=> 'max:15', 
'prj_outcome'=> 'max:425', 
'prj_deleted'=> 'integer', 
'prj_remark'=> 'max:100', 
'prj_created_date'=> 'integer', 
'prj_owner_id'=> 'integer', 
'prj_urban_ben_number'=> 'integer', 
'prj_rural_ben_number'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prj_created_by']=auth()->user()->usr_Id;
        Modelpmsproject::create($requestData);
        return redirect('project')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project/create')
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
        $query='SELECT prj_id,prj_name,prj_code,pms_project_status.prs_status_name_or AS prj_project_status_id,
        pms_project_category.pct_name_or AS prj_project_category_id,pms_budget_source.pbs_name_or AS prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,prj_end_date_plan_gc,prj_end_date_plan_et,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number FROM pms_project ';       
        $query .= ' LEFT JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id'; 
$query .= ' LEFT JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id'; 
$query .= ' LEFT JOIN pms_budget_source ON pms_project.prj_project_budget_source_id = pms_budget_source.pbs_id'; 
        $query .=' WHERE prj_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data=$data_info[0];
            $resultObject= array(
    "data" =>$data,
    "data_available"=>"1");
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }else{
            $resultObject= array(
    "data_available"=>"0");
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }

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
        $pms_project_status_set=\App\Modelpmsprojectstatus::latest()->get();
$pms_project_category_set=\App\Modelpmsprojectcategory::latest()->get();
$pms_budget_source_set=\App\Modelpmsbudgetsource::latest()->get();

        $data['related_pms_project_status']= $pms_project_status_set ;
$data['related_pms_project_category']= $pms_project_category_set ;
$data['related_pms_budget_source']= $pms_budget_source_set ;

        $data_info = Modelpmsproject::find($id);
        $data['pms_project_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project.form_pms_project', $data);
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
        'prj_name'=> trans('form_lang.prj_name'), 
'prj_code'=> trans('form_lang.prj_code'), 
'prj_project_status_id'=> trans('form_lang.prj_project_status_id'), 
'prj_project_category_id'=> trans('form_lang.prj_project_category_id'), 
'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'), 
'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'), 
'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'), 
'prj_geo_location'=> trans('form_lang.prj_geo_location'), 
'prj_sector_id'=> trans('form_lang.prj_sector_id'), 
'prj_location_region_id'=> trans('form_lang.prj_location_region_id'), 
'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'), 
'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'), 
'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'), 
'prj_location_description'=> trans('form_lang.prj_location_description'), 
'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'), 
'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'), 
'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'), 
'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'), 
'prj_owner_description'=> trans('form_lang.prj_owner_description'), 
'prj_start_date_et'=> trans('form_lang.prj_start_date_et'), 
'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'), 
'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'), 
'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'), 
'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'), 
'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'), 
'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'), 
'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'), 
'prj_outcome'=> trans('form_lang.prj_outcome'), 
'prj_deleted'=> trans('form_lang.prj_deleted'), 
'prj_remark'=> trans('form_lang.prj_remark'), 
'prj_created_date'=> trans('form_lang.prj_created_date'), 
'prj_owner_id'=> trans('form_lang.prj_owner_id'), 
'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'), 
'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'), 

    ];
    $rules= [
        'prj_name'=> 'max:200', 
'prj_code'=> 'max:10', 
'prj_project_status_id'=> 'max:200', 
'prj_project_category_id'=> 'max:200', 
'prj_project_budget_source_id'=> 'max:200', 
'prj_total_estimate_budget'=> 'max:200', 
'prj_total_actual_budget'=> 'max:200', 
'prj_geo_location'=> 'max:200', 
'prj_sector_id'=> 'integer', 
'prj_location_region_id'=> 'integer', 
'prj_location_zone_id'=> 'integer', 
'prj_location_woreda_id'=> 'integer', 
'prj_location_kebele_id'=> 'integer', 
'prj_location_description'=> 'max:200', 
'prj_owner_region_id'=> 'integer', 
'prj_owner_zone_id'=> 'integer', 
'prj_owner_woreda_id'=> 'integer', 
'prj_owner_kebele_id'=> 'integer', 
'prj_owner_description'=> 'max:200', 
'prj_start_date_et'=> 'max:15', 
'prj_start_date_gc'=> 'max:15', 
'prj_start_date_plan_et'=> 'max:15', 
'prj_start_date_plan_gc'=> 'max:15', 
'prj_end_date_actual_et'=> 'max:15', 
'prj_end_date_actual_gc'=> 'max:15', 
'prj_end_date_plan_gc'=> 'max:15', 
'prj_end_date_plan_et'=> 'max:15', 
'prj_outcome'=> 'max:425', 
'prj_deleted'=> 'integer', 
'prj_remark'=> 'max:100', 
'prj_created_date'=> 'integer', 
'prj_owner_id'=> 'integer', 
'prj_urban_ben_number'=> 'integer', 
'prj_rural_ben_number'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsproject::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project/'.$id.'/edit')
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
        Modelpmsproject::destroy($id);
        return redirect('project')->with('flash_message',  trans('form_lang.delete_success'));
    }
    
    public function listgrid(Request $request){

/*$query='SELECT prj_id,prj_name,prj_code, pms_project_status.prs_status_name_or As prj_project_status_id,pms_project_category.pct_name_or As prj_project_category_id, pms_budget_source.pbs_name_or AS prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,
prj_geo_location,pms_sector_information.sci_name_or As prj_sector_id, 
 prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,
prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,
prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
prj_end_date_plan_gc,prj_end_date_plan_et,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,
prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 0 AS is_deletable,COUNT(*) OVER () AS total_count  FROM pms_project ';   

$query .= ' LEFT JOIN pms_budget_source ON pms_project.prj_project_budget_source_id = pms_budget_source.pbs_id';  
$query .= ' LEFT JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id'; 

$query .= ' LEFT JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id'; 

$query .= ' LEFT JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id'; */
$query='SELECT prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id, prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,
prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,
prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,
prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
prj_end_date_plan_gc,prj_end_date_plan_et,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,
prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 0 AS is_deletable,COUNT(*) OVER () AS total_count  FROM pms_project ';

 $query .=' WHERE 1=1';
     $prjid=$request->input('prj_id');
if(isset($prjid) && isset($prjid)){
$query .=' AND prj_id="'.$prjid.'"'; 
}
$prjname=$request->input('prj_name');
if(isset($prjname) && isset($prjname)){
$query .=" AND prj_name='".$prjname."'"; 
}
$prjcode=$request->input('prj_code');
if(isset($prjcode) && isset($prjcode)){
$query .=" AND prj_code='".$prjcode."'"; 
}
$prjprojectstatusid=$request->input('prj_project_status_id');
if(isset($prjprojectstatusid) && isset($prjprojectstatusid)){
$query .=' AND prj_project_status_id="'.$prjprojectstatusid.'"'; 
}
$prjprojectcategoryid=$request->input('prj_project_category_id');
if(isset($prjprojectcategoryid) && isset($prjprojectcategoryid)){
$query .=" AND prj_project_category_id='".$prjprojectcategoryid."'"; 
}
$prjprojectbudgetsourceid=$request->input('prj_project_budget_source_id');
if(isset($prjprojectbudgetsourceid) && isset($prjprojectbudgetsourceid)){
$query .=' AND prj_project_budget_source_id="'.$prjprojectbudgetsourceid.'"'; 
}
$prjtotalestimatebudget=$request->input('prj_total_estimate_budget');
if(isset($prjtotalestimatebudget) && isset($prjtotalestimatebudget)){
$query .=' AND prj_total_estimate_budget="'.$prjtotalestimatebudget.'"'; 
}
$prjtotalactualbudget=$request->input('prj_total_actual_budget');
if(isset($prjtotalactualbudget) && isset($prjtotalactualbudget)){
$query .=' AND prj_total_actual_budget="'.$prjtotalactualbudget.'"'; 
}
$prjgeolocation=$request->input('prj_geo_location');
if(isset($prjgeolocation) && isset($prjgeolocation)){
$query .=' AND prj_geo_location="'.$prjgeolocation.'"'; 
}
$prjsectorid=$request->input('prj_sector_id');
if(isset($prjsectorid) && isset($prjsectorid)){
$query .=' AND prj_sector_id="'.$prjsectorid.'"'; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
}
$prjlocationkebeleid=$request->input('prj_location_kebele_id');
if(isset($prjlocationkebeleid) && isset($prjlocationkebeleid)){
$query .=' AND prj_location_kebele_id="'.$prjlocationkebeleid.'"'; 
}
$prjlocationdescription=$request->input('prj_location_description');
if(isset($prjlocationdescription) && isset($prjlocationdescription)){
$query .=' AND prj_location_description="'.$prjlocationdescription.'"'; 
}
$prjownerregionid=$request->input('prj_owner_region_id');
if(isset($prjownerregionid) && isset($prjownerregionid)){
$query .=' AND prj_owner_region_id="'.$prjownerregionid.'"'; 
}
$prjownerzoneid=$request->input('prj_owner_zone_id');
if(isset($prjownerzoneid) && isset($prjownerzoneid)){
$query .=' AND prj_owner_zone_id="'.$prjownerzoneid.'"'; 
}
$prjownerworedaid=$request->input('prj_owner_woreda_id');
if(isset($prjownerworedaid) && isset($prjownerworedaid)){
$query .=' AND prj_owner_woreda_id="'.$prjownerworedaid.'"'; 
}
$prjownerkebeleid=$request->input('prj_owner_kebele_id');
if(isset($prjownerkebeleid) && isset($prjownerkebeleid)){
$query .=' AND prj_owner_kebele_id="'.$prjownerkebeleid.'"'; 
}
$prjownerdescription=$request->input('prj_owner_description');
if(isset($prjownerdescription) && isset($prjownerdescription)){
$query .=' AND prj_owner_description="'.$prjownerdescription.'"'; 
}
$prjstartdateet=$request->input('prj_start_date_et');
if(isset($prjstartdateet) && isset($prjstartdateet)){
$query .=' AND prj_start_date_et="'.$prjstartdateet.'"'; 
}
$prjstartdategc=$request->input('prj_start_date_gc');
if(isset($prjstartdategc) && isset($prjstartdategc)){
$query .=' AND prj_start_date_gc="'.$prjstartdategc.'"'; 
}
$prjstartdateplanet=$request->input('prj_start_date_plan_et');
if(isset($prjstartdateplanet) && isset($prjstartdateplanet)){
$query .=' AND prj_start_date_plan_et="'.$prjstartdateplanet.'"'; 
}
$prjstartdateplangc=$request->input('prj_start_date_plan_gc');
if(isset($prjstartdateplangc) && isset($prjstartdateplangc)){
$query .=' AND prj_start_date_plan_gc="'.$prjstartdateplangc.'"'; 
}
$prjenddateactualet=$request->input('prj_end_date_actual_et');
if(isset($prjenddateactualet) && isset($prjenddateactualet)){
$query .=' AND prj_end_date_actual_et="'.$prjenddateactualet.'"'; 
}
$prjenddateactualgc=$request->input('prj_end_date_actual_gc');
if(isset($prjenddateactualgc) && isset($prjenddateactualgc)){
$query .=' AND prj_end_date_actual_gc="'.$prjenddateactualgc.'"'; 
}
$prjenddateplangc=$request->input('prj_end_date_plan_gc');
if(isset($prjenddateplangc) && isset($prjenddateplangc)){
$query .=' AND prj_end_date_plan_gc="'.$prjenddateplangc.'"'; 
}
$prjenddateplanet=$request->input('prj_end_date_plan_et');
if(isset($prjenddateplanet) && isset($prjenddateplanet)){
$query .=' AND prj_end_date_plan_et="'.$prjenddateplanet.'"'; 
}
$prjoutcome=$request->input('prj_outcome');
if(isset($prjoutcome) && isset($prjoutcome)){
$query .=' AND prj_outcome="'.$prjoutcome.'"'; 
}
$prjdeleted=$request->input('prj_deleted');
if(isset($prjdeleted) && isset($prjdeleted)){
$query .=' AND prj_deleted="'.$prjdeleted.'"'; 
}
$prjremark=$request->input('prj_remark');
if(isset($prjremark) && isset($prjremark)){
$query .=' AND prj_remark="'.$prjremark.'"'; 
}
$prjcreatedby=$request->input('prj_created_by');
if(isset($prjcreatedby) && isset($prjcreatedby)){
$query .=' AND prj_created_by="'.$prjcreatedby.'"'; 
}
$prjcreateddate=$request->input('prj_created_date');
if(isset($prjcreateddate) && isset($prjcreateddate)){
$query .=' AND prj_created_date="'.$prjcreateddate.'"'; 
}
$prjcreatetime=$request->input('prj_create_time');
if(isset($prjcreatetime) && isset($prjcreatetime)){
$query .=' AND prj_create_time="'.$prjcreatetime.'"'; 
}
$prjupdatetime=$request->input('prj_update_time');
if(isset($prjupdatetime) && isset($prjupdatetime)){
$query .=' AND prj_update_time="'.$prjupdatetime.'"'; 
}
$prjownerid=$request->input('prj_owner_id');
if(isset($prjownerid) && isset($prjownerid)){
$query .=' AND prj_owner_id="'.$prjownerid.'"'; 
}
$prjurbanbennumber=$request->input('prj_urban_ben_number');
if(isset($prjurbanbennumber) && isset($prjurbanbennumber)){
$query .=' AND prj_urban_ben_number="'.$prjurbanbennumber.'"'; 
}
$prjruralbennumber=$request->input('prj_rural_ben_number');
if(isset($prjruralbennumber) && isset($prjruralbennumber)){
$query .=' AND prj_rural_ben_number="'.$prjruralbennumber.'"'; 
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
        'prj_name'=> trans('form_lang.prj_name'), 
'prj_code'=> trans('form_lang.prj_code'), 
'prj_project_status_id'=> trans('form_lang.prj_project_status_id'), 
'prj_project_category_id'=> trans('form_lang.prj_project_category_id'), 
'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'), 
'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'), 
'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'), 
'prj_geo_location'=> trans('form_lang.prj_geo_location'), 
'prj_sector_id'=> trans('form_lang.prj_sector_id'), 
'prj_location_region_id'=> trans('form_lang.prj_location_region_id'), 
'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'), 
'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'), 
'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'), 
'prj_location_description'=> trans('form_lang.prj_location_description'), 
'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'), 
'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'), 
'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'), 
'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'), 
'prj_owner_description'=> trans('form_lang.prj_owner_description'), 
'prj_start_date_et'=> trans('form_lang.prj_start_date_et'), 
'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'), 
'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'), 
'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'), 
'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'), 
'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'), 
'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'), 
'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'), 
'prj_outcome'=> trans('form_lang.prj_outcome'), 
'prj_deleted'=> trans('form_lang.prj_deleted'), 
'prj_remark'=> trans('form_lang.prj_remark'), 
'prj_created_date'=> trans('form_lang.prj_created_date'), 
'prj_owner_id'=> trans('form_lang.prj_owner_id'), 
'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'), 
'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'), 

    ];
    $rules= [
        'prj_name'=> 'max:200', 
'prj_code'=> 'max:10', 
'prj_project_status_id'=> 'max:200', 
'prj_project_category_id'=> 'max:200', 
'prj_project_budget_source_id'=> 'max:200', 
'prj_total_estimate_budget'=> 'max:200', 
'prj_total_actual_budget'=> 'max:200', 
'prj_geo_location'=> 'max:200', 
'prj_sector_id'=> 'integer', 
'prj_location_region_id'=> 'integer', 
'prj_location_zone_id'=> 'integer', 
'prj_location_woreda_id'=> 'integer', 
//'prj_location_kebele_id'=> 'integer', 
'prj_location_description'=> 'max:200', 
'prj_start_date_et'=> 'max:15', 
'prj_start_date_gc'=> 'max:15', 
'prj_start_date_plan_et'=> 'max:15', 
'prj_start_date_plan_gc'=> 'max:15', 
'prj_end_date_actual_et'=> 'max:15', 
'prj_end_date_actual_gc'=> 'max:15', 
'prj_end_date_plan_gc'=> 'max:15', 
'prj_end_date_plan_et'=> 'max:15', 
'prj_outcome'=> 'max:425', 
'prj_remark'=> 'max:100',

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
        $id=$request->get("prj_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prj_status');
        if($status=="true"){
            $requestData['prj_status']=1;
        }else{
            $requestData['prj_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsproject::findOrFail($id);
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
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsproject::create($requestData);
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
        'prj_name'=> trans('form_lang.prj_name'), 
'prj_code'=> trans('form_lang.prj_code'), 
'prj_project_status_id'=> trans('form_lang.prj_project_status_id'), 
'prj_project_category_id'=> trans('form_lang.prj_project_category_id'), 
'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'), 
'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'), 
'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'), 
'prj_geo_location'=> trans('form_lang.prj_geo_location'), 
'prj_sector_id'=> trans('form_lang.prj_sector_id'), 
'prj_location_region_id'=> trans('form_lang.prj_location_region_id'), 
'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'), 
'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'), 
'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'), 
'prj_location_description'=> trans('form_lang.prj_location_description'), 
'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'), 
'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'), 
'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'), 
'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'), 
'prj_owner_description'=> trans('form_lang.prj_owner_description'), 
'prj_start_date_et'=> trans('form_lang.prj_start_date_et'), 
'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'), 
'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'), 
'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'), 
'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'), 
'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'), 
'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'), 
'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'), 
'prj_outcome'=> trans('form_lang.prj_outcome'), 
'prj_deleted'=> trans('form_lang.prj_deleted'), 
'prj_remark'=> trans('form_lang.prj_remark'), 
'prj_created_date'=> trans('form_lang.prj_created_date'), 
'prj_owner_id'=> trans('form_lang.prj_owner_id'), 
'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'), 
'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'), 

    ];
    $rules= [
'prj_name'=> 'max:200', 
'prj_code'=> 'max:10', 
'prj_project_status_id'=> 'max:200', 
'prj_project_category_id'=> 'max:200', 
'prj_project_budget_source_id'=> 'max:200', 
'prj_total_estimate_budget'=> 'max:200', 
'prj_total_actual_budget'=> 'max:200', 
'prj_geo_location'=> 'max:200', 
'prj_sector_id'=> 'integer', 
'prj_location_region_id'=> 'integer', 
'prj_location_zone_id'=> 'integer', 
'prj_location_woreda_id'=> 'integer', 
//'prj_location_kebele_id'=> 'integer', 
'prj_location_description'=> 'max:200', 
'prj_start_date_et'=> 'max:15', 
'prj_start_date_gc'=> 'max:15', 
'prj_start_date_plan_et'=> 'max:15', 
'prj_start_date_plan_gc'=> 'max:15', 
'prj_end_date_actual_et'=> 'max:15', 
'prj_end_date_actual_gc'=> 'max:15', 
'prj_end_date_plan_gc'=> 'max:15', 
'prj_end_date_plan_et'=> 'max:15', 
'prj_outcome'=> 'max:425', 
'prj_remark'=> 'max:100'
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
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
        $requestData['prj_created_by']=1;
        $status= $request->input('prj_status');
        if($status=="true"){
            $requestData['prj_status']=1;
        }else{
            $requestData['prj_status']=0;
        }
        $data_info=Modelpmsproject::create($requestData);
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
    $id=$request->get("prj_id");
    Modelpmsproject::destroy($id);
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
    Route::resource('project', 'PmsprojectController');
    Route::post('project/listgrid', 'Api\PmsprojectController@listgrid');
    Route::post('project/insertgrid', 'Api\PmsprojectController@insertgrid');
    Route::post('project/updategrid', 'Api\PmsprojectController@updategrid');
    Route::post('project/deletegrid', 'Api\PmsprojectController@deletegrid');
    Route::post('project/search', 'PmsprojectController@search');
    Route::post('project/getform', 'PmsprojectController@getForm');
    Route::post('project/getlistform', 'PmsprojectController@getListForm');

}
}