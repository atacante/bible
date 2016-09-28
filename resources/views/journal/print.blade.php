<style>
    .print-note-item{
        border-bottom: 1px dotted;
        margin-bottom: 20px;
        padding-bottom: 20px;
    }
    .print-note-item:last-child{
        border:0;
    }
</style>
<div>
@foreach($model as $journal)
<div class="print-journal-item">
    @if($journal->verse)
    <div>
        <div>{!! ViewHelper::getVerseNum($journal->verse) !!}</div>
    </div>
    @endif
    <div>
        {!! $journal->journal_text !!}
    </div>
</div>
@endforeach
</div>