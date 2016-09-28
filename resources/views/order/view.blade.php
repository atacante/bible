@extends('layouts.app')

@section('title')
    @parent
@stop

@section('content')
    <table class="kit-table1">
        <thead>
        <tr>
            <th style="width: 140px">Product</th>
            <th></th>
            <th>Quantity</th>
            <th>Price</th>
            <th style="width:12%">Subtotal</th>
        </tr>
        </thead>
        <tbody>
        @if($order->orderItems->count())
            @foreach($order->orderItems as $row)
                <tr>
                    <td>
                        @if($row->product->images->count())
                            <img class="img-thumbnail" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$row->product->images[0]->image !!}" />
                        @else
                            <div class="no-avatar img-thumbnail">
                                <div class="no-avatar-text text-center"><i class="fa fa-shopping-cart fa-4x"></i></div>
                            </div>
                        @endif
                    </td>
                    <td>
                        <p><strong>{!! $row->product->name !!}</strong></p>
                    </td>
                    <td>{!! $row->qty !!}</td>
                    <td>${!! $row->product->price !!}</td>
                    <td>${!! $row->product->price * $row->qty !!} </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="9">
                    <p class="text-center">No items added</p>
                </td>
            </tr>
        @endif
        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td class="label1">Subtotal</td>
            <td>${!! $order->subtotal !!}</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td class="label1">Tax</td>
            <td>${!! $order->tax !!}</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
            <td class="label1">Total</td>
            <td>${!! $order->total_paid !!}</td>
        </tr>
        </tfoot>
    </table>

    <div class="mb1">
        {!! Html::link(url('/shop'),'Back To Shop', ['class'=>'btn2-kit pull-right']) !!}
        <div class="clearfix"></div>
    </div>
    <!-- /.box-body -->

@endsection
