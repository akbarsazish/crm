<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Response;
use DB;
class Product extends Controller
{
    public function getProducts(Request $request)
    {
        $products=DB::table("Shop.dbo.PubGoods")->where("GoodSn","!=",0)->where("GoodName","!=","")->where("GoodGroupSn",">","69")->get();
        return Response::json($products);
    }
}
