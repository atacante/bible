@if($content['comments']->count())
    @foreach($content['comments'] as $comment)
        @include('community.wall-comment-item')
    @endforeach
@endif
@if( $content['nextPage'])
    <div class="load-more-block" style="margin-top: 10px;">
        <div class="text-center">
            {!! Html::link('/'.ViewHelper::getEntryControllerName($item->type).'/comments/'.$item->id.'?'.http_build_query(
                array_merge(Request::input(),['page' => $content['nextPage']])
            ),'Load More', ['class'=>'btn btn-default load-more j-load-more-comments','style' => 'width:100%;']) !!}
        </div>
    </div>
@endif