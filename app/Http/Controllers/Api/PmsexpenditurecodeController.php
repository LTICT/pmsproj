<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsexpenditurecode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsexpenditurecodeController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_expenditure_code');
    $dataInfo = Modelpmsexpenditurecode::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_expenditure_code_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_expenditure_code");
    return view('expenditure_code.list_pms_expenditure_code', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsexpenditurecode::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsexpenditurecodeController";
        $data= $this->validateEdit($data, $data_info['pec_create_time'], $controllerName);
        $data['pms_expenditure_code_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_expenditure_code");
$form= view('expenditure_code.form_popup_pms_expenditure_code', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_expenditure_code'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('expenditure_code.editable_list_pms_expenditure_code', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_expenditure_code'));
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
        
        
        $data['page_title']=trans("form_lang.pms_expenditure_code");
        $data['action_mode']="create";
        return view('expenditure_code.form_pms_expenditure_code', $data);
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
        'pec_name'=> trans('form_lang.pec_name'), 
'pec_code'=> trans('form_lang.pec_code'), 
'pec_status'=> trans('form_lang.pec_status'), 
'pec_description'=> trans('form_lang.pec_description'), 
'pec_created_date'=> trans('form_lang.pec_created_date'), 

    ];
    $rules= [
        'pec_name'=> 'max:200', 
'pec_code'=> 'max:200', 
'pec_status'=> 'integer', 
'pec_description'=> 'max:100', 
'pec_created_date'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pec_created_by']=auth()->user()->usr_Id;
        Modelpmsexpenditurecode::create($requestData);
        return redirect('expenditure_code')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('expenditure_code/create')
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
        $query='SELECT pec_id,pec_name,pec_code,pec_status,pec_description,pec_created_by,pec_created_date,pec_create_time,pec_update_time FROM pms_expenditure_code ';       
        
        $query .=' WHERE pec_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_expenditure_code_data']=$data_info[0];
        }
        //$data_info = Modelpmsexpenditurecode::findOrFail($id);
        //$data['pms_expenditure_code_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_expenditure_code");
        return view('expenditure_code.show_pms_expenditure_code', $data);
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
        
        
        $data_info = Modelpmsexpenditurecode::find($id);
        $data['pms_expenditure_code_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_expenditure_code");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('expenditure_code.form_pms_expenditure_code', $data);
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
        'pec_name'=> trans('form_lang.pec_name'), 
'pec_code'=> trans('form_lang.pec_code'), 
'pec_status'=> trans('form_lang.pec_status'), 
'pec_description'=> trans('form_lang.pec_description'), 
'pec_created_date'=> trans('form_lang.pec_created_date'), 

    ];
    $rules= [
        'pec_name'=> 'max:200', 
'pec_code'=> 'max:200', 
'pec_status'=> 'integer', 
'pec_description'=> 'max:100', 
'pec_created_date'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsexpenditurecode::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('expenditure_code')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('expenditure_code/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('expenditure_code/'.$id.'/edit')
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
        Modelpmsexpenditurecode::destroy($id);
        return redirect('expenditure_code')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $permissionIndex=",0 AS is_editable, 0 AS is_deletable";
     $permissionData=$this->getPagePermission($request,32);
     if(isset($permissionData) && !empty($permissionData)){
        $permissionIndex=",".$permissionData->pem_edit." AS is_editable, ".$permissionData->pem_delete." AS is_deletable";
     }
     $query="SELECT pec_id,pec_name,pec_code,pec_status,pec_description,pec_created_by,pec_created_date,pec_create_time,pec_update_time ".$permissionIndex."  FROM pms_expenditure_code ";       
     
     $query .=' WHERE 1=1';
     $pecid=$request->input('pec_id');
if(isset($pecid) && isset($pecid)){
$query .=' AND pec_id="'.$pecid.'"'; 
}
$pecname=$request->input('pec_name');
if(isset($pecname) && isset($pecname)){
$query .=" AND pec_name LIKE '%".$pecname."%'"; 

}
$peccode=$request->input('pec_code');
if(isset($peccode) && isset($peccode)){
$query .=" AND pec_code LIKE '%".$peccode."%'"; 

}
$pecstatus=$request->input('pec_status');
if(isset($pecstatus) && isset($pecstatus)){
$query .=' AND pec_status="'.$pecstatus.'"'; 
}
$pecdescription=$request->input('pec_description');
if(isset($pecdescription) && isset($pecdescription)){
$query .=' AND pec_description="'.$pecdescription.'"'; 
}
$peccreatedby=$request->input('pec_created_by');
if(isset($peccreatedby) && isset($peccreatedby)){
$query .=' AND pec_created_by="'.$peccreatedby.'"'; 
}
$peccreateddate=$request->input('pec_created_date');
if(isset($peccreateddate) && isset($peccreateddate)){
$query .=' AND pec_created_date="'.$peccreateddate.'"'; 
}
$peccreatetime=$request->input('pec_create_time');
if(isset($peccreatetime) && isset($peccreatetime)){
$query .=' AND pec_create_time="'.$peccreatetime.'"'; 
}
$pecupdatetime=$request->input('pec_update_time');
if(isset($pecupdatetime) && isset($pecupdatetime)){
$query .=' AND pec_update_time="'.$pecupdatetime.'"'; 
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
        'pec_name'=> trans('form_lang.pec_name'), 
'pec_code'=> trans('form_lang.pec_code'), 
'pec_status'=> trans('form_lang.pec_status'), 
'pec_description'=> trans('form_lang.pec_description'), 
'pec_created_date'=> trans('form_lang.pec_created_date'), 

    ];
    $rules= [
'pec_name'=> 'max:100', 
'pec_code'=> 'max:20', 
'pec_description'=> 'max:425', 

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
        $id=$request->get("pec_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pec_status');
        if($status=="true"){
            $requestData['pec_status']=1;
        }else{
            $requestData['pec_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsexpenditurecode::findOrFail($id);
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
        //$requestData['pec_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsexpenditurecode::create($requestData);
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
        'pec_name'=> trans('form_lang.pec_name'), 
'pec_code'=> trans('form_lang.pec_code'), 
'pec_status'=> trans('form_lang.pec_status'), 
'pec_description'=> trans('form_lang.pec_description'), 
'pec_created_date'=> trans('form_lang.pec_created_date'), 

    ];
    $rules= [
'pec_name'=> 'max:100', 
'pec_code'=> 'max:20', 
'pec_description'=> 'max:425',
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
        $requestData['pec_created_by']=auth()->user()->usr_id;
        $status= $request->input('pec_status');
        if($status=="true"){
            $requestData['pec_status']=1;
        }else{
            $requestData['pec_status']=0;
        }
        $data_info=Modelpmsexpenditurecode::create($requestData);
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
    $id=$request->get("pec_id");
    Modelpmsexpenditurecode::destroy($id);
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
    Route::resource('expenditure_code', 'PmsexpenditurecodeController');
    Route::post('expenditure_code/listgrid', 'Api\PmsexpenditurecodeController@listgrid');
    Route::post('expenditure_code/insertgrid', 'Api\PmsexpenditurecodeController@insertgrid');
    Route::post('expenditure_code/updategrid', 'Api\PmsexpenditurecodeController@updategrid');
    Route::post('expenditure_code/deletegrid', 'Api\PmsexpenditurecodeController@deletegrid');
    Route::post('expenditure_code/search', 'PmsexpenditurecodeController@search');
    Route::post('expenditure_code/getform', 'PmsexpenditurecodeController@getForm');
    Route::post('expenditure_code/getlistform', 'PmsexpenditurecodeController@getListForm');

}
}