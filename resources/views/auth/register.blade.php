@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                        {!! Form::open(array('url' => URL::to('auth/register'), 'method' => 'post', 'files'=> true,'class' =>'form-horizontal')) !!}
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', 'Name', array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::text('name', old('name'), array('class' => 'form-control')) !!}

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('username', 'Username', array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::text('username', old('username'), array('class' => 'form-control')) !!}

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        {{ $errors->first('username') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', 'Email', array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::text('email', old('email'), array('class' => 'form-control')) !!}

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', "Password", array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::password('password', array('class' => 'form-control')) !!}

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                       {{ $errors->first('password') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('password_confirmation', "Confirm Password", array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        {{ $errors->first('password_confirmation') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                            {!! Form::label('', null, array('class' => 'col-md-4 control-label')) !!}
                            <div class="col-md-6">
                                {!! Captcha::display() !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block">
                                        {{ $errors->first('g-recaptcha-response') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-user"></i>Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
