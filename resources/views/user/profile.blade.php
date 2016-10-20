    @extends('layouts.app')

    @section('breadcrumbs', Breadcrumbs::render('userUpdate',$model))

    @section('content')
        {!! Form::model($model, ['method' => 'put', 'class' => '','role' => 'form']) !!}
        {!! Form::hidden('user_id', $model->id) !!}
        <div class="row">
            <div class="col-xs-12">
                <h2 class="h2-new mb3">
                    <i class="bs-user cu-gift2" style="margin-right: 7px;"></i>
                    {{ $page_title or "Page Title" }}
                </h2>
            </div>
        </div>
        <div class="c-white-content">
            <div class="inner-pad2">
            <div class="row">
                <div class="col-xs-6">

                    <div class="c-cont-w-avatar">

                        <div class="left-avatar {{ $errors->has('avatar') ? ' has-error' : '' }}">
                            <div id="avatar" class="dropzone">
                                <div id="img-thumb-preview" class="pull-left">
                                    @if($model->avatar)
                                        <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete j-image-preview j-avatar-preview">
                                            <div class="dz-image">
                                                <img height="140" width="140" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$model->id.'/thumbs/'.$model->avatar !!}">
                                            </div>
                                            <div class="dz-details">
                                                <i data-filename="{!! $model->avatar !!}"  class="remove-image j-remove-image fa fa-times-circle fa-4x"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <i class="select-image j-select-image pull-left fa fa-plus-circle fa-4x" style="color: #367fa9; padding: 46px; cursor: pointer;"></i>
                            </div>
                            @if ($errors->has('avatar'))
                                <span class="help-block">
                                {{ $errors->first('avatar') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', 'Name:') !!}
                            {!! Form::text('name',  $model->name, array('class' => 'input1')) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', 'Email:') !!}
                            {!! Form::text('email',  $model->email, array('class' => 'input1')) !!}
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        {!! Form::label('password', 'New Password:') !!}
                        <input class="form-control input1" type="password" name="password" value="{!! $model->password !!}"/>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        {!! Form::label('password_confirmation', 'Confirm Password:') !!}
                        {{--        {!! Form::password('password_confirmation') !!}--}}
                        <input class="form-control input1" type="password" name="password_confirmation" value="{!! $model->password !!}"/>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                {{ $errors->first('password_confirmation') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('about_me') ? ' has-error' : '' }}">
                        {!! Form::label('about_me', 'About Me:') !!}
                        {!! Form::textarea('about_me', $model->about_me, array("class"=>"input1 cu-area-profile")) !!}
                        @if ($errors->has('about_me'))
                            <span class="help-block">
                                {{ $errors->first('about_me') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                        {!! Form::label('country_id', 'Country', array('class' => '')) !!}
                        {!! Form::select('country_id', ViewHelper::getCountriesList(), old('country_id'), ['class' => 'form-control2 input1 j-select2-country','data-url'=> '/ajax/users-list','placeholder' => 'Select country...']) !!}
                        @if ($errors->has('country_id'))
                            <span class="help-block">
                                {{ $errors->first('country_id') }}
                            </span>
                        @endif
                    </div>
                    <div class="j-state form-group{{ $errors->has('country_id') ? ' has-error' : '' }} {!! $model->country_id == 226?'':'hidden' !!}">
                        {!! Form::label('state', 'State', array('class' => '')) !!}
                        {!! Form::select('state', ViewHelper::getUsStatesList(), old('state'), ['class' => 'form-control2 input1 j-select2-state','placeholder' => 'Select state...']) !!}
                        @if ($errors->has('state'))
                            <span class="help-block">
                                {{ $errors->first('state') }}
                            </span>
                        @endif
                    </div>
                    <div class="j-state form-group{{ $errors->has('state') ? ' has-error' : '' }} {!! $model->country_id == 226?'hidden':'' !!}">
                        {!! Form::label('state', 'State', array('class' => 'control-label')) !!}
                        {!! Form::text('state', old('state'), array('class' => 'form-control input1')) !!}
                        @if ($errors->has('state'))
                            <span class="help-block">
                                {{ $errors->first('state') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
                        {!! Form::text('city', old('city'), array('class' => 'form-control input1')) !!}
                        @if ($errors->has('city'))
                            <span class="help-block">
                                    {{ $errors->first('city') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('subscribed', "Newsletter:") !!}

                        <div>
                            {!! Form::hidden('subscribed', 0) !!}
                            {!! Form::checkbox('subscribed', 1, $model->subscribed, ["class" => "cust-radio", "id" => "subscribed2"]) !!}
                            <label for="subscribed2" class="label-checkbox cu-label  mt15">I want to get the latest news by my Email</label>
                        </div>
                    </div>
                </div>


                {{-- COL 2 --}}


                <div class="col-xs-6">

                    <div class="form-group{{ $errors->has('church_name') ? ' has-error' : '' }}">
                        {!! Form::label('church_name', 'Church Name', array('class' => 'control-label')) !!}
                        {!! Form::text('church_name', old('church_name'), array('class' => 'form-control input1')) !!}
                        @if ($errors->has('church_name'))
                            <span class="help-block">
                                    {{ $errors->first('church_name') }}
                                </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('invited_by_id') ? ' has-error' : '' }}">
                        {!! Form::label('invited_by_id', 'Invited By') !!}
                        {!! Form::select('invited_by_id', $model->inviter?[$model->inviter->id => $model->inviter->name]:[], old('invited_by_id'),['class' => 'j-select2-ajax','data-url'=> '/ajax/users-list','placeholder' => 'Select users...']) !!}
                        @if ($errors->has('invited_by_id'))
                            <span class="help-block">
                                {{ $errors->first('invited_by_id') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        {!! Form::label('invite_link', 'Link to invite new users') !!}
                        <div class="input-group">
                            <input id="invite-link" type="text" class="form-control input1" value="{!! url('/invite/'.$model->id) !!}" readonly="readonly">
                            <span class="input-group-btn">
                                <button id="copy" title="Copy" class="btn copy btn-default cu-btn-copy" data-clipboard-target="#invite-link" type="button"><i class="bs-copypaste" aria-hidden="true"></i></button>
                            </span>
                        </div>
                    </div>
                    <div class="billing-box">
                        <div class="form-group {{ $errors->has('plan_type') ? ' has-error' : '' }}">
                            {!! Form::label('plan_type', "Subscription plan:") !!}

                            <div>
                                <div class="radio-inline mt15">
                                    {!! Form::radio('plan_type', 'free', true, ["class" => "cust-radio", 'id' => 'free']) !!}
                                    <label for="free" class="label-radio cu-label">Free</label>
                                </div>
                                <div class="radio-inline mt15">
                                    {!! Form::radio('plan_type', 'premium', false, ["class" => "cust-radio", 'id' => 'premium']) !!}
                                    <label for="premium" class="label-radio cu-label">Premium {!! $model->isPremiumPaid()?'(paid | Expires at: '.$model->getPlanExpiresAt().')' :'' !!}</label>
                                </div>
                                @if ($errors->has('plan_type'))
                                    <span class="help-block">
                                        {{ $errors->first('plan_type') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="premium-only {!! $model->plan_type == 'free'?'hidden':'' !!}" >
                            <div class="form-group {{ $errors->has('plan_name') ? ' has-error' : '' }}">
                                {!! Form::label('plan_name', "Subscription plan period:") !!}

                                <div>
                                    @foreach(App\User::getPossiblePlans() as $plan_name => $plan)
                                        <div class="radio-inline">
                                            {!! Form::radio('plan_name', $plan_name, ($model->getActivePlan() == $plan_name), ["class" => "cust-radio", "id" => $plan_name]) !!}
                                            <label class="label-radio cu-label" for="{{$plan_name}}"> {!! $plan_name.' ($'.$plan['amount'].')' !!}</label>
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
                                {!! Form::label('coupon_code', 'Coupon Code:') !!}
                                {!! Form::text('coupon_code', $model->coupon_code, ["class" => "input1"]) !!}
                                @if ($errors->has('coupon_code'))
                                    <span class="help-block">
                                    {{ $errors->first('coupon_code') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    {!! Form::label('current_cc_number', 'Current CC number:') !!}
                                    <div class="{{$model->card_last_four!=''?'' : 'hide' }}">
                                        <input class="form-control input1" type="text" value="**** **** **** {{$model->card_last_four}}" disabled="disabled">
                                    </div>
                                    <div class="{{$model->card_last_four!=''?'hide' : '' }}">
                                        <input class="form-control input1" type="text" value="No Credit Card" disabled="disabled">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
                                    {!! Form::label('card_number', 'New CC Number:') !!}
                                    {!! Form::text('card_number', null, ['placeholder' => 'XXXXXXXXXXXXXX', 'class' => 'input1']) !!}
                                    @if ($errors->has('card_number'))
                                        <span class="help-block">
                                        {{ $errors->first('card_number') }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="form-group {{ $errors->has('card_expiration') ? ' has-error' : '' }}">
                            {!! Form::label('card_expiration', 'Credit Card Expiration:') !!}
                            {!! Form::text('card_expiration', null, ['placeholder' => 'YYYY-MM', 'class' => 'input1', 'style' => 'width: 125px;']) !!}
                            @if ($errors->has('card_expiration'))
                                <span class="help-block">
                                {{ $errors->first('card_expiration') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('card_code') ? ' has-error' : '' }}">
                            {!! Form::label('card_code', 'Credit Card Code:') !!}
                            {!! Form::text('card_code', null, ['placeholder' => '***', 'class' => 'input1', 'style' => 'width: 125px;']) !!}
                            @if ($errors->has('card_code'))
                                <span class="help-block">
                                {{ $errors->first('card_code') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('billing_name') ? ' has-error' : '' }}">
                            {!! Form::label('billing_name', 'Billing Name:') !!}
                            {!! Form::text('billing_name', ($model->userMeta)?$model->userMeta->billing_first_name .' '.$model->userMeta->billing_last_name: '', ['class' => 'input1']) !!}
                            @if ($errors->has('billing_name'))
                                <span class="help-block">
                                    {{ $errors->first('billing_name') }}
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('billing_address') ? ' has-error' : '' }}">
                            {!! Form::label('billing_address', 'Billing Address:') !!}
                            {!! Form::text('billing_address', ($model->userMeta)?$model->userMeta->billing_address:'', ['class' => 'input1']) !!}
                            @if ($errors->has('billing_address'))
                                <span class="help-block">
                                    {{ $errors->first('billing_address') }}
                            </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('billing_zip') ? ' has-error' : '' }}">
                            {!! Form::label('billing_zip', 'Billing Zip:') !!}
                            {!! Form::text('billing_zip', ($model->userMeta)?$model->userMeta->billing_postcode:'', ['class' => 'input1']) !!}
                            @if ($errors->has('billing_zip'))
                                <span class="help-block">
                                    {{ $errors->first('billing_zip') }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->


            </div>
            </div>
        </div>

        <div>
            {!! Form::button('Save', ['type'=>'submit','class'=>'btn2-kit m-btn pull-right']) !!}
        </div>
        {!! Form::close() !!}
        @include('partials.imageupload')
    @endsection