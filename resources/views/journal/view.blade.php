<div>
    @if($model->verse)
    <div>
        <div>{!! ViewHelper::getVerseNum($model->verse) !!}</div>
    </div>
    @endif
    <div>
        {!! $model->journal_text !!}
    </div>
</div>