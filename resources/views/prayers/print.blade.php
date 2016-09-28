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
@foreach($model as $prayer)
<div class="print-prayer-item">
    @if($prayer->verse)
    <div>
        <div>{!! ViewHelper::getVerseNum($prayer->verse) !!}</div>
    </div>
    @endif
    <div>
        {!! $prayer->prayer_text !!}
    </div>
</div>
@endforeach
</div>