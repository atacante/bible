<div class="row col-md-12 j-verses-filters">
{!! Form::open(['method' => 'get']) !!}
{!! Form::select('version', $filters['versions'], Request::input('version'),['class' => 'pull-left', 'style' => 'width: 300px; margin-right:20px;']) !!}
{!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'pull-left', 'style' => 'width: 300px; margin-right:20px;']) !!}
{!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'pull-left', 'style' => 'width: 300px; margin-right:20px;']) !!}
{!! Form::token() !!}
{!! Form::submit('Go') !!}
{!! Form::close() !!}
</div>