<div id="journal" class="verse-journal my-journal-list j-my-journal-list">
    <h4>My Journal Entries for this Study Verse</h4>
    {!! Html::link('/journal/create','Create Journal Entry', ['class'=>'btn btn-success j-create-journal','style' => 'margin-bottom:10px;']) !!}
    {{--<a title="Print selected journal entries" href="#" class="pull-right j-print-all-journal"><i--}}
    {{--class="fa fa-print fa-2x" style=""></i></a>--}}
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            {{--<th width="20"><input type="checkbox" id="checkAll"/></th>--}}
            <th>Journal Text</th>
            <th width="150">Tags</th>
            <th width="100" class="text-center">Accessibility</th>
            <th width="100" class="text-center">Created</th>
            <th width="90" class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if($content['journal']->count())
            @foreach($content['journal'] as $journal)
                <tr>
                    {{--<td width="20"><input data-journalid="{!! $journal->id !!}" type="checkbox" class="check"></td>--}}
                    <td>
                        <div class="journal-text j-journal-text"
                             data-journalid="{!! $journal->id !!}">{!! str_limit(strip_tags($journal->journal_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
                    </td>
                    <td>
                        @if(count($journal->tags))
                            @foreach($journal->tags as $tag)
                                {{ Html::link(url('journal/list?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                <br/>
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">{!! ViewHelper::getAccessLevelIcon($journal->access_level) !!}</td>
                    <td>{!! $journal->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
                    <td class="text-center">
                        <a title="Print Journal Entry" href="#" data-journalid="{!! $journal->id !!}"
                           class="j-print-journal"><i
                                    class="fa fa-print fa-2x"
                                    style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                        <a title="Edit Journal Entry" class="j-create-journal"
                           href="{!! url('/journal/update/'.$journal->id) !!}"><i
                                    class="fa fa-edit"
                                    style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                        <a title="Delete Journal Entry" href="{!! url('/journal/delete',$journal->id) !!}"
                           data-toggle="modal"
                           data-target="#confirm-delete" data-header="Delete Confirmation"
                           data-confirm="Are you sure you want to delete this item?"><i class="fa fa-trash"
                                                                                        style="color: #367fa9; font-size: 1.4em;"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">
                    You haven’t got any journal entries yet.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
