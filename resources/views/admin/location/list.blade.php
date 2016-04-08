@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('locations'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.location.filters')
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
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-center" style="width: 150px">Verse</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['locations']))
                            @foreach($content['locations'] as $location)
                                <tr>
                                    <td class="img-column">
                                        @if($location->images->count())
                                            <img class="img-thumbnail"  src="{!! Config::get('app.locationImages').'thumbs/'.$location->images[0]->image !!}" />
                                        @else
                                            <div class="no-image img-thumbnail">
                                                <div class="no-image-text text-center">No image</div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{!! $location->location_name !!}</td>
                                    <td>{!! str_limit(strip_tags($location->location_description,'<p></p>'), $limit = 500, $end = '...') !!}</td>
                                    <td class="text-center"> - {{--{!! $location->booksListEn->book_name.' '.$location->chapter_num.':'.$location->verse_num !!}--}}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit location" href="{!! url('/admin/location/update/'.$location->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
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
                                <td colspan="5">
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