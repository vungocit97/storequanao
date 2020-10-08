@extends('backend.master.master')
@section('title',' Thêm sản phẩm ')
@section('product')
class="active"
@endsection
@section('content')
  <!--main-->
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Thêm sản phẩm</h1>
            </div>
        </div>
        <!--/.row-->
        <div class="row">
            <div class="col-xs-6 col-md-12 col-lg-12">
                <form method="POST" enctype="multipart/form-data">
                 @csrf
                <div class="panel panel-primary">
                        <form action="" method="post"></form>
                    <div class="panel-heading">Thêm sản phẩm</div>
                    <div class="panel-body">
                        <div class="row" style="margin-bottom:40px">
                            <div class="col-xs-8">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Danh mục</label>
                                            <select name="category" class="form-control">
                                                    {{ getCategory($categories,0,'',0) }}
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Mã sản phẩm</label>
                                            <input  type="text" name="product_code" class="form-control">
                                            @if($errors->has('product_code'))
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('product_code') }}</strong>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Tên sản phẩm</label>
                                            <input  type="text" name="product_name" class="form-control">
                                            @if($errors->has('product_name'))
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('product_name') }}</strong>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            <label>Giá sản phẩm (Giá chung)</label>
                                            <input  type="number" name="product_price" class="form-control">
                                            @if($errors->has('product_price'))
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('product_price') }}</strong>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                                <label>Sản phẩm nổi bật</label>
                                                <select  name="featured" class="form-control">
                                                    <option value="1">Có</option>
                                                    <option value="0">Không</option>
                                                </select>
                                            </div>
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <select  name="product_state" class="form-control">
                                                <option value="1">Còn hàng</option>
                                                <option value="0">Hết hàng</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Ảnh sản phẩm</label>
                                            @if($errors->has('product_img'))
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('product_img') }}</strong>
                                            </div>
                                            @endif
                                            <input id="img" type="file" name="product_img" class="form-control hidden"
                                                onchange="changeImg(this)">
                                            <img id="avatar" class="thumbnail" width="100%" height="350px" src="img/import-img.png">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Thông tin</label>
                                    <textarea  name="info" style="width: 100%;height: 100px;"></textarea>
                                </div>

                            </div>
                            <div class="col-xs-4">

                                <div class="panel panel-default">
                                    <div class="panel-body tabs">
                                        <label>Các thuộc Tính <a href="/admin/product/detail-attr"><span class="glyphicon glyphicon-cog"></span>
                                                Tuỳ chọn</a></label>
                                                @if ($errors->has('attr_name'))
                                                <div class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('attr_name') }}</strong>
                                                </div>
                                                @endif
                                                @if ($errors->has('value_name'))
                                                <div class="alert alert-danger" role="alert">
                                                <strong>{{ $errors->first('value_name') }}</strong>
                                                </div>
                                                 @endif
                                                 @if (session('thongbao'))
                                                <div class="alert alert-success" role="alert">
                                                <strong>{{ session('thongbao') }}</strong>
                                                </div>
                                                 @endif
                                        <ul class="nav nav-tabs">

                                            @php
                                                $i=0;
                                            @endphp
                                            @foreach ($attrs as $attr)
                                            <li @if($i==0) class='active' @endif><a href="#tab{{ $attr->id }}" data-toggle="tab">{{ $attr->name }}</a></li>
                                            @php
                                                $i=1;
                                            @endphp
                                            @endforeach
                                            <li><a href="#tab-add" data-toggle="tab">+</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            @foreach ($attrs as $attr)
                                            <div class="tab-pane fade @if($i==1) active @endif  in" id="tab{{ $attr->id }}">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>

                                                                @foreach ($attr->values as $value)
                                                                <th>{{ $value->value }}</th>
                                                                @endforeach

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                 @foreach ($attr->values as $value)
                                                                <td> <input class="form-check-input" type="checkbox" name="attr[{{ $attr->id }}][]" value="{{ $value->id }}"> </td>
                                                                @endforeach
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <hr>
                                                    <div class="form-group">
                                                        <form action="/admin/product/add-value" method="post">
                                                            @csrf
                                                        <label for="">Thêm biến thể cho thuộc tính</label>
                                                        <input type="hidden" name="attr_id" value="{{ $attr->id }}">
                                                        <input name="value_name" type="text" class="form-control"
                                                            aria-describedby="helpId" placeholder="">
                                                        <div> <button name="add_val" type="submit">Thêm</button></div>
                                                        </form>
                                                    </div>
                                         </div>
                                                @php
                                                $i=2;
                                                 @endphp
                                            @endforeach

                                            <div class="tab-pane fade" id="tab-add">
                                                <form action="/admin/product/add-attr" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="">Tên thuộc tính mới</label>
                                                    <input type="text" class="form-control" name="attr_name" aria-describedby="helpId" placeholder="">
                                                </div>
                                                <button type="submit" name="add_pro" class="btn btn-success"> <span class="glyphicon glyphicon-plus"></span>
                                                    Thêm thuộc tính</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <p></p>

                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Miêu tả</label>
                                        <textarea id="editor"  name="description" style="width: 100%;height: 100px;"></textarea>
                                    </div>
                                    <button class="btn btn-success" name="add-product" type="submit">Thêm sản phẩm</button>
                                    <button class="btn btn-danger" type="reset">Huỷ bỏ</button>
                                </div>
                            </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
            </div>
        </div>

        <!--/.row-->
    </div>

    <!--end main-->
    @endsection
