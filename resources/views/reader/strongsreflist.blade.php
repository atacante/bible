@if(count($content['references']))
    <div class="j-bible-text">
    @foreach($content['references'] as $title => $reference)
        <div>
            <b>{!! link_to(
                            'reader/verse?'.http_build_query([
                                                                'book' => $reference['link']['book_id'],
                                                                'chapter' => $reference['link']['chapter_num'],
                                                                'verse' => $reference['link']['verse_num'],
                                                            ]),
                            $title) !!}
            </b>
        </div>
        <div>
            @foreach($reference['data'] as $lexiconCode => $lexiconData)
                <div>
                    {!! link_to('reader/verse?'.http_build_query([
                                                   'version' => $reference['bible_version'][$lexiconCode],
                                                   'book' => $reference['link']['book_id'],
                                                   'chapter' => $reference['link']['chapter_num'],
                                                   'verse' => $reference['link']['verse_num'],
                                               ]),
                                           strtoupper($lexiconCode)).": " !!}
                    <span class="verse-text j-verse-text" data-version="{!! $reference['bible_version'][$lexiconCode] !!}" data-verseid="{!! $reference['verse'][$lexiconCode]['id'] !!}">{!! ViewHelper::highlightLexiconText($lexiconData[0]['verse_part'],$reference['verse'][$lexiconCode]['verse_text']) !!}</span>
                </div>
            @endforeach
        </div>
    @endforeach
        </div>
@else
    <p class="text-center">No any results found</p>
@endif