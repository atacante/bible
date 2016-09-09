@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')

    <h1 style="text-transform: uppercase; text-align: center"><i class="bs-myjourney" style="color: #00b9f7;"></i>&nbsp;My Journey</h1>
    <div class="row">
        <div class="col-lg-4">
            @if(Auth::user() && Auth::user()->avatar)
                <div class="user-image" style="margin-right: 30px; position:relative; float:left; background: url('{!! Auth::user()->avatar!=''?Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar:'' !!}') center no-repeat;"></div>
            @else
                <div class="user-image" style="margin-right: 30px; position:relative; float:left; "></div>
            @endif
            <div style="float: left">
                <div>&nbsp; {!! Auth::user()->name !!} &nbsp;</div>
                <div>&nbsp; Member Since: {!! Auth::user()->created_at->format('d M, Y') !!} &nbsp;</div>
                <div>&nbsp; <i class="bs-friends"></i>&nbsp;{!! Auth::user()->friends->count() !!} &nbsp;&nbsp;&nbsp; <i class="bs-s-groups"></i>&nbsp;{!! Auth::user()->joinedGroups->count() !!} &nbsp;</div>
            </div>
        </div>
        <div class="col-lg-2">
            <a href="{{ url('/notes/create') }}" class="j-create-note" style="color: #f0ad4e">
                <h3><i class="bs-note"></i>&nbsp;{!! $content['notesCount'] !!}</h3>
                <p>Note{!! $content['notesCount'] != 1?'s':'' !!}</p>
            </a>
        </div>
        <div class="col-lg-2">
            <a href="{{ url('/journal/create') }}" class="j-create-journal" style="color: #449d44">
                <h3><i class="bs-journal"></i>&nbsp;{!! $content['journalCount'] !!}</h3>
                <p>Journal Entr{!! $content['journalCount'] != 1?'ies':'y' !!}</p>
            </a>
        </div>
        <div class="col-lg-2">
            <a href="{{ url('/prayers/create') }}" class="j-create-prayer" style="color: #337ab7">
                <h3><i class="bs-pray"></i>&nbsp;{!! $content['prayersCount'] !!}</h3>
                <p>Prayer{!! $content['prayersCount'] != 1?'s':'' !!}</p>
            </a>
        </div>
        {{--<div class="user-image cu-ui1"></div>--}}
    </div>

    <div class="row my-entries-list j-my-entries-list">
        <div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filters</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('user.my-journey-filters')
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9" style="line-height: 30px;">
            <div class="create-btns">
                {{--<a href="{{ url('/notes/create') }}" class="btn btn-success j-create-note" style="margin-bottom:10px;"><i class="fa fa-btn fa-sticky-note"></i> Create Note</a>
                <a href="{{ url('/journal/create') }}" class="btn btn-success j-create-journal" style="margin-bottom:10px;"><i class="fa fa-btn fa-book"></i> Create Journal</a>
                <a href="{{ url('/prayers/create') }}" class="btn btn-success j-create-prayer" style="margin-bottom:10px;"><i class="fa fa-btn fa-hand-paper-o"></i> Create Prayer</a>--}}
            </div>
            @if($content['entries']->count())
                {{--<a title="Print selected entry" href="#" class="pull-right j-print-all-prayers"><i--}}
                            {{--class="fa fa-print fa-2x" style=""></i></a>--}}
                <table class="table table-hover">
                    <thead>
                    <tr>
                        {{--<th width="20"><input type="checkbox" id="checkAll"/></th>--}}
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
                            {{--<td width="20"><input data-prayerid="{!! $entry->id !!}" type="checkbox" class="check"></td>--}}
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
                            {{--<td width="120" class="text-center">
                                <div class="stars-block">
                                    <div class="star-line">
                                        <a title="Notes" class="star-link" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                            http_build_query(
                                                $entry->verse?[
                                                    'version' => $entry->bible_version,
                                                    'book' => $entry->verse->book_id,
                                                    'chapter' => $entry->verse->chapter_num,
                                                    'verse' => $entry->verse->verse_num
                                                ]:[
                                                    'rel' => $entry->rel_code,
                                                ])) !!}#notes">
                                            --}}{{--<div class="star star-n {!! $entry->notescount > 0?'active':'' !!}">N</div>--}}{{----}}{{-- ViewHelper::getEntriesCount('note',$entry) > 0 --}}{{--
                                            <span class="fa fa-star {!! $entry->notescount > 0?'active':'' !!}" aria-hidden="true" style=""></span>
                                            <span class="star-label" style="">N</span>
                                        </a>
                                    </div>
                                    <div class="star-line">
                                        <a title="Journal Entries" class="star-link" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                            http_build_query(
                                                $entry->verse?[
                                                    'version' => $entry->bible_version,
                                                    'book' => $entry->verse->book_id,
                                                    'chapter' => $entry->verse->chapter_num,
                                                    'verse' => $entry->verse->verse_num
                                                ]:[
                                                    'rel' => $entry->rel_code,
                                                ])) !!}#journal">
                                            --}}{{--<div class="star star-j {!! $entry->journalcount > 0?'active':'' !!}">J</div>--}}{{--
                                            <span class="fa fa-star {!! $entry->journalcount > 0?'active':'' !!}" aria-hidden="true" style=""></span>
                                            <span class="star-label" style="">J</span>
                                        </a>
                                        <a title="Prayers" class="star-link" href="{!! url('/reader/my-study-'.($entry->verse?'verse':'item').'?'.
                                            http_build_query(
                                                $entry->verse?[
                                                    'version' => $entry->bible_version,
                                                    'book' => $entry->verse->book_id,
                                                    'chapter' => $entry->verse->chapter_num,
                                                    'verse' => $entry->verse->verse_num
                                                ]:[
                                                    'rel' => $entry->rel_code,
                                                ])) !!}#prayers">
--}}{{--                                            <div class="star star-p {!! $entry->prayerscount > 0?'active':'' !!}">P</div>--}}{{--
                                            <span class="fa fa-star {!! $entry->prayerscount > 0?'active':'' !!}" aria-hidden="true" style=""></span>
                                            <span class="star-label" style="">P</span>
                                        </a>
                                    </div>
                                </div>
                            </td>--}}
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
                                {{--@if($tags = ViewHelper::getEntryTags($entry->type,$entry->id))
                                    @foreach($tags as $tag)
                                        {{ Html::link(url(ViewHelper::getEntryControllerName($entry->type).'/list?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                        <br />
                                    @endforeach
                                @endif--}}
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
                                {{--<a title="Print {!! $entry->type !!} entry" href="#" data-{!! $entry->type !!}id="{!! $entry->id !!}" class="j-print-{!! $entry->type !!}"><i
                                            class="fa fa-print fa-2x"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>--}}
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
                                {{--<a title="Edit {!! $entry->type !!}" class="j-create-{!! $entry->type !!}" href="{!! url('/'.ViewHelper::getEntryControllerName($entry->type).'/update/'.$entry->id) !!}"><i
                                            class="fa fa-edit"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>--}}
                                {{--<a title="Delete prayer" href="{!! url('/'.ViewHelper::getEntryControllerName($entry->type).'/delete',$entry->id) !!}" data-toggle="modal"
                                   data-target="#confirm-delete" data-header="Delete Confirmation"
                                   data-confirm="Are you sure you want to delete this item?"><i class="fa fa-trash"
                                                                                                style="color: #367fa9; font-size: 1.4em;"></i></a>--}}
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
