@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="h3-kit mb4 strongs-header">
                @if($prevNum = $content['pages']['prevNum'])
                    <a class="genesis-arrow" title="Prev Strong's Number" href="{!! url('reader/strongs/'.$prevNum.'/'.$content['dictionaryType'],[],false) !!}"><i class="bs-arrowleft cu-arrowleft"></i></a>
                @endif
                {!! $content['title'] !!}
                @if($nextNum = $content['pages']['nextNum'])
                    <a class="genesis-arrow" title="Next Strong's Number" href="{!! url('reader/strongs/'.$nextNum.'/'.$content['dictionaryType'],[],false) !!}"><i class="bs-arrowright cu-arrowright"></i></a>
                @endif
            </h3>
        </div>
    </div>
    <div class="row strongs-page" style="line-height: 30px;">
        <div class="col-md-6">
            <div class="c-white-content">
                <div class="inner-pad1">
                    @if(count($content['strongs_concordance']) && ($content['strongs_concordance']->original_word || $content['strongs_concordance']->transliteration || $content['strongs_concordance']->definition_short || $content['strongs_concordance']->definition_full))
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center h3-sub-kit strongs-subheader mt0">Strong's Concordance</h3>
                            @if($content['strongs_concordance']->original_word)
                            <div>Original word: <strong>{!! $content['strongs_concordance']->original_word !!}</strong></div>
                            @endif
                            @if($content['strongs_concordance']->transliteration)
                            <div>Transliteration: <strong>{!! $content['strongs_concordance']->transliteration !!}</strong></div>
                            @endif
                            @if($content['strongs_concordance']->definition_short)
                            <div>Definition (short): <strong>{!! $content['strongs_concordance']->definition_short !!}</strong></div>
                            @endif
                            @if($content['strongs_concordance']->definition_full)
                            <div>Definition (full): <strong>{!! $content['strongs_concordance']->definition_full !!}</strong></div>
                            @endif
                            <div></div>
                        </div>
                    </div>
                    @endif
                    @if(count($content['strongs_nasec']) && ($content['strongs_nasec']->original_word || $content['strongs_nasec']->definition || $content['strongs_nasec']->nasb_translation))
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center h3-sub-kit strongs-subheader">NAS Exhaustive Concordance</h3>
                                @if($content['strongs_nasec']->original_word)
                                <div>Word Origin: <strong>{!! $content['strongs_nasec']->original_word !!}</strong></div>
                                @endif
                                @if($content['strongs_nasec']->definition)
                                <div>Definition: <strong>{!! $content['strongs_nasec']->definition !!}</strong></div>
                                @endif
                                @if($content['strongs_nasec']->nasb_translation)
                                <div>NASB Translation: <strong>{!! $content['strongs_nasec']->nasb_translation !!}</strong></div>
                                @endif
                                <div style="font-size: 12px; margin-top: 10px; text-align: center;">
                                    NAS Exhaustive Concordance of the Bible with Hebrew-Aramaic and Greek Dictionaries.<br />
                                    Copyright &copy; 1981, 1998 by The Lockman Foundation.<br />
                                    All rights reserved <a target="_blank" href="http://lockman.org">Lockman.org</a>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(!empty($content['strongs_concordance']->exhaustive_concordance))
                        <div class="row">
                            <div class="col-md-12">
                                <h3 class="text-center h3-sub-kit strongs-subheader">Strong's Exhaustive Concordance</h3>
                                <div>{!! $content['strongs_concordance']->exhaustive_concordance !!}</div>
                                <div></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="c-white-content">
                <div class="inner-pad1">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center h3-sub-kit strongs-subheader mt0">References</h3>
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
        </div>
    </div>
@stop
