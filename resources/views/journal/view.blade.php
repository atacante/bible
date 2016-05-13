<div>
    @if($model->verse)
    <div>
        <div>
        {!! Html::link('/reader/verse?'.http_build_query(
            [
                'version' => $model->bible_version,
                'book' => $model->verse->book_id,
                'chapter' => $model->verse->chapter_num,
                'verse' => $model->verse->verse_num
            ]
            ),ViewHelper::getVerseNum($model->verse), ['class'=>'label label-success','style' => 'margin-bottom:10px;']) !!}
        </div>
    </div>
    @endif
    <div>
        {!! $model->journal_text !!}
    </div>
</div>