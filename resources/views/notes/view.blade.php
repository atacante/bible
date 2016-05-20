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
</div>