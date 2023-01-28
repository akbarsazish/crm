@extends('layout')
@section('content')
    <div class="container-fluid containerDiv">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> خط فروش </legend>
                    <button type="button" class="btn btn-sm btn-primary" id="addSaleLineBtn"> افزودن <i class="fa fa-plus"></i> </button>
                    <button type="button" class="btn btn-sm btn-primary" id="editSaleLineBtn"> ویرایش <i class="fa fa-plus"></i> </button>
                    <button type="button" class="btn btn-sm btn-primary" id="deleteSaleLineBtn"> حذف <i class="fa fa-plus"></i> </button>
                </fieldset>
            </div>
            <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                <div class="row contentHeader"> </div>
                <div class="row mainContent">
                    <table class="table table-bordered crmDataTable driverTable" id="tableGroupList">
                        <thead class="bg-primary text-warning">
                            <tr>
                                <th style="width:0px">#</th>
                                <th style="width:200px;">اسم خط فروش</th>
                            </tr>
                        </thead>
                        <tbody class="c-checkout" id="saleLines">
                            @foreach ($saleLines as $line)
                                <tr onclick="setSaleLineStuff(this,{{$line->SaleLineSn}})">
                                    <td style="width:0px">{{$loop->iteration}}</td>
                                    <td style="width:200px;">{{$line->LineName}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row contentFooter"></div>
            </div>
        </div>
    </div>
<!-- add sale line -->
        <div class="modal fade dragableModal" id="addSaleLineModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="exampleModalLabel">افزودن خط فروش</h5>
                    </div>
                    <div class="modal-body" >
                        <form action="{{url('/addSaleLine')}}" id="addSaleLineForm" method="get">
                            <label for="" class="form-label"> اسم خط فروش </label>
                            <input type="text" name="name" class="form-control" id="">
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success">ذخیره <i class="fa fa-save"></i></button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- edit sale line -->
        <div class="modal fade dragableModal" id="editSaleLineModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
                        <h5 class="modal-title" id="exampleModalLabel">ویرایش خط فروش</h5>
                    </div>
                    <div class="modal-body" >
                        <form action="{{url('/editSaleLine')}}" id="editSaleLineForm" method="get">
                            <label for="" class="form-label"> اسم خط فروش </label>
                            <input type="text" name="name" class="form-control" id="lineNameId">
                            <input type="hidden" name="snSaleLine" class="form-control" id="SaleLineId">
                        </div>
                        <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-success">ذخیره <i class="fa fa-save"></i></button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">بستن</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection