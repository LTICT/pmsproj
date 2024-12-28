<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmscontractortype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmscontractortypeController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_contractor_type');
    $dataInfo = Modelpmscontractortype::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_contractor_type_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_contractor_type");
    return view('contractor_type.list_pms_contractor_type', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmscontractortype::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmscontractortypeController";
        $data= $this->validateEdit($data, $data_info['cnt_create_time'], $controllerName);
        $data['pms_contractor_type_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_contractor_type");
$form= view('contractor_type.form_popup_pms_contractor_type', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_contractor_type'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('contractor_type.editable_list_pms_contractor_type', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_contractor_type'));
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
        
        
        $data['page_title']=trans("form_lang.pms_contractor_type");
        $data['action_mode']="create";
        return view('contractor_type.form_pms_contractor_type', $data);
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
        'cnt_type_name_or'=> trans('form_lang.cnt_type_name_or'), 
'cnt_type_name_am'=> trans('form_lang.cnt_type_name_am'), 
'cnt_type_name_en'=> trans('form_lang.cnt_type_name_en'), 
'cnt_description'=> trans('form_lang.cnt_description'), 
'cnt_status'=> trans('form_lang.cnt_status'), 

    ];
    $rules= [
        'cnt_type_name_or'=> 'max:200', 
'cnt_type_name_am'=> 'max:60', 
'cnt_type_name_en'=> 'max:60', 
'cnt_description'=> 'max:425', 
'cnt_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['cnt_created_by']=auth()->user()->usr_Id;
        Modelpmscontractortype::create($requestData);
        return redirect('contractor_type')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('contractor_type/create')
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
        $query='SELECT cnt_id,cnt_type_name_or,cnt_type_name_am,cnt_type_name_en,cnt_description,cnt_create_time,cnt_update_time,cnt_delete_time,cnt_created_by,cnt_status FROM pms_contractor_type ';       
        
        $query .=' WHERE cnt_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_contractor_type_data']=$data_info[0];
        }
        //$data_info = Modelpmscontractortype::findOrFail($id);
        //$data['pms_contractor_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_contractor_type");
        return view('contractor_type.show_pms_contractor_type', $data);
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
        
        
        $data_info = Modelpmscontractortype::find($id);
        $data['pms_contractor_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_contractor_type");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('contractor_type.form_pms_contractor_type', $data);
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
        'cnt_type_name_or'=> trans('form_lang.cnt_type_name_or'), 
'cnt_type_name_am'=> trans('form_lang.cnt_type_name_am'), 
'cnt_type_name_en'=> trans('form_lang.cnt_type_name_en'), 
'cnt_description'=> trans('form_lang.cnt_description'), 
'cnt_status'=> trans('form_lang.cnt_status'), 

    ];
    $rules= [
        'cnt_type_name_or'=> 'max:200', 
'cnt_type_name_am'=> 'max:60', 
'cnt_type_name_en'=> 'max:60', 
'cnt_description'=> 'max:425', 
'cnt_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmscontractortype::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('contractor_type')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('contractor_type/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('contractor_type/'.$id.'/edit')
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
        Modelpmscontractortype::destroy($id);
        return redirect('contractor_type')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT cnt_id,cnt_type_name_or,cnt_type_name_am,cnt_type_name_en,cnt_description,cnt_create_time,cnt_update_time,cnt_delete_time,cnt_created_by,cnt_status,1 AS is_editable, 1 AS is_deletable FROM pms_contractor_type ';       
     
     $query .=' WHERE 1=1';
     $cntid=$request->input('cnt_id');
if(isset($cntid) && isset($cntid)){
$query .=' AND cnt_id="'.$cntid.'"'; 
}
$cnttypenameor=$request->input('cnt_type_name_or');
if(isset($cnttypenameor) && isset($cnttypenameor)){
$query .=" AND cnt_type_name_or LIKE '%".$cnttypenameor."%'"; 

}
$cnttypenameam=$request->input('cnt_type_name_am');
if(isset($cnttypenameam) && isset($cnttypenameam)){
$query .=' AND cnt_type_name_am="'.$cnttypenameam.'"'; 
}
$cnttypenameen=$request->input('cnt_type_name_en');
if(isset($cnttypenameen) && isset($cnttypenameen)){
$query .=' AND cnt_type_name_en="'.$cnttypenameen.'"'; 
}
$cntdescription=$request->input('cnt_description');
if(isset($cntdescription) && isset($cntdescription)){
$query .=' AND cnt_description="'.$cntdescription.'"'; 
}
$cntcreatetime=$request->input('cnt_create_time');
if(isset($cntcreatetime) && isset($cntcreatetime)){
$query .=' AND cnt_create_time="'.$cntcreatetime.'"'; 
}
$cntupdatetime=$request->input('cnt_update_time');
if(isset($cntupdatetime) && isset($cntupdatetime)){
$query .=' AND cnt_update_time="'.$cntupdatetime.'"'; 
}
$cntdeletetime=$request->input('cnt_delete_time');
if(isset($cntdeletetime) && isset($cntdeletetime)){
$query .=' AND cnt_delete_time="'.$cntdeletetime.'"'; 
}
$cntcreatedby=$request->input('cnt_created_by');
if(isset($cntcreatedby) && isset($cntcreatedby)){
$query .=' AND cnt_created_by="'.$cntcreatedby.'"'; 
}
$cntstatus=$request->input('cnt_status');
if(isset($cntstatus) && isset($cntstatus)){
$query .=' AND cnt_status="'.$cntstatus.'"'; 
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
        'cnt_type_name_or'=> trans('form_lang.cnt_type_name_or'), 
'cnt_type_name_am'=> trans('form_lang.cnt_type_name_am'), 
'cnt_type_name_en'=> trans('form_lang.cnt_type_name_en'), 
'cnt_description'=> trans('form_lang.cnt_description'), 
'cnt_status'=> trans('form_lang.cnt_status'), 

    ];
    $rules= [
'cnt_type_name_or'=> 'required|max:30', 
'cnt_type_name_am'=> 'required|max:30', 
'cnt_type_name_en'=> 'required|max:30', 
'cnt_description'=> 'max:425', , 
//'cnt_status'=> 'integer', 

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
        $id=$request->get("cnt_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('cnt_status');
        if($status=="true"){
            $requestData['cnt_status']=1;
        }else{
            $requestData['cnt_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmscontractortype::findOrFail($id);
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
        //$requestData['cnt_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmscontractortype::create($requestData);
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
        'cnt_type_name_or'=> trans('form_lang.cnt_type_name_or'), 
'cnt_type_name_am'=> trans('form_lang.cnt_type_name_am'), 
'cnt_type_name_en'=> trans('form_lang.cnt_type_name_en'), 
'cnt_description'=> trans('form_lang.cnt_description'), 
'cnt_status'=> trans('form_lang.cnt_status'), 

    ];
    $rules= [
'cnt_type_name_or'=> 'required|max:30', 
'cnt_type_name_am'=> 'required|max:30', 
'cnt_type_name_en'=> 'required|max:30', 
'cnt_description'=> 'max:425', 
//'cnt_status'=> 'integer', 

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
        //$requestData['cnt_created_by']=auth()->user()->usr_Id;
        $requestData['cnt_created_by']=1;
        $status= $request->input('cnt_status');
        if($status=="true"){
            $requestData['cnt_status']=1;
        }else{
            $requestData['cnt_status']=0;
        }
        $data_info=Modelpmscontractortype::create($requestData);
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
    $id=$request->get("cnt_id");
    Modelpmscontractortype::destroy($id);
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
    Route::resource('contractor_type', 'PmscontractortypeController');
    Route::post('contractor_type/listgrid', 'Api\PmscontractortypeController@listgrid');
    Route::post('contractor_type/insertgrid', 'Api\PmscontractortypeController@insertgrid');
    Route::post('contractor_type/updategrid', 'Api\PmscontractortypeController@updategrid');
    Route::post('contractor_type/deletegrid', 'Api\PmscontractortypeController@deletegrid');
    Route::post('contractor_type/search', 'PmscontractortypeController@search');
    Route::post('contractor_type/getform', 'PmscontractortypeController@getForm');
    Route::post('contractor_type/getlistform', 'PmscontractortypeController@getListForm');

}
}