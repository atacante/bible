@extends('layouts.app')

@include('partials.cms-meta')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                {!! $page->title !!}
            </h2>
        </div>
    </div>
    <div class="my1-row mb2">
        <div class="my1-col-md-8">
            <div class="c-reader-content2"></div>
            <div class="inner-pad1">
                {!! $page->text !!}
                {!! Form::open(['method' => 'post', 'class' => '','role' => 'form']) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                            {!! Form::label('name', 'Name:') !!}
                            {!! Form::text('name', Request::get('name'), ["class" => "input1"]) !!}
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
                            {!! Form::text('email', Request::get('email'), ["class" => "input1"]) !!}
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
                            {!! Form::textarea('text', Request::get('text'), ["class" => "input1", "style" => 'max-width:740px !important;']) !!}
                            @if ($errors->has('text'))
                                <span class="help-block">
                                    {{ $errors->first('text') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group  {{ $errors->has('g-recaptcha-response') ? 'has-error' : '' }}">
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
                    {!! Form::button('Send', ['type'=>'submit','class'=>'btn2-kit mt16 cu-btn-pad1 pull-right']) !!}
                </div>
                <div class="clearfix"></div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="my1-col-md-4">
            <div class="c-reader-content2"></div>
            <div class="inner-pad1">
                <aside id="contact-info" style="position: relative;">
                    {!! $page_aside->text !!}
                </aside>
            </div>
        </div>
    </div>
@endsection
