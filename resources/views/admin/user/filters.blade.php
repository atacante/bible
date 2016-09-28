<?php
?>
<div class="j-admin-user-filters">
{!! Form::open(['method' => 'get','url' => '/admin/'.(isset($filterAction)?$filterAction:'')]) !!}
{!! Form::select('role',array_merge([0 => 'All Roles'],$filters['roles']), Request::input('role'),['class' => 'pull-left', 'style' => 'width: 150px; margin-right:10px;']) !!}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Html::link('/admin/'.(isset($filterAction)?$filterAction:''),'Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
{!! Form::close() !!}
</div>