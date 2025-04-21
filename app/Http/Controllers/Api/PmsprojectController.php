<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
//PROPERTY OF LT ICT SOLUTION PLC
class PmsprojectController extends MyController
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
    public function show(Request $request,$id)
    {
        $query='SELECT prj_parent_id,prj_object_type_id,owner_zone.add_name_or AS zone_owner,
        owner_woreda.add_name_or AS woreda_owner,
        location_zone.add_name_or AS zone_location,
        location_woreda.add_name_or AS woreda_location,
        prj_end_date_plan_gc,
         sci_name_en as sector_name,pct_name_en AS project_category, prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,prj_id,prj_name,prj_code,prj_project_status_id,
        pms_project_category.pct_name_or AS prj_project_category_id,prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number FROM pms_project ';
        $query .= " LEFT JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " LEFT JOIN gen_address_structure owner_zone ON pms_project.prj_owner_zone_id = owner_zone.add_id";
$query .= " LEFT JOIN gen_address_structure owner_woreda ON pms_project.prj_owner_woreda_id = owner_woreda.add_id";
$query .= " LEFT JOIN gen_address_structure location_zone ON pms_project.prj_location_zone_id = location_zone.add_id";
$query .= " LEFT JOIN gen_address_structure location_woreda ON pms_project.prj_location_woreda_id = location_woreda.add_id";
        $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .= ' LEFT JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id';
        $query .=" WHERE prj_id=".$id."  AND prj_owner_type=1";
        $data_info=DB::select($query);
        if(isset($data_info) && !empty($data_info)){
            //START PROJECT ANALYSIS
            //START PROJECT PERFORMANCE
            $query='SELECT prp_record_date_gc,prp_total_budget_used AS used_amount,prp_physical_performance AS physical_performance FROM pms_project_performance ';
             $query .=" WHERE prp_project_id= ".$id." ";
              $performance_info=DB::select($query);
            //END PROJECT PERFORMANCE
              //START COST
              $query='SELECT SUM(bdr_released_amount) AS additional_budget
     FROM pms_budget_request ';
     $query .=" WHERE bdr_project_id= ".$id." AND bdr_request_category_id > 1 ";
     $additional_budget_info=DB::select($query);
              //END COST
            //END PROJECT ANALYSIS
            $data=$data_info[0];
          /*  $request_role='requester';
            $authenticatedUser = $request->authUser;
            $userId=$authenticatedUser->usr_id;
            if($userId==9){
                $request_role='requester';
            }*/
            $tabInfo=$this->getAllTabPermission($request);
            $resultObject= array(
                'performance'=>$performance_info[0] ?? null,
                'additional_budget'=>$additional_budget_info ?? null,
                'allowedTabs'=>$tabInfo['allowedTabs'],
                "data" =>$data,
                "data_available"=>"1",
                "request_role"=>"requester");
            return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }else{
            $resultObject= array(
                "data_available"=>"0");
            return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }
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
    //to populate projects list based on selected program
    public function listgrid(Request $request){
        $permissionData=$this->getPagePermission($request,9, "project_info");
        //dd($permissionData);
        //dump($permissionData);
        /*$query='SELECT prj_parent_id,prj_object_type_id, prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,add_name_or, prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id,prj_total_estimate_budget,prj_total_actual_budget,
        prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,
        prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_description,
        prj_start_date_gc,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
        prj_end_date_plan_gc,prj_outcome,prj_remark
        ,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable,prj_program_id FROM pms_project ';*/
        $prjprojectstatusid=$request->input('prj_project_status_id');
        if(isset($prjprojectstatusid) && isset($prjprojectstatusid)){
            //$query .=' AND prj_project_status_id="'.$prjprojectstatusid.'"';
        }
         $programID=$request->input('program_id');
        if(isset($programID) && isset($programID)){
            //$query .=" AND prj_program_id='".$programID."'";
        }
        $prjownerzoneid=$request->input('prj_owner_zone_id');
if(isset($prjownerzoneid) && isset($prjownerzoneid)){
//$query .=" AND prj_owner_zone_id='".$prjownerzoneid."'"; 
}
$prjownerworedaid=$request->input('prj_owner_woreda_id');
if(isset($prjownerworedaid) && isset($prjownerworedaid)){
//$query .=" AND prj_owner_woreda_id='".$prjownerworedaid."'";
}
$prjsectorid=$request->input('prj_sector_id');
if(isset($prjsectorid) && isset($prjsectorid)){
//$query .=" AND prj_sector_id='".$prjsectorid."'";
}

        $parentId=$request->input('parent_id');
        if(isset($parentId) && isset($parentId)){
            //$query .=" AND prj_parent_id='".$parentId."'";
        }
         $objectTypeId=$request->input('object_type_id');
        if(isset($objectTypeId) && isset($objectTypeId)){
            //$query .=" AND prj_object_type_id='".$objectTypeId."'";
        }
        //prj_id ='.$parentId.' AND 
          $query='WITH RECURSIVE project_hierarchy AS (
    -- Anchor member: Start from the root project (change the ID as needed)
    SELECT 
        prj_id AS id,                     -- Primary key
        prj_name AS name,
        prj_parent_id AS "rootId",        -- Parent reference
        ARRAY[]::json[] AS children,      -- Placeholder for children
        prj_object_type_id, prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name, prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id,prj_total_estimate_budget,prj_total_actual_budget,
        prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,
        prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_description,
        prj_start_date_gc,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
        prj_end_date_plan_gc,prj_outcome,prj_remark
        ,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable
    FROM pms_project
    INNER JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id
    WHERE prj_owner_type =1 AND prj_owner_zone_id='.$prjownerzoneid.'
    AND prj_owner_woreda_id='.$prjownerworedaid.' AND prj_object_type_id=1 AND prj_sector_id ='.$prjsectorid.'

    UNION ALL
    -- Recursive member: Get children of the current node
    SELECT 
        p.prj_id AS id,                   -- Primary key
        p.prj_name AS name,
        p.prj_parent_id AS "rootId",
        ARRAY[]::json[] AS children,
        p.prj_object_type_id, ps.prs_color_code AS color_code,ps.prs_id AS status_id, ps.prs_status_name_en AS status_name, p.prj_name_en,p.prj_name_am,p.prj_department_id,p.prj_id,p.prj_name,p.prj_code, p.prj_project_status_id,p.prj_project_category_id,p.prj_total_estimate_budget,p.prj_total_actual_budget,
        p.prj_geo_location,p.prj_sector_id,p.prj_location_region_id,p.prj_location_zone_id,p.prj_location_woreda_id,
        p.prj_location_description,p.prj_owner_region_id,p.prj_owner_zone_id,p.prj_owner_woreda_id,p.prj_owner_description,
        p.prj_start_date_gc,p.prj_start_date_plan_gc,p.prj_end_date_actual_et,p.prj_end_date_actual_gc,
        p.prj_end_date_plan_gc,p.prj_outcome,p.prj_remark
        ,p.prj_owner_id,p.prj_urban_ben_number,p.prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable
    FROM pms_project p
    INNER JOIN project_hierarchy ph ON p.prj_parent_id = ph.id 
    INNER JOIN pms_project_status ps ON ps.prs_id= p.prj_project_status_id
)
SELECT * FROM project_hierarchy';
//WHERE prj_owner_zone_id='.$prjownerzoneid.'
       /* $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .=' LEFT JOIN gen_address_structure ON gen_address_structure.add_id= pms_project.prj_owner_zone_id';
        $query .=' WHERE prj_owner_type =1';*/
        //$query=$this->getSearchParam($request,$query);
        
        //$query.=' ORDER BY prj_id DESC';
        $data_info=DB::select($query);
        if(isset($data_info) && !empty($data_info)){
        $hierarchicalData = $this->buildHierarchy(json_decode(json_encode($data_info), true));
}else{
    $hierarchicalData=array("");
}

        //$this->getQueryInfo($query);
        $tabInfo=$this->getTabPermission($request);
        $dateIsValid=$this->getDateParameter(1);
        $resultObject= array(
            "data" =>$hierarchicalData,
            "previledge"=>array('is_role_editable'=>$dateIsValid ? ($permissionData->pem_edit ?? 0) : 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$dateIsValid ? ($permissionData->pem_insert ?? 0) : 0),
            'allowedTabs'=>$tabInfo['allowedTabs'],
            'allowedLinks'=>$tabInfo['allowedLinks'] );
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }

    //to populate projects list based on selected program
    public function getProjectByParent(Request $request){
        $permissionData=$this->getPagePermission($request,9, "project_info");
        //dd($permissionData);
        //dump($permissionData);
        $query='SELECT prj_parent_id,prj_object_type_id, prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,add_name_or, prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id,prj_total_estimate_budget,prj_total_actual_budget,
        prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,
        prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_description,
        prj_start_date_gc,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
        prj_end_date_plan_gc,prj_outcome,prj_remark
        ,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable,prj_program_id FROM pms_project ';
        $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .=' LEFT JOIN gen_address_structure ON gen_address_structure.add_id= pms_project.prj_owner_zone_id';
        $query .=' WHERE prj_owner_type =1';
        $parentId=$request->input('parent_id');
        if(isset($parentId) && isset($parentId)){
            $query .=" AND prj_parent_id='".$parentId."'";
        }
         $objectTypeId=$request->input('object_type_id');
        if(isset($objectTypeId) && isset($objectTypeId)){
            $query .=" AND prj_object_type_id='".$objectTypeId."'";
        }
        $query.=' ORDER BY prj_id DESC';
        $data_info=DB::select($query);
        //$this->getQueryInfo($query);
        $tabInfo=$this->getTabPermission($request);
        $dateIsValid=$this->getDateParameter(1);
        $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>$dateIsValid ? ($permissionData->pem_edit ?? 0) : 0,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$dateIsValid ? ($permissionData->pem_insert ?? 0) : 0),
            'allowedTabs'=>$tabInfo['allowedTabs'],
            'allowedLinks'=>$tabInfo['allowedLinks'] );
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }

//Only to search and display data
        public function listgridsearch(Request $request){
        $permissionData=$this->getPagePermission($request,9, "project_info");
        //dd($permissionData);
        //dump($permissionData);
        $query='SELECT prj_parent_id,prj_object_type_id,sci_name_en AS sector_name,prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,zone_info.add_name_or as zone_name, prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id,prj_total_estimate_budget,prj_total_actual_budget,
        prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,
        prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_description,
        prj_start_date_gc,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
        prj_end_date_plan_gc,prj_outcome,prj_remark
        ,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable,prj_program_id FROM pms_project ';
        $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .=' LEFT JOIN gen_address_structure zone_info ON zone_info.add_id= pms_project.prj_owner_zone_id';
        $query .=' LEFT JOIN pms_sector_information ON pms_sector_information.sci_id= pms_project.prj_sector_id';
        $query .=' WHERE prj_owner_type =1';
        $query=$this->getSearchParam($request,$query);
        $prjprojectstatusid=$request->input('prj_project_status_id');
        if(isset($prjprojectstatusid) && isset($prjprojectstatusid)){
            $query .=' AND prj_project_status_id="'.$prjprojectstatusid.'"';
        }
        $prjprojectcategoryid=$request->input('prj_project_category_id');
        if(isset($prjprojectcategoryid) && isset($prjprojectcategoryid)){
            $query .=" AND prj_project_category_id='".$prjprojectcategoryid."'";
        }
        $prjstartdategc=$request->input('prj_start_date_gc');
        if(isset($prjstartdategc) && isset($prjstartdategc)){
            $query .=' AND prj_start_date_gc="'.$prjstartdategc.'"';
        }
        $prjstartdateplangc=$request->input('prj_start_date_plan_gc');
        if(isset($prjstartdateplangc) && isset($prjstartdateplangc)){
            $query .=' AND prj_start_date_plan_gc="'.$prjstartdateplangc.'"';
        }
        $prjenddateactualgc=$request->input('prj_end_date_actual_gc');
        if(isset($prjenddateactualgc) && isset($prjenddateactualgc)){
            $query .=' AND prj_end_date_actual_gc="'.$prjenddateactualgc.'"';
        }
        $prjenddateplangc=$request->input('prj_end_date_plan_gc');
        if(isset($prjenddateplangc) && isset($prjenddateplangc)){
            $query .=' AND prj_end_date_plan_gc="'.$prjenddateplangc.'"';
        }
         $programID=$request->input('program_id');
        if(isset($programID) && isset($programID)){
            $query .=" AND prj_program_id='".$programID."'";
        }
        $parentId=$request->input('parent_id');
        if(isset($parentId) && isset($parentId)){
            $query .=" AND prj_parent_id='".$parentId."'";
        }
         $objectTypeId=$request->input('object_type_id');
        if(isset($objectTypeId) && isset($objectTypeId)){
            $query .=" AND prj_object_type_id='".$objectTypeId."'";
        }
        $query.=' ORDER BY prj_id DESC';
        //$this->getQueryInfo($query);
        $data_info=DB::select($query);

        $tabInfo=$this->getTabPermission($request);
        $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>$permissionData->pem_edit ?? 2,'is_role_deletable'=>$permissionData->pem_delete ?? 0,'is_role_can_add'=>$permissionData->pem_insert ?? 0),
            'allowedTabs'=>$tabInfo['allowedTabs'],
            'allowedLinks'=>$tabInfo['allowedLinks'] );
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }
    public function updategrid(Request $request)
    {
        $attributeNames = [
            'prj_name'=> trans('form_lang.prj_name'),
            'prj_code'=> trans('form_lang.prj_code'),
            'prj_project_status_id'=> trans('form_lang.prj_project_status_id'),
            'prj_project_category_id'=> trans('form_lang.prj_project_category_id'),
            'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'),
            'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'),
            'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'),
            'prj_geo_location'=> trans('form_lang.prj_geo_location'),
            'prj_sector_id'=> trans('form_lang.prj_sector_id'),
            'prj_location_region_id'=> trans('form_lang.prj_location_region_id'),
            'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'),
            'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'),
            'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'),
            'prj_location_description'=> trans('form_lang.prj_location_description'),
            'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'),
            'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'),
            'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'),
            'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'),
            'prj_owner_description'=> trans('form_lang.prj_owner_description'),
            'prj_start_date_et'=> trans('form_lang.prj_start_date_et'),
            'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'),
            'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'),
            'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'),
            'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'),
            'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'),
            'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'),
            'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'),
            'prj_outcome'=> trans('form_lang.prj_outcome'),
            'prj_deleted'=> trans('form_lang.prj_deleted'),
            'prj_remark'=> trans('form_lang.prj_remark'),
            'prj_created_date'=> trans('form_lang.prj_created_date'),
            'prj_owner_id'=> trans('form_lang.prj_owner_id'),
            'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'),
            'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'),
        ];
        $rules= [
            'prj_name'=> 'max:200',
            'prj_name_am'=> 'max:200',
            'prj_name_en'=> 'max:200',
            'prj_code'=> 'max:20',
            'prj_project_status_id'=> 'max:200',
            'prj_project_category_id'=> 'max:200',
            'prj_project_budget_source_id'=> 'max:200',
            'prj_total_estimate_budget'=> 'max:200',
            'prj_total_actual_budget'=> 'max:200',
            'prj_geo_location'=> 'max:200',
            //'prj_sector_id'=> 'integer',
            'prj_location_region_id'=> 'integer',
            'prj_location_zone_id'=> 'integer',
            'prj_location_woreda_id'=> 'integer',
//'prj_location_kebele_id'=> 'integer',
            'prj_location_description'=> 'max:250',
            'prj_start_date_et'=> 'max:15',
            'prj_start_date_gc'=> 'max:15',
            'prj_start_date_plan_et'=> 'max:15',
            'prj_start_date_plan_gc'=> 'max:15',
            'prj_end_date_actual_et'=> 'max:15',
            'prj_end_date_actual_gc'=> 'max:15',
            'prj_end_date_plan_gc'=> 'max:15',
            'prj_end_date_plan_et'=> 'max:15',
            'prj_outcome'=> 'max:425',
            'prj_remark'=> 'max:100',
        ];
       $validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "update");
