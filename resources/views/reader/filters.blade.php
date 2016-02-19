<?php
use Illuminate\Support\Facades\Config;
?>
<div class="row col-md-12 j-verses-filters">
{!! Form::open(['method' => 'get','url' => '/reader/read']) !!}
{!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version',Config::get('defaultBibleVersion')),['class' => 'pull-left', 'style' => 'width: 300px; margin-right:20px;']) !!}
{!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'pull-left', 'style' => 'width: 300px; margin-right:20px;']) !!}
{!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'pull-left', 'style' => 'width: 300px; margin-right:20px;']) !!}
{!! Form::token() !!}
{!! Form::submit('Go',['class' => 'btn btn-primary']) !!}
{!! Form::close() !!}
</div>