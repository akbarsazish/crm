@extends('layout')
@section('content')

<style>
    label{
        font-size: 14px;
        font-weight: bold;
    }
    .list-group-item{
        font-size:14px;
    }
        .grid-container {
            display: grid;
            grid-template-columns: auto auto;
            gap: 5px;
            padding: 5px;
            background-color:#f1efef;
            }

        .grid-container > div {
            text-align: center;
            font-size: 14px;
            font-weight:bold;
            text-align:right;
            padding:3px;
            }

   @media only screen and (max-width: 992px) {
    .driverTable .address,.choice {
        display:none;
    }
   }
   @media only screen and (max-width: 678px) and (min-width:278px) {
    .driveFactor{
        display: none !important;
    }
  }

  tbody, td, tfoot, th, thead, tr {
    font-size:12px;
  }


</style>

 <div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> راننده ها </legend>
                    </fieldset>
                </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> 
                        <div class="form-group col-sm-12 col-lg-2 col-md-2 mt-2">
                            <input type="text" name="" class="form-control form-control-sm" id="bargeriSecondDate" placeholder="تا تاریخ " />
                        </div>
                        <div class="form-group col-sm-12 col-lg-2 col-md-2 mt-2 mt-2">
                            <input class="form-control form-control-sm" type="text" id="bargerilist" placeholder="جستجو">
                        </div>
                    </div>
                    <div class="row mainContent">
                        <div class="col-lg-12 px-0">
                            <table class="table table-bordered crmDataTable driverTable" id="tableGroupList">
                                <thead class="bg-primary tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام مشتری</th>
                                        <th class="address"> آدرس </th>
                                        <th>تلفن </th>
                                        <th style="width:66px"> موقعیت </th>
                                        <th style="width:66px">فاکتور</th>
                                        <th class="choice" > انتخاب</th>
                                    </tr>
                                </thead>

                                <tbody class="c-checkout tableBody" id="crmDriverBargeri">
                                    @foreach ($factors as $factor)
                                        <tr onclick="setBargiryStuff(this)">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{ Str::limit($factor->Name, 22) }}</td>
                                            <td class="address">{{$factor->peopeladdress}}</td>
                                            <td><a style="color:black; font-size:12px;" href="tel:+900300400"> {{$factor->PhoneStr}} </a> </td>
                                            <td style="width:66px; text-align: center;"><a style="text-decoration:none;" target="_blank" href="https://maps.google.com/?q={{$factor->LonPers.','.$factor->LatPers}}"><i class="fas fa-map-marker-alt fa-1xl" style="color:#116bc7; "></i></a></td>
                                            <td style="text-align: center; cursor:pointer; width:66px" data-toggle="modal" data-target="#factorDeatials"><i class="fa fa-eye fa-1xl"> </i> </td>
                                            <td class="choice"> <input class="customerList form-check-input" name="factorId" type="radio" value="{{$factor->SnBargiryBYS.'_'.$factor->SerialNoHDS.'_'.$factor->TotalPriceHDS}}"></td>
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


<!-- Modal -->
<div class="modal fade" id="driverLocation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red"></button>
            <h5 class="modal-title" id="exampleModalLabel"> موقعیت راننده </h5>
        </div>
            <div class="modal-body">
                 <div class="container-fluid m-0 p-0">
                     <div id="map2" class="z-depth-1-half map-container-4" style="height: 500px"></div>
                 </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن </button>
        </div>
    </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="factorDeatials" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close" style="background-color:red"></button>
            <h5 class="modal-title" id="exampleModalLabel">فاکتور فروش <span  id="totalMoney"> </span> </h5>
        </div>
            <div class="modal-body">
                <div class="row"> 
                        <div class="col-lg-6">
                            <ul class="list-group px-0 card">
                                <li class="list-group-item">  <b>مشتری  :  </b><span id="customerNameFactor">  </span> </li>
                                <li class="list-group-item"> <b> آدرس  :  </b> <span id="customerAddressFactor"> </span>    </li>
                                <li class="list-group-item">  <b>تلفن :  </b> <span id="customerPhoneFactor"> </span> &nbsp; &nbsp; <b>تاریخ  :</b>  <span id="factorDate"> </span> </li>

                             </ul>
                          </div>
                          <div class="col-lg-6"> 
                            <div class="grid-container card">
                                    <div class="item1"><span> مبلغ کارت:</span>    <span id="cartPrice1"> </span></div>
                                    <div class="item1"><span> واریز:  </span>   <span id="varizPrice1"> </span></div>
                                    <div class="item2"><span> مبلغ نقد :</span>  <span id="naghdPrice1">  </span></div>
                                    <div class="item3 text-danger"> <span> تخفیف :  </span>   <span id="takhfifPrice1"> </span></div>  
                                    <div class="item5"><span> باقی :  </span>   <span  id="diffPrice1"> </span> </div>
                                    <div class="item6"><span> توضیح:  </span>   <span  id="description1">  </span></div>
                            </div>
                        </div>
                </div>
                <div class="row">
                    <table id="strCusDataTable"  class='css-serial display table table-bordered table-striped table-sm' style="background-color:#dee2e6">
                        <thead class="bg-primary">
                            <tr>
                                <th class="driveFactor">#</th>
                                <th>نام کالا </th>
                                <th class="driveFactor">تعداد/مقدار</th>
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
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" id="openReciveMoneyModal">  دریافت  <i class="fa fa-plus"> </i> </button>
            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" id="changeAddressOnMap"> دریافت لوکیشن <i class="fa fa-edit"> </i> </button>
            <input type="hidden" id="bargiriyBYSId"/>
        </div>
    </div>
    </div>
