@extends('layout')
@section('content')
    <div class="container-fluid containerDiv">
             <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                        
                            <fieldset class="border rounded mt-5">
                              <legend  class="float-none w-auto legendLabel mb-0"> نظر سنجی  </legend>
                              <form action="{{url('/getAsses')}}" method="get">
                                    <div class="mb-1">
                                        <input type="text" id="sefTarafHisabName" placeholder="جستجو " class="form-control form-control-sm">
                                    </div>
                                    <div class="input-group input-group-sm mb-1">
                                         <span class="input-group-text" id="inputGroup-sizing-sm">تاریخ </span>
                                         <input type="text" class="form-control" id="sefFirstDate">
                                     </div>
                                     <div class="input-group input-group-sm mb-1">
                                         <span class="input-group-text" id="inputGroup-sizing-sm"> الی </span>
                                         <input type="text" class="form-control" id="sefSecondDate">
                                     </div>
                                    <div class="form-check">
                                        <input class="form-check-input p-2 float-end" type="radio" name="analyzIdea" id="assesToday">
                                                <label class="form-check-label me-4" for="assesToday"> نظرات امروز </label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input p-2 float-end" type="radio" name="analyzIdea" id="assesPast">
                                            <label class="form-check-label me-4" for="assesPast"> نظرات گذشته </label>
                                       </div>
                                       <div class="form-check">
                                          <input class="form-check-input p-2 float-end" type="radio" name="analyzIdea" id="assesDone">
                                                <label class="form-check-label me-4" for="assesDone"> نظرات انجام شده </label>
                                       </div>
                                       <button class='btn btn-primary btn-sm text-warning' type="button" id='getAssesBtn'>بازخوانی<i class="fal fa-dashboard fa-lg"></i></button>
                                       </form>
                              </fieldset>
                    </div>
                    <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                            <div class="row contentHeader">
                                <div class="col-lg-12 text-start mt-3">
                                    <input type="text" id="customerSn" style="display:none"  value="" />
                                    <input type="text" id="factorSn" style="display:none"  value="" />
                                    <button class='btn btn-primary btn-sm text-warning' type="button" disabled id='openDashboard'>داشبورد<i class="fal fa-dashboard fa-lg"></i></button>
                                    <button class="btn btn-primary btn-sm text-warning" onclick="openAssesmentStuff()" id="openAssessmentModal1"  disabled  type="button"  > افزودن نظر <i class="fa fa-address-card"> </i> </button>
                                </div>
                            </div>
                            <div class="row mainContent">

                              <table class='table-striped table-bordered table-sm'>
                                <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>اسم</th>
                                        <th>مبلغ</th>
                                        <th>تاریخ</th>
                                        <th>شماره فاکتور</th>
                                        <th>انتخاب</th>
                                    </tr>
                                    </thead>
                                    <tbody class="select-highlight tableBody" id="customersAssesBody">
                                        @forelse ($customers as $customer)
                                            
                                        <tr onclick="assesmentStuff(this)">
                                                <td class="no-sort">{{$loop->iteration}}</td>
                                                <td>{{trim($customer->Name)}}</td>
                                                <td>{{number_format($customer->TotalPriceHDS/10)}} تومان</td>
                                                <td>{{$customer->FactDate}}</td>
                                                <td>{{trim($customer->FactNo)}}</td>
                                                <td> <input class="customerList form-check-input" name="factorId" type="radio" value="{{$customer->PSN.'_'.$customer->SerialNoHDS}}"></td>
                                            </tr>
                                            @empty
                                            
                                        @endforelse
                                    
                                    </tbody>
                                </table> 
                            </div>
                                <div class="row contentFooter">
                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
{{-- dashbor modal --}}
<div class="modal fade dragableModal" id="customerDashboard"  data-backdrop="static"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable  modal-dialog-scrollable modal-xl">
        <div class="modal-content"  style="background-color:#d2e9ff;">
            <div class="modal-header" style="border-bottom:1px solid rgb(7, 42, 214);">
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close" style="background-color: red;"></button>
                <h5 class="modal-title">نظر سنجی  </h5>
            </div>
            <div class="modal-body"  style="background-color:#d2e9ff;">
                <div class="row">
                    <div class="col-lg-12">
                       <button class="btn btn-primary btn-sm buttonHover float-start" onclick="openAssesmentStuff()"  type="button" > افزودن نظر <i class="fa fa-address-card fa-lg"> </i> </Button>
						           <form action="https://starfoods.ir/crmLogin" target="_blank"  method="get">
                                    <input type="text" id="customerSnLogin" style="display: none" name="psn" value="" />
                                    <input type="text"  style="display: none" name="otherName" value="{{trim(Session::get('username'))}}" />
                                    <Button type="button" class="btn btn-primary btn-sm float-start" type="submit"> ورود جعلی  <i class="fas fa-sign-in fa-lg"> </i> </Button>
                                </form>
                    </div>
                </div>
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
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#basketKalas"> کالاهای سبد خرید</a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#returnedFactors"> فاکتور های برگشت داده </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#comments">  کامنت ها </a></li>
                            <li><a data-toggle="tab" style="color:black; font-size:14px; font-weight:bold;"  href="#assesments"> نظرسنجی ها</a></li>
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
                                            <th>مشاهده</th>
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
                                        <thead class="tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام کالا</th>
                                            <th> </th>
                                            <th> </th>
                                        </tr>
                                        </thead>
                                        <tbody id="goodDetail" class="tableBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row c-checkout rounded-3 tab-pane talbeDashboardTop" id="basketKalas">
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
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
                                        <tbody id="basketOrders" class="tableBody">
                                        <tr>
                                            <td> 1 </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="c-checkout tab-pane talbeDashboardTop" id="returnedFactors" >
                            <div class="row c-checkout rounded-3 tab-pane tableDashboardMiddle" id="custAddress">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead class="tableHeader">
                                        <tr>
                                            <th> ردیف</th>
                                            <th>تاریخ</th>
                                            <th> نام راننده</th>
                                            <th>مبلغ </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="returnedFactorsBody" class="tableBody">
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
                                            <th>ردیف</th>
                                            <th>تاریخ</th>
                                            <th>کامنت</th>
                                            <th>کامنت بعدی</th>
                                            <th>تاریخ بعدی </th>
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
            <!-- Modal for reading comments-->
            <div class="modal fade dragableModal" id="viewComment" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
</div>


        <!-- Modal for reading factor Detail -->
        <div class="modal fade dragableModal" id="viewFactorDetail" tabindex="-1" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog  modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">جزئیات فاکتور</h5>
                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row rounded-3" style=" border:1px solid #dee2e6; padding:10px">
                                <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">فاکتور فروش </h4>
								<div class="grid-container">
									<div class="item1"> <b>تاریخ فاکتور   :  </b> <span id="factorDate1">  </span> </div>
									<div class="item2"> <b> مشتری  :  </b> <span id="customerNameFactor1"> </span>    </div>
									<div class="item3"> <b> آدرس  :  </b> <span id="customerAddressFactor1"> </span>   </div>
									<div class="item4"><span> تلفن :</span>    <span id="customerPhoneFactor1"> </span></div>
									<div class="item5"><span> کاربر :  </span>   <span id="Admin2"> </span></div>
									<div class="item6"><span>  شماره فاکتور :</span>  <span id="factorSnFactor1">  </span></div>
								</div>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable" class='table table-bordered table-striped table-sm'>
                                    <thead class="tableHeader">
                                    <tr>
                                        <th scope="col">ردیف</th>
                                        <th scope="col">نام کالا </th>
                                        <th scope="col">تعداد/مقدار</th>
                                        <th scope="col">واحد کالا</th>
                                        <th scope="col">فی (تومان)</th>
                                        <th scope="col" style="width:122px">مبلغ (تومان)</th>
                                    </tr>
                                    </thead>
                                    <tbody id="productList1" class="tableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
            {{-- modal for adding comments --}}
            <div class="modal fade dragableModal" id="assesmentDashboard" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-scrollable  modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #d2e9ff; border-bottom: 1px solid blue;">
                            <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close" id="cancelAssesment" style="background-color:red;"></button>
                            <h5 class="modal-title" style="float:left;">افزودن نظر </h5>
                        </div>
                    <div class="modal-body">
                        <form action="{{url('/addAssessment')}}" id="addAssesment" method="get" style="background-color:transparent; box-shadow:none;">
                        <div class="row mb-2">
                            <div class="col-lg-10">
                              <label for="tahvilBar"> مشتری: &nbsp; </label>
                                <span id="customerComenter" style="font-size:18px;margin-bottom:11px;"></span>
                            </div>
                            <div class="col-lg-2" style="display:flex; justify-content:flex-end;">
                                <button type="submit" class="btn btn-sm btn-primary">ذخیره <i class="fa fa-save"></i></button>
                            </div>
                        </div>
							
							
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
								 <div class="col-lg-3">
                                  <div class="col-lg-12 mb-1">
                                        <select class="form-select form-select-sm" name="shipmentProblem">
                                            <option hidden>مشکل در بار</option>
                                            <option value="1">بلی</option>
                                            <option value="0">خیر</option>
                                        </select>
                                     <input type="text" name="customerId" id="customerIdForAssesment" style="display:none;">
                                    <input type="text" name="factorId" id="factorIdForAssesment" style="display:none;">
                                 </div>
                                <div class="col-lg-12 mb-2">
                                    <select class="form-select form-select-sm" name="behavior">
                                        <option hidden>برخورد راننده</option>
                                        <option value="1">عالی</option>
                                        <option value="2">خوب</option>
                                        <option value="3">متوسط </option>
                                        <option value="4">بد</option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <input class="form-control form-control-sm" name="alarmDate" required autocomplete="off" id="commentDate2" placeholder="آلارم خرید بعدی">
                                </div>
                            </div>
                            <div class="col-lg-9">
							 <div class="row">
                                <div class="col-lg-6">
                                    <label for="tahvilBar" >کلاهای عودتی  </label>
                                    <textarea class="form-control  bg-light" style="position:relative" name="firstComment" rows="3"  ></textarea>
                                </div>
                                <div class="col-lg-6">
                                    <label for="tahvilBar"> کامنت</label>
                                    <textarea class="form-control bg-light" required name="comment" rows="3" ></textarea>
                                </div>
                             </div>
                         </div>
                         <div class="row mt-3" style=" border:1px solid #dee2e6; padding:5px; margin-right:3px;">
                              <h6 style="padding:10px; text-align:center;">فاکتور فروش </h6>
							    <div class="grid-container">
									<div class="item1"> <b>تاریخ فاکتور   :  </b> <span id="factorDate">  </span> </div>
									<div class="item2"> <b> مشتری  :  </b> <span  id="customerNameFactor"> </span>    </div>
									<div class="item3"> <b> آدرس  :  </b> <span id="customerAddressFactor"> </span>   </div>
									<div class="item4"><span> تلفن :</span>    <span id="customerPhoneFactor"> </span></div>
									<div class="item5"><span> کاربر :  </span>   <span id="Admin1"> </span></div>
									<div class="item6"><span>  شماره فاکتور :</span>  <span id="factorSnFactor">  </span></div>
								</div>
                            </div>
                        <div class="row">
                            <table id="strCusDataTable"  class='table table-bordered table-striped table-sm'>
                                <thead class="tableHeader">
                                  <tr>
                                    <th>ردیف</th>
                                    <th>نام کالا </th>
                                    <th>تعداد/مقدار</th>
                                    <th>واحد کالا</th>
                                    <th>فی (تومان)</th>
                                    <th style="width:122px">مبلغ (تومان)</th>
                                  </tr>
                                </thead>
                                <tbody id="productList" class="tableBody">

                                </tbody>
                              </table>
                        </div>
                    </div>
                </form>
                </div>
                </div>
            </div>

@endsection
