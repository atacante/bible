@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
    <div class="row">

    </div>
    <div class="row my-notes-list j-my-notes-list">
        <div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filters</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('notes.filters')
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 {!! $content['notes']->count()?'':'text-center' !!}" style="line-height: 30px;">
            <h3 class="text-center">My Notes</h3>
            @if(!$content['notes']->count())
                <div><p>
                        @if(Request::has('search') || Request::has('book') || Request::has('chapter') || Request::has('verse'))
                            You haven’t got any notes according to search criteria. Click at the button below to create
                            new.
                        @else
                            You haven’t got any notes yet. Click at the button below to create new.
                        @endif
                    </p>
                </div>
            @endif
            {!! Html::link('/notes/create','Create Note', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            @if($content['notes']->count())
                <a title="Print selected notes" href="#" class="pull-right j-print-all-notes"><i
                            class="fa fa-print fa-2x" style=""></i></a>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th width="20"><input type="checkbox" id="checkAll"/></th>
                        {{--<th>Note Text</th>--}}
                        {{--<th width="150">Verse</th>--}}
                        {{--<th width="150">Created <i class="fa fa-fw fa-sort-asc"></i></th>--}}
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
                        <th width="90">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content['notes'] as $note)
                        <tr>
                            <td width="20"><input data-noteid="{!! $note->id !!}" type="checkbox" class="check"></td>
                            <td>
                                <div class="note-text j-note-text"
                                     data-noteid="{!! $note->id !!}">{!! str_limit(strip_tags($note->note_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
                            </td>
                            <td>
                                @if($note->verse)
                                {!! Html::link('/reader/verse?'.http_build_query(
                                    [
                                        'version' => $note->bible_version,
                                        'book' => $note->verse->book_id,
                                        'chapter' => $note->verse->chapter_num,
                                        'verse' => $note->verse->verse_num
                                    ]
                                    ),ViewHelper::getVerseNum($note->verse), ['class'=>'label label-success','style' => 'margin-bottom:10px;']) !!}
                                    <br />
                                    {{ Html::link(url('reader/read?'.http_build_query(
                                        [
                                            'version' => $note->bible_version,
                                            'book' => $note->verse->book_id,
                                            'chapter' => $note->verse->chapter_num,
                                        ])."#verse".$note->verse->id,[],false), 'Go to Reader', ['class' => 'label label-primary','style' => ''], true)}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($note->journal)
                                    {{ Html::link(url('ajax/view-journal?'.http_build_query(
                                        [
                                            'id' => $note->journal->id,
                                        ]),[],false), 'journal', ['class' => 'label label-primary j-journal-text','data-journalid' => $note->journal->id], true)}}
                                    <br />
                                @endif
                                @if($note->prayer)
                                    {{ Html::link(url('ajax/view-prayer?'.http_build_query(
                                        [
                                            'id' => $note->prayer->id,
                                        ]),[],false), 'prayer', ['class' => 'label label-primary j-prayer-text','data-prayersid' => $note->prayer->id], true)}}
                                @endif
                                @if(!$note->prayer && !$note->journal)
                                    -
                                @endif
                            </td>
                            <td>
                                @if(count($note->tags))
                                    @foreach($note->tags as $tag)
                                        {{ Html::link(url('notes/list?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                        <br />
                                    @endforeach
                                @endif
                            </td>
                            <td>{!! $note->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
                            <td class="text-center">
                                <a title="Print note" href="#" data-noteid="{!! $note->id !!}" class="j-print-note"><i
                                            class="fa fa-print fa-2x"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                <a title="Edit note" href="{!! url('/notes/update/'.$note->id) !!}"><i
                                            class="fa fa-edit"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                <a title="Delete note" href="{!! url('/notes/delete',$note->id) !!}" data-toggle="modal"
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
            {!! $content['notes']->appends(Request::input())->links() !!}
        </div>
    </div>
@stop
