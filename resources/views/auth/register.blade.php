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

                        {{--<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">--}}
                        {{--{!! Form::label('username', 'Username', array('class' => 'col-md-4 control-label')) !!}--}}

                        {{--<div class="col-md-6">--}}
                        {{--{!! Form::text('username', old('username'), array('class' => 'form-control')) !!}--}}

                        {{--@if ($errors->has('username'))--}}
                        {{--<span class="help-block">--}}
                        {{--{{ $errors->first('username') }}--}}
                        {{--</span>--}}
                        {{--@endif--}}
                        {{--</div>--}}
                        {{--</div>--}}

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

                        <div class="form-group{{ $errors->has('invited_by_id') ? ' has-error' : '' }}">
                            {!! Form::label('invited_by_id', 'Invited By', array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                {!! Form::select('invited_by_id', [Session::pull('inviter_id') => Session::pull('inviter_name')], old('invited_by_id'),['class' => 'form-control j-select2-ajax','data-url'=> '/ajax/users-list','placeholder' => 'Select users...']) !!}
                                @if ($errors->has('invited_by_id'))
                                    <span class="help-block">
                                        {{ $errors->first('invited_by_id') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('plan_type') ? ' has-error' : '' }}">
                            {!! Form::label('plan_type', "Subscription plan", array('class' => 'col-md-4 control-label')) !!}

                            <div class="col-md-6">
                                <label class="radio-inline">
                                    {!! Form::radio('plan_type', 'free', true) !!}
                                    Free
                                </label>
                                <label class="radio-inline">
                                    {!! Form::radio('plan_type', 'premium', false) !!}
                                    Premium
                                </label>
                                @if ($errors->has('plan_type'))
                                    <span class="help-block">
                                        {{ $errors->first('plan_type') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="premium-only  {!! Request::old('plan_type') == 'premium' || $errors->has('coupon_code')?'':'hidden' !!}">
                            <div class="form-group {{ $errors->has('plan_name') ? ' has-error' : '' }}">
                                {!! Form::label('plan_name', "Subscription plan period:",['class' => 'col-md-4']) !!}

                                <div class="col-md-6">
                                    @foreach(App\User::getPossiblePlans() as $plan_name => $plan)
                                        <label class="radio-inline">
                                            {!! Form::radio('plan_name', $plan_name, false) !!}
                                            {!! $plan_name.'($'.$plan['amount'].')' !!}
                                        </label>
                                    @endforeach
                                    @if ($errors->has('plan_name'))
                                        <span class="help-block">
                                        {{ $errors->first('plan_name') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('coupon_code') ? ' has-error' : '' }}">
                                {!! Form::label('coupon_code', 'Coupon Code:',['class' => 'col-md-4']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('coupon_code') !!}
                                    @if ($errors->has('coupon_code'))
                                        <span class="help-block">
                                            {{ $errors->first('coupon_code') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
                                {!! Form::label('card_number', 'New CC Number:',['class' => 'col-md-4']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('card_number', null, ['placeholder' => 'XXXXXXXXXXXXXX']) !!}
                                    @if ($errors->has('card_number'))
                                        <span class="help-block">
                                            {{ $errors->first('card_number') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{ $errors->has('card_expiration') ? ' has-error' : '' }}">
                                {!! Form::label('card_expiration', 'Credit Card Expiration:',['class' => 'col-md-4']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('card_expiration', null, ['placeholder' => 'YYYY-MM']) !!}
                                    @if ($errors->has('card_expiration'))
                                        <span class="help-block">
                                        {{ $errors->first('card_expiration') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::hidden('subscribed', 0) !!}
                                        {!! Form::checkbox('subscribed', 1, true) !!}
                                        I want to get the latest news by my Email
                                    </label>
                                </div>
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
