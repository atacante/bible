@if($content['entries']->count())
    @foreach($content['entries'] as $item)
        <div class="related-item">
            <div class="item-header">
                <i title="{!! ucfirst($item->type) !!} Entry"
                   class="pull-left fa fa-btn {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                <div class="pull-left">
                    <strong>{!! (Auth::user() && $item->user && Auth::user()->id == $item->user->id)?"You":($item->user?$item->user->name:'somebody') !!}</strong>
                    @if($item->type == 'status')
                        posted status update
                    @else
                        shared a {!! ucfirst($item->type) !!} Entry&nbsp;
                    @endif
                </div>
                <div class="pull-left">
                    @if($item->verse)
                        <div class="pull-left">for&nbsp;</div>
                        <div class="pull-left">
                            {!! Html::link('/reader/verse?'.http_build_query(
                                [
                                    'version' => $item->bible_version,
                                    'book' => $item->verse->book_id,
                                    'chapter' => $item->verse->chapter_num,
                                    'verse' => $item->verse->verse_num
                                ]
                                ),ViewHelper::getVerseNum($item->verse).($item->bible_version?' ('.ViewHelper::getVersionName($item->bible_version).')':''), ['class'=>'label label-success','style' => 'display:block; line-height: 14px;']) !!}
                        </div>
                        <div class="pull-left">&nbsp;at {!! $item->published_at->format('m/d/Y') !!}&nbsp;</div>
                    @endif
                </div>
                {{--<a title="My Study Verse" href="{!! url('reader/my-study-verse?'.http_build_query([
                    'version' => $content['version_code'],
                    'book' => $item->verse->book_id,
                    'chapter' => $item->verse->chapter_num,
                    'verse' => $item->verse->verse_num,
                ]),[],false) !!}">
                    --}}{{--<i class="fa fa-pencil pull-right" aria-hidden="true"></i>--}}{{--
                    <i class="fa fa-location-arrow fa-graduation-cap pull-right" aria-hidden="true"></i>
                </a>--}}
                <div class="pull-right"></div>
            </div>
            <div class="item-body j-item-body" data-itemid="{!! $item->id !!}" , data-itemtype="{!! $item->type !!}">
                @if($item->highlighted_text)
                    <div class="verse-block">
                        Verse: <i>{!! str_limit(strip_tags($item->highlighted_text,'<p></p>'), $limit = 100, $end = '...') !!}</i>
                    </div>
                @endif
                {!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}
            </div>
            <div class="item-footer">
                <div class="item-actions">
                    <a href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/save-like') !!}" data-type="{!! $item->type !!}">
                        <i class="fa fa-btn fa-heart"></i>
                        Like
                    </a>
                    <a href="{!! url('/'.ViewHelper::getEntryControllerName($item->type).'/comments/'.$item->id) !!}" class="j-wall-item-comments" data-type="{!! $item->type !!}">
                        <i class="fa fa-btn fa-comments"></i>
                        Comments
                    </a>
                </div>
                <div class="clearfix j-item-comments">

                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="text-center">No any results found</p>
@endif
@if( $content['nextPage'])
    <div class="row load-more-block">
        <div class="text-center">
            {!! Html::link('/community/wall?'.http_build_query(
                array_merge(Request::input(),['page' => $content['nextPage']])
            ),'Load More', ['class'=>'btn btn-default load-more','style' => '']) !!}
            {{--                    {!! $content['entries']->appends(Request::input())->links() !!}--}}
        </div>
    </div>
@endif