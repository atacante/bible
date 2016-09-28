<?php
?>
<div class="j-admin-verses-filters">
{!! Form::open(['method' => 'get','url' => '/admin/shop-products/list']) !!}
{!! Form::text('search',Request::input('search'),['placeholder' => 'Keyword...','class' => 'pull-left','style' => 'margin-right:10px; width:200px;']) !!}
{!! Form::select('category', [0 => 'All Categories'] + $filters['categories'], Request::input('category'),['class' => 'pull-left', 'style' => 'width: 170px; margin-right:10px;']) !!}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Html::link('/admin/shop-products/list','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
{!! Form::close() !!}
</div>