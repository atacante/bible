@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    @include('reader.filters')
    <div class="row col-md-12">
        <h3 class="text-center">Parallel Verses ({!! link_to('reader/verse?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff') !!})</h3>
    </div>
    <div class="row col-md-12" style="line-height: 30px;">
        <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => Request::input('version')])),[],false), $content['main_verse']['version_name'], ['class' => '','style' => ''], true)}}</h4>
            <span style="">
                {!! $content['main_verse']['verse']->verse_text !!}
            </span>
    </div>
    <div class="row col-md-12"><hr></div>
    <div class="row col-md-12" style="line-height: 30px;">
        @foreach($content['verse'] as $code => $version)
            <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[],false), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>
            <span style="">
                {!! $version['verse']->verse_text !!}
            </span>
        @endforeach
    </div>
    <div class="row col-md-12 pagination" style="text-align: center;">
        <div class="btn-group" role="group" aria-label="...">
            @if($versePrev = $content['pagination']['versePrev'])
                {{ Html::link(url('reader/verse?'.http_build_query($versePrev),[],false), 'Prev Verse', ['class' => 'btn btn-default btn-danger','style' => 'width:120px;'], true)}}
            @endif
            @if($verseNext = $content['pagination']['verseNext'])
                {{ Html::link(url('reader/verse?'.http_build_query($verseNext),[],false), 'Next Verse', ['class' => 'btn btn-default btn-primary','style' => 'width:120px;'], true)}}
            @endif
        </div>
    </div>
@stop
