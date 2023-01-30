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

<div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> سرویس راننده ها  </legend>
                          <div class="form-check">
                              <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                              <label class="form-check-label me-4" for="assesPast"> سرویس راننده ها </label>
                          </div>
                          <div class="form-check">
                              <input class="form-check-input p-2 float-end" type="radio" name="settings" id="elseSettingsRadio">
                              <label class="form-check-label me-4" for="assesPast">  بارگیری  </label>
                          </div>
                       
                          <div class="form-group col-sm-12 mb-1">
                              <input type="text" name="" placeholder="ازتاریخ" class="form-control form-control-sm" id="firstDateReturned">
                          </div>
                          <div class="form-group col-sm-12 mb-2">
                              <input type="text" name="" placeholder="تا تاریخ" class="form-control form-control-sm" id="secondDateReturned">
                          </div>
                        <div class="col-sm-12 mt-2">
                              <select class="form-select form-select-sm" id="orderInactiveCustomers">
                                  <option value="-1"> راننده ها </option>
                                  <option value="3">حداد  </option>
                                  <option value="2">  اسنب   </option>
                                  <option value="3">  کیانی  </option>
                                  <option value="4">  فلانی   </option>
                              </select>
                          </div>
                           <div class="form-group col-sm-12" style="margin-top:22vh;">
                              <input type="text" name="" size="20" placeholder="جستجو به اساس نام مشتری" class="form-control form-control-sm" id="allKalaFirst">
                          </div>
                        
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader">
                          <div class="form-group col-sm-2 mt-2">
                              <input type="text" name="" size="20" placeholder="جستجو" class="form-control form-control-sm" id="allKalaFirst">
                          </div>
                          <div class="col-sm-2 mt-2">
                              <select class="form-select form-select-sm" id="orderInactiveCustomers">
                                  <option value="-1">مرتب سازی</option>
                                  <option value="3">اسم </option>
                                  <option value="2">  دور  </option>
                                  <option value="3">  متوسط  </option>
                                  <option value="4">  نزدیک  </option>
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
                            <button type="button" class="btn btn-sm btn-primary footerButton"> امروز  : </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> دیروز : </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> صد تای آخر : 100</button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> همه : </button>
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