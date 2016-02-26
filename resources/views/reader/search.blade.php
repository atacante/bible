@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row col-md-12">
        <h3 class="text-center">Bible Search</h3>
    </div>
    <div class="row col-md-12" style="line-height: 30px;">
        @if(count($content['verses']))
            @foreach($content['verses'] as $verse)
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
                <div> {!! $verse->highlighted_verse_text !!}</div>
            @endforeach
        @else
            <p class="text-center">No any results found</p>
        @endif

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
        {!! $content['verses']->appends(Request::input())->links() !!}
    </div>
@stop
