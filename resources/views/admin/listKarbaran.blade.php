@extends('layout')
@section('content')
<style>
  ul, #myUL {
        list-style-type: none;
        }

   #myUL {
        margin: 0;
        padding: 0;
        }

    .caret {
        cursor: pointer;
        -webkit-user-select: none; /* Safari 3.1+ */
        -moz-user-select: none; /* Firefox 2+ */
        -ms-user-select: none; /* IE 10+ */
        user-select: none;
        }
     span.caret {
            font-size:18px;
            font-weight:bold;
        }

    .lowLevelManager{
            font-size:16px;
            color:#0b2d62;
        }
 .caret::before {
        content: "\002B";
        color: black;
        display: inline-block;
        margin-right: 6px;
        font-size:26px;
         margin: 5px 5px 0px 5px;
    }

  .caret-minus::before {
        content: "\2212";
        color: black;
        display: inline-block;
        margin: 5px 5px;
        color:red;
         transform: rotate(0deg) !important; 
         font-size:26px;
        }


    .caret-down-minus::before {
        -ms-transform: rotate(90deg); /* IE 9 */
        -webkit-transform: rotate(90deg); /* Safari */'
        transform: rotate(90deg);  
        }

    .caret-down::before {
        -ms-transform: rotate(90deg); /* IE 9 */
        -webkit-transform: rotate(90deg); /* Safari */'
        transform: rotate(90deg);  
        }

    .nested {
        display: none;
        }

     .active {
        display: block;
        }


