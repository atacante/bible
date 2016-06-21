<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/community/find-friends']) !!}
    {{--@role('user')
    <div class="form-group">
        <div class="radio">
            <label>
                {!! Form::radio('type', 'all', !Request::get('type') || Request::get('type') == 'all') !!}
                All users
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('type', 'my', Request::get('type') == 'my') !!}
                Only my friends
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('type', 'new', Request::get('type') == 'new') !!}
                Only new users
            </label>
        </div>
    </div>
    @endrole--}}
    <div class="form-group">
        {!! Form::label('search', 'Search') !!}
        {!! Form::text('search',Request::input('search'),['placeholder' => 'Name or Email','class' => 'form-control','style' => '']) !!}
    </div>
    {!! Form::token() !!}
    <div class="form-group">
        {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
        {!! Html::link('/community/find-friends','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    </div>
{!! Form::close() !!}
</div>