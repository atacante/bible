@extends('layouts.app')

@section('title')
    @parent
@stop

@section('content')
    <div class="box box-success">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @if(Cart::count())
                     @foreach(Cart::content() as $row)
                        <tr>
                             <td>
                                 <p><strong>{!! $row->name !!}</strong></p>
                             </td>
                             <td><input type="text" value="{!! $row->qty !!}"></td>
                             <td>{!! $row->price !!}</td>
                             <td> {!! $row->total !!} </td>
                            <td class="text-center" style="width: 50px;">
                                <a title="Update item" href="{!! url('/shop/cart-update/'.$row->rowId) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                <a title="Delete item" href="{!! url('/shop/cart-delete',$row->rowId) !!}" data-toggle="modal"
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
                            <p class="text-center">No items added</p>
                        </td>
                    </tr>
                @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Subtotal</td>
                        <td><?php echo Cart::subtotal(); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Tax</td>
                        <td><?php echo Cart::tax(); ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Total</td>
                        <td><?php echo Cart::total(); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
@endsection
