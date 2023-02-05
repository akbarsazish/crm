@extends('layout')
@section('content')
<style>
    .loginReport {
    width:122px;
}
#customerWithOutAlarmBuyOrNot {
    display:none;
}
</style>
<div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> فیلتر آلارمها </legend>
                        <form action="{{url('/filteralarms')}}" method="get" id="filterAlarmsForm">
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end alarmRighRdios" type="radio" value="0" name="alarmState" id="customerWithAlarm">
                                <label class="form-check-label me-4" for="assesPast"> آلارمها </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end alarmRighRdios" type="radio" value="1" name="alarmState" id="">
                                <label class="form-check-label me-4" for="assesDone"> آلارمهای انجام شده  </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end alarmRighRdios" type="radio"  value="2" name="alarmState" id="customerWithOutAlarm">
                                <label class="form-check-label me-4" for="assesDone"> مشتریان فاقد آلارم </label>
                            </div>
                            <div class="form-group col-sm-12 mb-2" id="customerWithOutAlarmBuyOrNot">
                                <select class="form-select form-select-sm" name="buyOrNot" id="buyOrNot">
                                    <option value="-1"> خرید  </option>
                                    <option value="1"> دارد </option>
                                    <option value="0"> ندارد </option>
                                    <option value="2"> همه </option>
                                </select>
                            </div>
                            <div class="row" id="alarmDates">
                                <div class="form-group col-sm-12 mb-1">
                                    <input type="text" name="firstDateAlarm" placeholder=" از  تاریخ آلارم گذاری"    class="form-control form-control-sm" id="firstDateReturned">
                                </div>
                                <div class="form-group col-sm-12 mb-2">
                                    <input type="text" name="secondDateAlarm" placeholder="  تا تاریخ آلارم گذاری" class="form-control form-control-sm" id="secondDateReturned">
                                </div>
                            </div>
                            <div class="row" id="alarmBuysDates" style="display:none">
                                <div class="form-group col-sm-12 mb-1">
                                    <input type="text" name="firstDateBuy" placeholder="از  تاریخ خرید"    class="form-control form-control-sm" id="firstDate">
                                </div>
                                <div class="form-group col-sm-12 mb-2">
                                    <input type="text" name="secondDateBuy" placeholder="تا تاریخ خرید" class="form-control form-control-sm" id="secondDate">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12 mb-1">
                                    <button class='btn btn-primary btn-sm text-warning' type="submit"> بازخوانی <i class="fal fa-dashboard fa-lg"></i></button>
                                </div>
                            </div>
                        </form>
                        
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader">
                        <div class="col-sm-5 text-end">
                            <div class="row">
                                <div class="form-group col-sm-3 mt-2 px-1">
                                    <input type="text" name="" placeholder="جستجو" class="form-control form-control-sm " id="searchAlarmName">
                                </div>
                                <div class="form-group col-sm-3 mt-2 px-1">
                                    <select class="form-select form-select-sm " id="searchAlarmByCity">
                                       <option value="">--</option>
                                        <option value="80"> تهران </option>
                                     
                                    </select>
                                </div>
                                <div class="form-group col-sm-3 mt-2 px-1">
                                    <select class="form-select form-select-sm " id="searchAlarmByMantagheh">
                                    <option value="" hidden>منطقه</option>
                                    <option value="0">همه</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-3 mt-2 px-1">
                                    <select class="form-select form-select-sm alarmBtn" id="orderAlarms">
                                        <option value="-1">مرتب سازی</option>
                                        <option value="FactDate">تاریخ آخرین فاکتور </option>
                                        <option value="Name">نام مشتری</option>
                                        <option value="alarmDate"> تاریخ آلارم </option>
                                        <option value="n.poshtibanName"> نام کاربر  </option>
                                    </select>
                                    <select class="form-select form-select-sm" style="display:none" id="orderUnAlarms">
                                        <option value="-1">مرتب سازی</option>
                                        <option value="FactDate">تاریخ آخرین فاکتور </option>
                                        <option value="Name">نام مشتری</option>
                                        <option value="adminName"> نام کاربر  </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7 text-start">
                            <input type="text" id="customerSn" style="display: none" name="customerSn" value="" />
                            <input type="text" id="adminSn" style="display: none" name="adminSn" value="" />
                            <button class='enableBtn btn btn-sm btn-primary text-warning' disabled type="button" id='openDashboardForAlarm'> داشبورد <i class="fal fa-dashboard "></i></button>
                            <button class='enableBtn btn btn-sm btn-primary text-warning' disabled type="button" onclick="takhsisCustomerAlarm()"> تخصیص <i class="fa-solid fa-list-check"></i> </button>
                            <button class='enableBtn btn btn-sm btn-primary text-warning' disabled type="button" onclick="changeAdminAlarm()"> تغییر کاربر  <i class="fa-solid fa-edit"></i> </button>
                            <button class='enableBtn btn btn-sm btn-primary text-warning alarmBtn' disabled type="button"  onclick="changeAlarm()"> تغیر آلارم  <i class="fal fa-warning "></i></button>
                            <button class='enableBtn btn btn-sm btn-primary text-warning alarmBtn' disabled type="button"  onclick="alarmHistory()"> گردش آلارم  <i class="fal fa-history "></i></button>
                            <button class='enableBtn btn btn-sm btn-primary text-warning' disabled type="button" id="inactiveButton">غیر فعال <i class="fal fa-ban"></i> </button>
                            <input type="text" id="customerSn" style="display: none" name="customerSn" value="" />
                        </div>
                   </div>
                    <div class="row mainContent">
                        <div class="col-lg-12" id="alarmedCustomers">
                            <table id="strCusDataTable" class='table table-bordered table-striped table-sm'>
                                    <thead class="tableHeader">
                                        <tr >
											<th> ردیف </th>
											<th  style="width:111px">تاریخ ثبت</th>
											<th >اسم</th>
											<th> شماره تماس</th>
											<th  style="width:99px"> تعداد گردش  </th>
											<th style="width:77px">منطقه </th>
											<th style="width:66px"> تعیین </th>
                                            <th  style="width:111px">تاریخ فاکتور</th>
											<th style="width:111px"> تاریخ نمایش  </th>
											<th style="width:166px"> کاربر  </th>
											<th>انتخاب</th>
                                       </tr>
                                    </thead>
                                    <tbody class="select-highlight tableBody" id="alarmsbody">
                                        @foreach ($customers as $customer)
                                            <tr onClick="setAlarmCustomerStuff(this,{{$customer->id}})">
                                                <td >{{$loop->iteration}}</td>
                                                <td  style="width:111px">{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($customer->TimeStamp))->format("Y/m/d")}}</td>
                                                <td>{{trim($customer->Name)}}</td>
                                                <td>{{trim($customer->PhoneStr)}}</td>
                                                <td style="width:99px">{{$customer->countCycle}}</td>
                                                <td style="width:77px">{{trim($customer->NameRec)}}</td>
                                                <td style="width:66px">{{$customer->assignedDays}}</td>
                                                <td style="width:111px;">{{$customer->FactDate}}</td>
                                                <td style="width:111px; color:red">{{$customer->alarmDate}}</td>
                                                <td style="width:166px">{{trim($customer->poshtibanName).' '.trim($customer->poshtibanLastName)}}</td>
                                                <td><input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN.'_'.$customer->adminSn.'_'.$customer->SerialNoHDS}}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                             </table>
                             <div class="grid-today rounded-2">
                                <div class="today-item"> <span style="color:red; font-weight:bold;">  آخرین کامنت: </span> <span id="alarmLastComment"></span>  </div>
                            </div>
                        </div>

                        <div class="col-lg-12" id="unAlarmedCustomers" style="display:none">
                            <table id="strCusDataTable" class='table table-bordered table-striped table-sm'>
                                    <thead class="tableHeader">
                                        <tr >
											<th> ردیف </th>
											<th >اسم</th>
											<th >کد</th>
											<th> شماره تماس</th>
											<th style="width:77px">منطقه </th>
                                            <th  style="width:111px">تاریخ آخرین</th>
											<th style="width:166px"> کاربر  </th>
											<th>انتخاب</th>
                                       </tr>
                                    </thead>
                                    <tbody class="select-highlight tableBody" id="unalarmsbody">
                                        @foreach ($notAlarmed as $customer)
                                            <tr onclick="setUnAlarmStuff(this,{{$customer->PSN}},{{$customer->adminId}})">
                                                <td >{{$loop->iteration}}</td>
                                                <td>{{trim($customer->Name)}}</td>
                                                <td>{{trim($customer->PCode)}}</td>
                                                <td>{{trim($customer->PhoneStr)}}</td>
                                                <td style="width:77px">{{trim($customer->NameRec)}}</td>
                                                <td style="width:111px;">{{$customer->FactDate}}</td>
                                                <td style="width:166px">{{trim($customer->adminName)}}</td>
                                                <td><input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN.'_'.$customer->adminId.'_'.$customer->SerialNoHDS}}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                             </table> -->

                             <table>
  <thead>
    <tr>
      <th>#</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Username</th>
    </tr>
  </thead>
  <tbody>
    ...
  </tbody>
</table>


                             <div class="grid-today rounded-2">
                                <div class="today-item"> <span style="color:red; font-weight:bold;">   کامنت اختصاصی: </span> <span id="unAlarmLastComment"></span>  </div>
                            </div>
                        </div>

                    </div>
                    <div class="row contentFooter"> 
                        <div class="col-lg-12 text-start mt-2" id="alamButtonsHistoryDiv">
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getAlarmHistory('TODAY')"> امروز  : </button>
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getAlarmHistory('YESTERDAY')"> دیروز : </button>
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getAlarmHistory('LASTHUNDRED')"> صد تای آخر : 100</button>
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getAlarmHistory('ALLALARMS')"> همه : </button>
                        </div>
                        <div class="col-lg-12 text-start mt-2" id="noAlarmButtonsHistoryDiv" style="display:none">
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getUnAlarmHistory('TODAY')"> خرید امروز  : </button>
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getUnAlarmHistory('YESTERDAY')"> خرید دیروز : </button>
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getUnAlarmHistory('LASTHUNDRED')"> صد خرید آخر : 100</button>
                            <button type="button" class="btn btn-sm btn-primary loginReport" onclick="getUnAlarmHistory('ALLUNALARMS')"> همه : </button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    
    <!-- for takhsis change modal -->
    <div class="modal fade dragableModal" id="changeAdminModal" role="dialog"   data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header py-2 text-white" style="margin:0; border:none">
                            <button type="button" class="btn-close bg-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="exampleModalLongTitle"> انتقال مشتری از کاربر به کاربر  </h5>
                        </div>
                        <div class="modal-body pt-0">
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th> مشتری </th>
                                        <th> کاربر </th>
                                        <th>شماره تماس</th>
                                        <th style="display:none">انتخاب</th>
                                  </tr>
                                </thead>
                                <tbody id="customerToMoveBody" class="tableBody" style="height:160px !important;">

                                </tbody>
                            </table>
                                <input type="hidden" id="adminID" >
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کاربر </th>
                                        <th>نقش کاربر </th>
                                        <th>توضیحات</th>
                                        <th>انتخاب </th>
                                  </tr>
                                </thead>
                                <tbody id="selectKarbarToMove" class="tableBody">

                                </tbody>
                            </table>
                            <div class="col-lg-12 text-start">
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" id="cancelMoveKarbar"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                <button type="button" class="btn btn-danger btn-sm"  onclick="moveCustomerToAdmin()" > انتقال <i class="fa fa-sync"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end change modal -->

                <!-- for takhsis modal -->
    <div class="modal fade dragableModal" id="takhsisCustomerModal" role="dialog"   data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header py-2 text-white" style="margin:0; border:none">
                            <button type="button" class="btn-close bg-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="exampleModalLongTitle"> انتقال مشتری از کاربر به کاربر  </h5>
                        </div>
                        <div class="modal-body pt-0">
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th> مشتری </th>
                                        <th>شماره تماس</th>
                                        <th style="display:none">انتخاب</th>
                                  </tr>
                                </thead>
                                <tbody id="customerToTakhsisBody" class="tableBody" style="height:160px !important;">

                                </tbody>
                            </table>
                                <input type="hidden" id="adminID" >
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کاربر </th>
                                        <th>نقش کاربر </th>
                                        <th>توضیحات</th>
                                        <th>انتخاب </th>
                                  </tr>
                                </thead>
                                <tbody id="selectKarbarToTakhsis" class="tableBody">

                                </tbody>
                            </table>
                            <div class="col-lg-12 text-start">
                                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" id="cancelMoveKarbar"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                <button type="button" class="btn btn-danger btn-sm"  onclick="moveCustomerToAdmin()" > انتقال <i class="fa fa-sync"></i> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end change modal -->

    <div class="modal fade notScroll" id="customerDashboard" data-bs-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close btn-danger" style="background-color:red;" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="exampleModalLabel"> داشبورد </h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <span class="fw-bold fs-4"  id="dashboardTitle" style="display:none;"></span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 text-end">
                                <Button class="btn btn-sm buttonHover crmButtonColor float-end  mx-2" id="openAddCommentModal" type="button" value="" name="" > کامنت <i class="fas fa-comment fa-lg"> </i> </Button>
                                <form action="https://starfoods.ir/crmLogin" target="_blank"  method="get">
                                    <input type="text" id="customerSnLogin" style="display: none" name="psn" value="" />
                                    <input type="text"  style="display: none" name="otherName" value="{{trim(Session::get('username'))}}" />
                                    <Button class="btn btn-sm buttonHover crmButtonColor float-end" type="submit"> ورود جعلی  <i class="fas fa-sign-in fa-lg"> </i> </Button>
                                </form>
                            </div>
                        </div><hr>
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-outline">
                                                <label class="dashboardLabel form-label">کد</label>
                                                <input type="text" class="form-control form-control-sm noChange" id="customerCode" value="">
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-outline">
                                                <label class="dashboardLabel form-label">نام و نام خانوادگی</label>
                                                <input type="text" class="form-control form-control-sm noChange" id="customerName"  value="علی حسینی" >
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> تعداد فاکتور </label>
                                                <input type="text" class="form-control form-control-sm noChange" id="countFactor">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> شماره های تماس </label>
                                                <input class="form-control noChange" type="text" id="mobile1" >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label">نام کاربری</label>
                                                <input class="form-control noChange" type="text" id="username" >
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label">رمز کاربری</label>
                                                <input class="form-control noChange" type="text" id="password" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> آدرس </label>
                                                <input type="text" class="form-control form-control-sm noChange" id="customerAddress" value="آدرس">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="mb-3" style="width:350px;">
                                            <label for="exampleFormControlTextarea1" class="form-label fw-bold">یاداشت</label>
                                            <textarea class="form-control" id="customerProperty" onblur="saveCustomerCommentProperty(this)" rows="6"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                            <div class="col-sm-12" style="margin: 0; padding:0;">
                                <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                    <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                                    <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری شده </a></li>
                                    <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#userLoginInfo1"> کالاهای سبد خرید</a></li>
                                    <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerLoginInfo">ورود به سیستم</a></li>
                                    <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#returnedFactors1"> فاکتور های برگشت داده </a></li>
                                    <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments"> کامنت ها </a></li>
                                    <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#assesments"> نظرسنجی ها</a></li>
                                </ul>
                            </div>
                            <div class="c-checkout tab-content"   style="background-color:#f5f5f5; margin:0;padding:0.3%; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress"  style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
                                                <th>مشاهد   </th>
                                            </tr>
                                            </thead>
                                            <tbody  id="factorTable" class="tableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead class="tableHeader">
                                                <tr>
                                                    <th> ردیف</th>
                                                    <th>تاریخ</th>
                                                    <th> نام کالا</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody id="goodDetail" class="tableBody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row c-checkout rounded-3 tab-pane" id="userLoginInfo1" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                                <thead  class="tableHeader">
                                                <tr>
                                                    <th> ردیف</th>
                                                    <th>تاریخ</th>
                                                    <th> نام کالا</th>
                                                    <th>تعداد </th>
                                                    <th>فی</th>
                                                </tr>
                                                </thead>
                                                <tbody id="basketOrders" class="tableBody">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row c-checkout rounded-3 tab-pane" id="customerLoginInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                                <thead class="tableHeader">
                                                <tr>
                                                    <th> ردیف</th>
                                                    <th>تاریخ</th>
                                                    <th>نوع پلتفورم</th>
                                                    <th>مرورگر</th>
                                                </tr>
                                                </thead>
                                                <tbody id="customerLoginInfoBody" class="tableBody">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row c-checkout rounded-3 tab-pane" id="returnedFactors1"  style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                                <thead class="tableHeader">
                                                <tr>
                                                    <th> ردیف</th>
                                                    <th>تاریخ</th>
                                                    <th> نام راننده</th>
                                                    <th>مبلغ </th>
                                                </tr>
                                                </thead>
                                                <tbody id="returnedFactorsBody" class="tableBody">
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="c-checkout tab-pane" id="comments" style="margin:0; border-radius:10px 10px 2px 2px;">
                                    <div class="row c-checkout rounded-3 tab-pane active"  style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-sm">
                                                <thead class="tableHeader">
                                                <tr>
                                                    <th> ردیف</th>
                                                    <th>تاریخ</th>
                                                    <th> کامنت</th>
                                                    <th> کامنت بعدی</th>
                                                    <th> تاریخ بعدی </th>
                                                </tr>
                                                </thead>
                                                <tbody id="customerComments" class="tableBody">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="c-checkout tab-pane" id="assesments" style="margin:0; border-radius:10px 10px 2px 2px;">
                                    <div class="row c-checkout rounded-3 tab-pane active" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-sm" style="text-align:center;">
                                                <thead class="tableHeader">
                                                <tr>
                                                    <th> ردیف</th>
                                                    <th>تاریخ</th>
                                                    <th> کامنت</th>
                                                    <th> برخورد راننده</th>
                                                    <th> مشکل در بارگیری</th>
                                                    <th> کالاهای برگشتی</th>
                                                </tr>
                                                </thead>
                                                <tbody id="customerAssesments" class="tableBody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="modal" id="inactiveCustomer"  tabindex="-1" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel"> غیر فعالسازی </h6>
                </div>
                <form action="{{url('/inactiveCustomerAlarm')}}" id="inactiveCustomerForm" method="get">
                    <div class="modal-body">
                        <label class="dashboardLabel form-label">دلیل غیر فعالسازی</label>
                        <textarea class="form-control" required name="comment" id="" cols="30" rows="6"></textarea>
                        <input type="text" name="customerId" required style="display:none" id="inactiveId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" id="cancelinActive">بستن <i class="fa fa-xmark fa-lg"></i></button>
                        <button type="submit" class="btn btn-sm btn-primary" >ذخیره <i class="fa fa-save fa-lg"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="changeAlarm"  tabindex="-1" data-bs-backdrop="static" >
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> تغیر آلارم </h5>
                </div>
                <form action="{{url('/changeAlarm')}}" id="changeAlarmForm" method="get">
                    <div class="modal-body">
                        <label class="dashboardLabel form-label">دلیل</label>
                        <textarea class="form-control" required name="comment" id="" cols="30" rows="6"></textarea>
                        <label class="dashboardLabel form-label">تاریخ بعدی</label>
                        <input class="form-control" required placeholder="تاریخ بعدی" name="alarmDate" id="commentDate2">
                        <input class="form-control" style="display:none" id="factorAlarm" name="factorId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" id="cancelSetAlarm">بستن <i class="fa fa-xmark fa-lg"></i></button>
                        <button type="submit" class="btn btn-sm btn-primary" >ذخیره <i class="fa fa-save fa-lg"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="alarmHistoryModal"  tabindex="-1" data-bs-backdrop="static" >
        <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                <h5 class="modal-title" id="exampleModalLabel">گردش آلارم</h5>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="tableHeader">
                    <tr>
                        <th> ردیف</th>
                        <th>تاریخ</th>
                        <th> کامنت</th>
						 <th> </th>
                    </tr>
                    </thead>
                    <tbody class="tableBody" id="alarmHistoryBody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        </div>
    </div>
            <!-- Modal for reading factorDetails-->
    <div class="modal fade" id="viewFactorDetail" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog  modal-dialog   modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
					 <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
						<div class="grid-container">
							<div class="item1"> <b>تاریخ فاکتور   :  </b> <span id="factorDate">  </span> </div>
							<div class="item2"> <b> مشتری  :  </b> <span id="customerNameFactor"> </span>    </div>
							<div class="item3"> <b> آدرس  :  </b> <span id="customerAddressFactor1"> </span>   </div>
							<div class="item4"><span> تلفن :</span>    <span id="customerPhoneFactor"> </span></div>
							<div class="item5"><span> کاربر :  </span>   <span id="Admin"> </span></div>
							<div class="item6"><span>  شماره فاکتور :</span>  <span id="factorSnFactor">  </span></div>
						</div>
					
                            <div class="row">
                                <table id="strCusDataTable" class='table table-bordered table-striped table-sm' >
                                    <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کالا </th>
                                        <th>تعداد/مقدار</th>
                                        <th>واحد کالا</th>
                                        <th>فی (تومان)</th>
                                        <th>مبلغ (تومان)</th>
                                    </tr>
                                    </thead>
                                    <tbody class="tableBody" id="productList">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    {{-- modal for adding comments --}}
    <div class="modal" id="addComment" data-bs-backdrop="static" data-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" id="cancelCommentButton" data-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                    <h5 class="modal-title" id="exampleModalLabel"> افزودن کامنت </h5>
                </div>
            <div class="modal-body">
                <form action="{{url('/addComment')}}" id="addCommentForm" method="get">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="tahvilBar">نوع تماس</label>
                        <select class="form-select" name="callType">
                            <option value="1">موبایل</option>
                            <option value="2">تلفن ثابت</option>
                            <option value="3">واتساپ</option>
                            <option value="4">حضوری</option>
                        </select>
                        <input type="text" name="customerIdForComment" id="customerIdForComment" style="display:none;">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="tahvilBar" >کامنت </label>
                        <textarea class="form-control" style="position:relative" required name="firstComment" rows="3" ></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 fw-bold">
                        <label for="tahvilBar" >زمان تماس بعدی </label>
                            <input class="form-control" autocomplete="off" required name="nextDate" id="commentDate3">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="tahvilBar">کامنت بعدی</label>
                        <textarea class="form-control" name="secondComment" required rows="5" ></textarea>
                        <input class="form-control" type="text" style="display: none;" name="place" value="admins"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save"></i></button>
            </div>
        </form>
        </div>
        </div>
    </div>
    <div class="modal fade" id="customerDashboard" data-keyboard="false" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close btn-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel"> داشبورد </h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 coll-sm-12">
                            <span class="fw-bold fs-4"  id="dashboardTitle"></span>
                        </div>
                        <div class="col-lg-6 col-md-6 coll-sm-12">
                           <Button class="btn btn-sm buttonHover crmButtonColor float-end  mx-2" id="openAddCommentModal" type="button" value="" name="" > کامنت <i class="fas fa-comment fa-lg"> </i> </Button>
                         </div>
                    </div><hr>
                    <div class="row">
                       <div class="col-lg-8 col-md-8 col-sm-8">
                           <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 mb-2">
                                    <div class="form-outline" style="padding-bottom:1%">
                                        <label class="dashboardLabel form-label">کد</label>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerCode" value="">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-outline " style="padding-bottom:1%">
                                        <label class="dashboardLabel form-label">نام و نام خانوادگی</label>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerName"  value="علی حسینی" >
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> تعداد فاکتور </label>
                                        <input type="text" class="form-control form-control-sm noChange" id="countFactor">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label">  تلفن ثابت </label>
                                        <input class="form-control noChange" type="text" name="" >
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label">  تلفن همراه 1 </label>
                                        <input class="form-control noChange" type="text" id="mobile1" >
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label">  تلفن همراه 2 </label>
                                        <input class="form-control noChange" type="text" name="" >
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> آدرس </label>
                                        <input type="text" class="form-control form-control-sm noChange" id="customerAddress" value="آدرس">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <div class="mb-3" style="width:350px;">
                                    <label for="exampleFormControlTextarea1" class="form-label fw-bold">یاداشت  </label>
                                    <textarea class="form-control" id="customerProperty"  onblur="saveCustomerCommentProperty(this)" rows="6" ></textarea>
                                </div>
                            </div>
                       </div>
                    </div>

                    <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                        <div class="col-sm-8" style="margin: 0; padding:0;">
                            <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری کرده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#userLoginInfo"> کالاهای سبد خرید</a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#pictures"> فاکتور های برگشت داده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments"> کامنت ها </a></li>
                            </ul>
                        </div>
                        <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="dashbordTables factor table table-bordered table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
                                            </tr>
                                            </thead>
                                            <tbody  id="factorTable">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="dashbordTables buyiedKala table table-bordered table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                            </tr>
                                            </thead>
                                            <tbody id="goodDetail">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row c-checkout rounded-3 tab-pane" id="userLoginInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="dashbordTables basketKala table table-bordered table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody id="basketOrders">
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="c-checkout tab-pane" id="pictures" style="margin:0; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="dashboardLabel returnedFactor table table-bordered table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="c-checkout tab-pane" id="comments" style="margin:0; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="dashboardLabel comments table table-bordered table-striped table-sm">
                                            <thead>
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> کامنت</th>
                                                <th> کامنت بعدی</th>
                                                <th> تاریخ بعدی </th>
                                                <th> انتخاب </th>
                                            </tr>
                                            </thead>
                                            <tbody id="customerComments">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            <!-- Modal for reading comments-->
    <div class="modal fade" id="viewComment" tabindex="1" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">کامنت ها</h5>
                </div>
                <div class="modal-body">
                    <h3 id="readCustomerComment1"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
</main>


<script>

 $(function(){
    $(".resizableTable").resizableColumns();
  });

</script>

<script>
  $(function(){
    $("table").resizableColumns();
  });
</script>
@endsection
