@extends('layout')
@section('content')

<style>
    .profileHeight{
        min-height: 450px;
    }
</style>

<section class="profile-page container px-0" style="margin-top:6%;">
    <div class="container-fluid">
        <div class="row">
            <section style="background: linear-gradient(#85baef, #116bc7, #2659a9); border-radius:10px; width:80%;">
                <div class="container py-4">
                  <div class="row">
                        <div class="col-lg-4">
                              <div class="card profileHeight">
                                    <div class="card-body text-center">
                                        <img style="width:100px; height:100px;" src="{{url('resources/assets/images/admins/'.Session::get('asn').'.jpg')}}" alt="avatar"
                                            class="rounded-circle img-fluid" style="width: 150px;">
                                        <h5 class="my-3">{{Session::get('username')}} </h5>
                                        <table class="table table-borderless">
                                          <table class="table table-borderless">
                                            <tbody>
                                              <tr>
                                                <th>نقش کاربر </th>
                                                <td>
                                                  @if(Session::get('adminType')==1)
                                                  ادمین
                                                @elseif(Session::get('adminType')==2)
                                                پشتیبان
                                                @endif
                                                </td>
                                              </tr>

                                              <tr>
                                                <th> وضعیت </th>
                                                <td>@if(Session::get('activeState')==1)
                                                  فعال
                                                  @else
                                                  غیر فعال
                                                  @endif
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                          </table>
                                    </div>
                                   <button class="btn btn-primary" data-toggle="modal" data-target="#editProfile"> ویرایش <i class="fa fa-edit"> </i>  </button>
                              </div>
                        </div>

                    <div class="col-md-4">
                        <div class="card profileHeight">
                          <div class="card-body">
                            <table class="table  table-borderless">

                                <tbody>
                                  <tr>
                                    <th>نام کاربر </th>
                                    <td>{{$admin->name.' '.$admin->lastName}} </td>
                                  </tr>
                                  <tr>
                                    <th>شماره تماس </th>
                                    <td>{{$admin->phone}}</td>
                                  </tr>
                                  <tr>
                                    <th>آدرس  </th>
                                    <td>{{$admin->address}}</td>
                                  </tr>
                                </tbody>
                              </table>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
     </div>


             <!-- modal of new Brand -->
             <div class="modal fade" id="editProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin:0; border:none">
                            <button type="button" class="btn-close btn-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="exampleModalLongTitle"> ویرایش پروفایل </h5>
                        </div>
                        <div class="modal-body">
                                <form action="{{url('/editOwnAdmin')}}" method="post"  enctype="multipart/form-data">
                                  @csrf
                                    <div class="form-group">
                                        <label class="form-label"> نام کاربری</label>
                                        <input type="text" required maxlength="20" minlength="5" class="form-control" value="{{trim($admin->username)}}" autocomplete="off" name="userName">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"> شماره تماس </label>
                                        <input type="number" maxlength="12" minlength="10" required class="form-control" value="{{trim($admin->phone)}}" autocomplete="off" name="phone">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"> ادرس  </label>
                                        <input type="text" required class="form-control" value="{{trim($admin->address)}}" autocomplete="off" name="address">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"> رمز</label>
                                        <input type="text" required class="form-control"  maxlength="20" minlength="4" value="{{trim($admin->password)}}" autocomplete="off" name="password" >
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"> عکس </label>
                                        <input type="file" class="form-control" required name="picture" placeholder="">
                                    </div>
                                    <div class="form-group" style="margin-top:4%">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                                        <button type="submit" class="btn btn-primary">ذخیره <i class="fa fa-save" aria-hidden="true"> </i> </button>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
</section>
@endsection
