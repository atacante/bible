{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'class' => 'panel','role' => 'form']) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('location_name') ? ' has-error' : '' }}">
        {!! Form::label('location_name', 'Name:') !!}
        {!! Form::text('location_name') !!}
        @if ($errors->has('location_name'))
            <span class="help-block">
                        {{ $errors->first('location_name') }}
                    </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('location_description') ? ' has-error' : '' }}">
        {!! Form::label('location_description', 'Description:') !!}
        {!! Form::textarea('location_description',null,['id' => 'location-desc']) !!}
        @if ($errors->has('location_description'))
            <span class="help-block">
                {{ $errors->first('location_description') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/user/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}