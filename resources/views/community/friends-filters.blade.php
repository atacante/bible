{!! Form::open(['method' => 'get','url' => '/community/find-friends?']) !!}
    {!! Form::hidden('type',Request::input('type')) !!}
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by name or email...','class' => 'search-text-input']) !!}
    {!! Form::token() !!}
    {!! Form::button('<i class="bs-search cu-search1"></i>',['type' => 'submit','class' => 'btn1 cu3-btn1']) !!}
    <a href="{!! url('/community/find-friends') !!}" style="position: absolute; top: 2px; right: 66px; font-size: 18px;"><i class="fa fa-btn fa-times"></i></a>
{!! Form::close() !!}