@if($articles->count())
    @foreach($articles as $article)
        <div class="related-item">
            <div class="item-header">{{$article->title}}</div>
            <div class="item-body j-item-body">{{$article->text}}</div>
        </div>
    @endforeach
@else
    Nothing
@endif