@extends('layout')
@section('content')
    <div class="container" style="margin-top:6%;">
    <div class="row">
        <div class="col-md-6">
         <fieldset class="rounded">
             <legend class="float-none w-auto">ادمین ها</legend>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6" style="display:flex; justify-content:flex-end;">
                        <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" id="newAdminBtn">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                        <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="editAdmin" onclick="setKarbarEditStuff()" >ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="deleteAdmin" onclick="deleteAdminList()">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        <input type="hidden" id="AdminForAdd"/>
                    </div>
                </div>
                
                    <table class="select-highlight table table-bordered table-striped" id="tableGroupList">
                        <thead class="tableHeader">
                            <tr>
                                <th>ردیف</th>
                                <th>نام کاربر</th>
                                <th>نقش کاربری</th>
                                <th>توضیحات</th>
                                <th>فعال</th>
                            </tr>
                        </thead>
                        <tbody class="c-checkout tableBody" id="adminGroupList">
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
                 </fieldset>
            </div>

            <div class="col-md-6">
                <fieldset class="rounded">
                <legend  class="float-none w-auto"> راننده ها</legend>
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6" style="display:flex; justify-content:flex-end;">
                        <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" data-toggle="modal" data-target="#newAdmin">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                        <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="editDriver" onclick="setKarbarEditStuff()" >ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                        <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="deleteDriver"  onclick="deleteAdminList()">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                      </div>
                    </div>
                        <table class="select-highlight table table-bordered table-striped" id="tableGroupList">
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
                      </fieldset>
                </div>
            </div>
        <div class="row" style="margin-top:2%">
            <div class="col-md-6">
                <fieldset class="rounded">
                    <legend  class="float-none w-auto">  پشتیبان ها</legend>
                    <div class="row">
                       <div class="col-sm-6"></div>
                          <div class="col-sm-6" style="display:flex; justify-content:flex-end;">
                            <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" data-toggle="modal" data-target="#newAdmin">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                            <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="editSupporter" onclick="setKarbarEditStuff()" >ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="deleteSupporter"  onclick="deleteAdminList()">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                        </div>
                      </div>
                        <table class="select-highlight table-bordered table-striped" id="tableGroupList">
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
                                    @if($admin->adminTypeId==2)

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
                     </fieldset>
                </div>

                <div class="col-md-6">
                <fieldset class="rounded">
                    <legend  class="float-none w-auto">  بازاریاب ها</legend>
                    <div class="row">
                       <div class="col-sm-6"></div>
                          <div class="col-sm-6" style="display:flex; justify-content:flex-end;">
                            <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" data-toggle="modal" data-target="#newAdmin">جدید <i class="fa fa-plus fa-lg" aria-hidden="true"></i></a>
                            <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="editMarketer" onclick="setKarbarEditStuff()" >ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-sm text-warning buttonHover" disabled id="deleteMarketer"  onclick="deleteAdminList()">حذف <i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>
                            <input type="hidden" id="asn"/>
                          </div>
                       </div>
                        <table class=" select-highlight table table-bordered table-striped" id="tableGroupList">
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
                                    @if($admin->adminTypeId==3)

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
                     </fieldset>
                </div>
            </div>
        </div>
        
        <!-- modal of new Brand -->
        <div class="modal fade dragableModal" id="newAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="margin:0; border:none">
                        <button type="button" class="btn-close btn-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="exampleModalLongTitle"> کابر جدید</h5>
                    </div>
                    <div class="modal-body">
                        <form action="{{url('/addAdmin')}}" method="POST"  enctype="multipart/form-data">
                            {{ csrf_field() }}
                           <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام </label>
                                        <input type="text" required minlength="3" maxlength="12" class="form-control" autocomplete="off" name="name">
                                    </div>
                               </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نام خانوادگی</label>
                                        <input type="text" required  minlength="3" maxlength="12" class="form-control" autocomplete="off" name="lastName">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"> نام کاربری</label>
                                        <input type="text" id="userName"  minlength="3" maxlength="12" onblur="checkExistance(this)" required class="form-control" autocomplete="off" name="userName">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <span id="existAlert" style="color: red"> </span>
                                    <div class="form-group">
                                        <label class="form-label"> شماره تماس </label>
                                        <input type="number"   minlength="11" maxlength="12" required class="form-control" autocomplete="off" name="phone">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6"> 
                                    <div class="form-group">
                                        <label class="form-label"> آدرس </label>
                                        <input type="text" required  minlength="3"  class="form-control" autocomplete="off" name="address" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"> رمز</label>
                                        <input type="text" onblur="clearRiplicateData()"  minlength="3" maxlength="12" required class="form-control" autocomplete="off" name="password" >
                                    </div>
                                </div> 
                            </div>
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> جنسیت  </label>
                                        <select class="form-select" name="sex">
                                                <option value="" >--</option>
                                                <option value="1" >زن </option>
                                                <option value="2" >مرد</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="dashboardLabel form-label"> نوع کاربر </label>
                                        <select class="form-select" name="adminType">
                                                <option value="" >--</option>
                                                <option value="1" >ادمین</option>
                                                <option value="2" >پشتیبان</option>
                                                <option value="3" >بازاریاب</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label"> عکس </label>
                                        <input type="file" class="form-control" name="picture" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-6 ps-5">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input me-0" name="hasAsses" type="checkbox" id="flexSwitchCheckChecked" checked style="font-size:25px;">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">آیا نظر سنجی داشته باشد؟</label>
                                    </div> <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 ps-5">
                                    <div class="form-check form-switch mt-2">
                                        <label class="form-check-label">زیر نظر</label>
                                        <select class="form-select" name="bossId">
                                            <option value="0">هیچکس</option>
                                            @foreach($admins as $admin)
                                            @if($admin->adminTypeId != 4)
                                            <option value="{{$admin->id}}">{{$admin->name.' '.$admin->lastName}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ps-5">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input me-0" name="hasAllCustomer" type="checkbox" id="flexSwitchCheckChecked" checked style="font-size:25px;">
                                        <label class="form-check-label">آیابه همه کاربران دسترسی داشته باشد؟</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="form-label"> توضیحات</label>
                                            <textarea class="form-control"  minlength="3" maxlength="12" cols="10" rows="4" name="discription" style="background-color:blanchedalmond"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="form-group" style="margin-top:2%">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelAddAddmin"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                    <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                                </div>
                            </div>
                        </div>
                        </form>
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
                                        <select class="form-select" name="adminType" id="editAdminType">
                                        </select>
                                        <input class="form-control" style="display:none" name="adminId" id="editAdminID">
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
                                    <div class="row" >
                                        <div class="col-lg-6 " id='assignBossDiv'>
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
	
</script>
@endsection
