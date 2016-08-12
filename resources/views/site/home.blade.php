@extends('layouts.home')

@section('content')
    <div class="c-center-content site-index">
        <section class="home-ill-section h-ill1">
            <div class="pull-right mt4 mr1">
                <h2 class="h2-1">
                    STUDY THE <span>BIBLE</span>
                </h2>
                <p class="p-1 mt5">Learn and compare between different<br> versions of bible.</p>
                <a href="{{ URL::to('/reader/overview') }}" class="btn2 mt3">SEE BIBLE VERSIONS</a>
            </div>
        </section>
        <section class="home-ill-section h-ill2">
            <div class="pull-right mt4 mr1">
                <h2 class="h2-1 color1">
                    <span>Create</span> your own journey
                </h2>
                <p class="p-1 mt5 color1">Make a notes and write a journal. Share your favourites with<br>
                    friends. Do something more. Do one more thing.</p>
                <a href="{{ URL::to('auth/register') }}" class="btn3 mt3">SIGNUP TO START YOUR JOURNEY</a>
            </div>
        </section>
        <section class="home-ill-section h-ill3">
            <div class="pull-right mt6 mr3">
                <h2 class="h2-1">
                    get involved to<br>
                    <span>OUR community</span>
                </h2>
                <p class="p-1 mt5">Share your thoughts, prayers with others.<br>
                    Make a new friends.<br>
                    Probably more impressive description.
                </p>
                <a href="{{ URL::to('/community') }}" class="btn2 mt3">COMMUNITY OVERVIEW</a>
            </div>
        </section>
        <section class="home-ill-section h-ill4">
            <div class="pull-right mt4 mr1">
                <h2 class="h2-1">
                    <span>EXPLORE</span> the SYMBOLISM
                </h2>
                <p class="p-1 mt5 color2">
                    Learn more about historic locations & people.<br>
                    Probably more desription here.
                </p>
                <a href="{{ URL::to('/locations/list') }}" class="btn2 mt3">EXPLORE LOCATIONS & PEOPLE</a>
            </div>
        </section>
    </div>
    <h2 class="h2-2 mt7"><i class="bs-verseoftheday cu-verseoftheday"></i>VERSE OF THE DAY</h2>
    <div class="c-center-content2 mt8">
        <div class="col-left1 h-ill5">
            <div class="luke">LUKE 6:37</div>
        </div>
        <div class="col-right1">
            <p class="p-2">
               Do not judge, and you will not be judged.<br>
               Do not condemn, and you will not be condemned.<br>
               Forgive, and you will be forgiven.<br>
                <br>
               <span class="ital">LUKE 6:37</span>
            </p>
            <div class="c-share-panel">
                <h4 class="h4-1">
                    SHARE WITH:
                </h4>
                <ul class="c-social">
                    <li><a href="htpp://twitter.com" target="_blank" class="bs-twitter"></a></li>
                    <li><a href="htpp://facebook.com" target="_blank" class="bs-fb"></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="c-center-content3 mt11 text-center">
        <i class="bs-gift cu-gift"></i>
        <h2 class="h2-1 color1 text-center mt9">
            SEND A <span>GIFT OF LOVE</span> TO A FRIEND
        </h2>
        <div class="row mt10">
            <div class="col-lg-4">
                <a href="{{ URL::to('/shop/product/5') }}" class="gift-item">
                    <div class="gift1">
                    </div>
                    <h4 class="h4-gift">
                        our GREAT EBOOK
                    </h4>
                </a>
            </div>
            <div class="col-lg-4">
                <a href="{{ URL::to('/shop/product/6') }}" class="gift-item">
                    <div class="gift2">
                    </div>
                    <h4 class="h4-gift">
                        BLESSED T-SHIRT
                    </h4>
                </a>
            </div>
            <div class="col-lg-4">
                <a href="{{ URL::to('/shop/product/2') }}" class="gift-item">
                    <div class="gift3">
                    </div>
                    <h4 class="h4-gift">
                        JUST A CUP
                    </h4>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <a href="{{ URL::to('/shop') }}" class="btn3 mt3">VIEW ALL SHOP ITEMS</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 text-center pad1">
            <h2 class="h2-1 mt12">
                <span>Read, EXPLORE, COMPARE, Share</span>
            </h2>
            <a href="{{ URL::to('auth/register') }}" class="btn1 mt13">SIGN UP NOW</a>
        </div>
    </div>
@endsection
