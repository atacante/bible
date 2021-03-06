<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/admin/'.(isset($filterAction)?$filterAction:'')]) !!}
{{--{!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version','all'),['class' => 'pull-left', 'style' => 'width: 245px; margin-right:10px;']) !!}--}}
{!! Form::select('book', [0 => 'All Books']+$filters['books'], Request::input('book'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}
{!! Form::select('chapter',array_merge([0 => 'All Chapters'],(Request::input('book') == 0?[]:$filters['chapters'])), Request::input('chapter'),['class' => 'pull-left',(Request::input('book') == 0?'disabled':''), 'style' => 'width: 170px; margin-right:10px;']) !!}
{!! Form::select('verse',array_merge([0 => 'All Verses'],(Request::input('chapter') == 0?[]:$filters['verses'])), Request::input('verse'),['class' => 'pull-left', (Request::input('chapter') == 0?'disabled':''), 'style' => 'width: 115px; margin-right:10px;']) !!}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Html::link('/admin/'.(isset($filterAction)?$filterAction:''),'Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
{!! Form::close() !!}
</div>