<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.01">
    <script>
        window.addEventListener("load", function() {
            function onTouchPreventDefault(event) { event.preventDefault(); };
            document.addEventListener("touchmove", onTouchPreventDefault, false);
            document.addEventListener("touchstart", onTouchPreventDefault, false);
        }, false);
    </script>
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" /> -->
    <title> CRM </title>
	<link rel="icon" type="image/png" href="{{url('/resources/assets/images/part.png')}}"/>
    <link rel="stylesheet" href="{{ url('/resources/assets/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{ url('/resources/assets/fontawesome/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{ url('/resources/assets/css/mainAdmin.css')}}"/>
    <link rel="stylesheet" href="{{url('/resources/assets/css/jquery-ui.min.css')}}"/>
    <link rel="stylesheet" href="{{url('/resources/assets/js/persianDatepicker-master/css/persianDatepicker-default.css')}}" />
    <link rel="stylesheet" href="{{url('/resources/assets/js/calculator/jqueryscripttop.css')}}"/>
    <link rel="stylesheet" href="{{url('/resources/assets/css/calculator.css')}}"/>
    <link rel="stylesheet" href="{{ url('/resources/assets/css/clockMain.css')}}"/>
	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin="" />
	<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
	<script src="{{url('/resources/assets/js/jquery.min.js')}}"></script>
	<script src="{{url('/resources/assets/js/persianDatepicker-master/js/jquery-1.10.1.min.js')}}"></script> 
	<script src="{{url('/resources/assets/js/jquery-ui.min.js')}}"></script> 
    <script src="{{url('/resources/assets/js/persianDatepicker-master/js/persianDatepicker.min.js')}}"></script> 
	<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
