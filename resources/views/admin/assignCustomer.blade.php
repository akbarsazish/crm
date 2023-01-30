@extends('layout')
@section('content')

 <div class="container-fluid containerDiv">
        <div class="spinner-border text-danger" role="status" id="transferLoader" style="display:none;">
            <span class="sr-only">Loading...</span>
        </div>
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0">  تخصیص کاربران   </legend>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" value="1" type="radio" name="settings" id="takhsisManagerRadio">
                            <label class="form-check-label me-4" for="assesPast">  مدیران </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" value="2" type="radio" name="settings" id="takhsisHeadRadio">
                            <label class="form-check-label me-4" for="assesPast"> سرپرستان </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" value="3" type="radio" name="settings" id="takhsisEmployeeRadio">
                            <label class="form-check-label me-4"  for="assesPast"> کارمندان </label>
                        </div>
                        <div class="col-lg-12" style="margin-top:50vh;">
                          <a href="{{url('editAssignCustomer')}}"type="button" class="btn btn-primary btn-sm text-warning w-50" disabled>ویرایش <i class="fa fa-edit fa-lg" aria-hidden="true"></i></a>
                        </div>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader">
                        <div class="col-lg-12 text-start">
                            <button type="button" class="btn btn-primary btn-sm buttonHover text-warning" disabled id="emptyKarbarButton" >تخلیه کاربر <i class="fa fas fa-upload fa-lg" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-primary btn-sm buttonHover text-warning" disabled id="moveKarbarButton">تغییر کاربر <i class="fa fas fa-sync fa-lg" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="row mainContent">
                         <div class="col-lg-12 pe-0">
                             <table class="table table-bordered table-striped table-hover">
                                    <thead class="tableHeader">
                                        <tr>
                                            <th>ردیف</th>
                                            <th> کاربر</th>
                                            <th> تعداد مشتری  </th>
                                            <th>تاریخ تخصیص </th>
                                            <th> انتخاب </th>
                                        </tr>
                                    </thead>
                                    <tbody class="select-highlight tableBody" id="adminGroupList" style="height:250px !important;">
                                        @foreach ($admins as $admin)    
                                                <tr onclick="setAdminStuff(this,{{$admin->id}},{{$admin->adminType}})">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                                    <td>{{$admin->countCustomer}}</td>
                                                    <td>{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($admin->takhsisDate))->format("Y/m/d H:i:s")}}</td>
                                                    <td> <input class="mainGroupId" type="radio" name="AdminId[]" value="{{$admin->id}}"> </td>
                                                </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                 <div class="grid-today rounded-2 mx-2">
                                    <div class="today-item"> <span style="color:red; font-weight:bold;"> توضیحات:  {{trim($admin->discription)}} </span> <span id="loginTimeToday"></span>  </div>
                                 </div> 
                                 <table class="table table-bordered table-striped table-hover">
                                    <thead class="tableHeader">
                                        <tr>
                                            <th>ردیف</th>
                                            <th> اسم  </th>
                                            <th> منطقه  </th>
                                            <th style="width:199px">تاریخ اخرین خرید </th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody class="select-highlight tableBody" id="adminGroupList" style="height:250px !important;">
                                        <tr>
                                            <td>1</td>
                                            <td> </td>
                                            <td> </td>
                                            <td style="width:188px !important"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                       </div>  
                         
                    <div class="row contentFooter"> </div>
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
