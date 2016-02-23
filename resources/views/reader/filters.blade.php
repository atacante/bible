<?php
use Illuminate\Support\Facades\Config;
?>
<div class="row col-md-12 j-verses-filters">
{!! Form::open(['method' => 'get','url' => '/reader/read']) !!}
{!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version',Config::get('defaultBibleVersion')),['class' => 'pull-left', 'style' => 'width: 245px; margin-right:10px;']) !!}
{!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}
{!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}
{!! Form::token() !!}
{!! Form::submit('Go',['class' => 'btn btn-primary pull-left']) !!}
@if(isset($compare['versions']))
    {!! Form::select('compare', array_merge(['' => 'Compare with ...'],$compare['versions']), Request::input('compare'),['class' => 'pull-left', 'style' => 'width: 245px; margin-left:35px;']) !!}
    {!! Form::submit('Compare',['class' => 'btn btn-primary pull-left','style' => 'margin-left:10px;']) !!}
    {!! Html::link(url('reader/read?'.http_build_query($compare['resetParams']),[],false), 'Reset', ['class' => 'btn btn-default btn-danger','style' => 'margin-left:10px;'], true) !!}
@endif
{!! Form::close() !!}
</div>