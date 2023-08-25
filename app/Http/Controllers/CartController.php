<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Illuminate\Support\Facades\Redirect;
use Cart;
session_start(); 

class CartController extends Controller
{
    public function save_cart(Request $request){

        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();

        $productId = $request->productid_hidden;
        $quantity = $request->qty;

        $product_info = DB::table('tbl_product')
        ->where('product_id', $productId)
        ->first();
        
        $saleCondition = new \Darryldecode\Cart\CartCondition(array(
            'name' => 'TAX 5%',
            'type' => 'tax',
            'value' => '5%',
        ));

        $data['id'] = $product_info->product_id;
        $data['quantity'] = $quantity;
        $data['name'] = $product_info->product_name;
        $data['price'] = $product_info->product_price;
        $data['attributes']['image'] = $product_info->product_image;
        $data['conditions'] =$saleCondition;
        Cart::add(array($data));




        return Redirect::to('/show-cart');
    }

    public function show_cart(Request $request ){
        $cate_product = DB::table('tbl_category_product')->where('category_status', '1')->orderby('category_id', 'desc')->get();
        $brand_product = DB::table('tbl_brand')->where('brand_status', '1')->orderby('brand_id', 'desc')->get();

        $meta_desc = 'Giỏ hàng';
        $meta_keyword ='gio hang, giỏ hàng';
        $meta_title = 'Giỏ hàng';
        $url_canonical = $request->url();


        return view('pages.cart.show_cart')
           ->with('category',$cate_product)
           ->with('brand', $brand_product)
            ->with('meta_desc', $meta_desc)
            ->with('meta_keyword', $meta_keyword)
            ->with('meta_title', $meta_title)
            ->with('url_canonical', $url_canonical);      
    }

    public function delete_cart( $cartID){
        Cart::remove($cartID);
        return Redirect::to('/show-cart');
    }

    public function update_cart_quantity(Request $request){
        $cartID = $request->cartID;
        $quantity = $request->cartQuantity;
        // Cart::update($cartID, array(
        // 'quantity'=> $quantity

        Cart::update($cartID, array(
          'quantity' => array(
              'relative' => false,
              'value' => $quantity
          ),
        ));

        return Redirect::to('/show-cart');
    }
}
