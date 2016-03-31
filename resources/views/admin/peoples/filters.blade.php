<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/admin/peoples/list']) !!}
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Keyword...','class' => 'pull-left','style' => 'margin-right:10px; width:200px;']) !!}
{{--{!! Form::select('book', array_merge([0 => 'All Books'],$filters['books']), Request::input('book'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}--}}
{{--{!! Form::select('chapter',array_merge([0 => 'All Chapters'],(Request::input('book') == 0?[]:$filters['chapters'])), Request::input('chapter'),['class' => 'pull-left',(Request::input('book') == 0?'disabled':''), 'style' => 'width: 170px; margin-right:10px;']) !!}--}}
{{--{!! Form::select('verse',array_merge([0 => 'All Verses'],(Request::input('chapter') == 0?[]:$filters['verses'])), Request::input('verse'),['class' => 'pull-left', (Request::input('chapter') == 0?'disabled':''), 'style' => 'width: 115px; margin-right:10px;']) !!}--}}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Form::close() !!}
</div>