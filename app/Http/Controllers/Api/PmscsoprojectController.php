<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class PmscsoprojectController extends MyController
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
        $query='SELECT prj_direct_ben_male,prj_direct_ben_female,
    prj_indirect_ben_male,prj_indirect_ben_female,prj_date_agreement_signed,prj_agreement_signed_level, prj_cluster_id,prj_admin_cost,prj_program_cost,prj_funding_agency,prj_consortium_members, prj_assigned_sectors, prj_parent_id,prj_object_type_id,owner_zone.add_name_or AS zone_owner,
        owner_woreda.add_name_or AS woreda_owner,
        location_zone.add_name_or AS zone_location,
        location_woreda.add_name_or AS woreda_location,
         sci_name_en as sector_name,pct_name_en AS project_category, prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,prj_id,prj_name,prj_code,prj_project_status_id,
        pms_project_category.pct_name_or AS prj_project_category_id,prj_project_budget_source_id,prj_total_estimate_budget,prj_total_actual_budget,prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,prj_location_kebele_id,prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_kebele_id,prj_owner_description,prj_start_date_et,prj_start_date_gc,prj_start_date_plan_et,prj_start_date_plan_gc,prj_outcome,prj_deleted,prj_remark,prj_created_by,prj_created_date,prj_create_time,prj_update_time,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number FROM pms_project ';
$query .= ' LEFT JOIN pms_cso_info ON pms_project.prj_owner_id = pms_cso_info.cso_id';
$query .= " LEFT JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " LEFT JOIN gen_address_structure owner_zone ON pms_project.prj_owner_zone_id = owner_zone.add_id";
$query .= " LEFT JOIN gen_address_structure owner_woreda ON pms_project.prj_owner_woreda_id = owner_woreda.add_id";
$query .= " LEFT JOIN gen_address_structure location_zone ON pms_project.prj_location_zone_id = location_zone.add_id";
$query .= " LEFT JOIN gen_address_structure location_woreda ON pms_project.prj_location_woreda_id = location_woreda.add_id";
        $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .= ' LEFT JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id';
        $query .=" WHERE prj_id=".$id."  AND prj_owner_type=2 ";
        $data_info=DB::select($query);
        if(isset($data_info) && !empty($data_info)){
            $data=$data_info[0];
            $request_role='requester';
            $authenticatedUser = $request->authUser;
            $userId=$authenticatedUser->usr_id;
            if($userId==9){
                $request_role='requester';
            }
            $tabInfo=$this->getAllTabPermission($request);
            $resultObject= array(
                'allowedTabs'=>$tabInfo['allowedTabs'],
                "data" =>$data,
                "data_available"=>"1",
                "request_role"=>$request_role);
            return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }else{
            $resultObject= array(
                "data_available"=>"0");
            return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
        }
    }
    //to populate projects list based on selected program
    public function listgrid(Request $request){
        $permissionData=$this->getPagePermission($request,66, "project_info");
      $query='SELECT prj_direct_ben_male,prj_direct_ben_female,
    prj_indirect_ben_male,prj_indirect_ben_female,prj_date_agreement_signed,prj_agreement_signed_level, prj_male_participant,prj_female_participant,prj_measured_figure,prj_measurement_unit, prj_cluster_id, prj_admin_cost,prj_program_cost,prj_funding_agency,prj_consortium_members, prj_assigned_sectors, prj_parent_id,prj_object_type_id,prj_parent_id,prj_object_type_id,cso_name,sci_name_en AS sector_name,prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,zone_info.add_name_or as zone_name, prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id,prj_total_estimate_budget,prj_total_actual_budget,
        prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,
        prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_description,
        prj_start_date_gc,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
        prj_end_date_plan_gc,prj_outcome,prj_remark
        ,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable,prj_program_id FROM pms_project ';
        $query .= ' LEFT JOIN pms_cso_info ON pms_project.prj_owner_id = pms_cso_info.cso_id';
        $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .=' LEFT JOIN gen_address_structure zone_info ON zone_info.add_id= pms_project.prj_owner_zone_id';
        $query .=' LEFT JOIN pms_sector_information ON pms_sector_information.sci_id= pms_project.prj_sector_id';
        $query .=' WHERE prj_owner_type=2';
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
        $parentId=$request->input('parent_id');
        if(isset($parentId) && isset($parentId)){
            $query .=" AND prj_parent_id='".$parentId."'";
        }else{
            //$query .=" AND prj_parent_id=0";
        }
        //$query .=" AND prj_parent_id=0";
         $objectTypeId=$request->input('object_type_id');
        if(isset($objectTypeId) && isset($objectTypeId)){
            $query .=" AND prj_object_type_id='".$objectTypeId."'";
        }
        $userInfo=$this->getUserInfo($request);
        $sectorId=$userInfo->usr_sector_id;

        if(isset($userInfo)){
            if($userInfo->usr_owner_id > 0){
                $query .=" AND prj_owner_id='".$userInfo->usr_owner_id."'";
            }else{
                //$query=$this->getSearchParam($request,$query);
               $csoId=$request->input('prj_owner_id');
        if(isset($csoId) && !empty($csoId)){
            $query .=" AND prj_owner_id='".$csoId."'";
        }
        else if($sectorId > 1){
    $query .= " AND ".$sectorId." = ANY(prj_assigned_sectors)"; 
}
        }
        }

        $query.=' ORDER BY prj_id DESC';
        $data_info=DB::select($query);
        //$this->getQueryInfo($query);
        $tabInfo=$this->getTabPermission($request);
        $resultObject= array(
            "data" =>$data_info,
            "previledge"=>array('is_role_editable'=>1,'is_role_deletable'=>1,'is_role_can_add'=>1));
        return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
    }
//Only to search and display data
        public function listgridsearch(Request $request){
        $permissionData=$this->getPagePermission($request,9, "project_info");
        //dd($permissionData);
        //dump($permissionData);
        $query='SELECT prj_direct_ben_male,prj_direct_ben_female,
    prj_indirect_ben_male,prj_indirect_ben_female,prj_date_agreement_signed,prj_agreement_signed_level,prj_male_participant,prj_female_participant,prj_measured_figure,prj_measurement_unit,prj_cluster_id,prj_admin_cost,prj_program_cost,prj_funding_agency,prj_consortium_members, cso_name, sci_name_en AS sector_name,prs_color_code AS color_code,prs_id AS status_id, prs_status_name_en AS status_name,zone_info.add_name_or as zone_name, prj_name_en,prj_name_am,prj_department_id,prj_id,prj_name,prj_code, prj_project_status_id,prj_project_category_id,prj_total_estimate_budget,prj_total_actual_budget,
        prj_geo_location,prj_sector_id,prj_location_region_id,prj_location_zone_id,prj_location_woreda_id,
        prj_location_description,prj_owner_region_id,prj_owner_zone_id,prj_owner_woreda_id,prj_owner_description,
        prj_start_date_gc,prj_start_date_plan_gc,prj_end_date_actual_et,prj_end_date_actual_gc,
        prj_end_date_plan_gc,prj_outcome,prj_remark
        ,prj_owner_id,prj_urban_ben_number,prj_rural_ben_number,1 AS is_editable, 1 AS is_deletable,prj_program_id FROM pms_project ';
        $query .= ' LEFT JOIN pms_cso_info ON pms_project.prj_owner_id = pms_cso_info.cso_id';
        $query .=' LEFT JOIN pms_project_status ON pms_project_status.prs_id= pms_project.prj_project_status_id';
        $query .=' LEFT JOIN gen_address_structure zone_info ON zone_info.add_id= pms_project.prj_owner_zone_id';
        $query .=' LEFT JOIN pms_sector_information ON pms_sector_information.sci_id= pms_project.prj_sector_id';
        $query .=' WHERE prj_owner_type=2';
        $query=$this->getSearchParamCSO($request,$query);
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
        $query.=' ORDER BY cso_id ASC';
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
    $input = $request->input('prj_assigned_sectors');
// Decode to PHP array
$array = json_decode($input, true);
// Convert to PostgreSQL array literal
if (is_array($array)) {
    $pgArray = '{' . implode(',', $array) . '}';
} else {
    $pgArray = '{}'; // fallback to empty array
}
$requestData['prj_assigned_sectors']=$pgArray;
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
            $requestData['prj_parent_id']=$request->get('parent_id');
            $requestData['prj_object_type_id']=$request->get('object_type_id');
            $requestData['prj_program_id']=$request->get('program_id');
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
        //$requestData['prj_created_by']=auth()->user()->usr_Id;
        $requestData['prj_created_by']=1;
        $status= $request->input('prj_status');
        if($status=="true"){
            $requestData['prj_status']=1;
        }else{
            $requestData['prj_status']=0;
        }
        $userInfo=$this->getUserInfo($request);
        if(isset($userInfo)){
            /*$requestData['prj_owner_region_id']=1;
            $requestData['prj_owner_zone_id']=$userInfo->usr_zone_id;
            $requestData['prj_owner_woreda_id']=$userInfo->usr_woreda_id;
            $requestData['prj_sector_id']=$userInfo->usr_sector_id;*/
            //$requestData['prj_owner_id']=$userInfo->usr_owner_id;
            //$requestData['prj_department_id']=$userInfo->usr_department_id;
        }
        //set project status to 1 - Draft when a new project is created
        //$requestData['prj_project_status_id']=1;
        $requestData['prj_owner_type']=2;
        $requestData['prj_parent_id']=$request->get('parent_id');
        $requestData['prj_object_type_id']=$request->get('object_type_id');
        $data_info=Modelpmsproject::create($requestData);
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