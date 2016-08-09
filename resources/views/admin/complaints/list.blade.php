@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('complaints'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.complaints.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
{{--            {!! Html::link('/admin/complaints/create','Create Complaint', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}--}}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Content type</th>
                            <th>Content Text</th>
                            <th class="text-center">Content Status</th>
                            <th>User</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th class="text-center">Created</th>
                            <th class="text-center" width="90">Actions</th>
                        </tr>
                        @if(count($content['complaints']))
                            @foreach($content['complaints'] as $complaint)
                                <tr>
                                    <td>{!! $complaint->type() !!}</td>
                                    <td>{!! $complaint->item->text() !!}</td>
                                    <td  class="text-center" style="color: #367fa9; font-size: 1.4em;">{!! ViewHelper::getAccessLevelIcon($complaint->item->access_level) !!}</td>
                                    <td>{!! $complaint->user->name !!}</td>
                                    <td>{!! $complaint->reason_text !!}</td>
                                    <td>{!! $complaint->resolved?'<span class="label label-success">resolved</span>':'<span class="label label-danger">pending</span>' !!}</td>
                                    <td class="text-center">{!! $complaint->created_at->format($complaint::DFORMAT) !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit" href="{!! url('/admin/complaints/update/'.$complaint->type().'/'.$complaint->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Make private" href="{!! url('/admin/complaints/make-private/'.$complaint->type().'/'.$complaint->id) !!}"><i class="fa fa-lock" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Delete" href="{!! url('/admin/complaints/delete/'.$complaint->type(),$complaint->id) !!}" data-toggle="modal"
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
                {!! $content['complaints']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection