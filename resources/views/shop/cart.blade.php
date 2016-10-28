@extends('layouts.app')

@section('title')
    @parent
@stop

@section('content')
        <table class="kit-table1 cart-desktop">
            <thead>
                <tr>
                    <th style="width: 140px">Product</th>
                    <th></th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @if(Cart::count())
                 @foreach(Cart::content() as $row)
                    {!! Form::open(['method' => 'post','url' => '/shop/cart-update']) !!}
                    <tr>
                        <td>
                             @if($row->model->images->count())
                                 <img class="img-thumbnail" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$row->model->images[0]->image !!}" />
                             @else
                                 <div class="no-avatar img-thumbnail">
                                     <div class="no-avatar-text text-center"><i class="fa fa-shopping-cart fa-4x"></i></div>
                                 </div>
                             @endif
                        </td>
                        <td>
                            <p><strong>{!! $row->name !!}</strong></p>
                            <p>{!! (isset($row->options['size']))?'Size: '.$row->options['size']:'' !!}</p>
                            <p>{!! (isset($row->options['color']))?'Color: '.$row->options['color']:'' !!}</p>
                        </td>
                        <td>{!! Form::text('qty',$row->qty, ['class'=>'input1','style'=>'width:125px']) !!}</td>
                        <td>${!! $row->price !!}</td>
                        <td>${!! $row->total !!} </td>
                        <td style="width: 200px;">
                            {!! Form::token() !!}
                            {!! Form::hidden('rowId', $row->rowId) !!}
                            <a class="btn3-kit" title="Delete item" href="{!! url('/shop/cart-delete',$row->rowId) !!}" data-toggle="modal"
                               data-target="#confirm-delete" data-header="Delete Confirmation"
                               data-confirm="Are you sure you want to delete this item?">
                                <i class="fa fa-trash"></i>
                            </a>

                            {!! Form::button('Update',['type' => 'submit','class' => 'btn1-kit']) !!}
                        </td>
                    </tr>
                    {!! Form::close() !!}
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
                    <td>$<?php echo Cart::subtotal(); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td class="label1">Tax</td>
                    <td>$<?php echo Cart::tax(); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td class="label1">Total</td>
                    <td>$<?php echo Cart::total(); ?></td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>



{{-- Mobile --}}
        <div class="kit-table1 cart-mobile">

            @if(Cart::count())
                @foreach(Cart::content() as $row)
                    <div class="c-white-item">
                    {!! Form::open(['method' => 'post','url' => '/shop/cart-update']) !!}
                    <div>
                        <div>
                            @if($row->model->images->count())
                                <img class="img-thumbnail" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$row->model->images[0]->image !!}" />
                            @else
                                <div class="no-avatar img-thumbnail">
                                    <div class="no-avatar-text text-center"><i class="fa fa-shopping-cart fa-4x"></i></div>
                                </div>
                            @endif
                        </div>


                        <div>
                            <h3 class="h3-kit">{!! $row->name !!}</h3>
                            <p>{!! (isset($row->options['size']))?'Size: '.$row->options['size']:'' !!}</p>
                            <p>{!! (isset($row->options['color']))?'Color: '.$row->options['color']:'' !!}</p>
                        </div>

                        <div>
                            <h3>Quantity</h3>
                            {!! Form::text('qty',$row->qty, ['class'=>'input1','style'=>'width:125px']) !!}
                        </div>
                        <div>
                            <h3>Price</h3>
                            ${!! $row->price !!}
                        </div>
                        <div>
                            <h3>Subtotal</h3>
                            ${!! $row->total !!}
                        </div>
                        <div class="text-right">
                            {!! Form::token() !!}
                            {!! Form::hidden('rowId', $row->rowId) !!}
                            <a class="btn3-kit" title="Delete item" href="{!! url('/shop/cart-delete',$row->rowId) !!}" data-toggle="modal"
                               data-target="#confirm-delete" data-header="Delete Confirmation"
                               data-confirm="Are you sure you want to delete this item?">
                                <i class="fa fa-trash"></i>
                            </a>

                            {!! Form::button('Update',['type' => 'submit','class' => 'btn1-kit']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                    </div>
                @endforeach
            @else

                <div>
                    <p class="text-center">No items added</p>
                </div>

            @endif


            <div class="c-white-item">
                <div class="mb-resp">
                    <h3 class="h3-kit">Subtotal</h3>
                    <div>$<?php echo Cart::subtotal(); ?></div>
                </div>
                <div class="mb-resp">
                    <h3 class="h3-kit">Tax</h3>
                    <div>$<?php echo Cart::tax(); ?></div>
                </div>
                <div class="mb-resp">
                    <h3 class="h3-kit">Total</h3>
                    <div>$<?php echo Cart::total(); ?></div>
                </div>
            </div>
        </div>

        <div class="mb1 c-resp-checkout">
            @if(Cart::count())
                {!! Html::link(url('/order/create'),'Checkout', ['class'=>'btn2-kit pull-right']) !!}
            @else
                {!! Html::link(url('/shop'),'Back To Shop', ['class'=>'btn2-kit pull-right']) !!}
            @endif
            <div class="clearfix"></div>
        </div>
        <!-- /.box-body -->

@endsection
