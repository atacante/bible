<div class="c-white-content c-blog">
    @if($articles->count())
        @foreach($articles as $article)
            <div class="related-item">
                <div class="clearfix">
                    <div class="item-header mb5">
                        <div class="cu-date1">&nbsp; {!! $article->humanLastUpdate() !!}</div>
                        <h4 class="h4-sub-kit pull-left">{{$article->title}}</h4>
                    </div>
                    <div class="item-body j-show-article wall-text1" data-link="{!! url('/blog/article/'.$article->id,[]) !!}" >{!! str_limit($article->text, $limit = 800, $end = '...<p>'.Html::link(url('/blog/article/'.$article->id,[]), 'View Details', ['class' => 'btn1-kit','style' => 'padding: 0 5px;'], true).'</p>') !!}</div>
                    <span class="pull-right">Posted by <b>{!! $article->user->name !!}</b></span>
                </div>
            </div>
        @endforeach
    @else
        Nothing Found
    @endif
</div>
<div>
    <div class="text-center">
        {!! $articles->appends(Request::input())->links() !!}
    </div>
</div>