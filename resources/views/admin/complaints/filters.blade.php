<div class="j-admin-user-filters">
    {!! Form::open(['method' => 'get','url' => '/admin/complaints/list']) !!}
    {!! Form::select('type',array_merge([0 => 'All Types'],['note' => 'Notes','journal' => 'Journal','prayer' => 'Prayers','status' => 'Status Updates']), Request::input('type'),['class' => 'pull-left', 'style' => 'width: 150px; margin-right:10px;']) !!}
    {!! Form::select('status',array_merge(['' => 'All Statuses'],[0 => 'Pending',1 => 'Resolved']), Request::input('status'),['class' => 'pull-left', 'style' => 'width: 150px; margin-right:10px;']) !!}
    {!! Form::token() !!}
    {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
    {!! Html::link('/admin/complaints/list','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    {!! Form::close() !!}
</div>