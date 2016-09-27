@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('shop-orderView'))

@section('content')
    <div class="box box-success">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                @if($order->orderItems->count())
                     @foreach($order->orderItems as $row)
                        <tr>
                            @if($row->product)
                             <td>
                                 <div class="pull-left" style="margin-right: 10px;">
                                     @if($row->product->images->count())
                                         <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$row->product->images[0]->image !!}" />
                                     @else
                                         <div class="no-avatar img-thumbnail">
                                             <div class="no-avatar-text text-center"><i class="fa fa-shopping-cart fa-4x"></i></div>
                                         </div>
                                     @endif
                                 </div>
                                 <p><strong>{!! $row->product->name !!}</strong></p>
                             </td>
                             <td>{!! $row->qty !!}</td>
                             <td>{!! $row->product->price !!}</td>
                             <td> {!! $row->product->price * $row->qty !!} </td>
                            @else
                                <td colspan="4">Product is not available any more</td>
                            @endif
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
                        <td>Total</td>
                        <td><?php echo $order->total_paid; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><h4>Customer Details</h4></div>
        <div class="panel-body">
            <div class="box-body">
                @foreach($order->userMeta->getFillable() as $key => $property)
                    @if($property != 'user_id')
                        @if($key == 1 || $key == 10)
                            <div class="col-md-6">
                                @endif
                                <div class="form-group">
                                    {!!  Form::label($property, ucwords(str_replace('_',' ', $property))) !!}
                                    {!!  Form::text($property, $order->userMeta->$property, ['class' => 'form-control', 'disabled' => true]) !!}
                                </div>
                                @if($key == 9 || $key == 18)
                            </div>
                        @endif
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
