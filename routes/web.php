<?php
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('markasread', function(){
//auth()->user()->unreadNotifications()->markAsRead();
	auth()->user()->unreadNotifications()->update(['read_at' => now()]);
	return redirect()->back();
});
Route::get('', 'DashboardController@index');
Auth::routes();
Route::get('home', 'DashboardController@index');
Route::resource('admin/activitylogs', 'Admin\ActivityLogsController')->only([
	'index', 'show', 'destroy'
]);
Route::post('switchLanguage', 'MyController@switchLanguage');
Route::resource('pages', 'TblpagesController');
Route::resource('roles', 'TblrolesController');
Route::resource('users', 'TblusersController');
Route::get('loginas/{id}', 'TblusersController@loginAs');
Route::post('loginuser', 'Admin\\LoginController@authenticate');
//Route::resource('purpose', 'BldpurposeController');
Route::resource('company', 'GencompanyController');
Route::resource('notification', 'GennotificationController');
Route::resource('store', 'GenstoreController');
Route::resource('item', 'InvitemController');
Route::resource('lookup', 'LookupController');
Route::get('permission/listgrid', 'TblpermissionController@listgrid');
Route::post('permission/insertgrid', 'TblpermissionController@insertgrid');
Route::post('permission/updategrid', 'TblpermissionController@updategrid');
Route::post('permission/deletegrid', 'TblpermissionController@deletegrid');
Route::get('language_setting/listgrid', 'LanguageController@listgrid');
Route::post('language_setting/insertgrid', 'LanguageController@insertgrid');
Route::post('language_setting/updategrid', 'LanguageController@updategrid');
Route::post('language_setting/deletegrid', 'LanguageController@deletegrid');
Route::get('property_category/listgrid', 'GenpropertycategoryController@listgrid');
Route::post('property_category/insertgrid', 'GenpropertycategoryController@insertgrid');
Route::post('property_category/updategrid', 'GenpropertycategoryController@updategrid');
Route::post('property_category/deletegrid', 'GenpropertycategoryController@deletegrid');
Route::get('property_category', 'GenpropertycategoryController@create');
Route::get('property_category/show', 'GenpropertycategoryController@show');
Route::post('property_category/move', 'GenpropertycategoryController@moveCategory');
Route::post('property_category/subcategory', 'GenpropertycategoryController@getSubCategory');
Route::post('get_item', 'GenpropertycategoryController@getItemByCategory');
Route::get('department/listgrid', 'HrmdepartmentController@listgrid');
Route::post('department/insertgrid', 'HrmdepartmentController@insertgrid');
Route::post('department/updategrid', 'HrmdepartmentController@updategrid');
Route::post('department/deletegrid', 'HrmdepartmentController@deletegrid');
Route::get('department', 'HrmdepartmentController@create');
Route::get('department/show', 'HrmdepartmentController@show');
Route::post('department/move', 'HrmdepartmentController@moveDepartment');
Route::get('store_location/listgrid', 'GenstorelocationController@listgrid');
Route::post('store_location/insertgrid', 'GenstorelocationController@insertgrid');
Route::post('store_location/updategrid', 'GenstorelocationController@updategrid');
Route::post('store_location/deletegrid', 'GenstorelocationController@deletegrid');
//Route::get('dashboard', 'DashboardController@index');
Route::post('searchobj', 'SearchController@index');
Route::get('changePasswordScreen', 'TblusersController@changePasswordScreen');
Route::post('changePassword', 'TblusersController@changePassword');
Route::get('resetPasswordScreen/{id}', 'TblusersController@resetPasswordScreen');
Route::post('resetPassword', 'TblusersController@resetPassword');
//Route::resource('property_file', 'GenpropertyfileController');
Route::get('attachfile/{type}/{id}', 'GenpropertyfileController@attachFile');
Route::post('uploadfile', 'GenpropertyfileController@uploadfile');
Route::post('property_file/listgrid', 'GenpropertyfileController@listgrid');
Route::post('property_file/insertgrid', 'GenpropertyfileController@insertgrid');
Route::post('property_file/updategrid', 'GenpropertyfileController@updategrid');
Route::post('property_file/deletegrid', 'GenpropertyfileController@deletegrid');
Route::post('property_file/getform', 'GenpropertyfileController@getForm');
Route::post('property_file/getlistform', 'GenpropertyfileController@getListForm');
Route::resource('statisticalreport', 'StatisticalReportController');
Route::post('statistics/getdata', 'StatisticalReportController@listgrid');
Route::post('statistics/chartdata', 'StatisticalReportController@chartData');
Route::resource('access_log', 'TblaccesslogController');
Route::post('access_log/listgrid', 'TblaccesslogController@listgrid');
Route::post('note/listgrid', 'TblnoteController@listgrid');
Route::post('note/insertgrid', 'TblnoteController@insertgrid');
Route::post('note/updategrid', 'TblnoteController@updategrid');
Route::post('note/deletegrid', 'TblnoteController@deletegrid');
Route::post('note/getform', 'TblnoteController@getForm');
Route::post('note/getnotelist', 'TblnoteController@getNoteList');
Route::resource('allowed_machine', 'TblallowedmachineController');
Route::resource('databasebackup', 'TbldatabaseController');
Route::resource('regional_government', 'GenregionalgovernmentController');
Route::resource('company_user', 'GencompanyuserController');
Route::post('activatecompany/{id}', 'GencompanyController@activateCompany');
Route::post('documents/listgrid', 'GendocumentsController@listgrid');
Route::post('documents/insertgrid', 'GendocumentsController@insertgrid');
Route::post('documents/updategrid', 'GendocumentsController@updategrid');
Route::post('documents/deletegrid', 'GendocumentsController@deletegrid');
Route::post('documents/getform', 'GendocumentsController@getForm');
Route::resource('viewdocument', 'DocumentViewer');
Route::post('viewdocumentajax', 'DocumentViewer@getForm');
Route::post('register', 'Auth\\RegisterController@create');
Route::post('registeruser', 'Auth\\RegisterController@store');
Route::get('calculatemonthly', 'GentransactionController@calculateTransaction');
Route::get('viewvat/{id}', 'GentransactionController@show');
Route::get('vatdeclaration', 'GentransactionController@index');
Route::post('vatdeclaration/show', 'GentransactionController@displayVat');
Route::get('documents/sales', 'GendocumentsController@sales');
Route::get('documents/purchases', 'GendocumentsController@index');
Route::resource('document_type', 'GendocumenttypeController');
Route::post('paymentform', 'PaymentFormController@displayForm');
Route::get('withhold', 'WithholdingController@index');
Route::post('withhold/show', 'WithholdingController@displayWithhold');
Route::resource('employee_tax', 'GenemployeetaxController');
Route::get('editvat/{id}', 'GentransactionController@edit');
//NEW
Route::resource('address', 'HrmaddressController');

Route::post('address/populateWoreda', 'HrmaddressController@populateWoreda');
Route::post('address/listgrid', 'HrmaddressController@listgrid');
Route::post('address/display', 'HrmaddressController@displayReports');
Route::post('address/insertgrid', 'HrmaddressController@insertgrid');
Route::post('address/updategrid', 'HrmaddressController@updategrid');
Route::post('address/deletegrid', 'HrmaddressController@deletegrid');
Route::post('address/search', 'HrmaddressController@search');
Route::post('address/getform', 'HrmaddressController@getForm');
Route::post('address/getlistform', 'HrmaddressController@getListForm');
Route::post('address/moveaddress', 'HrmaddressController@moveAddress');

Route::get('company_account/listgrid', 'GencompanyaccountController@listgrid');
Route::post('company_account/insertgrid', 'GencompanyaccountController@insertgrid');
Route::post('company_account/updategrid', 'GencompanyaccountController@updategrid');
Route::post('company_account/deletegrid', 'GencompanyaccountController@deletegrid');
Route::resource('adminlookup', 'AdminLookupController');
Route::get('employeetax', 'EmployeeTaxController@index');
Route::post('employeetax/show', 'EmployeeTaxController@displayEmployeeTax');
Route::get('errorpage', 'ErrorPageController@index');
Route::get('settings', 'GensettingsController@index');
Route::post('settings/listgrid', 'GensettingsController@listgrid');
Route::post('settings/insertgrid', 'GensettingsController@insertgrid');
Route::post('settings/updategrid', 'GensettingsController@updategrid');
Route::post('settings/deletegrid', 'GensettingsController@deletegrid');
Route::resource('subuser', 'SubUsersController');
Route::post('letterform', 'LetterController@displayForm');
Route::resource('help', 'HelpController');
Route::resource('water_scheme_information', 'Waterscheme\WaswaterschemeinformationController');
Route::resource('water_scheme_operation', 'Waterscheme\WaswaterschemeoperationController');
Route::post('waterschemelist', 'Waterscheme\WaswaterschemeinformationController@listgrid');
Route::resource('water_scheme_info_encoder', 'Waterscheme\WaswaterschemencoderinfoController');
Route::resource('water_scheme_operation_encoder', 'Waterscheme\WaswaterschemeoperationencoderController');
Route::post('water_scheme_user/listgrid', 'Waterscheme\WaswaterschemeuserController@listgrid');
Route::post('water_scheme_user/insertgrid', 'Waterscheme\WaswaterschemeuserController@insertgrid');
Route::post('water_scheme_user/updategrid', 'Waterscheme\WaswaterschemeuserController@updategrid');
Route::post('water_scheme_user/deletegrid', 'Waterscheme\WaswaterschemeuserController@deletegrid');
/*START PMS*/
Route::resource('project', 'Project\PmsprojectController');

