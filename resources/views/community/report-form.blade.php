{!! Form::open(['method' => 'post', 'id' => 'report-form', 'class' => 'j-report-form','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('reason_text') ? ' has-error' : '' }}">
        {!! Form::label('reason_text', 'Reason:') !!}
        {!! Form::textarea('reason_text',null,['id' => 'group-text','rows' => 3]) !!}
        @if ($errors->has('reason_text'))
            <span class="help-block">
                {{ $errors->first('reason_text') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Send', ['type'=>'submit','class'=>'btn btn-primary j-send-report']) !!}
    {!! Html::link('#','Cancel', ['class'=>'btn btn-danger','data-dismiss' => 'modal']) !!}
</div>
{!! Form::close() !!}
