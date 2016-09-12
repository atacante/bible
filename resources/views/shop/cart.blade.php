@extends('layouts.app')

@section('title')
    @parent
@stop

@section('content')
        <table class="kit-table1">
            <thead>
                <tr>
                    <th>Product</th>
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
                        </td>
                        <td>{!! Form::text('qty',$row->qty, ['style'=>'width:100px']) !!}</td>
                        <td>{!! $row->price !!}</td>
                        <td> {!! $row->total !!} </td>
                        <td class="text-center" style="width: 50px;">
                            {!! Form::token() !!}
                            {!! Form::hidden('rowId', $row->rowId) !!}
                            <a title="Delete item" href="{!! url('/shop/cart-delete',$row->rowId) !!}" data-toggle="modal"
                               data-target="#confirm-delete" data-header="Delete Confirmation"
                               data-confirm="Are you sure you want to delete this item?"><i
                                        class="fa fa-trash"
                                        style="color: #367fa9; font-size: 1.4em;"></i></a>

                            {!! Form::button('Update',['type' => 'submit','class' => 'btn btn-primary','style' => 'padding: 2px 12px; margin-top:5px;']) !!}
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
                    <td>Subtotal</td>
                    <td><?php echo Cart::subtotal(); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>Tax</td>
                    <td><?php echo Cart::tax(); ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                    <td>Total</td>
                    <td><?php echo Cart::total(); ?></td>
                    <td>&nbsp;</td>
                </tr>
            </tfoot>
        </table>

        <div>
            {!! Html::link(url('/order/create'),'Checkout', ['class'=>'btn btn-success pull-right']) !!}
        </div>
        <!-- /.box-body -->

@endsection
