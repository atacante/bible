@if($content['likes']->count())
    <div style="max-width: 300px;" class="clearfix">
        @foreach($content['likes'] as $like)
            <div class="like-item">
                <a href="#" title="{!! $like->name !!}" style="margin: 0 !important;">
                    @if($like->avatar)
                        <div class="user-default2" style="background: url('{!! $like->avatar!=''?Config::get('app.userAvatars').$like->id.'/thumbs/'.$like->avatar:'' !!}') center no-repeat;">

                        </div>
                    @else
                        <div class="user-default2">

                        </div>
                    @endif

                </a>
            </div>
        @endforeach
        @if($content['nextPage'])
            <div class="pull-left like-item">
                <a href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/likes/'.$item->id.'/full') !!}" title="Show All"  class="friend-item j-show-all-likes" style="margin: 0;">
                    <div class="like-img pull-left" style="">
                        <div class="no-avatar-text text-center show-more-likes">
                            ...
                        </div>
                    </div>
                </a>
            </div>
        @endif
    </div>
@else
    <p class="text-center">No likes yet</p>
@endif