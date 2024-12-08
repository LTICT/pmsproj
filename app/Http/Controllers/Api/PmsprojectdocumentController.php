<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectdocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectdocumentController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_document');
    $dataInfo = Modelpmsprojectdocument::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_document_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_document");
    return view('project_document.list_pms_project_document', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    $pms_document_type_set=\App\Modelpmsdocumenttype::latest()->get();

    $data['related_pms_document_type']= $pms_document_type_set ;

    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectdocument::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectdocumentController";
        $data= $this->validateEdit($data, $data_info['prd_create_time'], $controllerName);
        $data['pms_project_document_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_document");
$form= view('project_document.form_popup_pms_project_document', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_document'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_document.editable_list_pms_project_document', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_document'));
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
        $pms_document_type_set=\App\Modelpmsdocumenttype::latest()->get();

        $data['related_pms_document_type']= $pms_document_type_set ;

        $data['page_title']=trans("form_lang.pms_project_document");
        $data['action_mode']="create";
        return view('project_document.form_pms_project_document', $data);
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
        'prd_project_id'=> trans('form_lang.prd_project_id'), 
'prd_document_type_id'=> trans('form_lang.prd_document_type_id'), 
'prd_name'=> trans('form_lang.prd_name'), 
'prd_file_path'=> trans('form_lang.prd_file_path'), 
'prd_size'=> trans('form_lang.prd_size'), 
'prd_file_extension'=> trans('form_lang.prd_file_extension'), 
'prd_uploaded_date'=> trans('form_lang.prd_uploaded_date'), 
'prd_description'=> trans('form_lang.prd_description'), 
'prd_status'=> trans('form_lang.prd_status'), 

    ];
    $rules= [
        'prd_project_id'=> 'max:200', 
'prd_document_type_id'=> 'max:200', 
'prd_name'=> 'max:200', 
'prd_file_path'=> 'max:200', 
'prd_size'=> 'max:200', 
'prd_file_extension'=> 'max:200', 
'prd_uploaded_date'=> 'max:10', 
'prd_description'=> 'max:425', 
'prd_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prd_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectdocument::create($requestData);
        return redirect('project_document')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_document/create')
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
        $query='SELECT prd_id,prd_project_id,pms_document_type.pdt_doc_name_or AS prd_document_type_id,prd_name,prd_file_path,prd_size,prd_file_extension,prd_uploaded_date,prd_description,prd_create_time,prd_update_time,prd_delete_time,prd_created_by,prd_status FROM pms_project_document ';       
        $query .= ' INNER JOIN pms_document_type ON pms_project_document.prd_document_type_id = pms_document_type.pdt_id'; 

        $query .=' WHERE prd_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_document_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectdocument::findOrFail($id);
        //$data['pms_project_document_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_document");
        return view('project_document.show_pms_project_document', $data);
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
        $pms_document_type_set=\App\Modelpmsdocumenttype::latest()->get();

        $data['related_pms_document_type']= $pms_document_type_set ;

        $data_info = Modelpmsprojectdocument::find($id);
        $data['pms_project_document_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_document");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_document.form_pms_project_document', $data);
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
        'prd_project_id'=> trans('form_lang.prd_project_id'), 
'prd_document_type_id'=> trans('form_lang.prd_document_type_id'), 
'prd_name'=> trans('form_lang.prd_name'), 
'prd_file_path'=> trans('form_lang.prd_file_path'), 
'prd_size'=> trans('form_lang.prd_size'), 
'prd_file_extension'=> trans('form_lang.prd_file_extension'), 
'prd_uploaded_date'=> trans('form_lang.prd_uploaded_date'), 
'prd_description'=> trans('form_lang.prd_description'), 
'prd_status'=> trans('form_lang.prd_status'), 

    ];
    $rules= [
        'prd_project_id'=> 'max:200', 
'prd_document_type_id'=> 'max:200', 
'prd_name'=> 'max:200', 
'prd_file_path'=> 'max:200', 
'prd_size'=> 'max:200', 
'prd_file_extension'=> 'max:200', 
'prd_uploaded_date'=> 'max:10', 
'prd_description'=> 'max:425', 
'prd_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectdocument::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_document')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_document/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_document/'.$id.'/edit')
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
        Modelpmsprojectdocument::destroy($id);
        return redirect('project_document')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT prd_id,prd_project_id, prd_document_type_id,prd_name,prd_file_path,prd_size,prd_file_extension, prd_uploaded_date,prd_description,prd_create_time,prd_update_time,prd_delete_time,prd_created_by,
     1 AS is_editable, 1 AS is_deletable,COUNT(*) OVER () AS total_count FROM pms_project_document ';       
     //$query .= ' INNER JOIN pms_document_type ON pms_project_document.prd_document_type_id = pms_document_type.pdt_id';
     $query .=' WHERE 1=1';
     $prdid=$request->input('prd_id');
if(isset($prdid) && isset($prdid)){
$query .=' AND prd_id="'.$prdid.'"'; 
}
$prdprojectid=$request->input('project_id');
if(isset($prdprojectid) && isset($prdprojectid)){
$query .=" AND prd_project_id='$prdprojectid'"; 

}
$prddocumenttypeid=$request->input('prd_document_type_id');
if(isset($prddocumenttypeid) && isset($prddocumenttypeid)){
$query .=' AND prd_document_type_id="'.$prddocumenttypeid.'"'; 
}
$prdname=$request->input('prd_name');
if(isset($prdname) && isset($prdname)){
$query .=' AND prd_name="'.$prdname.'"'; 
}
$prdfilepath=$request->input('prd_file_path');
if(isset($prdfilepath) && isset($prdfilepath)){
$query .=' AND prd_file_path="'.$prdfilepath.'"'; 
}
$prdsize=$request->input('prd_size');
if(isset($prdsize) && isset($prdsize)){
$query .=' AND prd_size="'.$prdsize.'"'; 
}
$prdfileextension=$request->input('prd_file_extension');
if(isset($prdfileextension) && isset($prdfileextension)){
$query .=' AND prd_file_extension="'.$prdfileextension.'"'; 
}
$prduploadeddate=$request->input('prd_uploaded_date');
if(isset($prduploadeddate) && isset($prduploadeddate)){
$query .=' AND prd_uploaded_date="'.$prduploadeddate.'"'; 
}
$prddescription=$request->input('prd_description');
if(isset($prddescription) && isset($prddescription)){
$query .=' AND prd_description="'.$prddescription.'"'; 
}
$prdcreatetime=$request->input('prd_create_time');
if(isset($prdcreatetime) && isset($prdcreatetime)){
$query .=' AND prd_create_time="'.$prdcreatetime.'"'; 
}
$prdupdatetime=$request->input('prd_update_time');
if(isset($prdupdatetime) && isset($prdupdatetime)){
$query .=' AND prd_update_time="'.$prdupdatetime.'"'; 
}
$prddeletetime=$request->input('prd_delete_time');
if(isset($prddeletetime) && isset($prddeletetime)){
$query .=' AND prd_delete_time="'.$prddeletetime.'"'; 
}
$prdcreatedby=$request->input('prd_created_by');
if(isset($prdcreatedby) && isset($prdcreatedby)){
$query .=' AND prd_created_by="'.$prdcreatedby.'"'; 
}
$prdstatus=$request->input('prd_status');
if(isset($prdstatus) && isset($prdstatus)){
$query .=' AND prd_status="'.$prdstatus.'"'; 
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
        'prd_project_id'=> trans('form_lang.prd_project_id'), 
'prd_document_type_id'=> trans('form_lang.prd_document_type_id'), 
'prd_name'=> trans('form_lang.prd_name'), 
'prd_file_path'=> trans('form_lang.prd_file_path'), 
'prd_size'=> trans('form_lang.prd_size'), 
'prd_file_extension'=> trans('form_lang.prd_file_extension'), 
'prd_uploaded_date'=> trans('form_lang.prd_uploaded_date'), 
'prd_description'=> trans('form_lang.prd_description'), 
'prd_status'=> trans('form_lang.prd_status'), 

    ];
    $rules= [
        'prd_project_id'=> 'max:200', 
'prd_document_type_id'=> 'max:200', 
'prd_name'=> 'max:200', 
'prd_file_path'=> 'max:200', 
'prd_size'=> 'max:200', 
'prd_file_extension'=> 'max:200', 
'prd_uploaded_date'=> 'max:10', 
'prd_description'=> 'max:425', 
//'prd_status'=> 'integer', 

    ];
    $uploadedFile = $request->file('prd_file'); 
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
        $id=$request->get("prd_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();
        $hasFile=$request->hasFile('prd_file');
                if($hasFile && $uploadedFile->isValid()){
                    $fileName = $uploadedFile->getClientOriginalName();
                    $fileExtension=$uploadedFile->getClientOriginalExtension();
                    $fileSize=$uploadedFile->getSize();
                    $uploadedFile->move(public_path('uploads/projectfiles'), $fileName);
                    $requestData['prd_file_extension']=$fileExtension;
                    $requestData['prd_size']=$fileSize;
                    //dd($fileName);
                    $requestData['prd_file_path']=$fileName;
                }           
        $status= $request->input('prd_status');
        if($status=="true"){
            $requestData['prd_status']=1;
        }else{
            $requestData['prd_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectdocument::findOrFail($id);
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
        //$requestData['prd_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectdocument::create($requestData);
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
        'prd_project_id'=> trans('form_lang.prd_project_id'), 
'prd_document_type_id'=> trans('form_lang.prd_document_type_id'), 
'prd_name'=> trans('form_lang.prd_name'), 
'prd_file_path'=> trans('form_lang.prd_file_path'), 
'prd_size'=> trans('form_lang.prd_size'), 
'prd_file_extension'=> trans('form_lang.prd_file_extension'), 
'prd_uploaded_date'=> trans('form_lang.prd_uploaded_date'), 
'prd_description'=> trans('form_lang.prd_description'), 
'prd_status'=> trans('form_lang.prd_status'), 

    ];
    $rules= [
        'prd_project_id'=> 'max:200', 
'prd_document_type_id'=> 'max:200', 
'prd_name'=> 'max:200', 
'prd_file_path'=> 'max:200', 
'prd_size'=> 'max:200', 
'prd_file_extension'=> 'max:200', 
'prd_uploaded_date'=> 'max:10', 
'prd_description'=> 'max:425', 
//'prd_status'=> 'integer', 

    ];
    $uploadedFile = $request->file('prd_file');
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
       $hasFile=$request->hasFile('prd_file');
                if($hasFile && $uploadedFile->isValid()){
                    $fileName = $uploadedFile->getClientOriginalName();
                    $fileExtension=$uploadedFile->getClientOriginalExtension();
                    $fileSize=$uploadedFile->getSize();
                    $uploadedFile->move(public_path('uploads/projectfiles'), $fileName);
                    $requestData['prd_file_extension']=$fileExtension;
                    $requestData['prd_size']=$fileSize;
                    //dd($fileName);
                    $requestData['prd_file_path']=$fileName;
                } 

        //$status= $request->input('prd_status');
        if($status=="true"){
            $requestData['prd_status']=1;
        }else{
            $requestData['prd_status']=0;
        }
        $data_info=Modelpmsprojectdocument::create($requestData);
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
    $id=$request->get("prd_id");
    Modelpmsprojectdocument::destroy($id);
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
    Route::resource('project_document', 'PmsprojectdocumentController');
    Route::post('project_document/listgrid', 'Api\PmsprojectdocumentController@listgrid');
    Route::post('project_document/insertgrid', 'Api\PmsprojectdocumentController@insertgrid');
    Route::post('project_document/updategrid', 'Api\PmsprojectdocumentController@updategrid');
    Route::post('project_document/deletegrid', 'Api\PmsprojectdocumentController@deletegrid');
    Route::post('project_document/search', 'PmsprojectdocumentController@search');
    Route::post('project_document/getform', 'PmsprojectdocumentController@getForm');
    Route::post('project_document/getlistform', 'PmsprojectdocumentController@getListForm');

}
}