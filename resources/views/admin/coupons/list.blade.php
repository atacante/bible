@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('coupons'))

@section('content')
    {{--<div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        @include('admin.coupons.filters')
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
    <div class="row">
        <div class="col-xs-12">
            {!! Html::link('/admin/coupons/create','Create Coupon', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Coupon Code</th>
                            {{--<th>Coupon Type</th>--}}
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Member Type</th>
                            <th>User</th>
                            <th class="text-center">Expiration</th>
                            <th class="text-center"># of uses</th>
                            <th class="text-center"># of already used</th>
                            <th class="text-center">Created</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['coupons']))
                            @foreach($content['coupons'] as $coupon)
                                <tr>
                                    <td>{!! $coupon->coupon_code !!}</td>
{{--                                    <td>{!! $coupon->coupon_type !!}</td>--}}
                                    <td>{!! $coupon->status?'<span class="label label-success">active</span>':'<span class="label label-danger">'.(strtotime($coupon->expire_at) > time()?'exhausted':'expired').'</span>' !!}</td>
                                    <td>{!! $coupon->amount !!}</td>
                                    <td>{!! $coupon->member_type?$coupon->member_type:'all' !!}</td>
                                    <td>{!! $coupon->user?$coupon->user->name:'all' !!}</td>
                                    <td class="text-center">{!! $coupon->expire_at?$coupon->expire_at->format($coupon::DFORMAT):'&#8734' !!}</td>
                                    <td class="text-center">{!! $coupon->uses_limit?$coupon->uses_limit:"&#8734" !!}</td>
                                    <td class="text-center">{!! $coupon->used?$coupon->used:0 !!}</td>
                                    <td class="text-center">{!! $coupon->created_at->format($coupon::DFORMAT) !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="Edit coupon" href="{!! url('/admin/coupons/update/'.$coupon->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                        <a title="Delete coupon" href="{!! url('/admin/coupons/delete',$coupon->id) !!}" data-toggle="modal"
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
                {!! $content['coupons']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection