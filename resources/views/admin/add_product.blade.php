@extends('admin_layout')
@section('admin_content')
 <div class="row">
        <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Thêm sản phẩm
                        </header>
                    
                        <div class="panel-body">
                            <div class="position-center">
                                <form role="form" action="{{URL::to('/save-product')}}" method="post" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên sản phẩm</label>
                                    <input type="text" data-validation="length" data-validation-length="min1" data-validation-error-msg="Tên sản phẩm không được để trống" name="product_name" class="form-control" id="exampleInputEmail1" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giá sản phẩm</label>
                                    <input type="text" data-validation="number" data-validation-error-msg="Giá tiền phải là số và lớn hơn 0"  data-validation-length="min0" name="product_price" class="form-control" id="" placeholder="Giá sản phẩm">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hình ảnh sản phẩm</label>
                                    <input type="file" name="product_images" class="form-control" id="exampleInputEmail1">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả sản phẩm</label>
                                    <textarea style="resize: none" rows="5" class="form-control" name="product_desc"  placeholder="Mô tả sản phẩm" id="ckeditor1"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Nội dung sản phẩm</label>
                                    <textarea style="resize: none" rows="5" class="form-control" name="product_content" id="ckeditor2" placeholder="Nội dung sản phẩm"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Danh mục sản phẩm</label>
                                    <select name="product_cate" class="form-control input-sm m-bot15">
                                        <?php foreach ($cate_product as $key => $cate): ?>
                                            <option value="{{$cate->category_id}}">{{$cate->category_name}}</option>                                  
                                        <?php endforeach ?>

                                        
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Thương hiệu</label>
                                    <select name="product_brand" class="form-control input-sm m-bot15">
                                        <?php foreach ($brand_product as $key => $brand): ?>
                                            <option value="{{$brand->brand_id}}">{{$brand->brand_name}}</option>                                  
                                        <?php endforeach ?>
                                    </select>
                                </div> 
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Hiển thị</label>
                                    <select name="product_status" class="form-control input-sm m-bot15">
                                        <option value="0">Ẩn</option>
                                        <option value="1">Hiển thị</option>
                                    </select>
                                </div>                        
                                <button type="submit" name="add_product" class="btn btn-info">Thêm sản phẩm</button>
                            </form>
                            </div>

                        </div>
                        <?php
                            //get lấy 
                            $messages = Session::get('message');    
                            if ($messages) {
                                echo '<span class = "text-alert">',$messages,'</span>';
                                Session::put('message', null);  
                            }
                        ?>
                    </section>

            </div>
        </div>
@endsection