<ul class="nav nav-pills nav-stacked nav-left community-menu" role="menu">
    @if($categories->count())
         <li class="{{ !Request::input('category') ? 'active' : '' }}" role="presentation">
             <a href="{{ url('shop') }}">All Categories</a>
         </li>
        @foreach($categories as $key => $category)
            <li class="{{($key > 9)?'hidden j-hidden ':'' }}{{(Request::input('category') == $category->id) ? 'active' : '' }}" role="presentation">
                <a href="{{ url('shop?category='.$category->id) }}"><i class="fa fa-btn fa-newspaper-o"></i>{{ $category->title }}</a>
            </li>
            <li role="separator" class="divider {{($key > 9)?' hidden j-hidden':'' }}" style=""></li>
        @endforeach
        @if($key > 9)
            <a href="#" class="j-show-more">show more <i class='fa fa-angle-down' aria-hidden='true'></i></a>
        @endif
    @endif
</ul>