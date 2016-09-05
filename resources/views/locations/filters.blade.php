<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => Request::url()]) !!}
{{--{!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version','all'),['class' => 'pull-left', 'style' => 'width: 245px; margin-right:10px;']) !!}--}}
{!! Form::text('search',Request::input('search'),['placeholder' => 'Search for Locations...','class' => 'pull-left mr6']) !!}
{!! Form::token() !!}
{!! Form::button('<i class="bs-search cu-search1"></i>',['type' => 'submit','class' => 'btn1 cu3-btn1 pull-left']) !!}
{!! Form::close() !!}
</div>