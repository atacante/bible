@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('shop-products'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.shop.products.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/shop-products/create','Create Product', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Image</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Short Description</th>
                            <th>Price</th>
                            <th>External Link</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['products']))
                            @foreach($content['products'] as $product)
                                <tr>
                                    <td class="img-column">
                                        @if($product->images->count())
                                            <img class="img-thumbnail"  src="{!! Config::get('app.productImages').'thumbs/'.$product->images[0]->image !!}" />
                                        @else
                                            <div class="no-image img-thumbnail">
                                                <div class="no-image-text text-center">No image</div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{!! $product->category->title !!}</td>
                                    <td>{!! $product->name !!}</td>
                                    <td>{!! $product->short_description !!}</td>
                                    <td>{!! $product->price !!}</td>
                                    <td>{!! $product->external_link !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit product" href="{!! url('/admin/shop-products/update/'.$product->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Delete product" href="{!! url('/admin/shop-products/delete',$product->id) !!}" data-toggle="modal"
                                           data-target="#confirm-delete" data-header="Delete Confirmation"
                                           data-confirm="Are you sure you want to delete this item?"><i
                                                    class="fa fa-trash"
                                                    style="color: #367fa9; font-size: 1.4em;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    <p class="text-center">No any results found</p>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
            <div class="text-center">
                {!! $content['products']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection