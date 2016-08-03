@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('cms'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        {{--<tr>
                            <th>System Name</th>
                            <th>Title</th>
                            <th>Meta Title</th>
                            <th>Meta Keywords</th>
                            <th>Meta Description</th>
                            <th>Actions</th>
                        </tr>--}}
                        @if(count($content['users']))
                            @foreach($content['users'] as $user)
                                <tr>
                                    <td>{!! $user->avatar !!}</td>
                                    <td>{!! $user->name !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <input type="checkbox" @if($user->subscribed) checked @endif>
                                        {{--<a title="Edit CMS" href="{!! url('/admin/cms/update/'.$page->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>--}}
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
               {{-- {!! $content['cms']->appends(Request::input())->links() !!}--}}
            </div>
        </div>
    </div>
@endsection