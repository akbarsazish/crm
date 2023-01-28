<?php
namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Controllers\Poshtiban;
use DB;
use Response;
use Carbon\Carbon;
use \Morilog\Jalali\Jalalian;
use Session;
class Admin extends Controller
{
    public function index(Request $request){
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('deleted',0)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType","crm_admin.discription")->orderby("admintype")->get();
        $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and SnMNM>82");
        $cities=DB::select("Select * FROM Shop.dbo.MNM WHERE  CompanyNo=5 and RecType=1 AND FatherMNM=79");
        $adminList=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('deleted',0)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType","crm_admin.discription")->orderby("admintype")->get();
        return View('admin.assignCustomer',['admins'=>$admins,'regions'=>$regions,'cities'=>$cities,'adminList'=>$adminList]);
    }
    public function listKarbaran(Request $request)
    {
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('deleted',0)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType","crm_admin.discription")->orderby("admintype")->get();
        $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and SnMNM>82");
        $cities=DB::select("Select * FROM Shop.dbo.MNM WHERE  CompanyNo=5 and RecType=1 AND FatherMNM=79");
        $saleLines=DB::select("SELECT * FROM CRM.dbo.crm_SaleLine where deleted=0");
        foreach ($saleLines as $line) {
            $managers=DB::table("CRM.dbo.crm_admin")->where('saleLineId',$line->SaleLineSn)->where("employeeType",1)->get();
            $line->manager=$managers;
            foreach($line->manager as $manager){
                $heads=DB::table("CRM.dbo.crm_admin")->where('bossId',$manager->id)->get();
                $manager->head=$heads;
                foreach($manager->head as $head){
                    $employee=DB::table("CRM.dbo.crm_admin")->where('bossId',$head->id)->get();
                    $head->employee=$employee;
                }
            }
        }
        $managers=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE employeeType=1 and deleted=0");
        $heads=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE employeeType=2  and deleted=0");

        return View('admin.listKarbaran',['admins'=>$admins,'regions'=>$regions,'cities'=>$cities,'saleLines'=>$saleLines,'managers'=>$managers,'heads'=>$heads]);
    }
    public function karbaranOperations(Request $request)
    {
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('deleted',0)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType","crm_admin.discription")->orderby("admintype")->get();
        $regions=DB::select("SELECT * FROM Shop.dbo.MNM WHERE CompanyNo=5 and SnMNM>82");
        $cities=DB::select("Select * FROM Shop.dbo.MNM WHERE  CompanyNo=5 and RecType=1 AND FatherMNM=79");
        $saleLines=DB::select("SELECT * FROM CRM.dbo.crm_SaleLine where deleted=0");
        foreach ($saleLines as $line) {
            $managers=DB::table("CRM.dbo.crm_admin")->where('saleLineId',$line->SaleLineSn)->where("employeeType",1)->get();
            $line->manager=$managers;
            foreach($line->manager as $manager){
                $heads=DB::table("CRM.dbo.crm_admin")->where('bossId',$manager->id)->get();
                $manager->head=$heads;
                foreach($manager->head as $head){
                    $employee=DB::table("CRM.dbo.crm_admin")->where('bossId',$head->id)->get();
                    $head->employee=$employee;
                }
            }
        }
        $managers=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE employeeType=1 and deleted=0");
        $heads=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE employeeType=2  and deleted=0");

        return View('admin.karbaranOperation',['admins'=>$admins,'regions'=>$regions,'cities'=>$cities,'saleLines'=>$saleLines,'managers'=>$managers,'heads'=>$heads]);
    }

