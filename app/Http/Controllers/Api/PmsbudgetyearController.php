<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsbudgetyear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsbudgetyearController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_budget_year');
    $dataInfo = Modelpmsbudgetyear::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_budget_year_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_budget_year");
    return view('budget_year.list_pms_budget_year', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsbudgetyear::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsbudgetyearController";
        $data= $this->validateEdit($data, $data_info['bdy_create_time'], $controllerName);
        $data['pms_budget_year_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_budget_year");
$form= view('budget_year.form_popup_pms_budget_year', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_budget_year'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('budget_year.editable_list_pms_budget_year', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_budget_year'));
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
        
        
        $data['page_title']=trans("form_lang.pms_budget_year");
        $data['action_mode']="create";
        return view('budget_year.form_pms_budget_year', $data);
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
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
        'bdy_name'=> 'max:200', 
'bdy_code'=> 'max:200', 
'bdy_description'=> 'max:425', 
'bdy_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['bdy_created_by']=auth()->user()->usr_Id;
        Modelpmsbudgetyear::create($requestData);
        return redirect('budget_year')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('budget_year/create')
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
        $query='SELECT bdy_id,bdy_name,bdy_code,bdy_description,bdy_create_time,bdy_update_time,bdy_delete_time,bdy_created_by,bdy_status FROM pms_budget_year ';       
        
        $query .=' WHERE bdy_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_budget_year_data']=$data_info[0];
        }
        //$data_info = Modelpmsbudgetyear::findOrFail($id);
        //$data['pms_budget_year_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_year");
        return view('budget_year.show_pms_budget_year', $data);
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
        
        
        $data_info = Modelpmsbudgetyear::find($id);
        $data['pms_budget_year_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_budget_year");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('budget_year.form_pms_budget_year', $data);
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
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
        'bdy_name'=> 'max:200', 
'bdy_code'=> 'max:200', 
'bdy_description'=> 'max:425', 
'bdy_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsbudgetyear::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('budget_year')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('budget_year/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('budget_year/'.$id.'/edit')
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
        Modelpmsbudgetyear::destroy($id);
        return redirect('budget_year')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT bdy_id,bdy_name,bdy_code,bdy_description,bdy_create_time,bdy_update_time,bdy_delete_time,bdy_created_by,bdy_status,1 AS is_editable, 1 AS is_deletable FROM pms_budget_year ';       
     
     $query .=' WHERE 1=1';
     $bdyid=$request->input('bdy_id');
if(isset($bdyid) && isset($bdyid)){
$query .=' AND bdy_id="'.$bdyid.'"'; 
}
$bdyname=$request->input('bdy_name');
if(isset($bdyname) && isset($bdyname)){
$query .=" AND bdy_name LIKE '%".$bdyname."%'"; 

}
$bdycode=$request->input('bdy_code');
if(isset($bdycode) && isset($bdycode)){
$query .=" AND bdy_code LIKE '%".$bdycode."%'"; 
}
$bdydescription=$request->input('bdy_description');
if(isset($bdydescription) && isset($bdydescription)){
$query .=' AND bdy_description="'.$bdydescription.'"'; 
}
$bdycreatetime=$request->input('bdy_create_time');
if(isset($bdycreatetime) && isset($bdycreatetime)){
$query .=' AND bdy_create_time="'.$bdycreatetime.'"'; 
}
$bdyupdatetime=$request->input('bdy_update_time');
if(isset($bdyupdatetime) && isset($bdyupdatetime)){
$query .=' AND bdy_update_time="'.$bdyupdatetime.'"'; 
}
$bdydeletetime=$request->input('bdy_delete_time');
if(isset($bdydeletetime) && isset($bdydeletetime)){
$query .=' AND bdy_delete_time="'.$bdydeletetime.'"'; 
}
$bdycreatedby=$request->input('bdy_created_by');
if(isset($bdycreatedby) && isset($bdycreatedby)){
$query .=' AND bdy_created_by="'.$bdycreatedby.'"'; 
}
$bdystatus=$request->input('bdy_status');
if(isset($bdystatus) && isset($bdystatus)){
$query .=' AND bdy_status="'.$bdystatus.'"'; 
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
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
      'bdy_name'=> 'required|max:4', 
'bdy_code'=> 'max:10', 
'bdy_description'=> 'max:425'
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
        $id=$request->get("bdy_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('bdy_status');
      /*  if($status=="true"){
            $requestData['bdy_status']=1;
        }else{
            $requestData['bdy_status']=0;
        }*/
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsbudgetyear::findOrFail($id);
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
        //$requestData['bdy_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsbudgetyear::create($requestData);
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
        'bdy_name'=> trans('form_lang.bdy_name'), 
'bdy_code'=> trans('form_lang.bdy_code'), 
'bdy_description'=> trans('form_lang.bdy_description'), 
'bdy_status'=> trans('form_lang.bdy_status'), 

    ];
    $rules= [
        'bdy_name'=> 'required|max:4', 
'bdy_code'=> 'max:10', 
'bdy_description'=> 'max:425', 
//'bdy_status'=> 'integer', 

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
        //$requestData['bdy_created_by']=auth()->user()->usr_Id;
        $requestData['bdy_created_by']=2;
        $status= $request->input('bdy_status');
      
        $data_info=Modelpmsbudgetyear::create($requestData);
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
    $id=$request->get("bdy_id");
    Modelpmsbudgetyear::destroy($id);
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
    Route::resource('budget_year', 'PmsbudgetyearController');
    Route::post('budget_year/listgrid', 'Api\PmsbudgetyearController@listgrid');
    Route::post('budget_year/insertgrid', 'Api\PmsbudgetyearController@insertgrid');
    Route::post('budget_year/updategrid', 'Api\PmsbudgetyearController@updategrid');
    Route::post('budget_year/deletegrid', 'Api\PmsbudgetyearController@deletegrid');
    Route::post('budget_year/search', 'PmsbudgetyearController@search');
    Route::post('budget_year/getform', 'PmsbudgetyearController@getForm');
    Route::post('budget_year/getlistform', 'PmsbudgetyearController@getListForm');

}
}