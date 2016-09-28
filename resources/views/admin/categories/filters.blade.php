<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/admin/categories/list']) !!}
{!! Form::text('search',Request::input('search'),['placeholder' => 'Keyword...','class' => 'pull-left','style' => 'margin-right:10px; width:200px;']) !!}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Html::link('/admin/categories/list','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
{!! Form::close() !!}
</div>