@if($articles->count())
    @foreach($articles as $article)
        <div class="related-item">
{{--            <div class="pull-left"><img src="{!! Config::get('app.blogImages').'thumbs/'.$article->img !!}"
                               class="img-thumbnail" alt="" style="cursor: pointer;">
            </div>--}}
            <div class="clearfix">
                <div class="item-header">{{$article->title}}</div>
                <div class="item-body">{!! str_limit($article->text, $limit = 800, $end = '... '.Html::link(url('/blog/article/'.$article->id,[],false), 'View Details', ['class' => 'btn btn-success','style' => 'padding: 0 5px;'], true)) !!}</div>
            </div>
        </div>
    @endforeach
@else
    Nothing Found
@endif

<div class="row">
    <div class="text-center">
        {!! $articles->appends(Request::input())->links() !!}
    </div>
</div>