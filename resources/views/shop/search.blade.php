{!! Form::open(['method' => 'get','url' => '/shop']) !!}
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by text...','class' => 'form-control','style' => 'width:200px; display:inline;']) !!}
    {!! Form::token() !!}
    {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary']) !!}
{!! Form::close() !!}