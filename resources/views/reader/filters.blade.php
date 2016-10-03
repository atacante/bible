<?php
?>
<div class="j-verses-filters">
{!! Form::open(['method' => 'get','url' => '/reader/'.(isset($filterAction)?$filterAction:'read')]) !!}
{!! Form::select('version', array_merge((Request::segment(2) == 'verse'?[]:['all' => 'All Versions']),$filters['versions']), Request::input('version','all'),['class' => 'pull-left', 'style' => 'width: 245px; margin-right:10px;']) !!}
{!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}
{!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}
@if(Request::segment(2) == 'verse')
    {!! Form::select('verse',$filters['verses'], Request::input('verse'),['class' => 'pull-left', 'style' => 'width: 115px; margin-right:10px;']) !!}
@endif
{!! Form::token() !!}
{!! Form::submit('Go',['class' => 'btn btn-primary pull-left']) !!}

@if(isset($compare['versions']))
    <div class="pull-left" style="margin-left:35px;">
        {!! Form::select('compare[]', array_merge([],$compare['versions']), Request::input('compare'),['placeholder' => 'Compare with ...','multiple' => true,'class' => 'pull-left j-compare-versions', 'style' => 'width: 245px; margin-left:35px;']) !!}
    </div>
    {!! Form::submit('Compare',['class' => 'btn btn-primary pull-left','style' => 'margin-left:10px;']) !!}
    {!! Html::link(url('reader/read?'.http_build_query($compare['resetParams']),[]), 'Reset', ['class' => 'btn btn-default btn-danger','style' => 'margin-left:10px;'], true) !!}
@endif

{!! Form::close() !!}
</div>