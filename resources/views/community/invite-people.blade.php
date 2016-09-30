@extends('layouts.app')

@section('content')
    <div class="row friends-list j-friends-list">
        <div class="col-xs-3">
            @include('community.menu')
        </div>
        <div class="col-xs-9" style="">
            <div class="c-white-content">
                <div class="inner-pad2">
                    <h4 class="h4-kit" style="font-size: 18px; line-height: 22px">
                        <i class="bs-invite cu-add"></i>
                        Invite People
                    </h4>
                    {!! Form::open(['method' => 'post', 'id' => 'invite-form', 'class' => 'j-report-form','role' => 'form','files' => true]) !!}
                    <div class="row form-group mt3 {{ $errors->has('emails') ? ' has-error' : '' }}">
                        {!! Form::label('emails', 'Emails of people to invite', ["class"=>"col-xs-3 mt15"]) !!}
                        <div class="col-xs-9">
                            {!! Form::select('emails[]', [], [],['placeholder' => '','multiple' => true,'class' => 'clear-fix j-invite-emails', 'style' => 'max-width: 100%']) !!}
                            @if ($errors->has('emails'))
                                <span class="help-block">
                                    {{ $errors->first('emails') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row form-group {{ $errors->has('invite_text') ? ' has-error' : '' }}">
                        {!! Form::label('invite_text', 'Invite text', ["class"=>"col-xs-3 mt15"]) !!}
                        <div class="col-xs-9">
                            {!! Form::textarea('invite_text',$content['invite_text'],['placeholder' => '','multiple' => true,'class' => 'clear-fix ckeditor', 'style' => '']) !!}
                            @if ($errors->has('invite_text'))
                                <span class="help-block">
                                    {{ $errors->first('invite_text') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row form-group">
                        {!! Form::label('placeholders', 'Placeholders', ["class"=>"col-xs-3 mt15"]) !!}
                        <div class="col-xs-9 urls-text">
                            {invite_url} - <span class="iu-text">{!! $content['invite_url'] !!}</span>
                                <br />
                            {invite_link} - <span class="il-text">{!! $content['invite_link'] !!}</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-xs-9 col-xs-offset-3">
                            {!! Form::button('Send', ['type'=>'submit','class'=>'btn2-kit cu-btn-pad1 mr7 mt16 j-send-report']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection