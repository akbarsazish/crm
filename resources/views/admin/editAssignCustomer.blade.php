

@extends('layout')
@section('content')

 <div class="container-fluid containerDiv">
            <div class="spinner-border text-danger" role="status" id="transferLoader" style="display:none;">
                <span class="sr-only">Loading...</span>
            </div>
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0">  تخصیص به کاربران   </legend>
                       
                         -->
                         <div class="form-group mb-1 col-sm-12">
                            <input type="text" style="display:none" id="asn"/>
                                <select name=""  class="form-select form-select-sm" id="searchCity">
                                <option value="" hidden>شهر</option>
                                <option value="0">همه</option>
                                @foreach($cities as $city)
                                    <option value="{{$city->SnMNM}}">{{trim($city->NameRec)}}</option>
                                @endforeach
                            </select>
                        </div>
                         <div class="form-group mb-1 col-sm-12">
                            <select name="" class="form-select form-select-sm" id="searchMantagheh">
                                <option hidden>منطقه</option>
                            </select>
                        </div>
                        <div class="form-group mb-2 col-sm-12">
                            <select name="" class="form-select form-select-sm" disabled id="activeOrInActive">
                                <option hidden>نوعیت مشتری</option>
                                <option value="1">فعال</option>
                                <option value="2">غیر فعال</option>
                                <option value="3">جدیدی ها</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 mb-1">
                                <input type="text" name="" placeholder="ازتاریخ" class="form-control form-control-sm" id="firstDateReturned">
                            </div>
                            <div class="form-group col-sm-12 mb-2">
                                <input type="text" name="" placeholder="تا تاریخ" class="form-control form-control-sm" id="secondDateReturned">
                            </div>
                        
                    </fieldset>
                  </div>
                <div class="col-sm-8 col-md-8 col-sm-12 contentDiv">
                    <div class="row contentHeader">
                        <div class="col-lg-9">
                            <div class="form-group mt-2 col-sm-2">
                                <input type="text" name="" placeholder="اسم" size="20" class="form-control form-control-sm" id="searchNameByMNM">
                            </div>
                        </div>
                        <div class="col-lg-3 text-start">
                              <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" id="cancelMoveKarbar"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                              <button type="button" class="btn btn-sm btn-primary" data-dismiss="modal" id="cancelMoveKarbar"> ثبت <i class="fa-solid fa-save"> </i> </button>
                        </div>
                        
                    </div>
                    <div class="row mainContent">
                          <div class="row text-center mx-0 px-0" id="customerContainer">
                            <div class="col-sm-5 px-0 mx-0">
                                <input type="text" id="AdminForAdd" style="display: none" >
                                  <table class="table table-bordered table-striped table-hover" id="allCustomers" style="border-left:1px solid #cbcbcb;">
                                    <thead class="tableHeader">
                                        <tr>
                                            <th>ردیف</th>
                                            <th> منطقه  </th>
                                            <th> نام و نام خانوادگی</th>
                                            <th> <input type="checkbox" name="" class="selectAllFromTop form-check-input" id="selectAllTopRight"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="allCustomer" class="tableBody">
                                    </tbody>
                             </table>
                          </div>
                        <div class="col-sm-2" style="">
                            <div class='modal-body' style="position:relative; right: 3%; top: 30%;">
                                <a id="addCustomerToAdmin"> <i class="fa-regular fa-circle-chevron-left fa-4x"></i></a>
                                <br />
                                <a id="removeCustomerFromAdmin"> <i class="fa-regular fa-circle-chevron-right fa-4x"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-5 px-0 mx-0">
                                <table class="table table-bordered table-striped table-hover"  id="addedCustomers" style="100%">
                                <thead class="tableHeader">
                                        <tr>
                                        <th>ردیف</th>
                                        <th> منطقه  </th>
                                        <th> نام و نام خانوادگی</th>
                                        <th> <input type="checkbox" name="" class="selectAllFromTop form-check-input" id="selectAllTopLeft"></th>
                                    </tr>
                                </thead>
                                <tbody id="addedCustomer" class="tableBody" style="border-right:1px solid #cbcbcb;">
                                </tbody>
                            </table>
                         </div>
                      </div>
                 
                     </div>
                    <div class="row contentFooter"> </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                 <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0">  تخصیص به کاربران   </legend>
                            <div class="form-group mb-1 col-sm-12">
                            <select name=""  class="form-select form-select-sm" id="searchAddedCity">
                            <option value="0" hidden>شهر</option>
                            <option value="1" >همه</option>
                                @foreach($cities as $city)
                                <option value="{{$city->SnMNM}}">{{trim($city->NameRec)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1 col-sm-12">
                            <select name="" class="form-select form-select-sm" id="searchAddedMantagheh">
                                <option value="" hidden>منطقه</option>
                                <option value="0">همه</option>
                            </select>
                        </div>
                        
                          <div class="form-group mb-1 col-sm-12">
                            <label class="form-label"> توضیحات</label>
                            <textarea class="form-control" cols="10" rows="4" name="discription" style="background-color:blanchedalmond" id="adminDiscription"></textarea>
                          </div>
                    </fieldset>
               </div>
        </div>
    </div>

    

             <!-- modal for removing user profile -->
             <div class="modal fade dragableModal" id="moveKarbar" role="dialog"   data-backdrop="static" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable  modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="margin:0; border:none">
                            <button type="button" class="btn-close btn-danger" style="background-color:red;" data-dismiss="modal" aria-label="Close"></button>
                            <h5 class="modal-title" id="exampleModalLongTitle"> انتقال مشتریان از کاربر به کاربر  </h5>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>نام کاربر </th>
                                        <th>نقش کاربر </th>
                                        <th>توضیحات</th>
                                  </tr>
                                </thead>
                                <tbody id="adminToMove" class="tableBody">

                                </tbody>
                            </table>
                                <input type="text" id="adminID" >
                                <input type="text" id="adminTakerId">
                            <table class="table table-bordered">
                                <thead class="text-warning tableHeader">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کاربر </th>
                                        <th>نقش کاربر </th>
                                        <th>توضیحات</th>
                                        <th>انتخاب </th>
                                  </tr>
                                </thead>
                                <tbody id="selectKarbarToMove" class="tableBody">

                                </tbody>
                            </table>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="cancelMoveKarbar"> انصراف <i class="fa-solid fa-xmark"> </i> </button>
                            <button type="button" onclick="moveStaff()"  class="bt btn-danger btn-lg"> انتقال <i class="fa fa-sync"></i> </button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
