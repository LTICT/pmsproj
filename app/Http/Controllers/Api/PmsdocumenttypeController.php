<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsdocumenttype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsdocumenttypeController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_document_type');
    $dataInfo = Modelpmsdocumenttype::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_document_type_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_document_type");
    return view('document_type.list_pms_document_type', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsdocumenttype::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsdocumenttypeController";
        $data= $this->validateEdit($data, $data_info['pdt_create_time'], $controllerName);
        $data['pms_document_type_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_document_type");
$form= view('document_type.form_popup_pms_document_type', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_document_type'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('document_type.editable_list_pms_document_type', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_document_type'));
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
        
        
        $data['page_title']=trans("form_lang.pms_document_type");
        $data['action_mode']="create";
        return view('document_type.form_pms_document_type', $data);
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
        'pdt_doc_name_or'=> trans('form_lang.pdt_doc_name_or'), 
'pdt_doc_name_am'=> trans('form_lang.pdt_doc_name_am'), 
'pdt_doc_name_en'=> trans('form_lang.pdt_doc_name_en'), 
'pdt_code'=> trans('form_lang.pdt_code'), 
'pdt_description'=> trans('form_lang.pdt_description'), 
'pdt_status'=> trans('form_lang.pdt_status'), 

    ];
    $rules= [
        'pdt_doc_name_or'=> 'max:200', 
'pdt_doc_name_am'=> 'max:100', 
'pdt_doc_name_en'=> 'max:100', 
'pdt_code'=> 'max:10', 
'pdt_description'=> 'max:425', 
'pdt_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['pdt_created_by']=auth()->user()->usr_Id;
        Modelpmsdocumenttype::create($requestData);
        return redirect('document_type')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('document_type/create')
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
        $query='SELECT pdt_id,pdt_doc_name_or,pdt_doc_name_am,pdt_doc_name_en,pdt_code,pdt_description,pdt_create_time,pdt_update_time,pdt_delete_time,pdt_created_by,pdt_status FROM pms_document_type ';       
        
        $query .=' WHERE pdt_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_document_type_data']=$data_info[0];
        }
        //$data_info = Modelpmsdocumenttype::findOrFail($id);
        //$data['pms_document_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_document_type");
        return view('document_type.show_pms_document_type', $data);
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
        
        
        $data_info = Modelpmsdocumenttype::find($id);
        $data['pms_document_type_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_document_type");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('document_type.form_pms_document_type', $data);
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
        'pdt_doc_name_or'=> trans('form_lang.pdt_doc_name_or'), 
'pdt_doc_name_am'=> trans('form_lang.pdt_doc_name_am'), 
'pdt_doc_name_en'=> trans('form_lang.pdt_doc_name_en'), 
'pdt_code'=> trans('form_lang.pdt_code'), 
'pdt_description'=> trans('form_lang.pdt_description'), 
'pdt_status'=> trans('form_lang.pdt_status'), 

    ];
    $rules= [
        'pdt_doc_name_or'=> 'max:200', 
'pdt_doc_name_am'=> 'max:100', 
'pdt_doc_name_en'=> 'max:100', 
'pdt_code'=> 'max:10', 
'pdt_description'=> 'max:425', 
'pdt_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsdocumenttype::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('document_type')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('document_type/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('document_type/'.$id.'/edit')
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
        Modelpmsdocumenttype::destroy($id);
        return redirect('document_type')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT pdt_id,pdt_doc_name_or,pdt_doc_name_am,pdt_doc_name_en,pdt_code,pdt_description,pdt_create_time,pdt_update_time,pdt_delete_time,pdt_created_by,pdt_status,1 AS is_editable, 1 AS is_deletable FROM pms_document_type ';       
     
     $query .=' WHERE 1=1';
     $pdtid=$request->input('pdt_id');
if(isset($pdtid) && isset($pdtid)){
$query .=' AND pdt_id="'.$pdtid.'"'; 
}
$pdtdocnameor=$request->input('pdt_doc_name_or');
if(isset($pdtdocnameor) && isset($pdtdocnameor)){
$query .=" AND pdt_doc_name_or LIKE '%".$pdtdocnameor."%'"; 
}
$pdtdocnameam=$request->input('pdt_doc_name_am');
if(isset($pdtdocnameam) && isset($pdtdocnameam)){
$query .=' AND pdt_doc_name_am="'.$pdtdocnameam.'"'; 
}
$pdtdocnameen=$request->input('pdt_doc_name_en');
if(isset($pdtdocnameen) && isset($pdtdocnameen)){
$query .=' AND pdt_doc_name_en="'.$pdtdocnameen.'"'; 
}
$pdtcode=$request->input('pdt_code');
if(isset($pdtcode) && isset($pdtcode)){
$query .=' AND pdt_code="'.$pdtcode.'"'; 
}
$pdtdescription=$request->input('pdt_description');
if(isset($pdtdescription) && isset($pdtdescription)){
$query .=' AND pdt_description="'.$pdtdescription.'"'; 
}
$pdtcreatetime=$request->input('pdt_create_time');
if(isset($pdtcreatetime) && isset($pdtcreatetime)){
$query .=' AND pdt_create_time="'.$pdtcreatetime.'"'; 
}
$pdtupdatetime=$request->input('pdt_update_time');
if(isset($pdtupdatetime) && isset($pdtupdatetime)){
$query .=' AND pdt_update_time="'.$pdtupdatetime.'"'; 
}
$pdtdeletetime=$request->input('pdt_delete_time');
if(isset($pdtdeletetime) && isset($pdtdeletetime)){
$query .=' AND pdt_delete_time="'.$pdtdeletetime.'"'; 
}
$pdtcreatedby=$request->input('pdt_created_by');
if(isset($pdtcreatedby) && isset($pdtcreatedby)){
$query .=' AND pdt_created_by="'.$pdtcreatedby.'"'; 
}
$pdtstatus=$request->input('pdt_status');
if(isset($pdtstatus) && isset($pdtstatus)){
$query .=' AND pdt_status="'.$pdtstatus.'"'; 
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
        'pdt_doc_name_or'=> trans('form_lang.pdt_doc_name_or'), 
'pdt_doc_name_am'=> trans('form_lang.pdt_doc_name_am'), 
'pdt_doc_name_en'=> trans('form_lang.pdt_doc_name_en'), 
'pdt_code'=> trans('form_lang.pdt_code'), 
'pdt_description'=> trans('form_lang.pdt_description'), 
'pdt_status'=> trans('form_lang.pdt_status'), 

    ];
    $rules= [
        'pdt_doc_name_or'=> 'max:100', 
'pdt_doc_name_am'=> 'max:100', 
'pdt_doc_name_en'=> 'max:100', 
'pdt_code'=> 'max:20', 
'pdt_description'=> 'max:425', 
//'pdt_status'=> 'integer', 

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
        $id=$request->get("pdt_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('pdt_status');
        if($status=="true"){
            $requestData['pdt_status']=1;
        }else{
            $requestData['pdt_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsdocumenttype::findOrFail($id);
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
        //$requestData['pdt_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsdocumenttype::create($requestData);
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
        'pdt_doc_name_or'=> trans('form_lang.pdt_doc_name_or'), 
'pdt_doc_name_am'=> trans('form_lang.pdt_doc_name_am'), 
'pdt_doc_name_en'=> trans('form_lang.pdt_doc_name_en'), 
'pdt_code'=> trans('form_lang.pdt_code'), 
'pdt_description'=> trans('form_lang.pdt_description'), 
'pdt_status'=> trans('form_lang.pdt_status'), 

    ];
    $rules= [
    'pdt_doc_name_or'=> 'max:100', 
'pdt_doc_name_am'=> 'max:100', 
'pdt_doc_name_en'=> 'max:100', 
'pdt_code'=> 'max:20', 
'pdt_description'=> 'max:425', 
//'pdt_status'=> 'integer', 

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
        //$requestData['pdt_created_by']=auth()->user()->usr_Id;
        $requestData['pdt_created_by']=1;
        $status= $request->input('pdt_status');
        if($status=="true"){
            $requestData['pdt_status']=1;
        }else{
            $requestData['pdt_status']=0;
        }
        $data_info=Modelpmsdocumenttype::create($requestData);
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
    $id=$request->get("pdt_id");
    Modelpmsdocumenttype::destroy($id);
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
    Route::resource('document_type', 'PmsdocumenttypeController');
    Route::post('document_type/listgrid', 'Api\PmsdocumenttypeController@listgrid');
    Route::post('document_type/insertgrid', 'Api\PmsdocumenttypeController@insertgrid');
    Route::post('document_type/updategrid', 'Api\PmsdocumenttypeController@updategrid');
    Route::post('document_type/deletegrid', 'Api\PmsdocumenttypeController@deletegrid');
    Route::post('document_type/search', 'PmsdocumenttypeController@search');
    Route::post('document_type/getform', 'PmsdocumenttypeController@getForm');
    Route::post('document_type/getlistform', 'PmsdocumenttypeController@getListForm');

}
}