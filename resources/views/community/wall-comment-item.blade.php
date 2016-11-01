<div class="c-people-comment j-wall-comment">
    @if(Auth::check() && Auth::user()->id == $comment->user_id)
    <div style="position: absolute; right: 30px;">
        <a class="j-delete-comment" href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/delete-comment/'.$comment->id) !!}">
            <i class="bs-close"></i>
        </a>
        {{--<a title="Delete comment" href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/delete-comment/'.$comment->id) !!}" data-toggle="modal"--}}
           {{--data-target="#confirm-delete" data-header="Delete Confirmation"--}}
           {{--data-confirm="Are you sure you want to delete this comment?">--}}
            {{--<i class="bs-close"></i>--}}
        {{--</a>--}}
    </div>
    @endif
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