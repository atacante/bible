<div class="row cu1-row">
@if(count($products))
    @foreach($products as $product)
        <div data-itemid="{!! $product->id !!}" class="my-item j-show-product" data-link="{!! ($product->external_link)? $product->external_link : url('/shop/product/'.$product->id,[],false) !!}">
            <div class="my-inner-location-item">


                @if($product->images->count())
                    <a href="{!! ($product->external_link)? $product->external_link : url('/shop/product/'.$product->id,[],false) !!}" class="product-image" data-dz-thumbnail="" data-image="{!!Config::get('app.productImages').'thumbs/'.$product->images[0]->image !!}" style="background: url('{!! $product->images[0]->image!=''?Config::get('app.productImages').'thumbs/'.$product->images[0]->image:'' !!}') center no-repeat;"></a>
                @else
                    <a href="{!! ($product->external_link)? $product->external_link : url('/shop/product/'.$product->id,[],false) !!}" class="product-image" data-dz-thumbnail="" data-image="" style="">
                        <i class="bs-producticon cu-producticon"></i>
                    </a>
                @endif

                <div class="c-inner-location-text">
                    <h4 class="h4-locations">{!! $product->name !!}</h4>
                    <p>
                        {!! str_limit(strip_tags($product->short_description,'<p></p>'), $limit = 140, $end = '... ') !!}
                    </p>
                    <div class="price-label">
                        ${!! $product->price !!}
                    </div>

                    @if($product->external_link)
                        <div>
                            <a class="btn1 cu2-2-btn1 j-show-product" href="{{$product->external_link}}" data-link = "{{$product->external_link}}">Details <i class="bs-arrowrt cu-arrowrt"></i></a>
                        </div>
                    @else
                        <a class="btn4 btn4-cu2" href="{{url('/shop/add-to-cart/'.$product->id,[],false)}}"><i class="bs-cart cu-cart"></i></a>
                        {!! Html::link(url('/shop/product/'.$product->id,[],false),'Details', ['class'=>'btn2 cu2-1-btn1']) !!}
                    @endif
                </div>

            </div>
        </div>
    @endforeach
@else
    <p class="text-center">No any results found</p>
@endif
</div>
<div class="row">
    <div class="col-xs-12 text-center">
        {!! $products->appends(Request::input())->links() !!}
    </div>
</div>