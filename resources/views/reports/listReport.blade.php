@extends('layout')
@section('content')
<style>
    table th, td {
        font-size:14px;
    }
    .labelText{
        font-size:12px;
    }

.inActiveBtn{
    display:none;
}

.evcuatedCustomer {
    display:none;
}
.referencialTools {
    display:none;
}
.loginReport, .referencialReport, .inactiveReport {
    display:none;
    width:122px;
}
</style>

<div class="container-fluid containerDiv">
        <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> عملکرد مشتریان </legend>
                            <div class="form-check bg-gray">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerLoginReportRadio">
                                <label class="form-check-label me-4" for="assesPast">  گزارش ورود </label>
                            </div>
                            <div class="form-check bg-gray">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                <label class="form-check-label me-4" for="assesPast"> غیرفعال </label>
                            </div>
                            <div class="form-check bg-gray">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="evacuatedCustomerRadio">
                                <label class="form-check-label me-4" for="assesPast"> فاقد کاربر </label>
                            </div>
                            <div class="form-check bg-gray">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="referentialCustomerRadio">
                                <label class="form-check-label me-4" for="assesPast"> ارجاعی</label>
                            </div>

                            <span id="allCustomerStaff">
                                <div class="form-group col-sm-12 mb-1">
                                    <select class="form-select form-select-sm  " id="locationOrNot">
                                        <option value="0" hidden>موقعیت</option>
                                        <option value="1">همه</option>
                                        <option value="2">موقعیت دار </option>
                                        <option value="3">بدون موقعیت</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 mb-1">
                                    <select class="form-select form-select-sm " id="searchAllFactorOrNot">
                                        <option value="-1" hidden>فاکتور</option>
                                        <option value="0">همه</option>
                                        <option value="1">دارد</option>
                                        <option value="2">ندارد</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 mb-1">
                                    <select class="form-select form-select-sm " id="searchAllBasketOrNot">
                                        <option value="-1">وضعیت سبد</option>
                                        <option value="0">همه</option>
                                        <option value="2"> سبد پر </option>
                                        <option value="1">سبد خالی </option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-12 mb-1">
                                    <select class="form-select form-select-sm " id="searchByAdmin">
                                        <option value="0" hidden> کاربر</option>
                                        <option value="0"> همه</option>
                                        @foreach($amdins as $admin)
                                        <option value="{{$admin->id}}"> {{trim($admin->name)}} {{trim($admin->lastName)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </span>

                        <!-- related to visitor -->
                        <div class="row" id="staffVisitor" style="display:none">
                            
                            <div class="col-lg-12 mb-1 mt-3">
                                <div class="form-group">
                                    <label class="labelText" for="visitorPlatform">پلتفورم</label>
                                    <select type="text" class="form-control form-control-sm" id="visitorPlatform">
                                        <option value='0'>همه</option>
                                        <option value='Android'>اندروید</option>
                                        <option value='iOS'>ios</option>
                                        <option value='Windows'>windows</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-1">
                                <div class="form-group">
                                    <label class="labelText" for="LoginDate1">از تاریخ</label>
                                    <input type="text" placeholder="تاریخ" id="LoginDate1" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-1">
                                <div class="form-group">
                                    <label class="labelText" for="LoginDate2">الی تاریخ</label>
                                    <input type="text" placeholder="تاریخ" id="LoginDate2" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-1">
                                <div class="form-group">
                                    <label class="labelText" for="LoginDate2">تعدا ورود از:</label>
                                    <input type="number" placeholder="تعداد" id="LoginFrom" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-1">
                                <div class="form-group">
                                    <label class="labelText" for="LoginDate2">تعداد ورود تا:</label>
                                    <input type="number" placeholder="تعداد" id="LoginTo" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-1">
                                <div class="form-group">
                                    <label class="labelText" for="countSameTime">تعداد همزمان هر مشتری از:</label>
                                    <input type="number" placeholder="تعداد" id="countSameTime" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                    <!-- Inactive Customer  -->
                    <div class="row" id="inActiveTools" style="display:none">
                       
                        <div class="form-group col-sm-12 mb-1 mt-3">
                            <select class="form-select form-select-sm" id="searchInActiveByLocation">
                                <option value="-1">  کاربر غیر فعال کننده   </option>
                                <option value="0"> همه </option>
                                <option value="1"> خانم ناصری   </option>
                                <option value="2"> شاه رخ احمدپور </option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 mb-1 mt-3">
                            <select class="form-select form-select-sm" id="searchInActiveByLocation">
                                <option value="-1">  خرید   </option>
                                <option value="0"> همه </option>
                                <option value="1"> خریده کرده </option>
                                <option value="2"> خرید نکرده </option>
                            </select>
                        </div>
                        
                    </div>

                    <!-- evacuated Customers tools -->
                     <div class="row evcuatedCustomer">
                           
                            <div class="form-group col-sm-12 mb-1">
                                <select class="form-select form-select-sm" id="buyOrNot">
                                    <option value="-1"> خرید  </option>
                                    <option value="2"> دارد </option>
                                    <option value="1"> ندارد </option>
                                </select>
                            </div>
                            <div class="form-group col-sm-12 mb-1">
                                <input type="text" name=""  placeholder="از تاریخ" class="form-control form-control-sm" id="searchEmptyFirstDate">
                            </div>
                            <div class="form-group col-sm-12 mb-1">
                                <input type="text" name=""  placeholder="تا تاریخ" class="form-control form-control-sm" id="searchEmptySecondDate">
                            </div>
                        </div>

                 <!-- referencial tools  -->
                        <div class="row referencialTools">
                            
                            <div class="form-group col-sm-12 mb-2">
                                <input type="text" name="" placeholder="ازتاریخ" class="form-control form-control-sm" id="firstDateReturned">
                            </div>
                            
                            <div class="form-group col-sm-12 mb-2">
                                <input type="text" name="" placeholder="تا تاریخ" class="form-control form-control-sm" id="secondDateReturned">
                            </div>
                            <div class="form-group col-sm-12 mb-1">
                                <select class="form-select form-select-sm" id="buyOrNot">
                                    <option value="-1"> خرید  </option>
                                    <option value="2"> دارد </option>
                                    <option value="1"> ندارد </option>
                                </select>
                            </div>
                
                            <div class="form-group col-sm-12 mb-2">
                                <select class="form-select form-select-sm" id="searchByReturner">
                                    <option value="0">کاربر ارجاع دهنده</option>
                                    @foreach ($returners as $returner)
                                        <option value="{{$returner->id}}">{{$returner->name.' '.$returner->lastName}}</option>  
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> 
                        <div class="col-sm-8 text-end">
                            <div class="row">
                                <div class="form-group col-sm-2 mt-2 px-1">
                                    <input type="text" name="" placeholder="جستجو" class="form-control form-control-sm " id="searchAllName">
                                </div>
                                <div class="form-group col-sm-2 mt-2 px-1">
                                    <select class="form-select form-select-sm " id="searchByCity">
                                       <option value="0" hidden> شهر</option>
                                       <option value="0"> همه</option>
                                        @foreach($cities as $city)
                                        <option value="{{$city->SnMNM}}"> {{trim($city->NameRec)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-2 mt-2 px-1">
                                    <select class="form-select form-select-sm " id="searchByMantagheh">
                                    <option value="0" hidden>منطقه</option>
                                    <option value="0">همه</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-2 mt-2 px-1">
                                    <select class="form-select form-select-sm" id="orderInactiveCustomers">
                                        <option value="-1">مرتب سازی</option>
                                        <option value="2"> کد </option>
                                        <option value="3">اسم</option>
                                        <option value="1">همراه </option>
                                        <option value="1"> تاریخ  </option>
                                        <option value="1"> کاربر </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 text-start">
                              <!-- Button trigger modal -->
                            <input type="text" id="customerSn" style="display: none" name="customerSn" value="" />
                            <button class='enableBtn btn btn-sm btn-primary text-warning' type="button" id='openDashboard' disabled> داشبورد <i class="fal fa-dashboard"></i></button>
                            <button class='enableBtn btn btn-sm btn-primary text-warning' id="takhsisButton" disabled>تخصیص کاربر  <i class="fal fa-tasks fa-lg"> </i> </button>
                            <!-- evacuated customer buttons -->
                            <button class='enableBtn btn btn-primary btn-sm text-warning evcuatedCustomer' disabled id="inactiveButton">غیر فعال کردن <i class="fal fa-ban fa-lg"> </i> </button>
                            <input type="text" id="customerSn"  value="" style="display: none;" />
                            <input type="text" id="adminSn"  value="" style="display: none;"/>
                            <!-- referencial customer buttons -->
                        
                            <button class='enableBtn btn btn-primary btn-sm text-warning referencialTools' disabled id="returnComment">علت ارجاع<i class="fal fa-eye fa-lg"> </i> </button>
                        </div>
                    </div>
                    <div class="row mainContent">
                        <table class='table table-bordered table-striped table-hover' id="customerActionTable">
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th style="width:333px">اسم</th>
                                    <th style="width:177px">همراه</th>
                                    <th>تاریخ فاکتور</th>
                                    <th>کاربر</th>
                                    <th style="width:66px"> انتخاب</th>
                                   
                                </tr>
                            </thead>
                            <tbody class="select-highlight tableBody" id="allCustomerReportyBody">
                                @forelse ($customers as $customer)
                                <tr>
                                    <td >{{$loop->iteration}}</td>
                                    <td style="width:333px">{{trim($customer->Name)}}</td>
                                    <td style="width:177px">{{trim($customer->hamrah)}}</td>
                                    <td >{{trim($customer->lastDate)}}</td>
                                    <td >{{trim($customer->adminName).' '.trim($customer->lastName)}}</td>
                                    <td  style="width:66px"> <input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN}}"></td>
                                </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>


                   <div class="c-checkout container-fluid" id="loginTosystemReport" style="background-image: linear-gradient(to right, #ffffff,#3fa7ef,#3fa7ef); margin:0.2% 0; margin-bottom:0; padding:0.5% !important; border-radius:10px 10px 2px 2px; display:none;">
                    <div class="col-sm-6" style="margin: 0; padding:0;">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                            <li><a class="active" data-toggle="tab" style="color:black;"  href="#karbarLogin">  گزارش ورود به سیستم (اشخاص)  </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#custAddress"> گزارش ورود به سیستم (نموداری) </a></li>
                        </ul>
                    </div>
                    <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
                      <div class="row c-checkout rounded-3 tab-pane active" id="karbarLogin" style="background-color:#f5f5f5; width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                         <div class="row " style="padding:0 1% 1% 0%">
                             <div class="col-sm-12 ">
                                <table class='table table-bordered table-striped table-sm'>
                                    <thead class="tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th> کاربر مربوطه </th>
                                            <th>آخرین ورود</th>
                                            <th style="width:244px"> نام مشتری</th>
                                            <th>سیستم </th>
                                            <th>مرورگر</th>
                                            <th style="width:77px">تعداد ورود </th>
                                            <th>  همزمان</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listVisitorBody" class="tableBody">
                                        @foreach($visitors as $visitor)
                                        <tr>
                                            <td >{{$loop->iteration}}</td>
                                            <td > </td>
                                            <td >{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($visitor->lastVisit))->format("Y/m/d H:i:s")}}</td>
                                            <td style="width:244px">{{$visitor->Name}}</td>
                                            <td >{{$visitor->platform}}</td>
                                            <td >{{$visitor->browser}}</td>
                                            <td>{{$visitor->countLogin}}</td>
                                            <td>{{$visitor->countSameTime}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            </div>
                        
                         <div class="row c-checkout rounded-3 tab-pane" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                            <div class="col-sm-12">
                                <div class="row " style="width:98%; padding:0 1% 2% 0%">
                                   <span class="card p-4">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="form-group">
                                                   <input type="date" class="form-control">
                                                </div>
                                            </div>
                                        </div> <br>
                                        <div class="col-lg-12 col-md-12 col-sm-12 card">
                                             <div id="chartdiv"></div>
                                        </div>
                                    </span>
                                 </div>
                              </div>
                          </div>
                       </div>
                    </div>

                 <!-- in active customer table -->
                 <div class="col-lg-12"  id="inActiveCustomerTable" style="display:none;">
                     <table class='table table-bordered table-striped px-0'>
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th>اسم</th>
                                    <th style="width:99px"> همراه</th>
                                    <th style="width:133px">ت-غیرفعال</th>
                                    <th style="width:133px">ک-غیرفعال</th>
                                    <th> کامنت  </th>
                                    <th>انتخاب</th>
                                </tr>
                            </thead>
                            <tbody class="select-highlight tableBody" id="inactiveCustomerBody">
                                @foreach ($inActiveCustomers as $customer)

                                <tr onclick="setInActiveCustomerStuff(this)">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{trim($customer->CustomerName)}}</td>
                                    <td  style="width:99px">{{trim($customer->hamrah)}}</td>
                                    <td style="width:133px">{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($customer->TimeStamp))->format('Y/m/d H:i:s')}}</td>
                                    <td style="width:133px">{{trim($customer->name).' '.trim($customer->lastName)}}</td>
                                    <td  style="font-size:12px;">{{trim($customer->comment)}}</td>
                                    <td><input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN}}"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="grid-today rounded-2">
                                <div class="today-item"> <span style="color:red; font-weight:bold;">  تاریخ آخرین فاکتور : </span> <span id="loginTimeToday"></span>  </div>
                               
                        </div>
                    </div>

                 <!-- evacuated customer table  -->
                    <div class="col-lg-12 evcuatedCustomer">
                        <table class='table table-bordered table-striped table-sm px-0'>
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th>اسم</th>
                                    <th style="width:66px;">کد</th>
                                    <th style="width:333px;">آدرس  </th>
                                    <th>همراه</th>
                                    <th>انتخاب</th>
                                </tr>
                            </thead>
                            <tbody class="select-highlight tableBody" id="returnedCustomerList">
                                @foreach ($evacuatedCustomers as $customer)
                                    <tr onclick="returnedCustomerStuff(this)">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$customer->Name}}</td>
                                        <td style="width:66px;">{{$customer->PCode}}</td>
                                        <td style="width:333px;">{{$customer->peopeladdress}}</td>
                                        <td>{{$customer->PhoneStr}}</td>
                                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="{{$customer->PSN}}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            <div class="grid-today rounded-2">
                                <div class="today-item"> <span style="color:red; font-weight:bold;">  تاریخ آخرین فاکتور : </span> <span id="loginTimeToday"></span>  </div>
                            </div>
                        </div>

                        <!-- referencial customer table -->
                        <div class="col-lg-12 referencialTools">
                           <table class='table table-bordered table-striped table-sm px-0'>
                               <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th style="width:188px;">اسم</th>
                                        <th style="width:144px;">همراه</th>
                                        <th style="width:133px;">ارجاع دهنده</th>
                                        <th style="width:88px;">تاریخ ارجاع</th>
                                        <th>انتخاب</th>
                                    </tr>
                                </thead>
                                <tbody class="select-highlight tableBody" id="returnedCustomerList">
                                    @foreach ($referencialCustomers as $customer)
                                        <tr onclick="returnedCustomerStuff(this)">
                                            <td>{{$loop->iteration}}</td>
                                            <td style="width:188px; font-size:12px">{{$customer->Name}}</td>
                                       
                                            <td style="width:144px;">{{$customer->hamrah}}</td>
                                            <td style="width:133px;">{{$customer->adminName.' '.$customer->adminLastName}}</td>
                                            <td style="width:88px;">{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($customer->returnDate))->format('Y/m/d')}}</td>
                                            <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="{{$customer->PSN.'_'.$customer->adminId}}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                            <div class="grid-today rounded-2">
                                <div class="today-item"> <span style="color:red; font-weight:bold;">  کامنت : </span> <span id="loginTimeToday"></span>  </div>
                                <div class="today-item"> <span style="color:red; font-weight:bold;">  تاریخ آخرین خرید : </span> <span id="loginTimeToday"></span>  </div>
                                
                            </div>
                       </div>
                      </div>
                       <div class="row contentFooter"> 
                            <div class="col-lg-12 text-start mt-2">
                                <button type="button" class="btn btn-sm btn-primary loginReport"> امروز  : </button>
                                <button type="button" class="btn btn-sm btn-primary loginReport"> دیروز : </button>
                                <button type="button" class="btn btn-sm btn-primary loginReport"> صد تای آخر : 100</button>
                                <button type="button" class="btn btn-sm btn-primary loginReport"> همه : </button>

                                <button type="button" class="btn btn-sm btn-primary referencialReport"> امروز  : </button>
                                <button type="button" class="btn btn-sm btn-primary referencialReport"> دیروز : </button>
                                <button type="button" class="btn btn-sm btn-primary referencialReport"> صد تای آخر : 100 </button>
                                <button type="button" class="btn btn-sm btn-primary referencialReport"> همه : </button>

                                <button type="button" class="btn btn-sm btn-primary inactiveReport"> امروز  : </button>
                                <button type="button" class="btn btn-sm btn-primary inactiveReport"> دیروز : </button>
                                <button type="button" class="btn btn-sm btn-primary inactiveReport"> صد تای آخر : 100 </button>
                                <button type="button" class="btn btn-sm btn-primary inactiveReport"> همه : </button>
                           </div>
                    </div>
             </div>
          </div>
      </div>




<div class="modal fade dragModal" id="reportCustomerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2 text-white">
                <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                <h5 class="modal-title">نظر سنجی  </h5>
            </div>
            <div class="modal-body"  style="background-color:#d2e9ff;">
                   <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <span class="fw-bold fs-4"  id="dashboardTitle"></span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 text-start">
                            <form action="https://starfoods.ir/crmLogin" target="_blank"  method="get" style="background-color:transparent; box-shadow:none;">
                                <input type="text" id="customerSn" style="display: none" name="psn" value="" />
                                <input type="text"  style="display:none;" name="otherName" value="{{trim(Session::get('username'))}}" />
                                    <button class="btn btn-primary buttonHover btn-sm" type="submit"> ورود جعلی  <i class="fas fa-sign-in fa-lg"> </i> </button>
                            </form>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 mb-1">
										<div class="input-group input-group-sm">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> کد  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="customerCode" disabled>
										</div>
                                    </div>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
										<div class="input-group input-group-sm">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> نام و نام خانوادگی  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="customerName" disabled>
										</div>
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3">
										<div class="input-group input-group-sm">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد فاکتور  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="countFactor" disabled>
										</div>
                                    </div>
                                </div>
								
                                <div class="row mb-1">
                                    <div class="col-lg-5 col-md-5 col-sm-5">
										<div class="input-group input-group-sm">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> شماره های تماس  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="mobile1" disabled>
										</div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
										<div class="input-group input-group-sm">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> رمز کاربری  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="password" disabled>
										</div>
										</div>
									<div class="col-lg-4 col-md-4 col-sm-4">
										<div class="input-group input-group-sm">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> کاربر مربوطه </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="admin" disabled>
										</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
										<div class="input-group input-group-sm mb-3">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> آدرس </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerAddress" disabled>
										</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-12">
								<div class="input-group">
								  <span class="input-group-text"> یاداشت </span>
								  <textarea class="form-control" aria-label="With textarea" id="customerProperty" rows="4" ></textarea>
								</div>
                            </div>
                        </div>
              
                <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                    <div class="col-sm-8" style="margin: 0; padding:0;">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                            <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress1"> فاکتور های ارسال شده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری کرده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerCard"> کالاهای سبد خرید</a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerRetunFactor"> فاکتور های برگشت داده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments">  نظرها </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerAssesmentAdmin">نظرسنجی ها</a></li>
                        </ul>
                    </div>
                    <div class="c-checkout tab-content talbeDashboardTop">
                            <div class="row c-checkout rounded-3 tab-pane active  tableDashboardMiddle" id="custAddress1">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
                                                <th>مشاهده </th>
                                            </tr>
                                        </thead>
                                        <tbody id="factorTable" class="tableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="moRagiInfo">
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام کالا</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="goodDetail" class="tableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="customerCard">
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="tableHeader">
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

                        <div class="c-checkout tab-pane talbeDashboardTop" id="customerRetunFactor" >
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام راننده</th>
                                            <th>مبلغ </th>
                                        </tr>
                                        </thead>
                                        <tbody id="returnedFactorTable" class="tableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="c-checkout tab-pane talbeDashboardTop" id="comments">
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
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
                        <div class="c-checkout tab-pane talbeDashboardTop" id="customerAssesmentAdmin">
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> کامنت</th>
                                            <th> برخورد راننده</th>
                                            <th> مشکل در بارگیری</th>
                                            <th> کالاهای برگشتی</th>
                                            <th> انتخاب </th>
                                        </tr>
                                        </thead>
                                        <tbody id="karbarActionAssesment" class="tableBody">

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
           <!-- Modal for reading factorDetails-->
    <div class="modal fade" id="viewFactorDetail" tabindex="-1"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog  modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h6 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h6>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                           <div class="row rounded-top" style=" border:1px solid #dee2e6; padding:10px">
                                <h5>فاکتور فروش </h5>
								<div class="grid-container">
									<div class="item1"> <b style="color:red; font-weight:bold;">تاریخ فاکتور   :  </b> <span id="factorDate">  </span> </div>
									<div class="item2"> <b style="color:red; font-weight:bold;"> مشتری  :  </b> <span id="customerNameFactor"> </span>    </div>
									<div class="item3"> <b style="color:red; font-weight:bold;"> آدرس  :  </b> <span id="customerAddressFactor1"> </span>   </div>
									<div class="item4"> <b style="color:red; font-weight:bold;"> تلفن :</b>    <span id="customerPhoneFactor"> </span></div>
									<div class="item5"> <b style="color:red; font-weight:bold;"> کاربر :  </b>   <span id="Admin"> </span></div>
									<div class="item6"> <b style="color:red; font-weight:bold;">  شماره فاکتور :</b>  <span id="factorSnFactor">  </span></div>
								</div>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable" class='table table-bordered table-striped table-sm' style="background-color:#dee2e6">
                                    <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کالا </th>
                                        <th >تعداد/مقدار</th>
                                        <th>واحد کالا</th>
                                        <th>فی (تومان)</th>
                                        <th>مبلغ (تومان)</th>
                                    </tr>
                                    </thead>
                                    <tbody id="productList" class="tableBody">

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

        <!-- Modal for reading comments-->
        <div class="modal fade" id="viewComment" tabindex="1"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">کامنت ها</h5>
                </div>
                <div class="modal-body">
                    <h3 id="readCustomerComment1"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="customerDashboard" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-xl">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" style="background-color:red;" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel"> داشبورد : <span class="fw-bold fs-6" id="dashboardTitle"></span> </h5>
                </div>
                <div class="modal-body">
					        <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">کد</span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="customerCode" value="" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">نام و نام خانوادگی </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerName"  disabled>
											</div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد فاکتور </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="countFactor" disabled>
											</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تلفن همراه 1 </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="mobile1" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> نام کاربری  </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="username" disabled>
											</div>
                                        </div>
										<div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">  رمز کاربری   </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="username" disabled>
											</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="input-group input-group-sm mb-2">
                                                <span class="input-group-text" id="inputGroup-sizing-sm"> آدرس  </span>
                                                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerAddress" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div style="width:300px;">
                                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">یاداشت  </label>
                                        <textarea class="form-control" id="customerProperty"  onblur="saveCustomerCommentProperty(this)" rows="3" ></textarea>
                                    </div>
                                </div>
                            </div> <hr>
					
					
                    <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                        <div class="col-sm-8" style="margin: 0; padding:0;">
                            <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری کرده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#shoppingBascketGoods"> کالاهای سبد خرید</a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#returnedFactors"> فاکتور های برگشت داده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments"> کامنت ها </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#assesments1"> نظر سنجی</a></li>
                            </ul>
                        </div>
                        <div class="c-checkout tab-content talbeDashboardTop">
                                <div class="row c-checkout rounded-3 tab-pane active tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
                                                <th>مشاهده </th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody" id="factorTable">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="moRagiInfo" >
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress" >
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
                                            <tbody class="tableBody" id="goodDetail">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="shoppingBascketGoods">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody" id="basketOrders">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane talbeDashboardTop" id="returnedFactors">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody">
                                            <tr>
                                                <td> 1 </td>
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

                            <div class="c-checkout tab-pane talbeDashboardTop" id="comments">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> کامنت</th>
                                                <th> کامنت بعدی</th>
                                                <th> تاریخ بعدی </th>
                                                <th> انتخاب </th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody" id="customerComments">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="c-checkout tab-pane talbeDashboardTop" id="assesments1">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm" >
                                            <thead class="tableHeader" >
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> کامنت</th>
                                                <th> برخورد راننده</th>
                                                <th> مشکل در بارگیری</th>
                                                <th> کالاهای برگشتی</th>
                                                <th> انتخاب </th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody" id="customerAssesments">

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
            <!-- Modal for reading factor details-->
    <div class="modal fade" id="viewFactorDetail" tabindex="-1"  data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog   modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row" style=" border:1px solid #dee2e6; padding:10px">
                                <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
                                <div class="col-sm-6">
                                    <table class="crmDataTable table table-borderless" style="background-color:#dee2e6">
                                        <tbody>
                                        <tr>
                                            <td>تاریخ فاکتور:</td>
                                            <td id="factorDate"></td>
                                        </tr>
                                        <tr>
                                            <td>مشتری:</td>
                                            <td id="customerNameFactor"></td>
                                        </tr>
                                        <tr>
                                            <td>آدرس:</td>
                                            <td id="customerAddressFactor"> </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table table-borderless" style="background-color:#dee2e6">
                                        <tbody>
                                            <tr>
                                                <td>تلفن :</td>
                                                <td id="customerPhoneFactor"></td>
                                            </tr>
                                        <tr>
                                            <td>کاربر :</td>
                                            <td >3</td>
                                        </tr>
                                        <tr>
                                            <td>شماره فاکتور :</td>
                                            <td id="factorSnFactor"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable"  class='crmDataTable dashbordTables table table-bordered table-striped table-sm' style="background-color:#dee2e6">
                                    <thead>
                                    <tr>
                                        <th scope="col">ردیف</th>
                                        <th scope="col">نام کالا </th>
                                        <th scope="col">تعداد/مقدار</th>
                                        <th scope="col">واحد کالا</th>
                                        <th scope="col">فی (تومان)</th>
                                        <th scope="col">مبلغ (تومان)</th>
                                    </tr>
                                    </thead>
                                    <tbody id="productList">

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

    <div class="modal fade" id="takhsesKarbar" tabindex="-1"  data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2 text-white">
            <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                <h6 class="modal-title"> تخصیص </h6>
            </div>
            <div class="modal-body" id="readCustomerComment">
                <div class="col-sm-12 " style="padding:0; padding-left:25px;  margin-top: 0;">

                    @if(isset($evacuatedCustomers))
					<h5> تخصیص به کاربر دیگر</h5>

                    <table class="crmDataTable table table-bordered table-hover table-sm" id="tableGroupList">
                        <thead>
                            <tr>
                                <th>ردیف</th>
                                <th>نام کاربر</th>
                                <th>نقش کاربری</th>
                                <th>فعال</th>
                            </tr>
                        </thead>
                        <tbody class="c-checkout" id="mainGroupList" style="max-height: 350px;">
                            @foreach ($admins as $admin)
                                
                            <tr onclick="setAdminStuff(this)">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                    <td>{{trim($admin->adminType)}}</td>
                                    <td>
                                        <input class="mainGroupId" type="radio" name="AdminId" value="{{$admin->id}}">
                                    </td>
                                </tr>
                            @endforeach
                        
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">انصراف <i class="fa fa-xmark"></i></button>
            <button type="button" onclick="activateCustomer()" class="btn btn-sm btn-primary">ذخیره <i class="fa fa-save"></i></button>
            </div>
        </div>


        </div>
    </div>
            {{-- modal for reading comments --}}
            <div class="modal fade" id="inactiveReadingComment" data-bs-backdrop="static" data-keyboard="false" aria-labelledby="staticBackdropLabel">
                <div class="modal-dialog modal-dialog-scrollable ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    <div class="modal-body">
                        <p>کامنت غیر فعالی </p>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="tahvilBar">کامنت مدیر</label>
                                <textarea class="form-control" rows="5" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" onclick="deleteConfirm()">بستن</button>
                            <button type="button" class="btn btn-info btn-sm crmButtonColor">ذخیره <i class="fa fa-save"> </i> </button>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal fade" id="viewComment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    سلام
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
                    </div>
                </div>
                </div>
            </div>

    <!-- evacuated customer modals -->
    
    <div class="modal fade dragableModal" id="customerDashboard1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title">  مشتریان تخلیه شده </h5>
                </div>
                <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">کد</span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerCode" value="" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">نام و نام خانوادگی </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="customerName" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
											
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد فاکتور </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="countFactor" disabled>
											</div>
                                        </div>
                                    </div>
                                    <div class="row">
										 <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
												 <span class="input-group-text" id="inputGroup-sizing-sm">تلفن ثابت </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
										</div>
									 </div>
										
                                        <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تلفن همراه 1 </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="mobile1" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تلفن همراه 2 </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
											</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
										<div class="input-group input-group-sm mb-2">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> آدرس  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerAddress" disabled>
										</div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div style="width:300px;">
                                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">یاداشت  </label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>

                    <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                        <div class="col-sm-8" style="margin: 0; padding:0;">
                            <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری کرده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#referedCard"> کالاهای سبد خرید</a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#referedReturnFactor"> فاکتور های برگشت داده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments"> کامنت ها </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#assesments3"> نظر سنجی ها</a></li>
                            </ul>
                        </div>
                        <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0;  padding:0.3%; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
												 <th> مشاهده </th>
                                            </tr>
                                            </thead>
                                            <tbody id="factorTable" class="tableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody id="goodDetail" class="tableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row c-checkout rounded-3 tab-pane" id="referedCard" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody id="basketOrders" class="tableBody">
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane" id="referedReturnFactor" style="margin:0; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                           <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody">
                                          
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane" id="comments" style="margin:0; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="dashbordTables comments crmDataTable table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> کامنت</th>
                                                <th> کامنت بعدی</th>
                                                <th> تاریخ بعدی </th>
                                                <th> انتخاب </th>
                                            </tr>
                                            </thead>
                                            <tbody id="customerComments" class="tableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="c-checkout tab-pane" id="assesments3" style="margin:0; border-radius:10px 10px 2px 2px;">
                                <div class="row c-checkout rounded-3 tab-pane active" id="custAddress" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> کامنت</th>
                                                <th> برخورد راننده</th>
                                                <th> مشکل در بارگیری</th>
                                                <th> کالاهای برگشتی</th>
                                                <th> انتخاب </th>
                                            </tr>
                                            </thead>
                                            <tbody id="customerAssesments" class="classBody">
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

    <div class="modal fade dragableModal" id="takhsesKarbar" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header py-2 text-white">
            <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                <h5 class="modal-title"> تخصیص </h5>
            </div>
            <div class="modal-body" id="readCustomerComment">
                    @if(isset($customer))
                     <h3> تخصیص ({{$customer->Name}}) به کاربر دیگر</h3>
                    <table class="table table-bordered table-hover table-sm " id="tableGroupList">
                        <thead class="tableHeader">
                            <tr>
                                <th>ردیف</th>
                                <th>نام کاربر</th>
                                <th>نقش کاربری</th>
                                <th>فعال</th>
                            </tr>
                        </thead>
                        <tbody class="tableBody" id="mainGroupList">
                            @foreach ($admins as $admin)
                                <tr onclick="setAdminStuff(this)">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$admin->name." ".$admin->lastName}}</td>
                                    <td>{{$admin->adminType}}</td>
                                    <td>
                                        <input class="mainGroupId" type="radio" name="AdminId" value="{{$admin->id}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
              </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">انصراف <i class="fa fa-xmark"></i></button>
            <button type="button" onclick="takhsisCustomer()" class="btn btn-primary">ذخیره <i class="fa fa-save"></i></button>
            </div>
        </div>
        </div>
    </div>
            <!-- modal of inactive customer -->
            <div class="modal fade dragableModal" id="inactiveCustomer" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" >
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2 text-white">
                      <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                       <h5 class="modal-title" id="exampleModalLabel"> غیر فعالسازی </h5>
                    </div>
                    <form action="{{url('/inactiveCustomer')}}" id="inactiveCustomerForm" method="get">
                    <div class="modal-body">
                        <label for="">دلیل غیر فعالسازی</label>
                    <textarea class="form-control" name="comment" id="" cols="30" rows="4"></textarea>
                    <input type="hidden" name="customerId" id="inactiveId">
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">بستن <i class="fa fa-xmark fa-lg"></i></button>
                    <button type="submit" class="btn btn-sm btn-success" >ذخیره <i class="fa fa-save fa-lg"></i></button>
                    </div>
                </form>
                </div>
                </div>
            </div>





            
    <div class="modal fade" id="customerDashboard2" data-bs-keyboard="false" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-xl">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                    <h5 class="modal-title"> ارجاعات </h5>
                </div>

                <div class="modal-body">
					
					           <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">کد</span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerCode" value="" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm">نام و نام خانوادگی </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="customerName" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3">
											
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تعداد فاکتور </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"  id="countFactor" disabled>
											</div>
                                        </div>
                                    </div>
                                    <div class="row">
										 <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
												 <span class="input-group-text" id="inputGroup-sizing-sm">تلفن ثابت </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
										</div>
									 </div>
										
                                        <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تلفن همراه 1 </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="mobile1" disabled>
											</div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
											<div class="input-group input-group-sm mb-2">
											  <span class="input-group-text" id="inputGroup-sizing-sm"> تلفن همراه 2 </span>
											  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
											</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
										<div class="input-group input-group-sm mb-2">
										  <span class="input-group-text" id="inputGroup-sizing-sm"> آدرس  </span>
										  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" id="customerAddress" disabled>
										</div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    <div style="width:300px;">
                                        <label for="exampleFormControlTextarea1" class="form-label fw-bold">یاداشت  </label>
                                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" ></textarea>
                                    </div>
                                </div>
                            </div> <hr>
					
					
					
                          
                    <div class="c-checkout container" style="background-color:#c5c5c5; padding:0.5% !important; border-radius:10px 10px 2px 2px;">
                    <div class="col-sm-8" style="margin: 0; padding:0;">
                            <ul class="header-list nav nav-tabs" data-tabs="tabs" style="margin: 0; padding:0;">
                                <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری کرده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#ShoppingBasckets"> کالاهای سبد خرید</a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#referedReturnFactor"> فاکتور های برگشت داده </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments"> کامنت ها </a></li>
                                <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#assesments2"> نظر سنجی ها</a></li>
                            </ul>
                        </div>
                        <div class="c-checkout tab-content talbeDashboardTop">
                                <div class="row c-checkout rounded-3 tab-pane active tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr class="theadTr">
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام راننده</th>
                                                <th>مبلغ </th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody  id="factorTable" class="tableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="moRagiInfo">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead  class="tableHeader">
                                            <tr class="theadTr">
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody id="goodDetail" class="tableBody">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="ShoppingBasckets">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm" >
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody id="basketOrders" class="tableBody">
                                           
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane talbeDashboardTop" id="referedReturnFactor">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr class="theadTr">
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> نام کالا</th>
                                                <th>تعداد </th>
                                                <th>فی</th>
                                            </tr>
                                            </thead>
                                            <tbody class="tableBody">
                                            <tr>
                                                <td> 1 </td>
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

                            <div class="c-checkout tab-pane talbeDashboardTop" id="comments">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr class="thead">
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

                            <div class="c-checkout tab-pane talbeDashboardTop" id="assesments2">
                                <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-striped table-sm">
                                            <thead class="tableHeader">
                                            <tr>
                                                <th> ردیف</th>
                                                <th>تاریخ</th>
                                                <th> کامنت</th>
                                                <th> برخورد راننده</th>
                                                <th> مشکل در بارگیری</th>
                                                <th> کالاهای برگشتی</th>
                                                <th> انتخاب </th>
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

    <div class="modal fade dragableModal" id="takhsesKarbar" tabindex="-1" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2 text-white">
            <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                <h5 class="modal-title"> تخصیص </h5>
            </div>
            <div class="modal-body" id="readCustomerComment">
                <div class="col-sm-12 " style="padding:0; padding-left:25px;  margin-top: 0;">
                    @if(isset($customer))
                    <div class="card px-3"> <h3> تخصیص ({{$customer->Name}}) به کاربر دیگر</h3></div>
                    <table class="table table-bordered table-hover" id="tableGroupList">
                        <thead class="tableHeader">
                            <tr>
                                <th>ردیف</th>
                                <th>نام کاربر</th>
                                <th>نقش کاربری</th>
                                <th>فعال</th>
                            </tr>
                        </thead>
                        <tbody id="mainGroupList" class="tableBody">
                            @foreach ($admins as $admin)
                                <tr onclick="setAdminStuff(this)">
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$admin->name." ".$admin->lastName}}</td>
                                    <td>{{$admin->adminType}}</td>
                                    <td>
                                        <input class="mainGroupId" type="radio" name="AdminId" value="{{$admin->id}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="cancelTakhsis">انصراف <i class="fa fa-xmark"></i></button>
            <button type="button" onclick="takhsisCustomer()" class="btn btn-primary" >ذخیره<i class="fa fa-save"></i></button>
            </div>
        </div>
       </div>
    </div>

            <!-- Modal for reading comments-->
            <div class="modal fade dragableModal" id="viewFactorDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog   modal-lg">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row" style=" border:1px solid #dee2e6; padding:10px">
                                <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
                                <div class="col-sm-6">
                                    <table class="table table-borderless" style="background-color:#dee2e6">
                                        <tbody>
                                        <tr>
                                            <td>تاریخ فاکتور:</td>
                                            <td id="factorDate"></td>
                                        </tr>
                                        <tr>
                                            <td>مشتری:</td>
                                            <td id="customerNameFactor"></td>
                                        </tr>
                                        <tr>
                                            <td>آدرس:</td>
                                            <td id="customerAddressFactor"> </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table table-borderless" style="background-color:#dee2e6">
                                        <tbody>
                                            <tr>
                                                <td>تلفن :</td>
                                                <td id="customerPhoneFactor"></td>
                                            </tr>
                                        <tr>
                                            <td>کاربر :</td>
                                            <td >3</td>
                                        </tr>
                                        <tr>
                                            <td>شماره فاکتور :</td>
                                            <td id="factorSnFactor"></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable"  class=' table table-bordered table-striped table-sm' style="background-color:#dee2e6">
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
                                    <tbody id="productList">
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

    <!-- modal of inactive customer -->
    <div class="modal fade dragableModal" id="inactiveCustomer"  tabindex="-1"  data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <h5 class="modal-title" id="exampleModalLabel"> غیر فعالسازی </h5>
                </div>
                <form action="{{url('/inactiveCustomer')}}" id="inactiveCustomerForm" method="get">
                    <div class="modal-body">
                        <label for="">دلیل غیر فعالسازی</label>
                        <textarea class="form-control" name="comment" id="" cols="30" rows="6"></textarea>
                        <input type="text" name="customerId" id="inactiveId" style="display:none">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal" id="cancelInActive">بستن <i class="fa fa-xmark fa-lg"></i></button>
                        <button type="submit" class="btn btn-primary btn-sm" >ذخیره <i class="fa fa-save fa-lg"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <!-- Modal for reading comments-->
        <div class="modal fade dragableModal" id="viewComment" tabindex="1"  data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">کامنت ها</h5>
                </div>
                <div class="modal-body" >
                    <h3 id="readCustomerComment1"></h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

        <!-- modal of view return comment -->
        <div class="modal fade dragableModal" id="returnViewComment"  tabindex="-1"   data-bs-backdrop="static" >
            <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header py-2 text-white">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                         <h5 class="modal-title" id="exampleModalLabel"> دلیل ارجاع</h5>
                    </div>
                    <div class="modal-body" style="font-size:16px">
                        <div class="well">
                        <span id="returnView"></span>
                    </div>
                    </div>
                </div>
            </div>
        </div>

</main>

<script>
        $('#strCusDataTable').DataTable({
            "paging" :true,
            "scrollCollapse" :true,
            "searching" :true,
            "info" :true,
            "columnDefs": [ {
                "searchable": false,
                "orderable": false,
                "targets":[0,8],
            } ],

            "dom":"lrtip",
            "order": [[ 1, 'asc' ]],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fa.json"
            }
        } );


       let oTable = $('#strCusDataTable').DataTable();
       $('#dataTableComplateSearch').keyup(function(){
          oTable.search($(this).val()).draw() ;
    });

  
  
    $('.select-highlight tr').click(function() {
         $(this).children('td').children('input').prop('checked', true);
         $(".enableBtn").prop("disabled",false);
         if($(".enableBtn").is(":disabled")){
             alert("good");
         }else{
            $(".enableBtn").css("color","red !important");
         }
            $('.select-highlight tr').removeClass('selected');

            $(this).toggleClass('selected');
            $('#customerSn').val($(this).children('td').children('input').val().split('_')[0]);
            $('#customerGroup').val($(this).children('td').children('input').val().split('_')[1]);
        });
       let oTable = $('#strCusDataTable').DataTable();
       $('#dataTableComplateSearch').keyup(function(){
          oTable.search($(this).val()).draw() ;
    });

    $('.withQuality').select2({
        dropdownParent: $('#addComment'),
        width: '100%'
    });

    $('.noQuality').select2({
        dropdownParent: $('#addComment'),
        width: '100%'
    });

    $('.returned').select2({
        dropdownParent: $('#addComment'),
        width: '100%'
    });

    $.ajax({
        method: 'get',
        url: baseUrl + "/getProducts",
        data: {
            _token: "{{ csrf_token() }}"
        },
        async: true,
        success: function(arrayed_result) {
            $('#prductQuality').empty();
            $('#prductNoQuality').empty();
            $('#returnedProducts').empty();
            arrayed_result.forEach((element, index) => {

                $('#prductQuality').append(`
                    <option value="`+element.GoodSn+`">`+element.GoodName+`</option>
                `);

                $('#returnedProducts').append(`
                    <option value="`+element.GoodSn+`">`+element.GoodName+`</option>
                `);

                $('#prductNoQuality').append(`
                    <option value="`+element.GoodSn+`">`+element.GoodName+`</option>
                `);
            });
        },
        error: function(data) {
        }
    });


    // evacuated customer script 

        
$("#searchEmptyFirstDate").persianDatepicker({
    cellWidth: 30,
    cellHeight: 12,
    fontSize: 12,
    formatDate: "YYYY/0M/0D"
});
    $("#searchEmptySecondDate").persianDatepicker({
    cellWidth: 30,
    cellHeight: 12,
    fontSize: 12,
    formatDate: "YYYY/0M/0D",
    onSelect:()=>{
        let secondDate=$("#searchEmptySecondDate").val();
        let firstDate=$("#searchEmptyFirstDate").val();
         $.ajax({
            method: 'get',
            url: baseUrl + "/searchEmptyByDate",
            data: {
                _token: "{{ csrf_token() }}",
                secondDate: secondDate,
                firstDate:firstDate
            },
            async: true,
            success: function(msg) {
                moment.locale('en');
                $("#returnedCustomerList").empty();
                msg.forEach((element,index)=>{
                    $("#returnedCustomerList").append(`
                    <tr onclick="returnedCustomerStuff(this)">
                        <td>`+(index+1)+`</td>
                        <td>`+element.Name+`</td>
                        <td>`+element.PCode+`</td>
                        <td>`+element.peopeladdress+`</td>
                        <td>`+element.PhoneStr+`</td>
                        <td>`+moment(element.removedDate, 'YYYY-M-D HH:mm:ss').locale('fa').format('HH:mm:ss YYYY/M/D')+`</td>
                        <td> <input class="customerList form-check-input" name="customerId[]" type="radio" value="`+element.PSN+` `+element.adminId+`"></td>
                    </tr> `);
                });
            },
            error: function(data) {alert("bad");}
        });
    }
});



</script>
@endsection
