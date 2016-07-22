@if($content['likes']->count())
    <div style="max-width: 300px;" class="clearfix">
        @foreach($content['likes'] as $like)
            <div class="pull-left like-item">
                <a href="#" title="{!! $like->name !!}"  class="friend-item" style="margin: 0;">
                    <div class="like-img pull-left" style="">
                        @if($like->avatar)
                            <img class="img-thumbnail-mini" height="40" width="40" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$like->id.'/thumbs/'.$like->avatar !!}"/>
                        @else
                            <div class="no-avatar-mini img-thumbnail-mini">
                                <div class="no-avatar-text text-center"><i class="fa fa-user" style="font-size:24px;"></i></div>
                            </div>
                        @endif
                    </div>
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