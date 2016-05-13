@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">My Journal</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        <div class="pull-right">
                            @include('journal.filters')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row j-my-journal-list">
        <div class="col-md-12 {!! $content['journal']->count()?'':'text-center' !!}" style="line-height: 30px;">
            @if(!$content['journal']->count())
                <div><p>
                        @if(Request::has('search') || Request::has('book') || Request::has('chapter') || Request::has('verse'))
                            You haven’t got any journal entries according to search criteria. Click at the button below to create
                            new.
                        @else
                            You haven’t got any journal entries yet. Click at the button below to create new.
                        @endif
                    </p>
                </div>
            @endif
            {!! Html::link('/journal/create','Create Journal Entry', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
            @if($content['journal']->count())
                {{--<a title="Print selected entry" href="#" class="pull-right j-print-all-journal"><i
                            class="fa fa-print fa-2x" style=""></i></a>--}}
                <table class="table table-hover">
                    <thead>
                    <tr>
                        {{--<th width="20"><input type="checkbox" id="checkAll"/></th>--}}
                        {{--<th>Journal Entry Text</th>--}}
                        {{--<th width="150">Verse</th>--}}
                        {{--<th width="150">Created <i class="fa fa-fw fa-sort-asc"></i></th>--}}
                        @foreach(array_keys($content['columns']) as $column)
                            <th {!! ($content['columns'][$column] == 'verse_id' || $content['columns'][$column] == 'created_at')?'width="150"':'' !!}>
                                @if ($content['sortby'] == $content['columns'][$column] && $content['order'] == 'asc')
                                    {{link_to(
                                      $content['action']."?".http_build_query(array_merge(Request::input(),['sortby' => $content['columns'][$column],'order' => 'desc'])),
                                      $column
                                    )}}
                                @else
                                    {{link_to(
                                      $content['action']."?".http_build_query(array_merge(Request::input(),['sortby' => $content['columns'][$column],'order' => 'asc'])),
                                      $column
                                    )}}
                                @endif
                                <i class="fa fa-fw fa-sort-{!! ($content['sortby'] == $content['columns'][$column])?$content['order']:'' !!}"
                                   style="color: #367fa9;"></i>
                            </th>
                        @endforeach
                        <th width="90">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content['journal'] as $entry)
                        <tr>
                            {{--<td width="20"><input data-journalid="{!! $entry->id !!}" type="checkbox" class="check"></td>--}}
                            <td>
                                <div class="journal-text j-journal-text"
                                     data-journalid="{!! $entry->id !!}">{!! str_limit(strip_tags($entry->journal_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
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
                                @else
                                    -
                                @endif
                            </td>
                            <td>{!! $entry->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
                            <td class="text-center">
                                {{--<a title="Print journal entry" href="#" data-journalid="{!! $entry->id !!}" class="j-print-journal"><i
                                            class="fa fa-print fa-2x"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>--}}
                                <a title="Edit journal entry" href="{!! url('/journal/update/'.$entry->id) !!}"><i
                                            class="fa fa-edit"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                <a title="Delete journal entry" href="{!! url('/journal/delete',$entry->id) !!}" data-toggle="modal"
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
            {!! $content['journal']->appends(Request::input())->links() !!}
        </div>
    </div>
@stop
