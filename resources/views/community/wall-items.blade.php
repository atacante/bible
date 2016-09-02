@if($content['entries']->count())
    @if(!ViewHelper::isRoute('groups.view') || (ViewHelper::isRoute('groups.view') && Auth::check() && (Auth::user()->id == $model->owner_id || in_array(Auth::user()->id,$model->members()->lists('users.id')->toArray()))))
        @foreach($content['entries'] as $item)
            <div class="related-item j-wall-item">
                {{--<div class="user-image cu-ui1"></div>--}}
                @if($item->user->avatar)
                    <div class="user-image cu-ui1" style="background: url('{!! $item->user->avatar!=''?Config::get('app.userAvatars').$item->user->id.'/thumbs/'.$item->user->avatar:'' !!}') center no-repeat;"></div>
                @else
                    <div class="user-image cu-ui1"></div>
                @endif
                <div class="item-header">
                    @if (ViewHelper::getRelatedItemIcon($item->type)!="")
                    <i title="{!! ucfirst($item->type) !!} Entry" class="verse-icon2 {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                    @endif
                    <span class="wall-text1">
                        <strong>{!! (Auth::user() && $item->user && Auth::user()->id == $item->user->id)?"You":($item->user?$item->user->name:'somebody') !!}</strong>
                        @if($item->type == 'status')
                            posted status update
                        @else
                            shared a {!! ucfirst($item->type) !!} Entry&nbsp;
                        @endif
                    </span>
                    <span class="">
                        @if(isset($item->verse))
                            <span >for&nbsp;</span>
                            <span class="">
                                {!! Html::link('/reader/verse?'.http_build_query(
                                    [
                                        'version' => $item->bible_version,
                                        'book' => $item->verse->book_id,
                                        'chapter' => $item->verse->chapter_num,
                                        'verse' => $item->verse->verse_num
                                    ]
                                    ),ViewHelper::getVerseNum($item->verse).($item->bible_version?' ('.ViewHelper::getVersionName($item->bible_version).')':''), ['class'=>'book-desc1']) !!}
                            </span>
                            <div class="cu-date1">&nbsp;at {!! $item->published_at->format($item::DFORMAT) !!}&nbsp;</div>
                        @endif
                    </span>
                </div>
                <div class="item-body j-item-body"  data-itemid="{!! $item->id !!}" data-itemtype="{!! $item->type !!}">
                    @if($item->highlighted_text)
                        <div class="verse-block">
                            Verse: <i>{!! str_limit(strip_tags($item->highlighted_text,'<p></p>'), $limit = 100, $end = '...') !!}</i>
                        </div>
                    @endif
                    <div class="wall-text1">{!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}</div>
                    <div id="img-thumb-preview" class="edit-images-thumbs j-with-images clearfix">
                        @if($content[ViewHelper::getEntryControllerName($item->type)]['images']->count())
                            @foreach($content[ViewHelper::getEntryControllerName($item->type)]['images'][$item->id] as $image)
                                <div class="img-thumb pull-left">
                                    <img height="100" width="100" src="{!! Config::get('app.'.ViewHelper::getEntryControllerName($item->type).'Images').$item->id.'/thumbs/'.$image->image !!}" class="j-image-thumb" />
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
                <div class="item-footer j-item-footer">
                    <div class="item-actions j-item-actions cu1-actions">
                        @role('user')
                        <a href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/save-like/'.$item->id) !!}" class="wall-like-btn j-wall-like-btn {!! in_array($item->id,ViewHelper::getMyLikes($item->type))?'hidden':'' !!}" data-type="{!! $item->type !!}" data-likeslink="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/likes/'.$item->id) !!}">
                            <i class="fa fa-btn fa-heart"></i>
                            @if($item->likescount)
                            {{--Like--}} <span class="j-likes-count">{!! $item->likescount !!}</span>
                            @endif
                        </a>
                        <a href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/remove-like/'.$item->id) !!}" class="wall-like-btn j-wall-like-btn liked {!! in_array($item->id,ViewHelper::getMyLikes($item->type))?'':'hidden' !!}" data-type="{!! $item->type !!}" data-likeslink="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/likes/'.$item->id) !!}">
                            <i class="fa fa-btn fa-heart"></i>
                            @if($item->likescount)
                            {{--Like--}} <span class="j-likes-count">{!! $item->likescount !!}</span>
                            @endif
                        </a>
                        @endrole
                        <a href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/comments/'.$item->id) !!}" class="j-wall-item-comments" data-type="{!! $item->type !!}">
                            <i class="fa fa-btn fa-comments"></i>
                            @if($item->commentscount)
                            {{--Comments--}} <span class="j-comments-count">{!! $item->commentscount !!}</span>
                            @endif
                        </a>
                        @role('user')
                        <a title="{!! in_array($item->id,ViewHelper::getMyContentReports($item->type))?'You have reported inappropriate content':'Report inappropriate content' !!}" href="{!! url('/community/report/'.$item->type.'/'.$item->id) !!}" class="j-item-report {!! in_array($item->id,ViewHelper::getMyContentReports($item->type))?'reported disabled':'' !!}" >
                            <i class="fa fa-btn fa-flag"></i>
                            {{--Report--}}
                        </a>
                        @endrole
                    </div>
                    <div class="clearfix j-item-comments">

                    </div>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-center">This is a private group, please join to view the wall</p>
    @endif
@else
    <p class="text-center">There are no comments available.</p>
@endif
@if( $content['nextPage'])
    <div class="load-more-block">
        <div class="text-center">
            {!! Html::link('/community/wall?'.http_build_query(
                array_merge(Request::input(),['page' => $content['nextPage']])
            ),'Load More', ['class'=>'btn btn-default load-more','style' => 'width:100%;']) !!}
        </div>
    </div>
@endif