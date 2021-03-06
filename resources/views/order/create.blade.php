    @extends('layouts.app')

    @section('content')
        <div class="c-white-content">
            {!! Form::model($model, ['class'=>'j-create-order-form', 'method' => 'post', 'url' => '/order/checkout']) !!}
            <h3 class="h3-kit cu1-title">{{ $page_title or "Page Title" }}</h3>
            <div class="panel-body">
                {!! Form::hidden('user_id', $user_id) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('shipping_first_name') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_first_name', ucwords(str_replace('_',' ', 'shipping_first_name'))) !!}
                                <span class="req">*</span>
                                {!!  Form::text('shipping_first_name', $model->shipping_first_name, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_first_name'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_first_name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('shipping_last_name') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_last_name', ucwords(str_replace('_',' ', 'shipping_last_name'))) !!}
                                <span class="req">*</span>
                                {!!  Form::text('shipping_last_name', $model->shipping_last_name, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_last_name'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_last_name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('shipping_email') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_email', ucwords(str_replace('_',' ', 'shipping_email'))) !!}
                                <span class="req">*</span>
                                {!!  Form::text('shipping_email', $model->shipping_email, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_email'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_email') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-8">
                            <div class="form-group {{ $errors->has('shipping_address') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_address', ucwords(str_replace('_',' ', 'shipping_address'))) !!}
                                <span class="req">*</span>
                                {!!  Form::text('shipping_address', $model->shipping_address, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_address'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_address') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('shipping_city') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_city', ucwords(str_replace('_',' ', 'shipping_city'))) !!}
                                <span class="req">*</span>
                                {!!  Form::text('shipping_city', $model->shipping_city, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_city'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_city') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('shipping_postcode') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_postcode', ucwords(str_replace('_',' ', 'shipping_postcode'))) !!}
                                <span class="req">*</span>
                                {!!  Form::text('shipping_postcode', $model->shipping_postcode, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_postcode'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_postcode') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('shipping_country') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_country', ucwords(str_replace('_',' ', 'shipping_country'))) !!}
                                {!!  Form::text('shipping_country', $model->shipping_country, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_country'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_country') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('shipping_state') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_state', ucwords(str_replace('_',' ', 'shipping_state'))) !!}
                                {!!  Form::text('shipping_state', $model->shipping_state, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_state'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_state') }}
                                </span>
                                @endif
                                <span style="font-size: 0.82rem">*shipping to Florida will add 7% tax to your order</span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('shipping_phone') ? ' has-error' : '' }}">
                                {!!  Form::label('shipping_phone', ucwords(str_replace('_',' ', 'shipping_phone'))) !!}
                                {!!  Form::text('shipping_phone', $model->shipping_phone, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('shipping_phone'))
                                    <span class="help-block">
                                    {{ $errors->first('shipping_phone') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mb4">
                        <input class="j-show-billing cust-radio" type="checkbox" name="show-billing" id="show-billing" checked />
                        <label class="label-checkbox" for="show-billing">Billing Information: Same as Shipping</label>
                    </div>
                </div>
                <div class="j-billing-meta {!! ViewHelper::checkBillingInfoToShow()?'':'hidden' !!}">
                    <div class="row">
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('billing_first_name') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_first_name', ucwords(str_replace('_',' ', 'billing_first_name'))) !!}
                                {!!  Form::text('billing_first_name', $model->billing_first_name, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_first_name'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_first_name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('billing_last_name') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_last_name', ucwords(str_replace('_',' ', 'billing_last_name'))) !!}
                                {!!  Form::text('billing_last_name', $model->billing_last_name, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_last_name'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_last_name') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('billing_email') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_email', ucwords(str_replace('_',' ', 'billing_email'))) !!}
                                {!!  Form::text('billing_email', $model->billing_email, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_email'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_email') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-8">
                            <div class="form-group {{ $errors->has('billing_address') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_address', ucwords(str_replace('_',' ', 'billing_address'))) !!}
                                {!!  Form::text('billing_address', $model->billing_address, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_address'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_address') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <div class="form-group {{ $errors->has('billing_city') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_city', ucwords(str_replace('_',' ', 'billing_city'))) !!}
                                {!!  Form::text('billing_city', $model->billing_city, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_city'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_city') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('billing_postcode') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_postcode', ucwords(str_replace('_',' ', 'billing_postcode'))) !!}
                                {!!  Form::text('billing_postcode', $model->billing_postcode, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_postcode'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_postcode') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('billing_country') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_country', ucwords(str_replace('_',' ', 'billing_country'))) !!}
                                {!!  Form::text('billing_country', $model->billing_country, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_country'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_country') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('billing_state') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_state', ucwords(str_replace('_',' ', 'billing_state'))) !!}
                                {!!  Form::text('billing_state', $model->billing_state, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_state'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_state') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-3">
                            <div class="form-group {{ $errors->has('billing_phone') ? ' has-error' : '' }}">
                                {!!  Form::label('billing_phone', ucwords(str_replace('_',' ', 'billing_phone'))) !!}
                                {!!  Form::text('billing_phone', $model->billing_phone, ['class' => 'form-control input1']) !!}
                                @if ($errors->has('billing_phone'))
                                    <span class="help-block">
                                    {{ $errors->first('billing_phone') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                @if($taxable)
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        {!! Form::button('Quote Shipping Rate', ['type'=>'button','class'=>'btn2-kit j-quote-rate']) !!}
                        <span class="j-quote-result"></span>
                    </div>
                </div>
                <hr />
                @endif
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <div class="form-group {{ $errors->has('card_number') ? ' has-error' : '' }}">
                            {!!  Form::label('card_number', ucwords(str_replace('_',' ', 'card_number'))) !!}
                            {!!  Form::text('card_number', null, ['placeholder'=>'XXXXXXXXXXXXXXXX','class' => 'form-control input1']) !!}
                            @if ($errors->has('card_number'))
                                <span class="help-block">
                                    {{ $errors->first('card_number') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="form-group {{ $errors->has('card_expiration') ? ' has-error' : '' }}">
                            {!!  Form::label('card_expiration', ucwords(str_replace('_',' ', 'card_expiration'))) !!}
                            {!!  Form::text('card_expiration', null, ['placeholder'=>'MM-YYYY', 'class' => 'form-control input1']) !!}
                            @if ($errors->has('card_expiration'))
                                <span class="help-block">
                                    {{ $errors->first('card_expiration') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <div class="form-group {{ $errors->has('card_code') ? ' has-error' : '' }}">
                            {!!  Form::label('card_code', ucwords(str_replace('_',' ', 'card_code'))) !!}
                            {!!  Form::text('card_code', null, ['placeholder'=>'XXX', 'class' => 'form-control input1']) !!}
                            @if ($errors->has('card_code'))
                                <span class="help-block">
                                    {{ $errors->first('card_code') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="mb1 mt13">
            {!! Form::button('Confirm', ['type'=>'button','class'=>'btn2-kit pull-right j-submit-order']) !!}
            <div class="clearfix"></div>
        </div>
        {!! Form::close() !!}
    @endsection