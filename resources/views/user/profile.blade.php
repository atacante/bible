    @extends('layouts.app')

    @section('breadcrumbs', Breadcrumbs::render('userUpdate',$model))

    @section('content')
        <div class="panel panel-default">
            <div class="panel-heading">{{ $page_title or "Page Title" }}</div>
            <div class="panel-body">
                {!! Form::model($model, ['method' => 'put', 'class' => '','role' => 'form']) !!}
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
                    <div class="form-group {{ $errors->has('about_me') ? ' has-error' : '' }}">
                        {!! Form::label('about_me', 'About Me:') !!}
                        {!! Form::textarea('about_me') !!}
                        @if ($errors->has('about_me'))
                            <span class="help-block">
                            {{ $errors->first('about_me') }}
                        </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('plan_type') ? ' has-error' : '' }}">
                        {!! Form::label('plan_type', "Subscription plan:") !!}

                        <div>
                            <label class="radio-inline">
                                {!! Form::radio('plan_type', 'free', true) !!}
                                Free
                            </label>
                            <label class="radio-inline">
                                {!! Form::radio('plan_type', 'premium', false) !!}
                                Premium ({!! $model->isPremiumPaid()?'paid':"$".App\User::PLAN_PREMIUM_COST !!})
                            </label>
                            @if ($errors->has('plan_type'))
                                <span class="help-block">
                                        {{ $errors->first('plan_type') }}
                                    </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {!! $model->plan_type == 'free'?'hidden':'' !!} {{ $errors->has('coupon_code') ? ' has-error' : '' }}">
                        {!! Form::label('coupon_code', 'Coupon Code:') !!}
                        {!! Form::text('coupon_code') !!}
                        @if ($errors->has('coupon_code'))
                            <span class="help-block">
                            {{ $errors->first('coupon_code') }}
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
        @include('partials.imageupload')
    @endsection