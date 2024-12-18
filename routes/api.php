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
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
});

Route::get('', 'DashboardController@index');
//Route::post('login', 'Api\APIController@loginUser');
Route::post('listprojects', 'Api\APIController@listProjects');
Route::post('listperformance', 'Api\APIController@listPerformance');
Route::post('saveperformance', 'Api\APIController@savePerformance');
Route::post('projectdashboard', 'Api\APIController@projectDashboard');
Route::post('projectdetail', 'Api\APIController@showProjectDetail');

Route::get('C2BPaymentQueryRequest', 'Api\PaymentController@C2BPaymentQueryRequest');
Route::get('C2BPaymentValidationRequest', 'Api\PaymentController@C2BPaymentValidationRequest');
Route::get('C2BPaymentConfirmationRequest', 'Api\PaymentController@C2BPaymentConfirmationRequest');

Route::get('/data', 'GanttController@get');
Route::resource('task', 'TaskController');
Route::resource('link', 'LinkController');

Route::post('budget_year/listgrid', 'Api\PmscontractorController@listgrid');
//START ROUTE
Route::resource('department', 'GendepartmentController');

Route::post('department/insertgrid', 'Api\GendepartmentController@insertgrid');
Route::post('department/updategrid', 'Api\GendepartmentController@updategrid');
Route::post('department/deletegrid', 'Api\GendepartmentController@deletegrid');
Route::post('department/search', 'GendepartmentController@search');
Route::post('department/getform', 'GendepartmentController@getForm');
Route::post('department/getlistform', 'GendepartmentController@getListForm');

 Route::resource('budget_request', 'PmsbudgetrequestController');
Route::post('budget_request/listgrid', 'Api\PmsbudgetrequestController@listgrid');
Route::post('budget_request/insertgrid', 'Api\PmsbudgetrequestController@insertgrid');
Route::post('budget_request/updategrid', 'Api\PmsbudgetrequestController@updategrid');
Route::post('budget_request/deletegrid', 'Api\PmsbudgetrequestController@deletegrid');
Route::post('budget_request/search', 'PmsbudgetrequestController@search');
Route::post('budget_request/getform', 'PmsbudgetrequestController@getForm');
Route::post('budget_request/getlistform', 'PmsbudgetrequestController@getListForm');

Route::resource('budget_source', 'PmsbudgetsourceController');
Route::post('budget_source/listgrid', 'Api\PmsbudgetsourceController@listgrid');
Route::post('budget_source/insertgrid', 'Api\PmsbudgetsourceController@insertgrid');
Route::post('budget_source/updategrid', 'Api\PmsbudgetsourceController@updategrid');
Route::post('budget_source/deletegrid', 'Api\PmsbudgetsourceController@deletegrid');
Route::post('budget_source/search', 'PmsbudgetsourceController@search');
Route::post('budget_source/getform', 'PmsbudgetsourceController@getForm');
Route::post('budget_source/getlistform', 'PmsbudgetsourceController@getListForm');

 Route::resource('budget_year', 'PmsbudgetyearController');
Route::post('budget_year/listgrid', 'Api\PmsbudgetyearController@listgrid');
Route::post('budget_year/insertgrid', 'Api\PmsbudgetyearController@insertgrid');
Route::post('budget_year/updategrid', 'Api\PmsbudgetyearController@updategrid');
Route::post('budget_year/deletegrid', 'Api\PmsbudgetyearController@deletegrid');
Route::post('budget_year/search', 'PmsbudgetyearController@search');
Route::post('budget_year/getform', 'PmsbudgetyearController@getForm');
Route::post('budget_year/getlistform', 'PmsbudgetyearController@getListForm');

    Route::resource('contractor_type', 'PmscontractortypeController');
Route::post('contractor_type/listgrid', 'Api\PmscontractortypeController@listgrid');
Route::post('contractor_type/insertgrid', 'Api\PmscontractortypeController@insertgrid');
Route::post('contractor_type/updategrid', 'Api\PmscontractortypeController@updategrid');
Route::post('contractor_type/deletegrid', 'Api\PmscontractortypeController@deletegrid');
Route::post('contractor_type/search', 'PmscontractortypeController@search');
Route::post('contractor_type/getform', 'PmscontractortypeController@getForm');
Route::post('contractor_type/getlistform', 'PmscontractortypeController@getListForm');

