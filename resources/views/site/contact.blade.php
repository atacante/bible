@extends('layouts.app')

@section('content')
    <h2>Contact Us</h2>
    <div class="row">
        <div class="col-md-9">
            <p>We are here to answer any questions you may have about our experiences. Reach out to us and we'll respond as soon as we can.</p>
            <p>Even if there is something you have always wanted to experience and can't find it on BibleProject, let us know and we promise we'll do our best to find it for you and send you there.</p></p>
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
                <dl>
                    <dt>EMAIL</dt>
                    <dd><a href="mailto:{!! Config::get('app.contactEmail') !!}" title="Click to send us an email">{!! Config::get('app.contactEmail') !!}</a></dd>
                    <dt>PHONE</dt>
                    <dd><a href="tel:00000000000" title="Click to call us">+44 20 0000 0000</a></dd>
                    <dt>SKYPE</dt>
                    <dd><a href="skype:bibleproject?call" title="Click to call us on Skype">bibleproject</a></dd>
                    <dt>ON THE WEB</dt>
                    <dd class="social-links">
                        <a class="fb" href="http://www.facebook.com/bibleproject" title="Find us on Facebook">Facebook</a>
                        <br />
                        <a class="tt" href="http://twitter.com/bibleproject" title="Find us on Twitter">Twitter</a>
                    </dd>
                </dl>
            </aside>
        </div>
    </div>
@endsection
