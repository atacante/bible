<div id="notes" class="verse-notes my-notes-list j-my-notes-list">
    <h4>My Notes for this Study Verse</h4>
    {!! Html::link('/notes/create','Create Note', ['class'=>'btn btn-success j-create-note','style' => 'margin-bottom:10px;']) !!}
    {{--<a title="Print selected notes" href="#" class="pull-right j-print-all-notes"><i--}}
    {{--class="fa fa-print fa-2x" style=""></i></a>--}}
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            {{--<th width="20"><input type="checkbox" id="checkAll"/></th>--}}
            <th>Note Text</th>
            <th width="150">Tags</th>
            <th width="100" class="text-center">Accessibility</th>
            <th width="100" class="text-center">Created</th>
            <th class="text-center" width="90">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if($content['notes']->count())
            @foreach($content['notes'] as $note)
                <tr>
                    {{--<td width="20"><input data-noteid="{!! $note->id !!}" type="checkbox" class="check"></td>--}}
                    <td>
                        <div class="note-text j-note-text"
                             data-noteid="{!! $note->id !!}">{!! str_limit(strip_tags($note->note_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
                    </td>
                    <td>
                        @if(count($note->tags))
                            @foreach($note->tags as $tag)
                                {{ Html::link(url('notes/list?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                <br/>
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">{!! ViewHelper::getAccessLevelIcon($note->access_level) !!}</td>
                    <td class="text-center">{!! $note->created_at->format($note::DFORMAT) !!}</td>{{--H:i--}}
                    <td class="text-center">
                        <a title="Print note" href="#" data-noteid="{!! $note->id !!}" class="j-print-note"><i
                                    class="fa fa-print fa-2x"
                                    style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                        <a title="Edit note" class="j-create-note"
                           href="{!! url('/notes/update/'.$note->id) !!}"><i
                                    class="fa fa-edit"
                                    style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                        <a title="Delete note" href="{!! url('/notes/delete',$note->id) !!}" data-toggle="modal"
                           data-target="#confirm-delete" data-header="Delete Confirmation"
                           data-confirm="Are you sure you want to delete this item?"><i class="fa fa-trash"
                                                                                        style="color: #367fa9; font-size: 1.4em;"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">
                    You haven’t got any notes yet.
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
