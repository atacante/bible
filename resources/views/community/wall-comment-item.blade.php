<div class="c-people-comment">
    <div class="" style="position: absolute;">
        @if($comment->user && $comment->user->avatar)
            <div class="user-image cu-ui2" style="background: url('{!! $comment->user->avatar!=''?Config::get('app.userAvatars').$comment->user->id.'/thumbs/'.$comment->user->avatar:'' !!}') center no-repeat;"></div>
        @else
            <div class="user-image cu-ui2"></div>
        @endif
    </div>
    <div class="people-comment-text wall-text1">
        <div><strong>{!! $comment->user?$comment->user->name:'somebody' !!}</strong>
            <span class="cu-date2">{!! $comment->humanFormat('created_at') !!}</span>
        </div>
        <div>{!! $comment->text !!}</div>
    </div>
</div>