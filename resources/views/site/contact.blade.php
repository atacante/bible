@extends('layouts.app')

@include('partials.cms-meta')

@section('content')
    <h2>{!! $page->title !!}</h2>
    <div class="row">
        <div class="col-md-9">
            {!! $page->text !!}
            {!! Form::open(['method' => 'post', 'class' => '','role' => 'form','style' => 'margin: 20px 20px 20px 0;']) !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name') !!}
                        @if ($errors->has('name'))
                            <span class="help-block">
                                {{ $errors->first('name') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        {!! Form::label('email', 'Email:') !!}
                        {!! Form::text('email') !!}
                        @if ($errors->has('email'))
                            <span class="help-block">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                        {!! Form::label('text', 'Message:') !!}
                        {!! Form::textarea('text') !!}
                        @if ($errors->has('text'))
                            <span class="help-block">
                                {{ $errors->first('text') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group  {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
{{--                        {!! Form::label('', null, array('class' => '')) !!}--}}
                        {!! Captcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                    {{ $errors->first('g-recaptcha-response') }}
                                </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="box-footer">
                {!! Form::button('Send', ['type'=>'submit','class'=>'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="col-md-3" style="border-left: 1px solid #ccc; height: 500px;">
            <aside id="contact-info">
                {!! $page_aside->text !!}
            </aside>
        </div>
    </div>
@endsection