Route::resource('contract_termination_reason', 'PmscontractterminationreasonController');
Route::post('contract_termination_reason/listgrid', 'Api\PmscontractterminationreasonController@listgrid');
Route::post('contract_termination_reason/insertgrid', 'Api\PmscontractterminationreasonController@insertgrid');
Route::post('contract_termination_reason/updategrid', 'Api\PmscontractterminationreasonController@updategrid');
Route::post('contract_termination_reason/deletegrid', 'Api\PmscontractterminationreasonController@deletegrid');
Route::post('contract_termination_reason/search', 'PmscontractterminationreasonController@search');
Route::post('contract_termination_reason/getform', 'PmscontractterminationreasonController@getForm');
Route::post('contract_termination_reason/getlistform', 'PmscontractterminationreasonController@getListForm');

Route::resource('document_type', 'PmsdocumenttypeController');
Route::post('document_type/listgrid', 'Api\PmsdocumenttypeController@listgrid');
Route::post('document_type/insertgrid', 'Api\PmsdocumenttypeController@insertgrid');
Route::post('document_type/updategrid', 'Api\PmsdocumenttypeController@updategrid');
Route::post('document_type/deletegrid', 'Api\PmsdocumenttypeController@deletegrid');
Route::post('document_type/search', 'PmsdocumenttypeController@search');
Route::post('document_type/getform', 'PmsdocumenttypeController@getForm');
Route::post('document_type/getlistform', 'PmsdocumenttypeController@getListForm');

Route::resource('project_category', 'PmsprojectcategoryController');
Route::post('project_category/listgrid', 'Api\PmsprojectcategoryController@listgrid');
Route::post('project_category/insertgrid', 'Api\PmsprojectcategoryController@insertgrid');
Route::post('project_category/updategrid', 'Api\PmsprojectcategoryController@updategrid');
Route::post('project_category/deletegrid', 'Api\PmsprojectcategoryController@deletegrid');
Route::post('project_category/search', 'PmsprojectcategoryController@search');
Route::post('project_category/getform', 'PmsprojectcategoryController@getForm');
Route::post('project_category/getlistform', 'PmsprojectcategoryController@getListForm');

Route::resource('project_contractor', 'PmsprojectcontractorController');
Route::post('project_contractor/listgrid', 'Api\PmsprojectcontractorController@listgrid');
Route::post('project_contractor/insertgrid', 'Api\PmsprojectcontractorController@insertgrid');
Route::post('project_contractor/updategrid', 'Api\PmsprojectcontractorController@updategrid');
Route::post('project_contractor/deletegrid', 'Api\PmsprojectcontractorController@deletegrid');
Route::post('project_contractor/search', 'PmsprojectcontractorController@search');
Route::post('project_contractor/getform', 'PmsprojectcontractorController@getForm');
Route::post('project_contractor/getlistform', 'PmsprojectcontractorController@getListForm');

Route::resource('project', 'Api\PmsprojectController');
Route::post('project/listgrid', 'Api\PmsprojectController@listgrid');
Route::post('project/insertgrid', 'Api\PmsprojectController@insertgrid');
Route::post('project/updategrid', 'Api\PmsprojectController@updategrid');
Route::post('project/deletegrid', 'Api\PmsprojectController@deletegrid');
Route::post('project/search', 'PmsprojectController@search');
Route::post('project/getform', 'PmsprojectController@getForm');
Route::post('project/getlistform', 'PmsprojectController@getListForm');

Route::resource('project_document', 'PmsprojectdocumentController');
Route::post('project_document/listgrid', 'Api\PmsprojectdocumentController@listgrid');
Route::post('project_document/insertgrid', 'Api\PmsprojectdocumentController@insertgrid');
Route::post('project_document/updategrid', 'Api\PmsprojectdocumentController@updategrid');
Route::post('project_document/deletegrid', 'Api\PmsprojectdocumentController@deletegrid');
Route::post('project_document/search', 'PmsprojectdocumentController@search');
Route::post('project_document/getform', 'PmsprojectdocumentController@getForm');
Route::post('project_document/getlistform', 'PmsprojectdocumentController@getListForm');

