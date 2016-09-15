@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <h2 class="h2-new mb3">
        <i class="bs-myjourney cu-gift2"></i>
        My Journey
    </h2>

    <div class="row">
        <div class="col-xs-12">
            <ul class="journey-top-panel">
                <li class="pull-left">
                    <div class="c-user-journey">
                        @if(Auth::user() && Auth::user()->avatar)
                            <div class="user-image jorney-ui" style="background: url('{!! Auth::user()->avatar!=''?Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar:'' !!}') center no-repeat;"></div>
                        @else
                            <div class="user-image jorney-ui"></div>
                        @endif
                        <div class="text-left user-descr">
                            <div class="user-name">{!! Auth::user()->name !!}</div>
                            <div>Member Since: {!! Auth::user()->created_at->format('d M, Y') !!}</div>
                            <div><i class="bs-friends"></i>&nbsp;{!! Auth::user()->friends->count() !!} &nbsp;&nbsp;&nbsp; <i class="bs-s-groups"></i>&nbsp;{!! Auth::user()->joinedGroups->count() !!} &nbsp;</div>
                        </div>
                    </div>
                </li>

                <li class="pull-right">
                    <a href="#" class="create-record">
                        <i class="bs-add"></i>
                        Create Record
                    </a>
                </li>
                <li class="w1">
                    <a href="{{ url('/prayers/create') }}" class="j-create-prayer a-create-journey color5">
                        <div class="acj-counter">
                            <i class="bs-pray"></i>&nbsp;{!! $content['prayersCount'] !!}
                        </div>
                        <div class="acj-title">Prayer{!! $content['prayersCount'] != 1?'s':'' !!}</div>
                    </a>
                </li>
                <li class="w1">
                    <a href="{{ url('/journal/create') }}" class="j-create-journal a-create-journey color6">
                        <div class="acj-counter">
                            <i class="bs-journal"></i> {!! $content['journalCount'] !!}
                        </div>
                        <div class="acj-title">Journal Entr{!! $content['journalCount'] != 1?'ies':'y' !!}</div>
                    </a>
                </li>
                <li class="w1">
                    <a href="{{ url('/notes/create') }}" class="j-create-note a-create-journey color7">
                        <div class="acj-counter">
                            <i class="bs-note"></i> {!! $content['notesCount'] !!}
                        </div>
                        <div class="acj-title">Note{!! $content['notesCount'] != 1?'s':'' !!}</div>
                    </a>
                </li>

            </ul>
        </div>
    </div>

    @include('user.my-journey-filters')

    <div class="row my-entries-list j-my-entries-list">
        <div class="col-md-12" style="line-height: 30px;">
            @if($content['entries']->count())


                <ul class="journey-list">
                    @foreach($content['entries'] as $entry)
                        <li>
                            {{-- Journey Top Panel --}}
                            <div class="c-journey-top">
                                <div class="journey-title">
                                    {!! ViewHelper::getEntryIcon($entry->type) !!}

                                    {!! $entry->type !!}
                                    @if($entry->verse)
                                        <span> for </span>
                                        {!! Html::link('/reader/verse?'.http_build_query(
                                            [
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]
                                            ),ViewHelper::getVerseNum($entry->verse), ['class'=>'label label-success','style' => 'margin-bottom:10px;']) !!}


                                        {{ Html::link(url('reader/read?'.http_build_query(
                                            [
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                            ])."#verse".$entry->verse->id,[],false), 'Go to Reader', ['class' => 'label label-primary','style' => ''], true)}}
                                    @endif
                                </div>
                                <div class="c-journey-date">
                                    {!! $entry->created_at->format($entry::DFORMAT) !!}
                                    - {!! ViewHelper::getAccessLevelIcon($entry->access_level) !!}
                                    {{--<span> Last update {!! $entry->updated_at->format($entry::DFORMAT) !!}  </span>--}}

                                </div>
                            </div> {{-- end Journey Top Panel --}}




                            <div class="c-journey-middle">
                                <div class="entry-text j-entry-text" data-prayersid="{!! $entry->id !!}">
                                    <a title="My Study {!! ($entry->verse?'Verse':'Item') !!}" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                    http_build_query(
                                        $entry->verse?[
                                            'version' => $entry->bible_version,
                                            'book' => $entry->verse->book_id,
                                            'chapter' => $entry->verse->chapter_num,
                                            'verse' => $entry->verse->verse_num
                                        ]:[
                                            'rel' => $entry->rel_code,
                                        ])) !!}">
                                        {!! str_limit(strip_tags($entry->text,'<p></p>'), $limit = 1300, $end = '...') !!}
                                    </a>
                                </div>
                                @if($entry->type == 'prayer' && $entry->answered)
                                    <div style="color: #00a65a;">
                                        <i class="fa fa-check-circle" aria-hidden="true" style="color: #00a65a;"></i> Answered
                                    </div>
                                @endif
                            </div>


                            {{-- Journey bottom panel --}}
                            <div class="c-journey-bottom">
                                <div class="c-journey-tags">
                                    @if(isset($content['tags'][$entry->type][$entry->id]))
                                        @foreach($content['tags'][$entry->type][$entry->id] as $tag)
                                            {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                        @endforeach
                                    @endif
                                </div>
                                <div class="c-journey-relations">
                                    Relations
                                    <a title="Notes" class="label label-warning" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                        http_build_query(
                                            $entry->verse?[
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]:[
                                                'rel' => $entry->rel_code,
                                            ])) !!}#notes">
                                        {!! $entry->notescount !!} note{!! $entry->notescount != 1?'s':'' !!}
                                    </a>
                                    <a title="Journal Entries" class="label label-success" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                        http_build_query(
                                            $entry->verse?[
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]:[
                                                'rel' => $entry->rel_code,
                                            ])) !!}#journal">
                                        {!! $entry->journalcount !!} journal entr{!! $entry->journalcount != 1?'ies':'y' !!}
                                    </a>
                                    <a title="Prayers" class="label label-primary" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                        http_build_query(
                                            $entry->verse?[
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]:[
                                                'rel' => $entry->rel_code,
                                            ])) !!}#prayers">
                                        {!! $entry->prayerscount !!} prayer{!! $entry->prayerscount != 1?'s':'' !!}
                                    </a>
                                </div>

                            </div>

                            {{--<div class="text-center">
                                <a title="My Study {!! ($entry->verse?'Verse':'Item') !!}" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                    http_build_query(
                                        $entry->verse?[
                                            'version' => $entry->bible_version,
                                            'book' => $entry->verse->book_id,
                                            'chapter' => $entry->verse->chapter_num,
                                            'verse' => $entry->verse->verse_num
                                        ]:[
                                            'rel' => $entry->rel_code,
                                        ])) !!}">
                                    <i class="fa fa-graduation-cap" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i>
                                </a>
                            </div>--}}
                        </li>
                    @endforeach

                </ul>
            @endif


            <div class="row">
                <div class="text-center">
                    {!! $content['entries']->appends(Request::input())->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop
