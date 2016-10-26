{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'coupon-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('coupon_code') ? ' has-error' : '' }}">
        {!! Form::label('coupon_code', 'Coupon Code (set manually or generate random value):') !!}
        {!! Form::text('coupon_code') !!}
        {!! Html::link('#','Generate code', ['type'=>'button','class'=>'btn btn-success generate-coupon-code j-generate-coupon-code']) !!}
        @if ($errors->has('coupon_code'))
            <span class="help-block">
                {{ $errors->first('coupon_code') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('member_type') ? ' has-error' : '' }}">
        {!! Form::label('member_type', 'Member Type (coupon will be assigned to specific users member type):') !!}
        {!! Form::select('member_type', [0 => 'All Member Types']+$userRoles, Request::input('user_id'),['class' => 'j-select-role', 'id' => '']) !!}
        @if ($errors->has('member_type'))
            <span class="help-block">
                {{ $errors->first('user_id') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('user_id') ? ' has-error' : '' }}">
        {!! Form::label('user_id', 'User (coupon will be assigned to specific user):') !!}
        {!! Form::select('user_id', [0 => 'All Users']+$users, Request::input('user_id'),['class' => 'j-select-user', 'id' => '']) !!}
        @if ($errors->has('user_id'))
            <span class="help-block">
                {{ $errors->first('user_id') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('amount') ? ' has-error' : '' }}">
        {!! Form::label('amount', 'Discount amount ($):') !!}
        {!! Form::text('amount') !!}
        @if ($errors->has('amount'))
            <span class="help-block">
                {{ $errors->first('amount') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('expire_at') ? ' has-error' : '' }}">
        {!! Form::label('expire_at', 'Expiration (leave empty to ignore expiration):') !!}
        {!! Form::text('expire_at',$model->expire_at?$model->expire_at->format($model::DFORMAT):'',['placeholder' => 'mm/dd/yyyy','class' => 'form-control coupon-datepicker','style' => '']) !!}
        @if ($errors->has('expire_at'))
            <span class="help-block">
                {{ $errors->first('expire_at') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('is_permanent') ? ' has-error' : '' }}">
        <div class="checkbox">
            <label>
                {!! Form::hidden('is_permanent', 0) !!}
                {!! Form::checkbox('is_permanent', 1,false) !!}
                Is permanent (will offer a permanently reduced membership rate for all future occurrences of the monthly bill):
            </label>
        </div>
        @if ($errors->has('is_permanent'))
            <span class="help-block">
                {{ $errors->first('is_permanent') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('uses_limit') ? ' has-error' : '' }}">
        {!! Form::label('uses_limit', '# of times can be used (leave empty or set 0 for unlimited):') !!}
        {!! Form::text('uses_limit') !!}
        @if ($errors->has('uses_limit'))
            <span class="help-block">
                {{ $errors->first('uses_limit') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/coupons/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}
@include('admin.partials.imageupload')