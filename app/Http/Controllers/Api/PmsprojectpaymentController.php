<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectpayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectpaymentController extends MyController
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
    $searchParams= $this->getSearchSetting('pms_project_payment');
    $dataInfo = Modelpmsprojectpayment::latest();
    $this->searchQuery($searchParams, $request,$dataInfo);
    $perPage = 20;
    $dataInfo =$dataInfo->paginate($perPage);
    $data['pms_project_payment_data']=$dataInfo;
    $generatedSearchInfo= $this->displaySearchForm($searchParams, $request,false, 1, true);
    $generatedSearchInfo=explode("@", $generatedSearchInfo);
    $generatedSearchForm=$generatedSearchInfo[0];
    $generatedSearchTitle=$generatedSearchInfo[1];
    $data['searchForm']=$generatedSearchForm;
    $data['searchTitle']=$generatedSearchTitle;
    $data['page_title']=trans("form_lang.pms_project_payment");
    return view('project_payment.list_pms_project_payment', $data);
}
function getForm(Request $request)
{
    $id=$request->get('id');
    
    
    $data['is_editable']=1;
    if(isset($id) && !empty($id)){
       $data_info = Modelpmsprojectpayment::findOrFail($id);                
       if(isset($data_info) && !empty($data_info)){
        $controllerName="PmsprojectpaymentController";
        $data= $this->validateEdit($data, $data_info['prp_create_time'], $controllerName);
        $data['pms_project_payment_data']=$data_info;
    }
}
$data['page_title']=trans("form_lang.pms_project_payment");
$form= view('project_payment.form_popup_pms_project_payment', $data)->render();
$resultObject = array(
    "" => "", "form" => $form, 'pageTitle'=>trans('form_lang.pms_project_payment'));
return response()->json($resultObject);
}
function getListForm(Request $request)
{
    $id=$request->get('id');
    $data['page_title']='';
    $form= view('project_payment.editable_list_pms_project_payment', $data)->render();
    $resultObject = array(
        "" => "", "form" => $form, 'page_info'=>trans('form_lang.pms_project_payment'));
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
        
        
        $data['page_title']=trans("form_lang.pms_project_payment");
        $data['action_mode']="create";
        return view('project_payment.form_pms_project_payment', $data);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_type'=> trans('form_lang.prp_type'), 
'prp_payment_date_et'=> trans('form_lang.prp_payment_date_et'), 
'prp_payment_date_gc'=> trans('form_lang.prp_payment_date_gc'), 
'prp_payment_amount'=> trans('form_lang.prp_payment_amount'), 
'prp_payment_percentage'=> trans('form_lang.prp_payment_percentage'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_type'=> 'max:200', 
'prp_payment_date_et'=> 'max:200', 
'prp_payment_date_gc'=> 'max:200', 
'prp_payment_amount'=> 'numeric', 
'prp_payment_percentage'=> 'numeric', 
'prp_description'=> 'max:425', 
//'prp_status'=> 'integer', 

    ]; 
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
        $requestData = $request->all();
        $requestData['prp_created_by']=auth()->user()->usr_Id;
        Modelpmsprojectpayment::create($requestData);
        return redirect('project_payment')->with('flash_message',  trans('form_lang.insert_success'));
    }else{
        return redirect('project_payment/create')
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
        $query='SELECT prp_id,prp_project_id,prp_type,prp_payment_date_et,prp_payment_date_gc,prp_payment_amount,prp_payment_percentage,prp_description,prp_create_time,prp_update_time,prp_delete_time,prp_created_by,prp_status FROM pms_project_payment ';       
        
        $query .=' WHERE prp_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_payment_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectpayment::findOrFail($id);
        //$data['pms_project_payment_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_payment");
        return view('project_payment.show_pms_project_payment', $data);
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
        
        
        $data_info = Modelpmsprojectpayment::find($id);
        $data['pms_project_payment_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_payment");
        $data['action_mode']="edit";
        $data['record_id']=$id;
        return view('project_payment.form_pms_project_payment', $data);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_type'=> trans('form_lang.prp_type'), 
'prp_payment_date_et'=> trans('form_lang.prp_payment_date_et'), 
'prp_payment_date_gc'=> trans('form_lang.prp_payment_date_gc'), 
'prp_payment_amount'=> trans('form_lang.prp_payment_amount'), 
'prp_payment_percentage'=> trans('form_lang.prp_payment_percentage'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_type'=> 'max:200', 
'prp_payment_date_et'=> 'max:200', 
'prp_payment_date_gc'=> 'max:200', 
'prp_payment_amount'=> 'numeric', 
'prp_payment_percentage'=> 'numeric', 
'prp_description'=> 'max:425', 
'prp_status'=> 'integer', 

    ];     
    $validator = Validator::make ( $request->all(), $rules );
    $validator->setAttributeNames($attributeNames);
    if (!$validator->fails()) {
     $requestData = $request->all();
     $data_info = Modelpmsprojectpayment::findOrFail($id);
     $data_info->update($requestData);
     $ischanged=$data_info->wasChanged();
     if($ischanged){
         return redirect('project_payment')->with('flash_message',  trans('form_lang.update_success'));
     }else{
        return redirect('project_payment/'.$id.'/edit')
        ->with('flash_message',trans('form_lang.not_changed') )
        ->withInput();
    }
}else{
    return redirect('project_payment/'.$id.'/edit')
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
        Modelpmsprojectpayment::destroy($id);
        return redirect('project_payment')->with('flash_message',  trans('form_lang.delete_success'));
    }
    public function listgrid(Request $request){
     $query='SELECT prj_name,prj_code,prp_id,prp_project_id,prp_type,prp_payment_date_et,prp_payment_date_gc,prp_payment_amount,prp_payment_percentage,prp_description,prp_create_time,prp_update_time,prp_delete_time,prp_created_by,prp_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_payment 
     INNER JOIN pms_project ON pms_project.prj_id=pms_project_payment.prp_project_id';
     $query .=' WHERE 1=1';
    $prjName=$request->input('prj_name');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
}
$startTime=$request->input('payment_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prp_payment_date_gc >='".$startTime." 00 00 00'"; 
}
$endTime=$request->input('payment_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prp_payment_date_gc <='".$endTime." 23 59 59'"; 
}
$prjCode=$request->input('prj_code');
if(isset($prjCode) && isset($prjCode)){
$query .=" AND prj_code='".$prjCode."'"; 
}
$prjlocationregionid=$request->input('prj_location_region_id');
if(isset($prjlocationregionid) && isset($prjlocationregionid)){
//$query .=" AND prj_location_region_id='".$prjlocationregionid."'"; 
}
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
}

$prpprojectid=$request->input('project_id');
if(isset($prpprojectid) && isset($prpprojectid)){
$query .= " AND prp_project_id = '".$prpprojectid."'";

}
$prptype=$request->input('prp_type');
if(isset($prptype) && isset($prptype)){
$query .=" AND prp_type='".$prptype."'"; 
}
$prppaymentdateet=$request->input('prp_payment_date_et');
if(isset($prppaymentdateet) && isset($prppaymentdateet)){
$query .=' AND prp_payment_date_et="'.$prppaymentdateet.'"'; 
}
$prppaymentdategc=$request->input('prp_payment_date_gc');
if(isset($prppaymentdategc) && isset($prppaymentdategc)){
$query .=' AND prp_payment_date_gc="'.$prppaymentdategc.'"'; 
}
$prppaymentamount=$request->input('prp_payment_amount');
if(isset($prppaymentamount) && isset($prppaymentamount)){
$query .=' AND prp_payment_amount="'.$prppaymentamount.'"'; 
}
$prppaymentpercentage=$request->input('prp_payment_percentage');
if(isset($prppaymentpercentage) && isset($prppaymentpercentage)){
$query .=' AND prp_payment_percentage="'.$prppaymentpercentage.'"'; 
}
$prpdescription=$request->input('prp_description');
if(isset($prpdescription) && isset($prpdescription)){
$query .=' AND prp_description="'.$prpdescription.'"'; 
}
$prpcreatetime=$request->input('prp_create_time');
if(isset($prpcreatetime) && isset($prpcreatetime)){
$query .=' AND prp_create_time="'.$prpcreatetime.'"'; 
}
$prpupdatetime=$request->input('prp_update_time');
if(isset($prpupdatetime) && isset($prpupdatetime)){
$query .=' AND prp_update_time="'.$prpupdatetime.'"'; 
}
$prpdeletetime=$request->input('prp_delete_time');
if(isset($prpdeletetime) && isset($prpdeletetime)){
$query .=' AND prp_delete_time="'.$prpdeletetime.'"'; 
}
$prpcreatedby=$request->input('prp_created_by');
if(isset($prpcreatedby) && isset($prpcreatedby)){
$query .=' AND prp_created_by="'.$prpcreatedby.'"'; 
}
$prpstatus=$request->input('prp_status');
if(isset($prpstatus) && isset($prpstatus)){
$query .=' AND prp_status="'.$prpstatus.'"'; 
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_type'=> trans('form_lang.prp_type'), 
'prp_payment_date_et'=> trans('form_lang.prp_payment_date_et'), 
'prp_payment_date_gc'=> trans('form_lang.prp_payment_date_gc'), 
'prp_payment_amount'=> trans('form_lang.prp_payment_amount'), 
'prp_payment_percentage'=> trans('form_lang.prp_payment_percentage'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_type'=> 'max:200', 
'prp_payment_date_et'=> 'max:200', 
'prp_payment_date_gc'=> 'max:200', 
'prp_payment_amount'=> 'numeric', 
'prp_payment_percentage'=> 'numeric', 
'prp_description'=> 'max:425', 
//'prp_status'=> 'integer', 

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
        $id=$request->get("prp_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectpayment::findOrFail($id);
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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $data_info=Modelpmsprojectpayment::create($requestData);
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
        'prp_project_id'=> trans('form_lang.prp_project_id'), 
'prp_type'=> trans('form_lang.prp_type'), 
'prp_payment_date_et'=> trans('form_lang.prp_payment_date_et'), 
'prp_payment_date_gc'=> trans('form_lang.prp_payment_date_gc'), 
'prp_payment_amount'=> trans('form_lang.prp_payment_amount'), 
'prp_payment_percentage'=> trans('form_lang.prp_payment_percentage'), 
'prp_description'=> trans('form_lang.prp_description'), 
'prp_status'=> trans('form_lang.prp_status'), 

    ];
    $rules= [
        'prp_project_id'=> 'max:200', 
'prp_type'=> 'max:200', 
'prp_payment_date_et'=> 'max:200', 
'prp_payment_date_gc'=> 'max:200', 
'prp_payment_amount'=> 'numeric', 
'prp_payment_percentage'=> 'numeric', 
'prp_description'=> 'max:425', 
//'prp_status'=> 'integer', 

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
        //$requestData['prp_created_by']=auth()->user()->usr_Id;
        $requestData['prp_created_by']=1;
        $status= $request->input('prp_status');
        if($status=="true"){
            $requestData['prp_status']=1;
        }else{
            $requestData['prp_status']=0;
        }
        $data_info=Modelpmsprojectpayment::create($requestData);
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
    $id=$request->get("prp_id");
    Modelpmsprojectpayment::destroy($id);
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
}