</head>
<body>    
    <header>
        <section class="topMenu">
            <section class="top-head container">
                <div class="right-head" >
                    <div id="mySidenav" class="sidenav" style="width:0px;overflow-x:hidden;margin-left:100px;">
                        <a href="javascript:void(0)" style="background-color:#42c0e6;color:white;width:100%" class="closebtn" onclick="closeNav()">&times;</a>
                        <a href="{{url('/userProfile')}}" class="sidenav__header" style=" text-align:center; height: 160px">
                            <img style="border-radius:50%; width:100px; height:100px; margin-top:10px;" src="{{url('resources/assets/images/admins/'.Session::get('asn').'.jpg')}}" />
                        </a>
                        <button onclick="closeNav()" class="closeMenuButton"><i class="fad fa-times"></i></button>
                        <div id='cssmenu' style="direction: rtl;">
                            <ul>
                                    <li class='has-sub'>
                                        <a class="mySidenav__item" href="#">
                                            <span>
                                                <i class="fa fa-bar-chart fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; اطلاعات پایه
                                            </span>
                                        </a>
                                        <ul>
                                            <li class=''><a class="mySidenav__item" href="{{url('/userProfile')}}"> &nbsp;&nbsp; <i class="fa-solid fa-user fa-lg"style="margin-right:15px; color:#ba7802;"></i>&nbsp; پروفایل </span></a> </li>
                                            <li class=''><a class="mySidenav__item" href="{{url('/randt')}}"> &nbsp;&nbsp; <i class="fa-solid fa-tasks fa-lg"style="margin-right:15px; color:#ba7802;"></i>&nbsp; R&D</span></a> </li>
                                            <li class=''><a class="mySidenav__item" href="{{url('/saleLine')}}"> &nbsp;&nbsp; <i class="fa-solid fa-tasks fa-lg"style="margin-right:15px; color:#ba7802;"></i>&nbsp; افزودن خط فروش</span></a> </li>
                                            <li class=''><a class="mySidenav__item" href="{{url('/bonusSetting')}}"> &nbsp;&nbsp; <i class="fa fa-cog fa-lg" style="margin-right:15px; color:#ba7802;"></i>&nbsp; تنظیمات  </span></a></li>
                                        </ul>
                                    </li>
                                    <li class='has-sub'>
                                        <a class="mySidenav__item" href="#">
                                            <span>
                                                <i class="fa fa-bar-chart fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; تعریف عناصر
                                            </span>
                                        </a>
                                        <ul>
                                        <li class=''><a class="mySidenav__item" href="{{url('/listKarbaran')}}"> &nbsp;&nbsp; <i class="fa-solid fa-tasks fa-lg"style="margin-right:15px; color:#ba7802;"></i>&nbsp; لیست کاربران</span></a> </li>
                                        </ul>
                                    </li>
                                    <li class='has-sub'>
                                        <a class="mySidenav__item" href="{{url('/dashboardAdmin')}}">
                                            <span>
                                                <i class="fa fa-bar-chart fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; عملیات
                                            @if($reffs>0 or $countNewCustomers>0)
                                                <span class="position-absolute badge rounded-pill bg-danger"> {{$reffs+$countNewCustomers}}</span>
                                            @endif
                                            </span>
                                        </a>
                                        <ul>
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/assignCustomer')}}"> &nbsp;&nbsp; <i class="fa-solid fa-tasks fa-lg"style="color:#fff;"></i>&nbsp; تخصیص به کاربر </span></a> </li>
                                            {{-- <li class=''><a class="mySidenav__item text-white" href="{{url('/karbaranOperations')}}"> &nbsp;&nbsp; <i class="fa-solid fa-tasks fa-lg"style="color:#fff;"></i>&nbsp; کاربران </span></a> </li> --}}
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/driverService')}}"> &nbsp;&nbsp; <i class="fas fa-car fa-lg"style="color:#fff;"></i>&nbsp;  راننده ها </span></a></li>
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/commentToday')}}"><span>  &nbsp; &nbsp; <i class="fa fa-check fa-lg" style="color:#fff;"></i>&nbsp; نظر سنجی  </span></a> </li>
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/bonusIncreaseDecrease')}}"><span>  &nbsp; &nbsp; <i class="fa-solid fa-plus-minus" style="color:#fff;"></i>  افزایش و کاهش امتیاز </span></a> </li>
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/randt')}}"> <span>  &nbsp; &nbsp; <i class="fa-solid fa-tasks fa-lg" style="color:#fff;"> </i> R&T</span></a> </li>
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/calendar')}}"><span> &nbsp; &nbsp; <i class="fa-solid fa-calendar fa-lg" style="color:#fff;"></i> تقویم روزانه  </span></a></li>
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('crmDriver?asn='.SESSION::get('dsn').'')}}"><span> &nbsp; &nbsp;  <i class="fas fa-car fa-lg" style="color:#fff;"></i>&nbsp;&nbsp;   بارگیری  </span></a></li>
                                            @php
                                            $hasAlarm=Session::get("hasAlarm");
                                            @endphp
                                            @if(trim(Session::get("hasAlarm"))=="on")
                                            <li class=''><a class="mySidenav__item text-white" href="{{url('/alarm')}}">  &nbsp; &nbsp; <i class="fa fa-exclamation-triangle" style="color:#fff;"></i> &nbsp; آلارم   @if($countAlarms>0) <span class="position-absolute badge rounded-pill bg-danger"> {{$countAlarms}} </span>  @endif </a> </li>
                                            @endif
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/message')}}"> &nbsp; &nbsp; <span><i class="fas fa-message fa-lg" style="color:#fff;"></i>&nbsp;&nbsp;پیام ها  @if($inbox) <span class="position-absolute badge rounded-pill bg-danger"> {{$inbox}} </span> @endif  </span></a>  </li>
                                        </ul>
                                    </li>
                                    <li class='has-sub'><a class="mySidenav__item" href="{{url('/dashboardAdmin')}}"><span><i class="fa fa-bar-chart fa-lg" style="color:#fff;"></i>&nbsp;&nbsp; گزارشات   </span></a>
                                        <ul>
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/subTrees')}}"><i class="fas fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i>&nbsp; عملکرد کاربران </a></li>
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/amalKardKarbarn')}}"><i class="fas fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i>&nbsp; تراز کاربران  </a></li>
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/reports')}}"><i class="fas fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i>&nbsp; عملکرد مشتریان </a></li>
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/kalaAction')}}"><i class="fa-regular fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i> &nbsp; عملکرد کالا </a></li>
                                        @if(Session::get("employeeType")==3)
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/saleExpertActionInfo?subId='.Session::get('asn'))}}"><i class="fa-regular fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i> &nbsp; عملکرد</a></li>
                                        @elseif(Session::get("employeeType")==4)
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/poshtibanActionInfo?subPoshtibanId='.Session::get('dsn'))}}"><i class="fa-regular fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i> &nbsp; عملکرد </a></li>
                                        @else
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/poshtibanActionInfo?subPoshtibanId='.Session::get('asn'))}}"><i class="fa-regular fa-tasks fa-lg" style="color:#fff; margin-right:20px;"></i> &nbsp; عملکرد</a></li>
                                        @endif
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/sendBackReport')}}"><i class="fa-regular fa-send-back fa-lg" style="color:#fff; margin-right:20px;"></i> &nbsp;  گزارش برگشتی کالا </a></li>
                                        <li class=''><a class="mySidenav__item text-white" href="{{url('/bargeryInfo')}}"><span> &nbsp; &nbsp;  <i class="fas fa-car fa-lg" style="color:#fff;"></i>&nbsp;&nbsp;   گزارش بارگیری  </span></a></li>
                                        </ul>
                                    </li>

                                <li class='last'><a class="mySidenav__item" href="{{url('/logoutUser')}}"><span><i class="fa-solid fa-sign-out fa-lg" style="color:rgb(247, 233, 233)" ></i>&nbsp;&nbsp; خروج </span></a></li>
                            </ul> 
                        </div>
                    </div>
                    <div id="MenuBack" style="font-size:25px;cursor:pointer;color:white;display:flex;justify-content:flex-start;text-align:right;width:34px">
                        <i onclick="goBack()" class="fas fa-chevron-right"></i>
                    </div>
                    <div style="font-size:25px;cursor:pointer;color:white;display:flex;justify-content:flex-start;text-align:right;width:25px; margin-right: 15px; margin-left: 50px" onclick="openNav()">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
                </div>
                    <div class="left-head">
                        <img onclick="calcAndClock()" id="myWatch" style="width:50px; height:50px; cursor:pointer;" class="showContent driverTable" src="{{url('resources/assets/images/clock.png')}}" alt="ساعت">
                        <img onclick="clockAndClac()" id="myCalculator" style="width:45px; height:50px; cursor:pointer;" class="showContent" src="{{url('resources/assets/images/calc.png')}}" alt="ماشین حساب">
                     <div class="devider">
                    </div>
                        <a class="headerOptions" style="font-family: IRANSans !important;" href="{{url('/message')}}">
                            <i class="far fa-envelope"></i>@if($inbox>0)
                            <span class="position-relative top-0 start-0 translate-middle p-2 bg-danger border border-light rounded-circle">{{$inbox}}</span>
                            
                            @endif
                        
                        </a>
                    </div>
            </section>
        </section>
    </header>
     @include('/..clock') 
     @include('/..calculator')
    @yield('content')