</style>
   <div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                    <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> کاربران </legend>
                        <div class="col-lg-12" style="margin-top:40vh">
                            <div class="row px-3">
                                <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" id="newAdminBtn">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                                <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="editAdmin" onclick="setKarbarEditStuff()"> ویرایش  <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                                <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="deleteAdmin" onclick="deleteAdminList()"> حذف  <i class="fa fa-trash fa-lg" aria-hidden="true" style="color:red;"></i></button>
                                <button type="button" class="btn btn-primary btn-sm text-warning buttonHover"  id="moveEmployee"> انتقال  <i class="fa fa-send fa-lg" aria-hidden="true" style="color:red;"></i></button>
                                <input type="hidden" id="AdminForAdd"/>
                            </div>
                        </div>
                    </fieldset>
                  </div>
                 <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> </div>
                    <div class="row mainContent"> 

                    <!-- start tree view -->
                     <div class="col-lg-12 rounded shadow bg-light">
                         <ul id="myUL">
                            @foreach($saleLines as $line)
                                <li><span class="caret ">{{$line->LineName}} </span>
                                    <ul class="nested">
                                        @foreach($line->manager as $manager)
                                            <li><span class="caret" onclick="setManagerStuff(this)">{{$manager->name .' '.$manager->lastName }} <input type="radio" class="form-check-input" style="display:none"  value="{{$manager->id}}" name="manager" id=""></span>
                                                <ul class="nested">
                                                    @foreach($manager->head as $head)
                                                        <li>
                                                            <span class="caret"  onclick="setHeadStuff(this,{{$head->id}})">{{$head->name .' '.$head->lastName }} <input type="radio" class="form-check-input"  style="display:none"   value="{{$head->id}}" name="head" id=""></span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach  
                                    </ul>
                                </li>
                            @endforeach
                            </ul> 
                     </div>
                     

                    <!-- end tree view -->
                        <div class="col-lg-12">
                            <table class="select-highlight table table-bordered table-striped" id="tableGroupList" style="display:none">
                                    <thead class="tableHeader">
                                        <tr>
                                            <th>ردیف</th>
                                            <th>نام کاربر</th>
                                            <th>نقش کاربری</th>
                                            <th>توضیحات</th>
                                            <th>فعال</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tableBody" id="adminGroupList" style="height:300px">
                                        @foreach ($admins as $admin)
                                            @if($admin->adminTypeId==1 or $admin->adminTypeId==5)
                                                
                                            <tr onclick="setAdminListStuff(this,{{$admin->adminTypeId}},{{$admin->id}},{{Session::get('asn')}})">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                                    <td>{{trim($admin->adminType)}}</td>
                                                    <td>{{trim($admin->discription)}}</td>
                                                    <td>
                                                        <input class="mainGroupId" type="radio" name="AdminId[]" value="{{$admin->id}}">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                            </table>

                            <table class="select-highlight table table-bordered table-striped forSecondHeadOfficer" id=" " style="display:none;">
                             <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th>نام کاربر</th>
                                    <th>نقش کاربری</th>
                                    <th>توضیحات</th>
                                    <th>فعال</th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="adminGroupList">
                            @foreach ($admins as $admin)
                                @if($admin->adminTypeId==4)

                                    <tr onclick="setAdminListStuff(this,{{$admin->adminTypeId}},{{$admin->id}},{{Session::get('asn')}})">
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                        <td>{{trim($admin->adminType)}}</td>
                                        <td>{{trim($admin->discription)}}</td>
                                        <td>
                                            <input class="mainGroupId" type="radio" name="AdminId[]" value="{{$admin->id}}">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        </div>
                        <div class="row">
                            <table class="select-highlight table table-bordered table-striped">
                                <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام </th>
                                        <th>شماره تماس</th>
                                        <th>توضیحات</th>
                                        <th>انتخاب</th>
                                    </tr>
                                </thead>
                                <tbody class="tableBody" id="customerListBody" style="height:300px">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row contentFooter"> </div>
                 </div>
           </div>
      </div>



        <!-- modal of new karabar -->
        <div class="modal fade dragableModal" id="newAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header py-2 text-white" style="margin:0; border:none">
                        <button type="button" class="btn-close bg-danger" style="background-color:red;" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="exampleModalLongTitle"> کابر جدید</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('/addAdmin')}}" method="POST"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                           <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام </label>
                                        <input type="text" required minlength="3" maxlength="12" class="form-control form-control-sm" autocomplete="off" name="name">
                                    </div>
                               </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام خانوادگی</label>
                                        <input type="text" required  minlength="3" maxlength="12" class="form-control form-control-sm" autocomplete="off" name="lastName">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label"> نام کاربری</label>
                                        <input type="text" id="userName"  minlength="3" maxlength="12" onblur="checkExistance(this)" required class="form-control form-control-sm" autocomplete="off" name="userName">
                                    </div>
                                </div>

                                 <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label"> رمز</label>
                                        <input type="text" onblur="clearRiplicateData()"  minlength="3" maxlength="12" required class="form-control form-control-sm" autocomplete="off" name="password" >
                                    </div>
                                </div> 
                                 <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> جنسیت  </label>
                                        <select class="form-select form-select-sm" name="sex">
                                                <option value="1" >زن </option>
                                                <option value="2" >مرد</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="form-label"> عکس </label>
                                        <input type="file" class="form-control form-control-sm" name="picture" placeholder="">
                                    </div>
                                </div>
                            </div> <br>

                           

                            <div class="row rounded px-0 mx-0" style="background-color:#abd2ed; padding-bottom:5px;"> 
                                   <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="dashboardLabel form-label"> نوع کاربر </label>
                                            <select class="form-select form-select-sm" name="employeeType"  id="employeeType">
                                                    <option value="1" > مدیر </option>
                                                    <option value="2" > سرپرست </option>
                                                    <option value="3" > کارمند </option>
                                            </select>
                                        </div>
                                    </div>
                              
                                 <div class="col-md-2">
                                        <div class="form-group" style="display:none" id="employeeJobDiv">
                                            <label class="dashboardLabel form-label">وظیفه کارمند</label>
                                            <select class="form-select form-select-sm" name="poshtibanType" >
                                                    <option value="1" >راننده</option>
                                                    <option value="2" >پشتیبان حضوری</option>
                                                    <option value="2" >پشتیبان هماهنگی</option>
                                                    <option value="2" >پشتیبان تلفنی</option>
                                                    <option value="3" >بازاریاب حضوری</option>
                                                    <option value="3" >بازاریاب هماهنگی</option>
                                                    <option value="3" >بازاریاب تلفنی</option>
                                            </select>
                                        </div>
                                </div> 

                                <div class="col-md-2">
                                    <div class="form-group"  style="display:none"  id="saleLineDive">
                                        <label class="dashboardLabel form-label"> خط فروش </label>
                                        <select class="form-select form-select-sm" name="saleLine">
                                                <option value="0" > -- </option>
                                            @foreach($saleLines as $saleLine)
                                                <option value="{{$saleLine->SaleLineSn}}" >{{$saleLine->LineName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                             
                                <div class="col-md-2">
                                    <div class="form-group" style="display:none" id="managerDiv">
                                        <label class="dashboardLabel form-label"> مدیر </label>
                                        <select class="form-select form-select-sm" name="manager" id="manager">
                                                <option value="0" > -- </option>
                                            @foreach($managers as $manager)
                                                <option value="{{$manager->id}}" > {{$manager->name .' '. $manager->lastName}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" style="display:none" id="headDiv">
                                        <label class="dashboardLabel form-label"> سرپرست </label>
                                        <select class="form-select form-select-sm" name="head" id="head">
                                                <option value="0" > -- </option>
                                        @foreach($heads as $head)
                                                <option value="{{$head->id}}" > {{$head->name .' '. $head->lastName}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-end">
                                <div class="col-md-4 mt-3">
                                    <div class="form-check">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">آیابه همه کاربران دسترسی داشته باشد؟</label>
                                        <input class="form-check-input p-2" name="hasAllCustomer" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    </div>
                                     <div class="form-check">
                                        <label class="form-check-label" for="flexSwitchCheckChecked"> آیا نظر سنجی داشته باشد؟</label>
                                        <input class="form-check-input p-2" name="hasAsses" type="checkbox" id="flexSwitchCheckChecked" checked>
                                    </div> 
                                </div>
                                <div class="col-md-8 mb-2">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label"> توضیحات</label>
                                            <textarea class="form-control"  minlength="3" rows="2" name="discription" style="background-color:blanchedalmond"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class='card mb-4' style="background-color:#abd2ed; padding-top:1%; paddding:0;">
                 <div class="container">
                        <ul class="header-list nav nav-tabs" data-tabs="tabs">
                            <li><a class="active" data-toggle="tab" style="color:black;" href="#webManagement"> اطلاعات پایه </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#definElement"> تعریف عناصر </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#kalasTab"> عملیات </a></li>
                            <li><a data-toggle="tab" style="color:black;"  href="#peoples"> گزارشات  </a></li>
                        </ul>
                        <div class="c-checkout tab-content" style="background-color:#f5f5f5; margin:0; margin-bottom:2%; padding:2%; border-radius:10px 10px 2px 2px; dir:ltr !important;">
                            <div class="c-checkout tab-pane active" id="webManagement" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                  <div class="row">
                                        <fieldset class="border rounded-3">
                                            <legend  class="float-none w-auto forLegend "> 
                                                 <input type="checkbox" name="manageWeb" class="webPageN form-check-input d-inline-block"/> اطلاعات پایه </legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6">
                                                     <input type="checkbox" name="homePageN" id="homePageN" class="webN form-check-input d-inline-block"/> پروفایل </legend>
                                                     
                                                <div class="form-check">
                                                    <input class="form-check-input box-check" id="homeDeleteN" type="checkbox" name="homeDeleteN">
                                                    <label class="form-check-label ">حذف</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input box-check " id="homeChangeN" type="checkbox" name="changeHomePageN">
                                                    <label class="form-check-label">تغییر</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input box-check" id="homeSeeN" type="checkbox" name="seeHomePageN">
                                                    <label class="form-check-label">مشاهده</label>
                                                </div>
                                            </fieldset>

                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6"> <input type="checkbox" name="karbaranN" id="karbaranN" class="webN form-check-input d-inline-block"/> R & D  &nbsp;</legend>
                                                       <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" name="karbaranN" id="karbaranN" class="webN form-check-input d-inline-block"/> وارد شده ها    &nbsp;</legend>
                                                            <div class="form-check">
                                                                <input class="form-check-input box-check" id="karbaranDeleteN" type="checkbox" name="karbaranDeleteN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="adminN form-check-input box-check" id="karbaranChangeN" type="checkbox" name="changeKarbaranN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" id="karbaranSeeN" type="checkbox"  name="seeKarbaranN">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                       <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" name="karbaranN" id="karbaranN" class="webN form-check-input d-inline-block"/> وارد نشده ها    &nbsp;</legend>
                                                            <div class="form-check">
                                                                <input class="form-check-input box-check" id="karbaranDeleteN" type="checkbox" name="karbaranDeleteN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="adminN form-check-input box-check" id="karbaranChangeN" type="checkbox" name="changeKarbaranN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" id="karbaranSeeN" type="checkbox"  name="seeKarbaranN">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6"> <input type="checkbox" id="specialSettingN" class="webN form-check-input d-inline-block" name="specialSettingN" /> افزودن خط فروش </legend>
                                                <div class="form-check">
                                                     <input class="form-check-input" id="specialDeleteN" type="checkbox" name="specialDeleteN"> 
                                                    <label class="form-check-label box-check">حذف</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="adminN form-check-input" type="checkbox" id="specialChangeN" name="changeSpecialSettingN">
                                                    <label class="form-check-label box-check">تغییر</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="poshtibanN web form-check-input" id="specialSeeN" type="checkbox" name="seeSpecialSettingN">
                                                    <label class="form-check-label box-check">مشاهده</label>
                                                </div>
                                            </fieldset>

                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6"> <input type="checkbox" id="specialSettingN" class="webN form-check-input d-inline-block" name="specialSettingN" /> تنظیمات </legend>
                                                     <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" id="specialSettingN" class="webN form-check-input d-inline-block" name="specialSettingN" /> سطح دسترسی </legend>
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="specialDeleteN" type="checkbox" name="specialDeleteN"> 
                                                                <label class="form-check-label box-check">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="adminN form-check-input" type="checkbox" id="specialChangeN" name="changeSpecialSettingN">
                                                                <label class="form-check-label box-check">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN web form-check-input" id="specialSeeN" type="checkbox" name="seeSpecialSettingN">
                                                                <label class="form-check-label box-check">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                     <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" id="specialSettingN" class="webN form-check-input d-inline-block" name="specialSettingN" />  تارگت ها و امتیازها  </legend>
                                                            <div class="form-check">
                                                                <input class="form-check-input" id="specialDeleteN" type="checkbox" name="specialDeleteN"> 
                                                                <label class="form-check-label box-check">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="adminN form-check-input" type="checkbox" id="specialChangeN" name="changeSpecialSettingN">
                                                                <label class="form-check-label box-check">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN web form-check-input" id="specialSeeN" type="checkbox" name="seeSpecialSettingN">
                                                                <label class="form-check-label box-check">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                </fieldset>
                                              </fieldset>
                                        </fieldset>
                                      </div>
                                </div>
                            </div>

                             <div class="c-checkout tab-pane" id="definElement" style="border-radius:10px 10px 2px 2px;">
                                  <div class="container">
                                      <div class="row">
                                            <fieldset class="border rounded-3">
                                                <legend  class="float-none w-auto fs-6"><input type="checkbox" class="messagesN form-check-input d-inline-block" name="messagesN" /> تعریف عناصر  </legend>
                                                <div class="form-check">
                                                    <input class="form-check-input box-check" id="deleteMessagesN" type="checkbox" name="deleteMessagesN">
                                                    <label class="form-check-label">حذف</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="adminN form-check-input box-check" type="checkbox" id="changeMessagesN" name="changeMessagesN">
                                                    <label class="form-check-label">تغییر</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeMessagesN" name="seeMessagesN">
                                                    <label class="form-check-label">مشاهده</label>
                                                </div>
                                             </fieldset>
                                        </div>
                                    </div>
                                </div>

                            <div class="c-checkout tab-pane" id="kalasTab" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto forLegend"><input type="checkbox" class="kalasN form-check-input d-inline-block" name="kalasN"/> عملیات </legend>

                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="kalaListN" name="kalaListN"/> تخصیص به کاربر </legend>
                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="kalaListN" name="kalaListN"/> مدیران  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                 </fieldset>
                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="kalaListN" name="kalaListN"/> سرپرستان  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                 </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="kalaListN" name="kalaListN"/> بازاریابها  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteKalaListN" type="checkbox" name="deleteKalaListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeKalaListN" name="changeKalaListN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeKalaListN" name="seeKalaListN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                 </fieldset>
                                        </fieldset>


                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="requestedKalaN" name="requestedKalaN"/>  راننده ها  </legend>
                                                  <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="requestedKalaN" name="requestedKalaN"/> سرویس راننده ها  </legend>
                                                    <div class="form-check">
                                                        <input class="form-check-input box-check" id="deleteRequestedKalaN" type="checkbox" name="deleteRequestedKalaN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="adminN form-check-input box-check " type="checkbox" id="changeRequestedKalaN" name="changeRequestedKalaN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check " type="checkbox" id="seeRequestedKalaN" name="seeRequestedKalaN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                                  <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="requestedKalaN" name="requestedKalaN"/>  بار گیری  </legend>
                                                    <div class="form-check">
                                                        <input class="form-check-input box-check" id="deleteRequestedKalaN" type="checkbox" name="deleteRequestedKalaN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="adminN form-check-input box-check " type="checkbox" id="changeRequestedKalaN" name="changeRequestedKalaN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check " type="checkbox" id="seeRequestedKalaN" name="seeRequestedKalaN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                        </fieldset>

                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="fastKalaN" name="fastKalaN"/> نظر سنجی </legend>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                     <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="fastKalaN" name="fastKalaN"/> نظرات امروز  </legend>
                                                            <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteFastKalaN" type="checkbox" name="deleteFastKalaN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeFastKalaN" name="changeFastKalaN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeFastKalaN" name="seeFastKalaN">
                                                                <label class="form-check-label">مشاهده</label>
                                                             </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                     <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="fastKalaN" name="fastKalaN"/> نظرات گذشته   </legend>
                                                            <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteFastKalaN" type="checkbox" name="deleteFastKalaN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeFastKalaN" name="changeFastKalaN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeFastKalaN" name="seeFastKalaN">
                                                                <label class="form-check-label">مشاهده</label>
                                                             </div>
                                                </fieldset>
                                                <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                     <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="fastKalaN" name="fastKalaN"/> نظرات انجام شده    </legend>
                                                            <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteFastKalaN" type="checkbox" name="deleteFastKalaN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeFastKalaN" name="changeFastKalaN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeFastKalaN" name="seeFastKalaN">
                                                                <label class="form-check-label">مشاهده</label>
                                                             </div>
                                                </fieldset>
                                        </fieldset>

                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="pishKharidN" name="pishKharidN" /> افزایش و کاهش امتیازات  </legend>
                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="pishKharidN" name="pishKharidN" />    اضافه شده  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deletePishKharidN" type="checkbox" name="deletePishKharidN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changePishKharidN" name="changePishKharidN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox"  id="seePishKharidN" name="seePishKharidN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                </fieldset>
                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="pishKharidN" name="pishKharidN" />  کاهش یافته  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deletePishKharidN" type="checkbox" name="deletePishKharidN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changePishKharidN" name="changePishKharidN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox"  id="seePishKharidN" name="seePishKharidN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                </fieldset>
                                        </fieldset>

                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="brandsN" name="brandsN"/>  R & D </legend>
                                             <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="brandsN" name="brandsN"/>  وارده شده  </legend>
                                                    <div class="form-check">
                                                        <input class="form-check-input box-check" id="deleteBrandsN" type="checkbox" name="deleteBrandsN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeBrandsN" name="changeBrandsN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeBrandsN" name="seeBrandsN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                             <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="brandsN" name="brandsN"/>  وارده نشده  </legend>
                                                    <div class="form-check">
                                                        <input class="form-check-input box-check" id="deleteBrandsN" type="checkbox" name="deleteBrandsN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeBrandsN" name="changeBrandsN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeBrandsN" name="seeBrandsN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                        </fieldset>

                                        
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="alertedN" name="alertedN"/> تقویم روزانه  </legend>
                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="alertedN" name="alertedN"/> تقویم روزانه </legend>
                                                    <div class="form-check">
                                                    <input class="form-check-input box-check" id="deleteAlertedN" type="checkbox" name="deleteAlertedN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeAlertedN" name="changeAlertedN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeAlertedN" name="seeAlertedN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                              <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="alertedN" name="alertedN"/> لیست مشتریان </legend>
                                                    <div class="form-check">
                                                    <input class="form-check-input box-check" id="deleteAlertedN" type="checkbox" name="deleteAlertedN">
                                                        <label class="form-check-label">حذف</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="adminN form-check-input box-check" type="checkbox" id="changeAlertedN" name="changeAlertedN">
                                                        <label class="form-check-label">تغییر</label>
                                                    </div>
                                                    <div class="form-check">
                                                    <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeAlertedN" name="seeAlertedN">
                                                        <label class="form-check-label">مشاهده</label>
                                                    </div>
                                                </fieldset>
                                        </fieldset>

                                        
                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="groupListN" name="groupListN" /> آلارم  </legend>
                                                 <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="groupListN" name="groupListN" /> آلارمها   </legend>
                                                        
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                     <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="groupListN" name="groupListN" />  انجام شده </legend>
                                                        
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                     <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="groupListN" name="groupListN" />  مشتریان فاقد آلارم   </legend>
                                                        
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                        </fieldset>

                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="groupListN" name="groupListN" /> پیام ها   </legend>
                                            
                                            <div class="form-check">
                                                 <input class="form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                <label class="form-check-label">حذف</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                <label class="form-check-label">تغییر</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                <label class="form-check-label">مشاهده</label>
                                            </div>
                                        </fieldset>

                                        <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="form-check-input d-inline-block" id="groupListN" name="groupListN" /> بار گیری   </legend>
                                            <div class="form-check">
                                                 <input class="form-check-input box-check" id="deleteGroupListN" type="checkbox" name="deleteGroupListN">
                                                <label class="form-check-label">حذف</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeGroupListN" name="changeGroupListN" >
                                                <label class="form-check-label">تغییر</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeGroupListN" name="seeGroupListN" >
                                                <label class="form-check-label">مشاهده</label>
                                            </div>
                                        </fieldset>
                                    </fieldset>
                                    </div>
                                </div>
                            </div>

                            <div class="c-checkout tab-pane" id="peoples" style="border-radius:10px 10px 2px 2px;">
                                <div class="container">
                                    <div class="row">
                                    <fieldset class="border rounded-3">
                                      <legend  class="float-none w-auto "><input type="checkbox" class="personsN form-check-input d-inline-block" name="personsN"/> گزارشات  </legend>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="customersN" name="customersN"/> عملکرد کاربران </legend>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="customersN" name="customersN"/> مدیران  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="customersN" name="customersN"/>  سرپرستان   </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="customersN" name="customersN"/>  پشتیبانها   </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="customersN" name="customersN"/>  بازاریابها    </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="customersN" name="customersN"/>  راننده ها     </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteCustomersN" type="checkbox" name="deleteCustomersN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeCustomersN" name="changeCustomersN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeCustomersN" name="seeCustomersN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                            </fieldset>


                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> تراز کاربران </legend>
                                                <div class="form-check">
                                                    <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                    <label class="form-check-label">حذف</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                    <label class="form-check-label">تغییر</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                    <label class="form-check-label">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/>  عملکرد مشتریان </legend>
                                                <div class="form-check">
                                                    <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                    <label class="form-check-label">حذف</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                    <label class="form-check-label">تغییر</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                    <label class="form-check-label">مشاهده</label>
                                                </div>
                                            </fieldset>
                                            
                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                              <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> عملکرد کالا </legend>
                                                  <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> گزارش فروش کالا  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                  <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> کالاهای برگشتی  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                  <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> کالاهای فقد موجودی  </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                                  <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                    <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> کالاهای راکت </legend>
                                                        <div class="form-check">
                                                            <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                            <label class="form-check-label">حذف</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                            <label class="form-check-label">تغییر</label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                            <label class="form-check-label">مشاهده</label>
                                                        </div>
                                                    </fieldset>
                                            </fieldset>

                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                              <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> گزارش برگشتی کالا </legend>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> تسویه شده  </legend>
                                                            <div class="form-check">
                                                                <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                                    <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                                        <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> تسویه شده  </legend>
                                                            <div class="form-check">
                                                                <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                                <label class="form-check-label">حذف</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                                <label class="form-check-label">تغییر</label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                                <label class="form-check-label">مشاهده</label>
                                                            </div>
                                                        </fieldset>
                                            </fieldset>

                                            <fieldset class="border rounded-3" style="display: justify-content:flex-start; float: right;">
                                            <legend  class="float-none w-auto fs-6"> <input type="checkbox" class="personN form-check-input d-inline-block" id="officialsN" name="officialsN"/> گزارش بارگیری </legend>
                                                <div class="form-check">
                                                    <input class="form-check-input box-check" id="deleteOfficialsN" type="checkbox" name="deleteOfficialsN">
                                                    <label class="form-check-label">حذف</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="adminN form-check-input box-check" type="checkbox" id="changeOfficialsN" name="changeOfficialsN">
                                                    <label class="form-check-label">تغییر</label>
                                                </div>
                                                <div class="form-check">
                                                     <input class="poshtibanN form-check-input box-check" type="checkbox" id="seeOfficialsN" name="seeOfficialsN">
                                                    <label class="form-check-label">مشاهده</label>
                                                </div>
                                            </fieldset>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                           
                            </div>
                         </div>
                       </div>
                     </div> 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="cancelAddAddmin"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                            <button type="submit" class="btn btn-sm btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button> 
                        </div>
                  </div>
                </form>
            </div>
        </div>
    </div>
</div>


  <!-- Modal for reading comments-->
    <div class="modal fade dragableModal" id="moveEmployeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog   modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                    <h5 class="modal-title" id="exampleModalLabel">انتقال کارمند</h5>

                </div>
                <div class="modal-body" id="readCustomerComment">
                    <div class="container">
                        <div class="row" style=" border:1px solid #dee2e6; padding:10px">
                        <div><button type="button" class="btn btn-sm btn-primary" id="moveEmployeeDoneBtn">انتقال <i class="fa fa-save fa-lg"></i></button></div>
                                <h4 style="padding:10px; border-bottom: 1px solid #dee2e6; text-align:center;">انتقال کارمند</h4>
                            </div>
                            <div class="row">
                                <table id="strCusDataTable"  class=' table table-bordered table-striped table-sm' style="background-color:#dee2e6">
                                    <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>اسم</th>
                                        <th>شماره تماس</th>
                                        <th>انتخاب</th>
                                    </tr>
                                    </thead>
                                    <tbody id="headList">
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


              <!-- modal for editing user profile -->
              <div class="modal fade dragableModal" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin:0; border:none">
                            <h5 class="modal-title" id="exampleModalLongTitle"> ویرایش پروفایل </h5>
                        </div>
                        <div class="modal-body">
                                <form action="{{url('/editAdmintListStuff')}}" method="POST"  enctype="multipart/form-data">
                                    <input type="hidden" id="adminId" name="adminId">
                                    @csrf
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> نام </label>
                                                <input type="text" required class="form-control" autocomplete="off" name="name" id="adminName">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> نام خانوادگی</label>
                                                <input type="text" required class="form-control" autocomplete="off" name="lastName" id="adminLastName">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> نام کاربری</label>
                                                <input type="text" required class="form-control" autocomplete="off" name="userName" id="adminUserName">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> شماره تماس </label>
                                                <input type="number" required class="form-control" autocomplete="off" name="phone" id="adminPhone">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> ادرس  </label>
                                                <input type="text" required class="form-control" autocomplete="off" name="address" id="adminAddress">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> رمز کاربری</label>
                                                <input type="text" required class="form-control" autocomplete="off" name="password" id="adminPassword">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> جنسیت  </label>
                                                <select class="form-select" name="sex" id="adminSex">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> نوع کاربر </label>
                                                <option value=""> -- </option>
                                                <select class="form-select" name="employeeType"  id="employeeTypeEdit">
                                                        <option value="1" id="managerEdit" > مدیر </option>
                                                        <option value="2" id="headEdit"> سرپرست </option>
                                                        <option value="3" id="employeeEdit"> کارمند </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group"  style="display:none" id="employeeJobDivEdit">
                                        <label class="dashboardLabel form-label">وظیفه کارمند</label>
                                        <select class="form-select" name="poshtibanType" >
                                                <option value="4" id="jobEdit1">راننده</option>
                                                <option value="2" id="jobEdit2">پشتیبان حضوری</option>
                                                <option value="2" id="jobEdit3">پشتیبان هماهنگی</option>
                                                <option value="2" id="jobEdit4">پشتیبان تلفنی</option>
                                                <option value="3" id="jobEdit5">بازاریاب حضوری</option>
                                                <option value="3" id="jobEdit6">بازاریاب هماهنگی</option>
                                                <option value="3" id="jobEdit7">بازاریاب تلفنی</option>
                                        </select>
                                    </div>
                                </div> 

                                <div class="col-md-6">
                                    <div class="form-group"  style="display:none"  id="saleLineDivEdit">
                                        <label class="dashboardLabel form-label"> خط فروش </label>
                                        <select class="form-select" name="saleLine">
                                                <option value="0" > -- </option>
                                            @foreach($saleLines as $saleLine)
                                                <option value="{{$saleLine->SaleLineSn}}" id="saleLineWork{{$saleLine->SaleLineSn}}">{{$saleLine->LineName}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group"  style="display:none" id="managerDivEdit">
                                        <label class="dashboardLabel form-label"> مدیر </label>
                                        <select class="form-select" name="manager">
                                                <option id="managerIdEdit" value=""> -- </option>
                                            @foreach($managers as $manager)
                                                <option value="{{$manager->id}}" id="manageWork{{$manager->id}}"> {{$manager->name .' '. $manager->lastName}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group"  style="display:none" id="headDivEdit">
                                        <label class="dashboardLabel form-label"> سرپرست </label>
                                        <select class="form-select" name="head" id="head">
                                                <option id="headIdEdit" value=""> -- </option>
                                        @foreach($heads as $head)
                                                <option value="{{$head->id}}" id="headWork{{$head->id}}"> {{$head->name .' '. $head->lastName}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                                    <div class="row"> 
                                        <div class="col-md-6 ps-5">
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input me-0" name="hasAsses" type="checkbox" id="adminHasAssess" checked style="font-size:25px;">
                                                <label class="form-check-label" for="flexSwitchCheckChecked">آیا نظر سنجی داشته باشد؟</label>
                                            </div> 
                                            </div> 
                                            <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="dashboardLabel form-label"> عکس </label>
                                                <input type="file" class="form-control" name="picture">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row"> 
                                        <div class="col-md-6 ps-5">
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input me-0" name="hasAllCustomer" type="checkbox" id="hasAllCustomer" checked style="font-size:25px;">
                                                <label class="form-check-label">آیابه همه کاربران دسترسی داشته باشد؟</label>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input me-0" name="hasAlarm" type="checkbox" id="hasAlarm" style="font-size:25px;">
                                                <label class="form-check-label">آیابه آلارم ها دسترسی داشته باشد؟</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label"> توضیحات</label>
                                                <textarea class="form-control" cols="10" rows="4" name="discription" style="background-color:blanchedalmond" id="adminDiscription"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"> 
                                    <div class="form-group" style="margin-top:4%">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelEditProfile"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                        <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                                    </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>

<script>
	$("#newAdminBtn").on("click", ()=>{
		   if (!($('.modal.in').length)) {
                $('.modal-dialog').css({
                  top: 0,
                  left: 0
                });
              }
              $('#newAdmin').modal({
                backdrop: false,
                show: true
              });
              
              $('.modal-dialog').draggable({
                  handle: ".modal-header"
                });
		$("#newAdmin").modal("show");
	});
	

var toggler = document.getElementsByClassName("caret");
var i;

for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function() {
        $(this).toggleClass(function(){
          return $(this).is('.caret::before, .caret-minus') ? 'caret::before caret-minus' : 'caret-minus';
     })
      
    this.parentElement.querySelector(".nested").classList.toggle("active");
    this.classList.toggle("caret-down caret-down-minus::before");
  });
}
</script>
@endsection

