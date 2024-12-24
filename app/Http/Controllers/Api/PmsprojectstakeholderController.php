<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectstakeholder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectstakeholderController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_stakeholder');
    $dataInfo = Modelpmsprojectstakeholder::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_stakeholder_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_stakeholder");
    return view('project_stakeholder.list_pms_project_stakeholder', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    $pms_stakeholder_type_set=\App\Modelpmsstakeholdertype::latest()->get();

    $data['related_pms_stakeholder_type']= $pms_stakeholder_type_set ;

    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectstakeholder::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectstakeholderController";
        $data= $this->validateEdit($data, $data_info['psh_create_time'], $controllerName);
        $data['pms_project_stakeholder_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_stakeholder");
$form= view('project_stakeholder.form_popup_pms_project_stakeholder', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_stakeholder'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_stakeholder.editable_list_pms_project_stakeholder', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_stakeholder'));
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
        $pms_stakeholder_type_set=\App\Modelpmsstakeholdertype::latest()->get();

        $data['related_pms_stakeholder_type']= $pms_stakeholder_type_set ;

        $data['page_title']=trans("form_lang.pms_project_stakeholder");
        $data['action_mode']="create";
        return view('project_stakeholder.form_pms_project_stakeholder', $data);
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
        'psh_project_id'=> trans('form_lang.psh_project_id'), 
'psh_name'=> trans('form_lang.psh_name'), 
'psh_stakeholder_type'=> trans('form_lang.psh_stakeholder_type'), 
'psh_representative_name'=> trans('form_lang.psh_representative_name'), 
'psh_representative_phone'=> trans('form_lang.psh_representative_phone'), 
'psh_role'=> trans('form_lang.psh_role'), 
'psh_description'=> trans('form_lang.psh_description'), 
'psh_status'=> trans('form_lang.psh_status'), 

    ];
    $rules= [
        'psh_project_id'=> 'max:200', 
'psh_name'=> 'max:200', 
'psh_stakeholder_type'=> 'max:200', 
'psh_representative_name'=> 'max:200', 
'psh_representative_phone'=> 'max:24', 
'psh_role'=> 'max:425', 
'psh_description'=> 'max:425', 
'psh_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['psh_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectstakeholder::create($requestData);
        return redirect('project_stakeholder')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_stakeholder/create')
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
        $query='SELECT psh_id,psh_project_id,psh_name,pms_stakeholder_type.sht_type_name_or AS psh_stakeholder_type,psh_representative_name,psh_representative_phone,psh_role,psh_description,psh_create_time,psh_update_time,psh_delete_time,psh_created_by,psh_status FROM pms_project_stakeholder ';       
        $query .= ' INNER JOIN pms_stakeholder_type ON pms_project_stakeholder.psh_stakeholder_type = pms_stakeholder_type.sht_id'; 

        $query .=' WHERE psh_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_stakeholder_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectstakeholder::findOrFail($id);
        //$data['pms_project_stakeholder_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_stakeholder");
        return view('project_stakeholder.show_pms_project_stakeholder', $data);
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
        $pms_stakeholder_type_set=\App\Modelpmsstakeholdertype::latest()->get();

        $data['related_pms_stakeholder_type']= $pms_stakeholder_type_set ;

        $data_info = Modelpmsprojectstakeholder::find($id);
        $data['pms_project_stakeholder_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_stakeholder");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_stakeholder.form_pms_project_stakeholder', $data);
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
        'psh_project_id'=> trans('form_lang.psh_project_id'), 
'psh_name'=> trans('form_lang.psh_name'), 
'psh_stakeholder_type'=> trans('form_lang.psh_stakeholder_type'), 
'psh_representative_name'=> trans('form_lang.psh_representative_name'), 
'psh_representative_phone'=> trans('form_lang.psh_representative_phone'), 
'psh_role'=> trans('form_lang.psh_role'), 
'psh_description'=> trans('form_lang.psh_description'), 
'psh_status'=> trans('form_lang.psh_status'), 

    ];
    $rules= [
        'psh_project_id'=> 'max:200', 
'psh_name'=> 'max:200', 
'psh_stakeholder_type'=> 'max:200', 
'psh_representative_name'=> 'max:200', 
'psh_representative_phone'=> 'max:24', 
'psh_role'=> 'max:425', 
'psh_description'=> 'max:425', 
'psh_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectstakeholder::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_stakeholder')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_stakeholder/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_stakeholder/'.$id.'/edit')
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
        Modelpmsprojectstakeholder::destroy($id);
        return redirect('project_stakeholder')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT psh_id,psh_project_id,psh_name,pms_stakeholder_type.sht_type_name_or AS psh_stakeholder_type,psh_representative_name,psh_representative_phone,psh_role,psh_description,psh_create_time,psh_update_time,psh_delete_time,psh_created_by,psh_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_stakeholder ';       
     $query .= ' INNER JOIN pms_stakeholder_type ON pms_project_stakeholder.psh_stakeholder_type = pms_stakeholder_type.sht_id'; 

     $query .=' WHERE 1=1';
     $pshid=$request->input('psh_id');
if(isset($pshid) && isset($pshid)){
$query .=' AND psh_id="'.$pshid.'"'; 
}
$pshprojectid=$request->input('project_id');
if(isset($pshprojectid) && isset($pshprojectid)){
$query .= " AND psh_project_id = '$pshprojectid'";
}
$pshname=$request->input('psh_name');
if(isset($pshname) && isset($pshname)){
$query .=' AND psh_name="'.$pshname.'"'; 
}
$pshstakeholdertype=$request->input('psh_stakeholder_type');
if(isset($pshstakeholdertype) && isset($pshstakeholdertype)){
$query .=' AND psh_stakeholder_type="'.$pshstakeholdertype.'"'; 
}
$pshrepresentativename=$request->input('psh_representative_name');
if(isset($pshrepresentativename) && isset($pshrepresentativename)){
$query .=' AND psh_representative_name="'.$pshrepresentativename.'"'; 
}
$pshrepresentativephone=$request->input('psh_representative_phone');
if(isset($pshrepresentativephone) && isset($pshrepresentativephone)){
$query .=' AND psh_representative_phone="'.$pshrepresentativephone.'"'; 
}
$pshrole=$request->input('psh_role');
if(isset($pshrole) && isset($pshrole)){
$query .=' AND psh_role="'.$pshrole.'"'; 
}
$pshdescription=$request->input('psh_description');
if(isset($pshdescription) && isset($pshdescription)){
$query .=' AND psh_description="'.$pshdescription.'"'; 
}
$pshcreatetime=$request->input('psh_create_time');
if(isset($pshcreatetime) && isset($pshcreatetime)){
$query .=' AND psh_create_time="'.$pshcreatetime.'"'; 
}
$pshupdatetime=$request->input('psh_update_time');
if(isset($pshupdatetime) && isset($pshupdatetime)){
$query .=' AND psh_update_time="'.$pshupdatetime.'"'; 
}
$pshdeletetime=$request->input('psh_delete_time');
if(isset($pshdeletetime) && isset($pshdeletetime)){
$query .=' AND psh_delete_time="'.$pshdeletetime.'"'; 
}
$pshcreatedby=$request->input('psh_created_by');
if(isset($pshcreatedby) && isset($pshcreatedby)){
$query .=' AND psh_created_by="'.$pshcreatedby.'"'; 
}
$pshstatus=$request->input('psh_status');
if(isset($pshstatus) && isset($pshstatus)){
$query .=' AND psh_status="'.$pshstatus.'"'; 
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
        'psh_project_id'=> trans('form_lang.psh_project_id'), 
'psh_name'=> trans('form_lang.psh_name'), 
'psh_stakeholder_type'=> trans('form_lang.psh_stakeholder_type'), 
'psh_representative_name'=> trans('form_lang.psh_representative_name'), 
'psh_representative_phone'=> trans('form_lang.psh_representative_phone'), 
'psh_role'=> trans('form_lang.psh_role'), 
'psh_description'=> trans('form_lang.psh_description'), 
'psh_status'=> trans('form_lang.psh_status'), 

    ];
    $rules= [
'psh_project_id'=> 'max:200', 
'psh_name'=> 'max:200', 
'psh_stakeholder_type'=> 'max:200', 
'psh_representative_name'=> 'max:200', 
'psh_representative_phone'=> 'max:24', 
'psh_role'=> 'max:425', 
'psh_description'=> 'max:425', 
//'psh_status'=> 'integer', 

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
        $id=$request->get("psh_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('psh_status');
        if($status=="true"){
            $requestData['psh_status']=1;
        }else{
            $requestData['psh_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectstakeholder::findOrFail($id);
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
        //$requestData['psh_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectstakeholder::create($requestData);
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
        'psh_project_id'=> trans('form_lang.psh_project_id'), 
'psh_name'=> trans('form_lang.psh_name'), 
'psh_stakeholder_type'=> trans('form_lang.psh_stakeholder_type'), 
'psh_representative_name'=> trans('form_lang.psh_representative_name'), 
'psh_representative_phone'=> trans('form_lang.psh_representative_phone'), 
'psh_role'=> trans('form_lang.psh_role'), 
'psh_description'=> trans('form_lang.psh_description'), 
'psh_status'=> trans('form_lang.psh_status'), 

    ];
    $rules= [
        'psh_project_id'=> 'max:200', 
'psh_name'=> 'max:200', 
'psh_stakeholder_type'=> 'max:200', 
'psh_representative_name'=> 'max:200', 
'psh_representative_phone'=> 'max:24', 
'psh_role'=> 'max:425', 
'psh_description'=> 'max:425', 
//'psh_status'=> 'integer', 

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
        //$requestData['psh_created_by']=auth()->user()->usr_Id;
        $requestData['psh_created_by']=1;
        $status= $request->input('psh_status');
        if($status=="true"){
            $requestData['psh_status']=1;
        }else{
            $requestData['psh_status']=0;
        }
        $data_info=Modelpmsprojectstakeholder::create($requestData);
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
    $id=$request->get("psh_id");
    Modelpmsprojectstakeholder::destroy($id);
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
    Route::resource('project_stakeholder', 'PmsprojectstakeholderController');
    Route::post('project_stakeholder/listgrid', 'Api\PmsprojectstakeholderController@listgrid');
    Route::post('project_stakeholder/insertgrid', 'Api\PmsprojectstakeholderController@insertgrid');
    Route::post('project_stakeholder/updategrid', 'Api\PmsprojectstakeholderController@updategrid');
    Route::post('project_stakeholder/deletegrid', 'Api\PmsprojectstakeholderController@deletegrid');
    Route::post('project_stakeholder/search', 'PmsprojectstakeholderController@search');
    Route::post('project_stakeholder/getform', 'PmsprojectstakeholderController@getForm');
    Route::post('project_stakeholder/getlistform', 'PmsprojectstakeholderController@getListForm');

}
}