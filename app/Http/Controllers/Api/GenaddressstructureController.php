<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelgenaddressstructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
//PROPERTY OF LT ICT SOLUTION PLC
class GenaddressstructureController extends MyController
{
 public function __construct()
 {
    parent::__construct();
    //$this->middleware('auth');
}
    //START BY PARENT ID
public function addressByParent(Request $request){
   $query='SELECT  add_id AS id,add_name_or AS name,add_parent_id AS rootId,0 AS selected FROM gen_address_structure ';
   $query .=' WHERE 1=1';
   $addparentid=$request->input('parent_id');
   if(isset($addparentid) && isset($addparentid)){
    $query .= " AND add_parent_id = '$addparentid'";
}
$data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
//END BY PARENT ID
public function listgrid(Request $request){
  $authenticatedUser = $request->authUser;
  $userId=$authenticatedUser->usr_id;
        //$userId=19;
     //$query='SELECT add_id AS id,add_name_or AS name,add_parent_id AS "rootId",0 AS selected FROM gen_address_structure ';
  if($userId!=9){ 
   $query ='WITH RECURSIVE children AS (
    SELECT
    add_id AS id,
    add_name_or AS name,
    0::integer AS "rootId",
    0 AS selected
    FROM
    gen_address_structure
    INNER JOIN tbl_users ON tbl_users.usr_zone_id = gen_address_structure.add_id
    WHERE
    usr_id = '.$userId.'
    UNION ALL
    SELECT
    a.add_id AS id,
    a.add_name_or AS name,
    a.add_parent_id::integer AS "rootId",
    0 AS selected
    FROM
    gen_address_structure a
    INNER JOIN children cd ON a.add_parent_id::text = cd.id::text
)
   SELECT * FROM children';
}else{
    $query='SELECT add_id AS id,add_name_or AS name,add_parent_id AS "rootId",0 AS selected FROM gen_address_structure ';
}
     //$query .=' INNER JOIN tbl_users ON tbl_users.usr_zone_id=gen_address_structure.add_id'; 
     //$query .=" WHERE usr_id=".$userId." ";
$addid=$request->input('add_id');
if(isset($addid) && isset($addid)){
    $query .=' AND add_id="'.$addid.'"'; 
}
$addnameor=$request->input('add_name_or');
if(isset($addnameor) && isset($addnameor)){
    $query .=' AND add_name_or="'.$addnameor.'"'; 
}
$addnameam=$request->input('add_name_am');
if(isset($addnameam) && isset($addnameam)){
    $query .=' AND add_name_am="'.$addnameam.'"'; 
}
$addnameen=$request->input('add_name_en');
if(isset($addnameen) && isset($addnameen)){
    $query .=' AND add_name_en="'.$addnameen.'"'; 
}
$addtype=$request->input('add_type');
if(isset($addtype) && isset($addtype)){
    $query .=' AND add_type="'.$addtype.'"'; 
}
$addparentid=$request->input('add_parent_id');
if(isset($addparentid) && isset($addparentid)){
    $query .= " AND add_parent_id = '$addparentid'";
}
$addphone=$request->input('add_phone');
if(isset($addphone) && isset($addphone)){
    $query .=' AND add_phone="'.$addphone.'"'; 
}
$adddescription=$request->input('add_description');
if(isset($adddescription) && isset($adddescription)){
    $query .=' AND add_description="'.$adddescription.'"'; 
}
$addcreatetime=$request->input('add_create_time');
if(isset($addcreatetime) && isset($addcreatetime)){
    $query .=' AND add_create_time="'.$addcreatetime.'"'; 
}
$addupdatetime=$request->input('add_update_time');
if(isset($addupdatetime) && isset($addupdatetime)){
    $query .=' AND add_update_time="'.$addupdatetime.'"'; 
}
$adddeletetime=$request->input('add_delete_time');
if(isset($adddeletetime) && isset($adddeletetime)){
    $query .=' AND add_delete_time="'.$adddeletetime.'"'; 
}
$addcreatedby=$request->input('add_created_by');
if(isset($addcreatedby) && isset($addcreatedby)){
    $query .=' AND add_created_by="'.$addcreatedby.'"'; 
}
$addstatus=$request->input('add_status');
if(isset($addstatus) && isset($addstatus)){
    $query .=' AND add_status="'.$addstatus.'"'; 
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
        'add_name_or'=> trans('form_lang.add_name_or'), 
        'add_name_am'=> trans('form_lang.add_name_am'), 
        'add_name_en'=> trans('form_lang.add_name_en'), 
        'add_type'=> trans('form_lang.add_type'), 
        'add_parent_id'=> trans('form_lang.add_parent_id'), 
        'add_phone'=> trans('form_lang.add_phone'), 
        'add_description'=> trans('form_lang.add_description'), 
        'add_status'=> trans('form_lang.add_status'), 
    ];
    $rules= [
        'add_name_or'=> 'max:30'
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
        $id=$request->get("add_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
        $requestData = $request->all();            
        $status= $request->input('add_status');
        if($status=="true"){
            $requestData['add_status']=1;
        }else{
            $requestData['add_status']=0;
        }
        $data_info = Modelgenaddressstructure::findOrFail($id);
            //$requestData['add_name_or']= $request->input('name');
        //$requestData['add_parent_id']= $request->input('rootId');
        //$requestData['add_id']= $request->input('id');
        $requestData['usr_directorate_id'] = $request->input('usr_directorate_id') ?: 0;
        $requestData['usr_team_id'] = $request->input('usr_team_id') ?: 0;
        $requestData['usr_officer_id'] = $request->input('usr_officer_id') ?: 0;
        $data_info->update($requestData);
             //$new_data_info['id']= $request->input('id');
        //$new_data_info['name']= $request->input('name');
        //$new_data_info['rootId']= $request->input('rootId');
        //$new_data_info['selected']= 0;
        //$new_data_info['add_id']= $data_info->add_id;
        //$new_data_info['add_name_or']= $request->input('add_name_or');
        $ischanged=$data_info->wasChanged();
        if($ischanged){
            Cache::forget('address');
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
}
public function insertgrid(Request $request)
{
    $attributeNames = [
        'add_name_or'=> trans('form_lang.add_name_or'), 
        'add_name_am'=> trans('form_lang.add_name_am'), 
        'add_name_en'=> trans('form_lang.add_name_en'), 
        'add_type'=> trans('form_lang.add_type'), 
        'add_parent_id'=> trans('form_lang.add_parent_id'), 
        'add_phone'=> trans('form_lang.add_phone'), 
        'add_description'=> trans('form_lang.add_description'), 
        'add_status'=> trans('form_lang.add_status'), 
    ];
    $rules= [
        'add_name_or'=> 'max:30'
    ];
    //dd($request->all());
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
        $requestData['add_created_by']=1;
        $status= $request->input('add_status');
        if($status=="true"){
            $requestData['add_status']=1;
        }else{
            $requestData['add_status']=0;
        }
       // SELECT add_id AS id,add_name_or AS name,add_parent_id AS rootId,false AS selected
        //$requestData['add_id']= $request->input('id');
        //$requestData['add_name_or']= $request->input('name');
        //$requestData['add_name_or']= $request->input('name');
        //$requestData['add_name_or']= $request->input('name');
        //$requestData['add_parent_id']= $request->input('rootId');
        $requestData['usr_directorate_id'] = $request->input('usr_directorate_id') ?: 0;
        $requestData['usr_team_id'] = $request->input('usr_team_id') ?: 0;
        $requestData['usr_officer_id'] = $request->input('usr_officer_id') ?: 0;
        $data_info=Modelgenaddressstructure::create($requestData);
        Cache::forget('address');
        /*$new_data_info['add']= $data_info->add_id;
        $new_data_info['name']= $request->input('add_name_or');
        $new_data_info['rootId']= $request->input('rootId');
        $new_data_info['children']= array();
        $new_data_info['selected']= 0;*/
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
    $id=$request->get("id");
    Modelgenaddressstructure::destroy($id);
    $resultObject= array(
        "value" =>"",
        "deleted_id"=>$id,
        "deleted"=>true,
        "status_code"=>200,
        "type"=>"delete",
        "errorMsg"=>""
    );
    return response()->json($resultObject);
}
function buildHierarchy(array $elements, $parentId=1) {
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
function buildTree(array $elements, $parentId = null) {
    $branch = [];
    foreach ($elements as $element) {
        if ($element->rootId == $parentId) {
            $children = $this->buildTree($elements, $element->id);
            $element->children = $children;
            $branch[] = $element;
        }
    }
    return $branch;
}
public function listaddress(Request $request){

        //INNER JOIN tbl_users ON tbl_users.usr_zone_id = gen_address_structure.add_id
     //WHERE usr_id = '.$userId.' AND add_id::integer =0   
  $authenticatedUser = $request->authUser;
  $userId=$authenticatedUser->usr_id;
  $userType=$authenticatedUser->usr_user_type;
        //$userId=79;
  $userInfo=$this->getUserInfo($request);
  if($userInfo !=null){
            $zoneId=$userInfo->usr_zone_id;
            $woredaId=$userInfo->usr_woreda_id;
            //if user is zone or woreda user
  if( $woredaId > 1){
   $query='WITH RECURSIVE address_hierarchy AS (
    SELECT 
    add_id AS id,
    add_name_or AS name,
    add_name_am AS add_name_am,
    add_name_en AS add_name_en,
    add_parent_id AS "rootId",
        ARRAY[]::json[] AS children -- Initialize children as empty array
        FROM gen_address_structure 
        INNER JOIN tbl_users ON tbl_users.usr_zone_id = gen_address_structure.add_id
        WHERE usr_id = '.$userId.' 
        UNION ALL
        SELECT 
        g.add_id AS id,
        g.add_name_or AS name,
        g.add_name_am AS add_name_am,
        g.add_name_en AS add_name_en,
        g.add_parent_id AS "rootId",
        ARRAY[]::json[] AS children
        FROM gen_address_structure g
    INNER JOIN address_hierarchy h ON g.add_parent_id::text = h.id::text AND add_id::integer = '.$woredaId.'::integer -- Link child nodes to parents
)
   SELECT * FROM address_hierarchy';
   //AND add_parent_id::text = '.$woredaId.'::text
}else if($zoneId > 1 ){
   // if(){
   $query='WITH RECURSIVE address_hierarchy AS (
    SELECT 
    add_id AS id,
    add_name_or AS name,
    add_name_am AS add_name_am,
    add_name_en AS add_name_en,
    add_parent_id AS "rootId",
        ARRAY[]::json[] AS children -- Initialize children as empty array
        FROM gen_address_structure 
        INNER JOIN tbl_users ON tbl_users.usr_zone_id = gen_address_structure.add_id
        WHERE usr_id = '.$userId.'
        UNION ALL
        SELECT 
        g.add_id AS id,
        g.add_name_or AS name,
        g.add_name_am AS add_name_am,
        g.add_name_en AS add_name_en,
        g.add_parent_id AS "rootId",
        ARRAY[]::json[] AS children
        FROM gen_address_structure g
    INNER JOIN address_hierarchy h ON g.add_parent_id::text = h.id::text  -- Link child nodes to parents
)
   SELECT * FROM address_hierarchy';
}
else{
 $query='WITH RECURSIVE address_hierarchy AS (
    SELECT 
    add_id AS id,
    add_name_or AS name,
    add_name_am AS add_name_am,
    add_name_en AS add_name_en,
    add_parent_id AS "rootId",
        ARRAY[]::json[] AS children -- Initialize children as empty array
        FROM gen_address_structure ';
        if($userType==3 || $userType==5){
            $query .='WHERE add_id::integer =1 ';
        }elseif($userType==1){
            $query .='WHERE add_id::integer =508 ';
                }elseif($userType==2){
            $query .='WHERE add_id::integer =1 ';
                }
        /*WHERE add_id::integer =508*/
        $query .='
        UNION ALL
        SELECT 
        g.add_id AS id,
        g.add_name_or AS name,
        g.add_name_am AS add_name_am,
        g.add_name_en AS add_name_en,
        g.add_parent_id AS "rootId",
        ARRAY[]::json[] AS children
        FROM gen_address_structure g
    INNER JOIN address_hierarchy h ON g.add_parent_id::text = h.id::text 
     -- Link child nodes to parents
)
 SELECT * FROM address_hierarchy';
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

$cacheKey = 'address';
/*if($zoneId == 0  && $woredaId == 0){
$data_info = Cache::rememberForever($cacheKey, function () use ($query) {
return DB::select($query);
});
}else{
    $data_info= DB::select($query);
}*/
$data_info= DB::select($query);
$hierarchicalData = $this->buildHierarchy(json_decode(json_encode($data_info), true));
}else{
    $hierarchicalData=array("");
}
$resultObject= array(
    "data" =>$hierarchicalData,
    "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
}