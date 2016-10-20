@if($data['verseOfDay'])
<h2 class="h2-2 mt7" id="day-verse"><i class="bs-verseoftheday cu-verseoftheday"></i>VERSE OF THE DAY</h2>
<div class="c-center-content2 mt8">
    <div class="col-left1 h-ill5"
         style="background:
                    url('{{ (!$data['verseOfDay']->image)? '/images/p5-home.jpg':
                      Config::get('app.verseOfDayImages').$data['verseOfDay']->image }}') left center no-repeat;
                background-size: cover; ">
        {{--<div class="luke">LUKE 6:37</div>--}}
    </div>
    <div class="col-right1">
        <p class="p-2">
            {!! $data['verseOfDay']->verse->verse_text !!}
        </p>
        <br />
        <p class="p-2">
            <span class="ital">
                <a href="{!! url('reader/verse?'.http_build_query(
                    [
                        'book' => $data['verseOfDay']->verse->book_id,
                        'chapter' => $data['verseOfDay']->verse->chapter_num,
                        'verse' => $data['verseOfDay']->verse->verse_num
                    ]),[],false) !!}" class="" style="color: #7b828a;">
                {!! strtoupper($data['verseOfDay']->verse->booksListEn->book_name).' '.$data['verseOfDay']->verse->chapter_num.':'.$data['verseOfDay']->verse->verse_num !!}
                </a>
            </span>
        </p>
        <div class="c-share-panel">
            <h4 class="h4-1">
                SHARE WITH:
            </h4>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_inline_share_toolbox c-social"></div>
        </div>
    </div>
</div>
@endif