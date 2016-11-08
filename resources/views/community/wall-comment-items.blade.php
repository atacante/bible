@if($content['otherCommentsCount'] > 0)
    <div class="text-center">
        {!! Html::link('/'.ViewHelper::getEntryControllerName($item->type).'/comments/'.$item->id.'?'.http_build_query(
            array_merge(Request::input(),['page' => 'all'])
        ),'View '.$content['otherCommentsCount'].' more comment'.($content['otherCommentsCount'] > 1?'s':''), ['class'=>'j-load-more-comments','style' => '']) !!}
    </div>
@endif
@if($content['comments']->count())
    @foreach($content['comments'] as $comment)
        @include('community.wall-comment-item')
    @endforeach
@endif