@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('shop-orders'))

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>User</th>
                            <th>Total</th>
                            <th>Placed</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        @if(count($content['orders']))
                            @foreach($content['orders'] as $order)
                                <tr>
                                    <td>{!! $order->user->name !!}</td>
                                    <td>{!! $order->total_paid !!}</td>
                                    <td>{!! $order->created_at->format($order::DFORMAT) !!}</td>
                                    <td class="text-center" style="width: 50px;">
                                        <a title="View order" href="{!! url('/admin/shop-orders/view/'.$order->id) !!}"><i class="fa fa-eye" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
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
                {!! $content['orders']->appends(Request::input())->links() !!}
            </div>
        </div>
    </div>
@endsection