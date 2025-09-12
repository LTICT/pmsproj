<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprojectbudgetsource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectbudgetsourceController extends MyController
{
   public function __construct()
   {
    parent::__construct();
    //$this->middleware('auth');
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
        $query='SELECT bsr_id,bsr_name,bsr_project_id,bsr_budget_source_id,bsr_amount,bsr_status,bsr_description,bsr_created_by,bsr_created_date,bsr_create_time,bsr_update_time FROM pms_project_budget_source ';       
        
        $query .=' WHERE bsr_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_project_budget_source_data']=$data_info[0];
        }
        //$data_info = Modelpmsprojectbudgetsource::findOrFail($id);
        //$data['pms_project_budget_source_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_project_budget_source");
        return view('project_budget_source.show_pms_project_budget_source', $data);
    }
    
    public function listgrid(Request $request){
        $canListData=$this->getSinglePagePermission($request,42,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }
     $query='SELECT prj_name,prj_code,pbs_name_or,bsr_id,bsr_name,bsr_project_id,bsr_budget_source_id,bsr_amount,bsr_status,bsr_description,bsr_created_by,bsr_created_date,bsr_create_time,bsr_update_time,1 AS is_editable, 1 AS is_deletable FROM pms_project_budget_source ';
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_source.bsr_project_id'; 
     $query .=' INNER JOIN pms_budget_source ON pms_budget_source.pbs_id=pms_project_budget_source.bsr_budget_source_id';    
     
     $query .=' WHERE 1=1';
     $bsrid=$request->input('bsr_id');
if(isset($prjName) && isset($prjName)){
$query .=" AND prj_name LIKE '%".$prjName."%'"; 
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
$bsrname=$request->input('bsr_name');
if(isset($bsrname) && isset($bsrname)){
$query .=' AND bsr_name="'.$bsrname.'"'; 
}
$bsrprojectid=$request->input('bsr_project_id');
if(isset($bsrprojectid) && isset($bsrprojectid)){
//$query .=" AND bsr_project_id='".$bsrprojectid."'"; 
}
$bsrbudgetsourceid=$request->input('bsr_budget_source_id');
if(isset($bsrbudgetsourceid) && isset($bsrbudgetsourceid)){
$query .=" AND bsr_budget_source_id='".$bsrbudgetsourceid."'"; 
}
$bsramount=$request->input('bsr_amount');
if(isset($bsramount) && isset($bsramount)){
$query .=' AND bsr_amount="'.$bsramount.'"'; 
}
$bsrstatus=$request->input('bsr_status');
if(isset($bsrstatus) && isset($bsrstatus)){
$query .=' AND bsr_status="'.$bsrstatus.'"'; 
}
$bsrdescription=$request->input('bsr_description');
if(isset($bsrdescription) && isset($bsrdescription)){
$query .=' AND bsr_description="'.$bsrdescription.'"'; 
}
$bsrcreatedby=$request->input('bsr_created_by');
if(isset($bsrcreatedby) && isset($bsrcreatedby)){
$query .=' AND bsr_created_by="'.$bsrcreatedby.'"'; 
}
$bsrcreateddate=$request->input('bsr_created_date');
if(isset($bsrcreateddate) && isset($bsrcreateddate)){
$query .=' AND bsr_created_date="'.$bsrcreateddate.'"'; 
}
$bsrcreatetime=$request->input('bsr_create_time');
if(isset($bsrcreatetime) && isset($bsrcreatetime)){
$query .=' AND bsr_create_time="'.$bsrcreatetime.'"'; 
}
$bsrupdatetime=$request->input('bsr_update_time');
if(isset($bsrupdatetime) && isset($bsrupdatetime)){
$query .=' AND bsr_update_time="'.$bsrupdatetime.'"'; 
}

//START
$bsrprojectid=$request->input('project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($bsrprojectid) && isset($bsrprojectid)){
$query .= " AND bsr_project_id = '$bsrprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
//$query.=' ORDER BY emp_first_name, emp_middle_name, emp_last_name';
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
$id=$request->get("bsr_id");
    $canEditData=$this->getSinglePagePermission($request,42,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
$attributeNames = [
        'bsr_name'=> trans('form_lang.bsr_name'), 
'bsr_project_id'=> trans('form_lang.bsr_project_id'), 
'bsr_budget_source_id'=> trans('form_lang.bsr_budget_source_id'), 
'bsr_amount'=> trans('form_lang.bsr_amount'), 
'bsr_status'=> trans('form_lang.bsr_status'), 
'bsr_description'=> trans('form_lang.bsr_description'), 
'bsr_created_date'=> trans('form_lang.bsr_created_date'), 

    ];
    $rules= [
         'bsr_name'=> 'max:200', 
//'bsr_project_id'=> 'max:200', 
'bsr_budget_source_id'=> 'max:200', 
'bsr_amount'=> 'numeric', 
//'bsr_status'=> 'integer', 
'bsr_description'=> 'max:100',
    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        $requestData = $request->all();            
        $status= $request->input('bsr_status');
        if($status=="true"){
            $requestData['bsr_status']=1;
        }else{
            $requestData['bsr_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprojectbudgetsource::find($id);
            if(!isset($data_info) || empty($data_info)){
             return $this->handleUpdateDataException();
            }
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
    }       
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
public function insertgrid(Request $request)
{
    $canAddData=$this->getSinglePagePermission($request,42,'save',"");
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
    $attributeNames = [
        'bsr_name'=> trans('form_lang.bsr_name'), 
'bsr_project_id'=> trans('form_lang.bsr_project_id'), 
'bsr_budget_source_id'=> trans('form_lang.bsr_budget_source_id'), 
'bsr_amount'=> trans('form_lang.bsr_amount'), 
'bsr_status'=> trans('form_lang.bsr_status'), 
'bsr_description'=> trans('form_lang.bsr_description'), 
'bsr_created_date'=> trans('form_lang.bsr_created_date'), 

    ];
    $rules= [
        'bsr_name'=> 'max:200', 
//'bsr_project_id'=> 'max:200', 
'bsr_budget_source_id'=> 'max:200', 
'bsr_amount'=> 'numeric', 
//'bsr_status'=> 'integer', 
'bsr_description'=> 'max:100',

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        //$requestData['bsr_created_by']=auth()->user()->usr_Id;
        $status= $request->input('bsr_status');
        if($status=="true"){
            $requestData['bsr_status']=1;
        }else{
            $requestData['bsr_status']=0;
        }
        $data_info=Modelpmsprojectbudgetsource::create($requestData);
        $data_info['is_editable']=1;
        $data_info['is_deletable']=1;
        return response()->json([
      "data" => $data_info,
        "previledge" => [
            'is_role_editable' => 1,
            'is_role_deletable' => 1
        ],
        "status_code" => 200,
        "type" => "save",
        "errorMsg" => ""
    ]);
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"save");
}
}
public function deletegrid(Request $request)
{
    $id=$request->get("bsr_id");
    Modelpmsprojectbudgetsource::destroy($id);
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