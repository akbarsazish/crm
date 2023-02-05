@extends('layout')
@section('content')

<style>
.grid-amalKard {
  display: grid;
  grid-template-columns: auto auto auto auto auto;
  margin-bottom:5px;
}
.amalKarItem {
  background-color:#b3d1ef;
  padding: 3px;
  font-size: 14px;
  text-align: center;
  border-radius:5px;
  margin:2px;
  
}
.today{
  color:red;
}

.amalKardContent {
    border: 1px solid #b3d1ef;
    border-radius:8px; 
    padding:5px;
    margin-bottom:15px;

}
</style>
    <div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded">
                        <legend  class="float-none w-auto legendLabel mb-0">  عملکرد کاربران </legend>
                            <div class="row mt-2">
                                <div class="form-group col-sm-12">
                                    <select class="form-select form-select-sm " id="searchManagerByLine">
                                        <option value="-1" hidden>  خطوط  </option>
                                        @foreach($saleLine as $line)
                                            <option value="{{$line->SaleLineSn}}"> {{$line->LineName}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="form-group col-sm-12 mt-1">
                                    <select class="form-select form-select-sm " id="searchManagerSelect">
                                    <option value="-1" hidden>  مدیران   </option>
                                        @foreach($admins as $admin)
                                            <option value="{{$admin->id}}">{{$admin->name.' '.$admin->lastName}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        <button class='btn btn-sm btn-primary text-warning w-50' type="button" id='openDashboard' style="margin-top:33vh"> تراز نامه  <i class="fal fa-dashboard"></i></button>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader" style="height:20px;"></div>
                    <div class="row mainContent"> 
                      <fieldset class="border rounded-2 pb-0" style="border:2px solid #0860d7 !important;">
                          <legend  class="float-none w-auto legendLabel mb-0"> <span id="managerName">مدیران</span> </legend>
                            
                            <div class="col-lg-12 text-start"> 
                                <button class="btn btn-sm btn-primary" type="button">  امتیاز <i class="fa fa-rocket"></i></button>
                                <button class="btn btn-sm btn-primary" type="button">  تسویه <i class="fas fa-balance-scale"></i> </button>
                            </div>

                            <div class="col-lg-12">
                                <div class="traz-today rounded-2 mx-0 mt-1">
                                    <div class="traz-item"> <span style="color:red;"> نصب: </span> <span id="loginTimeToday"></span>  </div>
                                    <div class="traz-item"> <span style="color:red;"> اقلام: </span> <span id="loginTimeToday"></span>  </div>
                                    <div class="traz-item"> <span style="color:red;"> فاکتورها: </span> <span id="loginTimeToday"></span>  </div>
                                    <div class="traz-item"> <span style="color:red;"> خرید اولیه: </span> <span id="loginTimeToday"></span>  </div>
                                </div>
                            </div>
                    </fieldset>
                   <fieldset class="border rounded-2 pb-0" style="border:2px solid #0860d7 !important;">
                     <legend  class="float-none w-auto legendLabel mb-0">  سرپرستان  </legend>

                           <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-check bg-gray">
                                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                        <label class="form-check-label me-4" for="assesPast"> سرپرست 1 </label>
                                    </div>
                                      <div class="form-check bg-gray">
                                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                        <label class="form-check-label me-4" for="assesPast"> سرپرست 1 </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-10 amalKardContent" >
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <div class="grid-amalKard">
                                                    <div class="amalKarItem today"> نصب امروز </div>
                                                    <div class="amalKarItem today"> اقلام امروز </div>
                                                    <div class="amalKarItem today"> فاکتور امروز </div>  
                                                    <div class="amalKarItem today"> خرید اولیه امروز</div>
                                                    <div class="amalKarItem"> نصب ها   </div>
                                                    <div class="amalKarItem"> اقلامها </div>  
                                                    <div class="amalKarItem"> فاکتورها </div>
                                                    <div class="amalKarItem"> خریدهای اولیه </div>
                                                    <div class="amalKarItem"> کل امتیاز (آذر) </div>  
                                                    <div class="amalKarItem"> تاریخچه عملکرد </div>  
                                                </div>
                                            </div>
                                             <div class="col-lg-2 p-0 m-0"> 
                                                <button class="btn btn-sm btn-primary" type="button">  امتیاز <i class="fa fa-rocket"></i></button>
                                                <button class="btn btn-sm btn-primary" type="button">  تسویه <i class="fas fa-balance-scale"></i> </button>
                                            </div>
                                        </div>
                                 </div>
                             </div>
                       
                    </fieldset>  
                     <fieldset class="border rounded-2 pb-0" style="border:2px solid #0860d7 !important;">
                       <legend  class="float-none w-auto legendLabel mb-0">  بازاریابها  </legend>            
                         <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-check bg-gray">
                                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                        <label class="form-check-label me-4" for="assesPast"> سرپرست 1 </label>
                                    </div>
                                      <div class="form-check bg-gray">
                                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                        <label class="form-check-label me-4" for="assesPast"> سرپرست 1 </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-10 amalKardContent" >
                                        <div class="row">
                                            <div class="col-lg-10">
                                                <div class="grid-amalKard">
                                                    <div class="amalKarItem today"> نصب امروز </div>
                                                    <div class="amalKarItem today"> اقلام امروز </div>
                                                    <div class="amalKarItem today"> فاکتور امروز </div>  
                                                    <div class="amalKarItem today"> خرید اولیه امروز</div>
                                                    <div class="amalKarItem"> نصب ها   </div>
                                                    <div class="amalKarItem"> اقلامها </div>  
                                                    <div class="amalKarItem"> فاکتورها </div>
                                                    <div class="amalKarItem"> خریدهای اولیه </div>
                                                    <div class="amalKarItem"> کل امتیاز (آذر) </div>  
                                                    <div class="amalKarItem"> تاریخچه عملکرد </div>  
                                                </div>
                                            </div>
                                             <div class="col-lg-2 p-0 m-0"> 
                                                <button class="btn btn-sm btn-primary" type="button">  امتیاز <i class="fa fa-rocket"></i></button>
                                                <button class="btn btn-sm btn-primary" type="button">  تسویه <i class="fas fa-balance-scale"></i> </button>
                                            </div>
                                        </div>
                                 </div>
                             </div>
                     </fieldset> 
                        <div class="col-lg-12 px-0">
                        <table class="table table-bordered table-striped" id="tableGroupList">
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th> آخرین فاکتور </th>
                                    <th> اسم مشتری </th>
                                    <th> ورود به سیستم </th>
                                    <th> انتخاب </th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="adminGroupList" style="height:200px!important;">
                                <tr>
                                    <td></td>
                                    <td> </td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <input class="mainGroupId" type="radio" name="AdminId[]" value="">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="traz-today rounded-2 mx-2">
                            <div class="traz-item"> <span style="color:red;"> توضیحات: </span> <span id="loginTimeToday"></span>  </div>
                        </div> <br>
                   </div>
                    </div>
                    <div class="row contentFooter" style="height:20px;"> </div>
                </div>
        </div>
    </div>

@endsection