Route::resource('project_payment', 'PmsprojectpaymentController');
Route::post('project_payment/listgrid', 'Api\PmsprojectpaymentController@listgrid');
Route::post('project_payment/insertgrid', 'Api\PmsprojectpaymentController@insertgrid');
Route::post('project_payment/updategrid', 'Api\PmsprojectpaymentController@updategrid');
Route::post('project_payment/deletegrid', 'Api\PmsprojectpaymentController@deletegrid');
Route::post('project_payment/search', 'PmsprojectpaymentController@search');
Route::post('project_payment/getform', 'PmsprojectpaymentController@getForm');
Route::post('project_payment/getlistform', 'PmsprojectpaymentController@getListForm');
Route::resource('project_stakeholder', 'PmsprojectstakeholderController');
Route::post('project_stakeholder/listgrid', 'Api\PmsprojectstakeholderController@listgrid');
Route::post('project_stakeholder/insertgrid', 'Api\PmsprojectstakeholderController@insertgrid');
Route::post('project_stakeholder/updategrid', 'Api\PmsprojectstakeholderController@updategrid');
Route::post('project_stakeholder/deletegrid', 'Api\PmsprojectstakeholderController@deletegrid');
Route::post('project_stakeholder/search', 'PmsprojectstakeholderController@search');
Route::post('project_stakeholder/getform', 'PmsprojectstakeholderController@getForm');
Route::post('project_stakeholder/getlistform', 'PmsprojectstakeholderController@getListForm');
//Route::resource('project_status', 'PmsprojectstatusController');
Route::post('project_status/insertgrid', 'Api\PmsprojectstatusController@insertgrid');
Route::post('project_status/updategrid', 'Api\PmsprojectstatusController@updategrid');
Route::post('project_status/deletegrid', 'Api\PmsprojectstatusController@deletegrid');
Route::post('project_status/search', 'PmsprojectstatusController@search');
Route::post('project_status/getform', 'PmsprojectstatusController@getForm');
Route::post('project_status/getlistform', 'PmsprojectstatusController@getListForm');
Route::resource('sector_information', 'PmssectorinformationController');
Route::post('sector_information/listgrid', 'Api\PmssectorinformationController@listgrid');
Route::post('sector_information/insertgrid', 'Api\PmssectorinformationController@insertgrid');
Route::post('sector_information/updategrid', 'Api\PmssectorinformationController@updategrid');
Route::post('sector_information/deletegrid', 'Api\PmssectorinformationController@deletegrid');
Route::post('sector_information/search', 'PmssectorinformationController@search');
Route::post('sector_information/getform', 'PmssectorinformationController@getForm');
Route::post('sector_information/getlistform', 'PmssectorinformationController@getListForm');
Route::resource('stakeholder_type', 'PmsstakeholdertypeController');
Route::post('stakeholder_type/listgrid', 'Api\PmsstakeholdertypeController@listgrid');
Route::post('stakeholder_type/insertgrid', 'Api\PmsstakeholdertypeController@insertgrid');
Route::post('stakeholder_type/updategrid', 'Api\PmsstakeholdertypeController@updategrid');
Route::post('stakeholder_type/deletegrid', 'Api\PmsstakeholdertypeController@deletegrid');
Route::post('stakeholder_type/search', 'PmsstakeholdertypeController@search');
Route::post('stakeholder_type/getform', 'PmsstakeholdertypeController@getForm');
Route::post('stakeholder_type/getlistform', 'PmsstakeholdertypeController@getListForm');

Route::resource('sector_category', 'PrjsectorcategoryController');
Route::post('sector_category/listgrid', 'Api\PrjsectorcategoryController@listgrid');
Route::post('sector_category/insertgrid', 'Api\PrjsectorcategoryController@insertgrid');
Route::post('sector_category/updategrid', 'Api\PrjsectorcategoryController@updategrid');
Route::post('sector_category/deletegrid', 'Api\PrjsectorcategoryController@deletegrid');
Route::post('sector_category/search', 'PrjsectorcategoryController@search');
Route::post('sector_category/getform', 'PrjsectorcategoryController@getForm');
Route::post('sector_category/getlistform', 'PrjsectorcategoryController@getListForm');

