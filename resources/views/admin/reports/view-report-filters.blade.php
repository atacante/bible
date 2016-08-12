<?php
?>
<div class="j-admin-user-filters">
{!! Form::open(['method' => 'get','url' => '/admin/reports/'.Request::segment(3).'/'.$id,'class' => 'form-inline']) !!}
    <div class="form-group">
{{--        {!! Form::label('date_from', 'Date') !!}--}}
        <div class="input-group input-daterange">
            {!! Form::text('date_from',Request::input('date_from'),['placeholder' => 'Date from','class' => 'form-control datepicker','style' => '']) !!}{{--mm/dd/yyyy--}}
            <span class="input-group-addon">to</span>
            {!! Form::text('date_to',Request::input('date_to'),['placeholder' => 'Date to','class' => 'form-control datepicker','style' => '']) !!}
        </div>
    </div>
{!! Form::token() !!}
    <div class="form-group">
        {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left','style' => 'margin-left:5px;']) !!}
        {!! Html::link('/admin/reports/'.Request::segment(3).'/'.$id,'Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    </div>
{!! Form::close() !!}
</div>