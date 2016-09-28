<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/community/wall']) !!}
    @role('user')
    <div class="form-group">
        <div class="radio">
            <label>
                {!! Form::radio('type', 'all', !Request::get('type') || Request::get('type') == 'all') !!}
                All public items
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('type', 'friends', Request::get('type') == 'friends') !!}
                My friends items
            </label>
        </div>
    </div>
    @endrole
    {!! Form::token() !!}
    <div class="form-group">
        {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
        {!! Html::link('/community/wall','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    </div>
{!! Form::close() !!}
</div>