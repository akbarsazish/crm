@extends('layout')
@section('content')
    <div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded">
                        <legend  class="float-none w-auto legendLabel mb-0">  عملکرد کاربران </legend>
                            <div class="row mt-2">
                                <div class="form-group col-sm-12">
                                    <select class="form-select form-select-sm " id="searchByMantagheh">
                                    <option value="0" hidden>  خطوط  </option>
                                    <option value="0"> خط فروش  </option>
                                    <option value="0">  1 خط فروش  </option>
                                    <option value="0"> خط فروش 2  </option>
                                    </select>
                                </div>
                            
                                <div class="form-group col-sm-12 mt-1">
                                    <select class="form-select form-select-sm " id="searchByMantagheh">
                                    <option value="0" hidden>  مدیران   </option>
                                    <option value="0"> مدیر 1 </option>
                                    <option value="0"> مدیر 2  </option>
                                    <option value="0"> مدیر 3 </option>
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
                          <legend  class="float-none w-auto legendLabel mb-0">  مدیران  </legend>
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
                                <div class="col-lg-10 mx-0">
                                    <div class="form-check bg-gray">
                                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                        <label class="form-check-label me-4" for="assesPast"> سرپرست 1 </label>
                                    </div>
                                    <div class="form-check bg-gray">
                                        <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                        <label class="form-check-label me-4" for="assesPast"> سرپرست 1 </label>
                                    </div>
                                </div>
                                <div class="col-lg-2 p-0 m-0 text-start"> 
                                    <button class="btn btn-sm btn-primary" type="button">  امتیاز <i class="fa fa-rocket"></i></button>
                                    <button class="btn btn-sm btn-primary" type="button">  تسویه <i class="fas fa-balance-scale"></i> </button>
                                </div>
                        </div>
                       
                        <div class="col-lg-12">
                            <div class="traz-today rounded-2">
                                    <div class="traz-item"> <span style="color:red;"> نصب: </span> <span id="loginTimeToday"></span>  </div>
                                    <div class="traz-item"> <span style="color:red;"> اقلام: </span> <span id="loginTimeToday"></span>  </div>
                                    <div class="traz-item"> <span style="color:red;"> فاکتورها: </span> <span id="loginTimeToday"></span>  </div>
                                    <div class="traz-item"> <span style="color:red;"> خرید اولیه: </span> <span id="loginTimeToday"></span>  </div>
                            </div>
                        </div>
                    </fieldset>  
                     <fieldset class="border rounded-2 pb-0" style="border:2px solid #0860d7 !important;">
                       <legend  class="float-none w-auto legendLabel mb-0">  بازاریابها  </legend>            
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-check bg-gray">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                    <label class="form-check-label me-4" for="assesPast"> بازاریاب 1 </label>
                                </div>
                                <div class="form-check bg-gray">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="customerInactiveRadio">
                                    <label class="form-check-label me-4" for="assesPast"> بازاریاب 2 </label>
                                </div> 
                                <div class="row">
                                    <div class="col-lg-12">
                                            <button type="button" class="btn btn-sm btn-primary footerButton m-1"> نصب </button>
                                            <button type="button" class="btn btn-sm btn-primary footerButton m-1"> اقلام </button>
                                            <button type="button" class="btn btn-sm btn-primary footerButton m-1">فاکتور ها   </button>
                                            <button type="button" class="btn btn-sm btn-primary footerButton m-1"> خرید اولیه  </button>
                                            <button type="button" class="btn btn-sm btn-primary footerButton m-1">  کل امتیاز (آذر) </button>
                                            <button type="button" class="btn btn-sm btn-primary footerButton m-1">  تاریخچه عملکرد </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 p-0 m-0 text-start"> 
                                <button class="btn btn-sm btn-primary" type="button">  امتیاز <i class="fa fa-rocket"></i></button>
                                <button class="btn btn-sm btn-primary" type="button">  تسویه <i class="fas fa-balance-scale"></i> </button>
                            </div>
                        </div>
                        <div class="col-lg-12">
                             <div class="traz-today rounded-2 mx-0 mt-1">
                                <div class="traz-item"> <span style="color:red;">  نصب امروز: </span> <span id="loginTimeToday"></span>  </div>
                                <div class="traz-item"> <span style="color:red;"> اقلام امروز: </span> <span id="loginTimeToday"></span>  </div>
                                <div class="traz-item"> <span style="color:red;"> فاکتور امروز: </span> <span id="loginTimeToday"></span>  </div>
                                <div class="traz-item"> <span style="color:red;"> خرید اولیه امروز: </span> <span id="loginTimeToday"></span>  </div>
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



