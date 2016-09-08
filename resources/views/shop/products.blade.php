@if(count($products))
    @foreach($products as $product)
        <div data-itemid="{!! $product->id !!}" class="my-item j-show-product" data-link="{!! ($product->external_link)? $product->external_link : url('/shop/product/'.$product->id,[],false) !!}" style="cursor: pointer">
            <div class="my-inner-location-item">


                @if($product->images->count())
                    {{--<img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.productImages').'thumbs/'.$product->images[0]->image !!}" />--}}
                    <div class="product-image" data-dz-thumbnail="" data-image="{!!Config::get('app.productImages').'thumbs/'.$product->images[0]->image !!}" style="background: url('{!! $product->images[0]->image!=''?Config::get('app.productImages').'thumbs/'.$product->images[0]->image:'' !!}') center no-repeat;"></div>
                @else
                    <div class="product-image" data-dz-thumbnail="" data-image="" style="">
                        <i class="bs-producticon cu-producticon"></i>
                    </div>
                @endif

                <div class="c-inner-location-text">
                    <h4 class="h4-locations">{!! $product->name !!}</h4>
                    <div style="line-height: 16px; font-size: 12px;">
                        <span style="color:#90949c;">{!! $product->short_description !!}</span>
                    </div>
                    <div class="price-label">
                        ${!! $product->price !!}
                    </div>

                    @if($product->external_link)
                        <div>
                            {!! Html::link($product->external_link,'Show details', ['class'=>'btn1 cu2-btn1 j-show-product', 'data-link' => $product->external_link]) !!}
                        </div>
                    @else
                        <div>
                            {!! Html::link(url('/shop/add-to-cart/'.$product->id,[],false),'Details', ['class'=>'btn2 cu2-btn1']) !!}
                        </div>
                    @endif
                </div>

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