@extends('layout')
@section('content')

<style>
    label { font-size:14px; font-weight: bold; }
#getPassword {
    cursor: pointer;
}


.bazarYabaction {
    min-width: 140px;
}
</style>
    <div class="container-xl" style="margin-top:6%;">
            <div class="row">
                <h3 class="page-title"> مشتریان R&D </h3>
            </div>
            <div class="row">
                     <div class="col-sm-2 mb-1">
                        <div class="form-group ">
                            <input type="text" name="" size="20" placeholder="جستجو" class="form-control publicTop" id="allKalaFirst">
                        </div>
                     </div>
                     <div class="col-sm-2">
                        <div class="form-group">
                            <select class="form-select publicTop" id="searchGroup">
                                <option value="0"> موقعیت </option>
                                <option value="0">موقعیت دار </option>
                                <option value="0"> بدون موقعیت </option>
                            </select>
                        </div>
                     </div>
                     <div class="col-sm-8" style="display:flex; justify-content:flex-end">
                        @csrf
                        <!-- <button class='enableBtn btn btn-primary btn-sm text-warning mx-1' type="button" disabled id='openDashboard'> داشبورد <i class="fal fa-dashboard"></i></button> -->
                        @if(Session::get('adminType')==1 or Session::get('adminType')==5)
                        <button class='enableBtn btn btn-primary btn-sm btn-md text-warning buttonHover'   style="width:170px;"  disabled id="takhsisButton">بررسی مشتری<i class="fal fa-tasks fa-lg"> </i> </button>
                        @endif
                        
                        <button class='enableBtn btn btn-primary btn-sm text-warning mx-1' type="button" disabled id="editRTbtn">ویرایش <i class="fa fa-plus-square fa-lg"></i></button>            
                       
                        <button class='enableBtn btn btn-primary btn-sm text-warning mx-1' type="button" id="addingNewCustomerBtn"> مشتری جدید  <i class="fa fa-plus-square fa-lg"></i></button>            
                    </div>
            </div>  
            <div class="row">
                <div class="col-lg-12 p-2">
                     <table class='table table-bordered table-striped homeTables'>
                        <thead class="tableHeader">
                        <tr>
                            <th class="mobileDisplay">ردیف</th>
                            <th style="width:122px;">اسم</th>
                            <th class="mobileDisplay" style="width:111px;">شماره تماس</th>
                            <th class="mobileDisplay" style="width:88px">منطقه </th>
                            <th style="width:88px">تاریخ ثبت</th>
                            <th> ادرس</th>
                            <th>انتخاب</th>
                        </tr>
                        </thead>
                        <tbody class="select-highlight tableBody" id="customerListBody1">
                            @foreach($customers as $customer)
                                <tr onclick="setEditRTStuff({{$customer->PSN}})">
                                    <td class="mobileDisplay" style="width:40px">{{$loop->iteration}}</td>
                                    <td style="width:122px;">{{$customer->Name}}</td>
                                    <td class="mobileDisplay" style="width:111px;">{{$customer->PhoneStr}}</td>
                                    <td class="mobileDisplay" style="width:88px">{{$customer->NameRec}}</td>
                                    <td style="width:88px">{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($customer->TimeStamp))->format("Y/m/d")}}</td>
                                    <td>{{$customer->peopeladdress}}</td>
                                    <td> <input class="customerList form-check-input" name="customerId" type="radio" value="{{$customer->PSN.'_'.$customer->GroupCode}}"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


    <!-- modal of adding new customer -->
    <div class="modal fade dragableModal" id="addingNewCutomer" tabindex="-1"  data-bs-backdrop="static" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header" style="margin:0; border:none">
                    <button type="button" class="btn-close btn-danger" style="background-color:red;" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLongTitle"> افزودن مشتری </h5>
                </div>
                <div class="modal-body">
                    <form action="{{url('/addRandT')}}" method="POST"  enctype="multipart/form-data">
                    @csrf    
                    <div class="row">
                            <div class="col-md-3 col-sm-4 ">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label">نام و نام خانوادگی</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="name">
                                </div>
                            </div>
						    <div class="col-md-3 col-sm-4 col-xs-5">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label">نام رستوران</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="restaurantName">
                                </div>
                            </div>
						
                            <div class="col-md-2 col-sm-4 col-xs-7">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> شماره همراه  </label>
                                    <input type="tel" required class="form-control" autocomplete="off" name="mobilePhone" maxlength="11">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-3 col-10">
                                   <div class="form-group ">
                                        <label class="dashboardLabel dashboardLabel form-label"> شماره ثابت </label>
                                             <div class="input-group  input-group-sm">
                                                 <input type="tel" style="height:40px !important;" required class="form-control p-0 " autocomplete="off" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="sabitPhone" min="0"  maxlength = "8">
                                            <div class="input-group-append">
                                                <select class="form-select" name="PhoneCode" id="PhoneCode">
                                                    @foreach($phoeCodes as $code)
                                                    <option value="{{$code->provinceCode}}">{{$code->provinceCode}}</option>
                                                    @endforeach
                                                </select>
                                            </div> &nbsp;
                                       <!--   <span id="addProvinceCode" data-toggle="modal" data-target="#countryCodeModal" style="margin-top:5px; color:blue; font-size:22px;"> <i class="fa fa-plus-circle fa-lg"></i> </sapn> -->
                                       </div>
                                 </div>
                            </div>
                            <div class="col-md-1 col-sm-1 col-1" style="margin-top:33px;"> 
                                <span id="addProvinceCode" data-toggle="modal" data-target="#countryCodeModal" style="margin-top:55px; color:blue; font-size:22px;"> <i class="fa fa-plus-circle fa-lg"></i> </sapn>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> ادرس کامل  </label>
                                    <input type="text" required class="form-control" autocomplete="off" name="peopeladdress">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> جنسیت</label>
                                    <select class="form-select" name="gender">
                                        <option value="2">مرد</option>
                                        <option value="1" >زن</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> شهر</label>
                                    <select class="form-select" id="searchCity" name="snNahiyeh">
                                        @foreach($cities as $city)
                                        <option value="{{$city->SnMNM}}" >{{$city->NameRec}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
							   <div class="col-md-2">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> منطقه </label>
                                    <select class="form-select" id="searchMantagheh" name="snMantagheh">
                                        @foreach ($mantagheh as $mantaghe) {
                                        <option value="{{$mantaghe->SnMNM}}">{{$mantaghe->NameRec}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
							<div class="col-md-2">
                                 <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> نوع مشتری </label>
                                    <select class="form-select" name="secondGroupCode">
										<option value="7" >رستوران</option>
										<option value="8" >کترينگ</option>
										<option value="9" >فست فود</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            @if(Session::get('adminType')==1 or Session::get('adminType')==5)
                            <div class="col-md-2">
                                <div class="form-group">
                                <label class="dashboardLabel dashboardLabel form-label">پشتیبان</label>
                                    <select class="form-select" name="adminId" id="">
                                        @foreach($admins as $admin)
                                        <option value="{{$admin->id}}">{{$admin->name.' '.$admin->lastName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="adminId" value="{{Session::get('asn')}}">
                            @endif
                            <div class="col-md-4">
                               <div class="form-group">
                               <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label">توضیح</label>
                                    <textarea name="discription"   required class="form-control" cols="20" rows="3"></textarea>
                                </div>
                                </div>
                            </div>  
							<div class="col-md-3">           
                                 <input type="text" id="customerLocation" name="location">  
                               <button type="button" class="btn btn-success mt-3" id="openCurrentLocationModal" >دریافت لوکیشن خودکار</button>
                           </div>
						</div>
                   
                        <div class="modal-footer mt-2">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                            <button type="submit" id="submitRT" disabled class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
</div>
		

    <!-- modal of editting new customer -->
    <div class="modal fade dragableModal" id="editNewCustomer" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header" style="margin:0; border:none">
                    <h5 class="modal-title" id="exampleModalLongTitle"> ویرایش مشتری</h5>
                </div>
                <div class="modal-body">
                    <form action="{{url('/editRT')}}" method="POST"  enctype="multipart/form-data">
                    @csrf   
                    <input type="hidden" name="customerId" id="customerID" value="3004345"> 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label">نام و نام خانوادگی</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="name" id="name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label">کد</label>
                                    <input type="text" required class="form-control" autocomplete="off" name="PCode" id="PCode">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> شماره همراه  </label>
                                    <input type="number" required class="form-control" autocomplete="off" name="mobilePhone" id="mobilePhone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> شماره ثابت </label>
                                    <input type="number" required class="form-control" autocomplete="off" name="sabitPhone" id="sabitPhone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> جنسیت</label>
                                    <select class="form-select" name="gender" id="gender">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> شهر</label>
                                    <select class="form-select" name="snNahiyeh" id="snNahiyehE">
                                        @foreach($cities as $city)
                                        <option value="{{$city->SnMNM}}" >{{$city->NameRec}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> منطقه </label>
                                    <select class="form-select" name="snMantagheh" id="snMantaghehE">
                                        @foreach ($mantagheh as $mantaghe) {
                                        <option value="{{$mantaghe->SnMNM}}">{{$mantaghe->NameRec}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> ادرس کامل  </label>
                                    <input type="text" required class="form-control" autocomplete="off" name="peopeladdress" id="peopeladdress">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label"> نوع مشتری </label>
                                    <select class="form-select" name="secondGroupCode">
										<option value="7" >رستوران</option>
										<option value="8" >کترينگ</option>
										<option value="9" >فست فود</option>
                                    </select>
                                </div>
                            </div>
							<div class="col-md-6">           
                                <div class="form-group">
                                    <label class="dashboardLabel dashboardLabel form-label">توضیح</label>
                                    <textarea name="discription"  required class="form-control" id="discription" cols="20" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group" style="margin-top:4%">
                            <button type="button" class="btn btn-danger" id="cancelEditCustomer"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                            <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   </div>


  {{-- dashbor modal --}}

 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

        <div class="modal" id="currentLocationModal" tabindex="-1" data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="background-color:red"></button>
                        <h5 class="modal-title" id="exampleModalLabel"> تعیین موقعیت </h5>
                    </div>
                        <div class="modal-body">
                             <div id="mapId" style="width: 100%; height: 60vh"></div> 
                        </div>
                    <div class="modal-footer">
                           <button type="button" class="btn btn-primary" disabled id="saveLocationBtn" onclick="saveLocation()">ذخیره <i class="fa fa-save"></i> </button>
						<input type="text" id="currentLocationInput">
						   <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن <i class="fa fa-x-mark"></i> </button>
                    </div>
                </div>
            </div>
        </div>

  
<script>
 
function generatePassword() {
    var length = 4,
        charset = "0123456789",
        retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    document.querySelector("#passwordValue").value = retVal;
}

 // for changing map
 $("#openCurrentLocationModal").on("click", ()=>{
	 var map_init = L.map('mapId').setView([35.70163, 51.39211], 12);
	
	 
        var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map_init);

       var lc = L.Control.geocoder().addTo(map_init);
        if (!navigator.geolocation) {
            console.log("لطفا مرورگر خویش را آپدیت نمایید!")
        } else {
            setInterval(() => {
                navigator.geolocation.getCurrentPosition(getPosition)
            }, 5000);
        };

        var marker, circle, lat, long, accuracy;

        function getPosition(position) {
            // console.log(position)
            lat = position.coords.latitude
            long = position.coords.longitude
            accuracy = position.coords.accuracy

            if (marker) {
                map_init.removeLayer(marker)
            }

            if (circle) {
                map_init.removeLayer(circle)
            }

            marker = L.marker([lat, long]);
            circle = L.circle([lat, long], { radius: accuracy });
            var featureGroup = L.featureGroup([marker, circle]).addTo(map_init);
            map_init.fitBounds(featureGroup.getBounds());
			$("#currentLocationInput").val(lat+','+long);
			$("#saveLocationBtn").prop("disabled",false);
            $("#submitRT").prop("disabled",false);
            //alert("Your coordinate is: Lat: " + lat + " Long: " + long + " Accuracy: " + accuracy);
        }
	  
	
       $("#currentLocationModal").modal("show");
            
        setTimeout(() => {
            map_init.invalidateSize();
        }, 500);

});
	
	function saveLocation(){
		$("#customerLocation").val($("#currentLocationInput").val());
		$("#openCurrentLocationModal").prop("disabled",true);
		$("#currentLocationModa").modal("hide");
	}
	
$("#addingNewCustomerBtn").on("click", ()=>{	
		$("#addingNewCutomer").modal("show");

});
</script>
@endsection
