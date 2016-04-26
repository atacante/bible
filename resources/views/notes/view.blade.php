<div>
    @if($model->verse)
    <div>
        <div>{!! ViewHelper::getVerseNum($model->verse) !!}</div>
    </div>
    @endif
    <div>
        {!! $model->note_text !!}
    </div>
</div>