<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmscontractterminationreason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmscontractterminationreasonController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_contract_termination_reason');
    $dataInfo = Modelpmscontractterminationreason::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_contract_termination_reason_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_contract_termination_reason");
    return view('contract_termination_reason.list_pms_contract_termination_reason', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmscontractterminationreason::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmscontractterminationreasonController";
        $data= $this->validateEdit($data, $data_info['ctr_create_time'], $controllerName);
        $data['pms_contract_termination_reason_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_contract_termination_reason");
$form= view('contract_termination_reason.form_popup_pms_contract_termination_reason', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_contract_termination_reason'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('contract_termination_reason.editable_list_pms_contract_termination_reason', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_contract_termination_reason'));
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
        
        
        $data['page_title']=trans("form_lang.pms_contract_termination_reason");
        $data['action_mode']="create";
        return view('contract_termination_reason.form_pms_contract_termination_reason', $data);
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
        'ctr_reason_name_or'=> trans('form_lang.ctr_reason_name_or'), 
'ctr_reason_name_am'=> trans('form_lang.ctr_reason_name_am'), 
'ctr_reason_name_en'=> trans('form_lang.ctr_reason_name_en'), 
'ctr_description'=> trans('form_lang.ctr_description'), 
'ctr_status'=> trans('form_lang.ctr_status'), 

    ];
    $rules= [
        'ctr_reason_name_or'=> 'max:200', 
'ctr_reason_name_am'=> 'max:60', 
'ctr_reason_name_en'=> 'max:60', 
'ctr_description'=> 'max:425', 
'ctr_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['ctr_created_by']=auth()->user()->usr_Id;
        Modelpmscontractterminationreason::create($requestData);
        return redirect('contract_termination_reason')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('contract_termination_reason/create')
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
        $query='SELECT ctr_id,ctr_reason_name_or,ctr_reason_name_am,ctr_reason_name_en,ctr_description,ctr_create_time,ctr_update_time,ctr_delete_time,ctr_created_by,ctr_status FROM pms_contract_termination_reason ';       
        
        $query .=' WHERE ctr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_contract_termination_reason_data']=$data_info[0];
        }
        //$data_info = Modelpmscontractterminationreason::findOrFail($id);
        //$data['pms_contract_termination_reason_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_contract_termination_reason");
        return view('contract_termination_reason.show_pms_contract_termination_reason', $data);
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
        
        
        $data_info = Modelpmscontractterminationreason::find($id);
        $data['pms_contract_termination_reason_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_contract_termination_reason");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('contract_termination_reason.form_pms_contract_termination_reason', $data);
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
        'ctr_reason_name_or'=> trans('form_lang.ctr_reason_name_or'), 
'ctr_reason_name_am'=> trans('form_lang.ctr_reason_name_am'), 
'ctr_reason_name_en'=> trans('form_lang.ctr_reason_name_en'), 
'ctr_description'=> trans('form_lang.ctr_description'), 
'ctr_status'=> trans('form_lang.ctr_status'), 

    ];
    $rules= [
        'ctr_reason_name_or'=> 'max:200', 
'ctr_reason_name_am'=> 'max:60', 
'ctr_reason_name_en'=> 'max:60', 
'ctr_description'=> 'max:425', 
'ctr_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmscontractterminationreason::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('contract_termination_reason')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('contract_termination_reason/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('contract_termination_reason/'.$id.'/edit')
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
        Modelpmscontractterminationreason::destroy($id);
        return redirect('contract_termination_reason')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT ctr_id,ctr_reason_name_or,ctr_reason_name_am,ctr_reason_name_en,ctr_description,ctr_create_time,ctr_update_time,ctr_delete_time,ctr_created_by,ctr_status,1 AS is_editable, 1 AS is_deletable FROM pms_contract_termination_reason ';       
     
     $query .=' WHERE 1=1';
     $ctrid=$request->input('ctr_id');
if(isset($ctrid) && isset($ctrid)){
$query .=' AND ctr_id="'.$ctrid.'"'; 
}
$ctrreasonnameor=$request->input('ctr_reason_name_or');
if(isset($ctrreasonnameor) && isset($ctrreasonnameor)){
$query .=" AND ctr_reason_name_or LIKE '%".$ctrreasonnameor."%'"; 
}
$ctrreasonnameam=$request->input('ctr_reason_name_am');
if(isset($ctrreasonnameam) && isset($ctrreasonnameam)){
$query .=' AND ctr_reason_name_am="'.$ctrreasonnameam.'"'; 
}
$ctrreasonnameen=$request->input('ctr_reason_name_en');
if(isset($ctrreasonnameen) && isset($ctrreasonnameen)){
$query .=' AND ctr_reason_name_en="'.$ctrreasonnameen.'"'; 
}
$ctrdescription=$request->input('ctr_description');
if(isset($ctrdescription) && isset($ctrdescription)){
$query .=' AND ctr_description="'.$ctrdescription.'"'; 
}
$ctrcreatetime=$request->input('ctr_create_time');
if(isset($ctrcreatetime) && isset($ctrcreatetime)){
$query .=' AND ctr_create_time="'.$ctrcreatetime.'"'; 
}
$ctrupdatetime=$request->input('ctr_update_time');
if(isset($ctrupdatetime) && isset($ctrupdatetime)){
$query .=' AND ctr_update_time="'.$ctrupdatetime.'"'; 
}
$ctrdeletetime=$request->input('ctr_delete_time');
if(isset($ctrdeletetime) && isset($ctrdeletetime)){
$query .=' AND ctr_delete_time="'.$ctrdeletetime.'"'; 
}
$ctrcreatedby=$request->input('ctr_created_by');
if(isset($ctrcreatedby) && isset($ctrcreatedby)){
$query .=' AND ctr_created_by="'.$ctrcreatedby.'"'; 
}
$ctrstatus=$request->input('ctr_status');
if(isset($ctrstatus) && isset($ctrstatus)){
$query .=' AND ctr_status="'.$ctrstatus.'"'; 
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
        'ctr_reason_name_or'=> trans('form_lang.ctr_reason_name_or'), 
'ctr_reason_name_am'=> trans('form_lang.ctr_reason_name_am'), 
'ctr_reason_name_en'=> trans('form_lang.ctr_reason_name_en'), 
'ctr_description'=> trans('form_lang.ctr_description'), 
'ctr_status'=> trans('form_lang.ctr_status'), 

    ];
    $rules= [
'ctr_reason_name_or'=> 'max:100', 
'ctr_reason_name_am'=> 'max:100', 
'ctr_reason_name_en'=> 'max:100', 
'ctr_description'=> 'max:425'
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
        $id=$request->get("ctr_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('ctr_status');
        if($status=="true"){
            $requestData['ctr_status']=1;
        }else{
            $requestData['ctr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmscontractterminationreason::findOrFail($id);
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
        //$requestData['ctr_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmscontractterminationreason::create($requestData);
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
        'ctr_reason_name_or'=> trans('form_lang.ctr_reason_name_or'), 
'ctr_reason_name_am'=> trans('form_lang.ctr_reason_name_am'), 
'ctr_reason_name_en'=> trans('form_lang.ctr_reason_name_en'), 
'ctr_description'=> trans('form_lang.ctr_description'), 
'ctr_status'=> trans('form_lang.ctr_status'), 

    ];
    $rules= [
'ctr_reason_name_or'=> 'max:100', 
'ctr_reason_name_am'=> 'max:100', 
'ctr_reason_name_en'=> 'max:100', 
'ctr_description'=> 'max:425'

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
        //$requestData['ctr_created_by']=auth()->user()->usr_Id;
        $requestData['ctr_created_by']=2;
        $status= $request->input('ctr_status');
        if($status=="true"){
            $requestData['ctr_status']=1;
        }else{
            $requestData['ctr_status']=0;
        }
        $data_info=Modelpmscontractterminationreason::create($requestData);
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
    $id=$request->get("ctr_id");
    Modelpmscontractterminationreason::destroy($id);
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
    Route::resource('contract_termination_reason', 'PmscontractterminationreasonController');
    Route::post('contract_termination_reason/listgrid', 'Api\PmscontractterminationreasonController@listgrid');
    Route::post('contract_termination_reason/insertgrid', 'Api\PmscontractterminationreasonController@insertgrid');
    Route::post('contract_termination_reason/updategrid', 'Api\PmscontractterminationreasonController@updategrid');
    Route::post('contract_termination_reason/deletegrid', 'Api\PmscontractterminationreasonController@deletegrid');
    Route::post('contract_termination_reason/search', 'PmscontractterminationreasonController@search');
    Route::post('contract_termination_reason/getform', 'PmscontractterminationreasonController@getForm');
    Route::post('contract_termination_reason/getlistform', 'PmscontractterminationreasonController@getListForm');

}
}