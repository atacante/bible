<ul class="nav nav-pills nav-stacked nav-left community-menu c-white-content hide-menu j-hide-menu" role="menu">
    @if($categories->count())
         <li class="{{ (!Request::input('category') && !isset($article)) ? 'active' : '' }}" role="presentation">
             <a href="{{ url('blog') }}">All Categories</a>
             <span class="caret"></span>
         </li>
        @foreach($categories as $key => $category)
            <li role="separator" class="divider {{($key > 9)?' hidden j-hidden':'' }}" style=""></li>
            <li class="{{($key > 9)?'hidden j-hidden ':'' }}{{((Request::input('category') == $category->id)||(isset($article)&&($article->category_id == $category->id))) ? 'active' : '' }}" role="presentation">
                <a href="{{ url('blog?category='.$category->id) }}">{{ $category->title }}</a>
                <span class="caret"></span>
            </li>
        @endforeach
        @if($key > 9)
            <a href="#" class="btn1 load-more j-show-more">See More<i class='fa fa-angle-down' aria-hidden='true'></i></a>
        @endif
    @endif
</ul>