@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('subscription'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>

                    <div class="box-tools">
                        @include('admin.subscription.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Role</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th class="text-center">Login Status</th>
                            <th class="text-center">Premium Upgraded</th>
                            <th class="text-center">Register Date</th>
                            <th>Subscribed</th>
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
                                    <td>{!! $user->email !!}</td>
                                    <td class="text-center">
                                        @if($user->isOnline())
                                            <span class="label label-success">online</span>
                                        @else
                                            <span class="label label-danger">offline</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{!! $user->upgraded_at?$user->upgraded_at->format($user::DFORMAT):'-' !!}</td>
                                    <td class="text-center">{!! $user->created_at->format($user::DFORMAT) !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <div title="Subscribed">
                                            <label>
                                                {!! Form::hidden('subscribed', 0) !!}
                                                {!! Form::checkbox('subscribed', $user->subscribed,$user->subscribed,['class' => 'j-subscribed-status','data-userid' => $user->id]) !!}
                                            </label>
                                        </div>
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