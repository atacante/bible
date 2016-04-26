@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">My Notes</h3>
        </div>
    </div>
    {{--<div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        <div class="pull-right">
                            @include('locations.filters')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>--}}
    <div class="row">
        <div class="col-md-12 {!! $content['notes']->count()?'':'text-center' !!}" style="line-height: 30px;">
            @if(!$content['notes']->count())
            <div><p>You haven’t got any notes yet. Click at the button below to create new.</p></div>
            @endif
            {!! Html::link('/notes/create','Create Note', ['class'=>'btn btn-success','style' => 'margin-bottom:10px;']) !!}
                @if($content['notes']->count())
                <table class="table table-hover">
                    <thead>
                    <tr>
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
                            @else
                            {{link_to(
                              $content['action']."?".http_build_query(array_merge(Request::input(),['sortby' => $content['columns'][$column],'order' => 'asc'])),
                              $column
                            )}}
                            @endif
                                <i class="fa fa-fw fa-sort-{!! ($content['sortby'] == $content['columns'][$column])?$content['order']:'' !!}" style="color: #367fa9;"></i>
                            </th>
                        @endforeach
                        <th width="50">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content['notes'] as $note)
                        <tr>
                            <td><div class="note-text j-note-text" data-noteid="{!! $note->id !!}">{!! str_limit(strip_tags($note->note_text,'<p></p>'), $limit = 300, $end = '...') !!}</div></td>
                            <td>{!! ViewHelper::getVerseNum($note->verse) !!}</td>
                            <td>{!! $note->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
                            <td class="text-center">
                                <a title="Edit note" href="{!! url('/notes/update/'.$note->id) !!}"><i class="fa fa-edit" style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                <a title="Delete note" href="{!! url('/notes/delete',$note->id) !!}" data-toggle="modal"
                                   data-target="#confirm-delete" data-header="Delete Confirmation"
                                   data-confirm="Are you sure you want to delete this item?"><i
                                            class="fa fa-trash"
                                            style="color: #367fa9; font-size: 1.4em;"></i></a></td>
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
