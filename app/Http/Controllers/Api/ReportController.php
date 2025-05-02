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
    COUNT(CASE WHEN bdr_request_status = '2' THEN 1 END) AS requested,
    COUNT(CASE WHEN bdr_request_status = '3' THEN 1 END) AS approved,
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
$query = 'SELECT prj_name || \' \' || prj_code AS "Project Name(Code)", zone_add.add_name_or AS "Zone", woreda_add.add_name_or AS "Woreda", sci_name_or AS "Sector", emp_full_name AS "Employee Name", emp_start_date_gc AS "Start Date", emp_end_date_gc AS "End Date" FROM pms_project_employee ';
$query .= ' INNER JOIN pms_project ON pms_project.prj_id=pms_project_employee.emp_project_id';
$query .= ' INNER JOIN gen_address_structure zone_add ON pms_project.prj_location_zone_id = zone_add.add_id';
$query .= ' LEFT JOIN gen_address_structure woreda_add ON pms_project.prj_location_woreda_id = woreda_add.add_id';
$query .= ' INNER JOIN pms_sector_information ON pms_sector_information.sci_id=pms_project.prj_sector_id';

$sector_id = $request->input('prj_sector_id');
if(!empty($sector_id) && is_numeric($sector_id)){
    $query .= " AND prj_sector_id = ".intval($sector_id); 
}

$query .= ' WHERE 1=1';

//Project budget plan
}else if($reportType==3){
$query='SELECT prj_name || \' \' || prj_code AS "Project Name(Code)", pms_budget_year.bdy_name AS "Budget Year", zone_add.add_name_or AS "Zone", woreda_add.add_name_or AS "Woreda", sci_name_or AS "Sector", pms_expenditure_code.pec_name  As "Budget Code",bpl_amount AS "Budget Amount" FROM pms_project_budget_plan ';
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_plan.bpl_project_id';
$query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_plan.bpl_budget_code_id';
$query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_plan.bpl_budget_year_id';

$query .= ' INNER JOIN gen_address_structure zone_add ON pms_project.prj_location_zone_id = zone_add.add_id';
$query .= ' LEFT JOIN gen_address_structure woreda_add ON pms_project.prj_location_woreda_id = woreda_add.add_id';
$query .= ' INNER JOIN pms_sector_information ON pms_sector_information.sci_id=pms_project.prj_sector_id';

$query .=' WHERE 1=1';

$sector_id = $request->input('prj_sector_id');
if(!empty($sector_id) && is_numeric($sector_id)){
    $query .= " AND prj_sector_id = ".intval($sector_id); 
}

$budgetyearid = $request->input('bdr_budget_year_id');
if(!empty($budgetyearid) && is_numeric($budgetyearid)){
    $query .= " AND bpl_budget_year_id = ".intval($budgetyearid); 
}
       //project budget expenditure
}else if($reportType==4){
$query='SELECT prj_name || \' \' || prj_code AS "Project Name(Code)", pbe_reason As "Reason", pms_expenditure_code.pec_name As "Budget Expenditure Code", pms_budget_year.bdy_name  As "Budget Year",pms_budget_month.bdm_month As "Budget Month", sci_name_or AS "Sector", pbe_used_date_gc As "Date",ppe_amount As "Budget Amount",
  SUM(ppe_amount) OVER (PARTITION BY pms_project.prj_id) AS "Total Amount"
   FROM pms_project_budget_expenditure ';       
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_expenditure.pbe_project_id';
$query .=' INNER JOIN pms_expenditure_code ON pms_expenditure_code.pec_id=pms_project_budget_expenditure.pbe_budget_code_id';
$query .=' INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_budget_expenditure.pbe_budget_year_id';
$query .=' INNER JOIN pms_budget_month ON pms_budget_month.bdm_id=pms_project_budget_expenditure.pbe_budget_month_id';
$query .= ' INNER JOIN pms_sector_information ON pms_sector_information.sci_id=pms_project.prj_sector_id';

$sector_id = $request->input('prj_sector_id');
if(!empty($sector_id) && is_numeric($sector_id)){
    $query .= " AND prj_sector_id = ".intval($sector_id); 
}

$budgetyearid = $request->input('pbe_budget_year_id');
if(!empty($budgetyearid) && is_numeric($budgetyearid)){
    $query .= " AND pbe_budget_year_id = ".intval($budgetyearid); 
}
$query .=' WHERE 1=1';


 //project budget source
}else if($reportType==5){
$query='SELECT pms_project_category.pct_name_or As  "Project Category",zone_Add.add_name_or AS "Zone", sci_name_or AS "Sector", prj_name || prj_code AS "Project Name(Code)", pbs_name_or AS "Budget Source",
  bsr_amount AS "Amount" FROM pms_project_budget_source ';
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_budget_source.bsr_project_id';
$query .=' INNER JOIN pms_budget_source ON pms_budget_source.pbs_id=pms_project_budget_source.bsr_budget_source_id';
$query .= ' INNER JOIN gen_address_structure zone_add ON pms_project.prj_location_zone_id = zone_add.add_id';
$query .= ' INNER JOIN pms_project_category ON pms_project.prj_project_category_id = pms_project_category.pct_id';
$query .= ' INNER JOIN pms_sector_information ON pms_sector_information.sci_id=pms_project.prj_sector_id';

$sector_id = $request->input('prj_sector_id');
if(!empty($sector_id) && is_numeric($sector_id)){
    $query .= " AND prj_sector_id = ".intval($sector_id); 
}

$query .=' WHERE 1=1';
 //project contractor
}else if($reportType==6){
$query='SELECT prj_name || \' \' || prj_code AS "Project Name(Code)",cni_name AS "Contractor Name",pms_contractor_type.cnt_type_name_or AS  "Contractor Type",cni_total_contract_price
   AS  "Contract Price",cni_contract_start_date_gc AS  "Contract Start Date",cni_contract_end_date_gc AS  "Contract End Date",cni_procrument_method 
    AS  "Procrument Method",cni_bid_invitation_date AS  "Invitation Date", cni_bid_contract_signing_date AS  "Invitation Signing Date"

 FROM pms_project_contractor ';       
     $query .= ' INNER JOIN pms_contractor_type ON pms_project_contractor.cni_contractor_type_id = pms_contractor_type.cnt_id'; 
$query .=' INNER JOIN pms_project ON pms_project.prj_id=pms_project_contractor.cni_project_id';

$contracttype = $request->input('cni_contractor_type_id');
if(!empty($contracttype) && is_numeric($contracttype)){
    $query .= " AND cni_contractor_type_id = ".intval($contracttype); 
}
$query .=' WHERE 1=1';

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

else if($reportType==8 || $reportType==9 || $reportType==10 ){
$query = ' SELECT  location_zone.add_name_or AS zone,
                cni_name AS cni_name, location_woreda.add_name_or AS woreda,
                prj_name, prj_code , prj_location_description,sci_name_or AS sector,
                COALESCE(budget_summary.bdr_released_amount, 0) AS bdr_released_amount,
                prp_physical_performance, prp_physical_planned, prj_total_estimate_budget,
                COALESCE(prj_urban_ben_number, 0) + COALESCE(prj_rural_ben_number, 0) AS beneficiery,
                EXTRACT(YEAR FROM prj_start_date_plan_gc::date) AS start_year,
                EXTRACT(YEAR FROM prj_end_date_plan_gc::date) AS end_year,
                prp_budget_baseline,prp_total_budget_used,prp_physical_baseline,
                COALESCE(budget_summary.bdr_requested_amountspecificyear, 0) AS bdr_requested_amountspecificyear,
                COALESCE(budget_summary.bdr_released_amountspecificyear, 0) AS bdr_released_amountspecificyear
                FROM pms_project
        LEFT JOIN pms_project_contractor 
            ON pms_project.prj_id = pms_project_contractor.cni_project_id
        LEFT JOIN pms_sector_information 
            ON pms_project.prj_sector_id = pms_sector_information.sci_id
        INNER JOIN pms_project_performance 
            ON pms_project.prj_id = pms_project_performance.prp_project_id
        INNER JOIN gen_address_structure AS location_zone 
            ON pms_project.prj_location_zone_id = location_zone.add_id
        LEFT JOIN gen_address_structure AS location_woreda 
            ON pms_project.prj_location_woreda_id = location_woreda.add_id
        -- released and requested amount for specific year
        LEFT JOIN (
            SELECT 
                bdr_project_id,
                SUM(CASE WHEN bdr_budget_year_id = bdy_id THEN bdr_requested_amount ELSE 0 END) AS bdr_requested_amountspecificyear,
                SUM(CASE WHEN bdr_budget_year_id = bdy_id THEN bdr_released_amount ELSE 0 END) AS bdr_released_amountspecificyear,
                SUM(bdr_released_amount) AS bdr_released_amount 
            FROM pms_budget_request
            LEFT JOIN pms_budget_year ON bdr_budget_year_id = bdy_id
            GROUP BY bdr_project_id
        ) AS budget_summary
            ON pms_project.prj_id = budget_summary.bdr_project_id';
      $query .=' WHERE 1=1';
   }

   else if($reportType==11){
$query = ' SELECT  location_zone.add_name_or AS zone,
                location_woreda.add_name_or AS woreda,
                prj_name, prj_code ,prj_location_description,sci_name_or AS sector,
                 pms_budget_year.bdy_name AS budgetyear,
                prp_physical_performance, prp_physical_planned,
                prp_pyhsical_planned_month_1, prp_pyhsical_planned_month_2, prp_pyhsical_planned_month_3,prp_pyhsical_planned_month_4,prp_pyhsical_planned_month_5,
                prp_pyhsical_planned_month_6,prp_pyhsical_planned_month_7,prp_pyhsical_planned_month_8,prp_pyhsical_planned_month_9,prp_pyhsical_planned_month_10,prp_pyhsical_planned_month_11,prp_pyhsical_planned_month_12,
                 ( COALESCE(prp_pyhsical_planned_month_1, 0) +
                    COALESCE(prp_pyhsical_planned_month_2, 0) +
                    COALESCE(prp_pyhsical_planned_month_3, 0) 
                ) AS quarter1total,

                ( COALESCE(prp_pyhsical_planned_month_4, 0) +
                    COALESCE(prp_pyhsical_planned_month_5, 0) +
                    COALESCE(prp_pyhsical_planned_month_6, 0) 
                ) AS quarter2total,

                 ( COALESCE(prp_pyhsical_planned_month_7, 0) +
                    COALESCE(prp_pyhsical_planned_month_8, 0) +
                    COALESCE(prp_pyhsical_planned_month_9, 0) 
                ) AS quarter3total,

                 ( COALESCE(prp_pyhsical_planned_month_10, 0) +
                    COALESCE(prp_pyhsical_planned_month_11, 0) +
                    COALESCE(prp_pyhsical_planned_month_12, 0) 
                ) AS quarter4total,
                prp_budget_baseline,prp_physical_baseline FROM pms_project
                INNER JOIN pms_sector_information 
                    ON pms_project.prj_sector_id = pms_sector_information.sci_id
                INNER JOIN pms_project_performance 
                    ON pms_project.prj_id = pms_project_performance.prp_project_id
                INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_performance.prp_budget_year_id
                INNER JOIN gen_address_structure AS location_zone 
                    ON pms_project.prj_location_zone_id = location_zone.add_id
                LEFT JOIN gen_address_structure AS location_woreda 
                    ON pms_project.prj_location_woreda_id = location_woreda.add_id';
      $query .=' WHERE 1=1';
      $budgetyearid = $request->input('prp_budget_year_id');
        if(!empty($budgetyearid) && is_numeric($budgetyearid)){
            $query .= " AND prp_budget_year_id = ".intval($budgetyearid); 
        }
   }

  else if($reportType==12){
      $query = ' SELECT  location_zone.add_name_or AS zone,
                location_woreda.add_name_or AS woreda,
                prj_name, prj_code , prj_location_description, sci_name_or AS sector,
                pms_budget_year.bdy_name AS budgetyear,
                prp_physical_performance, prp_physical_planned,
                prp_finan_planned_month_1, prp_finan_planned_month_2, prp_finan_planned_month_3,prp_finan_planned_month_4,prp_finan_planned_month_5,
                prp_finan_planned_month_6,prp_finan_planned_month_7,prp_finan_planned_month_8,prp_finan_planned_month_9,prp_finan_planned_month_10,prp_finan_planned_month_11,prp_finan_planned_month_12,
                 ( COALESCE(prp_finan_planned_month_1, 0) +
                    COALESCE(prp_finan_planned_month_2, 0) +
                    COALESCE(prp_finan_planned_month_3, 0) 
                ) AS quarter1total,

                ( COALESCE(prp_finan_planned_month_4, 0) +
                    COALESCE(prp_finan_planned_month_5, 0) +
                    COALESCE(prp_finan_planned_month_6, 0) 
                ) AS quarter2total,

                 ( COALESCE(prp_finan_planned_month_7, 0) +
                    COALESCE(prp_finan_planned_month_8, 0) +
                    COALESCE(prp_finan_planned_month_9, 0) 
                ) AS quarter3total,

                 ( COALESCE(prp_finan_planned_month_10, 0) +
                    COALESCE(prp_finan_planned_month_11, 0) +
                    COALESCE(prp_finan_planned_month_12, 0) 
                ) AS quarter4total,

                prp_budget_baseline,prp_physical_baseline FROM pms_project
                INNER JOIN pms_sector_information 
                    ON pms_project.prj_sector_id = pms_sector_information.sci_id
                INNER JOIN pms_project_performance 
                    ON pms_project.prj_id = pms_project_performance.prp_project_id
                INNER JOIN pms_budget_year ON pms_budget_year.bdy_id=pms_project_performance.prp_budget_year_id
                INNER JOIN gen_address_structure AS location_zone 
                    ON pms_project.prj_location_zone_id = location_zone.add_id
                LEFT JOIN gen_address_structure AS location_woreda 
                    ON pms_project.prj_location_woreda_id = location_woreda.add_id';
      $query .=' WHERE 1=1';

      $budgetyearid = $request->input('prp_budget_year_id');
        if(!empty($budgetyearid) && is_numeric($budgetyearid)){
            $query .= " AND prp_budget_year_id = ".intval($budgetyearid); 
        }
   }
   $prjlocationzoneid = $request->input('prj_location_zone_id');
    if(!empty($prjlocationzoneid)){
        $query .= " AND pms_project.prj_location_zone_id = '".$prjlocationzoneid."'"; 
    }
    // Filter by woreda if provided
    $prjlocationworedaid = $request->input('prj_location_woreda_id');
    if(!empty($prjlocationworedaid)){
        $query .= " AND pms_project.prj_location_woreda_id = '".$prjlocationworedaid."'"; 
    }
//$query .="GROUP BY sci_id";
//END COMMON PARAMETERS
 $data_info=DB::select($query);
$resultObject= array(
    "data" =>$data_info,
    "previledge"=>array());

return response()->json($resultObject,200, [], JSON_NUMERIC_CHECK);
}

}