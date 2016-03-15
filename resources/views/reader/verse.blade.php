@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row col-md-12">
        @include('reader.filters')
    </div>
    <div class="row col-md-12">
        <h3 class="text-center">KJV Lexicon</h3>
    </div>
    <div class="row col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Verse</th>
                <th>Definition</th>
                <th>Strong's</th>
                <th>Transliteration</th>
            </tr>
            </thead>
            <tbody>
            @foreach($content['lexicon'] as $lexiconinfo)
                <tr>
                    <td>{!! $lexiconinfo->verse_part !!}</td>
                    <td>{!! $lexiconinfo->strong_1_word_def !!}</td>
                    <td>{!! link_to('#',$lexiconinfo->strong_num) !!}</td>
                    <td>{!! $lexiconinfo->transliteration !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="row col-md-12">
        <h3 class="text-center">Parallel Verses {!! link_to('reader/verse?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}</h3>
    </div>
    <div class="row col-md-12" style="line-height: 30px;">
        <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => Request::input('version')])),[],false), $content['main_verse']['version_name'], ['class' => '','style' => ''], true)}}</h4>
            <span style="">
                {!! !empty($content['main_verse']['verse']->verse_text_with_lexicon)?$content['main_verse']['verse']->verse_text_with_lexicon:$content['main_verse']['verse']->verse_text !!}
            </span>
    </div>
    <div class="row col-md-12"><hr></div>
    <div class="row col-md-12" style="line-height: 30px;">
        @foreach($content['verse'] as $code => $version)
            <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[],false), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>
            <span style="">
                {!! !empty($version['verse']->verse_text_with_lexicon)?$version['verse']->verse_text_with_lexicon:$version['verse']->verse_text !!}
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
