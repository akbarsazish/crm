

@extends('layout')
@section('content')

 <div class="container-fluid containerDiv">
            <div class="spinner-border text-danger" role="status" id="transferLoader" style="display:none;">
                <span class="sr-only">Loading...</span>
            </div>
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0">  تخصیص به کاربران   </legend>
                        <!-- <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="elseSettingsRadio">
                            <label class="form-check-label me-4" for="assesPast">  سطح دسترسی  </label>
                        </div>
                         -->
                         <div class="form-group mb-1 col-sm-12">
                            <input type="text" style="display:none" id="asn"/>
                                <select name=""  class="form-select form-select-sm" id="searchCity">
                                <option value="" hidden>شهر</option>
                                <option value="0">همه</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->SnMNM}}">{{trim($city->NameRec)}}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="form-group mb-1 col-sm-12">
                            <select name="" class="form-select form-select-sm" id="searchMantagheh">
                                <option hidden>منطقه</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12">
                            <select name="" class="form-select form-select-sm" disabled id="activeOrInActive">
                                <option hidden>نوعیت مشتری</option>
                                <option value="1">فعال</option>
                                <option value="2">غیر فعال</option>
                                <option value="3">جدیدی ها</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12">
                            <input type="text" name="" placeholder="اسم" size="20" class="form-control form-control-sm" id="searchNameByMNM">
                        </div>
                        
                    </fieldset>
                  </div>
                <div class="col-sm-8 col-md-8 col-sm-12 contentDiv">
                    <div class="row contentHeader">
                        <div class="col-lg-12 text-start">
                              <button type="button" class="btn btn-primary btn-sm buttonHover text-warning" data-toggle="modal" data-target="#newAdmin">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                             <button type="button" class="btn btn-primary btn-sm buttonHover text-warning" disabled id="emptyKarbarButton" >تخلیه کاربر <i class="fa fas fa-upload fa-lg" aria-hidden="true"></i></button>
                             <button type="button" class="btn btn-primary btn-sm buttonHover text-warning" disabled id="moveKarbarButton">تغییر کاربر <i class="fa fas fa-sync fa-lg" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-sm buttonHover text-warning"  onclick="setKarbarEditStuff()" >ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-sm buttonHover text-warning" disabled id="deleteAdmin">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>

                        </div>
                    </div>
                    <div class="row mainContent">
                          <div class="row text-center mx-0 px-0" id="customerContainer">
                            <div class="col-sm-5 px-0">
                                <input type="text" id="AdminForAdd" style="display: none" >
                                   <div class='c-checkout'>
                                     <table class="table table-bordered table-striped table-hover" id="allCustomers">
                                        <thead class="tableHeader">
                                            <tr>
                                                <th>ردیف</th>
                                                <th> منطقه  </th>
                                                <th> نام و نام خانوادگی</th>
                                                <th> <input type="checkbox" name="" class="selectAllFromTop form-check-input" id="selectAllTopRight"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="allCustomer" class="tableBody">
                                        </tbody>
                                     </table>
                                </div>
                          </div>

                        <div class="col-sm-2" style="">
                            <div class='modal-body' style="position:relative; right: 3%; top: 30%;">
                                <div style="">
                                    <a id="addCustomerToAdmin"> <i class="fa-regular fa-circle-chevron-left fa-3x"></i></a>
                                    <br />
                                    <a id="removeCustomerFromAdmin"> <i class="fa-regular fa-circle-chevron-right fa-3x"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 px-0" style="border-right:1px solid white;">
                                  <div class='c-checkout'>
                                     <table class="table table-bordered table-striped table-hover"  id="addedCustomers" style="100%">
                                        <thead class="tableHeader">
                                             <tr>
                                                <th>ردیف</th>
                                                <th> منطقه  </th>
                                                <th> نام و نام خانوادگی</th>
                                                <th> <input type="checkbox" name="" class="selectAllFromTop form-check-input" id="selectAllTopLeft"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="addedCustomer" class="tableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                 
                     </div>
                    <div class="row contentFooter"> </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                 <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0">  تخصیص به کاربران   </legend>
                            <div class="form-group mb-1 col-sm-12">
                            <select name=""  class="form-select form-select-sm" id="searchAddedCity">
                            <option value="0" hidden>شهر</option>
                            <option value="1" >همه</option>
                                @foreach($cities as $city)
                                <option value="{{$city->SnMNM}}">{{trim($city->NameRec)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12">
                            <select name="" class="form-select form-select-sm" id="searchAddedMantagheh">
                                <option value="" hidden>منطقه</option>
                                <option value="0">همه</option>
                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12">
                            <input type="text" name="" size="20" placeholder="اسم" class="form-control form-control-sm" id="searchAddedNameByMNM">
                        </div>
                    </fieldset>
               </div>
        </div>
    </div>

    
               
                    
         

        
        <!-- modal of new Brand -->
        <div class="modal fade dragableModal" id="newAdmin" tabindex="-1" role="dialog"   data-backdrop="static" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0; border:none">
                        <h5 class="modal-title" id="exampleModalLongTitle"> کابر جدید</h5>
                    </div>
                    <div class="modal-body">
                            <form action="{{url('/addAdminFromList')}}" method="POST"  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="dashboardLabel form-label"> نام </label>
                                    <input type="text" required minlength="3" maxlength="12" class="form-control" autocomplete="off" name="name">
                                </div>
                                </div>
                                <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="dashboardLabel form-label"> نام خانوادگی</label>
                                    <input type="text" required  minlength="3" maxlength="12" class="form-control" autocomplete="off" name="lastName">
                                </div>
                                </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label"> نام کاربری</label>
                                    <input type="text" id="userName"  minlength="3" maxlength="12" onblur="checkExistance(this)" required class="form-control" autocomplete="off" name="userName">
                                </div>
                                </div>
                                <div class="col-lg-6">
                                <span id="existAlert" style="color: red"> </span>
                                <div class="form-group">
                                    <label class="form-label"> شماره تماس </label>
                                    <input type="number"   minlength="11" maxlength="12" required class="form-control" autocomplete="off" name="phone">
                                </div>
                                </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label"> آدرس </label>
                                    <input type="text" required  minlength="3" maxlength="12" class="form-control" autocomplete="off" name="address" >
                                </div>
                                </div>
                                <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label"> رمز</label>
                                    <input type="text" onblur="clearRiplicateData()"  minlength="3" maxlength="12" required class="form-control" autocomplete="off" name="password" >
                                </div>
                                </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="dashboardLabel form-label"> جنسیت  </label>
                                    <select class="form-select" name="sex">
                                            <option value="" >همه</option>
                                            <option value="1" >زن </option>
                                            <option value="2" >مرد</option>
                                    </select>
                                </div>
                                </div>
                                <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="dashboardLabel form-label"> نوع کاربر </label>
                                    <select class="form-select" name="adminType">
                                            <option value="" >همه</option>
                                            <option value="1" >ادمین</option>
                                            <option value="2" >پشتیبان</option>
                                            <option value="3" >بازاریاب</option>
                                    </select>
                                </div>
                                </div>
                                </div>

                                <div class="row">

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label"> عکس </label>
                                            <input type="file" class="form-control" required name="picture" placeholder="">
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6 ps-5">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input me-0" name="hasAsses" type="checkbox" checked style="font-size:25px;">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">آیا نظر سنجی داشته باشد؟</label>
                                        </div> 
                                    </div>

                                </div>

                                <div class="row">

                                <div class="col-lg-12 ps-5">
                                <label class="form-check-label" for="flexSwitchCheckChecked">زیر نظر</label>
                                    <select class="form-select" name="bossId">
                                    <option value="0">هیچ کس</option>
                                        @foreach($adminList as $admin)
                                        <option value="{{$admin->id}}">{{$admin->name.' '.$admin->lastName}}</option>
                                        @endforeach
                                </select>
                                </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6 ps-5">
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input me-0" name="hasAllCustomer" type="checkbox" checked style="font-size:25px;">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">آیا به همه کاربران دسترسی داشته باشد؟</label>
                                        </div> 
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label"> توضیحات</label>
                                            <textarea class="form-control"  minlength="3" maxlength="12" cols="10" rows="4" name="discription" style="background-color:blanchedalmond"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                               
                           
                                <div class="form-group" style="margin-top:4%">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelAddAddmin"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                    <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
              <!-- modal for editing user profile -->
              <div class="modal fade dragableModal" id="editProfile" tabindex="-1" role="dialog"   data-backdrop="static"  aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin:0; border:none">
                            <h5 class="modal-title" id="exampleModalLongTitle"> ویرایش پروفایل </h5>
                        </div>
                        <div class="modal-body">
                                <form action="{{url('/editAdmintStuff')}}" method="POST"  enctype="multipart/form-data">
                                    @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام </label>
                                        <input type="text" required class="form-control" autocomplete="off" name="name" id="adminName">
                                    </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام خانوادگی</label>
                                        <input type="text" required class="form-control" autocomplete="off" name="lastName" id="adminLastName">
                                    </div>
                                    </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام کاربری</label>
                                        <input type="text" required class="form-control" autocomplete="off" name="userName" id="adminUserName">
                                    </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> شماره تماس </label>
                                        <input type="number" required class="form-control" autocomplete="off" name="phone" id="adminPhone">
                                    </div>
                                    </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> ادرس  </label>
                                        <input type="text" required class="form-control" autocomplete="off" name="address" id="adminAddress">
                                    </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> رمز کاربری</label>
                                        <input type="text" required class="form-control" autocomplete="off" name="password" id="adminPassword">
                                    </div>
                                    </div>
                                    </div>

                                    <div class="row">
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> جنسیت  </label>
                                        <select class="form-select" name="sex" id="adminSex">
                                        </select>
                                    </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نوع کاربر </label>
                                        <select class="form-select" name="adminType" id="editAdminType">
                                        </select>
                                        <input class="form-control" style="display: none" name="adminId" id="editAdminID">
                                    </div>
                                    </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 ps-5">
                                            <div class="form-check form-switch mt-2" style="margin-left:10px;">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">آیا نظر سنجی داشته باشد؟</label>
                                                <input class="form-check-input me-0" name="hasAsses" type="checkbox" id="adminHasAssess" checked style="font-size:25px;">
                                            </div> 
                                        </div> 
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> عکس </label>
                                                <input type="file" class="form-control" name="picture">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6"  id='assignBossDiv'>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">زیر نظر</label>
                                                <select class="form-select" name="bossId" id="bosses">
                                            </select>
                                        </div>
                                        <div class="col-lg-6"  id="poshtibanDiv">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">نوعیت کارمند</label>
                                                <select class="form-select" name="poshtibanType" id="poshtibanType">
                                                    <option value="1">پشتیبان حضوری</option>
                                                    <option value="2">پشتیبان هماهنگی</option>
                                                    <option value="3"> پشتیبان تلفنی</option>
                                                    <option value="4"> راننده </option>
                                                </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6 ps-5">
                                            <div class="form-check form-switch mt-2">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">آیا به همه کاربران دسترسی داشته باشد؟</label>
                                                <input class="form-check-input" name="hasAllCustomer" id="hasAllCustomer" type="checkbox" checked style="font-size:25px;">
                                            </div> 
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"> توضیحات</label>
                                                <textarea class="form-control" cols="10" rows="4" name="discription" style="background-color:blanchedalmond" id="adminDiscription"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-top:4%">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelEditProfile"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                        <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
             <!-- modal for removing user profile -->
             <div class="modal fade dragableModal" id="removeKarbar" tabindex="-1" role="dialog"   data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin:0; border:none">
                            <h5 class="modal-title" id="exampleModalLongTitle"> تخلیه مشتریان کاربر </h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>نام کاربر </th>
                                        <th>نقش کاربر </th>
                                        <th style="width:600px">توضیحات</th>
                                  </tr>
                                </thead>
                                <tbody id="emptyKarbar" class="tableBody">
                                 </tbody>
                            </table>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelRemoveKarbar"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                            <button type="button" onclick="removeStaff()" class="bt btn-danger btn-lg">تخلیه <i class="fa fa-upload"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
             <!-- modal for removing user profile -->
             <div class="modal fade dragableModal" id="moveKarbar" role="dialog"   data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin:0; border:none">
                            <button type="button" class="btn-close btn-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="exampleModalLongTitle"> انتقال مشتریان از کاربر به کاربر  </h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>نام کاربر </th>
                                        <th>نقش کاربر </th>
                                        <th>توضیحات</th>
                                  </tr>
                                </thead>
                                <tbody id="adminToMove" class="tableBody">

                                </tbody>
                            </table>
                                <input type="text" id="adminID" >
                                <input type="text" id="adminTakerId">
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
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelMoveKarbar"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                            <button type="button" onclick="moveStaff()"  class="bt btn-danger btn-lg"> انتقال <i class="fa fa-sync"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
