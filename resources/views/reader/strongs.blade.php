@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">
                @if($prevNum = $content['pages']['prevNum'])
                    <a title="Prev Strong's Number" href="{!! url('reader/strongs/'.$prevNum.'/'.$content['dictionaryType'],[],false) !!}"><i class="glyphicon glyphicon-chevron-left"></i></a>
                @endif
                {!! $content['title'] !!}
                @if($nextNum = $content['pages']['nextNum'])
                    <a title="Next Strong's Number" href="{!! url('reader/strongs/'.$nextNum.'/'.$content['dictionaryType'],[],false) !!}"><i class="glyphicon glyphicon-chevron-right"></i></a>
                @endif
            </h3>
        </div>
    </div>
    <div class="row strongs-page" style="line-height: 30px;">
        <div class="col-md-6">
            @if(count($content['strongs_concordance']))
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">Strong's Concordance</h4>
                    <div>Original word: <strong>{!! $content['strongs_concordance']->original_word !!}</strong></div>
                    <div>Transliteration: <strong>{!! $content['strongs_concordance']->transliteration !!}</strong></div>
                    <div>Definition (short): <strong>{!! $content['strongs_concordance']->definition_short !!}</strong></div>
                    <div>Definition (full): <strong>{!! $content['strongs_concordance']->definition_full !!}</strong></div>
                    <div></div>
                </div>
            </div>
            @endif
            @if(count($content['strongs_nasec']))
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center">NAS Exhaustive Concordance</h4>
                        <div>Word Origin: <strong>{!! $content['strongs_nasec']->original_word !!}</strong></div>
                        <div>Definition: <strong>{!! $content['strongs_nasec']->definition !!}</strong></div>
                        <div>NASB Translation: <strong>{!! $content['strongs_nasec']->nasb_translation !!}</strong></div>
                        <div></div>
                    </div>
                </div>
            @endif
            @if(!empty($content['strongs_concordance']->exhaustive_concordance))
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-center">Strong's Exhaustive Concordance</h4>
                        <div>{!! $content['strongs_concordance']->exhaustive_concordance !!}</div>
                        <div></div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-center">References</h4>
                </div>
                <div class="col-md-12 text-center">
                    <strong>{!! link_to('/reader/strongs-references/'.$content['strongNum']."/".$content['dictionaryType'],"Strong's ".$content['strongNum'].": ".$content['totalReferences']." Occurrences") !!}</strong>
                </div>
            </div>
            @include('reader.strongsreflist')
            @if(count($content['references']) > 5)
                <div class="col-md-12 text-center">
                    <strong>{!! link_to('/reader/strongs-references/'.$content['strongNum']."/".$content['dictionaryType'],"View All Occurrences") !!}</strong>
                </div>
            @endif
        </div>
    </div>
@stop
