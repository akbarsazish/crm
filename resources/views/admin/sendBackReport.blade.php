@extends('layout')
@section('content')
<style>
    th, td {
        font-size:10px !important;
    }
</style>
    <div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="elseSettingsRadio">
                            <label class="form-check-label me-4" for="assesPast"> تسویه شده  </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                            <label class="form-check-label me-4" for="assesPast"> تسویه نشده  </label>
                        </div>
                        <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text" id=""> از تاریخ  </span>
                            <input type="text" class="form-control" id="assesFirstDate">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text" id=""> تا تاریخ  </span>
                            <input type="text" class="form-control" id="assesSecondDate">
                        </div>

                        <div class="row">
                            <div class="col-lg-6 ps-0">
                                <div class="input-group input-group-sm mt-2">
                                  <span class="input-group-text text-danger" id=""> ساعت   </span>
                                  <input type="time" class="form-control" id="assesFirstDate">
                                </div>
                            </div>
                            <div class="col-lg-6 pe-0">
                                <div class="input-group input-group-sm mt-2">
                                    <span class="input-group-text text-danger" id=""> الی </span>
                                    <input type="time" class="form-control" id="assesSecondDate">
                                </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 ps-0">
                                <div class="input-group input-group-sm mt-2">
                                  <span class="input-group-text text-danger" id=""> فاکتور   </span>
                                  <input type="number" min=0 placeholder="0" class="form-control" id="assesFirstDate">
                                </div>
                            </div>
                            <div class="col-lg-6 pe-0">
                                <div class="input-group input-group-sm mt-2">
                                    <span class="input-group-text text-danger" id=""> الی </span>
                                    <input type="number" min=0 placeholder="0" class="form-control" id="assesSecondDate">
                                </div>
                           </div>
                        </div>
                         <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text" id=""> خریدار </span>
                            <input type="text" class="form-control" id="">
                        </div>
                         <div class="form-group col-sm-12 mb-2">
                            <button class='btn btn-primary btn-sm text-warning' type="button" id='getHistorySearchBtn'> بازخوانی <i class="fal fa-refresh fa-lg"></i></button>
                        </div>

                        <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text" id=""> خریدار متفرقه </span>
                            <input type="text" class="form-control" id="">
                        </div>
                        <div class="col-sm-12">
                            <select class="form-select form-select-sm mt-2" id="searchKalaActiveOrNot">
                                <option value="0" hidden> تنظیم کننده  </option>
                                <option value="1"> حاجی احمدی  </option>
                                <option value="2">  خانم ناصری   </option>
                            </select>
                        </div>
                        <div class="col-sm-12">
                            <select class="form-select form-select-sm mt-2" id="searchKalaActiveOrNot">
                                <option value="0" hidden> انبار  </option>
                                <option value="1"> افشار </option>
                                <option value="2"> بندر  </option>
                                <option value="2"> سعید اباد  </option>
                            </select>
                        </div>
                        <div class="input-group input-group-sm mt-2">
                            <span class="input-group-text" id="" style="font-size:10px;"> انتخاب فاکتور به شماره </span>
                            <input type="number" min="0" class="form-control" id="">
                        </div>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> 
                        <div class="form-group col-lg-2">
                          <input type="text" name="" placeholder="جستجو" class="form-control form-control-sm mt-2" id="searchAdminNameCode"/>
                      </div>
                    </div>
                    <div class="row mainContent">
                         <table class="table table-bordered table-striped table-sm" id="allKala">
                            <thead class="tableHeader">
                                <tr>
                                    <th >ردیف</th>
                                    <th> شماره  </th>
                                    <th> تاریخ  </th>
                                    <th> توضیحات </th>
                                    <th >کد مشتری </th>
                                    <th > نام مشتری  </th>
                                    <th> تنظیم کننده </th>
                                    <th> از انبار </th>
                                    <th> تعداد چاپ </th>
                                    <th> پرسانت بازاریاب </th>
                                    <th>  مبلغ تخفیف </th>
                                    <th> تاریخ اعلام به انبار </th>
                                    <th> ساعت اعلام به انبار </th>
                                    <th> تاریخ بارگیری </th>
                                    <th>  ساعت بارگیری </th>
                                    <th> ساعت ثبت </th>
                                    <th>  از سفارش  </th>
                                    <th> شماره گیری </th>
                                    <th> تحویل به راننده  </th>
                                    <th> نام راننده </th>
                                    <th>  از سفارش  </th>
                                   
                                </tr>
                            </thead>
                            <tbody id='' class="select-highlightKala tableBody">
                                <tr>
                                    <td ></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="row contentFooter">
                        <div class="col-lg-12 mt-2 text-start">
                            <button type="button" class="btn btn-sm btn-primary footerButton">  امروز  </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> دیروز  </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> صدتای آخر  </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> همه </button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

@endsection





