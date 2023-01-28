@extends('layout')
@section('content')

<div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> تنظیمات </legend>
                          <div class="row">
                                <div class="col-sm-12">
                                    <input type="text"  class="form-control form-control-sm" autocomplete="off"  placeholder="اسم یا کد کالا" id="searchKalaNameCode">
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
                             </div>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> 
                        <div class="col-sm-12 text-start">
                            <form action="#" method="#" style="display: inline;">
                               <button type="submit" class="btn btn-primary btn-sm text-warning"> رویت <i class="fal fa-eye" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary btn-sm text-warning"> گردش کالا <i class="fal fa-history" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary btn-sm text-warning"> ارسال به اکسل  <i class="fal fa-file-excel" aria-hidden="true"></i></button>
                            </form>
                       </div>
                    </div>
                    <div class="row mainContent">
                        <table class="table table-bordered table-striped table-sm">
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
                    </div>
                    <div class="row contentFooter"> </div>
                </div>
        </div>
    </div>

@endsection