Route::post('project_followup/listgrid', 'Project\PmsprojectfollowupController@listgrid');
Route::post('project_followup/insertgrid', 'Project\PmsprojectfollowupController@insertgrid');
Route::post('project_followup/updategrid', 'Project\PmsprojectfollowupController@updategrid');
Route::post('project_followup/deletegrid', 'Project\PmsprojectfollowupController@deletegrid');
Route::post('project_followup/getform', 'Project\PmsprojectfollowupController@getForm');

Route::post('budget_expenditure/listgrid', 'Project\PmsbudgetexpenditureController@listgrid');
Route::post('budget_expenditure/insertgrid', 'Project\PmsbudgetexpenditureController@insertgrid');
Route::post('budget_expenditure/updategrid', 'Project\PmsbudgetexpenditureController@updategrid');
Route::post('budget_expenditure/deletegrid', 'Project\PmsbudgetexpenditureController@deletegrid');
Route::post('budget_expenditure/search', 'Project\PmsbudgetexpenditureController@search');
Route::post('budget_expenditure/getform', 'Project\PmsbudgetexpenditureController@getForm');
Route::get('budget_expenditure', 'Project\PmsbudgetexpenditureController@index');

Route::post('projectlist', 'Project\PmsprojectController@listgrid');
Route::post('project_progress/listgrid', 'Project\PmsprojectprogressController@listgrid');
Route::post('project_progress/insertgrid', 'Project\PmsprojectprogressController@insertgrid');
Route::post('project_progress/updategrid', 'Project\PmsprojectprogressController@updategrid');
Route::post('project_progress/deletegrid', 'Project\PmsprojectprogressController@deletegrid');
Route::post('project_progress/search', 'Project\PmsprojectprogressController@search');
Route::post('project_progress/getform', 'Project\PmsprojectprogressController@getForm');
Route::get('project_progress', 'Project\PmsprojectprogressController@index');
//START NEW PURCHASE
Route::get('projectprocurement', 'PmsprojectController@procurementList');
Route::resource('project', 'PmsprojectController');
Route::post('project/listgrid', 'PmsprojectController@listgrid');
Route::post('project/insertgrid', 'PmsprojectController@insertgrid');
Route::post('project/updategrid', 'PmsprojectController@updategrid');
Route::post('project/deletegrid', 'PmsprojectController@deletegrid');
Route::post('project/search', 'PmsprojectController@search');
Route::post('project/getform', 'PmsprojectController@getForm');
Route::post('project/getlistform', 'PmsprojectController@getListForm');
Route::post('project/listautocomplete', 'PmsprojectController@listAutoComplete');
Route::post('project/updatemap', 'PmsprojectController@updateMap');
Route::post('project/listmap', 'PmsprojectController@listMap');

Route::resource('monitoring', 'PmsmonitoringController');
Route::post('monitoring/listgrid', 'PmsmonitoringController@listgrid');
Route::post('monitoring/insertgrid', 'PmsmonitoringController@insertgrid');
Route::post('monitoring/updategrid', 'PmsmonitoringController@updategrid');
Route::post('monitoring/deletegrid', 'PmsmonitoringController@deletegrid');
Route::post('monitoring/search', 'PmsmonitoringController@search');
Route::post('monitoring/getform', 'PmsmonitoringController@getForm');
Route::post('monitoring/getlistform', 'PmsmonitoringController@getListForm');

Route::resource('key_challenges', 'PmskeychallengesController');
Route::post('key_challenges/listgrid', 'PmskeychallengesController@listgrid');
Route::post('key_challenges/insertgrid', 'PmskeychallengesController@insertgrid');
Route::post('key_challenges/updategrid', 'PmskeychallengesController@updategrid');
Route::post('key_challenges/deletegrid', 'PmskeychallengesController@deletegrid');
Route::post('key_challenges/search', 'PmskeychallengesController@search');
Route::post('key_challenges/getform', 'PmskeychallengesController@getForm');
Route::post('key_challenges/getlistform', 'PmskeychallengesController@getListForm');

Route::resource('project_performance', 'PmsprojectperformanceController');
Route::post('project_performance/listgrid', 'PmsprojectperformanceController@listgrid');
Route::post('project_performance/insertgrid', 'PmsprojectperformanceController@insertgrid');
Route::post('project_performance/updategrid', 'PmsprojectperformanceController@updategrid');
Route::post('project_performance/deletegrid', 'PmsprojectperformanceController@deletegrid');
Route::post('project_performance/search', 'PmsprojectperformanceController@search');
Route::post('project_performance/getform', 'PmsprojectperformanceController@getForm');
Route::post('project_performance/getlistform', 'PmsprojectperformanceController@getListForm');

Route::resource('file', 'DmsfileController');
Route::post('file/listgrid', 'DmsfileController@listgrid');
Route::post('file/insertgrid', 'DmsfileController@insertgrid');
Route::post('file/updategrid', 'DmsfileController@updategrid');
Route::post('file/deletegrid', 'DmsfileController@deletegrid');
Route::post('file/search', 'DmsfileController@search');
Route::post('file/getform', 'DmsfileController@getForm');
Route::post('file/getlistform', 'DmsfileController@getListForm');

Route::resource('bureau', 'VdcbureauController');
Route::post('bureau/listgrid', 'VdcbureauController@listgrid');
Route::post('bureau/display', 'VdcbureauController@displayReports');
Route::post('bureau/insertgrid', 'VdcbureauController@insertgrid');
Route::post('bureau/updategrid', 'VdcbureauController@updategrid');
Route::post('bureau/deletegrid', 'VdcbureauController@deletegrid');
Route::post('bureau/search', 'VdcbureauController@search');
Route::post('bureau/getform', 'VdcbureauController@getForm');
Route::post('bureau/getlistform', 'VdcbureauController@getListForm');

Route::get('map', 'GenmapController@index');
Route::post('map/listgrid', 'GenmapController@listgrid');
Route::post('map/display', 'GenmapController@displayReports');
Route::post('map/insertgrid', 'GenmapController@insertgrid');
Route::post('map/updategrid', 'GenmapController@updategrid');
Route::post('map/deletegrid', 'GenmapController@deletegrid');
Route::post('map/search', 'GenmapController@search');
Route::post('map/getform', 'GenmapController@getForm');
Route::post('map/getlistform', 'GenmapController@getListForm');
Route::get('map/detail', 'GenmapController@mapDetail');
//END NEW PROJECT

Route::resource('sector_category', 'PrjsectorcategoryController');
Route::post('sector_category/listgrid', 'PrjsectorcategoryController@listgrid');
Route::post('sector_category/display', 'PrjsectorcategoryController@displayReports');
Route::post('sector_category/insertgrid', 'PrjsectorcategoryController@insertgrid');
Route::post('sector_category/updategrid', 'PrjsectorcategoryController@updategrid');
Route::post('sector_category/deletegrid', 'PrjsectorcategoryController@deletegrid');
Route::post('sector_category/search', 'PrjsectorcategoryController@search');
Route::post('sector_category/getform', 'PrjsectorcategoryController@getForm');
Route::post('sector_category/getlistform', 'PrjsectorcategoryController@getListForm');
Route::post('sector_category/populateComponents', 'PrjsectorcategoryController@populateComponents');

Route::resource('project_status', 'PmsprojectstatusController');
Route::post('project_status/listgrid', 'PmsprojectstatusController@listgrid');
Route::post('project_status/display', 'PmsprojectstatusController@displayReports');
Route::post('project_status/insertgrid', 'PmsprojectstatusController@insertgrid');
Route::post('project_status/updategrid', 'PmsprojectstatusController@updategrid');
Route::post('project_status/deletegrid', 'PmsprojectstatusController@deletegrid');
Route::post('project_status/search', 'PmsprojectstatusController@search');
Route::post('project_status/getform', 'PmsprojectstatusController@getForm');
Route::post('project_status/getlistform', 'PmsprojectstatusController@getListForm');
Route::post('project_status/populateComponents', 'PmsprojectstatusController@populateComponents');

Route::resource('report_schedule', 'PmsreportscheduleController');
Route::get('report_schedule', 'PmsreportscheduleController@index');
Route::post('report_schedule/listgrid', 'PmsreportscheduleController@listgrid');
Route::post('report_schedule/display', 'PmsreportscheduleController@displayReports');
Route::post('report_schedule/insertgrid', 'PmsreportscheduleController@insertgrid');
Route::post('report_schedule/updategrid', 'PmsreportscheduleController@updategrid');
Route::post('report_schedule/deletegrid', 'PmsreportscheduleController@deletegrid');
Route::post('report_schedule/search', 'PmsreportscheduleController@search');
Route::post('report_schedule/getform', 'PmsreportscheduleController@getForm');
Route::post('report_schedule/getlistform', 'PmsreportscheduleController@getListForm');

//START PURCHASE
Route::resource('purchase_detail', 'Purchase\PurpurchasedetailController');
Route::post('purchase_detail/listgrid', 'Purchase\PurpurchasedetailController@listgrid');
Route::post('purchase_detail/insertgrid', 'Purchase\PurpurchasedetailController@insertgrid');
Route::post('purchase_detail/updategrid', 'Purchase\PurpurchasedetailController@updategrid');
Route::post('purchase_detail/deletegrid', 'Purchase\PurpurchasedetailController@deletegrid');
Route::post('purchase_detail/search', 'Purchase\PurpurchasedetailController@search');
Route::post('purchase_detail/getform', 'Purchase\PurpurchasedetailController@getForm');
Route::post('purchase_detail/getlistform', 'Purchase\PurpurchasedetailController@getListForm');

Route::resource('purchase_information', 'Purchase\PurpurchaseinformationController');
Route::post('purchase_information/listgrid', 'Purchase\PurpurchaseinformationController@listgrid');
Route::post('purchase_information/insertgrid', 'Purchase\PurpurchaseinformationController@insertgrid');
Route::post('purchase_information/updategrid', 'Purchase\PurpurchaseinformationController@updategrid');
Route::post('purchase_information/deletegrid', 'Purchase\PurpurchaseinformationController@deletegrid');
Route::post('purchase_information/search', 'Purchase\PurpurchaseinformationController@search');
Route::post('purchase_information/getform', 'Purchase\PurpurchaseinformationController@getForm');
Route::post('purchase_information/getlistform', 'Purchase\PurpurchaseinformationController@getListForm');

Route::resource('bidder', 'Purchase\PurbidderController');
Route::post('bidder/listgrid', 'Purchase\PurbidderController@listgrid');
Route::post('bidder/insertgrid', 'Purchase\PurbidderController@insertgrid');
Route::post('bidder/updategrid', 'Purchase\PurbidderController@updategrid');
Route::post('bidder/deletegrid', 'Purchase\PurbidderController@deletegrid');
Route::post('bidder/search', 'Purchase\PurbidderController@search');
Route::post('bidder/getform', 'Purchase\PurbidderController@getForm');
Route::post('bidder/getlistform', 'Purchase\PurbidderController@getListForm');

Route::resource('contract_extension', 'Purchase\PurcontractextensionController');
Route::post('contract_extension/listgrid', 'Purchase\PurcontractextensionController@listgrid');
Route::post('contract_extension/insertgrid', 'Purchase\PurcontractextensionController@insertgrid');
Route::post('contract_extension/updategrid', 'Purchase\PurcontractextensionController@updategrid');
Route::post('contract_extension/deletegrid', 'Purchase\PurcontractextensionController@deletegrid');
Route::post('contract_extension/search', 'Purchase\PurcontractextensionController@search');
Route::post('contract_extension/getform', 'Purchase\PurcontractextensionController@getForm');
Route::post('contract_extension/getlistform', 'Purchase\PurcontractextensionController@getListForm');

