@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <div class="c-white-content">
                <div class="inner-pad1">

                    <div class="location-item-details">
                        <div class="row">
                            <div class="col-xs-4">


                            @if($product->images->count())
                                <div id="product-{!! $product->id !!}" class="carousel slide" data-ride="carousel"
                                     data-interval="{!! rand(5000,7000) !!}">
                                    <!-- Indicators -->
                                    @if($product->images->count() > 1)
                                        <ol class="carousel-indicators">
                                            @foreach($product->images as $key => $image)
                                                <li data-target="#product-{!! $product->id !!}"
                                                    data-slide-to="{!! $key !!}"
                                                    class="{!! ($key == 0?'active':'') !!}"></li>
                                            @endforeach
                                        </ol>
                                        @endif

                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner" role="listbox">
                                            @foreach($product->images as $key => $image)
                                                <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                                    <img src="{!! Config::get('app.productImages').'thumbs/'.$image->image !!}"
                                                         class="img-thumbnail" alt="" style="cursor: pointer;">
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Controls -->
                                        @if($product->images->count() > 1)
                                            <a class="left carousel-control" href="#product-{!! $product->id !!}"
                                               role="button" data-slide="prev">
                                                        <span class="glyphicon glyphicon-chevron-left"
                                                              aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#product-{!! $product->id !!}"
                                               role="button" data-slide="next">
                                                        <span class="glyphicon glyphicon-chevron-right"
                                                              aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        @endif
                                </div>
                            @else
                                <div class="no-image img-thumbnail">
                                    <div class="no-image-text text-center">No image</div>
                                </div>
                            @endif



                            </div>

                            <div class="col-xs-8">
                                <h3 class="h3-kit">{!! $product->name !!}</h3>
                                <div class="product-label">
                                    Price:
                                </div>
                                <div class="price-label2">
                                     ${!! $product->price !!}
                                </div>

                                <div>
                                    {!! $product->long_description !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! Html::link(url('/shop/add-to-cart/'.$product->id,[],false),'Add To Cart', ['class'=>'btn btn-success']) !!}
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/shop'),'Back to list', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
@stop
