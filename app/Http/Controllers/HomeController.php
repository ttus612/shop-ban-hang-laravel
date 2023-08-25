<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start(); 

class HomeController extends Controller{
  
    public function index(Request $request){
        //SEO 
        $meta_desc = "Chuyên bán linh kiện điện tử";
        $meta_keyword = "laptop, ram, man hinh, keybord";
        $meta_title = "Linh kiện";
        $url_canonical = $request->url();

        //--SEO
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();

        // $all_product =  DB::table('tbl_product')
        // ->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')
        // ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        // ->orderby('tbl_product.product_id','desc')->get();

        $all_product = DB::table('tbl_product')->where('product_status', '1')->orderby('product_id','desc')->get();

        return view('pages.home')
        ->with('category',$cate_product)
        ->with('brand',$brand_product)
        ->with('all_product', $all_product)
        ->with('meta_desc', $meta_desc)
        ->with('meta_keyword', $meta_keyword)
        ->with('meta_title', $meta_title)
        ->with('url_canonical', $url_canonical);
    }

    public function search(Request $request){
        $keywords = $request->keywords_submit;
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();
        $search_product = DB::table('tbl_product')
                        ->where('product_name', 'like', '%'.$keywords.'%')
                        ->get();

        $meta_desc = 'Danh sách sản phẩm';
        $meta_keyword = 'Danh sách sản phẩm';
        $meta_title = 'Danh sách sản phẩm';
        $url_canonical = $request->url();

        return view('pages.sanpham.search')->with('category',$cate_product)->with('brand',$brand_product)->with('search_product', $search_product)
                  ->with('meta_desc', $meta_desc)
                    ->with('meta_keyword', $meta_keyword)
                    ->with('meta_title', $meta_title)
                    ->with('url_canonical', $url_canonical);
    }
}