</div>


<!-- modal for adding documents  -->
<div class="modal fade" id="addingDocuments" data-bs-backdrop='static' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(#4e6aa9, #4e6aa9, #4e6aa9);">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red"></button>
            <h5 class="modal-title" id="exampleModalLabel"> افزودن دریافت </h5>
        </div>
            <div class="modal-body">
                 <form method="GET" action="{{url('/setReciveMoneyDetail')}}" id="setReciveMonyDetails">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-1">
                                <label  class="form-label">  وجه نقدی (تومان)</label>
                                <input  type="text" class="receivedInput form-control form-control-sm" id="naghdHisab" name="naghdPrice" >
                                <input type="hidden" id="bargiryFactorId" name="bargiriId"/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-1">
                                <label  class="form-label"> کارتخوان  (تومان) </label>
                                <input type="text" class="receivedInput form-control form-control-sm received" id="cartHisab" name="cardPrice">
                            </div>
                        </div>
                       
                    </div>

                    <div class="row">
                       
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-1">
                                <label  class="form-label"> واریز به حساب  </label>
                                <input type="text" disabled class="receivedInput form-control form-control-sm received" id="varizHisab" name="varizPrice" >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-1">
                                <label  class="form-label"> تخفیف (تومان)</label>
                                <input type="text" class="receivedInput form-control form-control-sm received" id="discountHisab" name="takhfifPrice">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-1">
                                <label  class="form-label"> باقی (تومان)</label>
                                <input type="text" class="receivedInput form-control form-control-sm" disabled id="remainHisab" name="diffPrice" >
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="mb-1">
                                <label  class="form-label">توضیح</label>
                                <textarea name="description"  class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن <i class="fa fa-xmark"> </i> </button>
                <button type="submit" class="btn btn-primary" disabled id="receivedBtn">ذخیره <i class="fa fa-save"> </i> </button>
            </div>
        </form>
    </div>
    </div>
</div>

<!-- modal for changing addesss  -->
<div class="modal fade" id="changeAddressModal" data-bs-backdrop='static' tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header" style="background: linear-gradient(#4e6aa9, #4e6aa9, #4e6aa9);">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:red"></button>
            <h5 class="modal-title" id="exampleModalLabel"> تغییر آدرس </h5>
        </div>
            <div class="modal-body">
                 <div id="changeAdd"  style="width:100%; height:320px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن <i class="fa fa-xmark"> </i> </button>
                <button type="submit" class="btn btn-primary" disabled id="receivedBtn">ذخیره <i class="fa fa-save"> </i> </button>
            </div>
      </div>
    </div>
</div>

<script>
//     $('.received').keypress(function(e){ 
      
//    if (this.value.length == 0 && e.which == 48 ){
//       return false;
//    }
// });

$('#varizHisab').on("keyup", ()=>{
    $('#receivedBtn').prop("disabled", false);

    if(!$("#varizHisab").val()){
        $("#varizHisab").val(0);
    }
    $('#varizHisab').val(parseInt($('#varizHisab').val().replace(/\,/g,'')).toLocaleString("en-US"));
});


$('#naghdHisab').on("keyup", ()=>{
    $('#receivedBtn').prop("disabled", false);

    if(!$("#naghdHisab").val()){
        $("#naghdHisab").val(0);
    }
    $('#naghdHisab').val(parseInt($('#naghdHisab').val().replace(/\,/g,'')).toLocaleString("en-US"));
});


$('#cartHisab').on("keyup", ()=>{
    $('#receivedBtn').prop("disabled", false);

    if(!$("#cartHisab").val()){
        $("#cartHisab").val(0);
    }
    $('#cartHisab').val(parseInt($('#cartHisab').val().replace(/\,/g,'')).toLocaleString("en-US"));
});


$('#remainHisab').on("keyup", ()=>{
    $('#receivedBtn').prop("disabled", false);

    if(!$("#remainHisab").val()){
        $("#remainHisab").val(0);
    }
    $('#remainHisab').val(parseInt($('#remainHisab').val().replace(/\,/g,'')).toLocaleString("en-US"));
});

$('#discountHisab').on("keyup", ()=>{
    $('#receivedBtn').prop("disabled", false);

    if(!$("#discountHisab").val()){
        $("#discountHisab").val(0);
    }
    $('#discountHisab').val(parseInt($('#discountHisab').val().replace(/\,/g,'')).toLocaleString("en-US"));
});


$(".receivedInput").on("keyup",function(){
        let variz=0;
        let cart=0;
        let discount=0;
        let naghd=0;
        let allPrice=parseInt($("#totalMoney").text());
        let totalPardakht=0;
        variz=parseInt($('#varizHisab').val().replace(/\,/g,''));
        cart=parseInt($('#cartHisab').val().replace(/\,/g,''));
        discount=parseInt($('#discountHisab').val().replace(/\,/g,''));
        naghd=parseInt($('#naghdHisab').val().replace(/\,/g,''));
        
        if(!variz){
            variz=0;
        }

        if(!cart){
            cart=0;
        }

        if(!discount){
            discount=0;
        }

        if(!naghd){
            naghd=0;
        }
        

        totalPardakht=cart+discount+naghd;

        if(totalPardakht > allPrice){
            $("#remainHisab").css({"background":"red","color":"white"});
        }

        if(totalPardakht == allPrice){
            $("#remainHisab").css({"background":"green","color":"white"});
         
        }
        if(totalPardakht < allPrice){
            $("#remainHisab").css({"background":"white","color":"black"});
        }
        $("#remainHisab").val(parseInt((allPrice)-(totalPardakht)).toLocaleString("en-US")
        );

 });



$(document).ready(function() {
    $("#changeAddressOnMap").on("click", ()=>{
        var changeaddress = new Mapp({
          element: '#changeAdd',
          presets: { latlng: {
                  lat: 31, lng: 52,
              },
                zoom:5,
          },
          apiKey: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjMxNDMxNWIwNDI4YWQzNjQ4NzM2NDQ3OTJhNzRmYWY0MWM1M2VlNzhjYmMxNjQwNDYzNTNhMjU4MmNhNzU2MmNjMDkwMWU5ZWUwZWFkNjc4In0.eyJhdWQiOiIxOTk5NyIsImp0aSI6IjMxNDMxNWIwNDI4YWQzNjQ4NzM2NDQ3OTJhNzRmYWY0MWM1M2VlNzhjYmMxNjQwNDYzNTNhMjU4MmNhNzU2MmNjMDkwMWU5ZWUwZWFkNjc4IiwiaWF0IjoxNjY4NDMwNDg3LCJuYmYiOjE2Njg0MzA0ODcsImV4cCI6MTY3MDkzNjA4Nywic3ViIjoiIiwic2NvcGVzIjpbImJhc2ljIl19.bBvecfskKy0m6abjEKbRt_JZeu2hVyCjd7N8vxfv439efdtxJ6-c4UglKkLyjeOgnIhn_JPKuSYD5tQbEm5bI-TCD1sSpFosnz-eKeufsKY7AOtWTYXhQSZ2n6nRKQU6ltRyZQurWlP0lyeNZBYgVbgJFs1V1WVRErD3A8Kr5bztZESFdNI86KbQs6_I3BwwOA9GkXc-RyXU8dwxKj9uG4c7_w1E23e2jQOie4QfuFEdvqxRFoV5YFwUr_49HvdN7DoMC26Pj6QIPtv6h7Luwlmvn8vG8iiawreYtv0-EJUxxwVulkZMaU8YBa5_VXg5gvGWzTYtKcf3iBtIfivGBw',

      });

      changeaddress.addLayers();
      const southWest = L.latLng(35.564629176277855, 51.265826416015625),
        northEast = L.latLng(35.81335872633348, 51.73187255859375),
        bounds = L.latLngBounds(southWest, northEast);

       //* Restrict to current bound
      // app.map.setMaxBounds(app.map.getBounds());

      // Restrict to other bounds
       changeaddress.map.setMaxBounds(bounds);

      changeaddress.map.on('click', function(e) {
      // آدرس یابی و نمایش نتیجه در یک باکس مشخص
       changeaddress.showReverseGeocode({
            state: {
                latlng: {
                lat: e.latlng.lat,
                lng: e.latlng.lng
                },
                zoom: 16
              }
            });

            changeaddress.addMarker({
                name: 'advanced-marker',
                latlng: {
                    lat: e.latlng.lat,
                    lng: e.latlng.lng
                },
                icon:changeaddress.icons.blue,
                popup: false

                });
            });
   
             changeaddress.addFullscreen();
        $("#changeAddressModal").modal("show");
    });
});


</script>

@endsection
