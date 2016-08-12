<?php
?>
<div class="j-admin-user-filters">
{!! Form::open(['method' => 'get','url' => '/admin/reports/','class' => 'form-inline']) !!}
    <div class="form-group" style="margin-right: 5px;">
        {!! Form::select('plan_type', ['' => 'All Users',App\User::PLAN_FREE => 'Free users',App\User::PLAN_PREMIUM => 'Premium users'], Request::input('book'),['class' => 'form-control', 'style' => '']) !!}
    </div>
    <div class="form-group" style="margin-right: 5px;">
        {!! Form::text('search',Request::input('search'),['placeholder' => 'Search by name...','class' => 'form-control','style' => '']) !!}
    </div>
    <div class="form-group">
        <div class="input-group input-daterange">
            {!! Form::text('date_from',Request::input('date_from'),['placeholder' => 'Date from','class' => 'form-control datepicker','style' => '']) !!}{{--mm/dd/yyyy--}}
            <span class="input-group-addon">to</span>
            {!! Form::text('date_to',Request::input('date_to'),['placeholder' => 'Date to','class' => 'form-control datepicker','style' => '']) !!}
        </div>
    </div>
{!! Form::token() !!}
    <div class="form-group">
        {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left','style' => 'margin-left:5px;']) !!}
        {!! Html::link('/admin/reports/','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    </div>
{!! Form::close() !!}
</div>