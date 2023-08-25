@extends('admin_layout')
@section('admin_content')
 <div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                            Cập nhật thương hiệu sản phẩm
                        </header>
                        <div class="panel-body">
                            <?php foreach ($edit_brand_product as $key => $edit_value): ?>
                                <div class="position-center"> 
                                    <form role="form" method="post" action="{{URL::to('/update-brand-product/'.$edit_value->brand_id)}}" method="post" >
                                    {{csrf_field()}}
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên thương hiệu</label>
                                    <input type="text" value="{{$edit_value->brand_name}}" name="brand_product_name" class="form-control" id="exampleInputEmail1" placeholder="Tên danh mục">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Mô tả thương hiệu</label>
                                    <textarea  style="resize: none" rows="5" class="form-control" name="brand_product_desc" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{$edit_value->brand_desc}}</textarea>
                                </div>    
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Từ khóa thương hiệu</label>
                                    <textarea  style="resize: none" rows="5" class="form-control" name="brand_product_keyword" id="exampleInputPassword1" placeholder="Mô tả danh mục">{{$edit_value->meta_keyword}}</textarea>
                                </div>                                                 
                                    <button type="submit" name="update_brand_product" class="btn btn-info">Cập nhật thương hiệu</button>
                                    </form>
                                </div>   
                            <?php endforeach ?>
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