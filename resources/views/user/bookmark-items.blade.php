@if($content['bookmarks']->count())
    <div class="bookmarks-list clearfix">
        @foreach($content['bookmarks'] as $bookmark)
            <div class="bookmark-item">
                @if($bookmark->pivot->bookmark_type == App\User::BOOKMARK_CHAPTER)
                    {{ Html::link(url('reader/read?'.http_build_query(
                        [
                            'version' => $bookmark->pivot->bible_version,
                            'book' => $bookmark->book_id,
                            'chapter' => $bookmark->chapter_num,
                        ])/*."#verse".$bookmark->id*/,[],false), ViewHelper::getChapterNum($bookmark), ['class' => '','style' => ''], true)
                    }}
                @elseif($bookmark->pivot->bookmark_type == App\User::BOOKMARK_VERSE)
                    {{ Html::link(url('reader/verse?'.http_build_query(
                        [
                            'version' => $bookmark->pivot->bible_version,
                            'book' => $bookmark->book_id,
                            'chapter' => $bookmark->chapter_num,
                            'verse' => $bookmark->verse_num,
                        ]),[],false), ViewHelper::getVerseNum($bookmark), ['class' => '','style' => ''], true)
                    }}
                @endif
                    <a href="{!! url('reader/delete-bookmark',[$bookmark->pivot->bookmark_type,$bookmark->pivot->bible_version,$bookmark->id]) !!}" class="btn-reset j-remove-bookmark" style="position: relative; top: auto; right: auto; display: inline-block; font-size: 17px; height: 20px; width: 20px; line-height: 17px;">×</a>
            </div>
        @endforeach
        @if($content['nextPage'])
            <div class="u-footer load-more-block text-center" style="margin-top: 15px;">
                {!! Html::link('/user/my-bookmarks?'.http_build_query(
                    array_merge(Request::input(),['page' => $content['nextPage']])
                ),'Load More', ['class'=>'btn1 load-more','style' => 'width:100%;']) !!}
            </div>
        @endif
    </div>
@else
    <p class="text-center">No results found</p>
@endif
