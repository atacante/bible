@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h2 class="bl-register h2-new mb3">
            SIGN UP
        </h2>
    </div>
</div>
<div class="bl-register c-white-content mb2">
    <div class="inner-pad5">
        <div class="bl-form row">
            <div class="col-xs-6 col-md-offset-3">
                @if($text = ViewHelper::getTooltipText('beta_mode'))
                <div class="alert alert-info" role="alert" style="color: #31708f; background-color: #d9edf7; border: 1px solid #bce8f1;">
                    {!! $text !!}
                </div>
                @endif
                    {!! Form::open(array('url' => URL::to('auth/register'), 'method' => 'post', 'files'=> true,'class' =>'form-horizontal')) !!}
                    {!! csrf_field() !!}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="name">Name <span class="req">*</span></label>
                        <div class="col-xs-8">
                            {!! Form::text('name', old('name'), array('class' => 'form-control input1')) !!}

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="email">Email <span class="req">*</span></label>
                        <div class="col-xs-8">
                            {!! Form::text('email', old('email'), array('class' => 'form-control input1')) !!}

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="password">Password <span class="req">*</span></label>
                        <div class="col-xs-8">
                            {!! Form::password('password', array('class' => 'form-control input1')) !!}

                            @if ($errors->has('password'))
                                <span class="help-block">
                                   {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label class="col-md-4 control-label" for="password_confirmation">Confirm Password <span class="req">*</span></label>
                        <div class="col-xs-8">
                            {!! Form::password('password_confirmation', array('class' => 'form-control input1')) !!}

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    {{ $errors->first('password_confirmation') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                        {!! Form::label('country_id', 'Country', array('class' => 'col-md-4 control-label')) !!}

                        <div class="col-xs-8">
                            {!! Form::select('country_id', ViewHelper::getCountriesList(), old('country_id'),['class' => 'form-control input1 j-select2-country','data-url'=> '/ajax/users-list','placeholder' => 'Select country...']) !!}
                            @if ($errors->has('country_id'))
                                <span class="help-block">
                                    {{ $errors->first('country_id') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="j-state form-group{{ $errors->has('country_id') ? ' has-error' : '' }} hidden">
                        {!! Form::label('state', 'State', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-xs-8">
                            {!! Form::select('state', ViewHelper::getUsStatesList(), old('state'), ['class' => 'form-control2 input1 j-select2-state','placeholder' => 'Select state...']) !!}
                            @if ($errors->has('state'))
                                <span class="help-block">
                                {{ $errors->first('state') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="j-state form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                        {!! Form::label('state', 'State', array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-xs-8">
                            {!! Form::text('state', old('state'), array('class' => 'form-control input1')) !!}
                            @if ($errors->has('state'))
                                <span class="help-block">
                                    {{ $errors->first('state') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        {!! Form::label('city', 'City', array('class' => 'col-md-4 control-label')) !!}

                        <div class="col-xs-8">
                            {!! Form::text('city', old('city'), array('class' => 'form-control input1')) !!}

                            @if ($errors->has('city'))
                                <span class="help-block">
                                    {{ $errors->first('city') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('church_name') ? ' has-error' : '' }}">
                        {!! Form::label('church_name', 'Church Name', array('class' => 'col-md-4 control-label')) !!}

                        <div class="col-xs-8">
                            {!! Form::text('church_name', old('church_name'), array('class' => 'form-control input1')) !!}

                            @if ($errors->has('church_name'))
                                <span class="help-block">
                                    {{ $errors->first('church_name') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('invited_by_id') ? ' has-error' : '' }}">
                        {!! Form::label('invited_by_id', 'Invited By', array('class' => 'col-md-4 control-label')) !!}

                        <div class="col-xs-8">
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

                        <div class="col-xs-8">
                            <div class="radio-inline">
                                {!! Form::radio('plan_type', 'free', true, ["class" => "cust-radio", "id" => "free"]) !!}
                                <label class="label-radio cu-label" for="free">Free</label>
                            </div>
                            <div class="radio-inline">
                                {!! Form::radio('plan_type', 'premium', false, ["class" => "cust-radio", "id" => "premium"]) !!}
                                <label class="label-radio cu-label" for="premium">Premium</label>
                            </div>
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

                            <div class="col-xs-8">
                                @foreach(App\User::getPossiblePlans() as $plan_name => $plan)
                                    <div class="radio-inline">
                                        {!! Form::radio('plan_name', $plan_name, false, ["class" => "cust-radio", "id" => $plan_name]) !!}
                                        <label class="label-radio cu-label" for="{{$plan_name}}">{!! $plan_name.'($'.$plan['amount'].')' !!}</label>
                                    </div>
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
                            <div class="col-xs-8">
                                {!! Form::text('coupon_code', Request::get('coupon_code'), ['class' => 'input1']) !!}
                                @if ($errors->has('coupon_code'))
                                    <span class="help-block">
                                        {{ $errors->first('coupon_code') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
                            {!! Form::label('card_number', 'New CC Number:',['class' => 'col-md-4']) !!}
                            <div class="col-xs-8">
                                {!! Form::text('card_number', null, ['class' => 'input1', 'placeholder' => 'XXXXXXXXXXXXXX']) !!}
                                @if ($errors->has('card_number'))
                                    <span class="help-block">
                                        {{ $errors->first('card_number') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('card_expiration') ? ' has-error' : '' }}">
                            {!! Form::label('card_expiration', 'Credit Card Expiration:',['class' => 'col-md-4']) !!}
                            <div class="col-xs-8">
                                {!! Form::text('card_expiration', null, ['class' => 'input1', 'placeholder' => 'YYYY-MM']) !!}
                                @if ($errors->has('card_expiration'))
                                    <span class="help-block">
                                    {{ $errors->first('card_expiration') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('card_code') ? ' has-error' : '' }}">
                            {!! Form::label('card_code', 'Credit Card Code:', ['class' => 'col-md-4']) !!}
                            <div class="col-xs-8">
                                {!! Form::text('card_code', null, ['placeholder' => '***', 'class' => 'input1', 'style' => 'width: 125px;']) !!}
                                @if ($errors->has('card_code'))
                                    <span class="help-block">
                                        {{ $errors->first('card_code') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('billing_name') ? ' has-error' : '' }}">
                            {!! Form::label('billing_name', 'Billing Name:', ['class' => 'col-md-4']) !!}
                            <div class="col-xs-8">
                                {!! Form::text('billing_name', null, ['class' => 'input1']) !!}
                                @if ($errors->has('billing_name'))
                                    <span class="help-block">
                                        {{ $errors->first('billing_name') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('billing_address') ? ' has-error' : '' }}">
                            {!! Form::label('billing_address', 'Billing Address:', ['class' => 'col-md-4']) !!}
                             <div class="col-xs-8">
                                {!! Form::text('billing_address', null, ['class' => 'input1']) !!}
                                @if ($errors->has('billing_address'))
                                    <span class="help-block">
                                        {{ $errors->first('billing_address') }}
                                    </span>
                                @endif
                             </div>
                        </div>
                        <div class="form-group {{ $errors->has('billing_zip') ? ' has-error' : '' }}">
                            {!! Form::label('billing_zip', 'Billing Zip:', ['class' => 'col-md-4']) !!}
                            <div class="col-xs-8">
                                {!! Form::text('billing_zip', null, ['class' => 'input1']) !!}
                                @if ($errors->has('billing_zip'))
                                    <span class="help-block">
                                        {{ $errors->first('billing_zip') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group checkbox">
                        <div class="col-md-4"></div>
                        <div class="col-xs-8">
                            <div>
{{--                                    {!! Form::hidden('subscribed',0) !!}--}}
                                <input name="subscribed" type="hidden" value="0">
                                {!! Form::checkbox('subscribed', 1, true, ["class" => "cust-radio", "id" => "subscribed2"]) !!}
                                <label for="subscribed2" class="label-checkbox cu-label mt15">I want to get the latest news by Email</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group  {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
                        {!! Form::label('', null, array('class' => 'col-md-4 control-label')) !!}
                        <div class="col-xs-8">
                            {!! Captcha::display() !!}
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="help-block">
                                    {{ $errors->first('g-recaptcha-response') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-8 col-md-offset-4">
                            <button type="submit" class="btn2-kit mt16 cu-btn-pad1">
                                SIGN UP
                            </button>
                        </div>
                    </div>
                </form>

            </div>



        </div>
    </div>
</div>
{{--    </div>--}}
@endsection
