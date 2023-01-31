@extends('layout')
@section('content')
    <div class="container-fluid containerDiv">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 sideBar">
                <fieldset class="border rounded mt-5 sidefieldSet">
                    <legend  class="float-none w-auto legendLabel mb-0"> افزایش و کاهش امتیازات </legend>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="positiveBonusRadio">
                            <label class="form-check-label me-4" for="assesPast"> امتیاز های اضافه شده</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="negativeBonusRadio">
                            <label class="form-check-label me-4" for="assesPast"> امتیاز های کم شده </label>
                        </div>
                        <br>
                        <div class="form-group col-sm-12 mb-2">
                            <input type="text" name="" placeholder="ازتاریخ" class="form-control form-control-sm" id="firstDateReturned">
                        </div>
                        
                        <div class="form-group col-sm-12 mb-2">
                            <input type="text" name="" placeholder="تا تاریخ" class="form-control form-control-sm" id="secondDateReturned">
                        </div>
                        <div class="form-group col-sm-12 mb-2">
                            <button class='btn btn-primary btn-sm text-warning' type="button" id='getHistorySearchBtn'> بازخوانی <i class="fal fa-dashboard fa-lg"></i></button>
                        </div>
                        <div class="col-lg-12" style="margin-top:40vh">
                            <button type="button" class="btn d-block w-50 btn-primary btn-sm" data-bs-target="#creditSetting" data-bs-toggle="modal" > افزایش <i class="fa fa-plus"></i> </button>
                            <button type="button" class="btn d-block w-50 btn-primary btn-sm" data-bs-target="#decreasingCredit" data-bs-toggle="modal" > کاهش <i class="fa fa-minus"></i> </button>
                            <button type="button" class="btn d-block w-50 btn-primary btn-sm" id="editCreditBtn"> اصلاح <i class="fa fa-edit"></i> </button>
                            <button type="button" class="btn d-block w-50 btn-danger btn-sm" id="deleteCreditBtn"> حذف <i class="fa fa-trash"></i>  </button>
                        </div>
                </fieldset>
            </div>
            <div class="col-sm-10 col-md-10 col-sm-10 contentDiv">
                <div class="row contentHeader"> 
                    <div class="col-sm-8 text-end">
                        <div class="row">
                            <div class="form-group col-sm-2 mt-2 px-1">
                                <input type="text" name="" placeholder="جستجو" class="form-control form-control-sm " id="searchUpDownHistoryName">
                            </div>
                            <div class="form-group col-sm-2 mt-2 px-1">
                                <select class="form-select form-select-sm" id="orderBonusHistory">
                                    <option value="-1">مرتب سازی</option>
                                    <option value="name">اسم</option>
                                    <option value="TimeStamp"> تاریخ  </option>
                                    <option value="Bonus"> امتیاز </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 text-start">
                       
                    </div>
                </div>
                <div class="row mainContent"> 
                   <div class="col-lg-12 px-0">
                        <table class="table table-bordered table-striped" id="tableGroupList">
                            <thead class="tableHeader">
                                <tr>
                                    <th>ردیف</th>
                                    <th> تاریخ </th>
                                    <th> اسم شخص </th>
                                    <th> تعداد امتیاز </th>
                                    <th> کاربر </th>
                                </tr>
                            </thead>
                            <tbody class="tableBody" id="historyListBody">
                                @foreach ($admins as $admin)
                                    <tr onclick="setUpDownHistoryStuff(this,{{$admin->historyId}})">
                                        <td>{{$loop->iteration}}</td>
                                        <td> {{$admin->TimeStamp}} </td>
                                        <td>{{trim($admin->adminName)}}</td>
                                        <td @if($admin->negativeBonus>0) style="color:red;" @else   @endif>@if($admin->positiveBonus>0){{trim($admin->positiveBonus)}} @else {{trim($admin->negativeBonus)}} @endif</td>
                                        <td>{{trim($admin->superName)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="grid-today rounded-2 mx-2">
                            <div class="today-item"> <span style="color:red; font-weight:bold;"> توضیحات: </span> <span id="historyBonusDesc"></span>  </div>
                        </div>
                   </div>
                </div>
                <div class="row contentFooter">
                    <div class="col-lg-12 text-start mt-2">
                        <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getUpDownHistory('TODAY')"> امروز  : </button>
                        <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getUpDownHistory('YESTERDAY')"> دیروز : </button>
                        <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getUpDownHistory('LASTHUNDRED')"> صد تای آخر : 100</button>
                        <button type="button" class="btn btn-sm btn-primary footerButton" onclick="getUpDownHistory('ALL')"> همه : </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Modal for adding Emtyaz -->
<div class="modal fade dragableModal" id="creditSetting" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="creditSettingLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header py-2 text-white">
          <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
          <h6 class="modal-title" id="creditSettingLabel"> افزایش امتیاز  </h6>
      </div>
      <div class="modal-body">
            <form action="{{url('/addUpDownBonus')}}" id="addingEmtyaz" method="get">
                    @csrf
                        <input type="hidden" name="adminId" value="">
                        <div class="row">
                                <div class="col-lg-6">
                                    <label for="pwd" class="form-label"> کاربر امتیاز گیرنده  </label>
                                    <select class="form-select form-select-sm" name="adminId">
                                        @foreach($employies as $employee)
                                            <option value="{{$employee->id}}"> {{$employee->name.' '.$employee->lastName}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="pwd" class="form-label">افزایش امتیاز </label>
                                    <input type="text" name="positiveBonus" class="form-control form-control-sm" id="pwd" placeholder="افزایش امتیاز">
                                </div>
                        </div>
                        <div class="row mt-2">
                            <label for="comment">توضیحات </label>
                            <textarea class="form-control" rows="3" id="comment" name="discription"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">بستن <i class="fa fa-xmark"> </i></button>
                        <button type="submit" class="btn btn-primary btn-sm"> ذخیره <i class="fa fa-save"> </i> </button>
                    </div>
            </form>
         </div>
    </div>
  </div>
</div>


<!-- Modal for Decreasing Credit -->
<div class="modal fade dragableModal" id="decreasingCredit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="decreasingCreditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header py-2 text-white bg-danger">
          <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
          <h6 class="modal-title" id="decreasingCreditLabel"> کاهش امتیاز  </h6>
      </div>
      <div class="modal-body p-3">
            <form action="{{url('/addUpDownBonus')}}" id="decreasingEmtyaz" method="get">
                    @csrf
                        <input type="hidden" name="adminId" value="">
                        <div class="row">
                                <div class="col-lg-6">
                                    <label for="pwd" class="form-label"> کاربر امتیاز گیرنده  </label>
                                    <select class="form-select form-select-sm" name="adminId">
                                        @foreach($employies as $employee)
                                            <option value="{{$employee->id}}"> {{$employee->name.' '.$employee->lastName}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="pwd" class="form-label">کاهش امتیاز </label>
                                    <input type="text" class="form-control form-control-sm" name="negativeBonus" placeholder="کاهش امتیاز">
                                </div>
                        </div>
                        <div class="row mt-2">
                            <label for="comment">توضیحات </label>
                            <textarea class="form-control" rows="3" id="comment" name="discription"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm"> ذخیره <i class="fa fa-save"> </i> </button>
                        <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">بستن <i class="fa fa-xmark"> </i></button>
                    </div>
            </form>
         </div>
    </div>
  </div>
</div>


<!-- Modal for adding Emtyaz -->
<div class="modal fade dragableModal" id="editingCredit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editingCreditLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header py-2 text-white">
          <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
          <h6 class="modal-title" id="editingCreditLabel"> ویرایش افزایش و کاهش امتیاز </h6>
      </div>
      <div class="modal-body">
            <form action="{{url('/editUpDownBonus')}}" id="editEmtyaz" method="get">
                    @csrf
                        <input type="text" name="historyId" id="historyId">
                        <div class="row">
                                <div class="col-lg-4">
                                    <label for="pwd" class="form-label"> کاربر امتیاز گیرنده  </label>
                                    <select class="form-select form-select-sm" name="adminId" id="adminBonusTaker">
                                        
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="pwd" class="form-label">افزایش امتیاز </label>
                                    <input type="text" name="positive" class="form-control form-control-sm" id="pBonus" placeholder="افزایش امتیاز">
                                </div>
                                <div class="col-lg-4">
                                    <label for="pwd" class="form-label">کاهش امتیاز </label>
                                    <input type="text" name="negative" class="form-control form-control-sm" id="nBonus" placeholder="کاهش امتیاز">
                                </div>
                        </div>
                        <div class="row mt-2">
                            <label for="comment">توضیحات </label>
                            <textarea class="form-control" rows="3" id="commentBonus" name="discription"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">بستن <i class="fa fa-xmark"> </i></button>
                        <button type="submit" class="btn btn-primary btn-sm"> ذخیره <i class="fa fa-save"> </i> </button>
                    </div>
            </form>
         </div>
    </div>
  </div>
</div>



<script>

    function wantoDelet(){
        swal({
            title: "آیا مطمئن هستید؟",
            text: "پس از حذف، نمی توانید این معلومات را بازیابی کنید!",
            icon: "اخطار",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                swal("معلومات موفقانه حذف گردید!", {
                icon: "success",
                });
            } else {
                swal("معلومات شما محفوظ است.");
            }
            });
    }
</script>

@endsection



