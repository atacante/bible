<div id="notes" class="verse-notes my-notes-list j-my-notes-list">
    <div class="sub-header">
        <div class="sub-header-title">
            <i class="bs-note cu-note"></i>
            My <span>Notes</span> for this Study Verse
        </div>
        {!! Html::link('/notes/create','Create Note', ['class'=>'btn1-kit cu-study-btn j-create-note']) !!}
    </div>

    <ul class="study-list">
        @if($content['notes']->count())
            @foreach($content['notes'] as $note)
                <li>
                    <div class="note-text j-note-text" data-noteid="{!! $note->id !!}">
                        {!! str_limit(strip_tags($note->note_text,'<p></p>'), $limit = 300, $end = '...') !!}
                    </div>

                    <div class="c-study-bottom">
                        <div class="c-journey-tags">
                            @if(count($note->tags))
                                @foreach($note->tags as $tag)
                                    {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[]), '#'.$tag->tag_name, ['class' => 'link-tag'], true)}}
                                @endforeach
                            @endif
                        </div>
                        <ul class="icons-list pull-right">
                            @if(Agent::isModile())
                            <li>
                                <a title="Print note" href="#" data-noteid="{!! $note->id !!}" class="j-print-note">
                                    <i class="bs-print"></i>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a title="Edit note" class="j-create-note" href="{!! url('/notes/update/'.$note->id) !!}">
                                    <i class="bs-edit"></i>
                                </a>
                            </li>
                            <li>
                                <a title="Delete note" href="{!! url('/notes/delete',$note->id) !!}" data-toggle="modal" data-target="#confirm-delete" data-header="Delete Confirmation" data-confirm="Are you sure you want to delete this item?">
                                    <i class="bs-remove"></i>
                                </a>
                            </li>

                        </ul>
                        <div class="c-journey-date cu-date1">
                            {!! $note->humanLastUpdate($note::DFORMAT) !!}
                            {{--{!! $note->created_at->format($note::DFORMAT) !!}--}}
                            - {!! ViewHelper::getAccessLevelIcon($note->access_level) !!}
                        </div>

                    </div>

                </li>
            @endforeach
        @else
            <li class="text-center">
                    You haven’t got any notes yet.
            </li>
        @endif

    </ul>
</div>
