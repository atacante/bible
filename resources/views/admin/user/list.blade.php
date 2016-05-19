@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('users'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>

                    <div class="box-tools">
                        @include('admin.user.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/user/create','Create User', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success ">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th style="width: 50px">ID</th>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Login Status</th>
                            <th class="text-center">Last Login</th>
                            <th class="text-center">Premium Upgraded</th>
                            <th class="text-center">Register Date</th>
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
                                    <td class="text-center">
                                        @if($user->isOnline())
                                            <span class="label label-success">online</span>
                                        @else
                                            <span class="label label-danger">offline</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{!! $user->last_login_at?$user->last_login_at->format('m/d/Y'):'-' !!}</td>
                                    <td class="text-center">{!! $user->upgraded_at?$user->upgraded_at->format('m/d/Y'):'-' !!}</td>
                                    <td class="text-center">{!! $user->created_at->format('m/d/Y') !!}</td>
                                    <td class="text-center" style="width: 100px;">
                                        @if(!$user->is(Config::get('app.role.admin')) && Auth::user()->id != $user->id)
                                            <a title="Login as user" href="{!! url('/admin/user/authorize',$user->id) !!}"><i
                                                        class="fa fa-user-secret"
                                                        style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        @endif
                                        <a title="Edit user" href="{!! url('/admin/user/update',$user->id) !!}"><i class="fa fa-edit"
                                                                                                 style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        @if($role->slug != Config::get('app.role.admin'))
                                            <a title="Delete user" href="{!! url('/admin/user/delete',$user->id) !!}" data-toggle="modal"
                                               data-target="#confirm-delete" data-header="Delete Confirmation"
                                               data-confirm="Are you sure you want to delete this item?"><i
                                                        class="fa fa-trash"
                                                        style="color: #367fa9; font-size: 1.4em;"></i></a>
                                        @endif
                                    </td>
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
                {!! $content['users']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection