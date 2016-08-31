<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => Request::url()]) !!}
{{--{!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version','all'),['class' => 'pull-left', 'style' => 'width: 245px; margin-right:10px;']) !!}--}}
{!! Form::text('search',Request::input('search'),['placeholder' => 'Keyword...','class' => 'pull-left','style' => 'margin-right:10px; width:200px;']) !!}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Form::close() !!}
</div>