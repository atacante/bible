<div id="journal" class="verse-journal my-journal-list j-my-journal-list mt3">
    <h4 class="h4-study mb3">
        <i class="bs-journal cu-jornal"></i>
        My <span>Journal Entries</span> for this Study Verse
        {!! Html::link('/journal/create','Create Journal Entry', ['class'=>'btn1-kit cu-study-btn j-create-journal']) !!}
    </h4>
    <ul class="study-list">

        @if($content['journal']->count())
            @foreach($content['journal'] as $journal)
                <li>
                    <div class="note-text j-note-text" data-journalid="{!! $journal->id !!}">
                        {!! str_limit(strip_tags($journal->journal_text,'<p></p>'), $limit = 300, $end = '...') !!}
                    </div>

                    <div class="c-study-bottom">
                        <div class="c-journey-tags">
                            @if(count($journal->tags))
                                @foreach($journal->tags as $tag)
                                    {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[],false), '#'.$tag->tag_name, ['class' => 'link-tag'], true)}}
                                @endforeach
                            @endif
                        </div>
                        <ul class="icons-list pull-right">
                            <li>
                                <a title="Print journal entry" href="#" data-journalid="{!! $journal->id !!}" class="j-print-journal">
                                    <i class="bs-print"></i>
                                </a>
                            </li>
                            <li>
                                <a title="Edit journal entry" class="j-create-journal" href="{!! url('/journal/update/'.$journal->id) !!}">
                                    <i class="bs-edit"></i>
                                </a>
                            </li>
                            <li>
                                <a title="Delete journal entry" href="{!! url('/journal/delete',$journal->id) !!}" data-toggle="modal" data-target="#confirm-delete" data-header="Delete Confirmation" data-confirm="Are you sure you want to delete this item?">
                                    <i class="bs-remove"></i>
                                </a>
                            </li>

                        </ul>
                        <div class="c-journey-date cu-date1">
                            {!! $journal->humanLastUpdate($journal::DFORMAT) !!}
                            - {!! ViewHelper::getAccessLevelIcon($journal->access_level) !!}
                        </div>
                    </div>
                </li>
            @endforeach
        @else
            <li class="text-center">
                You haven’t got any journal entries yet.
            </li>
        @endif

    </ul>

{{--    <table class="table table-bordered table-hover">

        @if($content['journal']->count())
            @foreach($content['journal'] as $journal)
                <tr>
                    --}}{{--<td width="20"><input data-journalid="{!! $journal->id !!}" type="checkbox" class="check"></td>--}}{{--
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
                    <td>{!! $journal->created_at->format($journal::DFORMAT) !!}</td>--}}{{--H:i--}}{{--
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
    </table>--}}
</div>
