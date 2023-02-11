@extends('layout')
@section('content')

<style>
    .tableHeader .tableBbody, tr > th:first-child {
    width: 100px !important;
}
.tableHeader .tableBbody, tr > td:first-child {
    width: 100px !important;
}
tr > th:last-child, tr > td:last-child{
    width:40px;
}

#customerTable{
    display: none;

}

.customerStaff{
    display: none;
}
</style>
 <div class="container-fluid containerDiv">
            <div class="row">
                 <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                     <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> تقویم روزانه  </legend>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="calendarRadioBtn">
                                <label class="form-check-label me-4" for="assesPast"> تقویم روزانه </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerListRadioBtn">
                                <label class="form-check-label me-4" for="assesPast"> لیست مشتریان </label>
                            </div>

                            <form action="{{url('/changeDate')}}" method="POST">
                                @csrf
                            <select class="form-select form-select-sm col-sm6" id="month" name="month" style="font-size:16px; width:48%;display:inline">
                                @for ($i = 1; $i < 13; $i++)
                                    @switch($i)
                                        @case(1)
                                        <option @if($i==$month) selected @endif value="1">فروردین</option>
                                            @break
                                        @case(2)
                                        
                                        <option @if($i==$month) selected @endif  value="2">اردبهشت</option>
                                            @break
                                        @case(3)
                                        <option @if($i==$month) selected @endif  value="3">خرداد</option>
                                            @break
                                        @case(4)
                                        <option @if($i==$month) selected @endif  value="4">تیر</option>
                                            @break
                                        @case(5)
                                        <option @if($i==$month) selected @endif  value="5">مرداد</option>
                                            @break
                                        @case(6)
                                        <option @if($i==$month) selected @endif  value="6">شهریور</option>
                                            @break
                                        @case(7)
                                        <option @if($i==$month) selected @endif  value="7">مهر</option>
                                            @break
                                        @case(8)
                                        <option @if($i==$month) selected @endif  value="8">آبان</option>
                                            @break
                                        @case(9)
                                        <option @if($i==$month) selected @endif  value="9">آذر</option>
                                            @break
                                        @case(10)
                                        <option @if($i==$month) selected @endif  value="10">دی</option>
                                            @break
                                        @case(11)
                                        <option @if($i==$month) selected @endif  value="11">بهمن</option>
                                            @break
                                        @case(12)
                                        <option @if($i==$month) selected @endif  value="12">اسفند</option>
                                            @break
                                        @default
                                    @endswitch
                                @endfor
                            </select>
                            <select class="form-select form-select-sm col-sm-6 w-50" id="year" name="year" style="font-size:16px; width:48%;display:inline">
                                @for ($i = 1397; $i < 1420; $i++)
                                    <option @if($i==$year) selected @endif value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            <label class="form-lable">کاربر</label>
                            <select class="form-select form-select-sm" name="asn" id="searchByMantagheh">
                                @foreach($employies as $employee)
                                    <option @if($employee->id==$adminId) selected @endif value="{{$employee->id}}">{{$employee->name.' '.$employee->lastName}}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm"> بازخوانی <i class="fa fa-edit"></i> </button>
                        </form>
                      </fieldset>
                  </div>

                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                      <div class="row contentHeader pt-3">
                            <div class="form-group col-sm-2 customerStaff">
                                <input type="text" name="" placeholder="جستجو" class="form-control publicTop" id="searchCustomerName">
                            </div>
                            <div class="form-group col-sm-2 customerStaff">
                                <input type="number" name="" placeholder="جستجوی کد حساب" class="form-control publicTop" id="searchCustomerCode">
                            </div>
                            <div class="form-group col-sm-2 customerStaff">
                                <select class="form-select publicTop" id="orderByCodeOrName">
                                    <option value="1" hidden>مرتب سازی</option>
                                    <option value="1">اسم</option>
                                    <option value="0">کد</option>
                                </select>
                            </div>
                            <div class="form-group col-sm-2 customerStaff">
                                <select class="form-select publicTop" id="findMantaghehByCity">
                                <option value="شهر" hidden>شهر</option>
                                    @foreach($cities as $city)
                                    <option value="{{$city->SnMNM}}">{{trim($city->NameRec)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-sm-2 customerStaff">
                                <select class="form-select publicTop" id="searchCustomerByMantagheh">
                                    <option value="مناطق" hidden>مناطق</option>
                                </select>
                            </div>
                            <div class="col-sm-2 text-start">
                                <button class='enableBtn btn-sm btn btn-primary text-warning customerStaff' type="button" disabled id='openDashboard'> داشبورد <i class="fal fa-dashboard"></i></button>
                                <button class='enableBtn btn-sm btn btn-primary text-warning customerStaff' type="button" disabled id='returnCustomer'> ارجاع به مدیر<i class="fal fa-history"></i></button>
                            </div>
                        </div>
                        
                    <div class="row mainContent">
                        <div class="col-lg-12 px-0 pd-0">
                              <table class="table table-bordered border-primary resizableTable" id="timeTable">
                                <thead class="monthDay text-warning">
                                    <th class="weekDay">روزهای هفته</th>
                                    @for ($v = 1; $v < 32; $v++)
                                        <th >{{$v}}</th>
                                    @endfor
                                </thead>
                                <tbody class="monthDay">
                                    @for ($i = 0; $i < 7; $i++)
                                    <tr style="background-color:#b3d1ef">
                                        <td class="weekDay">
                                            @switch($i) 
                                               @case(0)شنبه @break
                                                @case(1) یکشنبه @break
                                                @case(2)  دوشنبه @break
                                                @case(3)  سه شنبه @break
                                                @case(4) چهار شنبه @break
                                                @case(5)پنجشنبه @break
                                                @case(6) جمعه  @break
                                                @default
                                            @endswitch
                                        </td>
                                     @for($j = 1; $j < 32; $j++)
                                            <td onclick="showTimeTableTasks(this,{{$adminId}})" style="cursor:pointer" class="">
                                                @foreach ($commenDates as $dt)
                                                    @php
                                                        $monthDay=\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($dt->specifiedDate))->getDay();
                                                        $commenYear=\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($dt->specifiedDate))->getYear();
                                                        $commenMonth=\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($dt->specifiedDate))->getMonth();
                                                        $weekDay=\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($dt->specifiedDate))->getDayOfWeek();
                                                    @endphp
                                                    @if( $monthDay==$j and $weekDay==$i and $commenYear==$year and $commenMonth==$month)
                                                        {{$dt->count}}
                                                    <input type="radio" style="display: none;" name="" value="{{$dt->specifiedDate}}" id="">
                                                    @endif
                                                  @endforeach
                                            </td>
                                  @endfor
                                </tr>
                            @endfor
                            </tbody>
                        </table>

                        <table class='table table-bordered table-striped table-sm' id="customerTable">
                            <thead class="tableHeader">
                              <tr>
                                  <th>ردیف</th>
                                  <th>کد</th>
                                  <th>اسم</th>
                                  <th>آدرس </th>
                                  <th>تلفن</th>
                                  <th>همراه</th>
                                  <th>منطقه </th>
                                  <th>انتخاب</th>
                              </tr>
                              </thead>
                              <tbody class="select-highlight tableBody" id="customerListBody1">
                                  @foreach ($customers as $customer)
                                      <tr @if($customer->maxTime) style="background-color:lightblue" @endif>
                                          <td>{{$loop->iteration}}</td>
                                          <td>{{trim($customer->PCode)}}</td>
                                          <td>{{trim($customer->Name)}}</td>
                                          <td>{{trim($customer->peopeladdress)}}</td>
                                          <td>{{trim($customer->PhoneStr)}}</td>
                                          <td>{{trim($customer->PhoneStr)}}</td>
                                          <td>{{trim($customer->NameRec)}}</td>
                                          <td> <input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN.'_'.$customer->GroupCode}}"></td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>  
                       </div>
                    </div>

                <div class="row contentFooter"> </div>
            </div>
        </div>
    </div>     
</div>


<div class="modal fade" id="customreForCallModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="customreForCallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header py-2 text-white">
            <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            <h6 class="modal-title fs-5" id="customreForCallModalLabel"> مشتریان  </h6>
        </div>
        <div class="modal-body p-1">
            <div class="col-sm-12 text-start" id="customerListSection">
                    <input type="hidden" id="customerSn" style="" name="customerSn" value="" />
                    <button class="btn-primary btn-sm text-warning" disabled id="openDashboard"> داشبورد <i class="fal fa-dashboard"> </i> </button>
                    <button class='btn-primary btn-sm text-warning' disabled id='returnCustomer'> ارجاع به مدیر <i class="fal fa-history"></i></button>
              
                <table class='table table-bordered table-striped table-sm'>
                    <thead class="tableHeader">
                        <tr>
                            <th>ردیف</th>
                            <th>کد</th>
                            <th>اسم</th>
                            <th>آدرس </th>
                            <th>تلفن</th>
                            <th>همراه</th>
                            <th>منطقه </th>
                            <th>انتخاب</th>
                        </tr>
                    </thead>
                    <tbody class="select-highlight tableBody" id="customerListBody" style="height:300px !important;">
                    </tbody>
                </table>
            </div>
        </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>



<div class="modal fade notScroll" id="customerDashboard" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                    <div class="col-lg-6 col-md-6 col-sm-12">
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
                                <table class="homeTables factor crmDataTable tableSection4 table table-bordered table-striped table-sm">
                                    <thead  style="position: sticky;top: 0;">
                                    <tr>
                                        <th> ردیف</th>
                                        <th>تاریخ</th>
                                        <th> نام راننده</th>
                                        <th>مبلغ </th>
                                        <th>مشاهد جزئیات </th>
                                    </tr>
                                    </thead>
                                    <tbody  id="factorTable">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row c-checkout rounded-3 tab-pane" id="moRagiInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                            <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <table class="homeTables crmDataTable buyiedKala tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                        <thead  style="position: sticky;top: 0;">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام کالا</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="goodDetail">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row c-checkout rounded-3 tab-pane" id="userLoginInfo1" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                            <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <table class="homeTables crmDataTable basketKala tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                        <thead  style="position: sticky;top: 0;">
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

                        <div class="row c-checkout rounded-3 tab-pane" id="customerLoginInfo" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                            <div class="row c-checkout rounded-3 tab-pane" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <table class="homeTables crmDataTable returnedFactor tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                        <thead  style="position: sticky;top: 0;">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th>نوع پلتفورم</th>
                                            <th>مرورگر</th>
                                        </tr>
                                        </thead>
                                        <tbody id="customerLoginInfoBody">
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
                                    <table class="homeTables crmDataTable comments tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                        <thead  style="position: sticky;top: 0;">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام راننده</th>
                                            <th>مبلغ </th>
                                        </tr>
                                        </thead>
                                        <tbody id="returnedFactorsBody">
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
                                    <table class="homeTables crmDataTable nazarSanji tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                        <thead  style="position: sticky;top: 0;">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> کامنت</th>
                                            <th> کامنت بعدی</th>
                                            <th> تاریخ بعدی </th>
                                        </tr>
                                        </thead>
                                        <tbody id="customerComments"  >

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="c-checkout tab-pane" id="assesments" style="margin:0; border-radius:10px 10px 2px 2px;">
                            <div class="row c-checkout rounded-3 tab-pane active" style="width:99%; margin:0 auto; padding:1% 0% 0% 0%">
                                <div class="col-sm-12">
                                    <table class="homeTables crmDataTable myCustomer tableSection4 table table-bordered table-striped table-sm" style="text-align:center;">
                                        <thead  style="position: sticky;top: 0;">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> کامنت</th>
                                            <th> برخورد راننده</th>
                                            <th> مشکل در بارگیری</th>
                                            <th> کالاهای برگشتی</th>
                                        </tr>
                                        </thead>
                                        <tbody id="customerAssesments"  >
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
    <div class="modal fade" id="viewFactorDetail" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog   modal-lg">
            <div class="modal-content">
                <div class="modal-header">
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
                                            <td id="Admin">3</td>
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
                                <table id="strCusDataTable"  class='homeTables crmDataTable dashbordTables table table-bordered table-striped table-sm' style="background-color:#dee2e6">
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

    <!-- Modal for returning customer-->
    <div class="modal fade" id="returnComment"  data-bs-backdrop="static"  aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                </div>
                <form action="{{url('/returnCustomer')}}" id="returnCustomerForm" method="get">
                    <div class="modal-body">
                        <input type="text" name="returnCustomerId" id="returnCustomerId" style="display:none;">
                        <div class="row">
                            <div class="col-sm-12 fw-bold">
                                <label for="tahvilBar">دلیل ارجاع</label>
                                <textarea class="form-control" style="position:relative" name="returnComment" rows="3" ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" id="cancelReturn" style="background-color:red;">انصراف<i class="fal fa-cancel"> </i></button>
                        <button type="submit" class="btn btn-sm btn-primary">ارجاع<i class="fal fa-history"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal for reading comments-->
<div class="modal fade" id="viewComment" tabindex="1"  data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
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
{{-- modal for adding comments --}}
<div class="modal" id="addComment"  data-bs-backdrop="static" >
    <div class="modal-dialog modal-dialog-scrollable ">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title"> افزودن کامنت </h5>
            </div>
            <div class="modal-body">
                <form action="{{url('/addComment')}}" id='addCommentTimeTable' method="get" style="background-color:transparent; box-shadow:none;">
                    @csrf
                <div class="row">
                    <div class="col-lg-12 fw-bold">
                        <label for="tahvilBar">نوع تماس</label>
                        <select class="form-select" name="callType">
                            <option value="1">موبایل  </option>
                            <option value="2"> تلفن ثابت </option>
                            <option value="3"> واتساپ</option>
                            <option value="4">حضوری </option>
                        </select>
                        <input type="text" name="customerIdForComment" id="customerIdForComment" style="display:none;">
                    </div>
                </div>
                <input type="hidden" value="" id="dayDate" >
                <div class="row">
                    <div class="col-sm-12 fw-bold">
                        <label for="tahvilBar" >کامنت </label>
                        <textarea class="form-control" style="position:relative" required name="firstComment" rows="3" ></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 fw-bold">
                        <label for="tahvilBar" >زمان تماس بعدی </label>
                        <input class="form-control" autocomplete="off" required name="nextDate" id="commentDate2">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 fw-bold">
                        <label for="tahvilBar">کامنت بعدی</label>
                        <textarea class="form-control" name="secondComment" required rows="5" ></textarea>
                        <input type="text" id="lastCommentId" style="display: none" name="lastCommentId">
                        <input type="text"  style="display: none" name="place" value="calendar">
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" id="cancelComment">انصراف <i class="fa fa-xmark"></i></button>
                    <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
 </div>

<!-- Modal -->

<script>
     $(function(){
    $(".resizableTable").resizableColumns();
  });
</script>


@endsection
