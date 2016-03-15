@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('users'))

@section('content')
    {{--<div class="row">
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
    </div>--}}
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Register Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['users']))
                            @foreach($content['users'] as $user)
                                <tr>
                                    <td>{!! $user->id !!}</td>
                                    <td>
                                        @if(count($user->roles))
                                            @foreach($user->roles as $role)
                                                <span class="label label-info">{!! $role->name !!}</span>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>{!! $user->name !!}</td>
                                    <td><a href="mailto:{!! $user->email !!}">{!! $user->email !!}</a></td>
                                    <td>{!! date('F d, Y', strtotime($user->created_at)) !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a href="{!! url('/admin/user/update',$user->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        @if($role->slug != Config::get('app.role.admin'))
                                            <a href="{!! url('/admin/user/delete',$user->id) !!}"><i class="fa fa-trash"
                                                                                                     style="color: #367fa9; font-size: 1.4em;"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
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
                {!! $content['users']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection