<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Product;
use App\Http\Middleware\CheckAdmin;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\SalseExper;
use App\Http\Controllers\Poshtiban;
//poshtiban routes
Route::get('/customers',[Customer::class,'index'])->middleware('CheckPoshtiban');
Route::post('/changeDate',[Admin::class,'changeDate'])->middleware('CheckPoshtiban');
Route::get("/calendar",[Admin::class,"myCalendar"])->middleware('CheckPoshtiban');
Route::get("/getCustomerForTimeTable",[Customer::class,"getCustomerForTimeTable"])->middleware('CheckPoshtiban');
Route::get('/myCustomers',[Customer::class,'myCustomers'])->middleware('CheckPoshtiban');
Route::get('/getCustomerInfo',[Customer::class,'getCustomerInfo'])->middleware('CheckPoshtiban');
Route::post('/editCustomer',[Customer::class,'editCustomer'])->middleware('CheckPoshtiban');
//common routes for all
Route::get("/addComment",[Customer::class,"addComment"])->middleware('CheckCommon');
Route::get("/message",[Admin::class,"message"]);
Route::get("/customerDashboard",[Customer::class,"customerDashboard"])->middleware('CheckCommon');
Route::get("/getFactorDetail",[Customer::class,"getFactorDetail"])->middleware('CheckCommon');
Route::get("/addAssessment",[Customer::class,"addAssessment"])->middleware('CheckCommon');
Route::get("/addAssessmentPast",[Customer::class,"addAssessmentPast"])->middleware('CheckCommon');
Route::get("/setCommentProperty",[Customer::class,"setCommentProperty"])->middleware('CheckCommon');
Route::get("/logoutUser",[Admin::class,"logoutUser"])->middleware('CheckCommon');
Route::get("/userProfile",[Admin::class,"userProfile"])->middleware('CheckCommon');
Route::get("/customerLocation",[Customer::class,"customerLocation"])->middleware('CheckCommon');
Route::post("/editOwnAdmin",[Admin::class,"editOwnAdmin"])->middleware('CheckCommon');
Route::get("/getAdminInfo",[Admin::class,"getAdminInfo"])->middleware('CheckCommon');
Route::get("/addMessage",[Admin::class,"addMessage"])->middleware('CheckCommon');
Route::get("/getDiscusstion",[Admin::class,"getDiscusstion"])->middleware('CheckCommon');
Route::get("/addDiscussion",[Admin::class,"addDiscussion"])->middleware('CheckCommon');
Route::get("/searchCustomerByName",[Customer::class,"searchCustomerByName"])->middleware('CheckCommon');
Route::get("/searchCustomerByMantagheh",[Customer::class,"searchCustomerByMantagheh"])->middleware('CheckCommon');
Route::get("/searchAllCustomerByMantagheh",[Customer::class,"searchAllCustomerByMantagheh"])->middleware('CheckCommon');
Route::get("/searchCustomerByCode",[Customer::class,"searchCustomerByCode"])->middleware('CheckCommon');
Route::get("/addAlarmClock",[Admin::class,"addAlarmClock"])->middleware('CheckCommon');
Route::get("/getAlarmTime",[Admin::class,"getAlarmTime"])->middleware('CheckCommon');
Route::get("/offAlarmClock",[Admin::class,"offAlarmClock"])->middleware('CheckCommon');
Route::get("/orderByNameCode",[Customer::class,"orderByNameCode"])->middleware('CheckCommon');
Route::get("/searchPastAssesByDate",[Customer::class,"searchPastAssesByDate"])->middleware('CheckCommon');
Route::get("/searchDoneAssesByDate",[Customer::class,"searchDoneAssesByDate"])->middleware('CheckCommon');
Route::get("/searchCustomerByRegion",[Customer::class,"searchCustomerByRegion"])->middleware('CheckCommon');
Route::get("/searchRegion",[Customer::class,"searchRegion"])->middleware('CheckCommon');
Route::get("/searchAssignRegion",[Customer::class,"searchAssignRegion"])->middleware('CheckCommon');

Route::get("/reports",[Admin::class,"report"])->middleware('CheckCommon');

//[drivers]
Route::get("/crmDriver",[DriverController::class,"crmDriver"])->middleware('CheckCommon');
// for searching 
Route::get("/crmDriverSearch",[DriverController::class,"crmDriverSearch"])->middleware('CheckCommon');
Route::get("/searchMapByFactor",[Customer::class,"searchMapByFactor"])->middleware('CheckCommon');
Route::get("/getFactorInfo",[DriverController::class,"getFactorInfo"])->middleware('CheckCommon');

//Admins routes

Route::get('/getAdminHistoryComment',[Admin::class,'getAdminHistoryComment'])->middleware('checkUser');
Route::get('/',[Admin::class,'crmTerminal']);
Route::get('/home', [Admin::class,'dashboard'])->middleware('checkUser');
Route::post('/addCustomer',[Customer::class,'addCustomer'])->middleware('CheckCommon');
Route::get("/getCustomer",[Customer::class,"getCustomer"])->middleware('checkUser');
Route::get("/getAddedCustomer",[Customer::class,"getAddedCustomer"])->middleware('checkUser');
Route::post("/assesCustomer",[Customer::class,"assesCustomer"])->middleware('CheckCommon');
Route::get('/newCustomer',[Customer::class,'newCustomer'])->middleware('checkUser');
Route::get("/assignCustomer",[Admin::class,'index'])->middleware('checkUser');
Route::post("/addAdmin",[Admin::class,'AddAdmin'])->middleware('checkUser');
Route::post("/addAdminFromList",[Admin::class,'addAdminFromList'])->middleware('checkUser');
Route::get("/AddCustomerToAdmin",[Admin::class,"AddCustomerToAdmin"])->middleware('checkUser');
Route::get("/RemoveCustomerFromAdmin",[Admin::class,"RemoveCustomerFromAdmin"])->middleware('checkUser');
Route::get("/karbarAction",[Admin::class,"karbarAction"])->middleware('checkUser');
Route::get("/getProducts",[Product::class,"getProducts"])->middleware('checkUser');
Route::get("/commentToday",[Customer::class,"todayComment"])->middleware('CheckCommon');
Route::get("/commentPast",[Customer::class,"pastComment"])->middleware('CheckCommon');
Route::get("/commentDone",[Customer::class,"doneComment"])->middleware('CheckCommon');
Route::get("/alarm",[Admin::class,"alarm"])->middleware('CheckCommon');
Route::get("/customerDashboardForAlarm",[Admin::class,"customerDashboardForAlarm"])->middleware('CheckCommon');
Route::get("/inactiveCustomerAlarm",[Customer::class,"inactiveCustomerAlarm"])->middleware('CheckCommon');
Route::get("/activateCustomer",[Admin::class,"activateCustomer"])->middleware('CheckCommon');
Route::get("/changeAlarm",[Admin::class,"changeAlarm"])->middleware('CheckCommon');
Route::get("/getAlarmHistory",[Admin::class,"getAlarmHistory"])->middleware('CheckCommon');
Route::get("/searchCustomerAalarmName",[Customer::class,"searchCustomerAalarmName"])->middleware('CheckCommon');
Route::get("/searchCustomerAalarmCode",[Customer::class,"searchCustomerAalarmCode"])->middleware('CheckCommon');
Route::get("/searchCustomerAalarmLocation",[Customer::class,"searchCustomerAalarmLocation"])->middleware('CheckCommon');
Route::get("/searchCustomerAalarmActive",[Customer::class,"searchCustomerAalarmActive"])->middleware('CheckCommon');
Route::get("/searchCustomerAalarmOrder",[Customer::class,"searchCustomerAalarmOrder"])->middleware('CheckCommon');

Route::get("/referedCustomer",[Admin::class,"referedCustomer"])->middleware('checkUser');
Route::get("/visitorReport",[Admin::class,"visitorReport"])->middleware('checkUser');
Route::get("/searchVisotrsByDate",[Admin::class,"searchVisotrsByDate"])->middleware('checkUser');
Route::get("/searchVisotrsLoginFrom",[Admin::class,"searchVisotrsLoginFrom"])->middleware('checkUser');
Route::get("/searchVisotrsPlatform",[Admin::class,"searchVisotrsPlatform"])->middleware('checkUser');
Route::get("/searchSameTimeCountLogin",[Admin::class,"searchSameTimeCountLogin"])->middleware('checkUser');
Route::get("/searchVisotrsLoginTo",[Admin::class,"searchVisotrsLoginTo"])->middleware('checkUser');
Route::get("/inactivCustomer",[Admin::class,"inactivCustomer"])->middleware('checkUser');
Route::get("/customerDashboardForAdmin",[Admin::class,"customerDashboardForAdmin"])->middleware('checkUser');
Route::get("/getFirstComment",[Customer::class,"getFirstComment"])->middleware('CheckCommon');
Route::get("/returnCustomer",[Customer::class,"returnCustomer"])->middleware('CheckCommon');
Route::get("/tempRoute",[Admin::class,"tempRoute"])->middleware('checkUser');
Route::get("/kalaAction",[Admin::class,"kalaAction"])->middleware('checkUser');
Route::get("/adminDashboard",[Admin::class,"adminDashboard"])->middleware('checkUser');
Route::get("/getAdminTodayInfo",[Admin::class,"getAdminTodayInfo"])->middleware('checkUser');
Route::get("/takhsisCustomer",[Admin::class,"takhsisCustomer"])->middleware('checkUser');
Route::get("/takhsisNewCustomer",[Admin::class,"takhsisNewCustomer"])->middleware('checkUser');
Route::get("/takhsisCustomerFromEmpty",[Admin::class,"takhsisCustomerFromEmpty"])->middleware('checkUser');
Route::post("/loginUser",[Admin::class,"loginUser"]);
Route::get("/crmSetting",[Admin::class,"crmSetting"])->middleware('checkUser');
Route::get("/checkUserNameExistance",[Admin::class,"checkUserNameExistance"])->middleware('checkUser');
Route::get("/searchMap",[Customer::class,"searchMap"])->middleware('checkUser');
Route::get("/getAdminForEmpty",[Admin::class,"getAdminForEmpty"])->middleware('checkUser');
Route::get("/emptyAdmin",[Admin::class,"emptyAdmin"])->middleware('checkUser');
Route::get("/moveCustomerToAdmin",[Admin::class,"moveCustomerToAdmin"])->middleware('checkUser');
Route::get("/getAdminForMove",[Admin::class,"getAdminForMove"])->middleware('checkUser');
Route::post("/editAdmintStuff",[Admin::class,"editAdmintStuff"])->middleware('checkUser');
Route::post("/editAdmintListStuff",[Admin::class,"editAdmintListStuff"])->middleware('checkUser');
Route::get("/deleteAdmin",[Admin::class,"deleteAdmin"])->middleware('checkUser');
Route::get("/deleteAdmin1",[Admin::class,"deleteAdmin1"])->middleware('checkUser');
Route::get("/inactiveCustomer",[Customer::class,"inactiveCustomer"])->middleware('checkUser');
Route::get("/gotEmpty",[Admin::class,"gotEmpty"])->middleware('checkUser');
Route::get("/searchReferedCustomerName",[Admin::class,"searchReferedCustomerName"])->middleware('checkUser');
Route::get("/searchReferedPCode",[Customer::class,"searchReferedPCode"])->middleware('checkUser');
Route::get("/searchReturnedByDate",[Customer::class,"searchReturnedByDate"])->middleware('checkUser');
Route::get("/searchByReturner",[Customer::class,"searchByReturner"])->middleware('checkUser');
Route::get("/searchEmptyByName",[Customer::class,"searchEmptyByName"])->middleware('checkUser');
Route::get("/searchEmptyByPCode",[Customer::class,"searchEmptyByPCode"])->middleware('checkUser');
Route::get("/searchEmptyByDate",[Customer::class,"searchEmptyByDate"])->middleware('checkUser');
Route::get("/searchAllCustomerByName",[Customer::class,"searchAllCustomerByName"])->middleware('checkUser');
Route::get("/searchAllCustomerByPCode",[Customer::class,"searchAllCustomerByPCode"])->middleware('checkUser');
Route::get("/searchAllCustomerByAdmin",[Customer::class,"searchAllCustomerByAdmin"])->middleware('checkUser');
Route::get("/searchAllCustomerActiveOrNot",[Customer::class,"searchAllCustomerActiveOrNot"])->middleware('checkUser');
Route::get("/searchAllCustomerLocationOrNot",[Customer::class,"searchAllCustomerLocationOrNot"])->middleware('checkUser');
Route::get("/searchAllCustomerFactorOrNot",[Customer::class,"searchAllCustomerFactorOrNot"])->middleware('checkUser');
Route::get("/searchAllCustomerBasketOrNot",[Customer::class,"searchAllCustomerBasketOrNot"])->middleware('checkUser');
Route::get("/searchAllCustomerLoginOrNot",[Customer::class,"searchAllCustomerLoginOrNot"])->middleware('checkUser');
Route::get("/searchKalaNameCode",[Customer::class,"searchKalaNameCode"])->middleware('checkUser');
Route::get("/searchKalaByStock",[Customer::class,"searchKalaByStock"])->middleware('checkUser');
Route::get("/searchKalaByActiveOrNot",[Customer::class,"searchKalaByActiveOrNot"])->middleware('checkUser');
Route::get("/searchKalaByZeroOrNot",[Customer::class,"searchKalaByZeroOrNot"])->middleware('checkUser');
Route::get("/searchSubGroupKala",[Customer::class,"searchSubGroupKala"])->middleware('checkUser');
Route::get("/searchBySubGroupKala",[Customer::class,"searchBySubGroupKala"])->middleware('checkUser');
Route::get("/searchAdminByNameCode",[Admin::class,"searchAdminByNameCode"])->middleware('checkUser');
Route::get("/searchAdminByType",[Admin::class,"searchAdminByType"])->middleware('checkUser');
Route::get("/searchAdminByActivation",[Admin::class,"searchAdminByActivation"])->middleware('checkUser');
Route::get("/searchAdminFactorOrNot",[Admin::class,"searchAdminFactorOrNot"])->middleware('checkUser');
Route::get("/searchAdminLoginOrNot",[Admin::class,"searchAdminLoginOrNot"])->middleware('checkUser');
Route::get("/searchAdminCustomerLoginOrNot",[Admin::class,"searchAdminCustomerLoginOrNot"])->middleware('checkUser');
Route::get("/searchInActiveCustomerByName",[Customer::class,"searchInActiveCustomerByName"])->middleware('checkUser');
Route::get("/searchInActiveCustomerByCode",[Customer::class,"searchInActiveCustomerByCode"])->middleware('checkUser');
Route::get("/searchInActiveCustomerByLocation",[Customer::class,"searchInActiveCustomerByLocation"])->middleware('checkUser');
Route::get("/orderInactiveCustomers",[Customer::class,"orderInactiveCustomers"])->middleware('checkUser');
Route::get("/viewReturnComment",[Customer::class,"viewReturnComment"])->middleware('checkUser');
Route::get("/allCustomers",[Admin::class,"allCustomers"])->middleware('checkUser');
Route::get("/searchAllCustomerByCode",[Admin::class,"searchAllCustomerByCode"])->middleware('checkUser');
Route::get("/orderAllCustomerByCName",[Admin::class,"orderAllCustomerByCName"])->middleware('checkUser');
Route::get("/searchAddedCustomerByRegion",[Customer::class,"searchAddedCustomerByRegion"])->middleware('checkUser');
Route::get("/searchAddedCustomerByNameMNM",[Customer::class,"searchAddedCustomerByNameMNM"])->middleware('checkUser');
Route::get("/searchCustomerByNameMNM",[Customer::class,"searchCustomerByNameMNM"])->middleware('checkUser');
Route::get("/listKarbaran",[Admin::class,"listKarbaran"])->middleware('checkUser');
Route::get("/testRoute",[Admin::class,"testRoute"])->middleware('checkUser');
Route::get("/getAssesComment",[Admin::class,"getAssesComment"])->middleware('checkUser');
Route::get("/getCustomerLoginInfo",[Admin::class,"getCustomerLoginInfo"])->middleware('checkUser');

Route::get("/searchCustomerLocation",[Customer::class,"searchCustomerLocation"])->middleware('checkUser');
Route::get("/searchingCustomerName",[Customer::class,"searchingCustomerName"])->middleware('checkUser');

//no need to checking
Route::get('/login',[Admin::class,'login']);
Route::get("/changePosition",[Customer::class,"changePosition"])->middleware('checkUser');
Route::get("/updatePosition",[Customer::class,"updatePosition"])->middleware('checkUser');
Route::get("/setReciveMoneyDetail",[DriverController::class,"setReciveMoneyDetail"])->middleware('CheckCommon');
Route::get('/addProvinceCode',[Admin::class,'addProvinceCode'])->middleware('CheckCommon');

// search bargeri based on date
Route::get("/searchBargeriByDate",[DriverController::class,"searchBargeriByDate"])->middleware('CheckCommon');
Route::get('/downloadApk',[Admin::class,'downloadApk']);

Route::get("/bargeryInfo",[DriverController::class,"bargeryInfo"])->middleware('CheckCommon');
Route::get("/bargeryFactors",[DriverController::class,"bargeryFactors"])->middleware('CheckCommon');

Route::get("/salesExpertAction",[SalseExper::class,"salesExpertAction"])->middleware('CheckCommon');
Route::get("/bonusSetting",[SalseExper::class,"bonusSetting"])->middleware('CheckCommon');
Route::get("/defineTarget",[SalseExper::class,"defineTarget"])->middleware('CheckCommon');
Route::get("/getSalesExpertSelfInfo",[SalseExper::class,"getSalesExpertSelfInfo"])->middleware('CheckCommon');
Route::get("/getTodaySelfInstalls",[SalseExper::class,"getTodaySelfInstalls"])->middleware('CheckCommon');
Route::get("/getTodaySelfBuyToday",[SalseExper::class,"getTodaySelfBuyToday"])->middleware('CheckCommon');
Route::get("/getAllNewInstallSelf",[SalseExper::class,"getAllNewInstallSelf"])->middleware('CheckCommon');
Route::get("/getAllNewBuySelf",[SalseExper::class,"getAllNewBuySelf"])->middleware('CheckCommon');
Route::get("/getSalesExpertSelfInfoByDates",[SalseExper::class,"getSalesExpertSelfInfoByDates"])->middleware('CheckCommon');
Route::get("/addTarget",[SalseExper::class,"addTarget"])->middleware('CheckCommon');
Route::get("/editTarget",[SalseExper::class,"editTarget"])->middleware('CheckCommon');
Route::get("/getTargetInfo",[SalseExper::class,"getTargetInfo"])->middleware('CheckCommon');
Route::get("/addSpecialBonus",[SalseExper::class,"addSpecialBonus"])->middleware('CheckCommon');
Route::get("/editSpecialBonus",[SalseExper::class,"editSpecialBonus"])->middleware('CheckCommon');
Route::get("/getSpecialBonusInfo",[SalseExper::class,"getSpecialBonusInfo"])->middleware('CheckCommon');
Route::get("/deleteSpecialBonus",[SalseExper::class,"deleteSpecialBonus"])->middleware('CheckCommon');
Route::get("/deleteTarget",[SalseExper::class,"deleteTarget"])->middleware('CheckCommon');
Route::get("/subTrees",[SalseExper::class,"subTrees"])->middleware('CheckCommon');
Route::get("/saleExpertActionInfo",[SalseExper::class,"saleExpertActionInfo"])->middleware('CheckCommon');
Route::get("/getAllBuyAghlamSelf",[SalseExper::class,"getAllBuyAghlamSelf"])->middleware('CheckCommon');
Route::get("/getTodayBuyAghlamSelf",[SalseExper::class,"getTodayBuyAghlamSelf"])->middleware('CheckCommon');
Route::get("/getAllBuyMoneySelf",[SalseExper::class,"getAllBuyMoneySelf"])->middleware('CheckCommon');
Route::get("/getTodayBuyMoneySelf",[SalseExper::class,"getTodayBuyMoneySelf"])->middleware('CheckCommon');
Route::get("/getThisDayMyCustomer",[Customer::class,"getThisDayMyCustomer"])->middleware('CheckCommon');
Route::get("/getThisDayCustomerForAdmin",[Customer::class,"getThisDayCustomerForAdmin"])->middleware('CheckCommon');

Route::get("/addUpDownBonus",[Admin::class,"addUpDownBonus"])->middleware('CheckCommon');
Route::get("/showAdminEmtyazHistory",[Admin::class,"showAdminEmtyazHistory"])->middleware('CheckCommon');
Route::get("/getAdminHistory",[Admin::class,"getAdminHistory"])->middleware('CheckCommon');
Route::get("/editEmtiyazHistory",[Admin::class,"editEmtiyazHistory"])->middleware('CheckCommon');
Route::get("/getActiveInactiveCustomers",[Customer::class,"getActiveInactiveCustomers"])->middleware('CheckCommon');
Route::get("/getGeneralBase",[SalseExper::class,"getGeneralBase"])->middleware('checkUser');
Route::post("/editGeneralTarget",[SalseExper::class,"editGeneralTarget"])->middleware('checkUser');

Route::get("/listPoshtibans",[Poshtiban::class,"getPostibanList"])->middleware('CheckCommon');
Route::get("/poshtibanActionInfo",[Poshtiban::class,"poshtibanActionInfo"])->middleware('CheckCommon');

Route::get("/subTrees",[SalseExper::class,"subTrees"])->middleware('CheckCommon');
Route::get("/getBossBazarYab",[SalseExper::class,"getBossBazarYab"])->middleware('CheckCommon');
Route::get("/getGeneralBonus",[SalseExper::class,"getGeneralBonus"])->middleware('CheckCommon');
Route::get("/editGeneralBonus",[SalseExper::class,"editGeneralBonus"])->middleware('CheckCommon');
Route::get("/getTodayBuyAghlamPoshtiban",[Poshtiban::class,"getTodayBuyAghlamPoshtiban"])->middleware('CheckCommon');
Route::get("/getAllBuyAghlamByAdmin",[Poshtiban::class,"getAllBuyAghlamPoshtiban"])->middleware('CheckCommon');
Route::get("/getAllBuyMoneyPoshtiban",[Poshtiban::class,"getAllBuyMoneyPoshtiban"])->middleware('CheckCommon');
Route::get("/getAllBuyMoneyTodayPoshtiban",[Poshtiban::class,"getAllBuyMoneyTodayPoshtiban"])->middleware('CheckCommon');
Route::get("/getAllNewBuyPoshtiban",[Poshtiban::class,"getAllNewBuyPoshtiban"])->middleware('CheckCommon');
Route::get("/getAllNewTodayBuyPoshtiban",[Poshtiban::class,"getAllNewTodayBuyPoshtiban"])->middleware('CheckCommon');
Route::get("/getPoshtibanActionInformation/{adminId}",[Poshtiban::class,"getPoshtibanActionInformation"])->middleware('checkUser');
Route::get("/getDriverTodayAghlam",[Poshtiban::class,"getDriverTodayAghlam"])->middleware('CheckCommon');
Route::get("/getDriverAllAghlam",[Poshtiban::class,"getDriverAllAghlam"])->middleware('CheckCommon');
Route::get("/getAllFactorDriver",[Poshtiban::class,"getAllFactorDriver"])->middleware('CheckCommon');
Route::get("/getTodayDriverFactors",[Poshtiban::class,"getTodayDriverFactors"])->middleware('CheckCommon');
Route::get("/driverService",[DriverController::class,"driverService"])->middleware('CheckCommon');
Route::get("/addService",[DriverController::class,"addService"])->middleware('CheckCommon');
Route::get("/getInfoForDriverService",[DriverController::class,"getInfoForDriverService"])->middleware('CheckCommon');
Route::get("/getServiceInfo",[DriverController::class,"getServiceInfo"])->middleware('CheckCommon');
Route::get("/editDriverService",[DriverController::class,"editDriverService"])->middleware('CheckCommon');
Route::get('/randt',[Customer::class,'randt'])->middleware('CheckCommon');
Route::post('/addRandT',[Customer::class,'addRandT'])->middleware('CheckCommon');


//بعد از تغیر ساختار
Route::get('/getAsses',[Customer::class,'getAsses'])->middleware('CheckCommon');
Route::get('/getDonCommentInfo',[Customer::class,'getDonCommentInfo'])->middleware('CheckCommon');
Route::get('/bonusIncreaseDecrease',[SalseExper::class,'bonusIncreaseDecrease'])->middleware('CheckCommon');