Route::resource('access_log', 'TblaccesslogController');
Route::post('access_log/listgrid', 'Api\TblaccesslogController@listgrid');
Route::post('access_log/insertgrid', 'Api\TblaccesslogController@insertgrid');
Route::post('access_log/updategrid', 'Api\TblaccesslogController@updategrid');
Route::post('access_log/deletegrid', 'Api\TblaccesslogController@deletegrid');
Route::post('access_log/search', 'TblaccesslogController@search');
Route::post('access_log/getform', 'TblaccesslogController@getForm');
Route::post('access_log/getlistform', 'TblaccesslogController@getListForm');
Route::resource('pages', 'TblpagesController');
Route::post('pages/listgrid', 'Api\TblpagesController@listgrid');
Route::post('pages/insertgrid', 'Api\TblpagesController@insertgrid');
Route::post('pages/updategrid', 'Api\TblpagesController@updategrid');
Route::post('pages/deletegrid', 'Api\TblpagesController@deletegrid');
Route::post('pages/search', 'TblpagesController@search');
Route::post('pages/getform', 'TblpagesController@getForm');
Route::post('pages/getlistform', 'TblpagesController@getListForm');
Route::resource('permission', 'TblpermissionController');
Route::post('permission/listgrid', 'Api\TblpermissionController@listgrid');
Route::post('permission/insertgrid', 'Api\TblpermissionController@insertgrid');
Route::post('permission/updategrid', 'Api\TblpermissionController@updategrid');
Route::post('permission/deletegrid', 'Api\TblpermissionController@deletegrid');
Route::post('permission/search', 'TblpermissionController@search');
Route::post('permission/getform', 'TblpermissionController@getForm');
Route::post('permission/getlistform', 'TblpermissionController@getListForm');
Route::resource('roles', 'TblrolesController');

Route::post('roles/insertgrid', 'Api\TblrolesController@insertgrid');
Route::post('roles/deletegrid', 'Api\TblrolesController@deletegrid');
Route::post('roles/search', 'TblrolesController@search');
Route::post('roles/getform', 'TblrolesController@getForm');
Route::post('roles/getlistform', 'TblrolesController@getListForm');
Route::resource('user_role', 'TbluserroleController');
Route::post('user_role/listgrid', 'Api\TbluserroleController@listgrid');
Route::post('user_role/insertgrid', 'Api\TbluserroleController@insertgrid');
Route::post('user_role/updategrid', 'Api\TbluserroleController@updategrid');
Route::post('user_role/deletegrid', 'Api\TbluserroleController@deletegrid');
Route::post('user_role/search', 'TbluserroleController@search');
Route::post('user_role/getform', 'TbluserroleController@getForm');
Route::post('user_role/getlistform', 'TbluserroleController@getListForm');
 Route::resource('users', 'TblusersController');
//Route::post('users/listgrid', 'Api\TblusersController@listgrid');
Route::post('users/insertgrid', 'Api\TblusersController@insertgrid');
Route::post('users/updategrid', 'Api\TblusersController@updategrid');
Route::post('users/deletegrid', 'Api\TblusersController@deletegrid');
Route::post('users/search', 'TblusersController@search');
Route::post('users/getform', 'TblusersController@getForm');
Route::post('users/getlistform', 'TblusersController@getListForm');
Route::post('addressbyparent', 'Api\GenaddressstructureController@addressByParent');
Route::resource('address_structure', 'GenaddressstructureController');
//Route::post('address_structure/listgrid', 'Api\GenaddressstructureController@listgrid');
Route::post('address_structure/insertgrid', 'Api\GenaddressstructureController@insertgrid');
Route::post('address_structure/updategrid', 'Api\GenaddressstructureController@updategrid');
Route::post('address_structure/deletegrid', 'Api\GenaddressstructureController@deletegrid');
Route::post('address_structure/search', 'GenaddressstructureController@search');
Route::post('address_structure/getform', 'GenaddressstructureController@getForm');
Route::post('address_structure/getlistform', 'GenaddressstructureController@getListForm');

Route::post('notification', 'Api\GennotificationController@listgrid');
Route::post('updatenotification', 'Api\GennotificationController@updategrid');
Route::post('login', 'AuthController@login');
Route::post('user/change_password', 'AuthController@changePassword');
Route::post('project_status/listgrid', 'Api\PmsprojectstatusController@listgrid');

Route::post('users/listgrid', 'Api\TblusersController@listgrid');
//NEWLY ADDED
 Route::resource('expenditure_code', 'PmsexpenditurecodeController');
    Route::post('expenditure_code/listgrid', 'Api\PmsexpenditurecodeController@listgrid');
    Route::post('expenditure_code/insertgrid', 'Api\PmsexpenditurecodeController@insertgrid');
    Route::post('expenditure_code/updategrid', 'Api\PmsexpenditurecodeController@updategrid');
    Route::post('expenditure_code/deletegrid', 'Api\PmsexpenditurecodeController@deletegrid');
    Route::post('expenditure_code/search', 'PmsexpenditurecodeController@search');
    Route::post('expenditure_code/getform', 'PmsexpenditurecodeController@getForm');
    Route::post('expenditure_code/getlistform', 'PmsexpenditurecodeController@getListForm');
    Route::resource('project_budget_expenditure', 'PmsprojectbudgetexpenditureController');
    Route::post('project_budget_expenditure/listgrid', 'Api\PmsprojectbudgetexpenditureController@listgrid');
    Route::post('project_budget_expenditure/insertgrid', 'Api\PmsprojectbudgetexpenditureController@insertgrid');
    Route::post('project_budget_expenditure/updategrid', 'Api\PmsprojectbudgetexpenditureController@updategrid');
    Route::post('project_budget_expenditure/deletegrid', 'Api\PmsprojectbudgetexpenditureController@deletegrid');
    Route::post('project_budget_expenditure/search', 'PmsprojectbudgetexpenditureController@search');
    Route::post('project_budget_expenditure/getform', 'PmsprojectbudgetexpenditureController@getForm');
    Route::post('project_budget_expenditure/getlistform', 'PmsprojectbudgetexpenditureController@getListForm');
    Route::resource('project_budget_source', 'PmsprojectbudgetsourceController');
    Route::post('project_budget_source/listgrid', 'Api\PmsprojectbudgetsourceController@listgrid');
    Route::post('project_budget_source/insertgrid', 'Api\PmsprojectbudgetsourceController@insertgrid');
    Route::post('project_budget_source/updategrid', 'Api\PmsprojectbudgetsourceController@updategrid');
    Route::post('project_budget_source/deletegrid', 'Api\PmsprojectbudgetsourceController@deletegrid');
    Route::post('project_budget_source/search', 'PmsprojectbudgetsourceController@search');
    Route::post('project_budget_source/getform', 'PmsprojectbudgetsourceController@getForm');
    Route::post('project_budget_source/getlistform', 'PmsprojectbudgetsourceController@getListForm');
     Route::resource('project_employee', 'PmsprojectemployeeController');
    Route::post('project_employee/listgrid', 'Api\PmsprojectemployeeController@listgrid');
    Route::post('project_employee/insertgrid', 'Api\PmsprojectemployeeController@insertgrid');
    Route::post('project_employee/updategrid', 'Api\PmsprojectemployeeController@updategrid');
    Route::post('project_employee/deletegrid', 'Api\PmsprojectemployeeController@deletegrid');
    Route::post('project_employee/search', 'PmsprojectemployeeController@search');
    Route::post('project_employee/getform', 'PmsprojectemployeeController@getForm');
    Route::post('project_employee/getlistform', 'PmsprojectemployeeController@getListForm');
    Route::resource('project_handover', 'PmsprojecthandoverController');
    Route::post('project_handover/listgrid', 'Api\PmsprojecthandoverController@listgrid');
    Route::post('project_handover/insertgrid', 'Api\PmsprojecthandoverController@insertgrid');
    Route::post('project_handover/updategrid', 'Api\PmsprojecthandoverController@updategrid');
    Route::post('project_handover/deletegrid', 'Api\PmsprojecthandoverController@deletegrid');
    Route::post('project_handover/search', 'PmsprojecthandoverController@search');
    Route::post('project_handover/getform', 'PmsprojecthandoverController@getForm');
    Route::post('project_handover/getlistform', 'PmsprojecthandoverController@getListForm');
    Route::resource('project_performance', 'PmsprojectperformanceController');
    Route::post('project_performance/listgrid', 'Api\PmsprojectperformanceController@listgrid');
    Route::post('project_performance/insertgrid', 'Api\PmsprojectperformanceController@insertgrid');
    Route::post('project_performance/updategrid', 'Api\PmsprojectperformanceController@updategrid');
    Route::post('project_performance/deletegrid', 'Api\PmsprojectperformanceController@deletegrid');
    Route::post('project_performance/search', 'PmsprojectperformanceController@search');
    Route::post('project_performance/getform', 'PmsprojectperformanceController@getForm');
    Route::post('project_performance/getlistform', 'PmsprojectperformanceController@getListForm');
    Route::resource('project_plan', 'PmsprojectplanController');
    Route::post('project_plan/listgrid', 'Api\PmsprojectplanController@listgrid');
    Route::post('project_plan/insertgrid', 'Api\PmsprojectplanController@insertgrid');
    Route::post('project_plan/updategrid', 'Api\PmsprojectplanController@updategrid');
    Route::post('project_plan/deletegrid', 'Api\PmsprojectplanController@deletegrid');
    Route::post('project_plan/search', 'PmsprojectplanController@search');
    Route::post('project_plan/getform', 'PmsprojectplanController@getForm');
    Route::post('project_plan/getlistform', 'PmsprojectplanController@getListForm');
    Route::resource('project_supplimentary', 'PmsprojectsupplimentaryController');
    Route::post('project_supplimentary/listgrid', 'Api\PmsprojectsupplimentaryController@listgrid');
    Route::post('project_supplimentary/insertgrid', 'Api\PmsprojectsupplimentaryController@insertgrid');
    Route::post('project_supplimentary/updategrid', 'Api\PmsprojectsupplimentaryController@updategrid');
    Route::post('project_supplimentary/deletegrid', 'Api\PmsprojectsupplimentaryController@deletegrid');
    Route::post('project_supplimentary/search', 'PmsprojectsupplimentaryController@search');
    Route::post('project_supplimentary/getform', 'PmsprojectsupplimentaryController@getForm');
    Route::post('project_supplimentary/getlistform', 'PmsprojectsupplimentaryController@getListForm');
      Route::resource('project_variation', 'PmsprojectvariationController');
    Route::post('project_variation/listgrid', 'Api\PmsprojectvariationController@listgrid');
    Route::post('project_variation/insertgrid', 'Api\PmsprojectvariationController@insertgrid');
    Route::post('project_variation/updategrid', 'Api\PmsprojectvariationController@updategrid');
    Route::post('project_variation/deletegrid', 'Api\PmsprojectvariationController@deletegrid');
    Route::post('project_variation/search', 'PmsprojectvariationController@search');
    Route::post('project_variation/getform', 'PmsprojectvariationController@getForm');
    Route::post('project_variation/getlistform', 'PmsprojectvariationController@getListForm');

    Route::post('project_budget_plan/listgrid', 'Api\PmsprojectbudgetplanController@listgrid');
    Route::post('project_budget_plan/insertgrid', 'Api\PmsprojectbudgetplanController@insertgrid');
    Route::post('project_budget_plan/updategrid', 'Api\PmsprojectbudgetplanController@updategrid');
    Route::post('project_budget_plan/deletegrid', 'Api\PmsprojectbudgetplanController@deletegrid');
    Route::post('project_budget_plan/search', 'PmsprojectbudgetplanController@search');
    Route::post('project_budget_plan/getform', 'PmsprojectbudgetplanController@getForm');
    Route::post('project_budget_plan/getlistform', 'PmsprojectbudgetplanController@getListForm');

    Route::post('budget_month/listgrid', 'Api\PmsbudgetmonthController@listgrid');
    Route::post('budget_month/insertgrid', 'Api\PmsbudgetmonthController@insertgrid');
    Route::post('budget_month/updategrid', 'Api\PmsbudgetmonthController@updategrid');
    Route::post('budget_month/deletegrid', 'Api\PmsbudgetmonthController@deletegrid');
    Route::post('budget_month/search', 'PmsbudgetmonthController@search');
    Route::post('budget_month/getform', 'PmsbudgetmonthController@getForm');
    Route::post('budget_month/getlistform', 'PmsbudgetmonthController@getListForm');
    //END NEWLY ADDED
   
   // Route::post('department/listgrid', [\Api\GendepartmentController::class, 'listgrid'])->middleware('apilogin');
Route::group(['middleware' => [\App\Http\Middleware\JwtMiddleware::class], 'except' => ['api/login', 'api/register']], function () {
 Route::post('dashboard_builder', 'Api\GendashboardbuilderController@listgrid');
Route::post('menus', 'Api\GenmenubuilderController@listgrid');
Route::post('address_structure/listgrid', 'Api\GenaddressstructureController@listgrid');
//Route::resource('roles', 'TblrolesController');
       //Route::post('menus', 'Api\GenmenubuilderController@listgrid');
       
       Route::post('roles/updategrid', 'Api\TblrolesController@updategrid');
       Route::post('roles/listgrid', 'Api\TblrolesController@listgrid');
    Route::post('department/listgrid', 'Api\GendepartmentController@listgrid');
    Route::post('department/insertgrid', 'Api\GendepartmentController@insertgrid');
    Route::post('department/updategrid', 'Api\GendepartmentController@updategrid');
    Route::post('department/deletegrid', 'Api\GendepartmentController@deletegrid');
});