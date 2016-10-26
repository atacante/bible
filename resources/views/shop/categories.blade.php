<div class="resp-text-center">
    <ul class="nav nav-pills tabs-nav resp-padd-search" role="menu">
        @if($categories->count())
             <li class="{{ !Request::input('category') ? 'active' : '' }}" role="presentation">
                 <a href="{{ url('shop') }}">All Categories</a>
             </li>
            @foreach($categories as $key => $category)
                <li class="{{($key > 9)?'hidden j-hidden ':'' }}{{(Request::input('category') == $category->id) ? 'active' : '' }}" role="presentation">
                    <a href="{{ url('shop?category='.$category->id) }}">{{ $category->title }}</a>
                </li>
                <li role="separator" class="divider {{($key > 9)?' hidden j-hidden':'' }}" style=""></li>
            @endforeach
            @if($key > 9)
                <a href="#" class="j-show-more">See More<i class='fa fa-angle-down' aria-hidden='true'></i></a>
            @endif
        @endif
        <li class="pull-right  resp-tab-search" style="">@include('shop.search')</li>
    </ul>
</div>