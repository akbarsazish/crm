@extends('layout')
@section('content')



<div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> عملکرد مشتریان </legend>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="elseSettingsRadio">
                                <label class="form-check-label me-4" for="assesPast">  گزارش ورود </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                                <label class="form-check-label me-4" for="assesPast"> غیرفعال </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                                <label class="form-check-label me-4" for="assesPast"> فاقد کاربر </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                                <label class="form-check-label me-4" for="assesPast"> ارجاعی</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                                <label class="form-check-label me-4" for="assesPast"> موقعیت </label>
                            </div>
                            <div class="form-group col-sm-12 mt-2">
                                 <input type="text" name="" size="20" placeholder="جستجو" class="form-control form-control-sm publicTop" id="searchAllName">
                             </div>
							<div class="form-group col-sm-12 mt-2">
								<select class="form-select form-select-sm publicTop" id="searchAllActiveOrNot">
									<option value="-1" hidden>وضعیت</option>
									<option value="0">همه</option>
									<option value="1">فعال</option>
									<option value="2"> غیر فعال</option>
								</select>
							</div>
							<div class="form-group col-sm-12 mt-2">
								<select class="form-select form-select-sm  publicTop" id="locationOrNot">
									<option value="0" hidden>موقعیت</option>
									<option value="1">همه</option>
									<option value="2">موقعیت دار </option>
									<option value="3">بدون موقعیت</option>
								</select>
							</div>
						  <div class="form-group col-sm-12 mt-2">
							<select class="form-select form-select-sm publicTop" id="searchAllFactorOrNot">
								<option value="-1" hidden>فاکتور</option>
								<option value="0">همه</option>
								<option value="1">دارد</option>
								<option value="2">ندارد</option>
							</select>
						 </div>
						 <div class="form-group col-sm-12 mt-2">
							<select class="form-select form-select-sm publicTop" id="searchAllBasketOrNot">
								<option value="-1">وضعیت سبد</option>
								<option value="0">همه</option>
								<option value="2"> نگذاشته اند</option>
								<option value="1">گذاشته اند</option>
							</select>
						 </div>
                        <div class="form-group col-sm-12 mt-2">
                            <select class="form-select form-select-sm publicTop" id="searchAllLoginOrNot">
                                <option value="-1" hidden>ورودبه سیستم </option>
                                <option value="0">همه</option>
                                <option value="1">وارد شده</option>
                                <option value="2">وارد نشده</option>
                            </select>
                        </div>
                       <div class="form-group col-sm-12 mt-2">
                        <select class="form-select form-select-sm publicTop" id="searchByAdmin">
                            <option value="0" hidden> کاربر</option>
                            <option value="0"> همه</option>
                            @foreach($amdins as $admin)
                            <option value="{{$admin->id}}"> {{trim($admin->name)}} {{trim($admin->lastName)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12 mt-2">
                        <select class="form-select form-select-sm publicTop" id="searchByCity">
                          <option value="0" hidden> شهر</option>
                          <option value="0"> همه</option>
                            @foreach($cities as $city)
                            <option value="{{$city->SnMNM}}"> {{trim($city->NameRec)}}</option>
                            @endforeach
                        </select>
                    </div>
                        <div class="form-group col-sm-12 mt-2">
                            <select class="form-select form-select-sm publicTop" id="searchByMantagheh">
                            <option value="0" hidden>منطقه</option>
                            <option value="0">همه</option>
                            </select>
                        </div>
              
                    </fieldset>
                </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> 
                        <div class="col-sm-12 text-start">
                              <button class='btn btn-primary btn-sm text-warning' data-toggle="modal" data-target="#reportCustomerModal" disabled  type="button" id="openCustomerActionModal"> داشبورد <i class="fal fa-dashboard fa-lg"></i></button>
                        </div>
                    </div>
                    <div class="row mainContent">
                          <table class='table table-bordered table-striped table-hover'>
                              <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th style="width:166px">اسم</th>
                                        <th>همراه</th>
                                        <th style="width:277px">آدرس</th>
                                        <th style="width:80px"> فاکتورها  </th>
                                        <th>تاریخ فاکتور</th>
                                        <th> تاریخ ورود </th>
										<th>کاربر</th>
                                        <th> انتخاب</th>
										<th>فعال</th>
                                    </tr>
                                    </thead>
                                    <tbody class="select-highlight tableBody" id="allCustomerReportyBody">
                                        @forelse ($customers as $customer)
                                        <tr>
                                            <td >{{$loop->iteration}}</td>
                                            <td style="width:177px">{{trim($customer->Name)}}</td>
                                            <td >{{trim($customer->hamrah)}}</td>
                                            <td style="width:266px">{{trim($customer->peopeladdress)}}</td>
                                            <td style="width:80px">{{trim($customer->countFactor)}}</td>
                                            <td >{{trim($customer->lastDate)}}</td>
                                            <td >هنوز نیست</td>
                                            <td >{{trim($customer->adminName).' '.trim($customer->lastName)}}</td>
                                            <td > <input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN}}"></td>
											<td >@if($customer->state==1) <input type="checkbox" disabled /> @else <input disabled type="checkbox" checked />  @endif</td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                      </div>
                    <div class="row contentFooter"> </div>
                </div>
        </div>
    </div>

    


<div class="modal fade dragModal" id="reportCustomerModal"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-xl">
        <div class="modal-content"  style="background-color:#d2e9ff;">
            <div class="modal-header" style="border-bottom:1px solid rgb(7, 42, 214);">
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close" style="background-color:red;"></button>
                <h5 class="modal-title" style="float:left;">نظر سنجی  </h5>
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
                            <li><a class="active" data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#custAddress"> فاکتور های ارسال شده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#moRagiInfo">  کالاهای خریداری کرده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerCard"> کالاهای سبد خرید</a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerRetunFactor"> فاکتور های برگشت داده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments">  نظرها </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#customerAssesmentAdmin">نظرسنجی ها</a></li>
                        </ul>
                    </div>
                    <div class="c-checkout tab-content talbeDashboardTop">
                            <div class="row c-checkout rounded-3 tab-pane active  tableDashboardMiddle" id="custAddress">
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
                <div class="modal-header">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row" style=" border:1px solid #dee2e6; padding:10px">
                                <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
							
								<div class="grid-container">
									<div class="item1"> <b>تاریخ فاکتور   :  </b> <span id="factorDate">  </span> </div>
									<div class="item2"> <b> مشتری  :  </b> <span id="customerNameFactor"> </span>    </div>
									<div class="item3"> <b> آدرس  :  </b> <span id="customerAddressFactor1"> </span>   </div>
									<div class="item4"><span> تلفن :</span>    <span id="customerPhoneFactor"> </span></div>
									<div class="item5"><span> کاربر :  </span>   <span id="Admin"> </span></div>
									<div class="item6"><span>  شماره فاکتور :</span>  <span id="factorSnFactor">  </span></div>
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
                <div class="modal-header">
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
</script>
@endsection
