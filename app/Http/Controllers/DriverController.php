<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use DB;
use Response;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
use Session;
class DriverController extends Controller
{
    //
    public function index(Request $request)
    {
        $drivers=DB::table("Shop.dbo.sla_Drivers")->get();
        return $drivers;
    }

    public function crmDriver() {
        $adminId=Session::get("dsn");
        $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
        $factors=DB::select("select Peopels.PSN,Peopels.Name,FactorHDS.FactNo,Peopels.LatPers,Peopels.LonPers,FactorHDS.SerialNoHDS,FactorHDS.FactDate,Sla_Drivers.NameDriver,a.PhoneStr,Peopels.peopeladdress,SnBargiryBYS,TotalPriceHDS from Shop.dbo.BargiryBYS Join Shop.dbo.FactorHDS on BargiryBYS.SnFact=FactorHDS.SerialNoHDS Join Shop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN 
        Join Shop.dbo.BargiryHDS on BargiryHDS.SnMasterBar=BargiryBYS.SnMaster Join Shop.dbo.Sla_Drivers on Sla_Drivers.SnDriver=BargiryHDS.SnDriver
        Join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
							FROM Shop.dbo.PhoneDetail
							GROUP BY SnPeopel)a on PSN=a.SnPeopel 
         where FactDate='$todayDate' and Sla_Drivers.SnDriver=".$adminId);
         $customerIds=array();
         foreach ($factors as $factor) {
            array_push($customerIds,$factor->PSN);
         }
         
        return view('driver.driverList',['factors'=>$factors,'customerIDs'=>$customerIds]);
    }

    public function setReciveMoneyDetail(Request $request)
    {
        $bargiryId=str_replace(",", "",$request->get("bargiriId"));
        $naghPrice=str_replace(",", "",$request->get("naghdPrice"));
        $cardPrice=str_replace(",", "",$request->get("cardPrice"));
       // $varizPrice=str_replace(",", "",$request->get("varizPrice"));
        $takhfifPrice=str_replace(",", "",$request->get("takhfifPrice"));
        $diffPrice=str_replace(",", "",$request->get("diffPrice"));
        $description=$request->get("description");
        DB::table("Shop.dbo.BargiryBYS")->where("SnBargiryBYS",$bargiryId)->update([
        'NaghdPrice'=>$naghPrice*10
        ,'KartPrice'=>$cardPrice*10
        ,'DifPrice'=>$diffPrice*10
        ,'DescRec'=>"".$description.""
        ,'VarizPrice'=>0
        ,'TakhfifPriceBar'=>$takhfifPrice*10]);
        $reciveDetail=DB::table("Shop.dbo.BargiryBYS")->where("SnBargiryBYS",$bargiryId)->first();
        return Response::json($reciveDetail);

    }



// the following method is for searching the bargeri list 
public function crmDriverSearch(Request $request) {
    $searchTerm=$request->get("searchTerm");
    $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
    $factors=DB::select("select Peopels.PSN,Peopels.Name,FactorHDS.FactNo,Peopels.LatPers,Peopels.LonPers,FactorHDS.SerialNoHDS,FactorHDS.FactDate,Sla_Drivers.NameDriver,a.PhoneStr,Peopels.peopeladdress from Shop.dbo.BargiryBYS Join Shop.dbo.FactorHDS on BargiryBYS.SnFact=FactorHDS.SerialNoHDS Join Shop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN 
    Join Shop.dbo.BargiryHDS on BargiryHDS.SnMasterBar=BargiryBYS.SnMaster Join Shop.dbo.Sla_Drivers on Sla_Drivers.SnDriver=BargiryHDS.SnDriver
    Join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
                        FROM Shop.dbo.PhoneDetail
                        GROUP BY SnPeopel)a on PSN=a.SnPeopel 
     where FactDate='$todayDate' and Name LIKE '%".$searchTerm."%'");
     $customerIds=array();
     foreach ($factors as $factor) {
        array_push($customerIds,$factor->PSN);
     }

    // return view('driver.driverList',['factors'=>$factors,'customerIDs'=>$customerIds]);
    return Response::json([$factors, $customerIds]);
}





    public function getFactorInfo(Request $request)
    {
        $fsn=$request->get("fsn");
        $bargiryId=$request->get("bargiriyBYSId");
        $factorInfo=DB::select("select PubGoods.GoodName,FactorBYS.Amount,PUBGoodUnits.UName,FactorBYS.Fi,FactorBYS.Price,PhoneStr,FactDate,Name,peopeladdress,FactNo from Shop.dbo.FactorHDS 
        join Shop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN 
        Join Shop.dbo.FactorBYS on FactorHDS.SerialNoHDS=FactorBYS.SnFact 
        join Shop.dbo.PubGoods on FactorBYS.SnGood=PubGoods.GoodSn 
		join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
							FROM Shop.dbo.PhoneDetail
							GROUP BY SnPeopel)a on PSN=a.SnPeopel
        Join Shop.dbo.PUBGoodUnits on PubGoods.DefaultUnit=PUBGoodUnits.USN
        where PubGoods.GoodGroupSn>49 and FactorHDS.SerialNoHDS=".$fsn);

        $reciveDetail=DB::table("Shop.dbo.BargiryBYS")->where("SnBargiryBYS",$bargiryId)->first();
        return Response::json([$factorInfo,$reciveDetail]);
    }

    // searching bargeri based on date
        public function searchBargeriByDate(Request $request){
            $adminId=Session::get("dsn");
            $secondDate=$request->get("secondDateBargeri");
            $fristDate=$request->get("firstDateBargeri");
            
            $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
            $factors=DB::select("select Peopels.PSN,Peopels.Name,FactorHDS.FactNo,Peopels.LatPers,Peopels.LonPers,FactorHDS.SerialNoHDS,FactorHDS.FactDate,Sla_Drivers.NameDriver,a.PhoneStr,Peopels.peopeladdress,SnBargiryBYS,TotalPriceHDS from Shop.dbo.BargiryBYS Join Shop.dbo.FactorHDS on BargiryBYS.SnFact=FactorHDS.SerialNoHDS Join Shop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN 
            Join Shop.dbo.BargiryHDS on BargiryHDS.SnMasterBar=BargiryBYS.SnMaster Join Shop.dbo.Sla_Drivers on Sla_Drivers.SnDriver=BargiryHDS.SnDriver
            Join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
                            FROM Shop.dbo.PhoneDetail
                            GROUP BY SnPeopel)a on PSN=a.SnPeopel 
            where FactDate='$secondDate' and Sla_Drivers.SnDriver=".$adminId);
            $customerIds=array();
            foreach ($factors as $factor) {
                array_push($customerIds,$factor->PSN);
            }
            
            return Response::json($factors);

        }
	
// the following function is return the bargeri information 
		public function bargeryInfo(Request $request){
			$admins=DB::select("select * from CRM.dbo.crm_admin join CRM.dbo.crm_adminType on crm_adminType.id=crm_admin.adminType
                                         where deleted=0 and crm_admin.adminType=4");
			return view("driver.bargeryInfo", ['admins'=>$admins]);
}

public function bargeryFactors(Request $request){
      $adminId=$request->get("adminId");
      $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
      $factors=DB::select("select Peopels.PSN,Peopels.Name,FactorHDS.FactNo,Peopels.LatPers,Peopels.LonPers,FactorHDS.SerialNoHDS,FactorHDS.FactDate,Sla_Drivers.NameDriver,a.PhoneStr,Peopels.peopeladdress,SnBargiryBYS,TotalPriceHDS from Shop.dbo.BargiryBYS Join Shop.dbo.FactorHDS on BargiryBYS.SnFact=FactorHDS.SerialNoHDS Join Shop.dbo.Peopels on FactorHDS.CustomerSn=Peopels.PSN 
      Join Shop.dbo.BargiryHDS on BargiryHDS.SnMasterBar=BargiryBYS.SnMaster Join Shop.dbo.Sla_Drivers on Sla_Drivers.SnDriver=BargiryHDS.SnDriver
      Join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr FROM Shop.dbo.PhoneDetail GROUP BY SnPeopel)a on PSN=a.SnPeopel 
       where FactDate='$todayDate' and Sla_Drivers.SnDriver=".$adminId);
    return Response::json($factors);
}
public function driverService(Request $request)
{
    $services=DB::select("SELECT name,lastName,crm_driverservice.discription,ServiceSn,serviceType,TimeStamp FROM CRM.dbo.crm_driverservice JOIN CRM.dbo.crm_admin on crm_driverservice.adminId=crm_admin.driverId where deleted=0 and adminType=4 order by TimeStamp desc");
    return view("driver.driverService",['services'=>$services]);
}
public function addService(Request $request)
{
    $driverId=$request->get("selectDriver");
    $discription=$request->get("discription");
    $servicType=$request->get("selectService");
    DB::table("CRM.dbo.crm_driverservice")->insert(["adminId"=>$driverId,"serviceType"=>$servicType,"discription"=>"".$discription.""]);
    $services=DB::select("SELECT name,lastName,crm_driverservice.discription,ServiceSn,serviceType,TimeStamp FROM CRM.dbo.crm_driverservice JOIN CRM.dbo.crm_admin on crm_driverservice.adminId=crm_admin.driverId where deleted=0 and adminType=4  order by TimeStamp desc");
    return Response::json($services);
}
public function getInfoForDriverService(Request $request)
{
    $drivers=DB::select("SELECT * FROM CRM.dbo.crm_admin where adminType=4 and deleted=0");
    return Response::json($drivers);
}
public function getServiceInfo(Request $request)
{
    $serviceId=$request->get("serviceId");
    $service=DB::table("CRM.dbo.crm_driverservice")->where("ServiceSn",$serviceId)->get();
    $drivers=DB::select("SELECT * FROM CRM.dbo.crm_admin where adminType=4 and deleted=0");
    return Response::json([$service,$drivers]);
}
public function editDriverService(Request $request)
{
    $serviceSn=$request->get("serviceSn");
    $editDriverSn=$request->get("editDriverSn");
    $editDiscription=$request->get("editDiscription");
    $editServiceType=$request->get("editServiceType");
    
    DB::table("CRM.dbo.crm_driverservice")->where("ServiceSn",$serviceSn)->update(["adminId"=>$editDriverSn
    ,"serviceType"=>$editServiceType
    ,"discription"=>"".$editDiscription.""]);
    $services=DB::select("SELECT name,lastName,crm_driverservice.discription,ServiceSn,serviceType,TimeStamp FROM CRM.dbo.crm_driverservice JOIN CRM.dbo.crm_admin on crm_driverservice.adminId=crm_admin.driverId where deleted=0 and adminType=4  order by TimeStamp desc");
    return Response::json($services);
}
}
