@extends('layout')
@section('content')
<main>
    <div class='container' style='margin-top:6%;'>
        <h3 class="page-title"> لیست کالا {{Session::get("adminId")}}</h3>
        <iframe name="votar" style="display:none;"></iframe>
        <div class="c-checkout card" style="padding-right:0;">
                <div class='modal-body'>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-3 mt-2">
                                    <input type="text"  class="form-control publicTop" autocomplete="off"  placeholder="اسم یا کد کالا" id="searchKalaNameCode">
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <select class="form-select publicTop" id="searchKalaStock">
                                        <option value="0" selected>انبار</option>
                                        @foreach ($stocks as $stock)

                                        <option value="{{$stock->SnStock}}">{{trim($stock->NameStock)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <select class="form-select publicTop" id="searchKalaActiveOrNot">
                                        <option value="0" hidden> فعال </option>
                                        <option value="1"> فعال </option>
                                        <option value="2"> غیر فعال </option>
                                    </select>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <select class="form-select publicTop" id="searchKalaExistInStock">
                                        <option value="0" hidden> نمایش موجودی </option>
                                        <option value="1"> موجودی صفر </option>
                                        <option value="2"> موجودی عدم صفر </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4" style="display:flex; justify-content:flex-end">
                            <form action="#" method="#" style="display: inline;">
                               <button type="submit" class="btn btn-primary text-warning"> رویت <i class="fal fa-eye fa-lg" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary text-warning"> گردش کالا <i class="fal fa-history fa-lg" aria-hidden="true"></i></button>
                               <button type="submit" class="btn btn-primary text-warning"> ارسال به اکسل  <i class="fal fa-file-excel fa-lg" aria-hidden="true"></i></button>
                            </form>
                     </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="well" style="margin-top:2%;">
                                <table class="table table-bordered table-striped" style='width:100%;'>
                                <thead class="tableHeader">
                                    <tr>
                                        <th >ردیف</th>
                                        <th >کد</th>
                                        <th>اسم</th>
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
                                        <td >{{trim($product->GoodCde)}}</td>
                                        <td>{{trim($product->GoodName)}}</td>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
@endsection
