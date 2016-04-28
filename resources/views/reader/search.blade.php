@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">Bible Search</h3>
        </div>
    </div>
    <div class="row j-bible-text" style="line-height: 30px;">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">Old Testament</h4>
                </div>
            </div>
            @if(count($content['oldTestamentVerses']))
                @foreach($content['oldTestamentVerses'] as $verse)
                    <div>
                        <b>{!! link_to(
                            'reader/verse?'.http_build_query([
                                                                /*'version' => $content['version_code'],*/
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]),
                            $verse->booksListEn->book_name." ".$verse->chapter_num  .":".$verse->verse_num) !!}
                        </b>
                    </div>
                    <div class="j-verse-text" data-version="" data-verseid="{!! $verse->id !!}"> {!! $verse->highlighted_verse_text !!}</div>
                @endforeach
            @else
                <p class="text-center">No any results found</p>
            @endif
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">New Testament</h4>
                </div>
            </div>
            @if(count($content['newTestamentVerses']))
                @foreach($content['newTestamentVerses'] as $verse)
                    <div>
                        <b>{!! link_to(
                            'reader/verse?'.http_build_query([
                                                                /*'version' => $content['version_code'],*/
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]),
                            $verse->booksListEn->book_name." ".$verse->chapter_num  .":".$verse->verse_num) !!}
                        </b>
                    </div>
                    <div class="j-verse-text" data-version="" data-verseid="{!! $verse->id !!}"> {!! $verse->highlighted_verse_text !!}</div>
                @endforeach
            @else
                <p class="text-center">No any results found</p>
            @endif
        </div>

        {{--@foreach($content['versions'] as $code => $version)--}}
        {{--<h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[],false), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>--}}
        {{--@foreach($version['verses'] as $verse)--}}
        {{--<span style="">--}}
        {{--<b>{!! link_to('#', $title = $verse->verse_num) !!}</b>&nbsp;{!! $verse->verse_text !!}--}}
        {{--</span>--}}
        {{--@endforeach--}}
        {{--@endforeach--}}
    </div>
    <div class="text-center">
        @if($content['oldTestamentVerses']->count() >= $content['newTestamentVerses']->count())
            {!! $content['oldTestamentVerses']->appends(Request::input())->links() !!}
        @else
            {!! $content['newTestamentVerses']->appends(Request::input())->links() !!}
        @endif
    </div>
@stop
