@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="j-my-study-verse">
        {{--<a href="{!! url('reader/read?'.http_build_query(
        [
            'version' => $content['verse']['version_code'],
            'book' => $content['verse']['verse']->book_id,
            'chapter' => $content['verse']['verse']->chapter_num
        ])."#verse".$content['verse']['verse']->id,[],false) !!}" class="btn btn-default btn-success btn-to-reader"
           style="">Go to Reader</a>--}}
        {!! Form::hidden('bible_version',$content['verse']['version_code']) !!}
        {!! Form::hidden('verse_id',$content['verse']['verse']->id) !!}

        <div class="row text-center my-study-verse">
            <div class="col-md-12">
                <h2 class="h2-new mb3">
                    <i class="bs-study cu-study2"></i>
                    My Study Verse
                </h2>

                <h4 class="text-center">
                    @if($versePrev = $content['pagination']['versePrev'])
                        <a class="cu-arrowleft3" title="Prev Verse" href="{!! url('reader/my-study-verse?'.http_build_query($versePrev),[],false) !!}">
                            <i class="bs-arrowleft"></i>
                        </a>
                    @endif
                    <span class="genesis-title1">
                        {!! $content['verse']['verse']->booksListEn->book_name.' <span style="font-size:25px;">'.$content['verse']['verse']->chapter_num.':'.$content['verse']['verse']->verse_num.'</span>' !!}
                    </span>
                    <span class="genesis-title11">
                        ({!! $content['verse']['version_name'] !!})
                    </span>
                    @if($verseNext = $content['pagination']['verseNext'])
                        <a class="cu-arrowright3" title="Next Verse" href="{!! url('reader/my-study-verse?'.http_build_query($verseNext),[],false) !!}">
                            <i class="bs-arrowright"></i>
                        </a>
                    @endif
                </h4>

                <div class="study-top-panel">
                    <span class="j-verse-text" data-version="{!! $content['verse']['version_code'] !!}"  data-verseid="{!! $content['verse']['verse']->id !!}">{!! ViewHelper::prepareVerseText($content['verse']['verse'],true) !!}</span>
                </div>

            </div>
        </div>
        @include('reader.entry-counters')

        @include('reader.my-notes-table')
        @include('reader.my-journal-table')
        @include('reader.my-prayers-table')

        <div class="row col-md-12 pagination" style="text-align: center;">
            <div class="btn-group" role="group" aria-label="...">
                @if($versePrev = $content['pagination']['versePrev'])
                    {{ Html::link(url('reader/my-study-verse?'.http_build_query($versePrev),[],false), 'Prev Verse', ['class' => 'btn btn-default btn-danger','style' => 'width:120px;'], true)}}
                @endif
                @if($verseNext = $content['pagination']['verseNext'])
                    {{ Html::link(url('reader/my-study-verse?'.http_build_query($verseNext),[],false), 'Next Verse', ['class' => 'btn btn-default btn-primary','style' => 'width:120px;'], true)}}
                @endif
            </div>
        </div>
    </div>
@stop
