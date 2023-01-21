@extends('layout')
@section('content')
<style> 
@media (max-width:920px){
	.salesExpertMobile{
		margin-top:22% !important;
	}
}

</style>
<div class="container salesExpertMobile" style="margin-top:8%;">
               <div class="row">
				    <div class="col-lg-6 col-sm-6 col-6">
						@if(Session::get('adminType')==5)
                           <span class="mb-3 fs-4" >کارشناسان فروش</span>
						@endif
                   </div>  
                    <div class="col-lg-6 col-sm-6 col-6 d-flex justify-content-end">
                        <form action="{{url('/saleExpertActionInfo')}}" method="get">
                            <input type="hidden" id="subBazaryabId" name="subId">
                            <button class="btn btn-sm btn-primary" disabled id="subListDashboardBtn"> رفتن به جزئیات <i class="fa fa-info-circle" aria-hidden="true"></i> </button>
                        </form>
                  </div>
		  </div>
	@if(Session::get('adminType')==5)
        <div class="col-lg-12">
          <div class="accordion accordion-flush allUsers" id="accordionFlushExample">
             @foreach ($bosses as $boss)
                <div class="accordion-item eachUser" id="topEmployee">
                      <h2 class="accordion-header" id="flush-heading{{$loop->iteration}}">
                           <button class="accordion-button collapsed" type="button" onclick="getBossBazarYab({{$boss->id}}, {{$loop->iteration}})" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{$loop->iteration}}" aria-expanded="false" aria-controls="flush-collapseOne">
                             {{trim($boss->name)." ".trim($boss->lastName)}}
                           </button>
                       </h2>
                       <div id="flush-collapse{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <table class=" select-highlight factor table" id="tableGroupList">
                                <thead style="position: sticky;top: 0;">
                                    <tr>
                                        <th>ردیف</th>
                                        <th>نام کاربر</th>
                                        <th>فعال</th>
                                    </tr>
                                </thead>
                                <tbody id="fellowEmployee{{$loop->iteration}}">
                                      
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
              @endforeach
             </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-md-12">
            
            <fieldset class="rounded">
                <legend  class="float-none w-auto">  بازاریاب ها</legend>
               
                <div class="row">
                   <div class="col-lg-12">
                    <table class=" select-highlight homeTables table" id="tableGroupList"  style='width:100%;'>
                        <thead style="position: sticky;top: 0;">
                            <tr>
                                <th style="width:50px">ردیف</th>
                                <th style="width:120px">نام کاربر</th>
                                <th style="width:300px;">توضیحات</th>
                                <th style="width:50px">فعال</th>
                            </tr>
                        </thead>
                        <tbody class="c-checkout" id="adminGroupList">
                            @foreach ($admins as $admin)
                                <tr onclick="setSubBazaryabStuff(this)">
                                    <td style="width:88px">{{$loop->iteration}}</td>
                                    <td style="width:140px">{{trim($admin->name)." ".trim($admin->lastName)}}</td>
                                    <td style="width:300px">{{trim($admin->discription)}}</td>
                                    <td style="width:60px">
                                        <input class="mainGroupId" type="radio" name="AdminId[]" value="{{$admin->id}}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  </div>
                </div>
            </fieldset>
        </div>
    </div>
@endif
</div>

