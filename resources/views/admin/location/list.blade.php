@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('locations'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.partials.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/location/create','Create Location', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            {{--<th style="width: 150px">Verse Number</th>--}}
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['locations']))
                            @foreach($content['locations'] as $location)
                                <tr>
{{--                                    <td>{!! $location->booksListEn->book_name.' '.$location->chapter_num.':'.$location->verse_num !!}</td>--}}
                                    <td>{!! $location->location_name !!}</td>
                                    <td>{!! $location->location_description !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit location" href="{!! url('/admin/location/update/'.$location->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em;"></i></a>
                                        <a title="Delete location" href="{!! url('/admin/location/delete',$location->id) !!}" data-toggle="modal"
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
                {!! $content['locations']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection