@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('terms'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.symbolism-encyclopedia.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/symbolism-encyclopedia/create','Create Term', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['terms']))
                            @foreach($content['terms'] as $term)
                                <tr>
                                    <td>{!! $term->term_name !!}</td>
                                    <td>{!! str_limit(strip_tags($term->term_description,'<p></p>'), $limit = 500, $end = '...') !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit term" href="{!! url('/admin/symbolism-encyclopedia/update/'.$term->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Delete term" href="{!! url('/admin/symbolism-encyclopedia/delete',$term->id) !!}" data-toggle="modal"
                                           data-target="#confirm-delete" data-header="Delete Confirmation"
                                           data-confirm="Are you sure you want to delete this item?"><i
                                                    class="fa fa-trash"
                                                    style="color: #367fa9; font-size: 1.4em;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
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
                {!! $content['terms']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection