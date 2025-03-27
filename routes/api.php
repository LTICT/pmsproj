<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::resource('films', 'film\\MoviesController');*/
/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
});*/
// Forgot password route
Route::post('forgot-password', 'Auth\PasswordResetController@sendResetLink');
// Reset password route
Route::post('reset-password', 'Auth\PasswordResetController@resetPassword');

Route::get('send_email', 'Api\EmailControllerTest@sendWelcomeEmail');
Route::post('email/send_email', 'Api\EmailController@sendEmail');
Route::get('/data', 'GanttController@get');
Route::resource('task', 'TaskController');
Route::resource('link', 'LinkController');
Route::post('login', 'AuthController@login')->middleware('throttle:login');
Route::post('refreshtoken', 'AuthController@refreshToken');
Route::post('addressbyparent', 'Api\GenaddressstructureController@addressByParent');
Route::post('departmentbyparent', 'Api\GendepartmentController@departmentByParent');
Route::post('project_document/insertgrid', 'Api\PmsprojectdocumentController@insertgrid');
   // Route::post('department/listgrid', [\Api\GendepartmentController::class, 'listgrid'])->middleware('apilogin');
Route::group(['middleware' => [\App\Http\Middleware\JwtMiddleware::class,'throttle:api'], 'except' => ['api/login', 'api/register']], function () {
    Route::resource('project', 'Api\PmsprojectController');
    Route::post('project/listgrid', 'Api\PmsprojectController@listgrid');
Route::post('project/insertgrid', 'Api\PmsprojectController@insertgrid');
Route::post('project/updategrid', 'Api\PmsprojectController@updategrid');
Route::post('project/deletegrid', 'Api\PmsprojectController@deletegrid');
Route::post('project/search', 'PmsprojectController@search');
Route::post('project/listgridsearch', 'Api\PmsprojectController@listgridsearch');

  Route::resource('project_cso', 'Api\PmscsoprojectController');
Route::post('project_cso/listgrid', 'Api\PmscsoprojectController@listgrid');
Route::post('project_cso/insertgrid', 'Api\PmscsoprojectController@insertgrid');
Route::post('project_cso/updategrid', 'Api\PmscsoprojectController@updategrid');
Route::post('project_cso/deletegrid', 'Api\PmscsoprojectController@deletegrid');
Route::resource('project_citizenship', 'Api\PmscitizenshipprojectController');
Route::post('project_citizenship/listgrid', 'Api\PmscitizenshipprojectController@listgrid');
Route::post('project_citizenship/insertgrid', 'Api\PmscitizenshipprojectController@insertgrid');
Route::post('project_citizenship/updategrid', 'Api\PmscitizenshipprojectController@updategrid');
Route::post('project_citizenship/deletegrid', 'Api\PmscitizenshipprojectController@deletegrid');
Route::post('project_citizenship/listgridsearch', 'Api\PmscitizenshipprojectController@listgridsearch');

 Route::post('dashboard_builder', 'Api\GendashboardbuilderController@dashboardData');
 Route::post('superset_dashboard', 'Api\GendashboardbuilderController@supersetData');
Route::post('menus', 'Api\GenmenubuilderController@listgrid');


       Route::post('roles/updategrid', 'Api\TblrolesController@updategrid');
       Route::post('roles/listgrid', 'Api\TblrolesController@listgrid');
    Route::post('department/departmentbyparent', 'Api\GendepartmentController@departmentByParent');

    Route::post('department/listdepartment', 'Api\GendepartmentController@listdepartment');
    Route::post('department/listgrid', 'Api\GendepartmentController@listgrid');
    Route::post('department/insertgrid', 'Api\GendepartmentController@insertgrid');
    Route::post('department/updategrid', 'Api\GendepartmentController@updategrid');
    Route::post('department/deletegrid', 'Api\GendepartmentController@deletegrid');

        Route::post('budget_month/listgrid', 'Api\PmsbudgetmonthController@listgrid');
    Route::post('budget_month/insertgrid', 'Api\PmsbudgetmonthController@insertgrid');
    Route::post('budget_month/updategrid', 'Api\PmsbudgetmonthController@updategrid');
    Route::post('budget_month/deletegrid', 'Api\PmsbudgetmonthController@deletegrid');
    Route::post('budget_month/search', 'PmsbudgetmonthController@search');
    //new
    Route::resource('email_information', 'GenemailinformationController');
    Route::post('email_information/listgrid', 'Api\GenemailinformationController@listgrid');
    Route::post('email_information/insertgrid', 'Api\GenemailinformationController@insertgrid');
    Route::post('email_information/updategrid', 'Api\GenemailinformationController@updategrid');
    Route::post('email_information/deletegrid', 'Api\GenemailinformationController@deletegrid');
    Route::resource('email_template', 'GenemailtemplateController');
    Route::post('email_template/listgrid', 'Api\GenemailtemplateController@listgrid');
    Route::post('email_template/insertgrid', 'Api\GenemailtemplateController@insertgrid');
    Route::post('email_template/updategrid', 'Api\GenemailtemplateController@updategrid');
    Route::post('email_template/deletegrid', 'Api\GenemailtemplateController@deletegrid');
    Route::resource('sms_template', 'GensmstemplateController');
    Route::post('sms_template/listgrid', 'Api\GensmstemplateController@listgrid');
    Route::post('sms_template/insertgrid', 'Api\GensmstemplateController@insertgrid');
    Route::post('sms_template/updategrid', 'Api\GensmstemplateController@updategrid');
    Route::post('sms_template/deletegrid', 'Api\GensmstemplateController@deletegrid');
    Route::resource('sms_information', 'GensmsinformationController');
    Route::post('sms_information/listgrid', 'Api\GensmsinformationController@listgrid');
    Route::post('sms_information/insertgrid', 'Api\GensmsinformationController@insertgrid');
    Route::post('sms_information/updategrid', 'Api\GensmsinformationController@updategrid');
    Route::post('sms_information/deletegrid', 'Api\GensmsinformationController@deletegrid');
     Route::resource('budget_exip_detail', 'PmsbudgetexipdetailController');
    Route::post('budget_exip_detail/listgrid', 'Api\PmsbudgetexipdetailController@listgrid');
    Route::post('budget_exip_detail/insertgrid', 'Api\PmsbudgetexipdetailController@insertgrid');
    Route::post('budget_exip_detail/updategrid', 'Api\PmsbudgetexipdetailController@updategrid');
    Route::post('budget_exip_detail/deletegrid', 'Api\PmsbudgetexipdetailController@deletegrid');

    Route::resource('budget_ex_source', 'PmsbudgetexsourceController');
    Route::post('budget_ex_source/listgrid', 'Api\PmsbudgetexsourceController@listgrid');
    Route::post('budget_ex_source/insertgrid', 'Api\PmsbudgetexsourceController@insertgrid');
    Route::post('budget_ex_source/updategrid', 'Api\PmsbudgetexsourceController@updategrid');
    Route::post('budget_ex_source/deletegrid', 'Api\PmsbudgetexsourceController@deletegrid');

    Route::resource('budget_request_amount', 'PmsbudgetrequestamountController');
    Route::post('budget_request_amount/listgrid', 'Api\PmsbudgetrequestamountController@listgrid');
    Route::post('budget_request_amount/insertgrid', 'Api\PmsbudgetrequestamountController@insertgrid');
    Route::post('budget_request_amount/updategrid', 'Api\PmsbudgetrequestamountController@updategrid');
    Route::post('budget_request_amount/deletegrid', 'Api\PmsbudgetrequestamountController@deletegrid');
    Route::resource('budget_request_task', 'PmsbudgetrequesttaskController');
    Route::post('budget_request_task/listgrid', 'Api\PmsbudgetrequesttaskController@listgrid');
    Route::post('budget_request_task/insertgrid', 'Api\PmsbudgetrequesttaskController@insertgrid');
    Route::post('budget_request_task/updategrid', 'Api\PmsbudgetrequesttaskController@updategrid');
    Route::post('budget_request_task/deletegrid', 'Api\PmsbudgetrequesttaskController@deletegrid');
     Route::resource('payment_category', 'PmspaymentcategoryController');
    Route::post('payment_category/listgrid', 'Api\PmspaymentcategoryController@listgrid');
    Route::post('payment_category/insertgrid', 'Api\PmspaymentcategoryController@insertgrid');
    Route::post('payment_category/updategrid', 'Api\PmspaymentcategoryController@updategrid');
    Route::post('payment_category/deletegrid', 'Api\PmspaymentcategoryController@deletegrid');

    Route::post('budget_month/listgrid', 'Api\PmsbudgetmonthController@listgrid');
Route::post('budget_month/insertgrid', 'Api\PmsbudgetmonthController@insertgrid');
Route::post('budget_month/updategrid', 'Api\PmsbudgetmonthController@updategrid');
Route::post('budget_month/deletegrid', 'Api\PmsbudgetmonthController@deletegrid');
Route::post('budget_month/search', 'PmsbudgetmonthController@search');

Route::post('budget_source/listgrid', 'Api\PmsbudgetsourceController@listgrid');
Route::post('budget_source/insertgrid', 'Api\PmsbudgetsourceController@insertgrid');
Route::post('budget_source/updategrid', 'Api\PmsbudgetsourceController@updategrid');
Route::post('budget_source/deletegrid', 'Api\PmsbudgetsourceController@deletegrid');

Route::resource('budget_year', 'PmsbudgetyearController');
Route::post('budget_year/listgrid', 'Api\PmsbudgetyearController@listgrid');
Route::post('budget_year/insertgrid', 'Api\PmsbudgetyearController@insertgrid');
Route::post('budget_year/updategrid', 'Api\PmsbudgetyearController@updategrid');
Route::post('budget_year/deletegrid', 'Api\PmsbudgetyearController@deletegrid');
Route::post('budget_year/search', 'PmsbudgetyearController@search');
Route::post('budget_year/listdropdown', 'Api\PmsbudgetyearController@listdropdown');

Route::resource('department', 'GendepartmentController');
Route::post('department/insertgrid', 'Api\GendepartmentController@insertgrid');
Route::post('department/updategrid', 'Api\GendepartmentController@updategrid');
Route::post('department/deletegrid', 'Api\GendepartmentController@deletegrid');
Route::post('department/search', 'GendepartmentController@search');

Route::post('contractor_type/listgrid', 'Api\PmscontractortypeController@listgrid');
Route::post('contractor_type/insertgrid', 'Api\PmscontractortypeController@insertgrid');
Route::post('contractor_type/updategrid', 'Api\PmscontractortypeController@updategrid');
Route::post('contractor_type/deletegrid', 'Api\PmscontractortypeController@deletegrid');
Route::post('contractor_type/search', 'PmscontractortypeController@search');

Route::post('contract_termination_reason/listgrid', 'Api\PmscontractterminationreasonController@listgrid');
Route::post('contract_termination_reason/insertgrid', 'Api\PmscontractterminationreasonController@insertgrid');
Route::post('contract_termination_reason/updategrid', 'Api\PmscontractterminationreasonController@updategrid');
Route::post('contract_termination_reason/deletegrid', 'Api\PmscontractterminationreasonController@deletegrid');
Route::post('contract_termination_reason/search', 'PmscontractterminationreasonController@search');

Route::post('document_type/listgrid', 'Api\PmsdocumenttypeController@listgrid');
Route::post('document_type/insertgrid', 'Api\PmsdocumenttypeController@insertgrid');
Route::post('document_type/updategrid', 'Api\PmsdocumenttypeController@updategrid');
Route::post('document_type/deletegrid', 'Api\PmsdocumenttypeController@deletegrid');
Route::post('document_type/search', 'PmsdocumenttypeController@search');

Route::post('project_category/listgrid', 'Api\PmsprojectcategoryController@listgrid');
Route::post('project_category/insertgrid', 'Api\PmsprojectcategoryController@insertgrid');
Route::post('project_category/updategrid', 'Api\PmsprojectcategoryController@updategrid');
Route::post('project_category/deletegrid', 'Api\PmsprojectcategoryController@deletegrid');

Route::post('sector_information/listgrid', 'Api\PmssectorinformationController@listgrid');
Route::post('sector_information/insertgrid', 'Api\PmssectorinformationController@insertgrid');
Route::post('sector_information/updategrid', 'Api\PmssectorinformationController@updategrid');
Route::post('sector_information/deletegrid', 'Api\PmssectorinformationController@deletegrid');
Route::post('sector_information/search', 'PmssectorinformationController@search');

Route::post('stakeholder_type/listgrid', 'Api\PmsstakeholdertypeController@listgrid');
Route::post('stakeholder_type/insertgrid', 'Api\PmsstakeholdertypeController@insertgrid');
Route::post('stakeholder_type/updategrid', 'Api\PmsstakeholdertypeController@updategrid');
Route::post('stakeholder_type/deletegrid', 'Api\PmsstakeholdertypeController@deletegrid');
Route::post('stakeholder_type/search', 'PmsstakeholdertypeController@search');

Route::post('sector_category/listgrid', 'Api\PrjsectorcategoryController@listgrid');
Route::post('sector_category/insertgrid', 'Api\PrjsectorcategoryController@insertgrid');
Route::post('sector_category/updategrid', 'Api\PrjsectorcategoryController@updategrid');
Route::post('sector_category/deletegrid', 'Api\PrjsectorcategoryController@deletegrid');
Route::post('sector_category/search', 'PrjsectorcategoryController@search');

Route::post('expenditure_code/listgrid', 'Api\PmsexpenditurecodeController@listgrid');
Route::post('expenditure_code/insertgrid', 'Api\PmsexpenditurecodeController@insertgrid');
Route::post('expenditure_code/updategrid', 'Api\PmsexpenditurecodeController@updategrid');
Route::post('expenditure_code/deletegrid', 'Api\PmsexpenditurecodeController@deletegrid');
Route::post('expenditure_code/search', 'PmsexpenditurecodeController@search');

Route::post('pages/listgrid', 'Api\TblpagesController@listgrid');
Route::post('pages/insertgrid', 'Api\TblpagesController@insertgrid');
Route::post('pages/updategrid', 'Api\TblpagesController@updategrid');
Route::post('pages/deletegrid', 'Api\TblpagesController@deletegrid');
Route::post('pages/search', 'TblpagesController@search');

Route::post('permission/listgrid', 'Api\TblpermissionController@listgrid');
Route::post('permission/insertgrid', 'Api\TblpermissionController@insertgrid');
Route::post('permission/updategrid', 'Api\TblpermissionController@updategrid');
Route::post('permission/deletegrid', 'Api\TblpermissionController@deletegrid');
Route::post('permission/search', 'TblpermissionController@search');
Route::post('permission/listroleassignedpermission', 'Api\TblpermissionController@listroleassignedpermission');
Route::post('permission/listuserassignedpermission', 'Api\TblpermissionController@listuserassignedpermission');

Route::post('roles/insertgrid', 'Api\TblrolesController@insertgrid');
Route::post('roles/deletegrid', 'Api\TblrolesController@deletegrid');
Route::post('roles/search', 'TblrolesController@search');

Route::post('user_role/listgrid', 'Api\TbluserroleController@listgrid');
Route::post('user_role/insertgrid', 'Api\TbluserroleController@insertgrid');
Route::post('user_role/updategrid', 'Api\TbluserroleController@updategrid');
Route::post('user_role/deletegrid', 'Api\TbluserroleController@deletegrid');
Route::post('user_role/search', 'TbluserroleController@search');

Route::post('users/listgrid', 'Api\TblusersController@listgrid');
Route::post('users/insertgrid', 'Api\TblusersController@insertgrid');
Route::post('users/updategrid', 'Api\TblusersController@updategrid');
Route::post('users/deletegrid', 'Api\TblusersController@deletegrid');
Route::post('users/search', 'TblusersController@search');
Route::post('users/changeuserstatus', 'Api\TblusersController@changeuserstatus');
Route::post('users/getuserinfo', 'Api\TblusersController@getUserInfo');
Route::post('users/updateprofile', 'Api\TblusersController@updateUserProfile');

Route::resource('address_structure', 'GenaddressstructureController');
//Route::post('address_structure/listgrid', 'Api\GenaddressstructureController@listgrid');
Route::post('address_structure/insertgrid', 'Api\GenaddressstructureController@insertgrid');
Route::post('address_structure/updategrid', 'Api\GenaddressstructureController@updategrid');
Route::post('address_structure/deletegrid', 'Api\GenaddressstructureController@deletegrid');
Route::post('address_structure/search', 'GenaddressstructureController@search');
Route::post('address_structure/listgrid', 'Api\GenaddressstructureController@listgrid');
Route::post('address_structure/listaddress', 'Api\GenaddressstructureController@listaddress');

Route::post('budget_request/listgrid', 'Api\PmsbudgetrequestController@listgrid');
Route::post('budget_request/insertgrid', 'Api\PmsbudgetrequestController@insertgrid');
Route::post('budget_request/updategrid', 'Api\PmsbudgetrequestController@updategrid');
Route::post('budget_request/deletegrid', 'Api\PmsbudgetrequestController@deletegrid');
Route::post('budget_request/search', 'PmsbudgetrequestController@search');
Route::post('budget_request_approval/listgrid', 'Api\PmsbudgetrequestapprovalController@listgrid');
Route::post('budget_request_approval/updategrid', 'Api\PmsbudgetrequestapprovalController@updategrid');

Route::post('cso_proposal_request_approval/listgrid', 'Api\PmscsoproposalrequestapprovalController@listgrid');
Route::post('cso_proposal_request_approval/updategrid', 'Api\PmscsoproposalrequestapprovalController@updategrid');
Route::post('cso_budget_request/listgrid', 'Api\PmscsobudgetrequestController@listgrid');
Route::post('cso_budget_request/insertgrid', 'Api\PmscsobudgetrequestController@insertgrid');
Route::post('cso_budget_request/updategrid', 'Api\PmscsobudgetrequestController@updategrid');
Route::post('cso_budget_request/deletegrid', 'Api\PmscsobudgetrequestController@deletegrid');
Route::post('cso_budget_request/search', 'PmscsobudgetrequestController@search');

Route::resource('project_contractor', 'PmsprojectcontractorController');
Route::post('project_contractor/listgrid', 'Api\PmsprojectcontractorController@listgrid');
Route::post('project_contractor/insertgrid', 'Api\PmsprojectcontractorController@insertgrid');
Route::post('project_contractor/updategrid', 'Api\PmsprojectcontractorController@updategrid');
Route::post('project_contractor/deletegrid', 'Api\PmsprojectcontractorController@deletegrid');
Route::post('project_contractor/search', 'PmsprojectcontractorController@search');
Route::post('project_document/listgrid', 'Api\PmsprojectdocumentController@listgrid');

Route::post('project_document/updategrid', 'Api\PmsprojectdocumentController@updategrid');
Route::post('project_document/deletegrid', 'Api\PmsprojectdocumentController@deletegrid');
Route::post('project_document/search', 'PmsprojectdocumentController@search');

Route::post('project_payment/listgrid', 'Api\PmsprojectpaymentController@listgrid');
Route::post('project_payment/insertgrid', 'Api\PmsprojectpaymentController@insertgrid');
Route::post('project_payment/updategrid', 'Api\PmsprojectpaymentController@updategrid');
Route::post('project_payment/deletegrid', 'Api\PmsprojectpaymentController@deletegrid');
Route::post('project_payment/search', 'PmsprojectpaymentController@search');

Route::post('project_stakeholder/listgrid', 'Api\PmsprojectstakeholderController@listgrid');
Route::post('project_stakeholder/insertgrid', 'Api\PmsprojectstakeholderController@insertgrid');
Route::post('project_stakeholder/updategrid', 'Api\PmsprojectstakeholderController@updategrid');
Route::post('project_stakeholder/deletegrid', 'Api\PmsprojectstakeholderController@deletegrid');
Route::post('project_stakeholder/search', 'PmsprojectstakeholderController@search');

Route::post('project_status/listgrid', 'Api\PmsprojectstatusController@listgrid');
Route::post('project_status/insertgrid', 'Api\PmsprojectstatusController@insertgrid');
Route::post('project_status/updategrid', 'Api\PmsprojectstatusController@updategrid');
Route::post('project_status/deletegrid', 'Api\PmsprojectstatusController@deletegrid');
Route::post('project_status/search', 'PmsprojectstatusController@search');

Route::post('access_log/listgrid', 'Api\TblaccesslogController@listgrid');
Route::post('access_log/insertgrid', 'Api\TblaccesslogController@insertgrid');
Route::post('access_log/updategrid', 'Api\TblaccesslogController@updategrid');
Route::post('access_log/deletegrid', 'Api\TblaccesslogController@deletegrid');
Route::post('access_log/search', 'TblaccesslogController@search');

Route::post('notification', 'Api\GennotificationController@listgrid');
Route::post('updatenotification', 'Api\GennotificationController@updategrid');

Route::post('user/change_password', 'AuthController@changePassword');

//NEWLY ADDED
 
    Route::post('expenditure_code/listgrid', 'Api\PmsexpenditurecodeController@listgrid');
    Route::post('expenditure_code/insertgrid', 'Api\PmsexpenditurecodeController@insertgrid');
    Route::post('expenditure_code/updategrid', 'Api\PmsexpenditurecodeController@updategrid');
    Route::post('expenditure_code/deletegrid', 'Api\PmsexpenditurecodeController@deletegrid');
    Route::post('expenditure_code/search', 'PmsexpenditurecodeController@search');
    
    Route::post('project_budget_expenditure/listgrid', 'Api\PmsprojectbudgetexpenditureController@listgrid');
    Route::post('project_budget_expenditure/insertgrid', 'Api\PmsprojectbudgetexpenditureController@insertgrid');
    Route::post('project_budget_expenditure/updategrid', 'Api\PmsprojectbudgetexpenditureController@updategrid');
    Route::post('project_budget_expenditure/deletegrid', 'Api\PmsprojectbudgetexpenditureController@deletegrid');
    Route::post('project_budget_expenditure/search', 'PmsprojectbudgetexpenditureController@search');
    
    Route::post('project_budget_source/listgrid', 'Api\PmsprojectbudgetsourceController@listgrid');
    Route::post('project_budget_source/insertgrid', 'Api\PmsprojectbudgetsourceController@insertgrid');
    Route::post('project_budget_source/updategrid', 'Api\PmsprojectbudgetsourceController@updategrid');
    Route::post('project_budget_source/deletegrid', 'Api\PmsprojectbudgetsourceController@deletegrid');
    Route::post('project_budget_source/search', 'PmsprojectbudgetsourceController@search');
    
    Route::post('project_employee/listgrid', 'Api\PmsprojectemployeeController@listgrid');
    Route::post('project_employee/insertgrid', 'Api\PmsprojectemployeeController@insertgrid');
    Route::post('project_employee/updategrid', 'Api\PmsprojectemployeeController@updategrid');
    Route::post('project_employee/deletegrid', 'Api\PmsprojectemployeeController@deletegrid');
    Route::post('project_employee/search', 'PmsprojectemployeeController@search');
    
    Route::post('project_handover/listgrid', 'Api\PmsprojecthandoverController@listgrid');
    Route::post('project_handover/insertgrid', 'Api\PmsprojecthandoverController@insertgrid');
    Route::post('project_handover/updategrid', 'Api\PmsprojecthandoverController@updategrid');
    Route::post('project_handover/deletegrid', 'Api\PmsprojecthandoverController@deletegrid');
    Route::post('project_handover/search', 'PmsprojecthandoverController@search');
    
    Route::post('project_performance/listgrid', 'Api\PmsprojectperformanceController@listgrid');
    Route::post('project_performance/insertgrid', 'Api\PmsprojectperformanceController@insertgrid');
    Route::post('project_performance/updategrid', 'Api\PmsprojectperformanceController@updategrid');
    Route::post('project_performance/deletegrid', 'Api\PmsprojectperformanceController@deletegrid');
    Route::post('project_performance/search', 'PmsprojectperformanceController@search');
    
    Route::post('project_plan/listgrid', 'Api\PmsprojectplanController@listgrid');
    Route::post('project_plan/insertgrid', 'Api\PmsprojectplanController@insertgrid');
    Route::post('project_plan/updategrid', 'Api\PmsprojectplanController@updategrid');
    Route::post('project_plan/deletegrid', 'Api\PmsprojectplanController@deletegrid');
    Route::post('project_plan/search', 'PmsprojectplanController@search');
    
    Route::post('project_supplimentary/listgrid', 'Api\PmsprojectsupplimentaryController@listgrid');
    Route::post('project_supplimentary/insertgrid', 'Api\PmsprojectsupplimentaryController@insertgrid');
    Route::post('project_supplimentary/updategrid', 'Api\PmsprojectsupplimentaryController@updategrid');
    Route::post('project_supplimentary/deletegrid', 'Api\PmsprojectsupplimentaryController@deletegrid');
    Route::post('project_supplimentary/search', 'PmsprojectsupplimentaryController@search');
    
    Route::post('project_variation/listgrid', 'Api\PmsprojectvariationController@listgrid');
    Route::post('project_variation/insertgrid', 'Api\PmsprojectvariationController@insertgrid');
    Route::post('project_variation/updategrid', 'Api\PmsprojectvariationController@updategrid');
    Route::post('project_variation/deletegrid', 'Api\PmsprojectvariationController@deletegrid');
    Route::post('project_variation/search', 'PmsprojectvariationController@search');
    
    Route::post('project_budget_plan/listgrid', 'Api\PmsprojectbudgetplanController@listgrid');
    Route::post('project_budget_plan/insertgrid', 'Api\PmsprojectbudgetplanController@insertgrid');
    Route::post('project_budget_plan/updategrid', 'Api\PmsprojectbudgetplanController@updategrid');
    Route::post('project_budget_plan/deletegrid', 'Api\PmsprojectbudgetplanController@deletegrid');
    Route::post('project_budget_plan/search', 'PmsprojectbudgetplanController@search');



    //END NEWLY ADDED
Route::post('statistics/getprojectstatistics', 'Api\StatisticalReportController@getprojectstatistics');
Route::post('statistical_report/getstatistics', 'Api\StatisticalReportController@getStatistics');
Route::post('report/getreport', 'Api\ReportController@getReport');
Route::post('me', 'TokenController@validateToken');
Route::post('refreshToken', 'TokenController@refreshToken');

Route::resource('conversation_information', 'GenconversationinformationController');
    Route::post('conversation_information/listgrid', 'Api\GenconversationinformationController@listgrid');
    Route::post('conversation_information/insertgrid', 'Api\GenconversationinformationController@insertgrid');
    Route::post('conversation_information/updategrid', 'Api\GenconversationinformationController@updategrid');
    Route::post('conversation_information/deletegrid', 'Api\GenconversationinformationController@deletegrid');
     Route::resource('request_information', 'GenrequestinformationController');
    Route::post('request_information/listgrid', 'Api\GenrequestinformationController@listgrid');
    Route::post('request_information/insertgrid', 'Api\GenrequestinformationController@insertgrid');
    Route::post('request_information/updategrid', 'Api\GenrequestinformationController@updategrid');
    Route::post('request_information/deletegrid', 'Api\GenrequestinformationController@deletegrid');
    Route::resource('request_status', 'GenrequeststatusController');
    Route::post('request_status/listgrid', 'Api\GenrequeststatusController@listgrid');
    Route::post('request_status/insertgrid', 'Api\GenrequeststatusController@insertgrid');
    Route::post('request_status/updategrid', 'Api\GenrequeststatusController@updategrid');
    Route::post('request_status/deletegrid', 'Api\GenrequeststatusController@deletegrid');

     Route::resource('proposal_request', 'PmsproposalrequestController');
    Route::post('proposal_request/listgrid', 'Api\PmsproposalrequestController@listgrid');
    Route::post('proposal_request/insertgrid', 'Api\PmsproposalrequestController@insertgrid');
    Route::post('proposal_request/updategrid', 'Api\PmsproposalrequestController@updategrid');
    Route::post('proposal_request/deletegrid', 'Api\PmsproposalrequestController@deletegrid');
    Route::resource('request_category', 'PmsrequestcategoryController');
    Route::post('request_category/listgrid', 'Api\PmsrequestcategoryController@listgrid');
    Route::post('request_category/insertgrid', 'Api\PmsrequestcategoryController@insertgrid');
    Route::post('request_category/updategrid', 'Api\PmsrequestcategoryController@updategrid');
    Route::post('request_category/deletegrid', 'Api\PmsrequestcategoryController@deletegrid');

    Route::post('user_sector/listgrid', 'Api\TblusersectorController@listgrid');
    Route::post('user_sector/insertgrid', 'Api\TblusersectorController@insertgrid');
    Route::post('user_sector/updategrid', 'Api\TblusersectorController@updategrid');
    Route::post('user_sector/deletegrid', 'Api\TblusersectorController@deletegrid');
    Route::post('user_sector/listgridtree', 'Api\TblusersectorController@listgridtree');
    
    Route::post('user_sector/listusersectors', 'Api\TblusersectorController@getUserSectors');
    Route::post('program_info/listgrid', 'Api\PmsprograminfoController@listgrid');
    Route::post('program_info/insertgrid', 'Api\PmsprograminfoController@insertgrid');
    Route::post('program_info/updategrid', 'Api\PmsprograminfoController@updategrid');
    Route::post('program_info/deletegrid', 'Api\PmsprograminfoController@deletegrid');

    Route::resource('date_setting', 'GendatesettingController');
    Route::post('date_setting/listgrid', 'Api\GendatesettingController@listgrid');
    Route::post('date_setting/insertgrid', 'Api\GendatesettingController@insertgrid');
    Route::post('date_setting/updategrid', 'Api\GendatesettingController@updategrid');
    Route::post('date_setting/deletegrid', 'Api\GendatesettingController@deletegrid');
    Route::resource('request_followup', 'GenrequestfollowupController');
    Route::post('request_followup/listgrid', 'Api\GenrequestfollowupController@listgrid');
    Route::post('request_followup/insertgrid', 'Api\GenrequestfollowupController@insertgrid');
    Route::post('request_followup/updategrid', 'Api\GenrequestfollowupController@updategrid');
    Route::post('request_followup/deletegrid', 'Api\GenrequestfollowupController@deletegrid');

    Route::post('cso_info/listgrid', 'Api\PmscsoinfoController@listgrid');
    Route::post('cso_info/insertgrid', 'Api\PmscsoinfoController@insertgrid');
    Route::post('cso_info/updategrid', 'Api\PmscsoinfoController@updategrid');
    Route::post('cso_info/deletegrid', 'Api\PmscsoinfoController@deletegrid');
 
});

   //START CACHE MANAGEMENT
    Route::get('/cache-files', function (Request $request) {
    $search = $request->query('search');
    $perPage = $request->query('perPage', 10); 
    $page = $request->query('page', 1); 

    // Recursively get all files in the cache directory
    $cacheFiles = [];
    $cachePath = storage_path('framework/cache/data');
    $files = File::allFiles($cachePath);

    // Extract relative file paths
    foreach ($files as $file) {
        $relativePath = str_replace($cachePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
        $cacheFiles[] = $relativePath;
    }

    // Filter files based on search term
    if ($search) {
        $cacheFiles = array_filter($cacheFiles, function ($file) use ($search) {
            return str_contains($file, $search);
        });
    }

    // Paginate the results
    $totalFiles = count($cacheFiles);
    $offset = ($page - 1) * $perPage;
    $paginatedFiles = array_slice($cacheFiles, $offset, $perPage);

    return response()->json([
        'data' => $paginatedFiles,
        'total' => $totalFiles,
        'perPage' => $perPage,
        'currentPage' => $page,
    ]);
});
// Delete a specific cached file
Route::delete('/cache-files/{filename}', function ($filename) {
    $filePath = storage_path('framework/cache/data/' . $filename);
    if (file_exists($filePath)) {
        unlink($filePath);
        return response()->json(['message' => 'File deleted successfully']);
    }
    return response()->json(['message' => 'File not found'], 404);
});

Route::delete('/cache-files', function () {
    $cachePath = storage_path('framework/cache/data');

    // Delete all files in the cache directory
    $files = File::allFiles($cachePath);
    foreach ($files as $file) {
        unlink($file->getPathname());
    }

    return response()->json(['message' => 'All cache files deleted successfully']);
});

//END CACHE MANAGEMENT

