@extends('layout')
@section('content')


<div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                    <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> سرویس راننده ها  </legend>
                        <form action="{{url('/searchDriverServices')}}" id="getServiceSearchForm" method="get">
                          <div class="form-group col-sm-12 mb-1">
                            <input type="text" name="firstDateService" placeholder="از تاریخ" class="form-control form-control-sm" id="firstDateReturned">
                          </div>
                          <div class="form-group col-sm-12 mb-2">
                            <input type="text" name="secondDateService" placeholder="تا تاریخ" class="form-control form-control-sm" id="secondDateReturned">
                          </div>
                          <div class="col-sm-12 mt-2">
                            <select class="form-select form-select-sm" name="driverSn" id="searchDriverSelect">
                              <option value="-1"> راننده ها </option>
                              @forelse($drivers as $driver)
                              <option value="{{$driver->driverId}}" id="driver{{$driver->driverId}}">{{$driver->name.' '.$driver->lastName}}</option>
                              @empty
                              <div><span>داده وجود ندارد</span></div>
                              @endif
                            </select>
                          </div>
                          <button class='btn btn-primary btn-sm text-warning' type="submit" id='getServiceSearchBtn'> بازخوانی <i class="fal fa-dashboard fa-lg"></i></button>
                        </form>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader">
                          <div class="col-sm-2 mt-2">
                              <select class="form-select form-select-sm" id="orderDriverServices">
                                  <option value="-1"> مرتب سازی </option>
                                  <option value="name">   اسم  </option>
                                  <option value="serviceType">  مسیر  </option>
                                  <option value="TimeStamp">  تاریخ  </option>
                              </select>
                          </div>
                        <div class="col-lg-8 text-start">
                            <button class="btn btn-primary btn-sm" id="driverServicesBtn"> افزودن سرویس <i class="fa fa-plus"></i> </button>
                            <button class="btn btn-primary btn-sm" id="editDriverServicesBtn"> ویرایش سرویس <i class="fa fa-edit"></i> </button>
                        </div>
                    </div>
                    <div class="row mainContent">
                <div class="row p-0 m-0">
                  <table class="table table-bordered table-striped" id="driverServicesTable">
                        <thead class="tableHeader">
                            <tr>
                            <th>  دریف  </th>
                            <th> نام راننده</th>
                            <th> نوع مسیر </th>
                            <th> توضیحات </th>
                            <th> تاریخ </th>
                            <th> انتخاب </th>
                            </tr>
                        </thead>
                        <tbody class="tableBody" id="driverServiceBodyList">
                          @foreach($services as $service)

                            <tr onclick="setDriverServiceStuff(this,{{$service->ServiceSn}})">
                                <th>{{$loop->iteration}}</th>
                                <td> {{$service->name.' '.$service->lastName}}</td>
                                <td>@if($service->serviceType==2) متوسط @endif @if($service->serviceType==1) دور @endif @if($service->serviceType==3) نزدیک @endif </td>
                                <td>{{$service->discription}} </td>
                                <td>{{\Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($service->TimeStamp))->format('Y/m/d H:i:s')}}</td>
                                <td>  <input  type="radio" name="radioBtn" value="{{$service->ServiceSn}}"> </td>
                            </tr>
                          @endforeach
                          </tbody>
                    </table>
               
                    </div>
                    </div>
                    <div class="row contentFooter"> 
                        <div class="col-lg-12 text-start mt-1">
                            <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getServices('TODAY')"> امروز  : </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getServices('YESTERDAY')"> دیروز : </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getServices('LASTHUNDRED')"> صد تای آخر : 100</button>
                            <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getServices('ALLSERVICES')"> همه : </button>
                        </div>
                    </div>
                </div>
        </div>
    </div>








<!-- Modal for adding services -->
<div class="modal fade" id="driverServicesModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="driverServicesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{url('/addService')}}" id="addService" method="get">
          <div class="modal-header bg-primary text-white py-3">
              <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
              <h6 class="modal-title" id="driverServicesModalLabel"> افزودن سرویس راننده ها  </h6>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6">
                <select class="form-select form-select-sm" id="selectDriver" name="selectDriver">
                  <option selected> انتخاب راننده </option>
                  <option value="2"> کیانی  </option>
                  <option value="2"> اسنب  </option>
                  <option value="3"> محمد رضا حداد </option>
                </select>
              </div>
              <div class="col-lg-6">
                <select class="form-select form-select-sm" id="selectService" name="selectService">
                  <option value="3"> نزدیک </option>
                  <option value="2"> متوسط </option>
                  <option value="1"> دور </option>
                </select>
              </div>
            </div>
            <div class="col-lg-12">
              <div class="mb-3 mt-2">
                <label for="exampleFormControlTextarea1" class="form-label" > توضیحات </label>
                <textarea class="form-control" name="discription" rows="3"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"> بستن <i class="fa fa-xmark"></i> </button>
            <button type="submit" class="btn btn-primary btn-sm"> ذخیره <i class="fa fa-save"></i> </button>
          </div>
        </form>
      </div>
    </div>
  </div>


<!-- Modal for editing services-->
<div class="modal fade" id="editDriverServicModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editDriverServicModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{url('/editDriverService')}}" id="editServiceForm" method="get">
      <div class="modal-header bg-primary text-white py-3">
          <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
          <h6 class="modal-title" id="editDriverServicModalLabel"> ویرایش سرویس راننده ها  </h6>
      </div>
      <div class="modal-body">
            <div class="row">
              <input type="text" name="serviceSn" id="serviceSn">
                <div class="col-lg-6">
                   <select class="form-select form-select-sm" name="editDriverSn" id="editDriverSn">
                    </select>
                </div>
                <div class="col-lg-6">
                   <select class="form-select form-select-sm" name="editServiceType" id="editServiceSn">
                        <option id="weakService" value="3">نزدیک</option>
                        <option id="mediumService" value="2">متوسط</option>
                        <option id="strongService" value="1">دور</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
               <div class="mb-3 mt-2">
                   <label for="editDiscription" class="form-label"> توضیحات </label>
                    <textarea class="form-control" id="editDiscription" name="editDiscription" rows="3"></textarea>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"> بستن <i class="fa fa-xmark"></i> </button>
        <button type="submit" class="btn btn-primary btn-sm"> ذخیره <i class="fa fa-save"></i> </button>
      </div>
      </form>
    </div>
  </div>
</div>

@endsection