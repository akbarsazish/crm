@extends('layout')
@section('content')
<style>
  #kalaSalesReport, #returnedKalaTable, #notExistKalaTable, #rocketKalaTable, #footerBtn{
    display:none;
  } 
  .forPrint{
    display:none;
  }

@media print {
  body {
    margin: 0;
    color: #000;
    background-color: #fff;
  }
}

</style>

<div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                          <div class="row">
                                <div class="form-check">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="allKalaRadio" checked>
                                    <label class="form-check-label me-4" for="assesPast"> همه کالا ها </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="salesKalaReportRadio">
                                    <label class="form-check-label me-4" for="assesPast"> گزارش فروش کالا </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="returnKalaReportRadio">
                                    <label class="form-check-label me-4" for="assesPast"> کالا های برگشتی  </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="notExistKalaRadio">
                                    <label class="form-check-label me-4" for="assesPast"> کالاهای فاقد موجودی </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input p-2 float-end" type="radio" name="settings" id="rocketKalaRadio">
                                    <label class="form-check-label me-4" for="assesPast"> کالاهای راکت </label>
                                </div>

                                <div class="col-sm-12">
                                    <input type="text"  class="form-control form-control-sm" autocomplete="off"  placeholder="اسم یا کد کالا" id="searchKalaNameCode">
                                </div>
                                <div class="col-sm-12">
                                    <select class="form-select form-select-sm" id="searchKalaActiveOrNot">
                                        <option value="0" hidden> گروبندی کالاها  </option>
                                        <option value="1"> برنج  </option>
                                        <option value="2">  برنج   </option>
                                    </select>
                                </div>


                                <div class="col-sm-12">
                                    <select class="form-select form-select-sm" id="searchKalaStock">
                                        <option value="0" selected>انبار</option>
                                        @foreach ($stocks as $stock)
                                          <option value="{{$stock->SnStock}}">{{trim($stock->NameStock)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <select class="form-select form-select-sm" id="searchKalaActiveOrNot">
                                        <option value="0" hidden> فعال </option>
                                        <option value="1"> فعال </option>
                                        <option value="2"> غیر فعال </option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <select class="form-select form-select-sm" id="searchKalaExistInStock">
                                        <option value="0" hidden> نمایش موجودی </option>
                                        <option value="1"> موجودی صفر </option>
                                        <option value="2"> موجودی عدم صفر </option>
                                    </select>
                                </div>

                                 <div class="input-group input-group-sm mt-2">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"> از تاریخ  </span>
                                    <input type="text" class="form-control" id="assesFirstDate">
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text" id="inputGroup-sizing-sm"> تا تاریخ  </span>
                                    <input type="text" class="form-control" id="assesSecondDate">
                                </div>

                                <div class="col-lg-12">
                                     <a href="{{url('https://starfoods.ir/webSpecialSettings')}}" type="submit" class="btn btn-primary btn-sm text-warning" style="margin-top:20vh"> تنظیمات کالا  <i class="fal fa-cog" aria-hidden="true"></i></a>
                                </div>
                             </div>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> 
                        <div class="col-sm-12 text-start">
                            <form action="#" method="#" style="display: inline;">
                               <button type="submit" class="btn btn-primary btn-sm text-warning" disabled> 10 گردش آخر <i class="fal fa-history" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary btn-sm text-warning"> رویت <i class="fal fa-eye" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary btn-sm text-warning"> گردش کالا <i class="fal fa-history" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary btn-sm text-warning"> ارسال به اکسل  <i class="fal fa-file-excel" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary btn-sm text-warning" onclick="window.print()"> پرنت <i class="fal fa-print" aria-hidden="true"></i></button>
                            </form>
                       </div>
                    </div>
                    <div class="row mainContent">
                        <table class="table table-bordered table-striped table-sm" id="allKala">
                            <thead class="tableHeader">
                                <tr>
                                    <th >ردیف</th>
                                    <th style="width:88px">کد</th>
                                    <th style="width:333px">اسم</th>
                                    <th>آخرین فروش</th>
                                    <th >غیرفعال</th>
                                    <th > موجودی </th>
                                    <th >انتخاب </th>
                                </tr>
                            </thead>
                            <tbody id='kalaContainer' class="select-highlightKala tableBody">
                                @foreach ($products as $product)
                              <tr>
                                <td >{{$loop->iteration}}</td>
                                <td style="width:88px">{{trim($product->GoodCde)}}</td>
                                <td style="width:333px">{{trim($product->GoodName)}}</td>
                                <td>{{trim($product->maxFactDate)}}</td>
                                <td >{{$product->hideKala}}</td>
                                <td style="color:red;background-color:azure;">{{number_format($product->Amount)}}</td>
                                <td >
                                    <input class="kala form-check-input" name="kalaId[]" type="radio" value="{{$product->GoodSn}}" id="flexCheckCheckedKala">
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>

                        <!-- kala sales table -->
                        <table class="table table-bordered table-striped table-sm" id="kalaSalesReport">
                            <thead class="tableHeader">
                                <tr>
                                    <th >ردیف</th>
                                    <th style="width:88px">کد</th>
                                    <th style="width:333px">اسم</th>
                                    <th> تعداد فروش </th>
                                    <th >انتخاب </th>
                                </tr>
                            </thead>
                            <tbody id='kalaContainer' class="select-highlightKala tableBody">
                                @foreach ($products as $product)
                              <tr>
                                <td >{{$loop->iteration}}</td>
                                <td style="width:88px">{{trim($product->GoodCde)}}</td>
                                <td style="width:333px">{{trim($product->GoodName)}}</td>
                                <td> </td>
                                <td >
                                    <input class="kala form-check-input" name="kalaId[]" type="radio" value="{{$product->GoodSn}}" id="flexCheckCheckedKala">
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- return back kala table -->
                          <table class="table table-bordered table-striped table-sm" id="returnedKalaTable">
                            <thead class="tableHeader">
                                <tr>
                                    <th >ردیف</th>
                                    <th style="width:88px">کد</th>
                                    <th style="width:333px">اسم</th>
                                    <th> تاریخ برگشتی  </th>
                                    <th> مشتری </th>
                                    <th> تعداد </th>
                                    <th >انتخاب </th>
                                </tr>
                            </thead>
                            <tbody id='kalaContainer' class="select-highlightKala tableBody">
                                @foreach ($products as $product)
                              <tr>
                                <td >{{$loop->iteration}}</td>
                                <td style="width:88px">{{trim($product->GoodCde)}}</td>
                                <td style="width:333px">{{trim($product->GoodName)}}</td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td >
                                    <input class="kala form-check-input" name="kalaId[]" type="radio" value="{{$product->GoodSn}}" id="flexCheckCheckedKala">
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- not existing Kalas -->
                          <table class="table table-bordered table-striped table-sm" id="notExistKalaTable">
                            <thead class="tableHeader">
                                <tr>
                                    <th >ردیف</th>
                                    <th style="width:333px">اسم</th>
                                    <th> آخرین فروش </th>
                                    <th >انتخاب </th>
                                </tr>
                            </thead>
                            <tbody id='kalaContainer' class="select-highlightKala tableBody">
                                @foreach ($products as $product)
                              <tr>
                                <td >{{$loop->iteration}}</td>
                                <td style="width:333px">{{trim($product->GoodName)}}</td>
                                <td>{{trim($product->maxFactDate)}}</td>
                                <td >
                                    <input class="kala form-check-input" name="kalaId[]" type="radio" value="{{$product->GoodSn}}" id="flexCheckCheckedKala">
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- rocket kala table -->
                        <table class="table table-bordered table-striped table-sm" id="rocketKalaTable">
                            <thead class="tableHeader">
                                <tr>
                                    <th >ردیف</th>
                                    <th  style="width:88px"> کد </th>
                                    <th style="width:333px">اسم</th>
                                    <th> آخرین فروش </th>
                                    <th > موجودی </th>
                                    <th >انتخاب </th>
                                </tr>
                            </thead>
                            <tbody id='kalaContainer' class="select-highlightKala tableBody">
                                @foreach ($products as $product)
                              <tr>
                                <td >{{$loop->iteration}}</td>
                                <td style="width:88px">{{trim($product->GoodCde)}}</td>
                                <td style="width:333px">{{trim($product->GoodName)}}</td>
                                <td>{{trim($product->maxFactDate)}}</td>
                                 <td style="color:red;background-color:azure;">{{number_format($product->Amount)}}</td>
                                <td >
                                    <input class="kala form-check-input" name="kalaId[]" type="radio" value="{{$product->GoodSn}}" id="flexCheckCheckedKala">
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row contentFooter"> 
                        <div class="col-lg-12 mt-3 text-start" id="footerBtn">
                            <button type="button" class="btn btn-sm btn-primary footerButton"> نظرات امروز  <i class="fa fa-comments"></i> </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> دیروز  <i class="fa fa-comments"></i> </button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> صدتای آخر  <i class="fa fa-comments"></i></button>
                            <button type="button" class="btn btn-sm btn-primary footerButton"> همه <i class="fa fa-comments"></i></button>
                        </div>
                    </div>
                </div>
        </div>
    </div>

@endsection
