<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start(); 

class BrandProduct extends Controller
{

    //START FUNCTION FOR ADMIN
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dash-board');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function add_brand_product(){
        $this->AuthLogin();
        return view('admin.add_brand_product');
    }

    public function all_brand_product(){
        $this->AuthLogin();
        $all_brand_product =  DB::table('tbl_brand')->get();
        $manager_brand_product = view('admin.all_brand_product')->with('all_brand_product', $all_brand_product);
      return view('admin_layout')->with('admin.all_brand_product', $manager_brand_product);
    }

    public function save_brand_product(Request $request ){
        $this->AuthLogin();
        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $data['meta_keyword'] = $request->brand_product_keyword;
        $data['brand_desc'] = $request->brand_product_desc;
        $data['brand_status'] = $request->brand_product_status;
        
        DB::table('tbl_brand')->insert($data);
        Session::put('message','Thêm danh mục thương hiệu thành công');
        return Redirect::to('add-brand-product');
    }

    public function unactive_brand_product($brand_product_id){
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id', $brand_product_id)->update(['brand_status' => 0]);
        Session::put('message','Không kích hoạt danh mục thương hiệu thành công');
        return Redirect::to('all-brand-product');
    }

    public function active_brand_product($brand_product_id){
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id', $brand_product_id)->update(['brand_status' => 1]);
        Session::put('message','Kích hoạt danh mục thương hiệu thành công');
        return Redirect::to('all-brand-product');
    }

    public function edit_brand_product($brand_product_id){
        $this->AuthLogin();
        //LẤY ĐÚNG 1 THƯƠNG HIỆU THUỘC ID ĐÓ THÔI  
        $edit_brand_product =  DB::table('tbl_brand')->where('brand_id', $brand_product_id)->get();
        $manager_brand_product = view('admin.edit_brand_product')->with('edit_brand_product', $edit_brand_product);
        return view('admin_layout')->with('admin.edit_brand_product', $manager_brand_product);
    }

    public function update_brand_product(Request $request, $brand_product_id){
        $this->AuthLogin();
        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $data['meta_keyword'] = $request->brand_product_keyword;
        $data['brand_desc'] = $request->brand_product_desc;
        DB::table('tbl_brand')->where('brand_id', $brand_product_id)->update($data);
        Session::put('message','Chỉnh sửa thương hiệu thành công');
        return Redirect::to('all-brand-product');
    }

    public function delete_brand_product($brand_product_id){
        $this->AuthLogin();
        DB::table('tbl_brand')->where('brand_id', $brand_product_id)->delete();
        Session::put('message','Xóa thương hiệu thành công');
        //TRẢ VỀ TRANG CHỨA THƯƠNG HIỆU
        return Redirect::to('all-brand-product');
    }
    //END FUNTION FOR ADDMIN

    //FUNCTION OTHER
    public function show_brand_home(Request $request, $brand_id){
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();

        $brand_by_id =DB::table('tbl_product')
        ->join('tbl_brand','tbl_product.brand_id','=','tbl_brand.brand_id')
        ->where('tbl_product.brand_id',$brand_id)
        ->where('tbl_product.product_status','1')
        ->get();

        //CHỈ LẤY 1 NAME
        $brand_single_name = DB::table('tbl_brand')
            ->where('tbl_brand.brand_id', $brand_id)
            ->limit(1)
            ->get();

            $meta_desc = '';
            $meta_keyword ='';
            $meta_title = '';
            $url_canonical = '';
        foreach ($brand_by_id as $key => $value) {
            $meta_desc = $value->brand_desc;
            $meta_keyword = $value->meta_keyword;
            $meta_title = $value->brand_name;
            $url_canonical = $request->url();
        }


        return view('pages.brand.show_brand')   
            ->with('category',$cate_product)   
            ->with('brand',$brand_product)  
            ->with('brand_by_id', $brand_by_id)
            ->with('brand_single_name',$brand_single_name)
            ->with('meta_desc', $meta_desc)
            ->with('meta_keyword', $meta_keyword)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', $url_canonical);
    }    
    //END FUNCTION OTHER
}
