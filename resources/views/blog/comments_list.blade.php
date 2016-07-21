@if($article->comments->count())
    @foreach($article->comments as $comment)
        <div style="margin-top: 5px;">
            <div class="" style="position: absolute;">
                @if($comment->user->avatar)
                    <img class="img-thumbnail" height="54" width="54" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$comment->user->id.'/thumbs/'.$comment->user->avatar !!}"/>
                @else
                    <i class="fa fa-user fa-4x"></i>
                @endif
            </div>
            <div class="" style="margin-left: 60px; min-height: 54px;">
                {!! $comment->text !!}
            </div>
        </div>
    @endforeach
@endif