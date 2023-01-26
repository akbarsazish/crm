@extends('layout')
@section('content')
    <div class="container-fluid containerDiv">
      <div class="row">
               <div class="col-lg-2 col-md-2 col-sm-3 sideBar">
                   <fieldset class="border rounded mt-5 sidefieldSet">
                        <legend  class="float-none w-auto legendLabel mb-0"> افزایش و کاهش امتیازات </legend>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="elseSettingsRadio">
                            <label class="form-check-label me-4" for="assesPast">  جمع کل امتیاز </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input p-2 float-end" type="radio" name="settings" id="settingAndTargetRadio">
                            <label class="form-check-label me-4" for="assesPast"> تاریخچه عملکرد </label>
                        </div>
                        <div class="row">
                             <div class="col-lg-12" style="margin-top:40vh">
                                 <button type="button" class="btn d-block w-50 btn-primary btn-sm" data-bs-target="#creditSetting" data-bs-toggle="modal"> افزایش <i class="fa fa-plus"></i> </button>
                                 <button type="button" class="btn d-block w-50 btn-primary btn-sm"> کاهش <i class="fa fa-minus"></i> </button>
                                 <button type="button" class="btn d-block w-50 btn-primary btn-sm"> اصلاح <i class="fa fa-edit"></i> </button>
                                 <button type="button" class="btn d-block w-50 btn-danger btn-sm"> حذف <i class="fa fa-trash"></i>  </button>
                             </div>
                        </div>
                    </fieldset>
                  </div>
                <div class="col-sm-10 col-md-10 col-sm-12 contentDiv">
                    <div class="row contentHeader"> </div>
                    <div class="row mainContent"> 

                    </div>
                    <div class="row contentFooter"> </div>
                </div>
        </div>
    </div>


<!-- Modal for adding Emtyaz -->
<div class="modal fade dragableModal" id="creditSetting" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="creditSettingLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header py-2 text-white">
          <button type="button" class="btn-close bg-danger" data-bs-dismiss="modal" aria-label="Close"></button>
          <h6 class="modal-title" id="creditSettingLabel"> افزایش و کاهش امتیاز </h6>
      </div>
      <div class="modal-body">
            <form action="{{url('/addUpDownBonus')}}" id="addingEmtyaz" method="get">
                    @csrf
                        <input type="hidden" name="adminId" value="">
                          <div class="row">
                              <div class="col-lg-3">
                                 <label for="pwd" class="form-label"> کاربر امتیازدهنده  </label>
                                    <select class="form-select form-select-sm" id="orderInactiveCustomers">
                                        <option value="-1"> احمد پور </option>
                                        <option value="2"> خانم ناصری  </option>
                                       
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="pwd" class="form-label">کاهش امتیاز </label>
                                    <input type="text" name="negative" class="form-control" id="pwd" placeholder="کاهش امتیاز">
                                </div>
                                <div class="col-lg-3">
                                    <label for="pwd" class="form-label">افزایش امتیاز </label>
                                    <input type="text" name="positive" class="form-control" id="pwd" placeholder="افزایش امتیاز">
                                </div>
                        </div>
                        <div class="row mt-2">
                            <label for="comment">توضیحات </label>
                            <textarea class="form-control" rows="3" id="comment" name="discription"></textarea>
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

@endsection



