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

    public function listgrid(Request $request){
        $canListData=$this->getSinglePagePermission($request,53,'list',"");
    if(!$canListData){
        return $this->cannotOperate("list");
    }
     $query='SELECT  prj_name,prj_code,psh_id,psh_project_id,psh_name,pms_stakeholder_type.sht_type_name_or AS psh_stakeholder_type,psh_representative_name,psh_representative_phone,psh_role,psh_description,psh_create_time,psh_update_time,psh_delete_time,psh_created_by,psh_status,1 AS is_editable, 1 AS is_deletable FROM pms_project_stakeholder ';       
     $query .= ' INNER JOIN pms_stakeholder_type ON pms_project_stakeholder.psh_stakeholder_type = pms_stakeholder_type.sht_id'; 
$query .= ' INNER JOIN pms_project ON pms_project.prj_id=pms_project_stakeholder.psh_project_id';
     $query .=' WHERE 1=1';
     $pshid=$request->input('psh_id');
if(isset($pshid) && isset($pshid)){
$query .=' AND psh_id="'.$pshid.'"';
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
//START
$pshprojectid=$request->input('project_id');
$requesttype=$request->input('request_type');
if(isset($requesttype) && !empty($requesttype) && $requesttype=='single'){
if(isset($pshprojectid) && isset($pshprojectid)){
$query .= " AND psh_project_id = '$pshprojectid'";
}
}else{
$query=$this->getSearchParam($request,$query);
}
//END
$query.=' ORDER BY psh_id DESC';
$data_info=DB::select($query);
$previledge=array('is_role_editable'=>0,'is_role_deletable'=>0,'is_role_can_add'=>0);
$permission=$this->ownsProject($request,$pshprojectid);
if($permission !=null)
{
   $previledge=array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1); 
}
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>$previledge);

return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
public function updategrid(Request $request)
{
    $id=$request->get("psh_id");
    $canEditData=$this->getSinglePagePermission($request,53,'update',$id);
    if(!$canEditData){
        return $this->cannotOperate("update");
    }
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
'psh_name'=> 'max:200', 
'psh_stakeholder_type'=> 'max:200', 
'psh_representative_name'=> 'max:200', 
'psh_representative_phone'=> 'max:24', 
'psh_role'=> 'max:425', 
'psh_description'=> 'max:425', 
//'psh_status'=> 'integer', 

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update", $id);
if ($validationResult !== false) {
    return $validationResult;
}
    try{
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
            $data_info = Modelpmsprojectstakeholder::find($id);
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
}catch (QueryException $e) {
  return $this->handleDatabaseException($e,"update");
}
}
public function insertgrid(Request $request)
{
    $projectId=$request->input('psh_project_id');
    $canAddData=$this->getSinglePagePermissionProject($request,53,'save',"", $projectId);
    if(!$canAddData){
        return $this->cannotOperate("save");
    }
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
'psh_name'=> 'max:200', 
'psh_stakeholder_type'=> 'max:200', 
'psh_representative_name'=> 'max:200', 
'psh_representative_phone'=> 'max:24', 
'psh_role'=> 'max:425', 
'psh_description'=> 'max:425', 
//'psh_status'=> 'integer', 

    ];
    $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
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
    $id=$request->get("psh_id");
    Modelpmsprojectstakeholder::destroy($id);
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