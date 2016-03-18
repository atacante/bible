    @extends('layouts.app')

    @section('breadcrumbs', Breadcrumbs::render('userUpdate',$model))

    @section('content')
        <div class="panel panel-default">
            <div class="panel-heading">{{ $page_title or "Page Title" }}</div>
            <div class="panel-body">
                {!! Form::model($model, ['method' => 'put', 'class' => '','role' => 'form']) !!}
                <div class="box-body">
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
                        {!! Form::label('password', 'New Password:') !!}
                        <input class="form-control" type="password" name="password" value="{!! $model->password !!}"/>
                        @if ($errors->has('password'))
                            <span class="help-block">
                            {{ $errors->first('password') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        {!! Form::label('password_confirmation', 'Confirm Password:') !!}
                        {{--        {!! Form::password('password_confirmation') !!}--}}
                        <input class="form-control" type="password" name="password_confirmation"
                               value="{!! $model->password !!}"/>
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
{{--                    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/user/list/'),'Cancel', ['class'=>'btn btn-default']) !!}--}}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endsection