@extends('layouts.app')
{{-- Web site Title --}}

@section('title')
    @parent
@stop

@section('content')

    <section class="bl-my-journey">
        <h2 class="bl-heading">
            <i class="bs-myjourney"></i>My Journey
        </h2>
        <sectin class="profile">
            <div class="user">
                @if(Auth::user() && Auth::user()->avatar)
                    <div class="userpic" style="background-image: url('{!!
                        Auth::user()->avatar != ''
                            ? Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar
                            : ''
                        !!}')"></div>
                @else
                    <div class="userpic no-photo"></div>
                @endif
                <div class="user-info">
                    <div class="name">{!! Auth::user()->name !!}</div>
                    <div class="since">Member Since: {!! Auth::user()->created_at->format('d M, Y') !!}</div>
                    <div class="relations">
                        <span class="friends">
                            <i class="bs-friends"></i>
                            {!! count(array_intersect(Auth::user()->requests->modelKeys(), Auth::user()->friends->modelKeys())) !!}
                        </span>
                        <span class="groups">
                            <i class="bs-s-groups"></i>
                            {!! Auth::user()->joinedGroups->count()+Auth::user()->myGroups->count() !!}
                        </span>
                    </div>
                </div>
            </div>

            <div class="counters">
                <div class="counter notes">
                    <div class="count">
                        <i class="bs-note"></i> {!! $content['notesCount'] !!}
                    </div>
                    <div class="title">
                        Note{!! $content['notesCount'] != 1 ? 's' : '' !!}
                    </div>
                </div>

                <div class="counter entries">
                    <div class="count">
                        <i class="bs-journal"></i> {!! $content['journalCount'] !!}
                    </div>
                    <div class="title">
                        Journal Entr{!! $content['journalCount'] != 1 ? 'ies' : 'y' !!}
                    </div>
                </div>

                <div class="counter prayers">
                    <div class="count">
                        <i class="bs-pray"></i>&nbsp;{!! $content['prayersCount'] !!}
                    </div>
                    <div class="title">
                        Prayer{!! $content['prayersCount'] != 1?'s':'' !!}
                    </div>
                </div>
            </div>

            <div class="create-record">
                <a href="#" class="button">
                    <i class="bs-add"></i>Create Record
                </a>
                <div class="dropdown">
                    <div class="title">
                        Create...
                    </div>
                    <ul>
                        <li>
                            <a class="j-create-note" href="{{ url('/notes/create') }}">
                                <i class="bs-note"></i>Note
                            </a>
                            </li>
                        <li>
                            <a class="j-create-journal" href="{{ url('/journal/create') }}">
                                <i class="bs-journal"></i>Journal Entry
                            </a>
                        </li>
                        <li>
                            <a class="j-create-prayer" href="{{ url('/prayers/create') }}">
                                <i class="bs-pray"></i>Prayer
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </sectin>


    </section>

    @include('user.my-journey-filters')


    <div class="row my-entries-list j-my-entries-list">
        <div class="col-md-12" style="line-height: 30px;">
            @if($content['entries']->count())
                <ul class="bl-journey-list journey-list">
                    @foreach($content['entries'] as $entry)
                        <li>
                            {{-- Journey Top Panel --}}
                            <div class="c-journey-top">
                                <div class="journey-title">
                                    {!! ViewHelper::getEntryIcon($entry->type) !!}
                                    <div class="type">
                                        <span>
                                            <span class="tu-text">{!! $entry->type !!}</span>
                                            @if($entry->verse) for @endif
                                        </span>
                                        @if($entry->verse)
                                            <span class="link">
                                                {!! Html::link('/reader/verse?'.http_build_query(
                                                    [
                                                        'version' => $entry->bible_version,
                                                        'book' => $entry->verse->book_id,
                                                        'chapter' => $entry->verse->chapter_num,
                                                        'verse' => $entry->verse->verse_num
                                                    ]
                                                    ),ViewHelper::getVerseNum($entry->verse), ['class'=>'']) !!}


                                                <a href="{{url('reader/read?'.http_build_query(
                                                    [
                                                        'version' => $entry->bible_version,
                                                        'book' => $entry->verse->book_id,
                                                        'chapter' => $entry->verse->chapter_num,
                                                    ])."#verse".$entry->verse->id,[],false)}}" style="text-decoration: none !important;">
                                                    <i class="bs-arrowrt" title="Go to Reader"></i>
                                                </a>
                                            </span>
                                        @endif
                                        @if($entry->type == 'prayer' && $entry->answered)
                                            <div class="i-ansvered">
                                                <i class="fa fa-check-circle" aria-hidden="true" style="color: #00a65a;"></i>&nbsp;Answered
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="c-journey-date cu-date1">
                                    {!! $entry->humanLastUpdate($entry::DFORMAT) !!}
                                    - {!! ViewHelper::getAccessLevelIcon($entry->access_level) !!}
                                </div>
                            </div> {{-- end Journey Top Panel --}}




                            <div class="c-journey-middle">
                                <div class="entry-text j-entry-text" data-prayersid="{!! $entry->id !!}">
                                    <a class="journey-text-link" title="My Study {!! ($entry->verse?'Verse':'Item') !!}" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
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
                            </div>


                            {{-- Journey bottom panel --}}
                            <div class="c-journey-bottom">
                                <div class="c-journey-tags">
                                    @if(isset($content['tags'][$entry->type][$entry->id]))
                                        @foreach($content['tags'][$entry->type][$entry->id] as $tag)
                                            {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[]), '#'.$tag->tag_name, ['class' => 'link-tag'], true)}}
                                        @endforeach
                                    @endif
                                </div>

                                <div class="c-journey-relations">
                                    <div class="rel-label">Relations:</div>

                                    <a title="Notes" class="rel-link" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                        http_build_query(
                                            $entry->verse?[
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]:[
                                                'rel' => $entry->rel_code,
                                            ])) !!}#notes">
                                        <i class="bs-note"></i>
                                        {!! $entry->notescount !!}
                                    </a>

                                    <a title="Journal Entries" class="rel-link" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                        http_build_query(
                                            $entry->verse?[
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]:[
                                                'rel' => $entry->rel_code,
                                            ])) !!}#journal">
                                        <i class="bs-journal"></i>
                                        {!! $entry->journalcount !!}
                                    </a>

                                    <a title="Prayers" class="rel-link" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                        http_build_query(
                                            $entry->verse?[
                                                'version' => $entry->bible_version,
                                                'book' => $entry->verse->book_id,
                                                'chapter' => $entry->verse->chapter_num,
                                                'verse' => $entry->verse->verse_num
                                            ]:[
                                                'rel' => $entry->rel_code,
                                            ])) !!}#prayers">
                                        <i class="bs-pray"></i>
                                        {!! $entry->prayerscount !!}
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