<script src="{{url('/resources/assets/js/calculator/popper.min.js')}}"></script>
<script src="{{url('/resources/assets/js/calculator/math.min.js')}}"></script>
<script defer src="{{ url('/resources/assets/js/bootstrap.bundle.min.js')}}"></script>
<script defer src="{{ url('/resources/assets/js/bootstrap.min.js')}}"></script>
<script src="{{url('/resources/assets/js/sweetalert.min.js')}}"></script>
<script src="{{ url('/resources/assets/js/persianumber.min.js') }}"></script>

<script src="{{url('/resources/assets/js/amcharts/hierarchy.js')}}"></script>
<script src="{{url('/resources/assets/js/amcharts/index.js')}}"></script>
<script src="{{url('/resources/assets/js/amcharts/xy.js')}}"></script>
<script src="{{url('/resources/assets/js/amcharts/Animated.js')}}"></script>
<script src="{{url('/resources/assets/js/amcharts/percent.js')}}"></script>
<script src="{{url('/resources/assets/js/amcharts/wrld.js')}}"></script>


<script src="{{url('/resources/assets/js/jalali-moment.browser.js')}}"></script>
<script src="{{url('/resources/assets/js/script.js')}}"></script>
<script src="{{url('/resources/assets/js/main.js')}}"></script>
<script src="{{url('/resources/assets/js/jquery.thooClock.js')}}"></script>
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script> 

<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

var intVal, myclock;
// $(window).resize(function(){
//     window.location.reload()
// });
$(document).ready(function(){
    var audioElement = new Audio("");
    //clock plugin constructor
    $('#myclock').thooClock({
        size:180,
        sweepingMinutes:true,
        sweepingSeconds:true,
        onAlarm:function(){
            //all that happens onAlarm
            $('#alarm1').show();
            alarmBackground(0);
            //audio element just for alarm sound
            document.body.appendChild(audioElement);
            var canPlayType = audioElement.canPlayType("audio/ogg");
            if(canPlayType.match(/maybe|probably/i)) {
                audioElement.src = '/resources/assets/alarm.ogg';
            } else {
                audioElement.src = '/resources/assets/alarm.mp3';
            }
            // erst abspielen wenn genug vom mp3 geladen wurde
            audioElement.addEventListener('canplay', function() {
                audioElement.loop = true;
                audioElement.play();
            }, false);
        },
        showNumerals:true,
        brandText:'THOOYORK',
        brandText2:'Germany',
        onEverySecond:function(){
        },
        offAlarm:function(){
            $('#alarm1').hide();
            audioElement.pause();
            clearTimeout(intVal);
            $('body').css('background-color','#FCFCFC');
        }
    });
});

$('#turnOffAlarm').click(function(){
    $.ajax({
        method: 'get',
        url: baseUrl + "/offAlarmClock",
        async: true,
        success: function(arrayed_result) {
            swal("آلارم خاموش شد!", {
                icon: "success",
            });
            $("#alarmShows").text("ثبت نشده است");
            $("#alarmComment").css('display',"flex");
            $("#set").css('display',"flex");
            $("#altime").css('display',"flex");
        },
        error: function(data) {
            alert(data[0]);
        }
    });
    $.fn.thooClock.clearAlarm();
});

