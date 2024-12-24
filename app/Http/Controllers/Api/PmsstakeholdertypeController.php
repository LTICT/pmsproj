<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsstakeholdertype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsstakeholdertypeController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_stakeholder_type');
    $dataInfo = Modelpmsstakeholdertype::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_stakeholder_type_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_stakeholder_type");
    return view('stakeholder_type.list_pms_stakeholder_type', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsstakeholdertype::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsstakeholdertypeController";
        $data= $this->validateEdit($data, $data_info['sht_create_time'], $controllerName);
        $data['pms_stakeholder_type_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_stakeholder_type");
$form= view('stakeholder_type.form_popup_pms_stakeholder_type', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_stakeholder_type'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('stakeholder_type.editable_list_pms_stakeholder_type', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_stakeholder_type'));
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
        
        
        $data['page_title']=trans("form_lang.pms_stakeholder_type");
        $data['action_mode']="create";
        return view('stakeholder_type.form_pms_stakeholder_type', $data);
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
        'sht_type_name_or'=> trans('form_lang.sht_type_name_or'), 
'sht_type_name_am'=> trans('form_lang.sht_type_name_am'), 
'sht_type_name_en'=> trans('form_lang.sht_type_name_en'), 
'sht_description'=> trans('form_lang.sht_description'), 
'sht_status'=> trans('form_lang.sht_status'), 

    ];
    $rules= [
        'sht_type_name_or'=> 'max:200', 
'sht_type_name_am'=> 'max:60', 
'sht_type_name_en'=> 'max:60', 
'sht_description'=> 'max:425', 
'sht_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['sht_created_by']=auth()->user()->usr_Id;
        Modelpmsstakeholdertype::create($requestData);
        return redirect('stakeholder_type')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('stakeholder_type/create')
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
        $query='SELECT sht_id,sht_type_name_or,sht_type_name_am,sht_type_name_en,sht_description,sht_create_time,sht_update_time,sht_delete_time,sht_created_by,sht_status FROM pms_stakeholder_type ';       
        
        $query .=' WHERE sht_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_stakeholder_type_data']=$data_info[0];
        }
        //$data_info = Modelpmsstakeholdertype::findOrFail($id);
        //$data['pms_stakeholder_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_stakeholder_type");
        return view('stakeholder_type.show_pms_stakeholder_type', $data);
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
        
        
        $data_info = Modelpmsstakeholdertype::find($id);
        $data['pms_stakeholder_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_stakeholder_type");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('stakeholder_type.form_pms_stakeholder_type', $data);
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
        'sht_type_name_or'=> trans('form_lang.sht_type_name_or'), 
'sht_type_name_am'=> trans('form_lang.sht_type_name_am'), 
'sht_type_name_en'=> trans('form_lang.sht_type_name_en'), 
'sht_description'=> trans('form_lang.sht_description'), 
'sht_status'=> trans('form_lang.sht_status'), 

    ];
    $rules= [
        'sht_type_name_or'=> 'max:200', 
'sht_type_name_am'=> 'max:60', 
'sht_type_name_en'=> 'max:60', 
'sht_description'=> 'max:425', 
'sht_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsstakeholdertype::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('stakeholder_type')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('stakeholder_type/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('stakeholder_type/'.$id.'/edit')
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
        Modelpmsstakeholdertype::destroy($id);
        return redirect('stakeholder_type')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT sht_id,sht_type_name_or,sht_type_name_am,sht_type_name_en,sht_description,sht_create_time,sht_update_time,sht_delete_time,sht_created_by,sht_status,1 AS is_editable, 1 AS is_deletable FROM pms_stakeholder_type ';       
     
     $query .=' WHERE 1=1';
     $shtid=$request->input('sht_id');
if(isset($shtid) && isset($shtid)){
$query .=' AND sht_id="'.$shtid.'"'; 
}
$shttypenameor=$request->input('sht_type_name_or');
if(isset($shttypenameor) && isset($shttypenameor)){
$query .=" AND sht_type_name_or LIKE '%".$shttypenameor."%'"; 
}
$shttypenameam=$request->input('sht_type_name_am');
if(isset($shttypenameam) && isset($shttypenameam)){
$query .=' AND sht_type_name_am="'.$shttypenameam.'"'; 
}
$shttypenameen=$request->input('sht_type_name_en');
if(isset($shttypenameen) && isset($shttypenameen)){
$query .=' AND sht_type_name_en="'.$shttypenameen.'"'; 
}
$shtdescription=$request->input('sht_description');
if(isset($shtdescription) && isset($shtdescription)){
$query .=' AND sht_description="'.$shtdescription.'"'; 
}
$shtcreatetime=$request->input('sht_create_time');
if(isset($shtcreatetime) && isset($shtcreatetime)){
$query .=' AND sht_create_time="'.$shtcreatetime.'"'; 
}
$shtupdatetime=$request->input('sht_update_time');
if(isset($shtupdatetime) && isset($shtupdatetime)){
$query .=' AND sht_update_time="'.$shtupdatetime.'"'; 
}
$shtdeletetime=$request->input('sht_delete_time');
if(isset($shtdeletetime) && isset($shtdeletetime)){
$query .=' AND sht_delete_time="'.$shtdeletetime.'"'; 
}
$shtcreatedby=$request->input('sht_created_by');
if(isset($shtcreatedby) && isset($shtcreatedby)){
$query .=' AND sht_created_by="'.$shtcreatedby.'"'; 
}
$shtstatus=$request->input('sht_status');
if(isset($shtstatus) && isset($shtstatus)){
$query .=' AND sht_status="'.$shtstatus.'"'; 
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
        'sht_type_name_or'=> trans('form_lang.sht_type_name_or'), 
'sht_type_name_am'=> trans('form_lang.sht_type_name_am'), 
'sht_type_name_en'=> trans('form_lang.sht_type_name_en'), 
'sht_description'=> trans('form_lang.sht_description'), 
'sht_status'=> trans('form_lang.sht_status'), 

    ];
    $rules= [
'sht_type_name_or'=> 'max:100', 
'sht_type_name_am'=> 'max:100', 
'sht_type_name_en'=> 'max:100', 
'sht_description'=> 'max:425', 

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
        $id=$request->get("sht_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('sht_status');
        if($status=="true"){
            $requestData['sht_status']=1;
        }else{
            $requestData['sht_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsstakeholdertype::findOrFail($id);
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
        //$requestData['sht_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsstakeholdertype::create($requestData);
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
        'sht_type_name_or'=> trans('form_lang.sht_type_name_or'), 
'sht_type_name_am'=> trans('form_lang.sht_type_name_am'), 
'sht_type_name_en'=> trans('form_lang.sht_type_name_en'), 
'sht_description'=> trans('form_lang.sht_description'), 
'sht_status'=> trans('form_lang.sht_status'), 

    ];
    $rules= [
'sht_type_name_or'=> 'max:100', 
'sht_type_name_am'=> 'max:100', 
'sht_type_name_en'=> 'max:100', 
'sht_description'=> 'max:425', 

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
        //$requestData['sht_created_by']=auth()->user()->usr_Id;
        $requestData['sht_created_by']=1;
        $status= $request->input('sht_status');
        if($status=="true"){
            $requestData['sht_status']=1;
        }else{
            $requestData['sht_status']=0;
        }
        $data_info=Modelpmsstakeholdertype::create($requestData);
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
    $id=$request->get("sht_id");
    Modelpmsstakeholdertype::destroy($id);
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
    Route::resource('stakeholder_type', 'PmsstakeholdertypeController');
    Route::post('stakeholder_type/listgrid', 'Api\PmsstakeholdertypeController@listgrid');
    Route::post('stakeholder_type/insertgrid', 'Api\PmsstakeholdertypeController@insertgrid');
    Route::post('stakeholder_type/updategrid', 'Api\PmsstakeholdertypeController@updategrid');
    Route::post('stakeholder_type/deletegrid', 'Api\PmsstakeholdertypeController@deletegrid');
    Route::post('stakeholder_type/search', 'PmsstakeholdertypeController@search');
    Route::post('stakeholder_type/getform', 'PmsstakeholdertypeController@getForm');
    Route::post('stakeholder_type/getlistform', 'PmsstakeholdertypeController@getListForm');

}
}