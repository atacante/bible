<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/journal/list']) !!}
    <div class="form-group">
        {!! Form::label('search', 'Search') !!}
        {!! Form::text('search',Request::input('search'),['placeholder' => 'Keyword...','class' => 'form-control','style' => '']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('date_from', 'Date') !!}
        <div class="input-group input-daterange">
            {!! Form::text('date_from',Request::input('date_from'),['placeholder' => 'mm/dd/yyyy','class' => 'form-control datepicker','style' => '']) !!}
            <span class="input-group-addon">to</span>
            {!! Form::text('date_to',Request::input('date_to'),['placeholder' => 'mm/dd/yyyy','class' => 'form-control datepicker','style' => '']) !!}
        </div>
    </div>
    {{--<div class="form-group">--}}
        {{--{!! Form::label('date_from', 'Date from') !!}--}}
        {{--{!! Form::text('date_from',Request::input('date_from'),['placeholder' => 'mm/dd/yyyy','class' => 'form-control datepicker','style' => '']) !!}--}}
    {{--</div>--}}
    {{--<div class="form-group">--}}
        {{--{!! Form::label('date_to', 'Date to') !!}--}}
        {{--{!! Form::text('date_to',Request::input('date_to'),['placeholder' => 'mm/dd/yyyy','class' => 'form-control datepicker','style' => '']) !!}--}}
    {{--</div>--}}
    <div class="form-group">
        {!! Form::label('version', 'Bible version') !!}
        {!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version','all'),['class' => 'form-control', 'style' => '']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('book', 'Book') !!}
        {!! Form::select('book', array_merge([0 => 'All Books'],$filters['books']), Request::input('book'),['class' => 'form-control', 'style' => '']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('chapter', 'Chapter') !!}
        {!! Form::select('chapter',array_merge([0 => 'All Chapters'],(Request::input('book') == 0?[]:$filters['chapters'])), Request::input('chapter'),['class' => 'form-control',(Request::input('book') == 0?'disabled':''), 'style' => '']) !!}
    </div>
    <div class="form-group">
        {!! Form::label('verse', 'Verse') !!}
        {!! Form::select('verse',array_merge([0 => 'All Verses'],(Request::input('chapter') == 0?[]:$filters['verses'])), Request::input('verse'),['class' => 'form-control', (Request::input('chapter') == 0?'disabled':''), 'style' => '']) !!}
    </div>
    {!! Form::token() !!}
    <div class="form-group">
        {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
        {!! Html::link('/journal/list','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    </div>
{!! Form::close() !!}
</div>