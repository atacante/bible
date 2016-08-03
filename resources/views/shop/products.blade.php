@if(count($products))
    @foreach($products as $product)
        <div data-itemid="{!! $product->id !!}" class="col-md-6 clearfix product-item j-show-product" data-link="{!! ($product->external_link)? $product->external_link : url('/shop/product/'.$product->id,[],false) !!}" style="cursor: pointer">
            <div class="pull-left" style="margin-right: 10px;">
                @if(count($product->images))
                    <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$product->images[0]->image !!}" />
                @else
                    <div class="no-avatar img-thumbnail">
                        <div class="no-avatar-text text-center"><i class="fa fa-shopping-cart fa-4x"></i></div>
                    </div>
                @endif
            </div>
            <div class="pull-left" style="margin-right: 10px; width: 200px;">
                <div><strong>{!! $product->name !!}</strong></div>
                <div style="line-height: 16px; font-size: 12px;">
                    <span style="color:#90949c;">{!! $product->short_description !!}</span>
                </div>
            </div>
            <div class="pull-right">
                @if($product->external_link)
                <div>
                    <a href="{!! $product->external_link !!}" class="btn btn-primary">Show details</a>
                </div>
                <div style="margin-top: 10px;">
                    ${!! $product->price !!}
                </div>
                @else
                    <div>
                        <a href="{!! url('/shop/add-to-cart/'.$product->id,[],false) !!}" class="btn btn-primary" >Add To Cart</a>
                    </div>
                    <div style="margin-top: 10px;">
                        ${!! $product->price !!}
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@else
    <p class="text-center">No any results found</p>
@endif

<div class="row">
    <div class="text-center">
        {!! $products->appends(Request::input())->links() !!}
    </div>
</div>