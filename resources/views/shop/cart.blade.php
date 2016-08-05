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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if(Cart::count())
                     @foreach(Cart::content() as $row)
                        <tr>
                            {!! Form::open(['method' => 'post','url' => '/shop/cart-update']) !!}
                                 <td>
                                     <div class="pull-left" style="margin-right: 10px;">
                                         @if($row->model->images->count())
                                             <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$row->model->images[0]->image !!}" />
                                         @else
                                             <div class="no-avatar img-thumbnail">
                                                 <div class="no-avatar-text text-center"><i class="fa fa-shopping-cart fa-4x"></i></div>
                                             </div>
                                         @endif
                                     </div>
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
                            {!! Form::close() !!}
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
