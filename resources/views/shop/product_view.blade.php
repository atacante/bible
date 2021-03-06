@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    {!! Form::open(['method' => 'get','url' => '/shop/add-to-cart/'.$product->id]) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="c-white-content">
                <div class="inner-pad1">
                        <div class="row">
                            <div class="col-md-4">


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
                                                    <div class="product-image-big" data-dz-thumbnail="" data-image="{!!Config::get('app.productImages').'thumbs/'.$image->image !!}" style="background: url('{!! $image->image!=''?Config::get('app.productImages').'thumbs/'.$image->image:'' !!}') center no-repeat;"></div>
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
                                <div class="product-image-big" data-dz-thumbnail="" data-image="" style="">
                                    <i class="bs-producticon cu-producticon"></i>
                                </div>
                            @endif



                            </div>

                            <div class="col-md-8">
                                <h3 class="h3-kit">{!! $product->name !!}</h3>

                                <h4 class="h4-sub-kit mt8">
                                    Price:
                                </h4>
                                <div class="price-label2">
                                     ${!! $product->price !!}
                                </div>

                                <h4 class="h4-sub-kit mt13">
                                    Description:
                                </h4>
                                <div class="p-medium">
                                    {!! $product->long_description !!}
                                </div>
                                @if($product->colors)
                                    <div class="form-group">
                                        {!! Form::label('color', 'Color') !!}
                                        {!! Form::select('color', $product->getColors(), null,['class' => 'form-control', 'style' => 'width:20%']) !!}
                                    </div>
                                @endif
                                @if($product->sizes)
                                    <div class="form-group">
                                        {!! Form::label('size', 'Size') !!}
                                        {!! Form::select('size', $product->getSizes(), null,['class' => 'form-control', 'style' => 'width:20%']) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt14 mb1">
        <div class="col-xs-12 text-right">
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/shop'),'Back to list', ['class'=>'btn2 ml1']) !!}
            {!! Form::button('Add To Cart', ['type'=>'submit', 'class'=>'bg-color-transparent btn1 ml1']) !!}
        </div>
    </div>
    {!! Form::close() !!}
@stop
