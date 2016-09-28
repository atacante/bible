{!! Form::open(['method' => 'get','url' => '/community/find-friends?']) !!}
    {!! Form::hidden('type',Request::input('type')) !!}
    {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by name or email...','class' => 'search-text-input cu-sti1']) !!}
    {!! Form::token() !!}
    {!! Form::button('<i class="bs-search cu-search1"></i>',['type' => 'submit','class' => 'btn1 cu3-btn1']) !!}
    <a href="{!! url('/community/find-friends') !!}" class="btn-reset" style="position: absolute; top: 4px; right: 48px;">&#215;</a>
{!! Form::close() !!}