if ($validationResult !== false) {
    return $validationResult;
}
    try{
            $id=$request->get("prj_id");
        //$requestData['foreign_field_name']=$request->get('master_id');
            //assign data from of foreign key
            $requestData = $request->all();
            $status= $request->input('prj_status');
            if($status=="true"){
                $requestData['prj_status']=1;
            }else{
                $requestData['prj_status']=0;
            }
            if(isset($id) && !empty($id)){
                $data_info = Modelpmsproject::findOrFail($id);
                $requestData['prj_object_type_id']=$request->get('object_type_id');
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
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
            $requestData['prj_program_id']=$request->get('program_id');
            $requestData['prj_parent_id']=$request->get('parent_id');
            $requestData['prj_object_type_id']=$request->get('object_type_id');
            $data_info=Modelpmsproject::create($requestData);
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
    $attributeNames = [
        'prj_name'=> trans('form_lang.prj_name'),
        'prj_code'=> trans('form_lang.prj_code'),
        'prj_project_status_id'=> trans('form_lang.prj_project_status_id'),
        'prj_project_category_id'=> trans('form_lang.prj_project_category_id'),
        'prj_project_budget_source_id'=> trans('form_lang.prj_project_budget_source_id'),
        'prj_total_estimate_budget'=> trans('form_lang.prj_total_estimate_budget'),
        'prj_total_actual_budget'=> trans('form_lang.prj_total_actual_budget'),
        'prj_geo_location'=> trans('form_lang.prj_geo_location'),
        'prj_sector_id'=> trans('form_lang.prj_sector_id'),
        'prj_location_region_id'=> trans('form_lang.prj_location_region_id'),
        'prj_location_zone_id'=> trans('form_lang.prj_location_zone_id'),
        'prj_location_woreda_id'=> trans('form_lang.prj_location_woreda_id'),
        'prj_location_kebele_id'=> trans('form_lang.prj_location_kebele_id'),
        'prj_location_description'=> trans('form_lang.prj_location_description'),
        'prj_owner_region_id'=> trans('form_lang.prj_owner_region_id'),
        'prj_owner_zone_id'=> trans('form_lang.prj_owner_zone_id'),
        'prj_owner_woreda_id'=> trans('form_lang.prj_owner_woreda_id'),
        'prj_owner_kebele_id'=> trans('form_lang.prj_owner_kebele_id'),
        'prj_owner_description'=> trans('form_lang.prj_owner_description'),
        'prj_start_date_et'=> trans('form_lang.prj_start_date_et'),
        'prj_start_date_gc'=> trans('form_lang.prj_start_date_gc'),
        'prj_start_date_plan_et'=> trans('form_lang.prj_start_date_plan_et'),
        'prj_start_date_plan_gc'=> trans('form_lang.prj_start_date_plan_gc'),
        'prj_end_date_actual_et'=> trans('form_lang.prj_end_date_actual_et'),
        'prj_end_date_actual_gc'=> trans('form_lang.prj_end_date_actual_gc'),
        'prj_end_date_plan_gc'=> trans('form_lang.prj_end_date_plan_gc'),
        'prj_end_date_plan_et'=> trans('form_lang.prj_end_date_plan_et'),
        'prj_outcome'=> trans('form_lang.prj_outcome'),
        'prj_deleted'=> trans('form_lang.prj_deleted'),
        'prj_remark'=> trans('form_lang.prj_remark'),
        'prj_created_date'=> trans('form_lang.prj_created_date'),
        'prj_owner_id'=> trans('form_lang.prj_owner_id'),
        'prj_urban_ben_number'=> trans('form_lang.prj_urban_ben_number'),
        'prj_rural_ben_number'=> trans('form_lang.prj_rural_ben_number'),
    ];
    $rules= [
        'prj_name'=> 'max:200',
        'prj_name_am'=> 'max:200',
        'prj_name_en'=> 'max:200',
        'prj_code'=> 'max:20',
        'prj_project_status_id'=> 'max:200',
        'prj_project_category_id'=> 'max:200',
        'prj_project_budget_source_id'=> 'max:200',
        'prj_total_estimate_budget'=> 'max:200',
        'prj_total_actual_budget'=> 'max:200',
        'prj_geo_location'=> 'max:200',
        //'prj_sector_id'=> 'integer',
        'prj_location_region_id'=> 'integer',
        'prj_location_zone_id'=> 'integer',
        'prj_location_woreda_id'=> 'integer',
//'prj_location_kebele_id'=> 'integer',
        'prj_location_description'=> 'max:200',
        'prj_start_date_et'=> 'max:15',
        'prj_start_date_gc'=> 'max:15',
        'prj_start_date_plan_et'=> 'max:15',
        'prj_start_date_plan_gc'=> 'max:15',
        'prj_end_date_actual_et'=> 'max:15',
        'prj_end_date_actual_gc'=> 'max:15',
        'prj_end_date_plan_gc'=> 'max:15',
        'prj_end_date_plan_et'=> 'max:15',
        'prj_outcome'=> 'max:425',
        'prj_remark'=> 'max:100'
    ];
$validationResult = $this->handleLaravelException($request, $attributeNames, $rules, "save");
if ($validationResult !== false) {
    return $validationResult;
}
try {
        $requestData = $request->all();
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
        $requestData['prj_created_by']=1;
        $status= $request->input('prj_status');
        if($status=="true"){
            $requestData['prj_status']=1;
        }else{
            $requestData['prj_status']=0;
        }
        $userInfo=$this->getUserInfo($request);
        if(isset($userInfo) && 1 == 2){
            $requestData['prj_owner_region_id']=1;
            $requestData['prj_owner_zone_id']=$userInfo->usr_zone_id;
            $requestData['prj_owner_woreda_id']=$userInfo->usr_woreda_id;
            $requestData['prj_sector_id']=$userInfo->usr_sector_id;
            //$requestData['prj_department_id']=$userInfo->usr_department_id;
        }
        //set project status to 1 - Draft when a new project is created
        $requestData['prj_project_status_id']=1;
        $requestData['prj_owner_type']=1;
        $requestData['prj_parent_id']=$request->get('parent_id');
        $requestData['prj_object_type_id']=$request->get('object_type_id');

        $data_info=Modelpmsproject::create($requestData);
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
    $id=$request->get("prj_id");
    Modelpmsproject::destroy($id);
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