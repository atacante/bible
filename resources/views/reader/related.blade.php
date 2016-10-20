{{-- ---------------- RELATED POSTS ---------------- --}}
@if($content['showRelated'] && $content['relatedItems']->count() && !Request::input('compare',false))

    <div class="my1-col-md-4 related-records">
        <div class="c-reader-content2"></div>
        <div class="inner-pad1 bord1">
            <h3 class="h3-related"><i class="bs-staroutlined cu-staroutlined"></i>Related Records <span class="ins-count">({{$content['relatedItems']->count()}})</span></h3>
            <div class="date-order-btn">
                <a href="{!! url('reader/read?'.http_build_query(array_merge(Request::input(),['relations-order' => Request::get('relations-order','desc') == 'asc'?'desc':'asc'])),[]) !!}">
                    <span class="fa fa-calendar"></span>
                    <span class="fa fa-sort-amount-{!! Request::get('relations-order','desc') !!}"></span>
                </a>
            </div>
        </div>


        @foreach($content['relatedItems'] as $item)
            <div class="inner-pad2 bord1">
                <div class="related-item">
                    <div class="item-header">
                        <a class="a-study" title="My Study Verse" href="{!! url('reader/my-study-verse?'.http_build_query([
                                            'version' => $content['version_code'],
                                            'book' => $item->verse->book_id,
                                            'chapter' => $item->verse->chapter_num,
                                            'verse' => $item->verse->verse_num,
                                        ]),[],false) !!}">
                            <i class="bs-study cu-study" aria-hidden="true"></i>
                        </a>

                        <a href="#verse{!! $item->verse->id !!}" data-verseid="{!! $item->verse->id !!}" class="j-highlight-verse title-verse">{!! ViewHelper::getVerseNum($item->verse) !!}</a>


                        <div class="verse-date">{!! $item->created_at->format($item::DFORMAT2) !!}</div>
                    </div>
                    <div class="item-body j-item-body" data-itemid="{!! $item->id !!}" , data-itemtype="{!! $item->type !!}">
                        @if($item->highlighted_text)
                            <div class="verse-block">
                                Verse: {!! str_limit(strip_tags($item->highlighted_text,'<p></p>'), $limit = 99999, $end = '...') !!}
                            </div>
                        @endif
                        <div class="verse-block-bott">
                            <i title="{!! ucfirst($item->type) !!} Entry" class="verse-icon {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                            {!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}
                        </div>
                        @if(Auth::user() && Auth::user()->isPremium() && $item->type == 'prayer')
                            <div class="addthis_inline_share_toolbox j-custom-sharing" data-url="{{url('/community')}}" data-title="{!! strip_tags($item->text) !!}"></div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach



    </div>
@endif
