@if($products->count())
    @foreach($products as $product)
        <div class="related-item">
{{--            <div class="pull-left"><img src="{!! Config::get('app.blogImages').'thumbs/'.$product->img !!}"
                               class="img-thumbnail" alt="" style="cursor: pointer;">
            </div>--}}
            <div class="clearfix">
                <div class="item-header">{{$product->name}}</div>
                <div class="item-body j-show-article" data-link="{!! url('/shop/product/'.$product->id,[],false) !!}" >{!! $product->short_description !!}</div>
            </div>
        </div>
    @endforeach
@else
    Nothing Found
@endif

<div class="row">
    <div class="text-center">
        {!! $products->appends(Request::input())->links() !!}
    </div>
</div>