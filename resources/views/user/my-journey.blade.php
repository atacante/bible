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
            <div class="create-btns">
            </div>
            @if($content['entries']->count())
                <table class="table table-hover">
                    <thead>
                    <tr>
                        @foreach(array_keys($content['columns']) as $column)
                            <th {!! ($content['columns'][$column] == 'verse_id' || $content['columns'][$column] == 'created_at')?'width="100"':'' !!}>
                                @if ($content['sortby'] == $content['columns'][$column] && $content['order'] == 'asc')
                                    {{link_to(
                                      $content['action']."?".http_build_query(array_merge(Request::input(),['sortby' => $content['columns'][$column],'order' => 'desc'])),
                                      $column
                                    )}}
                                @elseif(!$content['columns'][$column])
                                    {!! $column !!}
                                @else
                                    {{link_to(
                                      $content['action']."?".http_build_query(array_merge(Request::input(),['sortby' => $content['columns'][$column],'order' => 'asc'])),
                                      $column
                                    )}}
                                @endif
                                @if($content['columns'][$column])
                                <i class="fa fa-fw fa-sort-{!! ($content['sortby'] == $content['columns'][$column])?$content['order']:'' !!}"
                                   style="color: #367fa9;"></i>
                                @endif
                            </th>
                        @endforeach
                        <th width="40">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content['entries'] as $entry)
                        <tr>
                            <td>{!! ViewHelper::getEntryIcon($entry->type) !!}</td>
                            <td>
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
                                        {!! str_limit(strip_tags($entry->text,'<p></p>'), $limit = 300, $end = '...') !!}
                                    </a>
                                </div>
                                @if($entry->type == 'prayer' && $entry->answered)
                                    <div style="color: #00a65a;">
                                        <i class="fa fa-check-circle" aria-hidden="true" style="color: #00a65a;"></i> Answered
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($entry->verse)
                                    {!! Html::link('/reader/verse?'.http_build_query(
                                        [
                                            'version' => $entry->bible_version,
                                            'book' => $entry->verse->book_id,
                                            'chapter' => $entry->verse->chapter_num,
                                            'verse' => $entry->verse->verse_num
                                        ]
                                        ),ViewHelper::getVerseNum($entry->verse), ['class'=>'label label-success','style' => 'margin-bottom:10px;']) !!}
                                    <br />
                                    {{ Html::link(url('reader/read?'.http_build_query(
                                        [
                                            'version' => $entry->bible_version,
                                            'book' => $entry->verse->book_id,
                                            'chapter' => $entry->verse->chapter_num,
                                        ])."#verse".$entry->verse->id,[],false), 'Go to Reader', ['class' => 'label label-primary','style' => ''], true)}}
                                @else
                                    -
                                @endif

                            </td>
                            <td width="85" class="">
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
                            </td>
                            <td>
                                @if(isset($content['tags'][$entry->type][$entry->id]))
                                    @foreach($content['tags'][$entry->type][$entry->id] as $tag)
                                        {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                        <br />
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{!! ViewHelper::getAccessLevelIcon($entry->access_level) !!}</td>
                            <td>
                                {!! $entry->created_at->format($entry::DFORMAT) !!}<br />
                                <span style="color: #ccc;">
                                    Last update<br />
                                    {!! $entry->updated_at->format($entry::DFORMAT) !!}
                                </span>
                            </td>{{--H:i--}}
                            <td class="text-center">
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
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <div class="row">
                <div class="text-center">
                    {!! $content['entries']->appends(Request::input())->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop
