@extends('layouts.app')

@section('content')
    {{--<h3 class="text-center">Find Friends</h3>--}}
    <div class="row friends-list j-friends-list">
        <div class="col-md-2">
            @include('community.menu')
        </div>
        <div class="col-md-10" style="">
            {!! Form::open(['method' => 'post', 'id' => 'invite-form', 'class' => 'j-report-form','role' => 'form','files' => true]) !!}
            <div class="form-group {{ $errors->has('emails') ? ' has-error' : '' }}">
                {!! Form::label('emails', 'Emails of people to invite:') !!}
                {!! Form::select('emails[]', [], [],['placeholder' => '','multiple' => true,'class' => 'clear-fix j-invite-emails', 'style' => '']) !!}
                @if ($errors->has('emails'))
                    <span class="help-block">
                        {{ $errors->first('emails') }}
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('invite_text') ? ' has-error' : '' }}">
                {!! Form::label('invite_text', 'Invite text:') !!}
                {!! Form::textarea('invite_text',$content['invite_text'],['placeholder' => '','multiple' => true,'class' => 'clear-fix ckeditor', 'style' => '']) !!}
                @if ($errors->has('invite_text'))
                    <span class="help-block">
                        {{ $errors->first('invite_text') }}
                    </span>
                @endif
                <div style="margin-top: 10px;">
                    <strong>Placeholders:</strong><br />
                    {invite_url} - {!! $content['invite_url'] !!}<br />
                    {invite_link} - {!! $content['invite_link'] !!}
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
                {!! Form::button('Send', ['type'=>'submit','class'=>'btn btn-primary j-send-report']) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection