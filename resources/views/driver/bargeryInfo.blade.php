@extends('layout')
@section('content')

<style>
   
        .grid-container {
            display: grid;
            grid-template-columns: auto auto auto auto;
            gap: 3px;
            padding: 5px;
            }

        .grid-container > div {
            text-align: center;
            font-size: 14px;
            font-weight:bold;
            text-align:right;
            padding:5px;
			background-color:#bad5ef;
			border-radius:6px;
            }
</style>
    <div class="container-xl" style="margin-top:5%;">
       <div class="card mb-4">
           <div class="card-body">
                <div class="row">
                    <h3 style="font-size:22px; font-weight:bold; border-bottom:2px solid blue; width:40%">اطلاعات  بارگیری   </h3>
                    <div class="col-lg-12">
                       <table class="select-highlight table table-bordered table-striped" id="">
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام کاربر</th>
                                    <th>شماره تماس</th>
                                    <th>فعال</th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="">
                                @foreach ($admins as $admin)
                                        <tr onclick="showBargiriFactors(this,{{$admin->driverId}})">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                            <td>{{trim($admin->phone)}}</td>
<<<<<<< HEAD
                                            <td> <a href="{{url('crmDriver?asn='.$admin->driverId.'')}}"> <i class="fa fa-eye fa-lg" style="color:#000;"></i> </a> </td>
=======
>>>>>>> 89e064e2226f3b1b33769e779dc485d30cdb6b08
                                            <td>
                                                <input class="mainGroupId" type="radio" name="AdminId[]" value="{{$admin->id}}">
                                            </td>
                                        </tr>
<<<<<<< HEAD
                                    @endforeach
                                </tbody>
                             </table>
                            <table class="table table-bordered bargeriTable" id="tableGroupList">
                                <thead class="bg-primary text-warning tableHeader">
                                    <tr>
                                        <th>#</th>
                                        <th>نام مشتری</th>
                                        <th> آدرس </th>
                                        <th>تلفن </th>
                                        <th style="width:111px">فاکتور</th>
                                        <th> انتخاب</th>
                                    </tr>
                                </thead>
                                <tbody class="tableBody" id="crmDriverBargeri" style="height:250px !important">
                                </tbody>
                            </table>
                     </div>
                </div>
                <div class="row contentFooter">
                    <div class="col-lg-12 text-start mt-1" id="bottomServiceBttons">
                        <button type="button" class="btn btn-sm btn-primary footerButton"> امروز  : </button>
                        <button type="button" class="btn btn-sm btn-primary footerButton"> دیروز : </button>
                        <button type="button" class="btn btn-sm btn-primary footerButton"> صد تای آخر : 100</button>
                        <button type="button" class="btn btn-sm btn-primary footerButton"> همه : </button>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection





=======
                                @endforeach
                            </tbody>
                        </table>

                     </div>
                   </div>

                   <div class="row"> 
                        <h4> لیست فاکتور ها </h4>
                        <table class="table table-bordered" id="tableGroupList">
                            <thead class="bg-primary text-warning tableHeader">
                                <tr>
                                    <th class="mobileDisplay">#</th>
                                    <th>نام مشتری</th>
                                    <th class="mobileDisplay"> آدرس </th>
                                    <th>تلفن </th>
                                    <th style="width:111px">فاکتور</th>
                                    <th> انتخاب</th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="crmDriverBargeri">
                            </tbody>
                        </table>
                   </div>
              </div>

<!-- modal for demonestrating factor deatails -->
        <div class="modal fade" id="bargiriFactor" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close bg-danger" data-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="exampleModalLabel">فاکتور فروش <span  id="totalMoney"> </span> </h5>
                    </div>
                        <div class="modal-body">
                            <div class="grid-container">
                                <div class="item1"> <b>مشتری  :  </b> <span id="customerNameFactor">  </span> </div>
                                <div class="item2"> <b> آدرس  :  </b> <span id="customerAddressFactor"> </span>    </div>
                                <div class="item3"> <b>تلفن :  </b> <span id="customerPhoneFactor"> </span>   </div>
                                <div class="item4"><span> مبلغ کارت:</span>    <span id="cartPrice1"> </span></div>
                                <div class="item5"><span> واریز:  </span>   <span id="varizPrice1"> </span></div>
                                <div class="item6"><span> مبلغ نقد :</span>  <span id="naghdPrice1">  </span></div>
                                <div class="item7 text-danger"> <span> تخفیف :  </span>   <span id="takhfifPrice1"> </span></div> 
                                <div class="item8"><span> باقی :  </span>   <span  id="diffPrice1"> </span> </div>
                                <div class="item9"><span> توضیح:  </span>   <span  id="description1">  </span></div>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable" class='table table-bordered table-striped table-sm' style="background-color:#dee2e6">
                                    <thead class="bg-primary tableHeader">
                                        <tr>
                                            <th class="driveFactor">#</th>
                                            <th>نام کالا </th>
                                            <th class="driveFactor">تعداد/مقدار</th>
                                            <th>واحد کالا</th>
                                            <th>فی (تومان)</th>
                                            <th >مبلغ (تومان)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="productList" class="tableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">بستن  <i class="fa fa-xmark"> </i> </button>
                        <input type="hidden" id="bargiriyBYSId"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
>>>>>>> 89e064e2226f3b1b33769e779dc485d30cdb6b08
