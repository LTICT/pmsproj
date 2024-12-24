<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmssectorinformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmssectorinformationController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_sector_information');
    $dataInfo = Modelpmssectorinformation::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_sector_information_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_sector_information");
    return view('sector_information.list_pms_sector_information', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    $prj_sector_category_set=\App\Modelprjsectorcategory::latest()->get();

    $data['related_prj_sector_category']= $prj_sector_category_set ;

    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmssectorinformation::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmssectorinformationController";
        $data= $this->validateEdit($data, $data_info['sci_create_time'], $controllerName);
        $data['pms_sector_information_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_sector_information");
$form= view('sector_information.form_popup_pms_sector_information', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_sector_information'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('sector_information.editable_list_pms_sector_information', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_sector_information'));
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
        $prj_sector_category_set=\App\Modelprjsectorcategory::latest()->get();

        $data['related_prj_sector_category']= $prj_sector_category_set ;

        $data['page_title']=trans("form_lang.pms_sector_information");
        $data['action_mode']="create";
        return view('sector_information.form_pms_sector_information', $data);
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
        'sci_name_or'=> trans('form_lang.sci_name_or'), 
'sci_name_am'=> trans('form_lang.sci_name_am'), 
'sci_name_en'=> trans('form_lang.sci_name_en'), 
'sci_code'=> trans('form_lang.sci_code'), 
'sci_sector_category_id'=> trans('form_lang.sci_sector_category_id'), 
'sci_available_at_region'=> trans('form_lang.sci_available_at_region'), 
'sci_available_at_zone'=> trans('form_lang.sci_available_at_zone'), 
'sci_available_at_woreda'=> trans('form_lang.sci_available_at_woreda'), 
'sci_description'=> trans('form_lang.sci_description'), 
'sci_status'=> trans('form_lang.sci_status'), 

    ];
    $rules= [
        'sci_name_or'=> 'max:200', 
'sci_name_am'=> 'max:100', 
'sci_name_en'=> 'max:100', 
'sci_code'=> 'max:10', 
'sci_sector_category_id'=> 'max:200', 
'sci_available_at_region'=> 'integer', 
'sci_available_at_zone'=> 'integer', 
'sci_available_at_woreda'=> 'integer', 
'sci_description'=> 'max:425', 
'sci_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['sci_created_by']=auth()->user()->usr_Id;
        Modelpmssectorinformation::create($requestData);
        return redirect('sector_information')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('sector_information/create')
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
        $query='SELECT sci_id,sci_name_or,sci_name_am,sci_name_en,sci_code,prj_sector_category.psc_name AS sci_sector_category_id,sci_available_at_region,sci_available_at_zone,sci_available_at_woreda,sci_description,sci_create_time,sci_update_time,sci_delete_time,sci_created_by,sci_status FROM pms_sector_information ';       
        $query .= ' INNER JOIN prj_sector_category ON pms_sector_information.sci_sector_category_id = prj_sector_category.psc_id'; 

        $query .=' WHERE sci_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_sector_information_data']=$data_info[0];
        }
        //$data_info = Modelpmssectorinformation::findOrFail($id);
        //$data['pms_sector_information_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_sector_information");
        return view('sector_information.show_pms_sector_information', $data);
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
        $prj_sector_category_set=\App\Modelprjsectorcategory::latest()->get();

        $data['related_prj_sector_category']= $prj_sector_category_set ;

        $data_info = Modelpmssectorinformation::find($id);
        $data['pms_sector_information_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_sector_information");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('sector_information.form_pms_sector_information', $data);
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
        'sci_name_or'=> trans('form_lang.sci_name_or'), 
'sci_name_am'=> trans('form_lang.sci_name_am'), 
'sci_name_en'=> trans('form_lang.sci_name_en'), 
'sci_code'=> trans('form_lang.sci_code'), 
'sci_sector_category_id'=> trans('form_lang.sci_sector_category_id'), 
'sci_available_at_region'=> trans('form_lang.sci_available_at_region'), 
'sci_available_at_zone'=> trans('form_lang.sci_available_at_zone'), 
'sci_available_at_woreda'=> trans('form_lang.sci_available_at_woreda'), 
'sci_description'=> trans('form_lang.sci_description'), 
'sci_status'=> trans('form_lang.sci_status'), 

    ];
    $rules= [
        'sci_name_or'=> 'max:200', 
'sci_name_am'=> 'max:100', 
'sci_name_en'=> 'max:100', 
'sci_code'=> 'max:10', 
'sci_sector_category_id'=> 'max:200', 
'sci_available_at_region'=> 'integer', 
'sci_available_at_zone'=> 'integer', 
'sci_available_at_woreda'=> 'integer', 
'sci_description'=> 'max:425', 
'sci_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmssectorinformation::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('sector_information')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('sector_information/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('sector_information/'.$id.'/edit')
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
        Modelpmssectorinformation::destroy($id);
        return redirect('sector_information')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
    /* $query='SELECT sci_id,sci_name_or,sci_name_am,sci_name_en,sci_code,prj_sector_category.psc_name AS sci_sector_category_id,region.add_name_or as sci_available_at_region,
         zone.add_name_or as sci_available_at_zone,woreda.add_name_or as sci_available_at_woreda,sci_description,sci_create_time,sci_update_time,sci_delete_time,sci_created_by,sci_status,1 AS is_editable, 1 AS is_deletable FROM pms_sector_information ';       
     $query .= ' INNER JOIN prj_sector_category ON pms_sector_information.sci_sector_category_id = prj_sector_category.psc_id'; 
     $query .= ' INNER JOIN gen_address_structure region ON pms_sector_information.sci_available_at_region = region.add_id';
    $query .= ' INNER JOIN gen_address_structure zone ON pms_sector_information.sci_available_at_zone = zone.add_id';
    $query .= ' INNER JOIN gen_address_structure woreda ON pms_sector_information.sci_available_at_woreda = woreda.add_id';*/

    $query='SELECT sci_id,sci_name_or,sci_name_am,sci_name_en,sci_code,prj_sector_category.psc_name AS sci_sector_category_id, sci_available_at_region, sci_available_at_zone,sci_available_at_woreda,sci_description,sci_create_time,sci_update_time,sci_delete_time,sci_created_by,sci_status,1 AS is_editable, 1 AS is_deletable FROM pms_sector_information ';   
     $query .= ' LEFT JOIN prj_sector_category ON pms_sector_information.sci_sector_category_id = prj_sector_category.psc_id'; 

     $query .=' WHERE 1=1';
     $sciid=$request->input('sci_id');
if(isset($sciid) && isset($sciid)){
$query .=' AND sci_id="'.$sciid.'"'; 
}
$scinameor=$request->input('sci_name_or');
if(isset($scinameor) && isset($scinameor)){
$query .=" AND sci_name_or LIKE '%".$scinameor."%'"; 
}
$scinameam=$request->input('sci_name_am');
if(isset($scinameam) && isset($scinameam)){
$query .=" AND sci_name_am LIKE '%".$scinameam."%'"; 
}
$scinameen=$request->input('sci_name_en');
if(isset($scinameen) && isset($scinameen)){
$query .=" AND sci_name_en LIKE '%".$scinameen."%'"; 
}
$scicode=$request->input('sci_code');
if(isset($scicode) && isset($scicode)){
$query .=' AND sci_code="'.$scicode.'"'; 
}
$scisectorcategoryid=$request->input('sci_sector_category_id');
if(isset($scisectorcategoryid) && isset($scisectorcategoryid)){
$query .=' AND sci_sector_category_id="'.$scisectorcategoryid.'"'; 
}
$sciavailableatregion=$request->input('sci_available_at_region');
if(isset($sciavailableatregion) && isset($sciavailableatregion)){
$query .=' AND sci_available_at_region="'.$sciavailableatregion.'"'; 
}
$sciavailableatzone=$request->input('sci_available_at_zone');
if(isset($sciavailableatzone) && isset($sciavailableatzone)){
$query .=' AND sci_available_at_zone="'.$sciavailableatzone.'"'; 
}
$sciavailableatworeda=$request->input('sci_available_at_woreda');
if(isset($sciavailableatworeda) && isset($sciavailableatworeda)){
$query .=' AND sci_available_at_woreda="'.$sciavailableatworeda.'"'; 
}
$scidescription=$request->input('sci_description');
if(isset($scidescription) && isset($scidescription)){
$query .=' AND sci_description="'.$scidescription.'"'; 
}
$scicreatetime=$request->input('sci_create_time');
if(isset($scicreatetime) && isset($scicreatetime)){
$query .=' AND sci_create_time="'.$scicreatetime.'"'; 
}
$sciupdatetime=$request->input('sci_update_time');
if(isset($sciupdatetime) && isset($sciupdatetime)){
$query .=' AND sci_update_time="'.$sciupdatetime.'"'; 
}
$scideletetime=$request->input('sci_delete_time');
if(isset($scideletetime) && isset($scideletetime)){
$query .=' AND sci_delete_time="'.$scideletetime.'"'; 
}
$scicreatedby=$request->input('sci_created_by');
if(isset($scicreatedby) && isset($scicreatedby)){
$query .=' AND sci_created_by="'.$scicreatedby.'"'; 
}
$scistatus=$request->input('sci_status');
if(isset($scistatus) && isset($scistatus)){
$query .=' AND sci_status="'.$scistatus.'"'; 
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
        'sci_name_or'=> trans('form_lang.sci_name_or'), 
'sci_name_am'=> trans('form_lang.sci_name_am'), 
'sci_name_en'=> trans('form_lang.sci_name_en'), 
'sci_code'=> trans('form_lang.sci_code'), 
'sci_sector_category_id'=> trans('form_lang.sci_sector_category_id'), 
'sci_available_at_region'=> trans('form_lang.sci_available_at_region'), 
'sci_available_at_zone'=> trans('form_lang.sci_available_at_zone'), 
'sci_available_at_woreda'=> trans('form_lang.sci_available_at_woreda'), 
'sci_description'=> trans('form_lang.sci_description'), 
'sci_status'=> trans('form_lang.sci_status'), 

    ];
    $rules= [
        'sci_name_or'=> 'max:200', 
'sci_name_am'=> 'max:100', 
'sci_name_en'=> 'max:100', 
'sci_code'=> 'max:20', 
//'sci_sector_category_id'=> 'max:200', 
'sci_available_at_region'=> 'integer', 
'sci_available_at_zone'=> 'integer', 
'sci_available_at_woreda'=> 'integer', 
'sci_description'=> 'max:425', 
//'sci_status'=> 'integer', 

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
        $id=$request->get("sci_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('sci_status');
        if($status=="true"){
            $requestData['sci_status']=1;
        }else{
            $requestData['sci_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmssectorinformation::findOrFail($id);
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
        //$requestData['sci_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmssectorinformation::create($requestData);
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
        'sci_name_or'=> trans('form_lang.sci_name_or'), 
'sci_name_am'=> trans('form_lang.sci_name_am'), 
'sci_name_en'=> trans('form_lang.sci_name_en'), 
'sci_code'=> trans('form_lang.sci_code'), 
'sci_sector_category_id'=> trans('form_lang.sci_sector_category_id'), 
'sci_available_at_region'=> trans('form_lang.sci_available_at_region'), 
'sci_available_at_zone'=> trans('form_lang.sci_available_at_zone'), 
'sci_available_at_woreda'=> trans('form_lang.sci_available_at_woreda'), 
'sci_description'=> trans('form_lang.sci_description'), 
'sci_status'=> trans('form_lang.sci_status'), 

    ];
    $rules= [
        'sci_name_or'=> 'max:200', 
'sci_name_am'=> 'max:100', 
'sci_name_en'=> 'max:100', 
'sci_code'=> 'max:20', 
//'sci_sector_category_id'=> 'max:200', 
'sci_available_at_region'=> 'integer', 
'sci_available_at_zone'=> 'integer', 
'sci_available_at_woreda'=> 'integer', 
'sci_description'=> 'max:425', 
//'sci_status'=> 'integer', 

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
        //$requestData['sci_created_by']=auth()->user()->usr_Id;
        $requestData['sci_created_by']=1;
        $status= $request->input('sci_status');
        if($status=="true"){
            $requestData['sci_status']=1;
        }else{
            $requestData['sci_status']=0;
        }
        $data_info=Modelpmssectorinformation::create($requestData);
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
    $id=$request->get("sci_id");
    Modelpmssectorinformation::destroy($id);
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
    Route::resource('sector_information', 'PmssectorinformationController');
    Route::post('sector_information/listgrid', 'Api\PmssectorinformationController@listgrid');
    Route::post('sector_information/insertgrid', 'Api\PmssectorinformationController@insertgrid');
    Route::post('sector_information/updategrid', 'Api\PmssectorinformationController@updategrid');
    Route::post('sector_information/deletegrid', 'Api\PmssectorinformationController@deletegrid');
    Route::post('sector_information/search', 'PmssectorinformationController@search');
    Route::post('sector_information/getform', 'PmssectorinformationController@getForm');
    Route::post('sector_information/getlistform', 'PmssectorinformationController@getListForm');

}
}