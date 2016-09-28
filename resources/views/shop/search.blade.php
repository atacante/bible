{!! Form::open(['method' => 'get','url' => '/shop']) !!}
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by text...','class' => 'form-control search-text-input']) !!}
    {!! Form::token() !!}
    {!! Form::button('<i class="bs-search cu-search1"></i>',['type' => 'submit','class' => 'btn1 cu3-btn1 pull-left']) !!}
{!! Form::close() !!}