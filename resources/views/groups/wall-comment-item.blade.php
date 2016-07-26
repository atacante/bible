<div style="margin-top: 5px;">
    <div class="" style="position: absolute;">
        @if($comment->user && $comment->user->avatar)
            <img class="img-thumbnail" height="54" width="54" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$comment->user->id.'/thumbs/'.$comment->user->avatar !!}"/>
        @else
            <div class="no-avatar-midi img-thumbnail">
                <div class="no-avatar-text text-center"><i class="fa fa-user" style="font-size: 30px;"></i></div>
            </div>
        @endif
    </div>
    <div class="" style="margin-left: 60px; min-height: 54px;">
        <div><strong>{!! $comment->user?$comment->user->name:'somebody' !!}</strong></div>
        <div>{!! $comment->text !!}</div>
    </div>
</div>