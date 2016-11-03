@if($data['verseOfDay'])
    @if(Request::input('vod',false))
        @section('meta_description')
            <meta name="description" content="{{ $verse_day_text }}"/>
        @stop

        @section('meta_og')
            <meta property="og:title" content="Bible Study Company: Verse of a Day" />
            <meta property="og:image" content="{!! url('/images/logo.png') !!}" />
            <meta property="og:description" content="{{ $verse_day_text }}" />
        @stop

        @section('meta_twitter')
            <meta property="twitter:card" content="summary">
            <meta property="twitter:title" content="Bible Study Company: Verse of a Day">
            <meta property="twitter:image" content="{!! url('/images/logo.png') !!}" />
            <meta property="twitter:description" content="{{ $verse_day_text }}">
        @stop
    @endif
<h2 class="h2-2 mt7" id="day-verse">
    <i class="bs-verseoftheday cu-verseoftheday"></i>
    VERSE OF THE DAY
</h2>

<style>
    /* -------------- desctop ---------------- */
    .h-ill5 {
        @if (!$data['verseOfDay']->image)
            background-image: url('/images/p5-home.jpg');
        @else
            background-image: url('{{Config::get('app.verseOfDayImages').$data['verseOfDay']->image}}');
        @endif
    }
    /* -------------- mobile ---------------- */
    @media (max-width: 768px) {
        .h-ill5 {
            @if (!$data['verseOfDay']->image_mobile)
                background-image: url('/images/p5-home.jpg');
            @else
                background-image: url('{{Config::get('app.verseOfDayImages').$data['verseOfDay']->image_mobile}}');
            @endif
        }
    }
</style>

<div class="c-center-content2 mt8">
    <div class="home-ill-section h-ill5">

        <div class="ill-text-pos5">
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
                        ]),[],false) !!}">
                    {!! strtoupper($data['verseOfDay']->verse->booksListEn->book_name).' '.$data['verseOfDay']->verse->chapter_num.':'.$data['verseOfDay']->verse->verse_num !!}
                    </a>
                </span>
            </p>
            <div class="c-share-panel">
                <h4 class="h4-1">
                    SHARE WITH:
                </h4>
                <!-- Go to www.addthis.com/dashboard to customize your tools -->
                <div class="addthis_inline_share_toolbox c-social" data-url="{!! url('?vod=1') !!}"></div>
            </div>
        </div>

    </div>


</div>
@endif