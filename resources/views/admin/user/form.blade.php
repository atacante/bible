{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'class' => 'panel','role' => 'form']) !!}
{!! Form::hidden('user_id', $model->id) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('avatar') ? ' has-error' : '' }}">
        {!! Form::label('avatar', 'Avatar:') !!}
        <div id="avatar" class="dropzone user-image">
            <div id="img-thumb-preview" class="pull-left">
                @if($model->avatar)
                    <div class="dz-preview dz-processing dz-image-preview dz-success dz-complete j-image-preview j-avatar-preview">
                        <div class="dz-image">
                            <img height="120" width="120" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$model->id.'/thumbs/'.$model->avatar !!}">
                        </div>
                        <div class="dz-details">
                            <i data-filename="{!! $model->avatar !!}"  class="remove-image j-remove-image fa fa-times-circle fa-4x" style="position:absolute; top: 34px; left: 39px; color: #f4645f; cursor: pointer;"></i>
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
        {!! Form::text('name', $model->getRawName()) !!}
        @if ($errors->has('name'))
            <span class="help-block">
                {{ $errors->first('name') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('lastname') ? ' has-error' : '' }}">
        {!! Form::label('lastname', 'Last Name:') !!}
        {!! Form::text('lastname') !!}
        @if ($errors->has('lastname'))
            <span class="help-block">
                {{ $errors->first('lastname') }}
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
    <div class="form-group {{ $errors->has('plan_type') ? ' has-error' : '' }}">
        {!! Form::label('plan_type', "Account Type:") !!}

        <div>
            <div class="radio-inline mt15">
                {!! Form::radio('plan_type', 'free', true, ["class" => "cust-radio", 'id' => 'free']) !!}
                <label for="free" class="label-radio cu-label">Free</label>
            </div>
            <div class="radio-inline mt15">
                {!! Form::radio('plan_type', 'premium', false, ["class" => "cust-radio", 'id' => 'premium']) !!}
                <label for="premium" class="label-radio cu-label">Premium (Beta Tester) {!! $model->isPremiumPaid()?'(Active - Expires '.$model->getPlanExpiresAt().')' :'' !!}</label>
            </div>
            @if ($errors->has('plan_type'))
                <span class="help-block">
                    {{ $errors->first('plan_type') }}
                </span>
            @endif
        </div>
    </div>
    <div class="premium-only {!! ($model->plan_type == 'free' && Request::old('plan_type') != 'premium')?'hidden':'' !!}" >
        <div class="form-group {{ $errors->has('plan_name') ? ' has-error' : '' }}">
            {!! Form::label('plan_name', "Subscription plan period:") !!}

            <div>
                @foreach(App\User::getPossiblePlans() as $plan_name => $plan)
                    <div class="radio-inline">
                        {!! Form::radio('plan_name', $plan_name, $model->getActivePlan()?($model->getActivePlan() == $plan_name):('3 months' == $plan_name), ["class" => "cust-radio", "id" => $plan_name]) !!}
                        <label class="label-radio cu-label" for="{{$plan_name}}"> {!! $plan_name !!}</label>
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
            {!! Form::label('coupon_code', 'Beta Code:') !!}
            {!! Form::text('coupon_code', $model->coupon_code, ["class" => "input1"]) !!}
            @if ($errors->has('coupon_code'))
                <span class="help-block">
                    {{ $errors->first('coupon_code') }}
                </span>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('about_me') ? ' has-error' : '' }}">
        {!! Form::label('about_me', 'About Me:') !!}
        {!! Form::textarea('about_me') !!}
        @if ($errors->has('about_me'))
            <span class="help-block">
                {{ $errors->first('about_me') }}
            </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
        {!! Form::label('country_id', 'Country', array('class' => 'control-label')) !!}
        {!! Form::select('country_id', App\Country::pluck('nicename','id')->toArray(), old('country_id'),['class' => 'form-control j-select2','data-url'=> '/ajax/users-list','placeholder' => 'Select users...']) !!}
        @if ($errors->has('country_id'))
            <span class="help-block">
                                    {{ $errors->first('country_id') }}
                                </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        {!! Form::label('state', 'State', array('class' => 'control-label')) !!}
        {!! Form::text('state', old('state'), array('class' => 'form-control')) !!}
        @if ($errors->has('state'))
            <span class="help-block">
                                    {{ $errors->first('state') }}
                                </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
        {!! Form::label('city', 'City', array('class' => 'control-label')) !!}
        {!! Form::text('city', old('city'), array('class' => 'form-control')) !!}
        @if ($errors->has('city'))
            <span class="help-block">
                                    {{ $errors->first('city') }}
                                </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('church_name') ? ' has-error' : '' }}">
        {!! Form::label('church_name', 'Church Name', array('class' => 'control-label')) !!}
        {!! Form::text('church_name', old('church_name'), array('class' => 'form-control')) !!}
        @if ($errors->has('church_name'))
            <span class="help-block">
                                    {{ $errors->first('church_name') }}
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
@include('admin.partials.imageupload')