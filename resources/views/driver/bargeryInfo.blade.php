@extends('layout')
@section('content')
<div class="container-fluid containerDiv">
    <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                       <div class="form-group col-sm-12 mb-1">
                            <input type="text" name="firstDateService" placeholder="از تاریخ" class="form-control form-control-sm" id="firstDateReturned">
                        </div>
                        <div class="form-group col-sm-12 mb-2">
                            <input type="text" name="secondDateService" placeholder="تا تاریخ" class="form-control form-control-sm" id="secondDateReturned">
                        </div>
                          
                       <div class="form-group col-lg-12" style="margin-top:28vh;">
                          <input type="text" name="" placeholder="جستجوی مشتری" class="form-control form-control-sm" id="searchAdminNameCode"/>
                      </div>
                    
                </fieldset>
                </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader">
                    <div class="form-group col-lg-2 mt-2">
                          <input type="text" name="" placeholder="نام" class="form-control form-control-sm" id="searchAdminNameCode"/>
                      </div>
                    <div class="col-sm-2 mt-2" id="orderService">
                        <select class="form-select form-select-sm" id="orderDriverServices">
                            <option value="-1"> مرتب سازی </option>
                            <option value="name">   اسم  </option>
                            <option value="serviceType">  مسیر  </option>
                            <option value="TimeStamp">  تاریخ  </option>
                        </select>
                    </div> 
                </div>
                <div class="row mainContent">
                    <div class="col-lg-12 px-0">
                        <table class="select-highlight table table-bordered table-striped bargeriTable" id="">
                                <thead class="tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th> اسم راننده </th>
                                        <th>شماره تماس</th>
                                        <th> جزئیات </th>
                                        <th>فعال</th>
                                    </tr>
                                </thead>
                                <tbody class="tableBody" id="" style="height:244px !important">
                                    @foreach ($admins as $admin)
                                        <tr onclick="showBargiriFactors(this,{{$admin->driverId}})">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                            <td>{{trim($admin->phone)}}</td>
                                            <td> <a href="{{url('crmDriver?asn='.$admin->driverId.'')}}"> <i class="fa fa-eye fa-lg" style="color:#000;"></i> </a> </td>
                                            <td>
                                                <input class="mainGroupId" type="radio" name="AdminId[]" value="{{$admin->id}}">
                                            </td>
                                        </tr>
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





