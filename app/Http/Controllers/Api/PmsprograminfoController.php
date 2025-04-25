<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsprograminfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprograminfoController extends MyController
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
        $query='SELECT pri_id,pri_owner_region_id,pri_owner_zone_id,pri_owner_woreda_id,pri_sector_id,pri_name_or,pri_name_am,pri_name_en,pri_program_code,pri_description,pri_create_time,pri_update_time,pri_delete_time,pri_created_by,pri_status FROM pms_program_info ';       
        
        $query .=' WHERE pri_id='.$id.' ';
        $data_info=DB::select(DB::raw($query));
        if(isset($data_info) && !empty($data_info)){
            $data['pms_program_info_data']=$data_info[0];
        }
        //$data_info = Modelpmsprograminfo::findOrFail($id);
        //$data['pms_program_info_data']=$data_info;
        $data['page_title']=trans("form_lang.pms_program_info");
        return view('program_info.show_pms_program_info', $data);
    }
    //Get List
    public function listgrid(Request $request){
     $query="SELECT pri_id,pri_owner_region_id,pri_owner_zone_id,pri_owner_woreda_id,pri_sector_id,pri_name_or,pri_name_am,pri_name_en,pri_program_code,pri_description,pri_create_time,pri_update_time,pri_delete_time,pri_created_by,pri_status,1 AS is_editable, 1 AS is_deletable FROM pms_program_info ";

$query .=' WHERE 1=1';
$priownerzoneid=$request->input('pri_owner_zone_id');
if(isset($priownerzoneid) && isset($priownerzoneid)){
$query .=" AND pri_owner_zone_id='".$priownerzoneid."'"; 
}
$priownerworedaid=$request->input('pri_owner_woreda_id');
if(isset($priownerworedaid) && isset($priownerworedaid)){
$query .=" AND pri_owner_woreda_id='".$priownerworedaid."'";
}
$prisectorid=$request->input('pri_sector_id');
if(isset($prisectorid) && isset($prisectorid)){
$query .=" AND pri_sector_id='".$prisectorid."'";
}
$priprogramcode=$request->input('pri_program_code');
if(isset($priprogramcode) && isset($priprogramcode)){
$query .=" AND pri_program_code='".$priprogramcode."'"; 
}
$priparent_id=$request->input('parent_id');
if(isset($priparent_id) && isset($priparent_id)){
$query .=" AND pri_parent_id='".$priparent_id."'"; 
}
$data_info=DB::select($query);
$permission=$this->getDateParameter(1)==true ? 1 : 0;
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>$permission,'is_role_deletable'=>$permission,'is_role_can_add'=>$permission)
);
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//Update Data
public function updategrid(Request $request)
{
    $attributeNames = [
        'pri_owner_region_id'=> trans('form_lang.pri_owner_region_id'), 
'pri_owner_zone_id'=> trans('form_lang.pri_owner_zone_id'), 
'pri_owner_woreda_id'=> trans('form_lang.pri_owner_woreda_id'), 
'pri_sector_id'=> trans('form_lang.pri_sector_id'), 
'pri_name_or'=> trans('form_lang.pri_name_or'), 
'pri_name_am'=> trans('form_lang.pri_name_am'), 
'pri_name_en'=> trans('form_lang.pri_name_en'), 
'pri_program_code'=> trans('form_lang.pri_program_code'), 
'pri_description'=> trans('form_lang.pri_description'), 
'pri_status'=> trans('form_lang.pri_status'), 

    ];
    $rules= [
       // 'pri_owner_region_id'=> 'required', 
//'pri_owner_zone_id'=> 'required', 
//'pri_owner_woreda_id'=> 'required', 
//'pri_sector_id'=> 'required', 
'pri_name_or'=> 'max:200', 
'pri_name_am'=> 'max:50', 
'pri_name_en'=> 'max:50', 
'pri_program_code'=> 'max:200', 
'pri_description'=> 'max:425'

    ];
   $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update");
if ($validationResult !== false) {
    return $validationResult;
}
    try{
        $id=$request->get("pri_id");
        $requestData = $request->all();            
        $status= $request->input('pri_status');
        if($status=="true"){
            $requestData['pri_status']=1;
        }else{
            $requestData['pri_status']=0;
        }
        if(isset($id) && !empty($id)){
            $data_info = Modelpmsprograminfo::findOrFail($id);
            //$requestData['pri_parent_id']=$request->get('parent_id');
            $requestData['pri_object_type_id']=$request->get('object_type_id');
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
        $requestData['pri_parent_id']=$request->get('parent_id');
        $requestData['pri_object_type_id']=$request->get('object_type_id');
        $data_info=Modelpmsprograminfo::create($requestData);
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
//Insert Data
public function insertgrid(Request $request)
{
    $attributeNames = [
        'pri_owner_region_id'=> trans('form_lang.pri_owner_region_id'), 
'pri_owner_zone_id'=> trans('form_lang.pri_owner_zone_id'), 
'pri_owner_woreda_id'=> trans('form_lang.pri_owner_woreda_id'), 
'pri_sector_id'=> trans('form_lang.pri_sector_id'), 
'pri_name_or'=> trans('form_lang.pri_name_or'), 
'pri_name_am'=> trans('form_lang.pri_name_am'), 
'pri_name_en'=> trans('form_lang.pri_name_en'), 
'pri_program_code'=> trans('form_lang.pri_program_code'), 
'pri_description'=> trans('form_lang.pri_description'), 
'pri_status'=> trans('form_lang.pri_status'),
    ];
    $rules= [
  // 'pri_owner_region_id'=> 'required', 
//'pri_owner_zone_id'=> 'required', 
//'pri_owner_woreda_id'=> 'required', 
//'pri_sector_id'=> 'required', 
'pri_name_or'=> 'max:200', 
'pri_name_am'=> 'max:50', 
'pri_name_en'=> 'max:50', 
'pri_program_code'=> 'max:200', 
'pri_description'=> 'max:425'
    ];
   $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        $requestData['pri_created_by']=auth()->user()->usr_Id;
        $status= $request->input('pri_status');
        if($status=="true"){
            $requestData['pri_status']=1;
        }else{
            $requestData['pri_status']=0;
        }
        $requestData['pri_created_by']=1;
        $requestData['pri_parent_id']=$request->get('parent_id');
        $requestData['pri_object_type_id']=$request->get('object_type_id');
        $data_info=Modelpmsprograminfo::create($requestData);
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
//Delete Data
public function deletegrid(Request $request)
{
    $id=$request->get("pri_id");
    Modelpmsprograminfo::destroy($id);
    $resultObject= array(
        "odata.metadata"=>"",
        "value" =>"",
        "statusCode"=>200,
        "type"=>"delete",
        "deleted_id"=>$id,
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
//to populate projects list based on selected program
    public function listprogramtree(Request $request){
        $permissionData=$this->getPagePermission($request,9, "project_info");
        $prjsectorid=$request->input('pri_sector_id');
        $parentId=$request->input('parent_id');
        $objectTypeId=$request->input('object_type_id');        
        $query='WITH RECURSIVE program_hierarchy AS (
    -- Anchor member: Start from the root project (change the ID as needed)
    SELECT 
        pri_id AS id,                     -- Primary key
        pri_name_en AS name,
        pri_parent_id AS "rootId",        -- Parent reference
        ARRAY[]::json[] AS children,      -- Placeholder for children
        pri_object_type_id,
        pri_start_date,
        pri_end_date,
        pri_description,
        pri_name_or,
        pri_name_am,
        pri_sector_id,
        pri_program_code
    FROM pms_program_info
    WHERE pri_sector_id ='.$prjsectorid.' AND pri_object_type_id=1
    UNION ALL
    -- Recursive member: Get children of the current node
    SELECT 
        p.pri_id AS id,                   -- Primary key
        p.pri_name_en AS name,
        p.pri_parent_id AS "rootId",
        ARRAY[]::json[] AS children,
        p.pri_object_type_id,
        p.pri_start_date,
        p.pri_end_date,
        p.pri_description,
        p.pri_name_or,
        p.pri_name_am,
        p.pri_sector_id,
        p.pri_program_code
    FROM pms_program_info p
    INNER JOIN program_hierarchy ph ON p.pri_parent_id = ph.id 
)
SELECT * FROM program_hierarchy';
        $data_info=DB::select($query);
        if(isset($data_info) && !empty($data_info)){
        $hierarchicalData = $this->buildHierarchy(json_decode(json_encode($data_info), true));
}else{
    $hierarchicalData=array();
}

        //$this->getQueryInfo($query);        
        $resultObject= array(
            "data" =>$hierarchicalData,
            "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0)
         );
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }
function buildHierarchy(array $elements, $parentId=2) {
    $branch = [];
    //dd($elements);
    foreach ($elements as $element) {
        //dd($element);
        if ($element['rootId'] == $parentId) {
            $children = $this->buildHierarchy($elements, $element['id']);
            $element['children'] = $children;
            $branch[] = $element;
        }
    }
    return $branch;
}
}