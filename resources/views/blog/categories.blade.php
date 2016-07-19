<ul class="nav nav-pills nav-stacked nav-left community-menu" role="menu">
    @if($categories->count())
        @foreach($categories as $category)
            <li class="{{ (Request::is('blog/category/'.$category->id) ? 'active' : '') }}" role="presentation">
                <a href="{{ url('blog/category/'.$category->id) }}"><i class="fa fa-btn fa-newspaper-o"></i>{{ $category->title }}</a>
            </li>
            <li role="separator" class="divider" style=""></li>
        @endforeach
    @endif
</ul>