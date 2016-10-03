<div class="related-item-view">
    @if($model->verse)
        <div>
            <div style="margin-bottom:10px;">
                {!! Html::link('/reader/verse?'.http_build_query(
                    [
                        'version' => $model->bible_version,
                        'book' => $model->verse->book_id,
                        'chapter' => $model->verse->chapter_num,
                        'verse' => $model->verse->verse_num
                    ]
                    ),ViewHelper::getVerseNum($model->verse).($model->bible_version?' ('.ViewHelper::getVersionName($model->bible_version).')':''), ['class'=>'label label-success','style' => '']) !!}
            </div>
            @if($model->highlighted_text)
                <div class="verse-block">
                    {!! Form::label('verse_num', 'Verse:') !!}
                    <i>{!! $model->highlighted_text !!}</i>
                </div>
            @endif
        </div>
    @endif
    <div>
        {!! $model->note_text !!}
    </div>
    @if($model->journal || $model->prayer)
        <div>
            Relations:
            @if($model->journal)
                {{ Html::link(url('ajax/view-journal?'.http_build_query(
                    [
                        'id' => $model->journal->id,
                    ]),[],false), 'journal', ['class' => 'label label-primary j-journal-text','data-journalid' => $model->journal->id], true)}}
            @endif
            @if($model->prayer)
                {{ Html::link(url('ajax/view-prayer?'.http_build_query(
                    [
                        'id' => $model->prayer->id,
                    ]),[],false), 'prayer', ['class' => 'label label-primary j-prayer-text','data-prayersid' => $model->prayer->id], true)}}
            @endif
        </div>
    @endif
    @if(Auth::user() && $model->user_id == Auth::user()->id  && count($model->tags))
        <div>
            Tags:
            @foreach($model->tags as $tag)
                {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[]), $tag->tag_name, ['class' => 'label label-info'], true)}}
            @endforeach
        </div>
    @endif
</div>