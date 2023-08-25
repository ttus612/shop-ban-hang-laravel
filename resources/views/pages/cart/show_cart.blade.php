@extends('welcome')
@section('content') 
<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
				  <li class="active">Giỏ hàng của bạn</li>
				</ol>
			</div>
			<div class="table-responsive cart_info">
				<?php 
				   $content = Cart::getContent(); 
				   $sumTotal = 0; 	
				   $sumToTalCast = 0;
				?>
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Hình ảnh</td>
							<td class="description">Mô tả</td>
							<td class="price">Giá</td>
							<td class="quantity">Số lượng</td>
							<td class="total">Tổng tiền</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($content as $v_content): ?>
							<?php 
								$itemTotal =  $v_content->price * $v_content->quantity;
								$sumTotal += $itemTotal;
							 ?>
											
							<tr>
								<td class="cart_product">
									<a href=""><img src="{{URL::to('public/uploads/product/'.$v_content->attributes->image)}}" alt="" width="70" /></a>
								</td>
								<td class="cart_description">
									<h4><a href="">{{$v_content->name}}</a></h4>
									<p>Web ID: 1089772</p>
								</td>
								<td class="cart_price">
									<p>{{number_format($v_content->price).' VNĐ'}}</p>
								</td>
								<td class="cart_quantity">
									<div class="cart_quantity_button">
										<form action="{{URL::to('/update-cart-quantity')}}" method="POST">
											{{csrf_field()}}
											<input class="cart_quantity_input" type="text" name="cartQuantity" value="{{$v_content->quantity}}" >
											<input type="hidden" value="{{$v_content->id}}" name="cartID" class="form-control ">
											<input type="submit" value="Cập nhật" name="update_qty" class="btn btn-default btn-sm ">
										</form>
									</div>
								</td>
								<td class="cart_total">
									<p class="cart_total_price">
										<?php 											
											echo number_format($itemTotal).' VNĐ';
										 ?>
									</p>
								</td>
								<td class="cart_delete">
									<a class="cart_quantity_delete" href="{{URL::to('/delete-to-cart/'.$v_content->id)}}"><i class="fa fa-times"></i></a>
								</td>
							</tr>									
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</section> <!--/#cart_items-->
	<section id="do_action">
		<div class="container">
			<div class="row">
				<div class="col-sm-6">
					<div class="total_area">
						<ul>
							<li>Tổng 
								<span>
								<?php 
									$totalTaxCast = Cart::getTotal()-$sumTotal;
								 ?>
								{{number_format($sumTotal).' VNĐ'}}
								</span></li>
								
								<li>Thuế <span>
									<?php 
										$tax = 0;
										$total = 0;
									foreach ($content as $key => $value) {
										$tax=$value->conditions->getValue();
										$total =$totalTaxCast;
									}
							
								 	?>	
								 	{{'('.$tax.')'.' '.number_format($total).' VNĐ'}}
									</span>
								</li>
							<li>Phí vận chuyển<span>Free</span></li>
							<li>Thành tiền<span>{{number_format(Cart::getTotal()).' VNĐ'}}</span></li>
						</ul>
							<!-- <a class="btn btn-default update" href="">Update</a> -->
                                <?php 
                                    $customer_id = Session::get('customer_id');
                                    $shipping_id = Session::get('shipping_id'); 
                                    if ($customer_id!=NULL && $shipping_id==NULL) {
                                ?>
                                	<a class="btn btn-default check_out" href="{{URL::to('/checkout')}}">Thanh toán</a>
                                <?php 
                                	}elseif($customer_id!=NULL && $shipping_id!=NULL) {
                                ?>	
                                	<a class="btn btn-default check_out" href="{{URL::to('/payment')}}">Thanh toán</a>
                                <?php 
                                    }else{
                                 ?>
                              		<a class="btn btn-default check_out" href="{{URL::to('/login-checkout')}}">Thanh toán</a>
                                <?php 
                                    }
                                ?>
					</div>
				</div>
			</div>
		</div>
	</section><!--/#do_action-->

@endsection