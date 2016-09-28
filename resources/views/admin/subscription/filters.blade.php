<?php
?>
<div>
{!! Form::open(['method' => 'get','url' => '/admin/'.(isset($filterAction)?$filterAction:'')]) !!}
{!! Form::select('subscription', [0 => 'All Subscriptions', 1 => 'Subscribed', 2 => 'Unsubscribed'], Request::input('subscription'), ['class' => 'pull-left', 'style' => 'width: 150px; margin-right:10px;']) !!}
{!! Form::token() !!}
{!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
{!! Html::link('/admin/'.(isset($filterAction)?$filterAction:''),'Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
{!! Form::close() !!}
</div>