$('#set').click(function(){
    let dateTime=$("#altime").val();
    let comment=$("#alarmComment").val();
    $("#altime").val("");
    $("#alarmComment").val("");
    if(dateTime.length>3){
    $.ajax({
        method: 'get',
        url: baseUrl + "/addAlarmClock",
        data: {
            _token: "{{ csrf_token() }}",
            dateTime: dateTime,
            comment:comment
        },
        async: true,
        success: function(arrayed_result) {
            $("#alarmComment").css('display',"none");
            $("#set").css('display',"none");
            $("#altime").css('display',"none");
            $("#alarmShows").text(dateTime);
            swal("شما یک آلارم ثبت نمودید!", {icon: "success",});

            $.ajax({
            method: 'get',
            url: baseUrl + "/getAlarmTime",
            async: true,
            success: function(arrayed_result) {
                const comment=arrayed_result[3].comment;
                if(arrayed_result[0]){
                    setTimeout(() => {
                    var inp = $('#altime').val();
                    $.fn.thooClock.setAlarm(inp);
                    $("#alarmShows").text(comment);
                    },1);
                }else{
                    setTimeout(() => {
                    var inp = $('#altime').val();
                    $.fn.thooClock.setAlarm(inp);
                    },arrayed_result[1]*1000);
                    $("#alarmComment").css('display',"none");
                    $("#set").css('display',"none");
                    $("#altime").css('display',"none");
                }
            },
            error: function(data) {
            }
        });

        },
        error: function(data) {
            
        }
    });}else{
        $("#altime").css({"border":"2px solid red","backgroundColor":"black","color":"white"});
    }
});

$.ajax({
        method: 'get',
        url: baseUrl + "/getAlarmTime",
        async: true,
        success: function(arrayed_result) {
        if(arrayed_result[0]){
            setTimeout(() => {
            var inp = $('#altime').val();
            $.fn.thooClock.setAlarm(inp);
            $("#alarmShows").text("الآن");
            },1);
        }else{
            setTimeout(() => {
            var inp = $('#altime').val();
            $.fn.thooClock.setAlarm(inp);
            },arrayed_result[1]*1000);
            $("#alarmComment").css('display',"none");
            $("#set").css('display',"none");
            $("#altime").css('display',"none");
        }
        },
        error: function(data) {
        }
    });

function alarmBackground(y){
    var color;
    if(y===1){
        color = '#CC0000';
        y=0;
    }
    else{
        color = '#FCFCFC';
        y+=1;
    }
    $('body').css('background-color',color);
    intVal = setTimeout(function(){alarmBackground(y);},100);
}

   function deleteConfirm() {
          swal({
                  title: "مطمئین هستید؟",
                  text: "پس از خذف نمی توانید این فایل را بازیابی نمایید!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                  })
          .then((willDelete) => {
            if (willDelete) {
                  swal("فایل شما حذف گردید!", {
                  icon: "success",
                });
              }
            });
        }

    function goBack() {
            window.history.back();
        }
    function openNav() {
        document.getElementById("mySidenav").style.width = "260px";
    }
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
// ---------------------------------- new sidebar code---------------------------------------
$('#cssmenu > ul > li ul').each(function(index, e){
  var count = `<i class="fa-solid fa-caret-down text-white"></i>`;
  var content = '<span class="cnt">' + count + '</span>';
  $(e).closest('li').children('a').append(content);
});

$('#cssmenu ul ul li:odd').addClass('odd');
$('#cssmenu ul ul li:even').addClass('even');
$('#cssmenu > ul > li > a').click(function() {
  $('#cssmenu li').removeClass('active');
  $(this).closest('li').addClass('active');
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('li ul')) && (!checkElement.is(':visible'))) {
    
    $('#cssmenu ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;
  }
});

$('#cssmenu > ul> li> ul> li ul').each(function(index, e){
  var count = `<i class="fa-solid fa-caret-down text-white"></i>`;
  var content = '<span class="cnt">' + count + '</span>';
  $(e).closest('li').children('a').append(content);
});

$('#cssmenu > ul> li> ul> li> a').click(function() {
  $('#cssmenu li').removeClass('active');
  $(this).closest('li').addClass('active');
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    
    $(this).closest('li').removeClass('active');
    $('#cssmenu ul ul ul:visible').slideUp('normal');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('li ul')) && (!checkElement.is(':visible'))) {
    
    $('#cssmenu ul ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;
  }
});

$("#show-date").text(moment().locale('fa').format('YYYY/M/D'));
</script>

	 
</body>
</html>
