@extends('layouts.home')
@section('meta_description')
    <meta name="description" content="{{ strip_tags($verse_day) }}"/>
@stop

@section('meta_twitter')
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Verse of a Day">
    <meta property="twitter:description" content="{{ strip_tags($verse_day) }}">
@stop

@section('content')
    <div class="container-fluid main-container">
        <div class="inner-container">
            <div class="bg-g1">
                <div class="in-inner-container text-center">
                    @include('partials.nav-home')
                    <h1 class="h1-1 mt1">{!! $homedata['home_main_block']->text !!}</h1>
                    <h2 class="h2-3">{!! $homedata['home_main_block']->description !!}</h2>
                    <a href="{{ URL::to('/reader/read?version=nasb') }}" class="btn1 mt2 mb1">READ BIBLE NOW</a>
                </div>
            </div>

            <div class="alert-container home-alert-container">
                @notification()
            </div>

            <div class="in-inner-container">
                <div class="c-center-content site-index">

                    <section class="home-ill-section h-ill1" style="background: url('/images/{{ $homedata['home_reader_block']->background }}') center top no-repeat;">
                        <div class="ill-text-pos1">
                            <h2 class="h2-1">
                                {!! $homedata['home_reader_block']->text !!}
                            </h2>
                            <p class="p-1 mt5">{!! $homedata['home_reader_block']->description !!}</p>
                            <a href="{{ URL::to('/reader/read?version=nasb') }}" class="btn2 mt3">Go To Reader</a>
                        </div>
                    </section>

                    <section class="home-ill-section h-ill2" style="background: url('/images/{{ $homedata['home_journey_block']->background }}') right center no-repeat;">
                        <div class="ill-text-pos2">
                            <h2 class="h2-1 color3">
                                {!! $homedata['home_journey_block']->text !!}
                            </h2>
                            <p class="p-1 mt5 color2">{!! $homedata['home_journey_block']->description !!}</p>
                            <a href="{{ !Auth::check()?URL::to('auth/register'): URL::to('user/my-journey') }}" class="btn2 mt3">{{ Auth::check()?'':'SIGNUP TO ' }}START YOUR JOURNEY</a>
                        </div>
                    </section>

                    <section class="home-ill-section h-ill3" style="background: url('/images/{{ $homedata['home_community_block']->background }}') center center no-repeat;">
                        <div class="ill-text-pos3">
                            <h2 class="h2-1">
                                {!! $homedata['home_community_block']->text !!}
                            </h2>
                            <p class="p-1 mt5">{!! $homedata['home_community_block']->description !!}</p>
                            <a href="{{ URL::to('/community') }}" class="btn2 mt3">COMMUNITY OVERVIEW</a>
                        </div>
                    </section>

                    <section class="home-ill-section h-ill4" style="background: url('/images/{{ $homedata['home_explore_block']->background }}') right center no-repeat;">
                        <div class="ill-text-pos4">
                            <h2 class="h2-1">
                                {!! $homedata['home_explore_block']->text !!}
                            </h2>
                            <p class="p-1 mt5 color2">
                               {!! $homedata['home_explore_block']->description !!}
                            </p>
                            <a href="{{ URL::to('/locations/list') }}" class="btn2 mt3">EXPLORE LOCATIONS & PEOPLE</a>
                        </div>
                    </section>

                </div>
                @include('partials.verse-of-day')
            </div>
        </div>
    </div>

        <div class="container-fluid c-center-content3 mt11">
            <div class="row">
                <div class="col-xs-12">
                    <div class="text-center pad2">
                        <i class="bs-gift cu-gift"></i>
                        <h2 class="h2-1 color1 text-center mt9 gift-title">
                            SEND A <span>GIFT OF LOVE</span> TO A FRIEND
                        </h2>

                        <div class="c-center-content-shop">
                            <div class="mt10 c-home-products">
                                @foreach($products as $product)
                                <div class="product-item">
                                    <a href="{!! ($product->external_link)? $product->external_link : url('/shop/product/'.$product->id,[]) !!}" target="{!! ($product->external_link)?'_blank':'_self' !!}" class="gift-item">
                                        <div class="gift1" style="background:url('{{($product->images->count())?Config::get('app.productImages').'thumbs/'.$product->images[0]->image:'/images/cup.png' }}')  50% 5% no-repeat;">
                                        </div>
                                        <h4 class="h4-gift">
                                            {{$product->name}}
                                        </h4>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <a href="{{ URL::to('/shop') }}" class="btn3 mt3">VIEW ALL SHOP ITEMS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!Auth::check())
        <div class="container-fluid">
            <div class="row mb1">
                <div class="col-lg-12 text-center pad1">
                    <h2 class="h2-1 mt12">
                        <span>Read, EXPLORE, COMPARE, Share</span>
                    </h2>
                    <a href="{{ URL::to('auth/register') }}" class="btn1 mt13">SIGN UP NOW</a>
                </div>
            </div>
        </div>
        @endif


        @include('admin.partials.deletepop')
        @include('partials.popup')


@endsection
