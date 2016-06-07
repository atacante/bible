@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">

        </div>
    </div>
    <div class="row">

    </div>
    <div class="row my-prayers-list j-my-prayers-list">
        <div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filters</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('prayers.filters')
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 {!! $content['prayers']->count()?'':'text-center' !!}" style="line-height: 30px;">
            <h3 class="text-center">My Prayers</h3>
            @if(!$content['prayers']->count())
                <div><p>
                        @if(Request::has('search') || Request::has('book') || Request::has('chapter') || Request::has('verse'))
                            You haven’t got any prayers according to search criteria. Click at the button below to create
                            new.
                        @else
                            You haven’t got any prayers yet. Click at the button below to create new.
                        @endif
                    </p>
                </div>
            @endif
            {!! Html::link('/prayers/create','Create Prayer', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            @if($content['prayers']->count())
                <a title="Print selected entry" href="#" class="pull-right j-print-all-prayers"><i
                            class="fa fa-print fa-2x" style=""></i></a>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="20"><input type="checkbox" id="checkAll"/></th>
                        @foreach(array_keys($content['columns']) as $column)
                            <th {!! ($content['columns'][$column] == 'verse_id' || $content['columns'][$column] == 'created_at')?'width="150"':'' !!}>
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
                        <th width="100">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content['prayers'] as $entry)
                        <tr>
                            <td width="20"><input data-prayerid="{!! $entry->id !!}" type="checkbox" class="check"></td>
                            <td>
                                <div class="prayer-text j-prayer-text"
                                     data-prayersid="{!! $entry->id !!}">{!! str_limit(strip_tags($entry->prayer_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
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
                            <td>
                                @if($entry->note)
                                    {{ Html::link(url('ajax/view-note?'.http_build_query(
                                        [
                                            'id' => $entry->note->id,
                                        ]),[],false), 'note', ['class' => 'label label-primary j-note-text','data-noteid' => $entry->note->id], true)}}
                                    <br />
                                @endif
                                @if($entry->journal)
                                    {{ Html::link(url('ajax/view-journal?'.http_build_query(
                                        [
                                            'id' => $entry->journal->id,
                                        ]),[],false), 'journal', ['class' => 'label label-primary j-journal-text','data-journalid' => $entry->journal->id], true)}}
                                @endif
                                @if(!$entry->note && !$entry->journal)
                                    -
                                @endif
                            </td>
                            <td>
                                @if(count($entry->tags))
                                    @foreach($entry->tags as $tag)
                                        {{ Html::link(url('prayers/list?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                        <br />
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">{!! ViewHelper::getAccessLevelIcon($entry->access_level) !!}</td>
                            <td>{!! $entry->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
                            <td class="text-center">
                                <a title="Print prayer entry" href="#" data-prayerid="{!! $entry->id !!}" class="j-print-prayer"><i
                                            class="fa fa-print fa-2x"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                {{--<a title="Edit prayer" href="{!! url('/prayers/update/'.$entry->id) !!}"><i
                                            class="fa fa-edit"
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
                                <a title="Delete prayer" href="{!! url('/prayers/delete',$entry->id) !!}" data-toggle="modal"
                                   data-target="#confirm-delete" data-header="Delete Confirmation"
                                   data-confirm="Are you sure you want to delete this item?"><i class="fa fa-trash"
                                                                                                style="color: #367fa9; font-size: 1.4em;"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! $content['prayers']->appends(Request::input())->links() !!}
        </div>
    </div>
@stop
