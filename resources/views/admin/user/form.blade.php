{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'class' => 'panel','role' => 'form']) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
        {!! Form::label('role', 'Role:') !!}
        {!! Form::select('role',array_merge([],$roles), count($model->roles)?$model->roles[0]->slug:Config::get('app.role.user'),[]) !!}
        @if ($errors->has('role'))
            <span class="help-block">
                        {{ $errors->first('role') }}
                    </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name') !!}
        @if ($errors->has('name'))
            <span class="help-block">
                        {{ $errors->first('name') }}
                    </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        {!! Form::label('email', 'Email:') !!}
        {!! Form::text('email') !!}
        @if ($errors->has('email'))
            <span class="help-block">
                        {{ $errors->first('email') }}
                    </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
        {!! Form::label('password', 'Password:') !!}
        <input class="form-control" type="password" name="password" value="{!! $model->password !!}" />
        @if ($errors->has('password'))
            <span class="help-block">
                        {{ $errors->first('password') }}
                    </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        {!! Form::label('password_confirmation', 'Confirm Password:') !!}
{{--        {!! Form::password('password_confirmation') !!}--}}
        <input class="form-control" type="password" name="password_confirmation" value="{!! $model->password !!}" />
        @if ($errors->has('password_confirmation'))
            <span class="help-block">
                        {{ $errors->first('password_confirmation') }}
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