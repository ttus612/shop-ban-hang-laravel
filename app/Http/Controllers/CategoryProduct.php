<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start(); 

class CategoryProduct extends Controller
{


    
    //START FUNCTIONS FOR ADMIN
    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dash-board');
        }else{
            return Redirect::to('admin')->send();
        }
    }


    public function add_category_product(){
        $this->AuthLogin();
        return view('admin.add_category_product');
    }

    public function all_category_product(){
              $this->AuthLogin();
        $all_category_product =  DB::table('tbl_category_product')->get();
        $manager_category_product = view('admin.all_category_product')->with('all_category_product', $all_category_product);
      return view('admin_layout')->with('admin.all_category_product', $manager_category_product);
    }

    public function save_category_product(Request $request ){
        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['meta_keyword'] = $request->category_product_keyword;
        $data['category_desc'] = $request->category_product_desc;
        $data['category_status'] = $request->category_product_status;
        
        DB::table('tbl_category_product')->insert($data);
        Session::put('message','Thêm danh mục sản phẩm thành công');
        return Redirect::to('add-category-product');
    }

    public function unactive_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id', $category_product_id)->update(['category_status' => 0]);
        Session::put('message','Không kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }

    public function active_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id', $category_product_id)->update(['category_status' => 1]);
        Session::put('message','Kích hoạt danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }

    public function edit_category_product($category_product_id){
        $this->AuthLogin();
        //LẤY ĐÚNG 1 SẢN PHẨM THUỘC ID ĐÓ THÔI  
        $edit_category_product =  DB::table('tbl_category_product')->where('category_id', $category_product_id)->get();
        $manager_category_product = view('admin.edit_category_product')->with('edit_category_product', $edit_category_product);
        return view('admin_layout')->with('admin.edit_category_product', $manager_category_product);
    }

    public function update_category_product(Request $request, $category_product_id){
        $this->AuthLogin();
        $data = array();
        $data['category_name'] = $request->category_product_name;
        $data['meta_keyword'] = $request->category_product_keyword;
        $data['category_desc'] = $request->category_product_desc;
        DB::table('tbl_category_product')->where('category_id', $category_product_id)->update($data);
        Session::put('message','Chỉnh sửa danh mục sản phẩm thành công');
        return Redirect::to('all-category-product');
    }

    public function delete_category_product($category_product_id){
        $this->AuthLogin();
        DB::table('tbl_category_product')->where('category_id', $category_product_id)->delete();
        Session::put('message','Xóa sản danh mục phẩm thành công');
        //TRẢ VỀ TRANG CHỨA SẢN PHẨM
        return Redirect::to('all-category-product');
    }
    //END FUNCTIONS FOR ADMIN

    //START FUNCTIONS OTHER
    public function show_category_home(Request $request,$category_id){

        $cate_product = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderby('category_id', 'desc')
            ->get();

        $brand_product = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderby('brand_id', 'desc')
            ->get();

        $category_by_id =DB::table('tbl_product')
            ->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')
            ->where('tbl_product.category_id',$category_id)
            ->where('tbl_product.product_status','1')
            ->get();

        //CHỈ LẤY 1 NAME
        $category_single_name = DB::table('tbl_category_product')
            ->where('tbl_category_product.category_id', $category_id)
            ->limit(1)
            ->get();

            $meta_desc = '';
            $meta_keyword ='';
            $meta_title = '';
            $url_canonical = '';
        foreach ($category_by_id as $key => $value) {
            $meta_desc = $value->category_desc;
            $meta_keyword = $value->meta_keyword;
            $meta_title = $value->category_name;
            $url_canonical = $request->url();
        }

        return view('pages.category.show_category')
            ->with('category',$cate_product)
            ->with('brand',$brand_product)
            ->with('category_by_id', $category_by_id)
            ->with('category_single_name', $category_single_name)
            ->with('meta_desc', $meta_desc)
            ->with('meta_keyword', $meta_keyword)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', $url_canonical);
    }



    //END FUNCTIONS OTHER
}
