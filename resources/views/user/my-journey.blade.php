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
        <h3 class="section-title text-center">My Journey</h3>
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #f0ad4e">
                <div class="inner">
                    <h3>{!! $content['notesCount'] !!}</h3>
                    <p>Note{!! $content['notesCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-btn fa-sticky-note"></i>
                </div>
                <a href="{{ url('/notes/create') }}" class="small-box-footer j-create-note"> Create new note <i class="fa fa-arrow-circle-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #449d44">
                <div class="inner">
                    <h3>{!! $content['journalCount'] !!}</h3>
                    <p>Journal Entr{!! $content['journalCount'] != 1?'ies':'y' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-btn fa-book"></i>
                </div>
                <a href="{{ url('/journal/create') }}" class="small-box-footer j-create-journal"> Create new Journal Entry <i class="fa fa-arrow-circle-right"></i> </a>
            </div>
        </div>
        <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box" style="background-color: #337ab7">
                <div class="inner">
                    <h3>{!! $content['prayersCount'] !!}</h3>
                    <p>Prayer{!! $content['prayersCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-btn fa-hand-paper-o"></i>
                </div>
                <a href="{{ url('/prayers/create') }}" class="small-box-footer j-create-prayer"> Create new Prayer <i class="fa fa-arrow-circle-right"></i> </a>
            </div>
        </div>
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
                        <th width="140">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($content['entries'] as $entry)
{{--                        {!! var_dump(get_class($entry)) !!}--}}
                        <tr>
                            {{--<td width="20"><input data-prayerid="{!! $entry->id !!}" type="checkbox" class="check"></td>--}}
                            <td>{!! $entry->type !!}</td>
                            <td>
                                <div class="entry-text j-entry-text"
                                     data-prayersid="{!! $entry->id !!}">{!! str_limit(strip_tags($entry->text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
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
                            {{--<td>

                            </td>--}}
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
                            <td>{!! $entry->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
                            <td class="text-center">
                                <a title="Print {!! $entry->type !!} entry" href="#" data-{!! $entry->type !!}id="{!! $entry->id !!}" class="j-print-{!! $entry->type !!}"><i
                                            class="fa fa-print fa-2x"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
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
                                <a title="Edit {!! $entry->type !!}" class="j-create-{!! $entry->type !!}" href="{!! url('/'.ViewHelper::getEntryControllerName($entry->type).'/update/'.$entry->id) !!}"><i
                                            class="fa fa-edit"
                                            style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                                <a title="Delete prayer" href="{!! url('/'.ViewHelper::getEntryControllerName($entry->type).'/delete',$entry->id) !!}" data-toggle="modal"
                                   data-target="#confirm-delete" data-header="Delete Confirmation"
                                   data-confirm="Are you sure you want to delete this item?"><i class="fa fa-trash"
                                                                                                style="color: #367fa9; font-size: 1.4em;"></i></a>
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
