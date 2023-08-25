<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
use Cart;
session_start(); 

class CheckoutController extends Controller
{

    public function AuthLogin(){
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return Redirect::to('dash-board');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function login_checkout(Request $request){
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();
    
        $meta_desc = 'Đăng nhập vào web site';
        $meta_keyword ='checkout, login, register';
        $meta_title = 'Đăng nhập & Đăng kí';
        $url_canonical = $request->url();
       

        return view('pages.checkout.login_checkout')
            ->with('category',$cate_product)
            ->with('brand',$brand_product)
            ->with('meta_desc', $meta_desc)
            ->with('meta_keyword', $meta_keyword)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', $url_canonical);;
    }

    public function add_customer(Request $request){
        $data = array();
        $data['customer_name'] = $request->customer_name;
        $data['customer_email'] = $request->customer_email;
        $data['customer_password'] = md5($request->customer_password);
        $data['customer_phone'] = $request->customer_phone;

        $customer_id = DB::table('tbl_customers')->insertGetId($data);

        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->customer_name);    
        return Redirect::to('/checkout'); 
    }

    public function checkout(Request $request){

       $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();
        $meta_desc = 'Thanh toán';
        $meta_keyword ='Thanh toán';
        $meta_title = 'Thanh toán';
        $url_canonical = $request->url();

        return view('pages.checkout.show_checkout')->with('category',$cate_product)->with('brand',$brand_product)   ->with('meta_desc', $meta_desc)
                ->with('meta_keyword', $meta_keyword)
                ->with('meta_title', $meta_title)
                ->with('url_canonical', $url_canonical);
    }

    public function save_checkout_customer(Request $request){
        $data = array();
        $data['shipping_name'] = $request->shipping_name;
        $data['shipping_email'] = $request->shipping_email;
        $data['shipping_phone'] = $request->shipping_phone;
        $data['shipping_address'] = $request->shipping_address;
        $data['shipping_notes'] = $request->shipping_notes;

        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);

        Session::put('shipping_id', $shipping_id); 
        return Redirect::to('/payment'); 
    }

    public function payment(Request $request){
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();
        
        $meta_desc = 'Thanh toán';
        $meta_keyword ='Thanh toán';
        $meta_title = 'Thanh toán';
        $url_canonical = $request->url();

        return view('pages.checkout.payment')->with('category',$cate_product)->with('brand',$brand_product)   
                ->with('meta_desc', $meta_desc)
                ->with('meta_keyword', $meta_keyword)
                ->with('meta_title', $meta_title)
                ->with('url_canonical', $url_canonical);
    }

    public function logout_checkout(){
        // Xóa tất cả dữ liệu khỏi phiên làm việc hiện tại
        Session::flush();
        return Redirect::to('/login-checkout');
    }

    public function login_customer(Request $request){
        $email = $request->email_account;
        $password = md5($request->password_account);
        $result = DB::table('tbl_customers')
        ->where('customer_email', $email)
        ->where('customer_password', $password)
        ->first();
     
        if ($result) {
            Session::put('customer_id', $result->customer_id);    
            return Redirect::to('/checkout');
        } else {
             return Redirect::to('/login-checkout'); 
        }
    }

    public function order_place(Request $request){ 
        //Insert payment method
        $payment_data = array();
        $payment_data['payment_method'] = $request->payment_option;
        $payment_data['payment_status'] = 'Đang chờ xử lí';
        $payment_id = DB::table('tbl_payment')->insertGetId($payment_data);

        //Insert order
        $order_data = array();
        $order_data['customer_id'] = Session::get('customer_id');
        $order_data['shipping_id'] = Session::get('shipping_id');
        $order_data['payment_id'] = $payment_id;
        $order_data['order_total'] = Cart::getTotal();
        $order_data['order_status'] = "Đang chờ xử lí";
        $order_id = DB::table('tbl_order')->insertGetId($order_data);

        //Insert order details
        $content = Cart::getContent();
        foreach ($content as $key => $value) {
            $order_details_data['order_id'] = $order_id;
            $order_details_data['product_id'] = $value->id;
            $order_details_data['product_name'] = $value->name;
            $order_details_data['product_price'] = $value->price;
            $order_details_data['product_sales_quantity'] = $value->quantity;
            DB::table('tbl_order_details')->insert($order_details_data); 

        }
        $meta_desc = 'Xác nhận thanh toán';
        $meta_keyword ='Thanh toán';
        $meta_title = 'Xác nhận thanh toán';
        $url_canonical = $request->url();

        if ($payment_data['payment_method'] == 1) {
            echo 'Thanh toán thẻ ATM';
        }else if ($payment_data['payment_method'] == 2) {
            $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
            $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();

            
            Cart::clear(); 
            return view('pages.checkout.hashcash')->with('category',$cate_product)->with('brand',$brand_product)    ->with('meta_desc', $meta_desc)
                    ->with('meta_keyword', $meta_keyword)
                    ->with('meta_title', $meta_title)
                    ->with('url_canonical', $url_canonical);
        }else{
            echo 'Thẻ ghi nợ';
        }
  
    }


    public function manager_order(){
        $this->AuthLogin();
        $all_order =  DB::table('tbl_order')
        ->join('tbl_customers','tbl_order.customer_id','=','tbl_customers.customer_id')
        ->select('tbl_order.*','tbl_customers.customer_name')
        ->orderby('tbl_order.order_id','desc')
        ->get();
        $manager_order =view('admin.manager_order')->with('all_order', $all_order);
        return view('admin_layout')->with('admin.manager_order', $manager_order);
    }

    public function view_order($orderId){
        $this->AuthLogin();
        $order_by_id =  DB::table('tbl_order')
        ->join('tbl_customers','tbl_order.customer_id','=','tbl_customers.customer_id')
        ->join('tbl_shipping','tbl_order.shipping_id','=','tbl_shipping.shipping_id')
        ->join('tbl_order_details','tbl_order.order_id','=','tbl_order_details.order_id')
        ->select('tbl_order.*','tbl_customers.*','tbl_shipping.*','tbl_order_details.*')
        ->first();
        // echo '<pre>';
        //     print_r($order_by_id);
        // echo '</pre>';
    
        $manager_order_by_id =view('admin.view_order')->with('order_by_id', $order_by_id);
        return view('admin_layout')->with('admin.view_order', $manager_order_by_id);
   
    }
}
