@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('viewReport'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.reports.view-report-filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success ">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Group name</th>
                            <th>Group description</th>
                            <th class="text-center">Members</th>
                            <th class="text-center">Created at</th>
                        </tr>
                        @if(count($content['userGroups']))
                            @foreach($content['userGroups'] as $group)
                                <tr>
                                    <td>
                                        <div>{!! Html::link(url('/groups/view',$group->id)
                                                ,$group->group_name, ['target' => '_blank','class'=>'label label-success','style' => 'margin-bottom:10px;']) !!}</div>
                                    </td>
                                    <td>
                                        <div>{!! $group->group_desc !!}</div>
                                    </td>
                                    <td class="text-center">
                                        {!! $group->members()->count() !!}
                                    </td>
                                    <td class="text-center">{!! $group->created_at->format($group::DFORMAT) !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
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
                {!! $content['userGroups']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection
