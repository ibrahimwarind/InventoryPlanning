<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\LogManagementController;
use App\Http\Controllers\OrderManagementController;
use App\Http\Controllers\MustSellingManagementController;
use App\Http\Controllers\GeoFancingController;
use App\Http\Controllers\InventoryForecastingController;
use App\Http\Controllers\DsfTrackingController;

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


Route::get('/',[IndexController::class,'Index']);
Route::get('/test',[IndexController::class,'TestPage']);
Route::get('/index',[IndexController::class,'Index']);
Route::get('/index2',[IndexController::class,'Index2']);
Route::get('/index3',[IndexController::class,'Index3']);
Route::get("/changepassword",[AuthController::class,'ChangePassword']);
Route::post("/changepassword",[AuthController::class,'UpdatePassword']);

 
//new routes
Route::get('/formulamanagement',[MasterController::class,'FormulaManagement']);
Route::post('getformuladescription',[MasterController::class,'GetFormulaDescription']);
Route::get('/addformuladetail',[MasterController::class,'AddFormulaDetail']);
Route::get('/create_groupmapping',[MasterController::class,'CreateGroupMapping']);
Route::get('/groupmappinglist',[MasterController::class,'GroupMappingList']);
Route::get('/groupmappinglist2',[MasterController::class,'GroupMappingList2']);
Route::get('/viewgroupmapping/{id}/{type}',[MasterController::class,'ViewGroupMapping']);
Route::post('getformuladetail',[MasterController::class,'GetFormulaDetail']);
Route::post('getproductdetail',[MasterController::class,'GetProductDetail']);
Route::get('/create_groupmapping2',[MasterController::class,'CreateGroupMapping2']);
Route::get('/productpackingsize',[MasterController::class,'ProductPackingSize']);
Route::get('/productpackingsize2',[MasterController::class,'ProductPackingSize2']);
Route::get('/formulamanagementlist',[MasterController::class,'FormulaManagementList']);
Route::get('/editformulamanagement/{id}',[MasterController::class,'EditFormulaManagement']);
Route::post('removegroupproduct',[MasterController::class,'RemoveGroupProduct']);
Route::get('/editformuladetail',[MasterController::class,'EditFormulaDetail']);
Route::get('/notsetpackingsizeproduct',[MasterController::class,'NotSetPackingSizeProduct']);
Route::get('/notsetpackingsizeproduct2',[MasterController::class,'NotSetPackingSizeProduct2']);
Route::get('/checklistquestion',[MasterController::class,'CheckListQuestion']);
Route::get('/checklistquestion2',[MasterController::class,'CheckListQuestion2']);
Route::get('/checklistquestionlist',[MasterController::class,'CheckListQuestionList']);
Route::post('/questionstatuschange',[MasterController::class,'QuestionStatusChange']);
Route::post('/questionversionchange',[MasterController::class,'QuestionVersionChange']);
Route::get('/productactiveinactive',[MasterController::class,'ProductActiveInActive']);
Route::post('productstatuschange',[MasterController::class,'ProductStatusChange']);
Route::get('addproductingroup',[MasterController::class,'AddProductInGroup']);

  

Route::get('/createforecasting',[InventoryForecastingController::class,'CreateInventoryForecasting']);
Route::get('/createforecasting/{formulaid}/{formuladetailid}/{branchid}/{companyid}/{shelflife}',[InventoryForecastingController::class,'CreateInventoryForecastingthrough']);

Route::post('getformulagroup',[InventoryForecastingController::class,'GetFormulaGroup']);
Route::post('getgroupcompany',[InventoryForecastingController::class,'GetGroupCompany']);
Route::get('/createforecasting2/{formula_id}/{formula_det_id}/{group_id}/{company_id}/{shelf_life}/{forecasting_month}/{stockshowing}/{versionprevious}/{sort_by}/{questionids}/{branch_id}/{product_code}/{fid}',[InventoryForecastingController::class,'CreateInventoryForecasting2']);
Route::get('/createforecastinglist',[InventoryForecastingController::class,'CreateInventoryForecastingList']);

Route::post('getcompanyproduct',[InventoryForecastingController::class,'GetCompanyProduct']);
Route::post('getproductinventorycosting',[InventoryForecastingController::class,'GetProductInventoryCosting']);
Route::post('getformulamultiply',[InventoryForecastingController::class,'GetFormulaMultiply']);
Route::post('/saveforecasting',[InventoryForecastingController::class,'SaveForecasting']);
Route::get('/savefinalforecasting',[InventoryForecastingController::class,'SaveFinalForecasting']);

Route::post('checkfirstversionornot',[InventoryForecastingController::class,'CheckFirstVersionOrNot']);
Route::post('getmultiplygroupid',[InventoryForecastingController::class,'GetMultiplyGroupId']);
Route::get('/forecastinglist',[InventoryForecastingController::class,'ForecastingList']);
Route::get('/searchforecastinglist',[InventoryForecastingController::class,'SearchForecastingList']);
Route::get('/view_forecasting/{id}/{key}/{sort}',[InventoryForecastingController::class,'ViewForeCasting']);
Route::get('/exportforcasting/{fid}/{key}/{sort}',[InventoryForecastingController::class,'ExportForeCasting']);
Route::get('/exportforcasting2/{fid}/{key}/{branchid}',[InventoryForecastingController::class,'ExportForeCasting2']);
Route::post('/getbranchforecastingcheck',[InventoryForecastingController::class,'GetBranchForecastingCheck']);
Route::post('/getbranchforecastingcheck2',[InventoryForecastingController::class,'GetBranchForecastingCheck2']);


Route::get('/printforecasting/{fid}/{key}/{sort}',[InventoryForecastingController::class,'PrintForeCasting']);
Route::post('/getcompanybranch',[InventoryForecastingController::class,'GetCompanyBranch']);
Route::post('/geteverytimequestion',[InventoryForecastingController::class,'GetEveryTimeQuestion']);
Route::post('/getfirstversionquestion',[InventoryForecastingController::class,'GetFirstVersionQuestion']);
Route::get('/branchwiseforecastinglist',[InventoryForecastingController::class,'BranchWiseForecastingList']);
Route::get('/searchbranchwiseforecastinglist',[InventoryForecastingController::class,'SearchBranchWiseForecastinglist']);
Route::get('/view_branchforecasting/{fid}/{branchid}/{key}/{version_no}',[InventoryForecastingController::class,'View_BranchForecasting']);
Route::get('/pending_closing_forecasting',[InventoryForecastingController::class,'PendingClosingForecasting']);



Route::get("/customerperformance",[IndexController::class,'CustomerPerformance']);
Route::get('/exportperformancereport/{sdate}/{edate}',[IndexController::class,'ExportPerformanceReport']);
Route::get('changesession/{id}',[IndexController::class,'ChangeSession']);



//auth controller ke
Route::get('login',[AuthController::class,'Login']);
Route::get('logout',[AuthController::class,'Logout']);
Route::post("/login",[AuthController::class,'LoginData']);
Route::get("/changepassword",[AuthController::class,'ChangePassword']);
Route::post("/changepassword",[AuthController::class,'UpdatePassword']);


Route::get('/clear-cache', function() {
    // $exitCode = Artisan::call('cache:clear');
   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";
    // return what you want
});

Route::get('/adduser',[UserManagementController::class,'AddUser']);
Route::get('adduser2',[UserManagementController::class,'SaveUser']);
Route::get('/userlist',[UserManagementController::class,'UserList']);
Route::get('/assignrole/{uid}',[UserManagementController::class,'AssignRole']);
Route::get('/assignrole2',[UserManagementController::class,'AssignRole2']);
Route::get('deleteuser/{id}',[UserManagementController::class,'DeleteUser']);
Route::get('edituser/{id}',[UserManagementController::class,'EditUser']);
Route::get('updateuser',[UserManagementController::class,'UpdateUser']);
Route::get('/addsupervisor',[UserManagementController::class,'AddSupervisor']);
Route::get('/addsupervisor2',[UserManagementController::class,'SaveSupervisor']);
Route::post('getbranchbusinessline',[UserManagementController::class,'GetBranchBusinessLine']);
Route::post('supervisoraddgroup',[UserManagementController::class,'SupervisorAddGroup']);
Route::post('getsupervisorgroup',[UserManagementController::class,'GetSupervisorGroup']);
Route::get('/supervisorlist',[UserManagementController::class,'SupervisorList']);
Route::get('/editsupervisor/{id}',[UserManagementController::class,'EditSupervisor']);
Route::get('/updatesupervisor',[UserManagementController::class,'UpdateSupervisor']);
Route::get('/safopenrequest',[UserManagementController::class,'SafOpenRequest']);
Route::post('safopenstatuschange',[UserManagementController::class,'SafOpenStatusChange']);
Route::get('/approvedsafopen',[UserManagementController::class,'ApprovedSafOpen']);


Route::get('/branchlist',[MasterController::class,'BranchList']);
Route::get('/branchlist2',[MasterController::class,'BranchList2']);
Route::get('/businesslinelist',[MasterController::class,'BusinessLineList']);
Route::get('/businesslinelist2',[MasterController::class,'BusinessLineList2']);
Route::get('/businesswiseproducts/{bcode}/{grpid}/{compcode}/{bname}/{grpname}/{compname}',[MasterController::class,'BusinessWiseProducts']);
Route::get('/businesslineproduct',[MasterController::class,'BusinessLineProductList']);
Route::get('/businesslineproduct2',[MasterController::class,'BusinessLineProductList2']);
Route::get('/companylist',[MasterController::class,'CompanyList']);
Route::get('/customerlist',[MasterController::class,'CustomerList']);
Route::get('/customerlist2',[MasterController::class,'CustomerList2']);
Route::get('/productlist',[MasterController::class,'ProductList']);
Route::get('/productlist2',[MasterController::class,'ProductList2']);
Route::get('/branchwiseproduct/{pid}/{pname}',[MasterController::class,'BranchWiseProduct']);
Route::get('/dsflist',[MasterController::class,'DsfList']);
Route::get('/dsflist2',[MasterController::class,'DsfList2']);
Route::get('/dsfchangepassword/{id}/{name}',[MasterController::class,'DsfChangePassword']);
Route::get('/updatedsfpassword',[MasterController::class,'UpdateDsfPassword']);
Route::get('/dsfbusinessline',[MasterController::class,'DsfBusinessLine']);
Route::get('/dsfbusinessline2',[MasterController::class,'DsfBusinessLine2']);
Route::get('/dsfsafdetail',[MasterController::class,'DsfSafDetail']);
Route::get('/dsfsafdetail2',[MasterController::class,'DsfSafDetail2']);

Route::post('getbranchgroup',[MasterController::class,'GetBranchGroup']);
Route::post('getbranchdsf',[MasterController::class,'GetBranchDsf']);
Route::post('dsfstatuschange',[MasterController::class,'DsfStatusChange']);
Route::post('getbranchregionzone',[MasterController::class,'GetBranchRegionZone']);
Route::post('getbranchzonecity',[MasterController::class,'GetBranchZoneCity']);
Route::post('productstatuschange',[MasterController::class,'ProductStatusChange']);
Route::post('dsfsafbranchwisesync',[MasterController::class,'DsfSafBranchwiseSync']);
Route::post('branchexcelstatuschange',[MasterController::class,'BranchExcelStatusChange']);


Route::get('/dsfsafereportsummarize',[MasterController::class,'DsfSafReportSummarize']);
Route::get('/dsfsafereportsummarize2',[MasterController::class,'DsfSafReportSummarize2']);
Route::get('/supervisorproducts',[MasterController::class,'SupervisorProducts']);
Route::get('/supervisorproducts2',[MasterController::class,'SupervisorProducts2']);
Route::post('/productstatuschange2',[MasterController::class,'ProductStatusChange2']);
Route::get('/excelcontroll',[MasterController::class,'ExcelControll']);
Route::get('/productlockunlock',[MasterController::class,'ProductLockUnlock']);
Route::get('/productlockunlock2',[MasterController::class,'ProductLockUnlock2']);
Route::post('/productstatuschangeforall',[MasterController::class,'ProductStatusChangeForAll']);
Route::post('/getbusinesslinedsf',[MasterController::class,'GetBusinessLineDsf']);
Route::get('/lockparticulardsf/{branchid}/{usercode}/{id}/{product_code}/{product_name}',[MasterController::class,'LockParticularDsf']);
Route::get('/lockparticulardsf2',[MasterController::class,'LockParticularDsf2']);

Route::get('/bmsapilog',[LogManagementController::class,'BmsApiLog']);
Route::get('/bmsapilog2',[LogManagementController::class,'BmsApiLog2']);

Route::get('/mobileappapilog',[LogManagementController::class,'MobileAppApiLog']);
Route::get('/mobileappapilog2',[LogManagementController::class,'MobileAppApiLog2']);

//order controller
Route::get('/orderlist',[OrderManagementController::class,'OrderList']);
Route::post('getbranchdsfdata',[OrderManagementController::class,'GetBranchDsfData']);
Route::get('/searchorder',[OrderManagementController::class,'SearchOrder']);
Route::get('/vieworder/{id}/{uid}',[OrderManagementController::class,'ViewOrder']);
Route::post('addorderremark',[OrderManagementController::class,'AddOrderRemark']);
Route::get('/exportorderdetaillist/{id}/{uid}',[OrderManagementController::class,'ExportOrderDetail']);
Route::get('/exportorderlist/{sdate}/{edate}/{oid}/{brid}/{dfid}/{dover}/{status}',[OrderManagementController::class,'ExportOrderList']);
Route::any('/importordercsv',[OrderManagementController::class,'ImportOrderCsv']);
Route::get('/csv_order_detail/{sysid}/{uqid}/{dfcode}/{brcode}',[OrderManagementController::class,'CsvOrderDetail']);
Route::any('/csvplaceorder',[OrderManagementController::class,'CsvPlaceOrder']);
Route::get('/exportordercsv_formbms/{oid}/{uqid}',[OrderManagementController::class,'ExportOrderCsvforBms']);
Route::get('/view_dsf_route/{oid}/{uqid}',[OrderManagementController::class,'ViewDsfRoute']);
Route::post('getdispatchlat',[OrderManagementController::class,'GetOrderLatLng']);
//must selling product
Route::get('/addmustsellingproduct',[MustSellingManagementController::class,'AddMustSellingProduct']);
Route::get('/addmustsellingproduct2',[MustSellingManagementController::class,'AddMustSellingProduct2']);
Route::get('/addmustsellingproduct3',[MustSellingManagementController::class,'AddMustSellingProduct3']);
Route::post('/addproductmst',[MustSellingManagementController::class,'AddProductMsl']);
Route::get('/mustsellinglist',[MustSellingManagementController::class,'MustSellingList']);
Route::get('/mustsellinglist2',[MustSellingManagementController::class,'MustSellingList2']);
Route::get('/deletemsl/{sid}',[MustSellingManagementController::class,'DeleteMslList']);
Route::post('/updateproductmst',[MustSellingManagementController::class,'UpdateProductMsl']);


//geo fancing controller
Route::get('/dsffancing_lockunlock',[GeoFancingController::class,'DsfFancing_LockUnlock']);
Route::post('/dsffancingstatuschange',[GeoFancingController::class,'DsfFancingStatusChange']);
Route::get('/customergeofancing_lockunlock',[GeoFancingController::class,'CustomerGeoFancing_Lockunlock']);
Route::post('/customerfancingstatuschange',[GeoFancingController::class,'CustomerFancingStatusChange']);
Route::get('/customergeofancing_lockunlock2',[GeoFancingController::class,'CustomerGeoFancing_Lockunlock2']);
Route::get('/dsffancing_order_report',[GeoFancingController::class,'DsfFancing_Order_Report']);
Route::get('/dsffancing_order_report2',[GeoFancingController::class,'DsfFancing_Order_Report2']);


Route::get('/minbooking_amount',[BusinessLineManagementController::class,'AddMinBookingAmount']);
Route::get('/minbooking_amount2',[BusinessLineManagementController::class,'AddMinBookingAmount2']);
Route::get('/deleterestriction/{id}/{sname}',[BusinessLineManagementController::class,'DeleteRestriction']);


Route::get('/dsfroutetracking',[DsfTrackingController::class,'DsfRouteTracking']);
Route::get('/dsfroutetracking2',[DsfTrackingController::class,'DsfRouteTracking2']);
Route::get('/dsfday_route/{dsfcode}/{brcode}/{bdate}/{bname}/{dname}/{hour}/{minute}/{strtime}/{endtime}',[DsfTrackingController::class,'DsfDay_Route']);
Route::post('/getdsfdailyroute',[DsfTrackingController::class,'GetDsfDailyRoute']);
Route::get('/dsfbooking_analysis',[DsfTrackingController::class,'DsfBooking_Analysis']);
Route::get('/dsfbooking_analysis2',[DsfTrackingController::class,'DsfBooking_Analysis2']);
Route::get('/get-markers',[DsfTrackingController::class,'Get_Markers']);
Route::get('/dsfsaf_inmap/{dsfcode}/{brcode}/{bdate}/{bname}/{dname}/{hour}/{minute}/{strtime}/{endtime}',[DsfTrackingController::class,'DsfSaf_InMap']);
Route::get('/salesman_routetracking',[DsfTrackingController::class,'SalesmanRouteTracking']);
Route::get('/salesman_routetracking2',[DsfTrackingController::class,'SalesmanRouteTracking2']);
Route::get('/salesmanday_route/{smancode}/{smanname}/{strtime}/{endtime}/{totalcust}/{hour}/{minute}/{brcode}/{tripdate}/{tripstrlat}/{tripstrlong}/{tripendlat}/{tripendlong}',[DsfTrackingController::class,'SalesmanDay_Route']);
Route::post('/getsalesmandailyroute',[DsfTrackingController::class,'GetSalesmanDailyRoute']);
Route::get('/salesman_route_detail/{smancode}/{smanname}/{strtime}/{endtime}/{totalcust}/{hour}/{minute}/{brcode}/{tripdate}/{tripstrlat}/{tripstrlong}/{tripendlat}/{tripendlong}',[DsfTrackingController::class,'Salesman_Route_Detail']);
Route::get('/salesman_shop_detail/{smancode}/{smanname}/{strtime}/{endtime}/{totalcust}/{hour}/{minute}/{brcode}/{tripdate}/{tripstrlat}/{tripstrlong}/{tripendlat}/{tripendlong}',[DsfTrackingController::class,'Salesman_Shop_Detail']);
Route::get('/get-markers2',[DsfTrackingController::class,'Get_Markers2']);
