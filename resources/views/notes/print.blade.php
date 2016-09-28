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
@foreach($model as $note)
<div class="print-note-item">
    @if($note->verse)
    <div>
        <div>{!! ViewHelper::getVerseNum($note->verse) !!}</div>
    </div>
    @endif
    <div>
        {!! $note->note_text !!}
    </div>
</div>
@endforeach
</div>