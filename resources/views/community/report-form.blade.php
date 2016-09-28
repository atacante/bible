{!! Form::open(['method' => 'post', 'id' => 'report-form', 'class' => 'j-report-form entry-form','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('reason_text') ? ' has-error' : '' }}">
        {!! Form::label('reason_text', 'Reason:') !!}
        {!! Form::textarea('reason_text',null,['id' => 'group-text','rows' => 5]) !!}
        @if ($errors->has('reason_text'))
            <span class="help-block">
                {{ $errors->first('reason_text') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer" style="padding-top: 10px;">
    {!! Form::button('Send', ['type'=>'submit','class'=>'btn2-kit cu-btn-pad1 j-send-report']) !!}
    {!! Html::link('#','Cancel', ['class'=>'btn4-kit cu-btn-pad1','data-dismiss' => 'modal']) !!}
</div>
{!! Form::close() !!}
