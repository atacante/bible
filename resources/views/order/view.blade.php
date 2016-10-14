@extends('layouts.app')

@section('title')
    @parent
@stop

@section('content')
    <table class="kit-table1 pull-left" style="width:49%">
        <thead>
        <tr>
            <th colspan="2">
                Ship To
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="label1">
                Name:
            </td>
            <td style="padding-right: 5px">
                {{ $order->userMeta->shipping_first_name.' '.$order->userMeta->shipping_last_name }}
            </td>
        </tr>
        <tr>
            <td class="label1">
                Address:
            </td>
            <td style="padding-right: 5px">
                {{
                   $order->userMeta->shipping_postcode.', '.
                   $order->userMeta->shipping_address.', '.
                   $order->userMeta->shipping_city.', '.
                   $order->userMeta->shipping_state.', '.
                   $order->userMeta->shipping_country
                }}
            </td>
        </tr>
        <tr>
            <td class="label1">
                Email:
            </td>
            <td style="padding-right: 5px">
                {{ $order->userMeta->shipping_email }}
            </td>
        </tr>
        <tr>
            <td class="label1">
                Phone:
            </td>
            <td style="padding-right: 5px">
                {{ $order->userMeta->shipping_phone }}
            </td>
        </tr>
        </tbody>
    </table>
    <table class="kit-table1 pull-right" style="width:50%">
        <thead>
        <tr>
            <th style="width: 140px">Order Placed</th>
            <th style="width: 40%"></th>
            <th>Quantity</th>
            <th style="padding-right: 5px">Price</th>
            {{--<th style="width:12%">Subtotal</th>--}}
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
                    <td style="padding-right: 5px">${!! $row->product->price !!}</td>
                    {{--<td>${!! $row->product->price * $row->qty !!} </td>--}}
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8">
                    <p class="text-center">No items added</p>
                </td>
            </tr>
        @endif
        </tbody>
        <tfoot>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td class="label1">Subtotal</td>
            <td style="padding-right: 5px">${!! $order->subtotal !!}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td class="label1">Tax</td>
            <td style="padding-right: 5px">${!! $order->tax !!}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td class="label1">Shipping Rate</td>
            <td style="padding-right: 5px">${!! $order->shipping_rate !!}</td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
            <td class="label1">Total</td>
            <td style="padding-right: 5px">${!! $order->total_paid !!}</td>
        </tr>
        </tfoot>
    </table>

    <div class="clearfix"></div>
    <div class="mb1">
        {!! Html::link(url('/shop'),'Back To Shop', ['class'=>'btn2-kit pull-right']) !!}
        <div class="clearfix"></div>
    </div>
    <!-- /.box-body -->

@endsection
