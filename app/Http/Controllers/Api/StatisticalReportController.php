<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\MyController;
use App\Models\Modelpmsproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
//PROPERTY OF LT ICT SOLUTION PLC
class StatisticalReportController extends MyController
{
 public function __construct()
 {
    parent::__construct();
}
 
public function getStatistics(Request $request){
$reportType=$request->input('report_type');
$locale=$request->input('locale');
//$locale = Session::get('app_locale', 'en');
App::setLocale($locale);

//project Information
$projectname = str_replace('/', '', trans("form_lang.prj_name_code"));
$projectcategory = trans("form_lang.prj_project_category_id");
$totalestimatebudget = trans("form_lang.prj_total_estimate_budget");
$totalactualbudget = trans("form_lang.prj_total_actual_budget");
$sectorname = trans("form_lang.sector_name");
$zone = trans("form_lang.zone");
$startdategc = trans("form_lang.prj_start_date_gc");
$urbanbennumber = trans("form_lang.prj_urban_ben_number");
$ruralbennumber = trans("form_lang.prj_rural_ben_number");
$emp_full_name = trans("form_lang.emp_full_name");
$emp_start_date_gc = trans("form_lang.emp_start_date_gc");
$emp_end_date_gc = trans("form_lang.emp_end_date_gc");
$budgetCode = trans("form_lang.pec_name");
$reason = trans("form_lang.reason");
$budgetExpenditureCode = trans("form_lang.budget_expenditure_code");
$budgetMonth = trans("form_lang.budget_month");
$date = trans("form_lang.date");
$budgetAmount = trans("form_lang.budget_amount");
$budgetSource = trans("form_lang.budget_source");
$contractorName = trans("form_lang.contractor_name");
$contractorType = trans("form_lang.contractor_type");
$contractPrice = trans("form_lang.contract_price");
$contractStartDate = trans("form_lang.contract_start_date");
$contractEndDate = trans("form_lang.contract_end_date");
$procurementMethod = trans("form_lang.procurement_method");
$invitationDate = trans("form_lang.invitation_date");
$invitationSigningDate = trans("form_lang.invitation_signing_date");
$paymentType = trans("form_lang.payment_type");
$paymentDate = trans("form_lang.payment_date");
$paymentAmount = trans("form_lang.payment_amount");
$paymentPercentage = trans("form_lang.payment_percentage");
$recordDate = trans("form_lang.record_date");
$totalBudgetUsed = trans("form_lang.total_budget_used");
$physicalPerformance = trans("form_lang.physical_performance");
$budgetYear = trans("form_lang.budget_year");
$statusName = trans("form_lang.status_name");
$stakeholderName = trans("form_lang.stakeholder_name");
$stakeholderType = trans("form_lang.stakeholder_type");
$representativeName = trans("form_lang.representative_name");
$stakeholderRole = trans("form_lang.stakeholder_role");
$requestedAmount = trans("form_lang.requested_amount");
$releasedAmount = trans("form_lang.released_amount");
$requestedDate = trans("form_lang.requested_date");
$releasedDate = trans("form_lang.released_date");
$handoverDate = trans("form_lang.handover_date");
$projectStatus = trans("form_lang.prs_status_name_or");
$projectStatus = trans("form_lang.prs_status_name_or");
$requestStatus = trans("form_lang.request_status");
$requestAmount = trans("form_lang.bdr_requested_amount");
$approvedAmount = trans("form_lang.bdr_released_amount");

// Determine column suffix based on locale
if ($locale == "or") {
    $suffix = "_or";
} elseif ($locale == "am") {
    $suffix = "_am";
} else {
    $suffix = "_en";
}

if($reportType==1){
    //Project information
$query = "SELECT 
    prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
    prj_name || prj_code AS \"$projectname\",     
    prj_total_estimate_budget AS \"$totalestimatebudget\",
    prj_total_actual_budget AS \"$totalactualbudget\",    
    prj_start_date_gc AS \"$startdategc\",
    prj_urban_ben_number AS \"$urbanbennumber\",
    prj_rural_ben_number AS \"$ruralbennumber\"
FROM pms_project";
$query .= " LEFT JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
}
//Project employees
else if($reportType==2){
    $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
    prj_name || prj_code AS \"$projectname\",
    emp_start_date_gc AS \"$emp_start_date_gc\", 
    emp_end_date_gc AS \"$emp_end_date_gc\" 
    FROM pms_project_employee";
$query .=" INNER JOIN pms_project ON pms_project.prj_id=pms_project_employee.emp_project_id";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
}
//Project budget plan
else if($reportType==3){
 $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
     prj_name || prj_code AS \"$projectname\", 
     bdy_name AS \"$budgetYear\", 
      pec_name  As \"$budgetExpenditureCode\",
      bpl_amount AS \"$budgetAmount\" 
      FROM pms_project_budget_plan ";
     $query .=" INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_plan.bpl_project_id";
     $query .=" INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_plan.bpl_budget_code_id";
       $query .=" INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_plan.bpl_budget_year_id";
       $query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
}
//project budget expenditure
else if($reportType==4){
$query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
    prj_name || prj_code AS \"$projectname\",
    pec_name AS \"$budgetExpenditureCode\",
    bdy_name AS \"$budgetYear\",
    bdm_month AS \"$budgetMonth\",    
    ppe_amount AS \"$budgetAmount\"
FROM pms_project_budget_expenditure";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_budget_expenditure.pbe_project_id";
$query .= " INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id = pms_project_budget_expenditure.pbe_budget_code_id";
$query .= " INNER JOIN pms_budget_year ON pms_budget_year.bdy_id = pms_project_budget_expenditure.pbe_budget_year_id";
$query .= " INNER JOIN pms_budget_month ON pms_budget_month.bdm_id = pms_project_budget_expenditure.pbe_budget_month_id";
  $query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
} 
//project budget source
else if($reportType==5){
$query = "SELECT 
    pct_name{$suffix} AS \"$projectcategory\", 
    zone_Add.add_name{$suffix} AS \"$zone\", 
    sci_name{$suffix} AS \"$sectorname\", 
    prj_name || prj_code AS \"$projectname\", 
    pbs_name{$suffix} AS \"$budgetSource\", 
    bsr_amount AS \"$budgetAmount\" 
FROM pms_project_budget_source";

$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_budget_source.bsr_project_id";
$query .= " INNER JOIN pms_budget_source ON pms_budget_source.pbs_id = pms_project_budget_source.bsr_budget_source_id";
$query .= " INNER JOIN gen_address_structure zone_add ON pms_project.prj_location_zone_id = zone_add.add_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " LEFT JOIN pms_sector_information ON pms_sector_information.sci_id = pms_project.prj_sector_id";
$query .= " WHERE 1=1";
 //project contractor
}else if($reportType==6){
$query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
    prj_name || prj_code AS \"$projectname\",
    cnt_type_name{$suffix} AS \"$contractorType\",
    cni_total_contract_price AS \"$contractPrice\",
    cni_contract_start_date_gc AS \"$contractStartDate\",
    cni_contract_end_date_gc AS \"$contractEndDate\",
    cni_procrument_method AS \"$procurementMethod\"
FROM pms_project_contractor";
     $query .= ' INNER JOIN pms_contractor_type ON pms_project_contractor.cni_contractor_type_id = pms_contractor_type.cnt_id'; 
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_contractor.cni_project_id';
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
}
//project payment
else if($reportType==7){
   $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
        prj_name || prj_code AS \"$projectname\",
        prp_type AS \"$paymentType\",
        prp_payment_date_gc AS \"$paymentDate\",
        prp_payment_amount AS \"$paymentAmount\"
    FROM pms_project_payment";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_payment.prp_project_id";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1 = 1";

$startTime = $request->input('payment_dateStart');
if (isset($startTime) && !empty($startTime)) {
    $query .= " AND prp_payment_date_gc >= '".$startTime." 00:00:00'"; 
}

$endTime = $request->input('payment_dateEnd');
if (isset($endTime) && !empty($endTime)) {
    $query .= " AND prp_payment_date_gc <= '".$endTime." 23:59:59'"; 
}

}
//project performance
else if($reportType==8){
   $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
            prj_name || prj_code AS \"$projectname\",
            prp_record_date_gc AS \"$recordDate\",
            prp_total_budget_used AS \"$totalBudgetUsed\",
            prp_physical_performance AS \"$physicalPerformance\",
            bdy_name AS \"$budgetYear\",
            bdm_month AS \"$budgetMonth\"
        FROM pms_project_performance";
    $query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_performance.prp_project_id";
    $query .= " LEFT JOIN pms_budget_year ON pms_budget_year.bdy_id = pms_project_performance.prp_budget_year_id";
    $query .= " LEFT JOIN pms_budget_month ON pms_budget_month.bdm_id = pms_project_performance.prp_budget_month_id";
    $query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
    $query .= " WHERE 1=1";
}
//project stakeholder
else if($reportType==9){
$query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
    prj_name || prj_code AS \"$projectname\",
    psh_name AS \"$stakeholderName\",
    sht_type_name{$suffix} AS \"$stakeholderType\",
    psh_representative_name AS \"$representativeName\",
    psh_role AS \"$stakeholderRole\"
FROM pms_project_stakeholder";
$query .= " INNER JOIN pms_stakeholder_type ON pms_project_stakeholder.psh_stakeholder_type = pms_stakeholder_type.sht_id";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_stakeholder.psh_project_id";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";

}
//project supplimentary
else if($reportType==10){
 $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
    prj_name || prj_code AS \"$projectname\",
    prs_requested_amount AS \"$requestedAmount\", 
    prs_released_amount AS \"$releasedAmount\", 
    prs_requested_date_gc AS \"$requestedDate\", 
    prs_released_date_gc AS \"$releasedDate\",
    bdy_name AS \"$budgetYear\"
FROM pms_project_supplimentary";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_supplimentary.prs_project_id";
$query .= " INNER JOIN pms_budget_year ON pms_budget_year.bdy_id = pms_project_supplimentary.prs_budget_year_id";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1"; // This can be removed if no condition is added

// Start time filter
$startTime = $request->input('start_dateStart');
if (isset($startTime)) {
    $query .= " AND prs_requested_date_gc >= '" . $startTime . "'";
}
// End time filter
$endTime = $request->input('start_dateEnd');
if (isset($endTime)) {
    $query .= " AND prs_requested_date_gc <= '" . $endTime . " 23:59:59'";
}

}

//project variation
else if($reportType==11){
 $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",
          prj_name || prj_code AS \"$projectname\",
          prv_requested_amount AS \"$requestedAmount\", 
          prv_released_amount AS \"$releasedAmount\", 
          prv_requested_date_gc AS \"$requestedDate\", 
          prv_released_date_gc AS \"$releasedDate\",
          bdy_name AS \"$budgetYear\"
          FROM pms_project_variation";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_variation.prv_project_id";
$query .= " INNER JOIN pms_budget_year ON pms_budget_year.bdy_id = pms_project_variation.prv_budget_year_id";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";

// Start time filter
$startTime = $request->input('variation_dateStart');
if (isset($startTime)) {
    $query .= " AND prv_released_date_gc >= '" . $startTime . "'";
}
// End time filter
$endTime = $request->input('variation_dateEnd');
if (isset($endTime)) {
    $query .= " AND prv_released_date_gc <= '" . $endTime . " 23:59:59'";
}

}
//project handover
else if($reportType==12){
 $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",  
          prj_name || prj_code AS \"$projectname\",
          prh_handover_date_gc AS \"$handoverDate\",
          bdy_name AS \"$budgetYear\"
          FROM pms_project_handover";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_project_handover.prh_project_id";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_budget_year ON pms_budget_year.bdy_id = pms_project_handover.prh_budget_year_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
$startTime = $request->input('handover_dateStart');
if (isset($startTime) && !empty($startTime)) {
    $query .= " AND prh_handover_date_gc >= '".$startTime." 00 00 00'"; 
}

$endTime = $request->input('handover_dateEnd');
if (isset($endTime) && !empty($endTime)) {
    $query .= " AND prh_handover_date_gc <= '".$endTime." 23 59 59'"; 
}
}
else if($reportType==13){
 $query="SELECT prs_status_name{$suffix} AS \"$projectStatus\",
    pct_name{$suffix} AS \"$projectcategory\",
    sci_name{$suffix} AS \"$sectorname\",
    gen_address_structure.add_name{$suffix} AS \"$zone\",  
          prj_name || prj_code AS \"$projectname\",
          rqs_name{$suffix} AS \"$requestStatus\",
          bdr_requested_amount AS \"$requestAmount\",
          bdr_released_amount AS \"$releasedAmount\",
          bdy_name AS \"$budgetYear\"
          FROM pms_budget_request";
$query .= " INNER JOIN pms_project ON pms_project.prj_id = pms_budget_request.bdr_project_id";
$query .= " INNER JOIN pms_budget_year ON pms_budget_year.bdy_id = pms_budget_request.bdr_budget_year_id";
$query .= " INNER JOIN gen_request_status ON gen_request_status.rqs_id = pms_budget_request.bdr_request_status";
$query .= " INNER JOIN pms_sector_information ON pms_project.prj_sector_id = pms_sector_information.sci_id";
$query .= " INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id";
$query .= " INNER JOIN pms_project_status ON pms_project.prj_project_status_id = pms_project_status.prs_id";
$query .= " LEFT JOIN gen_address_structure ON pms_project.prj_location_zone_id = gen_address_structure.add_id";
$query .= " WHERE 1=1";
/*$startTime = $request->input('handover_dateStart');
if (isset($startTime) && !empty($startTime)) {
    $query .= " AND prh_handover_date_gc >= '".$startTime." 00 00 00'"; 
}

$endTime = $request->input('handover_dateEnd');
if (isset($endTime) && !empty($endTime)) {
    $query .= " AND prh_handover_date_gc <= '".$endTime." 23 59 59'"; 
}*/
}
//project document
else if($reportType==13){

}
$query =$this->getSearchParam($request,$query);
//$this->getQueryInfo($query);
//END COMMON PARAMETERS
 $data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array());
return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);

}
}