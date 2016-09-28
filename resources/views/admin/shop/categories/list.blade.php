@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('shop-categories'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.shop.categories.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/shop-categories/create','Create Category', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Title</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['categories']))
                            @foreach($content['categories'] as $category)
                                <tr>
                                    <td>{!! $category->title !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit category" href="{!! url('/admin/shop-categories/update/'.$category->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Delete category" href="{!! url('/admin/shop-categories/delete',$category->id) !!}" data-toggle="modal"
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
                                    <p class="text-center">No results found</p>
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
                {!! $content['categories']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection