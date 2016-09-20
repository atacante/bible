@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('viewReport'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.reports.ref-users-filters')
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
                            <th style="width: 50px">ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Last Login</th>
                            <th class="text-center">Register Date</th>
                        </tr>
                        @if(count($content['users']))
                            @foreach($content['users'] as $user)
                                <tr>
                                    <td>{!! $user->id !!}</td>
                                    <td>{!! $user->name !!}</td>
                                    <td><a href="mailto:{!! $user->email !!}">{!! $user->email !!}</a></td>
                                    <td class="text-center">{!! $user->last_login_at?$user->last_login_at->format($user::DFORMAT):'-' !!}</td>
                                    <td class="text-center">{!! $user->created_at->format($user::DFORMAT) !!}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
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
                {!! $content['users']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection