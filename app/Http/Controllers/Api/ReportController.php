<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
//PROPERTY OF LT ICT SOLUTION PLC
class ReportController extends MyController
{
 public function __construct()
 {
    parent::__construct();
}
public function getReport(Request $request){
$reportType=$request->input('report_type');
//project Information
if($reportType==1){
$query="SELECT MIN(psc_name) AS sector_category,
    MIN(sci_name_or) AS sector_name, 
    MIN(sci_id) AS sci_id,
    MIN(psc_id) AS psc_id,
    MIN(sci_code) AS sci_code,
    COUNT(prj_id) AS requested_count,
    COUNT(CASE WHEN bdr_request_status = 'Requested' THEN 1 END) AS requested,
    COUNT(CASE WHEN bdr_request_status = 'Approved' THEN 1 END) AS approved,
    SUM(bdr_requested_amount) AS requested_amount,
    SUM(bdr_released_amount) AS released_amount
FROM pms_project 
INNER JOIN pms_budget_request ON pms_budget_request.bdr_project_id = pms_project.prj_id
INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id
INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id
INNER JOIN prj_sector_category ON prj_sector_category.psc_id = pms_sector_information.sci_sector_category_id
INNER JOIN gen_address_structure zone_info ON pms_project.prj_location_zone_id = zone_info.add_id";
$query .=" WHERE 1=1 ";
/*$query='SELECT sci_id, sci_code, prj_name, prj_code,zone_info.add_name_or AS zone_name,psc_name AS sector_category,sci_name_or AS sector_name,
zone_info.add_id AS zone_id,sci_id AS sector_id,psc_id AS sector_category_id,
SUM(bdr_requested_amount) AS bdr_requested_amount, SUM(bdr_released_amount) AS bdr_requested_amount
FROM pms_project 
INNER JOIN pms_budget_request ON pms_budget_request.bdr_project_id = pms_project.prj_id
INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id
INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id
INNER JOIN prj_sector_category ON prj_sector_category.psc_id = pms_sector_information.sci_sector_category_id
LEFT JOIN gen_address_structure zone_info ON pms_project.prj_location_zone_id = zone_info.add_id';
$query .=" WHERE 1=1 ";*/
//Project employees
}else if($reportType==2){
    $query='SELECT prj_name || prj_code AS "Project Name(Code)", emp_full_name AS "Employee Name",emp_start_date_gc AS "Start Date" ,emp_end_date_gc AS "End Date" FROM pms_project_employee ';
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_employee.emp_project_id';
//Project budget plan
}else if($reportType==3){
$query='SELECT prj_name || prj_code AS "Project Name(Code)", pms_budget_year.bdy_name AS "Budget Year", pms_expenditure_code.pec_name  As "Budget Code",bpl_amount AS "Budget Amount" FROM pms_project_budget_plan ';
     $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_plan.bpl_project_id';
     $query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_plan.bpl_budget_code_id';
       $query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_plan.bpl_budget_year_id';
       //project budget expenditure
}else if($reportType==4){
$query='SELECT prj_name || prj_code AS "Project Name(Code)", pbe_reason As "Reason", pms_expenditure_code.pec_name As "Budget Expenditure Code", pms_budget_year.bdy_name  As "Budget Year",pms_budget_month.bdm_month As "Budget Month",pbe_used_date_gc As "Date",ppe_amount As "Budget Amount" FROM pms_project_budget_expenditure ';       
         $query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_expenditure.pbe_project_id';
         $query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_expenditure.pbe_budget_code_id';
         $query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_expenditure.pbe_budget_year_id';
         $query .=' INNER JOIN pms_budget_month ON pms_budget_month.bdm_id=pms_project_budget_expenditure.pbe_budget_month_id';
 //project budget source
}else if($reportType==5){
$query='SELECT pms_project_category.pct_name_or As  "Project Category",zone_Add.add_name_or AS "Zone", sci_name_or AS "Sector", prj_name || prj_code AS "Project Name(Code)", pbs_name_or AS "Budget Source",
  bsr_amount AS "Amount" FROM pms_project_budget_source ';
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_source.bsr_project_id';
$query .=' INNER JOIN pms_budget_source ON pms_budget_source.pbs_id=pms_project_budget_source.bsr_budget_source_id';
$query .= ' INNER JOIN gen_address_structure zone_add ON pms_project.prj_location_zone_id = zone_add.add_id';
     $query .= ' INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id';
     $query .= ' LEFT JOIN pms_sector_information ON pms_sector_information.sci_id=pms_project.prj_sector_id';
 //project contractor
}else if($reportType==6){
$query='SELECT prj_name || prj_code AS "Project Name(Code)",cni_name AS "Contractor Name",pms_contractor_type.cnt_type_name_or AS  "Contractor Type",cni_total_contract_price
   AS  "Contract Price",cni_contract_start_date_gc AS  "Contract Start Date",cni_contract_end_date_gc AS  "Contract End Date",cni_procrument_method 
    AS  "Procrument Method",cni_bid_invitation_date AS  "Invitation Date",cni_bid_contract_signing_date AS  "Invitation Signing Date"

 FROM pms_project_contractor ';       
     $query .= ' INNER JOIN pms_contractor_type ON pms_project_contractor.cni_contractor_type_id = pms_contractor_type.cnt_id'; 
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_contractor.cni_project_id';
//project payment
}else if($reportType==7){
$query='SELECT pms_project_category.pct_name_or As  "Project Category",zone_Add.add_name_or AS "Zone", sci_name_or AS "Sector", prj_name || prj_code AS "Project Name(Code)", prp_type AS "Payment Type",prp_payment_date_gc AS "Payment Date" ,prp_payment_amount AS "Payment Amount",prp_payment_percentage AS "Payment Percentage" FROM pms_project_payment 
     INNER JOIN pms_project ON pms_project.prj_id=pms_project_payment.prp_project_id';
     $query .= ' INNER JOIN gen_address_structure zone_add ON pms_project.prj_location_zone_id = zone_add.add_id';
     $query .= ' INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id';
     $query .= ' LEFT JOIN pms_sector_information ON pms_sector_information.sci_id=pms_project.prj_sector_id';
     $query .=' WHERE 1=1';
     $startTime=$request->input('payment_dateStart');
if(isset($startTime) && isset($startTime)){
$query .=" AND prp_payment_date_gc >='".$startTime." 00 00 00'";
}
$endTime=$request->input('payment_dateEnd');
if(isset($endTime) && isset($endTime)){
$query .=" AND prp_payment_date_gc <='".$endTime." 23 59 59'"; 
}
}
//START COMMON PARAMETERS
$prjlocationzoneid=$request->input('prj_location_zone_id');
if(isset($prjlocationzoneid) && isset($prjlocationzoneid)){
$query .=" AND prj_location_zone_id='".$prjlocationzoneid."'"; 
}
$prjlocationworedaid=$request->input('prj_location_woreda_id');
if(isset($prjlocationworedaid) && isset($prjlocationworedaid)){
$query .=" AND prj_location_woreda_id='".$prjlocationworedaid."'"; 
}
$query .="GROUP BY sci_id ORDER BY psc_id,sci_id";
//END COMMON PARAMETERS
 $data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array());
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}
}