    public function allCustomers(Request $request)
    {
        $customers=DB::select("SELECT TOP 20 * FROM(
                            SELECT * FROM(
                            SELECT * FROM(
                            SELECT * FROM(
                            SELECT PCode,PSN,Name,peopeladdress,SnMantagheh FROM Shop.dbo.Peopels
                            WHERE  PSN IN  (SELECT DISTINCT customer_id FROM CRM.dbo.crm_customer_added))b 
                            JOIN   (SELECT * FROM CRM.dbo.crm_customer_added)c ON b.PSN=c.customer_id)d )e
                            join(SELECT SnMNM,NameRec FROM Shop.dbo.MNM )f ON e.SnMantagheh=f.SnMNM)g");
        
        foreach ($customers as $customer) {

            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";

            foreach ($phones as $phone) {

                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }

            }

            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;

        }
        return View('admin.allCustomerList',['customers'=>$customers]);
    }
	
	public function crmTerminal(Request $request)
    {
        switch (Session::get("adminType")) {
            case 1:
                return redirect('/home');
                break;
            case 2:
                return redirect('/customers');
                break;
            case 3:
                return redirect('/myCustomers');
                break; 
            case 4:
                return redirect('/crmDriver');
                break;   
            case 5:
                return redirect('/home');
                break;             
            default:
                return redirect('/login');
                break;
        }
    }
        

    public function searchAllCustomerByName(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $customers=DB::select("SELECT * FROM (
SELECT * FROM (
                        SELECT * FROM (
                        SELECT * FROM (SELECT PSN,Name,peopeladdress,CompanyNo,GroupCode FROM Shop.dbo.Peopels) a
                        left JOIN   (
                        SELECT COUNT(SerialNoHDS) as countFactor,FactorHDS.CustomerSn FROM Shop.dbo.FactorHDS GROUP BY    FactorHDS.CustomerSn) b ON a.PSN=b.CustomerSn )c
                        join(SELECT MAX(FactorHDS.FactDate)as lastDate,CustomerSn as customerId FROM Shop.dbo.FactorHDS GROUP BY    FactorHDS.CustomerSn
                        )d
                        ON d.customerId=c.PSN )e
						left join(select state,customerId as csn from CRM.dbo.crm_inactiveCustomer)h on e.customerId=h.csn
						left JOIN   (SELECT customer_id,admin_id,name as adminName,lastName,returnState FROM CRM.dbo.crm_customer_added JOIN   CRM.dbo.crm_admin ON CRM.dbo.crm_customer_added.admin_id=crm_admin.id where returnState=0)f ON f.customer_id=e.PSN)g
                        WHERE g.CompanyNo=5  and Name LIKE '%".$searchTerm."%'");
        
        foreach ($customers as $customer) {

            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";
            
            foreach ($phones as $phone) {

                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }

            }

            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;

        }

        return Response::json($customers);
    }
	    public function addProvinceCode(Request $request)
    {
        $provinceCode=$request->get('provinceCode');
        $provinceCodes=DB::table("NewStarfood.dbo.star_provincePhoneCode")->where('provinceCode',$provinceCode)->get();
        if(count($provinceCodes)<1){
            DB::table("NewStarfood.dbo.star_provincePhoneCode")->insert(['provinceCode'=>''.$provinceCode.'']);
        }
        $provinceCodes=DB::table("NewStarfood.dbo.star_provincePhoneCode")->get();
        return Response::json($provinceCodes);
    }
    public function searchAllCustomerByCode(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $customers=DB::select("SELECT * FROM(
                        SELECT * FROM(
                        SELECT * FROM(
                        SELECT * FROM(
                        SELECT PCode,PSN,Name,peopeladdress,SnMantagheh FROM Shop.dbo.Peopels
                        WHERE  PSN IN  (SELECT DISTINCT customer_id FROM CRM.dbo.crm_customer_added))b 
                        JOIN   (SELECT * FROM CRM.dbo.crm_customer_added)c ON b.PSN=c.customer_id)d )e
                        join(SELECT SnMNM,NameRec FROM Shop.dbo.MNM )f ON e.SnMantagheh=f.SnMNM)g WHERE  PCode LIKE '%".$searchTerm."%'");
        foreach ($customers as $customer) {
        $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
        $hamrah="";
        $sabit="";
        foreach ($phones as $phone) {
        if($phone->PhoneType==1){
            $sabit.="\n".$phone->PhoneStr;
        }else{
            $hamrah.="\n".$phone->PhoneStr;
        }
        }
        $customer->sabit=$sabit;
        $customer->hamrah=$hamrah;
        }
        return Response::json($customers);
    }
    public function orderAllCustomerByCName(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm==1){
            $customers=DB::select("SELECT * FROM(
                                SELECT * FROM(
                                SELECT * FROM(
                                SELECT * FROM(
                                SELECT PCode,PSN,Name,peopeladdress,SnMantagheh FROM Shop.dbo.Peopels
                                WHERE  PSN IN  (SELECT DISTINCT customer_id FROM CRM.dbo.crm_customer_added))b 
                                JOIN   (SELECT * FROM CRM.dbo.crm_customer_added)c ON b.PSN=c.customer_id)d )e
                                join(SELECT SnMNM,NameRec FROM Shop.dbo.MNM )f ON e.SnMantagheh=f.SnMNM)g order By Name asc");
        
        }else{
            $customers=DB::select("SELECT * FROM(
                                SELECT * FROM(
                                SELECT * FROM(
                                SELECT * FROM(
                                SELECT PCode,PSN,Name,peopeladdress,SnMantagheh FROM Shop.dbo.Peopels
                                WHERE  PSN IN  (SELECT DISTINCT customer_id FROM CRM.dbo.crm_customer_added))b 
                                JOIN   (SELECT * FROM CRM.dbo.crm_customer_added)c ON b.PSN=c.customer_id)d )e
                                join(SELECT SnMNM,NameRec FROM Shop.dbo.MNM )f ON e.SnMantagheh=f.SnMNM)g order By PCode asc");
        }
        foreach ($customers as $customer) {
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            $hamrah="";
            $sabit="";

            foreach ($phones as $phone) {

                if($phone->PhoneType==1){
                    $sabit.="\n".$phone->PhoneStr;
                }else{
                    $hamrah.="\n".$phone->PhoneStr;
                }

            }

            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;

        }
        return Response::json($customers);
    }
    public function dashboard() {
        $allCustomerCount=DB::select("SELECT COUNT(PSN) as countAllCustomers FROM Shop.dbo.Peopels WHERE  Peopels.CompanyNo=5 and Peopels.GroupCode IN ( ".implode(",",Session::get("groups")).")");
        
        $allCustomers=$allCustomerCount[0]->countAllCustomers;
        
        $allActiveCustomerCount=DB::select("SELECT COUNT(PSN) as countActiveCustomers FROM Shop.dbo.Peopels WHERE  PSN in(SELECT customer_id FROM CRM.dbo.crm_customer_added WHERE  returnState=0) and Peopels.CompanyNo=5 and Peopels.GroupCode IN ( ".implode(",",Session::get("groups")).")");
        
        $allActiveCustomers=$allActiveCustomerCount[0]->countActiveCustomers;
        
        $allInActiveCustomerCount=DB::select("SELECT COUNT(PSN) as countInActiveCustomers FROM Shop.dbo.Peopels WHERE  PSN in(SELECT customerId FROM CRM.dbo.crm_inactiveCustomer WHERE  state=1) AND  Peopels.CompanyNo=5 and Peopels.GroupCode IN ( ".implode(",",Session::get("groups")).")");
        
        $allInActiveCustomers=$allInActiveCustomerCount[0]->countInActiveCustomers;
        
        $allEmptyCustomerCount=DB::select("SELECT COUNT(customer_id) as countCustomer FROM(
                                        SELECT DISTINCT * FROM(
                                        SELECT CRM.dbo.crm_customer_added.customer_id FROM CRM.dbo.crm_customer_added WHERE  gotEmpty=1 and customer_id not IN  (SELECT CRM.dbo.crm_returnCustomer.customerId FROM CRM.dbo.crm_returnCustomer WHERE customerId is not null and  returnState=1)
                                        )d
                                        JOIN   (SELECT * FROM Shop.dbo.Peopels)c
                                        ON c.PSN=d.customer_id
                                        JOIN   (SELECT PhoneStr,SnPeopel FROM Shop.dbo.PhoneDetail)b ON d.customer_id=b.SnPeopel
                                        WHERE  PSN not IN  (SELECT customerId FROM CRM.dbo.crm_inactiveCustomer WHERE customerId is not null and   state=1))e
                                        JOIN   (SELECT customerId,removedDate FROM(
                                        SELECT DISTINCT customer_id as customerId FROM CRM.dbo.crm_customer_added WHERE   gotEmpty=1 and customer_id not in(SELECT customer_id FROM CRM.dbo.crm_customer_added WHERE  returnState=0))a
                                        JOIN   (SELECT MAX(removedTime)as removedDate,customer_id FROM CRM.dbo.crm_customer_added GROUP BY    customer_id)b ON a.customerId=b.customer_id)f ON f.customerId=e.PSN");
       
        $allEmptyCustomers=$allEmptyCustomerCount[0]->countCustomer;
        
        $allGoodsCount=DB::select("SELECT COUNT(GoodSn) countAllGoods FROM Shop.dbo.PubGoods WHERE  PubGoods.GoodGroupSn>49 and PubGoods.CompanyNo=5");
        
        $allGoods=$allGoodsCount[0]->countAllGoods;
        
        $allPrebuyableCount=DB::select("SELECT COUNT(GoodSn) countPrepbuyables FROM Shop.dbo.PubGoods WHERE  GoodSn in(SELECT productId FROM NewStarfood.dbo.star_GoodsSaleRestriction WHERE  activePishKharid=1) and PubGoods.GoodGroupSn>49 and PubGoods.CompanyNo=5");
        
        $allPrebuyableGoods=$allPrebuyableCount[0]->countPrepbuyables;
        
        $allBoughtGoodsCount=DB::select("SELECT COUNT(GoodSn) countBoughtGoods FROM Shop.dbo.PubGoods WHERE  GoodSn in(SELECT DISTINCT SnGood FROM Shop.dbo.FactorBYS JOIN   Shop.dbo.PubGoods ON FactorBYS.SnGood=PubGoods.GoodSn WHERE  PubGoods.GoodGroupSn>49 and PubGoods.CompanyNo=5) and PubGoods.GoodGroupSn>49 and PubGoods.CompanyNo=5 
         ");
        
        $boughtGoods=$allBoughtGoodsCount[0]->countBoughtGoods;
        
        $allGoodsInBrandsCount=DB::select("SELECT COUNT(productId) as countBrandProducts FROM(
                                    SELECT DISTINCT productId FROM NewStarfood.dbo.star_add_prod_brands WHERE  brandId>6)a");
        
        $allBrandGoods=$allGoodsInBrandsCount[0]->countBrandProducts;
        
        $allBrandsCount=DB::select("SELECT COUNT(id) as countBrands FROM NewStarfood.dbo.star_brands");
        
        $allBrands=$allBrandsCount[0]->countBrands;
        
        $allMainGroupCount=DB::select("SELECT COUNT(id) as countMainGroups FROM NewStarfood.dbo.Star_Group_Def WHERE  selfGroupId=0");
        
        $allmainGroup=$allMainGroupCount[0]->countMainGroups;
        
        $allSubGroupCount=DB::select("SELECT COUNT(id) as countSubGroups FROM NewStarfood.dbo.Star_Group_Def WHERE  selfGroupId>0");
        
        $allSubGroups=$allSubGroupCount[0]->countSubGroups;
        
        $allReturnedCustomers=DB::select("SELECT COUNT(id) as countReturnedCustomers FROM CRM.dbo.crm_returnCustomer WHERE  returnState=1");
        
        $allReturnedCustomer=$allReturnedCustomers[0]->countReturnedCustomers;
        
        $admins=DB::select("SELECT * FROM(
                        SELECT crm_admin.id as adminId,crm_admin.name,deleted,crm_admin.lastName,crm_admin.phone,crm_admin.address,crm_adminType.adminType FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id)a
                        left JOIN   (SELECT COUNT(id) as countCustomer,admin_id FROM CRM.dbo.crm_customer_added WHERE  returnState=0 GROUP BY    admin_id)b ON a.adminId=b.admin_id
                        where deleted=0
                        ORDER BY     a.adminType asc");
        
        return view('admin.dashboard',['allCustomers'=>$allCustomers,'allActiveCustomers'=>$allActiveCustomers,'allInActiveCustomers'=>$allInActiveCustomers,
                'allEmptyCustomers'=>$allEmptyCustomers,'allGoods'=>$allGoods,'prebuyableGoods'=>$allPrebuyableGoods,'allboughtGoods'=>$boughtGoods,'allBrandGoods'=>$allBrandGoods
                ,'allBrands'=>$allBrands,'allmainGroup'=>$allmainGroup,'allSubGroups'=>$allSubGroups,'allReturnedCustomer'=>$allReturnedCustomer,'admins'=>$admins]);
    }
    public function AddAdmin(Request $request)
    {
        $name=$request->post("name");
        $userName=$request->post("userName");
        $lastName=$request->post("lastName");
        
        $password=$request->post("password");
        $adminType=$request->post("adminType");
        $phone=$request->post("phone");
        $address=$request->post("address");
        $sex=$request->post("sex");
        $discription=$request->post("discription");
        $hasAsses=$request->post("hasAsses");
        $hasAllCustomer=$request->post("hasAllCustomer");
        $picture=$request->file('picture');
        $poshtibanType=$request->post("poshtibanType");
        $employeeType=$request->post("employeeType");
        $manager=$request->post("manager");
        $head=$request->post("head");
        $saleLine=$request->post("saleLine");
        $bossId=0;
        $saleLineSn=0;

        if($manager){
            $bossId=$manager;  
        }

        if($head){
            $bossId=$head;  
        }
        
        
        if($saleLine){
            $saleLineSn=$saleLine;
        }
        
        if($picture){
            $fileName=$picture->getClientOriginalName();
            $maxId=0;
            $maxId=DB::table("CRM.dbo.crm_admin")->max('id');
            if($maxId>1){
                $maxId=$maxId+1;
            }else{
                $maxId=1;
            }
            $fileName=$maxId.".jpg";
            $picture->move("resources/assets/images/admins/",$fileName);
        }
        DB::table("CRM.dbo.crm_admin")->insert(['username'=>"".$userName."",'name'=>"".$name."",'lastName'=>"".$lastName."",
        'adminType'=>$adminType,'password'=>"".$password."",'activeState'=>1,'phone'=>$phone,'address'=>$address,
        'sex'=>"".$sex."",'discription'=>"".$discription."",'hasAsses'=>$hasAsses,'driverId'=>0,'hasAllCustomer'=>$hasAllCustomer,
        'bossId'=>$bossId,'employeeType'=>$employeeType,'SaleLineId'=>$saleLineSn,'poshtibanType'=>$poshtibanType]);
        return redirect("/listKarbaran");
    }
    public function addAdminFromList(Request $request)
    {
        $name=$request->post("name");
        $userName=$request->post("userName");
        $lastName=$request->post("lastName");
        $password=$request->post("password");
        $adminType=$request->post("adminType");
        $bossId=$request->post("bossId");
        $phone=$request->post("phone");
        $address=$request->post("address");
        $sex=$request->post("sex");
        $discription=$request->post("discription");
        $hasAsses=$request->post("hasAsses");
        $hasAllCustomer=$request->post("hasAllCustomer");
        $picture=$request->file('picture');
        if($picture){
            $fileName=$picture->getClientOriginalName();
            list($a,$b)=explode(".",$fileName);
            $maxId=0;
            $maxId=DB::table("CRM.dbo.crm_admin")->max('id');
            if($maxId>1){
                $maxId=$maxId+1;
            }else{
                $maxId=1;
            }

            $fileName=$maxId.".jpg";
            $picture->move("resources/assets/images/admins/",$fileName);
        }

        DB::table("CRM.dbo.crm_admin")->insert(['username'=>"".$userName."",'name'=>"".$name."",'lastName'=>"".$lastName."",'adminType'=>$adminType,'password'=>"".$password."",'activeState'=>1,'phone'=>$phone,'address'=>$address,'sex'=>"".$sex."",'discription'=>"".$discription."",'hasAsses'=>$hasAsses,'driverId'=>0,'hasAllCustomer'=>$hasAllCustomer,'bossId'=>"".$bossId.""]);
        return redirect("/assignCustomer"); 
    }
    public function AddCustomerToAdmin(Request $request)
    {
        $adminId=$request->get("adminId");
        $customerIDs=$request->get("customerIDs");
        foreach ($customerIDs as $customerId) {
            DB::table("CRM.dbo.crm_customer_added")->insert(['admin_id'=>$adminId,'customer_id'=>$customerId,'returnState'=>0]);
        }
        $customers=DB::select("SELECT * FROM Shop.dbo.Peopels WHERE  Peopels.PSN IN  (SELECT customer_id FROM CRM.dbo.crm_customer_added WHERE  admin_id=".$adminId." and returnState!=1)  AND CompanyNo=5 AND GroupCode IN ( ".implode(",",Session::get("groups")).")");
        DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(["emptyState"=>0]);
       return Response::json($customers);
    }
    public function RemoveCustomerFromAdmin(Request $request)
    {
        $customerIds=$request->get("customerIDs");
        $adminId=$request->get("adminId");
        $countCustomers=DB::table("CRM.dbo.crm_customer_added")->where("admin_id",$adminId)->where("returnState",0)->count();
        $isEqualEmty=0;
        if($countCustomers == count($customerIds)){
            $isEqualEmty=1;  
        }
        if($isEqualEmty==0){
            foreach ($customerIds as $customerId) {
            DB::table("CRM.dbo.crm_customer_added")->where("customer_id",$customerId)->update(['returnState'=>1,'gotEmpty'=>1,'removedTime'=>"".Carbon::now().""]);
            }
        }
        if($isEqualEmty==1){
            return Response::json($isEqualEmty);
        }
        // $customers=DB::select("SELECT * FROM Shop.dbo.Peopels WHERE  Peopels.PSN 
        // not IN  (SELECT DISTINCT customer_id FROM CRM.dbo.crm_customer_added WHERE  customer_id not in(SELECT customerId FROM CRM.dbo.crm_inactiveCustomer WHERE  state=0) and customer_id not in(SELECT customerId
        // FROM CRM.dbo.crm_returnCustomer WHERE  returnState=1) and returnState=0)  AND CompanyNo=5 AND GroupCode IN ( ".implode(",",Session::get("groups")).") And Name!=''");
        $customers=DB::select("SELECT * FROM Shop.dbo.Peopels where Peopels.PSN in (SELECT customer_id FROM CRM.dbo.crm_customer_added where admin_id=".$adminId." and crm_customer_added.returnState=0)  AND CompanyNo=5 AND GroupCode IN ( ".implode(",",Session::get("groups")).")");
        return Response::json($customers);
    }

    public function RemoveCustomerAndEmpty(Request $request)
    {
        $customerIds=$request->get("customerIDs");
        $adminId=$request->get("adminId");
        $countCustomers=DB::table("CRM.dbo.crm_customer_added")->where("admin_id",$adminId)->where("returnState",0)->count();
        
        foreach ($customerIds as $customerId) {
            DB::table("CRM.dbo.crm_customer_added")->where("customer_id",$customerId)->update(['returnState'=>1,'gotEmpty'=>1,'removedTime'=>"".Carbon::now().""]);
        }

        DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(['emptyState'=>1]);

        // $customers=DB::select("SELECT * FROM Shop.dbo.Peopels WHERE  Peopels.PSN 
        // not IN  (SELECT DISTINCT customer_id FROM CRM.dbo.crm_customer_added WHERE  customer_id not in(SELECT customerId FROM CRM.dbo.crm_inactiveCustomer WHERE  state=0) and customer_id not in(SELECT customerId
        // FROM CRM.dbo.crm_returnCustomer WHERE  returnState=1) and returnState=0)  AND CompanyNo=5 AND GroupCode IN ( ".implode(",",Session::get("groups")).") And Name!=''");
        $customers=DB::select("SELECT * FROM Shop.dbo.Peopels where Peopels.PSN in (SELECT customer_id FROM CRM.dbo.crm_customer_added where admin_id=".$adminId." and crm_customer_added.returnState=0)  AND CompanyNo=5 AND GroupCode IN ( ".implode(",",Session::get("groups")).")");
        return Response::json($customers);
    }


    public function myCalendar(){
        $adminId=Session::get('asn');
        $now = Jalalian::fromCarbon(Carbon::now());
        $month= $now->getMonth();
        $year= $now->getYear();
        $workList=DB::select("SELECT count(a.workId) as count,a.specifiedDate FROM (SELECT DISTINCT crm_workList.id as workId, crm_workList.specifiedDate FROM CRM.dbo.crm_workList Join CRM.dbo.crm_comment ON crm_workList.commentId=crm_comment.id
        JOIN   CRM.dbo.crm_customer_added ON crm_comment.customerId=crm_customer_added.customer_id WHERE  crm_customer_added.admin_id=".$adminId." and crm_workList.doneState=0 and crm_customer_added.returnState=0)a GROUP BY    a.specifiedDate");
        return view ("admin.calendar",['commenDates'=>$workList,'month'=>$month,'year'=>$year]);
    }

    public function changeDate(Request $request)
    {
        $month=$request->post("month");
        $year=$request->post("year");
        $adminId=Session::get('asn');
        $workList=DB::select("SELECT count(a.workId) as count,a.specifiedDate FROM (SELECT crm_workList.id as workId, crm_workList.specifiedDate FROM CRM.dbo.crm_workList Join CRM.dbo.crm_comment ON crm_workList.commentId=crm_comment.id
        JOIN   CRM.dbo.crm_customer_added ON crm_comment.customerId=crm_customer_added.customer_id WHERE  crm_customer_added.admin_id=".$adminId." and crm_workList.doneState=0 and crm_customer_added.returnState=0 and crm_comment.customerId not IN  (SELECT customerId FROM CRM.dbo.crm_returnCustomer WHERE  crm_returnCustomer.returnState=1))a GROUP BY    a.specifiedDate");
        return view ("admin.calendar",['commenDates'=>$workList,'month'=>$month,'year'=>$year]);
    }
    public function takhsisCustomer(Request $request)
    {
        $customerId=$request->get("csn");
        $adminId=$request->get("asn");
        // add to customer update two places
        $admin=DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->first();
        if($admin->emptyState==1){
            DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(["emptyState"=>0]);
        }
        DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->where('returnState',0)->update(['removedTime'=>"".Carbon::now()."",'returnState'=>1]);
        DB::table("CRM.dbo.crm_customer_added")->insert(['admin_id'=>$adminId,'customer_id'=>$customerId,'returnState'=>0]);
        DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->update(['gotEmpty'=>0]);
        DB::update("UPDATE CRM.dbo.crm_returnCustomer SET returnState=0 WHERE  customerId=".$customerId." and returnState=1");
        DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->where('emptyState',1)->update(['emptyState'=>0]);
        $customers=DB::table("Shop.dbo.Peopels")->join("CRM.dbo.crm_returnCustomer","Peopels.PSN","=","crm_returnCustomer.customerId")->join("Shop.dbo.PhoneDetail","Peopels.PSN","=","PhoneDetail.SnPeopel")->where("crm_returnCustomer.returnState",1)->select("Peopels.PSN","Peopels.PCode","Peopels.Name","PhoneDetail.PhoneStr","Peopels.peopeladdress")->get();
        return Response::json($customers);

    }
    public function takhsisNewCustomer(Request $request)
    {
        $customerId=$request->get("csn");
        $adminId=$request->get("asn");
        // add to customer update two places
        $admin=DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->first();
        if($admin->emptyState==1){
            DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(["emptyState"=>0]);
        }
        DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->where('returnState',0)->update(['removedTime'=>"".Carbon::now()."",'returnState'=>1]);
        DB::table("CRM.dbo.crm_customer_added")->insert(['admin_id'=>$adminId,'customer_id'=>$customerId,'returnState'=>0]);
        DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->update(['gotEmpty'=>0]);
        DB::update("UPDATE CRM.dbo.crm_returnCustomer SET returnState=0 WHERE  customerId=".$customerId." and returnState=1");
        DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->where('emptyState',1)->update(['emptyState'=>0]);
        $customers=DB::select("SELECT DISTINCT Peopels.PSN,Peopels.PCode,Peopels.Name,Peopels.GroupCode,Peopels.TimeStamp,Peopels.peopeladdress,SnMantagheh,NameRec,crm_admin.name as adminName ,crm_admin.lastName as adminLastName
        FROM Shop.dbo.Peopels
        JOIN Shop.dbo.MNM ON SnMantagheh=MNM.SnMNM
        JOIN CRM.dbo.crm_inserted_customers on Peopels.PSN=crm_inserted_customers.customerId
        join CRM.dbo.crm_admin on crm_admin.id=crm_inserted_customers.adminId
        where GroupCode=314  and PSN NOT IN(SELECT customer_id from CRM.dbo.crm_customer_added)");
        foreach ($customers as $customer) {
            $sabit="";
            $hamrah="";
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                $sabit.=$phone->PhoneStr."\n";
                }else{
                    $hamrah.=$phone->PhoneStr."\n";   
                }
            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }
        return Response::json($customers);

    }
    public function takhsisCustomerFromEmpty(Request $request)
    {
        $customerId=$request->get("csn");
        $adminId=$request->get("asn");
        // add to customer update two places
        $admin=DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->first();
        if($admin->emptyState==1){
            DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(["emptyState"=>0]);
        }
        DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->where('returnState',0)->update(['removedTime'=>"".Carbon::now().""]);
        DB::table("CRM.dbo.crm_customer_added")->insert(['admin_id'=>$adminId,'customer_id'=>$customerId,'returnState'=>0]);
        DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->update(['gotEmpty'=>0]);
        DB::update("UPDATE CRM.dbo.crm_returnCustomer SET returnState=0 WHERE  customerId=".$customerId." and returnState=1");
        DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->where('emptyState',1)->update(['emptyState'=>0]);
        $customers=DB::select("SELECT DISTINCT * FROM(
                        SELECT CRM.dbo.crm_customer_added.customer_id FROM CRM.dbo.crm_customer_added WHERE  gotEmpty=1 and customer_id not IN  (SELECT CRM.dbo.crm_returnCustomer.customerId FROM CRM.dbo.crm_returnCustomer WHERE  returnState=1)
                        )d
                        JOIN(SELECT * FROM Shop.dbo.Peopels)c
                        ON c.PSN=d.customer_id
                        JOIN (SELECT * FROM Shop.dbo.PhoneDetail)b ON d.customer_id=b.SnPeopel");
        return Response::json($customers);
    }

    public function activateCustomer(Request $request)
    {
        $customerId=$request->get("csn");
        $adminId=$request->get("asn");
        // add to customer update two places
        // $result1=DB::table("CRM.dbo.crm_customer_added")->where('customer_id',$customerId)->where("returnState",1)->update(['admin_id'=>$adminId,'customer_id'=>$customerId,'returnState'=>0]);
        DB::table("CRM.dbo.crm_customer_added")->insert(['admin_id'=>$adminId,'customer_id'=>$customerId,'returnState'=>0]);
        DB::update("UPDATE CRM.dbo.crm_inactiveCustomer SET state=0 WHERE  customerId=".$customerId." and state=1");

        $customers=DB::select("SELECT * FROM (
                        SELECT * FROM (
                        SELECT * FROM CRM.dbo.crm_inactiveCustomer
                        join(SELECT name,lastName,id as admin_id FROM CRM.dbo.crm_admin)a ON a.admin_id=adminId)b
                        JOIN   (SELECT Name as CustomerName,PSN,PCode FROM Shop.dbo.Peopels)c ON c.PSN=b.customerId)d
                        JOIN   (SELECT SnPeopel,PhoneStr FROM Shop.dbo.PhoneDetail)e ON e.SnPeopel=d.PSN
                        WHERE  state=1");
        return Response::json($customers);
    }

    public function report(){
		$amdins=DB::select("Select * FROM CRM.dbo.crm_admin WHERE  adminType=2 and deleted=0");
        $customers=DB::select("select * from(
            SELECT * FROM (
                SELECT * FROM (
                SELECT * FROM (SELECT PSN,Name,peopeladdress,CompanyNo,GroupCode,IsActive FROM Shop.dbo.Peopels) a
                left JOIN   (
                SELECT COUNT(SerialNoHDS) as countFactor,FactorHDS.CustomerSn FROM Shop.dbo.FactorHDS where FactType=3 GROUP BY    FactorHDS.CustomerSn) b ON a.PSN=b.CustomerSn )c
                left join(SELECT MAX(FactorHDS.FactDate)as lastDate,CustomerSn as customerId FROM Shop.dbo.FactorHDS GROUP BY    FactorHDS.CustomerSn
                )d
                ON d.customerId=c.PSN )e
                left JOIN   (SELECT customer_id,admin_id,name as adminName,lastName,returnState FROM CRM.dbo.crm_customer_added JOIN   CRM.dbo.crm_admin ON CRM.dbo.crm_customer_added.admin_id=crm_admin.id where returnState=0)f ON f.customer_id=e.PSN

                )g

                left join(select state,customerId as csn from CRM.dbo.crm_inactiveCustomer)h on g.customerId=h.csn
                WHERE  g.GroupCode IN (291,297,299,312,313,314) and g.CompanyNo=5 ORDER BY countFactor desc
                ");
        
        foreach ($customers as $customer) {
            $sabit="";
            $hamrah="";
            $phones=DB::table("Shop.dbo.PhoneDetail")->where("SnPeopel",$customer->PSN)->get();
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                $sabit.=$phone->PhoneStr."\n";
                }else{
                    $hamrah.=$phone->PhoneStr."\n";   
                }
            }
            $customer->sabit=$sabit;
            $customer->hamrah=$hamrah;
        }
        $cities=DB::table("Shop.dbo.MNM")->where("Rectype",1)->where("FatherMNM",79)->get();


        $visitors=DB::select("SELECT * FROM (
            SELECT CONVERT(date,lastVisit) as lastV,lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
            JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
            ON a.customerId=b.PSN)c
            JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
            JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
            JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
            LEFT JOIN (SELECT count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN)j
            WHERE lastV=Convert(date,getDate())
            order by lastVisit desc");

                $admins=DB::table("CRM.dbo.crm_admin")
                ->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')
                ->where('crm_admin.adminType',2)->where('deleted',0)
                ->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType")
                ->get();

                $inActiveCustomers=DB::select("SELECT * FROM (
                    SELECT * FROM (
                    SELECT * FROM (
                    SELECT * FROM CRM.dbo.crm_inactiveCustomer
                    JOIN(SELECT name,lastName,id as admin_id FROM CRM.dbo.crm_admin)a ON a.admin_id=adminId)b
                    JOIN (SELECT Name as CustomerName,PSN,PCode,SnMantagheh FROM Shop.dbo.Peopels)c ON c.PSN=b.customerId)d
                    JOIN (SELECT SnMNM,NameRec FROM Shop.dbo.MNM)e ON d.SnMantagheh=e.SnMNM)f
                    WHERE  state=1");
                foreach ($inActiveCustomers as $customer) {
                $sabit="";
                $hamrah="";
                $phones=DB::select("SELECT * FROM Shop.dbo.PhoneDetail WHERE  SnPeopel=".$customer->PSN);
                foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                    $sabit.=$phone->PhoneStr."\n";
                }else{
                    $hamrah.=$phone->PhoneStr."\n";    
                }
                }
                $customer->hamrah=$hamrah;
                }


            // evacuated customer query

            $evacuatedCustomers=DB::select("SELECT * FROM Shop.dbo.Peopels
               JOIN (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
                FROM Shop.dbo.PhoneDetail
                GROUP BY SnPeopel)a on PSN=a.SnPeopel
                where PSN not in ( SELECT distinct customer_id FROM CRM.dbo.crm_customer_added where returnState=0 and customer_id is not null)
                and PSN not in (SELECT customerId FROM CRM.dbo.crm_inactiveCustomer where customerId is not null and state=1)
                and PSN not in(SELECT customerId FROM CRM.dbo.crm_returnCustomer where customerId is not null and returnState=1)
                AND CompanyNo=5 AND IsActive=1
                AND GroupCode IN(291,297,299,312,313,314)");

                $evacuatedAdmins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('crm_admin.adminType','!=',1)->where('crm_admin.adminType','!=',4)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType")->get();       




            // referencial customer query 
            $referencialAdmins=DB::table("CRM.dbo.crm_admin")
            ->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')
                ->where('crm_admin.adminType',2)->where('deleted',0)
                ->select("crm_admin.id","crm_admin.name","crm_admin.lastName",
                "crm_admin.adminType as adminTypeId","crm_adminType.adminType")->get();

                $referencialCustomers=DB::select("select * from Shop.dbo.Peopels join (select distinct name as adminName,lastName as adminLastName,crm_admin.id as adminId,customerId,returnDate,returnState from CRM.dbo.crm_returnCustomer join CRM.dbo.crm_admin on crm_returnCustomer.adminId=crm_admin.id)a on PSN=a.customerId 
                where returnState=1 order by returnDate desc");

                foreach ($referencialCustomers as $customer) {
                    $phones=DB::select("SELECT * FROM Shop.dbo.PhoneDetail WHERE  SnPeopel=".$customer->PSN);
                    $hamrah="";
                    $sabit="";
                    foreach ($phones as $phone) {
                        if($phone->PhoneType==2){
                            $hamrah.=$phone->PhoneStr."\n";
                        }else{
                            $sabit.=$phone->PhoneStr."\n";
                        }
                    }
                    $customer->hamrah=$hamrah;
                }
                $returnerAdmins=DB::select("SELECT * FROM CRM.dbo.crm_admin 
                                JOIN(SELECT DISTINCT CRM.dbo.crm_returnCustomer.adminId
                            FROM CRM.dbo.crm_returnCustomer WHERE returnState=1)b ON CRM.dbo.crm_admin.id=b.adminId");


        return view ("reports.listReport",['customers'=>$customers,'cities'=>$cities, 'amdins'=>$amdins, 
                     "admin.visitorReport",'visitors'=>$visitors, 'inActiveCustomers'=>$inActiveCustomers,'admins'=>$admins,
                     'evacuatedCustomers'=>$evacuatedCustomers,'evacuatedAdmins'=>$evacuatedAdmins,
                     'referencialCustomers'=>$referencialCustomers, 'referencialAdmins'=>$referencialAdmins,'returners'=>$returnerAdmins
                    ]);
    }





















    public function referedCustomer(){
        $admins=DB::table("CRM.dbo.crm_admin")
            ->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')
                ->where('crm_admin.adminType',2)->where('deleted',0)
                ->select("crm_admin.id","crm_admin.name","crm_admin.lastName",
                "crm_admin.adminType as adminTypeId","crm_adminType.adminType")->get();

        $customers=DB::select("select * from Shop.dbo.Peopels join (select distinct name as adminName,lastName as adminLastName,crm_admin.id as adminId,customerId,returnDate,returnState from CRM.dbo.crm_returnCustomer join CRM.dbo.crm_admin on crm_returnCustomer.adminId=crm_admin.id)a on PSN=a.customerId 
        where returnState=1 order by returnDate desc");

        foreach ($customers as $customer) {
            $phones=DB::select("SELECT * FROM Shop.dbo.PhoneDetail WHERE  SnPeopel=".$customer->PSN);
            $hamrah="";
            $sabit="";
            foreach ($phones as $phone) {
                if($phone->PhoneType==2){
                    $hamrah.=$phone->PhoneStr."\n";
                }else{
                    $sabit.=$phone->PhoneStr."\n";
                }
            }
            $customer->hamrah=$hamrah;
        }
        $returnerAdmins=DB::select("SELECT * FROM CRM.dbo.crm_admin 
                        JOIN(SELECT DISTINCT CRM.dbo.crm_returnCustomer.adminId
                        FROM CRM.dbo.crm_returnCustomer WHERE returnState=1)b ON CRM.dbo.crm_admin.id=b.adminId");

        return view ("admin.referedCustomer",['customers'=>$customers, 'admins'=>$admins,'returners'=>$returnerAdmins]);
    }



    public function searchReferedCustomerName(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        
        // $customers=DB::select("SELECT PSN,PCode,Name,peopeladdress,adminId FROM(
        //                 SELECT * FROM(SELECT * FROM Shop.dbo.Peopels)a
        //                 JOIN (SELECT * FROM CRM.dbo.crm_returnCustomer)b ON a.PSN=b.customerId)c
        //                 WHERE c.returnState=1 AND Name LIKE '%$searchTerm%'");

        $customers=DB::table("Shop.dbo.Peopels")
                    ->join("CRM.dbo.crm_returnCustomer","Peopels.PSN","=","crm_returnCustomer.customerId")
                    ->join("CRM.dbo.crm_admin","crm_returnCustomer.adminId","=","crm_admin.id")
                    ->where("crm_returnCustomer.returnState",1)
                    ->where("Peopels.Name","LIKE",'%'.$searchTerm.'%')
                    ->select("Peopels.PSN","Peopels.PCode","Peopels.Name","crm_returnCustomer.returnDate",
                    "crm_admin.name as adminName","crm_admin.lastName as adminLastName","Peopels.peopeladdress","crm_returnCustomer.adminId")
                    ->get();

        foreach ($customers as $customer) {
            $phones=DB::select("SELECT * FROM Shop.dbo.PhoneDetail WHERE  SnPeopel=".$customer->PSN);
            $hamrah="";
            $sabit="";
            foreach ($phones as $phone) {
                if($phone->PhoneType==2){
                    $hamrah.=$phone->PhoneStr."\n";
                }else{
                    $sabit.=$phone->PhoneStr."\n";
                }
            }
            $customer->hamrah=$hamrah;
        }                
        return Response::json($customers);
    }

    public function login(){
       return view ("admin.login");

    }


    public function alarm(){
        $maxFacors=DB::select("SELECT max(SerialNoHDS) AS MaxFactorId,CustomerSn FROM (
                               SELECT * FROM Shop.dbo.FactorHDS WHERE CustomerSn in(SELECT customer_id FROM CRM.dbo.crm_customer_added)
                               )a GROUP BY CustomerSn");

        $inAlarmFactors=DB::select("SELECT factorId,CustomerSn FROM CRM.dbo.crm_alarm 
                                    JOIN Shop.dbo.FactorHDS ON crm_alarm.factorId=FactorHDS.SerialNoHDS");

        foreach ($maxFacors as $factor) {
            foreach ($inAlarmFactors as $alarm) {
                if($factor->CustomerSn==$alarm->CustomerSn and ($factor->MaxFactorId>$alarm->factorId)){
                    DB::table("CRM.dbo.crm_alarm")->where("factorId",$alarm->factorId)->update(["state"=>1]);
                }
            }
        }
        $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
        $customers=DB::select("SELECT * FROM (
    SELECT * FROM (
		SELECT * FROM (
			SELECT * FROM (
				SELECT * FROM (
					SELECT DISTINCT * FROM (
						SELECT alarmDate, TimeStamp,factorId,state,adminId,comment,id from CRM.dbo.crm_alarm)a
				JOIN (SELECT factorId AS factorNumber FROM CRM.dbo.crm_assesment)b ON a.factorId=b.factorNumber)c
			JOIN (SELECT id AS admin_Id,name AS AdminName,lastName FROM CRM.dbo.crm_admin)d ON c.adminId=d.Admin_Id)e
		JOIN (SELECT SerialNoHDS,CustomerSn,NetPriceHDS FROM Shop.dbo.FactorHds )f ON f.SerialNoHDS=e.factorId)g
	JOIN (SELECT PSN,Name,CompanyNo,peopeladdress,GroupCode,SnMantagheh FROM Shop.dbo.Peopels)j ON j.PSN=g.CustomerSn)k
JOIN (SELECT SnMNM,NameRec FROM Shop.dbo.MNM WHERE  CompanyNo=5)l ON k.SnMantagheh=l.SnMNM)m

JOIN (select name poshtibanName,lastName as poshtibanLastName,customer_id from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)n on m.CustomerSn=n.customer_id
join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
			FROM Shop.dbo.PhoneDetail
			GROUP BY SnPeopel)a on n.customer_id=a.SnPeopel
WHERE  GroupCode IN ( ".implode(",",Session::get("groups")).") and CompanyNo=5  and alarmDate<'".$todayDate."' and state=0
and PSN in(SELECT customer_id FROM CRM.dbo.crm_customer_added where returnState=0)" );
        foreach ($customers as $customer) {
            $customer->assignedDays=\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d',trim($customer->alarmDate))->diffInDays($customer->TimeStamp);
			
            $customer->PassedDays=\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d',trim($customer->alarmDate))->diffInDays(Carbon::now());
        
        }
        return view ("admin.alarm",['customers'=>$customers]);
    }
    public function customerDashboardForAdmin(Request $request)
    {
        $psn=$request->get("csn");
        $customers=DB::select("SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM
            (SELECT Peopels.CompanyNo,Peopels.Name,Peopels.PSN,Peopels.GroupCode,Peopels.peopeladdress,Peopels.PCode,SnMantagheh FROM Shop.dbo.Peopels)a
            LEFT JOIN (SELECT COUNT(Shop.dbo.FactorHDS.SerialNoHDS)AS countFactor,CustomerSn 
            FROM Shop.dbo.FactorHDS WHERE FactType=3 GROUP BY CustomerSn)b ON b.CustomerSn=a.PSN)c
            JOIN (SELECT customer_id,crm_admin.id,name AS adminName,lastName,returnState
            FROM CRM.dbo.crm_customer_added JOIN CRM.dbo.crm_admin ON crm_customer_added.admin_id=crm_admin.id)d ON d.customer_id=c.PSN)e
			right join(select * from NewStarfood.dbo.star_CustomerPass)g on g.customerId=PSN
			join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
			FROM Shop.dbo.PhoneDetail
			GROUP BY SnPeopel)a on g.customerId=a.SnPeopel
                            WHERE e.CompanyNo=5 AND e.GroupCode IN ( ".implode(",",Session::get("groups")).") AND e.PSN=".$psn);
        $exactCustomer=0;

        foreach ($customers as $customer) {
            $sabit="";
            $hamrah="";
            $phones=DB::select("SELECT * FROM Shop.dbo.PhoneDetail WHERE  SnPeopel=".$customer->PSN);
            foreach ($phones as $phone) {
                if($phone->PhoneType==1){
                    $sabit.=$phone->PhoneStr."\n";
                }else{
                    $hamrah.=$phone->PhoneStr."\n";    
                }
            }
            $customer->hamrah=$hamrah;
            $customer->sabit=$sabit;
        }
        $exactCustomer=$customers[0];
        $factors=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE  FactType=3 and CustomerSn=".$psn." ORDER BY FactDate desc");
        $returnedFactors=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE  FactType=4 and CustomerSn=".$psn." ORDER BY FactDate desc");

        $GoodsDetail=DB::select("SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood FROM(
                                SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood FROM Shop.dbo.FactorHDS
                                JOIN Shop.dbo.FactorBYS ON FactorHDS.SerialNoHDS=FactorBYS.SnFact
                                WHERE  FactorHDS.CustomerSn=".$psn.")g GROUP BY    SnGood)c
                                JOIN (SELECT * FROM Shop.dbo.PubGoods)d ON d.GoodSn=c.SnGood order by maxTime desc");

        $basketOrders=DB::select("SELECT orderStar.TimeStamp,PubGoods.GoodName,orderStar.Amount,orderStar.Fi 
                                FROM newStarfood.dbo.FactorStar 
                                JOIN newStarfood.dbo.orderStar ON FactorStar.SnOrder=orderStar.SnHDS
                                JOIN Shop.dbo.PubGoods ON orderStar.SnGood=PubGoods.GoodSn  
                                WHERE  orderStatus=0 and CustomerSn=".$psn);

        $comments=DB::select("SELECT  crm_comment.id,newComment,nexComment,TimeStamp,customerId,adminId,specifiedDate FROM CRM.dbo.crm_comment 
                                JOIN CRM.dbo.crm_workList ON crm_comment.id=crm_workList.commentId WHERE customerId=".$psn."  order by TimeStamp desc");

        $specialComment=DB::table("CRM.dbo.crm_customerProperties")->where("customerId",$psn)->select("comment")->get();
        
        $assesments=DB::select("SELECT crm_assesment.comment,crm_assesment.factorId,crm_assesment.TimeStamp,crm_assesment.shipmentProblem,crm_assesment.driverBehavior FROM CRM.dbo.crm_assesment
                                JOIN   Shop.dbo.FactorHDS ON crm_assesment.factorId=FactorHDS.SerialNoHDS JOIN   Shop.dbo.Peopels ON Peopels.PSN=FactorHDS.CustomerSn WHERE  PSN=".$psn." order by TimeStamp desc");
        
        return Response::json([$exactCustomer,$factors,$GoodsDetail,$basketOrders,$comments,$specialComment,$assesments,$returnedFactors]);
    }

    // ======================
    public function customerDashboardForAlarm(Request $request)
    {
        $psn=$request->get("csn");
        $asn=$request->get("asn");
        $customer=DB::select("SELECT * FROM(
                        SELECT * FROM (
                        SELECT COUNT(Shop.dbo.FactorHDS.SerialNoHDS)as countFactor,CustomerSn FROM Shop.dbo.FactorHDS GROUP BY    CustomerSn
                        )a
                        JOIN   (SELECT customer_id,returnState,admin_id FROM CRM.dbo.crm_customer_added)b ON a.CustomerSn=b.customer_id
                        )c
                        JOIN   (SELECT Name,PSN,PCode,CompanyNo FROM Shop.dbo.Peopels)d ON c.customer_id=d.PSN
                        WHERE   PSN=".$psn);
        $exactCustomer=0;
        foreach ($customer as $cust) {
            $exactCustomer=$cust;
        }
        $factors=DB::select("SELECT * FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$psn." ORDER BY     FactDate desc");
        
        $GoodsDetail=DB::select("SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood FROM(
                            SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood FROM Shop.dbo.FactorHDS
                            JOIN Shop.dbo.FactorBYS ON FactorHDS.SerialNoHDS=FactorBYS.SnFact
                            WHERE FactorHDS.CustomerSn=".$psn.")g GROUP BY    SnGood)c
                            JOIN (SELECT * FROM Shop.dbo.PubGoods)d ON d.GoodSn=c.SnGood");

        $basketOrders=DB::select("SELECT orderStar.TimeStamp,PubGoods.GoodName,orderStar.Amount,orderStar.Fi FROM newStarfood.dbo.FactorStar 
                            JOIN newStarfood.dbo.orderStar ON FactorStar.SnOrder=orderStar.SnHDS
                            JOIN Shop.dbo.PubGoods ON orderStar.SnGood=PubGoods.GoodSn  WHERE  orderStatus=0 and CustomerSn=".$psn);
        
        $comments=DB::select("SELECT  crm_comment.newComment,crm_comment.nexComment,crm_comment.TimeStamp,customerId,adminId,specifiedDate,doneState,crm_comment.id 
                            FROM CRM.dbo.crm_comment JOIN CRM.dbo.crm_workList ON crm_comment.id=crm_workList.id 
                            WHERE customerId=".$psn);
        
        $specialComment=DB::table("CRM.dbo.crm_customerProperties")->where("customerId",$psn)->select("comment")->get();
        
        $assesments=DB::select("SELECT crm_assesment.comment,crm_assesment.factorId,crm_assesment.TimeStamp,crm_assesment.shipmentProblem,crm_assesment.driverBehavior FROM CRM.dbo.crm_assesment
                                JOIN Shop.dbo.FactorHDS ON crm_assesment.factorId=FactorHDS.SerialNoHDS JOIN   Shop.dbo.Peopels ON Peopels.PSN=FactorHDS.CustomerSn WHERE  PSN=".$psn);
        
        return Response::json([$exactCustomer,$factors,$GoodsDetail,$basketOrders,$comments,$specialComment,$assesments]);
    }
        
    // ======================

    public function getAssesComment(Request $request)
    {
        $id=$request->get("assesId");
        $comment=DB::table("CRM.dbo.crm_assesment")->where("id",$id)->first();
        return Response::json($comment);
    }
    
    // ======================

    public function message(){
         $admins=DB::select("SELECT crm_admin.id,crm_admin.name,crm_admin.lastName,crm_admin.adminType as adminTypeId,crm_adminType.adminType FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_adminType.id=crm_admin.adminType WHERE deleted=0 and  crm_admin.id!=".Session::get('asn'));
         
         $messages=DB::select("SELECT * FROM (
                        SELECT * FROM(
                        SELECT MAX(id) maxID,senderId FROM CRM.dbo.crm_message WHERE  getterId=".Session::get('asn')." GROUP BY    crm_message.senderId)a
                        JOIN   CRM.dbo.crm_admin ON a.senderId=crm_admin.id )b
                        JOIN   CRM.dbo.crm_message ON b.maxID=crm_message.id WHERE  crm_message.senderId!=".Session::get('asn')."  and crm_message.getterId=".Session::get('asn'));
        return view("admin.message",['admins'=>$admins,'messages'=>$messages]);
    }
        
    // ======================

    // public function inactivCustomer(){
    //     $admins=DB::table("CRM.dbo.crm_admin")
    //                 ->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')
    //                 ->where('crm_admin.adminType',2)->where('deleted',0)
    //                 ->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType")
    //                 ->get();

    //     $customers=DB::select("SELECT * FROM (
    //                     SELECT * FROM (
    //                     SELECT * FROM (
    //                     SELECT * FROM CRM.dbo.crm_inactiveCustomer
    //                     JOIN(SELECT name,lastName,id as admin_id FROM CRM.dbo.crm_admin)a ON a.admin_id=adminId)b
    //                     JOIN (SELECT Name as CustomerName,PSN,PCode,SnMantagheh FROM Shop.dbo.Peopels)c ON c.PSN=b.customerId)d
    //                     JOIN (SELECT SnMNM,NameRec FROM Shop.dbo.MNM)e ON d.SnMantagheh=e.SnMNM)f
    //                     WHERE  state=1");
    //         foreach ($customers as $customer) {
    //             $sabit="";
    //             $hamrah="";
    //             $phones=DB::select("SELECT * FROM Shop.dbo.PhoneDetail WHERE  SnPeopel=".$customer->PSN);
    //             foreach ($phones as $phone) {
    //                 if($phone->PhoneType==1){
    //                     $sabit.=$phone->PhoneStr."\n";
    //                 }else{
    //                     $hamrah.=$phone->PhoneStr."\n";    
    //                 }
    //             }
    //             $customer->hamrah=$hamrah;
    //         }
    //     return view ("customer.inactiveCustomer",['customers'=>$customers,'admins'=>$admins]);
    // }
        
    // ======================

    public function loginUser(Request $request)
    {
        
        $userName=$request->post("userName");
        $password=$request->post("password");
        $result=DB::table("CRM.dbo.crm_admin")->where("username",$userName)->where("password",$password)->count();
        if($result>0){
            $admin=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE username='".$userName."' and password='".$password."'");
            $exactAdmin;
			
            foreach ($admin as $ad) {
                $exactAdmin=$ad;
            }
             $isLogedIn=DB::table("CRM.dbo.crm_loginTrack")->where('adminId',$exactAdmin->id)->where('loginDate',Carbon::now()->format('Y-m-d'))->count();
            if($isLogedIn>0){
                DB::table('CRM.dbo.crm_loginTrack')->where('adminId',$exactAdmin->id)->update(['loginTime'=>Carbon::now()]);
            }else{
                DB::table('CRM.dbo.crm_loginTrack')->insert(['adminId'=>$exactAdmin->id,'loginDate'=>Carbon::now()->format('Y-m-d'),'loginTime'=>Carbon::now()]);
            }
			
            Session::put("username",$exactAdmin->name.' '.$exactAdmin->lastName);
            Session::put("asn",$exactAdmin->id);
            Session::put("dsn",$exactAdmin->driverId);
            Session::put("adminType",$exactAdmin->adminType);
            Session::put("activeState",$exactAdmin->activeState);
            Session::put("hasAsses",$exactAdmin->hasAsses);
           // return $exactAdmin->hasAlarm;
            Session::put("hasAlarm",$exactAdmin->hasAlarm);
            Session::put("hasAllCustomer",$exactAdmin->hasAllCustomer);
            Session::put('groups',[291,297,299,312,313,314]);
            switch (Session::get("adminType")) {
                case 1:
                    return redirect('/home');
                    break;
                case 2:
                    return redirect('/customers');
                    break;
                case 3:
                    return redirect('/myCustomers');
                    break; 
                case 4:
                    return redirect('/crmDriver');
                    break;   
                case 5:
                    return redirect('/home');
                    break;             
                default:
                    return redirect('/notfound');
                    break;
            }
            
            
        }else{
            return view('admin.login',['loginError'=>"نام کاربری و یا رمز ورود اشتباه است"]);
        }
    }
        
    // ======================

    public function logoutUser(Request $request)
    {
        Session::forget("username");
        Session::forget("asn");
        Session::forget("adminType");
        Session::forget("hasAsses");
        return view('admin.login');
    }
        
    // ======================

    public function kalaAction(){
                            
        $products=DB::select("SELECT TOP 20  PubGoods.GoodName,PubGoods.GoodCde,PubGoods.GoodSn,star_GoodsSaleRestriction.hideKala,ViewGoodExists.Amount,a.maxFactDate FROM
                        Shop.dbo.PubGoods 
                        JOIN NewStarfood.dbo.star_GoodsSaleRestriction ON PubGoods.GoodSn=star_GoodsSaleRestriction.productId
                        JOIN Shop.dbo.ViewGoodExists ON PubGoods.GoodSn=ViewGoodExists.SnGood
                        JOIN(
                        Select MAX(Shop.dbo.FactorHDS.FactDate) as maxFactDate,FactorBYS.SnGood
                        FROM Shop.dbo.FactorHDS JOIN Shop.dbo.FactorBYS ON FactorBYS.SnFact=FactorHDS.SerialNoHDS
                        GROUP BY    FactorBYS.SnGood)a
                        ON a.SnGood=PubGoods.GoodSn
                        WHERE  ViewGoodExists.CompanyNo=5 and ViewGoodExists.FiscalYear=1399 and PubGoods.GoodGroupSn>49");
        
        $stocks=DB::select("SELECT * FROM Shop.dbo.Stocks WHERE  CompanyNo=5");
        $mainGroups=DB::select("SELECT * FROM NewStarfood.dbo.Star_Group_Def WHERE  selfGroupId=0");
        return view ("admin.kalaAction",['products'=>$products,'stocks'=>$stocks,'mainGroups'=>$mainGroups]);
    }

    
    // ======================

    public function userProfile(){
        $adminId=Session::get("asn");
        $admin=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE  id=".$adminId);
        $exactAdmin;
        foreach ($admin as $admin) {
            $exactAdmin=$admin;
        }
        return view ("admin.userProfile",['admin'=>$exactAdmin]);
    }
    
    // ======================

    public function editProfile(){
        return view ("admin.editProfile");
    }
        
    // ======================

    public function editOwnAdmin(Request $request)
    {
        $adminId=Session::get("asn");
        $userName=$request->post("userName");
        $picture=$request->file("picture");
        $phone=$request->post("phone");
        $address=$request->post("address");
        $password=$request->post("password");
        $fileName=$picture->getClientOriginalName();
        $fileName=$adminId.".jpg";
        $picture->move("resources/assets/images/admins/",$fileName);
        $result=DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(['username'=>"".$userName."",'password'=>"".$password."",'address'=>"".$address."",'phone'=>"".$phone.""]);
        return redirect("/userProfile");
    }
    
    // ======================

    public function crmSetting(){
        return view ("admin.crmSetting");
    }
        
    // ======================

    public function karbarAction(Request $request)
    {
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where("crm_admin.adminType",2)->orwhere("crm_admin.adminType",3)->where('deleted',0)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType")->get();
        $adminTypes=DB::select("SELECT * FROM CRM.dbo.crm_adminType WHERE  id=2 or id=3");
        return view("admin.karbarAction",['admins'=>$admins,'adminTypes'=>$adminTypes]);
    }
        
    // ======================

    public function adminDashboard(Request $request)
    {
        $adminId=$request->get("asn");
        $admin=DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->first();
        if($admin->emptyState==0){
            
            $admins=DB::select("SELECT id, minDate,countPeopel,adminId,discription,name,lastName FROM(
                            SELECT MIN(addedTime) as minDate,COUNT(customer_id) as countPeopel,adminId 
                            FROM(SELECT crm_admin.id as adminId,crm_customer_added.addedTime,crm_customer_added.customer_id 
                            FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_customer_added ON crm_admin.id=crm_customer_added.admin_id 
                            WHERE  crm_customer_added.returnState=0)d  GROUP BY    adminId)d
                            LEFT JOIN   (SELECT * FROM CRM.dbo.crm_admin)a ON a.id=d.adminId WHERE  adminId=".$adminId);
            
            $info=DB::select("SELECT COUNT(customer_id)as countPeopels,crm_customer_added.admin_id 
                            FROM CRM.dbo.crm_customer_added where returnState=0 and crm_customer_added.admin_id=$adminId GROUP BY admin_id");
            
            $customers=DB::select("SELECT customer_id,returnState,addedTime,removedTime 
                            FROM CRM.dbo.crm_customer_added WHERE  returnState=0 and crm_customer_added.admin_id=".$adminId);
            
            $countAllFactor=0;
            $sumAllFactor=0;
            $countAllReturnedFactor=0;
            $sumAllReturnedFactor=0;
            $seenCustomers=array();
            foreach ($customers as $customer) {
                $removedDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
                $addedDate=Jalalian::fromCarbon(Carbon::parse($customer->addedTime))->format('Y/m/d');
                $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=3 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."'  GROUP BY    FactorHDS.SerialNoHDS");
                $returendFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=4 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."'  GROUP BY    FactorHDS.SerialNoHDS");
                foreach ($factors as $factor) {
                    if($factor->countFactors>0 and !in_array($customer->customer_id, $seenCustomers)){
                    array_push($seenCustomers,$customer->customer_id);
                    }
                    $countAllFactor+=$factor->countFactors;
                    $sumAllFactor+=$factor->sumFactors;
                }
                foreach ($returendFactors as $factor) {
                    $countAllReturnedFactor+=$factor->countFactors;
                    $sumAllReturnedFactor+=$factor->sumFactors;
                }
            }
            $boughtPeopelsCount=count($seenCustomers);
            $lastFactorAllMoney=DB::select("SELECT factorAllMoney,lastMonthReturnedAllMoney FROM CRM.dbo.crm_adminHistory WHERE   timeStamp=(
                SELECT MAX(timeStamp)from CRM.dbo.crm_adminHistory WHERE  adminId=".$adminId." GROUP BY    crm_adminHistory.adminId)");
            $lastFactorAllMoney1=0;
            $lastMonthReturnAllMoney1=0;
            foreach ($lastFactorAllMoney as $last) {
                $lastFactorAllMoney1=$last->factorAllMoney;
                $lastMonthReturnAllMoney1=$last->lastMonthReturnedAllMoney;
            }

            foreach ($info as $infor) {
                $infor->countFactor=$countAllFactor;
                $infor->totalMoneyHds=$sumAllFactor;
                $infor->countReturnFactor=$countAllReturnedFactor;
                $infor->totalReturnMoneyHds=$sumAllReturnedFactor;
                $infor->boughtPeopelsCount=$boughtPeopelsCount;
                $infor->lastMonthFactorAllMoney=$lastFactorAllMoney1;
                $infor->lastMonthReturnedAllMoney=$lastMonthReturnAllMoney1;
            }

            $history=DB::table("CRM.dbo.crm_adminHistory")->where("adminId",$adminId)->get();
            $minDate=DB::table("CRM.dbo.crm_customer_added")->where("returnState",0)->where("admin_id",$adminId)->min("addedTime");
            $countAllFactor=0;
            $sumAllFactor=0;
            $countAllReturnedFactor=0;
            $sumAllReturnedFactor=0;
            $customers=DB::select("SELECT customer_id,addedTime FROM CRM.dbo.crm_customer_added WHERE  admin_id=".$adminId." and returnState=0");
            $endDate=Jalalian::fromCarbon(Carbon::parse($minDate))->format('Y/m/d');
            $startDate=Jalalian::fromCarbon(Carbon::parse($minDate)->subdays(30))->format('Y/m/d');

            foreach ($customers as $customer) {
                $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$customer->customer_id." and FactType=3 and FactorHDS.FactDate>='".$startDate."' and FactorHDS.FactDate<='".$endDate."'  GROUP BY    FactorHDS.CustomerSn");
                foreach ($factors as $factor) {
                    $countAllFactor+=$factor->countFactors;
                    $sumAllFactor+=$factor->sumFactors;
                }

                $returnFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$customer->customer_id." and FactType=4 and FactorHDS.FactDate>='".$startDate."' and FactorHDS.FactDate<='".$endDate."'  GROUP BY    FactorHDS.CustomerSn");
                foreach ($returnFactors as $factor) {
                    $countAllReturnedFactor+=$factor->countFactors;
                    $sumAllReturnedFactor+=$factor->sumFactors;
                }
            }
            // DB::table("CRM.dbo.crm_adminHistory")->where("adminId",$adminId)->update(['noCommentCust'=>0,'noDoneWork'=>0]);
            $customers=array(array('countCustomers'=>count($customers),'countAllFactor'=>$countAllFactor,'sumAllFactor'=>$sumAllFactor,'countAllReturnedFactor'=>$countAllReturnedFactor,'sumAllReturnedFactor'=>$sumAllReturnedFactor));

            return Response::json([$admins,$info,$history,$customers]);
        }else{
            $admins1=DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->get();
            foreach ($admins1 as $admin) {
                $admin->minDate=0;
                $admin->countPeopel=0;
            }

            $lastFactorAllMoney=DB::select("SELECT factorAllMoney FROM CRM.dbo.crm_adminHistory WHERE   timeStamp=(
                SELECT MAX(timeStamp)from CRM.dbo.crm_adminHistory WHERE  adminId=".$adminId." GROUP BY    crm_adminHistory.adminId)");
            $lastFactorAllMoney1=0;
            foreach ($lastFactorAllMoney as $last) {
                $lastFactorAllMoney1=$last->factorAllMoney;
            }
            $info=array(array('countFactor'=>0,'totalMoneyHds'=>0,'boughtPeopelsCount'=>0,'lastMonthFactorAllMoney'=>$lastFactorAllMoney1));
            $history=DB::table("CRM.dbo.crm_adminHistory")->where("adminId",$adminId)->get();
            $minDate=DB::table("CRM.dbo.crm_customer_added")->where("returnState",0)->where("admin_id",$adminId)->min("addedTime");
            $countAllFactor=0;
            $sumAllFactor=0;
            $countAllReturnedFactor=0;
            $sumAllReturnedFactor=0;
            $customers=DB::select("SELECT customer_id,addedTime FROM CRM.dbo.crm_customer_added WHERE  admin_id=".$adminId." and returnState=0");
            foreach ($customers as $customer) {
                $removedDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
                $addedDate=Jalalian::fromCarbon(Carbon::parse($customer->addedTime))->format('Y/m/d');
                $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=3 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."'  GROUP BY    FactorHDS.CustomerSn");
                foreach ($factors as $factor) {
                    $countAllFactor+=$factor->countFactors;
                    $sumAllFactor+=$factor->sumFactors;
                }

                $returnFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=4 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."'  GROUP BY    FactorHDS.CustomerSn");
                foreach ($returnFactors as $factor) {
                    $countAllReturnedFactor+=$factor->countFactors;
                    $sumAllReturnedFactor+=$factor->sumFactors;
                }
            }
            $customers=array(array('countCustomers'=>count($customers),'countAllFactor'=>$countAllFactor,'sumAllFactor'=>$sumAllFactor,'countAllReturnedFactor'=>$countAllReturnedFactor,'sumAllReturnedFactor'=>$sumAllReturnedFactor));

            return Response::json([$admins1,$info,$history,$customers]);
        }
    }

    public function getAdminHistoryComment(Request $request)
    {
        $adminId=$request->get("id");
        $timeStamp=$request->get("timeStamp");
        $info=DB::table("CRM.dbo.crm_adminHistory")->where("adminId",$adminId)->where("timeStamp",$timeStamp)->first();
        return Response::json($info);
    }
    
    // ======================

    public function searchAdminByNameCode(Request $request)
    {
        $searchTerm=$request->get("searchTerm");

        $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType,crm_admin.id AS id 
                        FROM CRM.dbo.crm_admin
                        JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id
                        WHERE ( crm_admin.adminType=2 and crm_admin.deleted=0) AND (crm_admin.name LIKE '%$searchTerm%' OR crm_admin.lastName LIKE '%$searchTerm%')");
        return Response::json($admins);
    }
        
    // ======================

    public function searchAdminByType(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm!=0){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin
                            JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id
                            WHERE deleted=0 and crm_admin.adminType=".$searchTerm);
            return Response::json($admins);
        }else{
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin
                            JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id where deleted=0");
            return Response::json($admins);
        }
    }
        
    // ======================

    public function searchAdminByActivation(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm==0){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin
                            JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id WHERE  crm_admin.adminType=2 and deleted=0");
            return Response::json($admins);
        }
        if($searchTerm==1){
            $searchTerm=$request->get("searchTerm");
            
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin
                            JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id
                            WHERE  crm_admin.activeState=1 AND  (crm_admin.adminType=2 or deleted=0)");
            
            return Response::json($admins);
        }
        if($searchTerm==2){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin
                            JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id
                            WHERE  crm_admin.activeState=0 AND  (crm_admin.adminType=2 and deleted=0)");
            
            return Response::json($admins);
        }
    }
    
    // ======================

    public function searchAdminFactorOrNot(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
        
        if($searchTerm==1){
            $admins=DB::select("SELECT * FROM (
                            SELECT DISTINCT admin_id FROM(
                            SELECT * FROM (
                            SELECT * FROM CRM.dbo.crm_customer_added WHERE  admin_id not IN  (
                            SELECT DISTINCT admin_id FROM (
                            SELECT COUNT(SerialNoHDS)countFactor,CustomerSn FROM Shop.dbo.FactorHDS WHERE  FactDate='1401/05/6' GROUP BY    CustomerSn)a JOIN   (SELECT * FROM CRM.dbo.crm_customer_added WHERE  returnState=0)l ON a.CustomerSn=l.customer_id
                            ))b
                            WHERE  returnState=0)c)d
                            JOIN   (SELECT crm_admin.adminType as adminKind,name,lastName,crm_adminType.adminType,crm_admin.id FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id where deleted=0)e ON d.admin_id=e.id");
            return Response::json($admins);
        }

        if($searchTerm==2){
            $admins=DB::select("SELECT * FROM (
                            SELECT DISTINCT admin_id FROM(
                            SELECT * FROM (
                            SELECT * FROM CRM.dbo.crm_customer_added WHERE  admin_id IN  (
                            SELECT DISTINCT admin_id FROM (
                            SELECT COUNT(SerialNoHDS)countFactor,CustomerSn FROM Shop.dbo.FactorHDS WHERE  FactDate='1401/05/6' GROUP BY    CustomerSn)a JOIN   (SELECT * FROM CRM.dbo.crm_customer_added WHERE  returnState=0)l ON a.CustomerSn=l.customer_id
                            ))b
                            WHERE  returnState=0)c)d
                            JOIN   (SELECT crm_admin.adminType as adminKind,name,lastName,crm_adminType.adminType,crm_admin.id FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id where deleted=0)e ON d.admin_id=e.id");
            return Response::json($admins);
        }

        if($searchTerm==0){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin
            JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id WHERE  crm_admin.adminType=2 and deleted=0");
                return Response::json($admins);
                }
    }
        
    // ======================

    public function searchAdminLoginOrNot(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        if($searchTerm==2){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin JOIN CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id WHERE crm_admin.id NOT IN(
                            SELECT DISTINCT adminId FROM CRM.dbo.crm_loginTrack) and (crm_admin.adminType=2 ) and deleted=0");
            return Response::json($admins);
        }
        if($searchTerm==1){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin JOIN CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id WHERE crm_admin.id IN(
                            SELECT DISTINCT adminId FROM CRM.dbo.crm_loginTrack) and (crm_admin.adminType=2) and deleted=0");
            return Response::json($admins);
        }
        if($searchTerm==0){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin JOIN CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id  and (crm_admin.adminType=2) and deleted=0");
            return Response::json($admins);
        }
    }
    
    // ======================

    public function searchAdminCustomerLoginOrNot(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        
        if($searchTerm==2){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id
                            WHERE deleted=0 and  crm_admin.id not in(
                            SELECT * FROM(
                            SELECT DISTINCT admin_id FROM(
                            SELECT * FROM (
                            SELECT * FROM(
                            SELECT PSN FROM Shop.dbo.Peopels WHERE  PSN in(
                            SELECT customerId FROM NewStarfood.dbo.star_customerSession1))a
                            JOIN   (SELECT customer_id,admin_id FROM CRM.dbo.crm_customer_added WHERE  returnState=0 )b ON a.PSN=b.customer_id)c)f)g
                            )  and (crm_admin.adminType=2 or crm_admin.adminType=3)");
            return Response::json($admins);
        }
        
        if($searchTerm==1){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id
                            WHERE deleted=0 and crm_admin.id in(
                            SELECT * FROM(
                            SELECT DISTINCT admin_id FROM(
                            SELECT * FROM (
                            SELECT * FROM(
                            SELECT PSN FROM Shop.dbo.Peopels WHERE  PSN in(
                            SELECT customerId FROM NewStarfood.dbo.star_customerSession1))a
                            JOIN   (SELECT customer_id,admin_id FROM CRM.dbo.crm_customer_added WHERE  returnState=0 )b ON a.PSN=b.customer_id)c)f)g
                            )  and (crm_admin.adminType=2 or crm_admin.adminType=3)");
            return Response::json($admins);
        }
        if($searchTerm==0){
            $admins=DB::select("SELECT name,lastName,address,password,activeState,
            sex,discription,emptyState,driverId,crm_admin.adminType as adminTypeId,crm_adminType.adminType,crm_admin.id as id 
            FROM CRM.dbo.crm_admin JOIN   CRM.dbo.crm_adminType ON crm_admin.adminType=crm_adminType.id  WHERE  crm_admin.adminType=2 and deleted=0");
            return Response::json($admins);
        }
    }
    
    // ======================

    public function getAdminTodayInfo(Request $request)
    {
        $adminId=$request->get("asn");
        $todayDate=Carbon::now()->format('Y-m-d');
        $today=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
        
        $todayInfo=DB::select("SELECT * FROM (SELECT countPeopel,countFactor,countComment,a.admin_id FROM(
                            SELECT COUNT(crm_customer_added.id)as countPeopel,crm_customer_added.admin_id FROM CRM.dbo.crm_customer_added WHERE  crm_customer_added.returnState=0 GROUP BY    admin_id)
                            a
                            left JOIN   (SELECT COUNT(FactorHDS.SerialNoHDS) as countFactor,admin_id FROM Shop.dbo.FactorHDS JOIN   CRM.dbo.crm_customer_added ON FactorHDS.CustomerSn=crm_customer_added.customer_id  WHERE  FactorHDS.FactDate='".$today."'  GROUP BY    admin_id)
                            b
                            ON a.admin_id=b.admin_id
                            left JOIN   (SELECT COUNT(id) as countComment,crm_comment.adminId FROM CRM.dbo.crm_comment WHERE  crm_comment.TimeStamp='".$todayDate."' GROUP BY    adminId)
                            c
                            ON a.admin_id=c.adminId)d
                            JOIN   CRM.dbo.crm_admin ON d.admin_id=crm_admin.id
                            WHERE  d.admin_id=".$adminId);
        
        $todayAdminInfo=DB::select("SELECT count(customerId) countComment FROM(
                                SELECT * FROM (SELECT * FROM (
                                SELECT Peopels.Name,Peopels.PSN,admin_id,returnState FROM Shop.dbo.Peopels
                                JOIN   CRM.dbo.crm_customer_added ON Peopels.PSN=CRM.dbo.crm_customer_added.customer_id)
                                a
                                left JOIN   (SELECT COUNT(FactorHDS.SerialNoHDS) as countFactor,FactorHDS.CustomerSn FROM Shop.dbo.FactorHDS WHERE  FactorHDS.FactDate='".$today."'  GROUP BY    FactorHDS.CustomerSn)
                                b
                                ON a.PSN=b.CustomerSn
                                WHERE  returnState=0 AND  admin_id=".$adminId.")c
                                JOIN   (SELECT MAX(timeStamp) as maxHour,customerId FROM CRM.dbo.crm_comment WHERE  CAST(timeStamp as DATE)='".$todayDate."' GROUP BY    customerId)d
                                ON d.customerId=c.PSN)d");
        
                                $countFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactor FROM Shop.dbo.FactorHDS WHERE  FactorHDS.CustomerSn in(SELECT customer_id FROM CRM.dbo.crm_customer_added WHERE  admin_id=".$adminId." and returnState=0) and FactorHDS.FactDate='".$today."'");
        $countCustomers=DB::select("SELECT COUNT(id) as countCustomer FROM CRM.dbo.crm_customer_added WHERE  returnState=0 and admin_id=".$adminId);
        $countComment=0;
        $countFactor=0;
        $countCustomer=0;
        
        foreach ($todayAdminInfo as $info) {
            $countComment=$info->countComment;
        }
        
        foreach ($countFactors as $fact) {
            $countFactor=$fact->countFactor;
        }
        
        foreach ($countCustomers as $cust) {
            $countCustomer=$cust->countCustomer;
        }
        $loginTime=DB::table("CRM.dbo.crm_loginTrack")->where('adminId',$adminId)->select('loginTime')->first();
        $loginTime1=0;
        
        if($loginTime){
        $loginTime1=$loginTime->loginTime;
        }
        
        $adminTodayInfo=array(array('countComments'=>$countComment,'countFctors'=>$countFactor,'countCustomers'=>$countCustomer,'loginTime'=>$loginTime1));
        
        $customers=DB::select("SELECT * FROM (SELECT * FROM (
                        SELECT Peopels.Name,Peopels.PSN,admin_id,returnState FROM Shop.dbo.Peopels
                        JOIN   CRM.dbo.crm_customer_added ON Peopels.PSN=CRM.dbo.crm_customer_added.customer_id)
                        a
                        left JOIN   (SELECT COUNT(FactorHDS.SerialNoHDS) as countFactor,FactorHDS.CustomerSn FROM Shop.dbo.FactorHDS WHERE  FactorHDS.FactDate='".$today."'  GROUP BY    FactorHDS.CustomerSn)
                        b
                        ON a.PSN=b.CustomerSn
                        WHERE  returnState=0 and admin_id=".$adminId.")c
                        JOIN   (SELECT MAX(timeStamp) as maxHour,customerId FROM CRM.dbo.crm_comment WHERE  CAST(timeStamp as DATE)='".$todayDate."' GROUP BY    customerId)d
                        ON d.customerId=c.PSN");
        return Response::json([$todayInfo,$customers,$adminTodayInfo]);
    }
        
    // ======================

    public function checkUserNameExistance(Request $request)
    {
        $username=$request->get("username");
        $countExistance=DB::table("CRM.dbo.crm_admin")->where('username',$username)->count();
        return Response::json($countExistance);
    }
        
    // ======================

    public function getAdminForEmpty(Request $request)
    {
        $adminId=$request->get("asn");
        $admin=DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->get();
        return Response::json($admin);
    }
        
    // ======================

    public function getAdminForMove(Request $request)
    {
        $adminId=$request->get("asn");
        $admin=DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->get();
        $otherAdmins=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE id not in(SELECT DISTINCT admin_id FROM CRM.dbo.crm_customer_added WHERE  returnState=0) and id !=".$adminId." and (adminType=3 or adminType=2)");
        $bossAdmins=DB::select("SELECT * FROM CRM.dbo.crm_admin WHERE id !=".$adminId." and deleted=0 and (adminType!=4)");


        return Response::json([$admin,$otherAdmins,$bossAdmins]);
    }
    
    // ======================

    public function emptyAdmin(Request $request)
    {
        $adminId=$request->get("asn");
        $personType=DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->get()[0];
        //برای محاسبه اطلاعات تاریخچه بازاریابها
        //نصب‌ها---1
        $count_All_aghlam=0;
        $istallComTg="هیچکدام";
        $aghlamComTg="هیچکدام";
        $monyComTg="هیچکدام";
        $countBuyComTg="هیچکدام";
        //تارگت ها تکمیل شده
        $istallComTgBonus=0;
        $aghlamComTgBonus=0;
        $monyComTgBonus=0;
        $countBuyComTgBonus=0;
        //امتیاز های اختصاصی
        $istallBonus=0;
        $aghlamBonus=0;
        $monyBonus=0;
        $countBuyBonus=0;
        //امتیاز اضافی فرد
        $extarBonus=0;
        //مجموع کل امتیازات
        $allBonuses=0;
        $all_bonus_since_Empty=0;


//بازاریابها
        $all_monthly_bonuses=0;
        $all_bonus_since_Empty=0;
        $count_All_aghlam=0;
        $count_All_Install=0;
        $count_All_New_buys=0;
        $sum_all_money=0;
//امتیازات این ماه بعد از تخلیه
        $bonus_All_aghlam=0;
        $bonus_All_Install=0;
        $bonus_All_New_buys=0;
        $bonus_all_money=0;
        
//  مشتریان مربوط به پشتیبان اختصاصی
        $customers=DB::select("SELECT customer_id,returnState,addedTime FROM CRM.dbo.crm_customer_added 
        WHERE  returnState=0 and crm_customer_added.admin_id=".$adminId." and customer_id is not null");
        $countAllFactor=0;
        $sumAllFactor=1;
        $countAllReturnedFactor=0;
        $sumAllReturnedFactor=1;
        $seenCustomers=array();
        foreach ($customers as $customer) {

            $removedDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
            $addedDate=Jalalian::fromCarbon(Carbon::parse($customer->addedTime))->format('Y/m/d');
            $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=3 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."' GROUP BY    FactorHDS.SerialNoHDS");
            $returnedFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=4 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."' GROUP BY    FactorHDS.SerialNoHDS");

            foreach ($factors as $factor) {
                if($factor->countFactors>0 and !in_array($customer->customer_id, $seenCustomers)){
                array_push($seenCustomers,$customer->customer_id);
                }
                $countAllFactor+=$factor->countFactors;
                $sumAllFactor+=$factor->sumFactors;
            }
            
            foreach ($returnedFactors as $factor) {
                $countAllReturnedFactor+=$factor->countFactors;
                $sumAllReturnedFactor+=$factor->sumFactors;
            }
        }

        $boughtPeopelsCount=count($seenCustomers);


        $info=DB::select("SELECT COUNT(customer_id)as countPeopels,crm_customer_added.admin_id FROM CRM.dbo.crm_customer_added 
            JOIN CRM.dbo.crm_admin ON crm_customer_added.admin_id=crm_admin.id 
            WHERE returnState=0 and crm_customer_added.admin_id=".$adminId." GROUP BY    admin_id");

        foreach ($info as $infor) {
            $infor->countFactor=$countAllFactor;
            $infor->totalMoneyHds=$sumAllFactor;
            $infor->boughtPeopelsCount=$boughtPeopelsCount;
            $infor->sumAllReturnedFactor=$sumAllReturnedFactor;
        }
        $exactInfo=$info[0];

//برای اطلاعات ماه قبل همین مشتریان
        $history=DB::table("CRM.dbo.crm_adminHistory")->where("adminId",$adminId)->get();
        $minDate=DB::table("CRM.dbo.crm_customer_added")->where("returnState",0)->where("admin_id",$adminId)->min("addedTime");
        $countAllFactor2=0;
        $sumAllFactor2=0;
        $countAllReturnedFactor2=0;
        $sumAllReturnedFactor2=0;
        $customers=DB::select("SELECT customer_id,addedTime FROM CRM.dbo.crm_customer_added WHERE  admin_id=".$adminId." and returnState=0");
        $endDate=Jalalian::fromCarbon(Carbon::parse($minDate))->format('Y/m/d');
        $startDate=Jalalian::fromCarbon(Carbon::parse($minDate)->subdays(30))->format('Y/m/d');

        foreach ($customers as $customer) {
            $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$customer->customer_id." and FactType=3 and FactorHDS.FactDate>='".$startDate."' and FactorHDS.FactDate<='".$endDate."'  GROUP BY    FactorHDS.CustomerSn");
            foreach ($factors as $factor) {
                $countAllFactor2+=$factor->countFactors;
                $sumAllFactor2+=$factor->sumFactors;
            }

            $returnFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$customer->customer_id." and FactType=4 and FactorHDS.FactDate>='".$startDate."' and FactorHDS.FactDate<='".$endDate."'  GROUP BY    FactorHDS.CustomerSn");
            foreach ($returnFactors as $factor) {
                $countAllReturnedFactor2+=$factor->countFactors;
                $sumAllReturnedFactor2+=$factor->sumFactors;
            }
        }

        //اطلاعات ماه قبل تاریخچه
        $lastHistoryInfo=DB::select("SELECT * FROM CRM.DBO.crm_adminHistory WHERE crm_adminHistory.timeStamp=(SELECT MAX(timeStamp) as MaxDate FROM CRM.dbo.crm_adminHistory WHERE adminId=".$adminId." GROUP BY    adminId)");
        
        $meanIncreas=0;
        
        if($sumAllFactor2!=0){
            $meanIncreas=($sumAllFactor-$sumAllFactor2)/$sumAllFactor2;
            }
        //اطلاعات مربوط به کامنت
        $comment=DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->first();
		$adminComment="";
		if($comment){
		$adminComment=$comment->discription;
		}
        $countAllCommentedCustomers=DB::select("select COUNT(customerId) AS countComment from(
                                                select distinct customerId from CRM.dbo.crm_comment where adminId=$adminId and TimeStamp>=(select min(addedTime) from CRM.dbo.crm_customer_added where returnState=0 and admin_id=$adminId )
                                                and TimeStamp<=(select CURRENT_TIMESTAMP  
                                                )
                                                )a");
        $allActiveCustomerCount=$exactInfo->countPeopels;
        $countAllComments=0;
        if(count($countAllCommentedCustomers)>0){
            $countAllComments=$countAllCommentedCustomers[0]->countComment;
        }
        $nocommentedCustomers=$allActiveCustomerCount -$countAllComments;

        if($nocommentedCustomers<0){
            $nocommentedCustomers=0; 
        }

        $todayDate=Carbon::now()->format('Y-m-d');
        $countNoDoneWork=\DB::select("SELECT count(countJob) as countJob from(
            SELECT COUNT(id) as countJob,customer_id from (
            SELECT * from (
            SELECT crm_workList.commentId,crm_workList.id,crm_workList.doneState,crm_workList.specifiedDate,crm_comment.customerId from CRM.dbo.crm_workList 
            JOIN CRM.dbo.crm_comment on crm_workList.commentId=crm_comment.id where doneState=0)a
            JOIN (SELECT customer_id,returnState,admin_id from CRM.dbo.crm_customer_added )c on a.customerId =c.customer_id where c.returnState=0 and admin_id=$adminId)b
            where specifiedDate<='".$todayDate."'
            group by customer_id)a");
        if($personType->adminType==3){
// // در صورتیکه بازاریاب تخلیه شود
//         $EMPTYDATE='2022-11-11';
//         $emptyDateInfo=DB::select("SELECT CONVERT(DATE,timeStamp) AS emptyDate FROM CRM.dbo.crm_adminHistory WHERE id=(SELECT MAX(id) FROM CRM.dbo.crm_adminHistory WHERE adminId=$adminId)");
//         if($emptyDateInfo){
//             $EMPTYDATE=$emptyDateInfo[0]->emptyDate;
//         }
//         $specialBonuses=DB::table("CRM.dbo.crm_specialBonus")->get();
//         foreach($specialBonuses as $special){
//             if($special->id==11){
//                 //نصب
//                 $count_New_Install=DB::select("SELECT count(id) as countNewInstall from(
//                     SELECT *, convert(date,addedDate) as justDate from CRM.dbo.crm_inserted_customers)a where a.justDate=CAST( GETDATE() AS Date ) and adminId=$adminId");
        
//                 $count_All_Install=DB::select("SELECT count(id) as countAllInstall from(
//                     SELECT * from CRM.dbo.crm_inserted_customers where CONVERT(DATE,crm_inserted_customers.addedDate)>='$EMPTYDATE')a where  adminId=$adminId");
// 				if(count($count_All_Install)>0){
                    
//                     $count_All_Install=$count_All_Install[0]->countAllInstall;
// 				}else{
// 					$count_All_Install=0;
// 				}
                    
//                 $installBonus=((int)($count_All_Install/$special->limitAmount)) * $special->Bonus;
//                 $all_bonus_since_Empty+=$installBonus;
//                 $bonus_All_Install=$installBonus;
//             }
            
//             if($special->id==12){
//                 //اقلام
//                 $count_All_aghlamR=DB::select("SELECT count(countGoods) as countAghlam,admin_id from (			
//                                             SELECT count(SnGood) as countGoods,admin_id,SnGood from (
//                                             SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood,CustomerSn from(
//                                             SELECT * FROM(
//                                                 SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood,CustomerSn FROM Shop.dbo.FactorHDS
//                                                 JOIN Shop.dbo.FactorBYS on FactorHDS.SerialNoHDS=FactorBYS.SnFact)a
//                                                 )g WHERE CONVERT(DATE,TimeStamp)>='$EMPTYDATE' group by SnGood,CustomerSn)c
//                                                 join (select * from CRM.dbo.crm_customer_added)d on c.CustomerSn=d.customer_id)f group by admin_id,SnGood
//                                                 )e where e.admin_id=$adminId group by admin_id");
//                 if(count($count_All_aghlamR)>0){
//                     $count_All_aghlam=$count_All_aghlamR[0]->countAghlam;
//                 }

//                 $count_aghlam_todayR=DB::select("SELECT count(countGoods) as countAghlam,admin_id from (			
//                     SELECT count(SnGood) as countGoods,admin_id,SnGood from (
//                     SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood,CustomerSn from(
//                     SELECT * FROM(
//                         SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood,CustomerSn FROM Shop.dbo.FactorHDS
//                         JOIN Shop.dbo.FactorBYS on FactorHDS.SerialNoHDS=FactorBYS.SnFact)a
//                         )g group by SnGood,CustomerSn)c
//                         join (select * from CRM.dbo.crm_customer_added)d on c.CustomerSn=d.customer_id)f WHERE CONVERT(date,maxTime)=CONVERT(date,CURRENT_TIMESTAMP) group by admin_id,SnGood
//                         )e where e.admin_id=$adminId group by admin_id");

//                 if(count($count_aghlam_todayR)>0){
//                     $count_aghlam_today=$count_aghlam_todayR[0]->countAghlam;
//                 }

//                 $instAghlamBonus=((int)($count_All_aghlam/$special->limitAmount)) * $special->Bonus;
//                 $all_bonus_since_Empty+=$instAghlamBonus;
//                 $bonus_All_aghlam=$instAghlamBonus;
//             }

//             if($special->id==13){
//                 //مبلغ
//                 $allMoney_till_now=DB::select("SELECT SUM(NetPriceHDS) AS SumOfMoney,admin_id FROM Shop.dbo.factorHds
//                 JOIN (SELECT * FROM CRM.dbo.crm_customer_added)d ON factorHds.CustomerSn=d.customer_id
//                 WHERE FactType=3 AND admin_id=$adminId and CONVERT(DATE,timestamp)>='$EMPTYDATE' GROUP BY admin_id");
//                 if(count($allMoney_till_now)>0){
//                     $sum_all_money=$allMoney_till_now[0]->SumOfMoney;
//                 }

//                 $today_money=DB::select("SELECT SUM(NetPriceHDS) AS SumOfMoney,admin_Id FROM Shop.dbo.factorHds
//                                         JOIN (SELECT * FROM CRM.dbo.crm_customer_added)d ON factorHds.CustomerSn=d.customer_id
//                                         WHERE FactType=3 AND admin_id=$adminId AND CONVERT(date,timestamp)=CONVERT(date,CURRENT_TIMESTAMP) GROUP BY admin_Id");
//                 if(count($today_money)>0){
//                     $sum_today_money=$today_money[0]->SumOfMoney;
//                 }
//                 $allMoneyBonus=((int)($sum_all_money/10/$special->limitAmount)) * $special->Bonus;
//                 $all_bonus_since_Empty+=$allMoneyBonus;
//                 $bonus_all_money=$allMoneyBonus;
//             }

//             if($special->id==14){
//                 //خرید اولیه
//                 //همه           
//                 $count_All_New_buys=DB::select("SELECT count(CustomerSn) as countNewFactor,admin_id from (
//                     SELECT distinct CustomerSn from (SELECT * from Shop.dbo.FactorHds
// 					JOIN CRM.dbo.crm_inserted_customers on FactorHDS.CustomerSn=crm_inserted_customers.customerId 
// 					where FactType=3 AND DATEDIFF(hour,CONVERT(DATE,addedDate), CONVERT(DATE,timestamp))<=72 and crm_inserted_customers.adminId=$adminId and CONVERT(DATE,timestamp)>='$EMPTYDATE')b
//                     )c  join CRM.dbo.crm_customer_added on c.CustomerSn=customer_id where admin_id=$adminId  group by admin_id");
//                 if(count($count_All_New_buys)>0){
//                 $count_All_New_buys=$count_All_New_buys[0]->countNewFactor;
//                 }else{
//                 $count_All_New_buys=0;
//                 }
               
//                 $allBuyBonus=((int)($count_All_New_buys/$special->limitAmount)) * $special->Bonus;
//                 $all_bonus_since_Empty+=$allBuyBonus;
//                 $bonus_All_New_buys=$allBuyBonus;
//             }
            
//             //installs
//             $special->count_New_Install=$count_New_Install;
//             $special->count_All_Install=$count_All_Install;
//             //buys
//             $special->count_All_New_buys=$count_All_New_buys;
//             $special->count_All_aghlam=$count_All_aghlam;
//             $special->sum_all_money=$sum_all_money;
//         }

//         //محاسبه امتیازات اضافی بازاریابها
//         $all_monthly_bonuses=0;
//         $historyExist=DB::select("select sum(positiveBonus)-sum(negativeBonus) as sumAllBonus from CRM.dbo.crm_adminUpDownBonus  where adminId=$adminId and isUsed=0");
//         if($historyExist){
//             $all_monthly_bonuses=$historyExist[0]->sumAllBonus;
//         }

//         $all_bonus_since_Empty+=$all_monthly_bonuses;

//         $selfHistory=DB::table("CRM.dbo.crm_adminHistory")->where('adminId',$adminId)->get();

//         //اکمال تارگت های فروش
//         $targets=DB::select("SELECT * FROM CRM.dbo.crm_targets");
//         //ارزیابی تارگت‌ها
//         foreach($targets as $target){
//             //تارگت‌های نصب
//             if($target->id==4){
//                 if($count_All_Install >= $target->thirdTarget){
//                     $istallComTg="تارگیت سوم";
//                     $istallComTgBonus=$target->thirdTargetBonus;
//                 }else{
//                     if($count_All_Install >= $target->secondTarget){
//                         $istallComTg="تارگیت دوم";
//                         $istallComTgBonus=$target->scondTargetBonus;
//                     }else{
//                         if($count_All_Install >= $target->firstTarget){
//                             $istallComTg="تارگیت اول";
//                             $istallComTgBonus=$target->firstTargetBonus;
//                         }
//                     }
//                 }
//             }
//                 //تارگت‌های تعداد خرید اولیه
//             if($target->id==6){
//                 if($count_All_New_buys >= $target->thirdTarget){
//                     $countBuyComTg="تارگیت سوم";
//                     $countBuyComTgBonus=$target->thirdTargetBonus;
//                 }else{
//                     if($count_All_New_buys >= $target->secondTarget){
//                         $countBuyComTg="تارگیت دوم";
//                         $countBuyComTgBonus=$target->thirdTargetBonus;
//                     }else{
//                         if($count_All_New_buys >= $target->firstTarget){
//                             $countBuyComTg="تارگیت اول";
//                             $countBuyComTgBonus=$target->thirdTargetBonus;
//                         }
//                     }
//                 }
//             }
//             if(count($allMoney_till_now)>0){
//                 //تارگت‌های مبلغ خرید
//             if($target->id==7){
//                 if(($allMoney_till_now[0]->SumOfMoney/10) >= $target->thirdTarget){
//                     $monyComTg="تارگیت سوم";
//                     $monyComTgBonus=$target->thirdTargetBonus;
//                 }else{
//                     if(($allMoney_till_now[0]->SumOfMoney/10) >= $target->secondTarget){
//                         $monyComTg="تارگیت دوم";
//                         $monyComTgBonus=$target->thirdTargetBonus;
//                     }else{
//                         if(($allMoney_till_now[0]->SumOfMoney/10) >= $target->firstTarget){
//                             $monyComTg="تارگیت اول";
//                             $monyComTgBonus=$target->thirdTargetBonus;
//                         }
//                     }
//                 }
//             }
//         }
//                 //تارگت‌های اقلام خرید
//             if($target->id==5){
//                 if($count_All_aghlam >= $target->thirdTarget){
//                     $aghlamComTg="تارگیت سوم";
//                     $aghlamComTgBonus=$target->thirdTargetBonus;
//                 }else{
//                     if($count_All_aghlam >= $target->secondTarget){
//                         $aghlamComTg="تارگیت دوم";
//                         $aghlamComTgBonus=$target->thirdTargetBonus;
//                     }else{
//                         if($count_All_aghlam >= $target->firstTarget){
//                             $aghlamComTg="تارگیت اول";
//                             $aghlamComTgBonus=$target->firtTargetBonus;
//                         }
//                     }
//                 }
//             }

//             //امتیازات اضافی جریان ماه
//             $all_monthly_bonuses=0;
//             $historyExist=DB::select("select sum(positiveBonus)-sum(negativeBonus) as sumAllBonus from CRM.dbo.crm_adminUpDownBonus  where adminId=$adminId and isUsed=0");
//             if($historyExist){
//                 $all_monthly_bonuses=$historyExist[0]->sumAllBonus;
//             }

//             $all_bonus_since_Empty+=$all_monthly_bonuses;

//             DB::update("update CRM.dbo.crm_adminUpDownBonus set isUsed=1 where adminId=$adminId");

//         }
    }

    if($personType->adminType==2){
    // در صورتیکه پشتیبان باشد
    $poshtiban=new Poshtiban;
    // $poshtiban->getPoshtibanActionInformation($adminId);
    // return $poshtiban->getPoshtibanActionInformation($adminId);

    $poshtibanBonusInfo=$poshtiban->getPoshtibanActionInformation($adminId);
    
   


    $count_All_aghlam=$poshtibanBonusInfo['count_All_aghlam'];
    $count_All_New_buys=$poshtibanBonusInfo['count_All_New_buys'];
    $istallComTg=$poshtibanBonusInfo['istallComTg'];
    $aghlamComTg=$poshtibanBonusInfo['aghlamComTg'];
    $monyComTg=$poshtibanBonusInfo['monyComTg'];
    $countBuyComTg=$poshtibanBonusInfo['countBuyComTg'];
    //تارگت ها تکمیل شده

    $istallComTgBonus=0;
    $aghlamComTgBonus=$poshtibanBonusInfo['aghlamComTgBonus'];
    $monyComTgBonus=$poshtibanBonusInfo['monyComTgBonus'];
    $countBuyComTgBonus=$poshtibanBonusInfo['countBuyComTgBonus'];
    //امتیاز های اختصاصی
    $istallBonus=$poshtibanBonusInfo['bonus_All_Install'];
    $aghlamBonus=$poshtibanBonusInfo['bonus_All_aghlam'];
    $monyBonus=$poshtibanBonusInfo['bonus_all_money'];
    $countBuyBonus=$poshtibanBonusInfo['bonus_All_New_buys'];
    //امتیاز اضافی فرد
    if($poshtibanBonusInfo['all_monthly_bonuses']){
        $extarBonus=$poshtibanBonusInfo['all_monthly_bonuses'];
    }else{
        $extarBonus=0;
    }
    
    //مجموع کل امتیازات
    $all_bonus_since_Empty=$poshtibanBonusInfo['all_bonus_since_Empty'];


    }
    if($personType->adminType==4){
// در صورتیکه راننده باشد
    }
    if($personType->adminType==1){
//در صورتیکه ادمین باشد

    }
        //ثبت تاریخچه
        DB::table("CRM.dbo.crm_adminHistory")->insert(['adminId'=>$adminId,'countPeople'=>$exactInfo->countPeopels,'countFactor'=>$countAllFactor,'countBuyPeople'=>$boughtPeopelsCount,'factorAllMoney'=>$sumAllFactor
        ,'lastMonthAllMoney'=>$sumAllFactor2,'lastMonthReturnedAllMoney'=>$sumAllReturnedFactor,'meanIncrease'=>$meanIncreas,'comment'=>"".$adminComment."",'noCommentCust'=>$nocommentedCustomers,'noDoneWork'=>$countNoDoneWork[0]->countJob,
        'countReturnedFactor'=>$countAllReturnedFactor,'countLastMonthFactor'=>$countAllFactor2,'countLastMonthReturnedFactor'=>$countAllReturnedFactor2
        ,'allInstall'=>$count_All_Install
        ,'allPrimaryBuy'=>$count_All_New_buys
        ,'allAghlam'=>$count_All_aghlam
        ,'lastComInstallTg'=>"".$istallComTg.""
        ,'lastComPrimBuyTg'=>"".$aghlamComTg.""
        ,'lastComAghlamTg'=>"".$monyComTg.""
        ,'lastComMoneyTg'=>"".$countBuyComTg.""
        ,'installBonus'=> $istallBonus
        ,'primeBuyBonus'=> $countBuyBonus
        ,'totalMoneyBonus'=>$monyBonus
        ,'totalAghlamBonus'=>$aghlamBonus
        ,'lastComInstallTgB'=>$istallComTgBonus
        ,'lastComPrimBuyTgB'=>$aghlamComTgBonus
        ,'lastComAghlamTgB'=>$monyComTgBonus
        ,'lastComMoneyTgB'=>$countBuyComTgBonus
        ,'extraBonus'=>$extarBonus
        ,'allBonus'=>$all_bonus_since_Empty]);
        //ثبت تاریخ حذف مشتریان
        DB::update("UPDATE CRM.dbo.crm_customer_added set removedTime='".Carbon::now()."' WHERE  returnState=0 and admin_id=".$adminId);
        //تخیلیه مشتریان
        DB::update("UPDATE CRM.dbo.crm_customer_added set returnState=1, gotEmpty=1 WHERE  returnState=0 and admin_id=".$adminId);
        // ادمین به عنوان خالی
        DB::update("UPDATE CRM.dbo.crm_admin set emptyState=1 WHERE  id=".$adminId);

        DB::update("update CRM.dbo.crm_adminUpDownBonus set isUsed=1 where adminId=$adminId");
        return Response::json(1);


    
        //ختم تخلیه بازاریابها

        
    }   
    // ======================

    public function moveCustomerToAdmin(Request $request)
    {
        $holderAdmin=$request->get("holderID");
        $giverAdmin=$request->get("giverID");
        $adminId=$giverAdmin;
        
        //  مشتریان مربوط به پشتیبان اختصاصی
        $customers=DB::select("SELECT customer_id,returnState,addedTime FROM CRM.dbo.crm_customer_added 
        WHERE  returnState=0 and crm_customer_added.admin_id=".$adminId);
        $countAllFactor=0;
        $sumAllFactor=1;
        $countAllReturnedFactor=0;
        $sumAllReturnedFactor=1;
        $seenCustomers=array();
        foreach ($customers as $customer) {
            $removedDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
            $addedDate=Jalalian::fromCarbon(Carbon::parse($customer->addedTime))->format('Y/m/d');
            $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=3 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."' GROUP BY    FactorHDS.SerialNoHDS");
            $returnedFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  FactType=4 and CustomerSn=".$customer->customer_id." and FactorHDS.FactDate>='".$addedDate."' and FactorHDS.FactDate<='".$removedDate."' GROUP BY    FactorHDS.SerialNoHDS");

            foreach ($factors as $factor) {
                if($factor->countFactors>0 and !in_array($customer->customer_id, $seenCustomers)){
                array_push($seenCustomers,$customer->customer_id);
                }
                $countAllFactor+=$factor->countFactors;
                $sumAllFactor+=$factor->sumFactors;
            }
            
            foreach ($returnedFactors as $factor) {
                $countAllReturnedFactor+=$factor->countFactors;
                $sumAllReturnedFactor+=$factor->sumFactors;
            }
        }
        $boughtPeopelsCount=count($seenCustomers);

        $info=DB::select("SELECT COUNT(customer_id)as countPeopels,crm_customer_added.admin_id FROM CRM.dbo.crm_customer_added 
            JOIN CRM.dbo.crm_admin ON crm_customer_added.admin_id=crm_admin.id 
            WHERE returnState=0 and crm_customer_added.admin_id=".$adminId." GROUP BY    admin_id");

        foreach ($info as $infor) {
            $infor->countFactor=$countAllFactor;
            $infor->totalMoneyHds=$sumAllFactor;
            $infor->boughtPeopelsCount=$boughtPeopelsCount;
            $infor->sumAllReturnedFactor=$sumAllReturnedFactor;
        }
        $exactInfo=$info[0];

//برای اطلاعات ماه قبل همین مشتریان
        $history=DB::table("CRM.dbo.crm_adminHistory")->where("adminId",$adminId)->get();
        $minDate=DB::table("CRM.dbo.crm_customer_added")->where("returnState",0)->where("admin_id",$adminId)->min("addedTime");
        $countAllFactor2=0;
        $sumAllFactor2=0;
        $countAllReturnedFactor2=0;
        $sumAllReturnedFactor2=0;
        $customers=DB::select("SELECT customer_id,addedTime FROM CRM.dbo.crm_customer_added WHERE  admin_id=".$adminId." and returnState=0");
        $endDate=Jalalian::fromCarbon(Carbon::parse($minDate))->format('Y/m/d');
        $startDate=Jalalian::fromCarbon(Carbon::parse($minDate)->subdays(30))->format('Y/m/d');

        foreach ($customers as $customer) {
            $factors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$customer->customer_id." and FactType=3 and FactorHDS.FactDate>='".$startDate."' and FactorHDS.FactDate<='".$endDate."'  GROUP BY    FactorHDS.CustomerSn");
            foreach ($factors as $factor) {
                $countAllFactor2+=$factor->countFactors;
                $sumAllFactor2+=$factor->sumFactors;
            }

            $returnFactors=DB::select("SELECT COUNT(FactorHDS.SerialNoHDS) as countFactors,SUM(FactorHDS.TotalPriceHDS) as sumFactors FROM Shop.dbo.FactorHDS WHERE  CustomerSn=".$customer->customer_id." and FactType=4 and FactorHDS.FactDate>='".$startDate."' and FactorHDS.FactDate<='".$endDate."'  GROUP BY    FactorHDS.CustomerSn");
            foreach ($returnFactors as $factor) {
                $countAllReturnedFactor2+=$factor->countFactors;
                $sumAllReturnedFactor2+=$factor->sumFactors;
            }
        }
        //اطلاعات ماه قبل تاریخچه
        $lastHistoryInfo=DB::select("SELECT * FROM CRM.DBO.crm_adminHistory WHERE crm_adminHistory.timeStamp=(SELECT MAX(timeStamp) as MaxDate FROM CRM.dbo.crm_adminHistory WHERE adminId=".$adminId." GROUP BY    adminId)");
        
        $meanIncreas=0;
        
        if($sumAllFactor2!=0){
        $meanIncreas=($sumAllFactor-$sumAllFactor2)/$sumAllFactor2;
        }
       // if($meanIncreas<0){
       //     $meanIncreas=0;
       // }

        //اطلاعات مربوط به کامنت
        $comment=DB::table("CRM.dbo.crm_admin")->where('id',$adminId)->first();
		$adminComment="";
		if($comment){
		$adminComment=$comment->discription;
		}
        $countAllCommentedCustomers=DB::select("select COUNT(customerId) AS countComment from(
                                                select distinct customerId from CRM.dbo.crm_comment where adminId=$adminId and TimeStamp>=(select min(addedTime) from CRM.dbo.crm_customer_added where returnState=0 and admin_id=$adminId )
                                                and TimeStamp<=(select CURRENT_TIMESTAMP  
                                                )
                                                )a");
        $allActiveCustomerCount=$exactInfo->countPeopels;
        $countAllComments=0;
        if(count($countAllCommentedCustomers)>0){
            $countAllComments=$countAllCommentedCustomers[0]->countComment;
        }
        $nocommentedCustomers=$allActiveCustomerCount -$countAllComments;

        if($nocommentedCustomers<0){
            $nocommentedCustomers=0; 
        }

        $todayDate=Carbon::now()->format('Y-m-d');
        $countNoDoneWork=\DB::select("select count(countJob) as countJob from(
            select COUNT(id) as countJob,customer_id from (
            select * from (
            select crm_workList.commentId,crm_workList.id,crm_workList.doneState,crm_workList.specifiedDate,crm_comment.customerId from CRM.dbo.crm_workList 
            join CRM.dbo.crm_comment on crm_workList.commentId=crm_comment.id where doneState=0)a
            join (select customer_id,returnState,admin_id from CRM.dbo.crm_customer_added )c on a.customerId =c.customer_id where c.returnState=0 and admin_id=$adminId)b
            where specifiedDate<='".$todayDate."'
            group by customer_id)a");
         //برای محاسبه اطلاعات تاریخچه بازاریابها
        //نصب‌ها---1
        $count_All_aghlam=0;
        $istallComTg="هیچکدام";
        $aghlamComTg="هیچکدام";
        $monyComTg="هیچکدام";
        $countBuyComTg="هیچکدام";
        //تارگت ها تکمیل شده
        $istallComTgBonus=0;
        $aghlamComTgBonus=0;
        $monyComTgBonus=0;
        $countBuyComTgBonus=0;
        //امتیاز های اختصاصی
        $istallBonus=0;
        $aghlamBonus=0;
        $monyBonus=0;
        $countBuyBonus=0;
        //امتیاز اضافی فرد
        $extarBonus=0;
        //مجموع کل امتیازات
        $allBonuses=0;
        //معیارهای مخصوص
        $specialBases=DB::select("SELECT * FROM CRM.dbo.crm_specialBonus");
        //تارگیت‌ها
        $targets=DB::select("SELECT * FROM CRM.dbo.crm_targets");
        //اطلاعات ادمین در حال تخلیه
        $adminInfo=DB::select("SELECT * FROM CRM.dbo.crm_admin where id=$adminId");
        //امتیاز اختصاصی و اضافی کاربر
        $extarBonus=$adminInfo[0]->extraBonus; 
        if(!$extarBonus){
            $extarBonus=0;
        }
        


        $count_All_Install=DB::select("SELECT count(id) as countAllInstall from( SELECT * from CRM.dbo.crm_inserted_customers WHERE Convert(date,crm_inserted_customers.addedDate)>=Convert(date,'$minDate'))a where  adminId=$adminId");
        $count_All_Install=$count_All_Install[0]->countAllInstall;


        //2--تعداد اقلام
        $count_All_aghlamR=DB::select("SELECT count(countGoods) as countAghlam,adminId from (			
                                        SELECT count(SnGood) as countGoods,adminId,SnGood from (
                                        SELECT * FROM (SELECT MAX(TimeStamp)as maxTime,SnGood,CustomerSn from(
                                        SELECT * FROM(
                                            SELECT FactorBYS.TimeStamp,FactorBYS.Fi,FactorBYS.Amount,FactorBYS.SnGood,CustomerSn FROM Shop.dbo.FactorHDS
                                            JOIN Shop.dbo.FactorBYS on FactorHDS.SerialNoHDS=FactorBYS.SnFact)a where CONVERT(DATE,TimeStamp)>=Convert(date,'$minDate')
                                            )g group by SnGood,CustomerSn)c
                                            join (select * from CRM.dbo.crm_inserted_customers)d on c.CustomerSn=d.customerId)f group by adminId,SnGood
                                            )e where e.adminId=$adminId group by adminId");
        if(count($count_All_aghlamR)>0){
            $count_All_aghlam=$count_All_aghlamR[0]->countAghlam;
        }

        //تعداد خرید اولیه
        $count_All_New_buys=DB::select("SELECT count(CustomerSn) as countNewFactor,adminId from (
            SELECT CustomerSn from (SELECT * from Shop.dbo.FactorHds where FactType=3 AND CONVERT(DATE,TimeStamp)>=Convert(date,'$minDate'))b group by CustomerSn having  COUNT(SerialNoHDS)>1 
            )c join CRM.dbo.crm_inserted_customers on c.CustomerSn=crm_inserted_customers.customerId where adminId=$adminId  group by adminId");

        if(count($count_All_New_buys)>0){
            $count_All_New_buys=$count_All_New_buys[0]->countNewFactor;
        }else{
            $count_All_New_buys=0;
        }
        //ارزیابی معیارهای اختصاصی
        foreach($specialBases as $special){
            if($special->id==11){//نصب امتیاز
                $istallBonus=((int)($count_All_Install/$special->limitAmount)) * $special->Bonus;
            }            
            if($special->id==12){//اقلام امتیاز
                $aghlamBonus=((int)($count_All_aghlam/$special->limitAmount)) * $special->Bonus;
            }
            if($special->id==13){//مبلغ امتیاز
                $countBuyBonus=((int)($count_All_New_buys/$special->limitAmount)) * $special->Bonus;
            }
            if($special->id==14){//خرید اولیه امتیاز
                $monyBonus=((int)($sumAllFactor/10/$special->limitAmount)) * $special->Bonus;
            }            

        }
        //ارزیابی تارگت‌ها
        foreach($targets as $target){
                //تارگت‌های نصب
            if($target->id==5){
                if($count_All_Install >= $target->thirdTarget){
                    $istallComTg="تارگیت سوم";
                    $istallComTgBonus=$target->thirdTargetBonus;
                }else{
                    if($count_All_Install >= $target->secondTarget){
                        $istallComTg="تارگیت دوم";
                        $istallComTgBonus=$target->scondTargetBonus;
                    }else{
                        if($count_All_Install >= $target->firstTarget){
                            $istallComTg="تارگیت اول";
                            $istallComTgBonus=$target->firstTargetBonus;
                        }
                    }
                }
            }
                //تارگت‌های تعداد خرید اولیه
            if($target->id==7){
                if($count_All_New_buys >= $target->thirdTarget){
                    $countBuyComTg="تارگیت سوم";
                    $countBuyComTgBonus=$target->thirdTargetBonus;
                }else{
                    if($count_All_New_buys >= $target->secondTarget){
                        $countBuyComTg="تارگیت دوم";
                        $countBuyComTgBonus=$target->thirdTargetBonus;
                    }else{
                        if($count_All_New_buys >= $target->firstTarget){
                            $countBuyComTg="تارگیت اول";
                            $countBuyComTgBonus=$target->thirdTargetBonus;
                        }
                    }
                }
            }
                //تارگت‌های مبلغ خرید
            if($target->id==8){
                if($sumAllFactor >= $target->thirdTarget){
                    $monyComTg="تارگیت سوم";
                    $monyComTgBonus=$target->thirdTargetBonus;
                }else{
                    if($sumAllFactor >= $target->secondTarget){
                        $monyComTg="تارگیت دوم";
                        $monyComTgBonus=$target->thirdTargetBonus;
                    }else{
                        if($sumAllFactor >= $target->firstTarget){
                            $monyComTg="تارگیت اول";
                            $monyComTgBonus=$target->thirdTargetBonus;
                        }
                    }
                }
            }
                //تارگت‌های اقلام خرید
            if($target->id==9){
                if($count_All_aghlam >= $target->thirdTarget){
                    $aghlamComTg="تارگیت سوم";
                    $aghlamComTgBonus=$target->thirdTargetBonus;
                }else{
                    if($count_All_aghlam >= $target->secondTarget){
                        $aghlamComTg="تارگیت دوم";
                        $aghlamComTgBonus=$target->thirdTargetBonus;
                    }else{
                        if($count_All_aghlam >= $target->firstTarget){
                            $aghlamComTg="تارگیت اول";
                            $aghlamComTgBonus=$target->thirdTargetBonus;
                        }
                    }
                }
            }
        }
        $allBonuses=$extarBonus+$istallBonus+$aghlamBonus+$monyBonus+$countBuyBonus+$istallComTgBonus+$aghlamComTgBonus+$monyComTgBonus+$countBuyComTgBonus;
            //ختم تارگت‌ها
        
        //ختم کد‌های بازاریابها
        //ثبت تاریخچه
        DB::table("CRM.dbo.crm_adminHistory")->insert(['adminId'=>$adminId,'countPeople'=>$exactInfo->countPeopels,'countFactor'=>$countAllFactor,'countBuyPeople'=>$boughtPeopelsCount,'factorAllMoney'=>$sumAllFactor
        ,'lastMonthAllMoney'=>$sumAllFactor2,'lastMonthReturnedAllMoney'=>$sumAllReturnedFactor,'meanIncrease'=>$meanIncreas,'comment'=>"".$adminComment."",'noCommentCust'=>$nocommentedCustomers,'noDoneWork'=>$countNoDoneWork[0]->countJob,
        'countReturnedFactor'=>$countAllReturnedFactor,'countLastMonthFactor'=>$countAllFactor2,'countLastMonthReturnedFactor'=>$countAllReturnedFactor2
        ,'allInstall'=>$count_All_Install
        ,'allPrimaryBuy'=>$count_All_New_buys
        ,'allAghlam'=>$count_All_aghlam
        ,'lastComInstallTg'=>"".$istallComTg.""
        ,'lastComPrimBuyTg'=>"".$aghlamComTg.""
        ,'lastComAghlamTg'=>"".$monyComTg.""
        ,'lastComMoneyTg'=>"".$countBuyComTg.""
        ,'installBonus'=> $istallBonus
        ,'primeBuyBonus'=> $countBuyBonus
        ,'totalMoneyBonus'=>$monyBonus
        ,'totalAghlamBonus'=>$aghlamBonus
        ,'lastComInstallTgB'=>$istallComTgBonus
        ,'lastComPrimBuyTgB'=>$aghlamComTgBonus
        ,'lastComAghlamTgB'=>$monyComTgBonus
        ,'lastComMoneyTgB'=>$countBuyComTgBonus
        ,'extraBonus'=>$extarBonus
        ,'allBonus'=>$allBonuses]);
        
        $customersToMove=DB::select("SELECT customer_id FROM CRM.dbo.crm_customer_added WHERE admin_id=".$giverAdmin." and returnState!=1");
        
        foreach ($customersToMove as $customer) {
            DB::table("CRM.dbo.crm_customer_added")->insert(['admin_id'=>$holderAdmin,'customer_id'=>$customer->customer_id,'returnState'=>0]);
        }
        
        DB::table("CRM.dbo.crm_customer_added")->where('admin_id',$giverAdmin)->update(['returnState'=>1,'removedTime'=>Carbon::now()]);
        DB::table("CRM.dbo.crm_admin")->where('id',$giverAdmin)->update(['emptyState'=>1]);
        DB::table("CRM.dbo.crm_admin")->where('id',$holderAdmin)->update(['emptyState'=>0]);
        return Response::json(1);
    }
        
    // ======================

    public function editAdmintStuff(Request $request)
    {
        $name=$request->post("name");
        $userName=$request->post("userName");
        $lastName=$request->post("lastName");
        $password=$request->post("password");
        $adminType=$request->post("adminType");
        $poshtibanType=$request->post("poshtibanType");
        $phone=$request->post("phone");
        $address=$request->post("address");
        $sex=$request->post("sex");
        $discription=$request->post("discription");
        $hasAsses=$request->post("hasAsses");
        $hasAllCustomer=$request->post("hasAllCustomer");
        $adminId=$request->post('adminId');

        if($request->file('picture')){
            $picture=$request->file('picture');
            $fileName=$adminId.".jpg";
            $picture->move("resources/assets/images/admins/",$fileName);
        }
        DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(['username'=>"".$userName."",'name'=>"".$name."",
                                            'lastName'=>"".$lastName."",'adminType'=>$adminType,'password'=>"".$password."",
                                            'activeState'=>1,'phone'=>$phone,'address'=>$address,'sex'=>"".$sex."",'discription'=>"".$discription."",'hasAsses'=>$hasAsses,'driverId'=>0,'poshtibanType'=>$poshtibanType,'hasAllCustomer'=>$hasAllCustomer,'bossId'=>$bossId]);
        
        return redirect("/assignCustomer");
    }
        
    // ======================

    public function editAdmintListStuff(Request $request)
    {
        $name=$request->post("name");
        $userName=$request->post("userName");
        $lastName=$request->post("lastName");
        $password=$request->post("password");
        $adminType=$request->post("adminType");
        $poshtibanType=$request->post("poshtibanType");
        $phone=$request->post("phone");
        $address=$request->post("address");
        $sex=$request->post("sex");
        $discription=$request->post("discription");
        $hasAsses=$request->post("hasAsses");
        $hasAlarm=$request->post("hasAlarm");
        if(!$hasAlarm){
            $hasAlarm="off";  
        }
        $hasAllCustomer=$request->post("hasAllCustomer");
        $adminId=$request->post('adminId');
        $bossId=0;
        $poshtibanType=$request->post("poshtibanType");
        $employeeType=$request->post("employeeType");
        $manager=$request->post("manager");
        $head=$request->post("head");
        $saleLine=$request->post("saleLine");
       // $bossId=0;
        $saleLineSn=0;

        if($manager){
            $bossId=$manager;  
        }

        if($head){
            $bossId=$head;  
        }
        
        if($saleLine){
            $saleLineSn=$saleLine;
        }


        if($request->file('picture')){
            $picture=$request->file('picture');
            $fileName=$adminId.".jpg";
            $picture->move("resources/assets/images/admins/",$fileName);
        }
        
        DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(['username'=>"".$userName."",'name'=>"".$name."",'lastName'=>"".$lastName."",'poshtibanType'=>$poshtibanType,
                                                                        'adminType'=>$adminType,'password'=>"".$password."",'activeState'=>1,'phone'=>$phone,
                                                                        'address'=>$address,'sex'=>"".$sex."",'discription'=>"".$discription."",'hasAsses'=>$hasAsses,'hasAllCustomer'=>$hasAllCustomer,'hasAlarm'=>$hasAlarm]);
        return redirect("/listKarbaran");
    }
    public function getHeads(Request $request)
    {
        $heads=DB::select("SELECT * FROM CRM.dbo.crm_admin where employeeType=2 and deleted=0");
        return Response::json($heads);
    }

    
    // ======================

    public function deleteAdmin(Request $request)
    {
        $asn=$request->get("asn");
        DB::table("CRM.dbo.crm_admin")->where("id",$asn)->update(['deleted'=>1]);
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('deleted',0)->where('crm_admin.adminType',2)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType","crm_admin.discription")->orderby("admintype")->get();
        return Response::json($admins);
    }
	    public function deleteAdmin1(Request $request)
    {
        $asn=$request->get("asn");
        DB::table("CRM.dbo.crm_admin")->where("id",$asn)->update(['deleted'=>1]);
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('deleted',0)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType","crm_admin.discription")->orderby("admintype")->get();
        return Response::json($admins);
    }
        
    // ======================

    public function changeAlarm(Request $request){
        $comment=$request->get("comment");
        $adminId=Session::get("asn");
        $alarmDate=$request->get("alarmDate");
        $factorId=$request->get("factorId");
        DB::table("CRM.dbo.crm_alarm")->where('factorId',$factorId)->update(['state'=>1]);
        DB::table("CRM.dbo.crm_alarm")->insert(['comment'=>"".$comment."",'adminId'=>$adminId,'state'=>0,'alarmDate'=>"".$alarmDate."",'factorId'=>$factorId]);
        $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
        
        $todayDate=Jalalian::fromCarbon(Carbon::now())->format('Y/m/d');
        $customers=DB::select("SELECT * FROM (
                                    SELECT * FROM (
                                        SELECT * FROM (
                                            SELECT * FROM (
                                                SELECT * FROM (
                                                    SELECT DISTINCT * FROM (
                                                        SELECT alarmDate, TimeStamp,factorId,state,adminId,comment,id from CRM.dbo.crm_alarm)a
                                                JOIN (SELECT factorId AS factorNumber FROM CRM.dbo.crm_assesment)b ON a.factorId=b.factorNumber)c
                                            JOIN (SELECT id AS admin_id,name AS AdminName,lastName FROM CRM.dbo.crm_admin)d ON c.adminId=d.Admin_id)e
                                        JOIN (SELECT SerialNoHDS,CustomerSn,NetPriceHDS FROM Shop.dbo.FactorHds )f ON f.SerialNoHDS=e.factorId)g
                                    JOIN (SELECT PSN,Name,CompanyNo,peopeladdress,GroupCode,SnMantagheh FROM Shop.dbo.Peopels)j ON j.PSN=g.CustomerSn)k
                                JOIN (SELECT SnMNM,NameRec FROM Shop.dbo.MNM WHERE  CompanyNo=5)l ON k.SnMantagheh=l.SnMNM)m

                                JOIN (select name poshtibanName,lastName as poshtibanLastName,customer_id from CRM.dbo.crm_customer_added join CRM.dbo.crm_admin on admin_id=crm_admin.id where returnState=0)n on m.CustomerSn=n.customer_id
                                join (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
                                FROM Shop.dbo.PhoneDetail
                                GROUP BY SnPeopel)a on n.customer_id=a.SnPeopel
                                WHERE  GroupCode IN ( ".implode(",",Session::get("groups")).") and CompanyNo=5  and alarmDate<'".$todayDate."' and state=0
                                and PSN in(SELECT customer_id FROM CRM.dbo.crm_customer_added where returnState=0)" );
        foreach ($customers as $customer) {
            $customer->assignedDays=\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d',trim($customer->alarmDate))->diffInDays($customer->TimeStamp);
			
            $customer->PassedDays=\Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d',trim($customer->alarmDate))->diffInDays(Carbon::now());
            
        }
        return Response::json($customers);
    }

    // ======================

    public function getAlarmHistory(Request $request)
    {
        $fsn=$request->get("fsn");
        $history=DB::table("CRM.dbo.crm_alarm")->where("factorId",$fsn)->get();
        return Response::json($history);
    }
    // ======================

    public function gotEmpty(Request $request)
    {
        $customers=DB::select("SELECT * FROM Shop.dbo.Peopels
                        JOIN (SELECT SnPeopel, STRING_AGG(PhoneStr, '-') AS PhoneStr
							FROM Shop.dbo.PhoneDetail
							GROUP BY SnPeopel)a on PSN=a.SnPeopel
                        where PSN not in ( SELECT distinct customer_id FROM CRM.dbo.crm_customer_added where returnState=0 and customer_id is not null)
        and PSN not in (SELECT customerId FROM CRM.dbo.crm_inactiveCustomer where customerId is not null and state=1)
        and PSN not in(SELECT customerId FROM CRM.dbo.crm_returnCustomer where customerId is not null and returnState=1)
        AND CompanyNo=5 AND IsActive=1
        AND GroupCode IN(291,297,299,312,313,314)");
            
        $admins=DB::table("CRM.dbo.crm_admin")->join("CRM.dbo.crm_adminType",'crm_adminType.id','=','crm_admin.adminType')->where('crm_admin.adminType','!=',1)->where('crm_admin.adminType','!=',4)->select("crm_admin.id","crm_admin.name","crm_admin.lastName","crm_admin.adminType as adminTypeId","crm_adminType.adminType")->get();
        
        return view('admin.gotEmpty',['customers'=>$customers,'admins'=>$admins]);
    }
        
    // ======================
    
    public function getAdminInfo(Request $request)
    {
        $id=$request->get("id");
        $appositId=$id;
        $myId=Session::get('asn');
        $admin=DB::table("CRM.dbo.crm_admin")->where('id',$id)->first();
        $sendedMessages=DB::select("SELECT *,DATEDIFF (day,EndDate,messageDate) as diffDate from(
            SELECT *,Lag(messageDate, 1) OVER(
                   ORDER BY messageDate ASC) AS EndDate from(
            SELECT * FROM (
                                            SELECT * FROM(
                                            SELECT * FROM CRM.dbo.crm_message)a
                                            JOIN   (SELECT name,id as adminId FROM CRM.dbo.crm_admin)c ON a.senderId=c.adminId )b)c
            
                                            WHERE  (senderId=".$myId." and getterId=".$appositId.") or (senderId=".$appositId." and getterId=".$myId."))d order by messageDate desc");
        
        DB::update("UPDATE CRM.dbo.crm_message set readState=1 WHERE senderId=".$appositId." and getterId=".$myId);
        
        return Response::json([$sendedMessages,$appositId,$myId,$admin]);
    }
    
    // ======================

    public function getDiscusstion(Request $request)
    {
        $appositId=$request->get("sendId");
        $myId=Session::get('asn');
        $sendedMessages=DB::select("select *,DATEDIFF (day,EndDate,messageDate) as diffDate from(

            select *,Lag(messageDate, 1) OVER(
                   ORDER BY messageDate ASC) AS EndDate from(
            SELECT * FROM (
                                            SELECT * FROM(
                                            SELECT * FROM CRM.dbo.crm_message)a
                                            JOIN   (SELECT name,id as adminId FROM CRM.dbo.crm_admin)c ON a.senderId=c.adminId )b)c
            
                                            WHERE  (senderId=".$myId." and getterId=".$appositId.") or (senderId=".$appositId." and getterId=".$myId."))d order by messageDate desc");
              
        DB::update("UPDATE CRM.dbo.crm_message set readState=1 WHERE senderId=".$appositId." and getterId=".$myId);
              
        return Response::json([$sendedMessages,$appositId,$myId]);
    }
    
    // ======================

    public function addMessage(Request $request)
    {
        $senderId=Session::get("asn");
        $getterId=$request->get("getterId");
        $messageContent=$request->get("messageContent");
        DB::table("CRM.dbo.crm_message")->insert(['messageContent'=>"".$messageContent."",'readState'=>0,'senderId'=>$senderId,'getterId'=>$getterId]);
        $sendedMessages=DB::select("SELECT * FROM (
                                SELECT * FROM(
                                SELECT * FROM CRM.dbo.crm_message)a
                                JOIN   (SELECT name,id as adminId FROM CRM.dbo.crm_admin)c ON a.senderId=c.adminId )b
                                WHERE  (senderId=".$senderId." and getterId=".$getterId.") or (senderId=".$getterId." and getterId=".$senderId.") ORDER BY     messageDate desc");
        return Response::json([$sendedMessages,$getterId,$senderId]);
    }
    
    // ======================

    public function addDiscussion(Request $request)
    {
        $senderId=Session::get("asn");
        $getterId=$request->get("getterId");
        $messageContent=$request->get("messageArea");
        DB::table("CRM.dbo.crm_message")->insert(['messageContent'=>"".$messageContent."",'readState'=>0,'senderId'=>$senderId,'getterId'=>$getterId]);
        $sendedMessages=DB::select("								select *,DATEDIFF (day,EndDate,messageDate) as diffDate from(

            select *,Lag(messageDate, 1) OVER(
                   ORDER BY messageDate ASC) AS EndDate from(
            SELECT * FROM (
                                            SELECT * FROM(
                                            SELECT * FROM CRM.dbo.crm_message)a
                                            JOIN   (SELECT name,id as adminId FROM CRM.dbo.crm_admin)c ON a.senderId=c.adminId )b)c
            WHERE  (senderId=".$senderId." and getterId=".$getterId.") or (senderId=".$getterId." and getterId=".$senderId."))d  ORDER BY messageDate desc");
        return Response::json([$sendedMessages,$getterId,$senderId]);
    }
    
    // ======================

    public function addAlarmClock(Request $request)
    {
        $comment=$request->get("comment");
        $adminId=Session::get("asn");
        $dateTime=$request->get("dateTime");
        DB::table("CRM.dbo.crm_alarmClock")->insert(['comment'=>"".$comment."",'TimeStamp'=>"".$dateTime."",'adminId'=>$adminId,'doneState'=>0]);
        return Response::json("1");
    }
        
    // ======================

    public function getAlarmTime(Request $request)
    {
        $adminId=Session::get("asn");
        $nowTime=Carbon::now();
        $alarmTime=DB::table("CRM.dbo.crm_alarmClock")->where('doneState',0)->where('adminId',$adminId)->first();
        $alarmTime1=list($t,$d)=explode(" ",$alarmTime->TimeStamp);
        $newTime=$d.' '.$t;
        $alarmTime2=Jalalian::fromFormat('Y/m/d H:i:s', $newTime)->toCarbon();
        $result=$nowTime->gte($alarmTime2);
        $diff = $nowTime->diffInSeconds($alarmTime2);
        return Response::json([$result,$diff,$newTime,$alarmTime]);
    }
        
    // ======================

    public function offAlarmClock(Request $request)
    {
        $adminId=Session::get('asn');
        DB::table("CRM.dbo.crm_alarmClock")->where("adminId",$adminId)->update(["doneState"=>1]);
        return Response::json(1);
    }

    // ======================

    // public function visitorReport(){
    //     $visitors=DB::select("SELECT * FROM (
    //             SELECT CONVERT(date,lastVisit) as lastV,lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
    //             SELECT * FROM(
    //             SELECT * FROM(
    //             SELECT * FROM(
    //             SELECT * FROM(
    //             SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
    //             JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
    //             ON a.customerId=b.PSN)c
    //             JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
    //             JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
    //             JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
    //             LEFT JOIN (SELECT count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN)j
    //             WHERE lastV=Convert(date,getDate())
    //             order by lastVisit desc");
        
    //     return view("admin.visitorReport",['visitors'=>$visitors]);
    // }
    public function searchVisotrsByDate(Request $request)
    {
        $firstDate=Jalalian::fromFormat('Y/m/d', $request->get("firstDate"))->toCarbon()->format('Y-m-d');
        $secondDate=Jalalian::fromFormat('Y/m/d',$request->get("secondDate"))->toCarbon()->format('Y-m-d');

        $visitors=DB::select("select * from(SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime,CONVERT(date,lastVisit) AS lastVis FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
            JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
            ON a.customerId=b.PSN)c
            JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
            JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
            JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
            left join (select count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN)a
            where a.lastVis>='$firstDate' and a.lastVis<='$secondDate'
            order by lastVisit desc");
        return Response::json($visitors);
    }

    public function searchVisotrsLoginFrom(Request $request)
    {
        $loginFrom=$request->get("loginFrom");

        $visitors=DB::select("SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
            JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
            ON a.customerId=b.PSN)c
            JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
            JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
            JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
            left join (select count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN
            where countLogin >= $loginFrom
            order by lastVisit desc ");
        return Response::json($visitors);
    }

    public function searchVisotrsLoginTo(Request $request)
    {
        $loginTo=$request->get("loginTo");

        $visitors=DB::select("SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
            JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
            ON a.customerId=b.PSN)c
            JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
            JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
            JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
            left join (select count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN
            where countLogin <= $loginTo
            order by lastVisit desc ");
        return Response::json($visitors);
    }
    public function searchVisotrsPlatform(Request $request)
    {
        $platform=$request->get("platform");
        if($platform !=0){
        $visitors=DB::select("SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
            JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
            ON a.customerId=b.PSN)c
            JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
            JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
            JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
            left join (select count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN
            where platform ='$platform'
            order by lastVisit desc ");
            return Response::json($visitors);
        }else{
            $visitors=DB::select("SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
                SELECT * FROM(
                SELECT * FROM(
                SELECT * FROM(
                SELECT * FROM(
                SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
                JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
                ON a.customerId=b.PSN)c
                JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
                JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
                JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
                left join (select count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN
                order by lastVisit desc ");
            return Response::json($visitors);
        }
    }
    public function searchSameTimeCountLogin(Request $request)
    {
        $contSameTimeLogin=$request->get('countSameTimeLogin');
        $visitors=DB::select("SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit,visitDate,countSameTime FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT * FROM(
            SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
            JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
            ON a.customerId=b.PSN)c
            JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
            JOIN   (SELECT visitDate,browser,platform,customerId as cid FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
            JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
            left join (select count(customerId) as countSameTime,customerId from NewStarfood.dbo.star_customerSession1 group by customerId)j on j.customerId=i.PSN
            where countSameTime>=$contSameTimeLogin
            order by lastVisit desc ");
            return Response::json($visitors);
    }
        
    // ======================

    public function getCustomerLoginInfo(Request $request)
    {
        $searchTerm=$request->get("searchTerm");
        $visitors=DB::select("SELECT lastVisit,PSN,countLogin,Name,platform,browser,firstVisit FROM(
                        SELECT * FROM(
                        SELECT * FROM(
                        SELECT * FROM(
                        SELECT * FROM(
                        SELECT MAX(visitDate) as lastVisit,customerId FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)a
                        JOIN   (SELECT Name,PSN,GroupCode FROM Shop.dbo.Peopels)b
                        ON a.customerId=b.PSN)c
                        JOIN   (SELECT COUNT(id) as countLogin,customerId as csn FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)d ON c.customerId=d.csn)e
                        JOIN   (SELECT visitDate,browser,platform FROM NewStarfood.dbo.star_customerTrack)f ON e.lastVisit=f.visitDate)g
                        JOIN   (SELECT MIN(visitDate) as firstVisit,customerId as CUSTOMERID2 FROM NewStarfood.dbo.star_customerTrack GROUP BY    customerId)h ON g.PSN=h.CUSTOMERID2)i
                        WHERE Name LIKE '%".$searchTerm."%'");
        
        return Response::json($visitors);
    }

    public function tempRoute(Request $request)
    {
        $drivers=DB::table("Shop.dbo.sla_Drivers")->get();
        $i=8;
        foreach ($drivers as $driver) {
            $i++;
            $userName='driver'.$i;
            DB::table("CRM.dbo.crm_admin")->insert(['username'=>"".$userName."",'name'=>"".$driver->NameDriver."",'lastName'=>"",'adminType'=>4,'password'=>"111",'activeState'=>1,'phone'=>'0910','address'=>"آدرس",'sex'=>1,'discription'=>"دستی وارد شده است",'hasAsses'=>0,'driverId'=>$driver->SnDriver,'hasAllCustomer'=>0]);
        }
        
        return $drivers;
        $adminId=19;
        $countAllCommentedCustomers=DB::select("select COUNT(customerId) AS countComment from(
            select distinct customerId from CRM.dbo.crm_comment where adminId=$adminId and TimeStamp>=(select min(addedTime) from CRM.dbo.crm_customer_added where returnState=1 and admin_id=$adminId and customer_id not in(select customerId from CRM.dbo.crm_returnCustomer where returnState=1)
            and customer_id not in(select customerId from CRM.dbo.crm_inactiveCustomer where state=1)
            )
            and TimeStamp<=(select max(removedTime) from CRM.dbo.crm_customer_added where returnState=1 and admin_id=$adminId and customer_id not in(select customerId from CRM.dbo.crm_returnCustomer where returnState=1)
            and customer_id not in(select customerId from CRM.dbo.crm_inactiveCustomer where state=1)
            )
            )a ");
        $allActiveCustomerCount=DB::select("SELECT COUNT(PSN) as countActiveCustomers FROM Shop.dbo.Peopels WHERE  PSN in(SELECT customer_id FROM CRM.dbo.crm_customer_added WHERE  returnState=1  and admin_id=$adminId
        and PSN not in(select customerId from CRM.dbo.crm_returnCustomer where returnState=1)
            and PSN not in(select customerId from CRM.dbo.crm_inactiveCustomer where state=1)
        ) and Peopels.CompanyNo=5 and Peopels.GroupCode IN ( ".implode(",",Session::get("groups")).")");
        $nocommentedCustomers=$allActiveCustomerCount[0]->countActiveCustomers - $countAllCommentedCustomers[0]->countComment;
        if($nocommentedCustomers<0){
            $nocommentedCustomers=0; 
        }
        $todayDate=Carbon::now()->format('Y-m-d');
        $countNoDoneWork=\DB::select("select sum(countJob) as countJob from(
                                    select COUNT(id) as countJob,specifiedDate from (
                                    select * from (
                                    select crm_workList.commentId,crm_workList.id,crm_workList.doneState,crm_workList.specifiedDate,crm_comment.customerId from CRM.dbo.crm_workList join CRM.dbo.crm_comment on crm_workList.commentId=crm_comment.id where doneState=0)a
                                    join (select customer_id,returnState,admin_id from CRM.dbo.crm_customer_added )c on a.customerId =c.customer_id where c.returnState=0 and admin_id=".$adminId.")b
                                    where specifiedDate<='".$todayDate."'
                                    group by specifiedDate)a");
        return $countNoDoneWork[0]->countJob;
// 'noCommentCust'=>$nocommentedCustomers,
        DB::table("CRM.dbo.crm_adminHistory")->where('adminId',$adminId)->update(['noDoneWork'=>$countNoDoneWork[0]->countJob]);
    }
	
    public function downloadApk(Request $request)
    {
        $headers =  [
                'Content-Type'=>'application/vnd.android.package-archive',
                'Content-Disposition'=> 'attachment; filename="starfood.apk"',
            ];
       // return response()->download(base_path('resources\\assets\\apks\\starfood.apk'));
        return response()->file(base_path('resources\\assets\\apks\\starfoodCRM.apk') , $headers);
    }

    public function addUpDownBonus(Request $request)
    {
        $positiveBonus=$request->get("positive");
        $negativeBonus=$request->get("negative");
        if(!$positiveBonus){
            $positiveBonus=0;
        }
        if(!$negativeBonus){
            $negativeBonus=0;
        }
        $discriptionBonus=$request->get("discription");
        $adminId=$request->get("adminId");
        DB::table("CRM.dbo.crm_adminUpDownBonus")
                    ->insert(["positiveBonus"=>$positiveBonus
                    ,"negativeBonus"=>$negativeBonus
                    ,"discription"=>"$discriptionBonus"
                    ,"adminId"=>$adminId]);

                    return Response::json("goood");
    }
    public function showAdminEmtyazHistory(Request $request)
    {
        $adminId=$request->get("adminID");
        $adminHistory=DB::select("SELECT name,lastName,TimeStamp,positiveBonus,negativeBonus,crm_adminUpDownBonus.discription,crm_adminUpDownBonus.id FROM CRM.dbo.crm_admin JOIN CRM.dbo.crm_adminUpDownBonus  ON crm_admin.id=crm_adminUpDownBonus.adminId WHERE isUsed=0 AND adminId=$adminId");
        return Response::json($adminHistory);
    }
    public function getAdminHistory(Request $request)
    {
       $historyID=$request->get("historyID");
       $history=DB::table("CRM.dbo.crm_adminUpDownBonus")->where("id",$historyID)->get();
       return Response::json($history);
    }
    public function editEmtiyazHistory(Request $request)
    {
        $negativeEmtiyasEdit=$request->get("negativeEmtiyasEdit");
        $historyIDEmtiyasEdit=$request->get("historyIDEmtiyasEdit");
        $positiveEmtiyasEdit=$request->get("positiveEmtiyasEdit");
        $discriptionEmtiyasEdit=$request->get("discriptionEmtiyasEdit");
        $adminId=$request->get("adminId");
        DB::table("CRM.dbo.crm_adminUpDownBonus")->where("id",$historyIDEmtiyasEdit)
                                                            ->update(["positiveBonus"=>$positiveEmtiyasEdit
                                                            ,"negativeBonus"=>$negativeEmtiyasEdit
                                                            ,"discription"=>"$discriptionEmtiyasEdit"]);

        $adminHistory=DB::select("SELECT name,lastName,TimeStamp,positiveBonus,negativeBonus,
                                crm_adminUpDownBonus.discription,crm_adminUpDownBonus.id FROM CRM.dbo.crm_admin JOIN CRM.dbo.crm_adminUpDownBonus
                                ON crm_admin.id=crm_adminUpDownBonus.adminId WHERE isUsed=0 AND adminId=$adminId");

        return Response::json($adminHistory);
    }
    public function addToHeadEmployee(Request $request)
    {
        $adminIDs=$request->get("adminID");
        $headId=$request->get("headId");
        foreach($adminIDs as $adminId){
            DB::table("CRM.dbo.crm_admin")->where("id",$adminId)->update(['bossId'=>$headId]);
        }
        return Response::json("good");
    }

}