Route::resource('purchase_followup', 'Purchase\PurpurchasefollowupController');
Route::post('purchase_followup/listgrid', 'Purchase\PurpurchasefollowupController@listgrid');
Route::post('purchase_followup/insertgrid', 'Purchase\PurpurchasefollowupController@insertgrid');
Route::post('purchase_followup/updategrid', 'Purchase\PurpurchasefollowupController@updategrid');
Route::post('purchase_followup/deletegrid', 'Purchase\PurpurchasefollowupController@deletegrid');
Route::post('purchase_followup/search', 'Purchase\PurpurchasefollowupController@search');
Route::post('purchase_followup/getform', 'Purchase\PurpurchasefollowupController@getForm');
Route::post('purchase_followup/getlistform', 'Purchase\PurpurchasefollowupController@getListForm');
//END PURCHASE

Route::resource('monthly_activity_report_status', 'PmsmonthlyactivityreportstatusController');
Route::get('monthly_activity_report_status', 'PmsmonthlyactivityreportstatusController@index');
Route::post('monthly_activity_report_status/listgrid', 'PmsmonthlyactivityreportstatusController@listgrid');
Route::post('monthly_activity_report_status/display', 'PmsmonthlyactivityreportstatusController@displayReports');
Route::post('monthly_activity_report_status/insertgrid', 'PmsmonthlyactivityreportstatusController@insertgrid');
Route::post('monthly_activity_report_status/updategrid', 'PmsmonthlyactivityreportstatusController@updategrid');
Route::post('monthly_activity_report_status/deletegrid', 'PmsmonthlyactivityreportstatusController@deletegrid');
Route::post('monthly_activity_report_status/search', 'PmsmonthlyactivityreportstatusController@search');
Route::post('monthly_activity_report_status/getform', 'PmsmonthlyactivityreportstatusController@getForm');
Route::post('monthly_activity_report_status/getlistform', 'PmsmonthlyactivityreportstatusController@getListForm');



Route::resource('activity', 'PmsactivityController');
Route::get('activity', 'PmsactivityController@index');
Route::post('activity/listgrid', 'PmsactivityController@listgrid');
Route::post('activity/display', 'PmsactivityController@displayReports');
Route::post('activity/insertgrid', 'PmsactivityController@insertgrid');
Route::post('activity/updategrid', 'PmsactivityController@updategrid');
Route::post('activity/deletegrid', 'PmsactivityController@deletegrid');
Route::post('activity/search', 'PmsactivityController@search');
Route::post('activity/getform', 'PmsactivityController@getForm');
Route::post('activity/getlistform', 'PmsactivityController@getListForm');


Route::get('project_report', 'PmsreportController@index');
Route::post('project_report/donorreport', 'PmsreportController@donorReport');
Route::get('project_report/internalrep', 'PmsreportController@internalrep');
Route::post('project_report/internalreport', 'PmsreportController@donorReport');
Route::get('project_report/budgetrep', 'PmsreportController@budgetrep');
Route::post('project_report/budgetreport', 'PmsreportController@budgetreport');
Route::get('project_report/trackingsheetrep', 'PmsreportController@trackingsheetrep');
Route::post('project_report/trackingsheetreport', 'PmsreportController@trackingsheetreport');

Route::get('project_report/exportdonorreport/{prj_name}/{donor}/{prj_code}/{prj_location}/{prj_start_date}/{prj_end_date}/{reschedule}/{repstatus}/{fieldofficedesc}/{mainofficedesc}', 'PmsreportController@exportDonorReport');
Route::get('project_report/gantchart', 'PmsreportController@gantChart');
Route::post('project_report/gantchatgenerator', 'PmsreportController@gantChatGenerator');
Route::post('project_report/populateschedule', 'PmsreportController@populateschedule');
Route::post('project_report/populatemonthlyreport', 'PmsreportController@populatemonthlyreport');

Route::post('project_report/listgrid', 'PmsreportController@listgrid');
Route::post('project_report/display', 'PmsreportController@displayReports');
Route::post('project_report/insertgrid', 'PmsreportController@insertgrid');
Route::post('project_report/updategrid', 'PmsreportController@updategrid');
Route::post('project_report/deletegrid', 'PmsreportController@deletegrid');
Route::post('project_report/search', 'PmsreportController@search');
Route::post('project_report/getform', 'PmsreportController@getForm');
Route::post('project_report/getlistform', 'PmsreportController@getListForm');
Route::resource('employee', 'PmsemployeeController');
Route::post('employee/listgrid', 'PmsemployeeController@listgrid');
Route::post('employee/insertgrid', 'PmsemployeeController@insertgrid');
Route::post('employee/updategrid', 'PmsemployeeController@updategrid');
Route::post('employee/deletegrid', 'PmsemployeeController@deletegrid');
Route::post('employee/search', 'PmsemployeeController@search');
Route::post('employee/getform', 'PmsemployeeController@getForm');
Route::post('employee/getlistform', 'PmsemployeeController@getListForm');
Route::resource('stakeholder', 'PmsstakeholderController');
Route::post('stakeholder/listgrid', 'PmsstakeholderController@listgrid');
Route::post('stakeholder/insertgrid', 'PmsstakeholderController@insertgrid');
Route::post('stakeholder/updategrid', 'PmsstakeholderController@updategrid');
Route::post('stakeholder/deletegrid', 'PmsstakeholderController@deletegrid');
Route::post('stakeholder/search', 'PmsstakeholderController@search');
Route::post('stakeholder/getform', 'PmsstakeholderController@getForm');
Route::post('stakeholder/getlistform', 'PmsstakeholderController@getListForm');
Route::resource('contractor', 'PmscontractorController');
Route::post('contractor/listgrid', 'PmscontractorController@listgrid');
Route::post('contractor/insertgrid', 'PmscontractorController@insertgrid');
Route::post('contractor/updategrid', 'PmscontractorController@updategrid');
Route::post('contractor/deletegrid', 'PmscontractorController@deletegrid');
Route::post('contractor/search', 'PmscontractorController@search');
Route::post('contractor/getform', 'PmscontractorController@getForm');
Route::post('contractor/getlistform', 'PmscontractorController@getListForm');
Route::resource('payment_information', 'PmspaymentinformationController');
Route::post('payment_information/listgrid', 'PmspaymentinformationController@listgrid');
Route::post('payment_information/insertgrid', 'PmspaymentinformationController@insertgrid');
Route::post('payment_information/updategrid', 'PmspaymentinformationController@updategrid');
Route::post('payment_information/deletegrid', 'PmspaymentinformationController@deletegrid');
Route::post('payment_information/search', 'PmspaymentinformationController@search');
Route::post('payment_information/getform', 'PmspaymentinformationController@getForm');
Route::post('payment_information/getlistform', 'PmspaymentinformationController@getListForm');

Route::resource('budget_request', 'PmsbudgetrequestController');
Route::post('budget_request/listgrid', 'PmsbudgetrequestController@listgrid');
Route::post('budget_request/insertgrid', 'PmsbudgetrequestController@insertgrid');
Route::post('budget_request/updategrid', 'PmsbudgetrequestController@updategrid');
Route::post('budget_request/deletegrid', 'PmsbudgetrequestController@deletegrid');
Route::post('budget_request/search', 'PmsbudgetrequestController@search');
Route::post('budget_request/getform', 'PmsbudgetrequestController@getForm');
Route::post('budget_request/getlistform', 'PmsbudgetrequestController@getListForm');

Route::resource('cost_breakdown', 'PmscostbreakdownController');
Route::post('cost_breakdown/listgrid', 'PmscostbreakdownController@listgrid');
Route::post('cost_breakdown/insertgrid', 'PmscostbreakdownController@insertgrid');
Route::post('cost_breakdown/updategrid', 'PmscostbreakdownController@updategrid');
Route::post('cost_breakdown/deletegrid', 'PmscostbreakdownController@deletegrid');
Route::post('cost_breakdown/search', 'PmscostbreakdownController@search');
Route::post('cost_breakdown/getform', 'PmscostbreakdownController@getForm');
Route::post('cost_breakdown/getlistform', 'PmscostbreakdownController@getListForm');

Route::resource('languagesetting', 'LanguageController');
Route::get('language_setting/listgrid', 'LanguageController@listgrid');
Route::post('language_setting/insertgrid', 'LanguageController@insertgrid');
Route::post('language_setting/updategrid', 'LanguageController@updategrid');
Route::post('language_setting/deletegrid', 'LanguageController@deletegrid');

Route::get('gantt', function () {
    return view('project.gantt');
});
