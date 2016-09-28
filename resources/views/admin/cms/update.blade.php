@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('cmsUpdate'))

@section('content')
    <div class="box box-primary">
        {!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'cms-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
        <div class="box-body">
            <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                {!! Form::label('title', 'Title:') !!}
                {!! Form::text('title') !!}
                @if ($errors->has('title'))
                    <span class="help-block">
                {{ $errors->first('title') }}
            </span>
                @endif
            </div>
            @if($model->exists && $model->content_type == App\CmsPage::CONTENT_PAGE)
            <div class="form-group {{ $errors->has('meta_title') ? ' has-error' : '' }}">
                {!! Form::label('meta_title', 'Meta Title:') !!}
                {!! Form::text('meta_title') !!}
                @if ($errors->has('meta_title'))
                    <span class="help-block">
                {{ $errors->first('meta_title') }}
            </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('meta_description') ? ' has-error' : '' }}">
                {!! Form::label('meta_description', 'Meta Description:') !!}
                {!! Form::text('meta_description') !!}
                @if ($errors->has('meta_description'))
                    <span class="help-block">
                {{ $errors->first('meta_description') }}
            </span>
                @endif
            </div>

            <div class="form-group {{ $errors->has('meta_keywords') ? ' has-error' : '' }}">
                {!! Form::label('meta_keywords', 'Meta Keywords:') !!}
                {!! Form::text('meta_keywords') !!}
                @if ($errors->has('meta_keywords'))
                    <span class="help-block">
                {{ $errors->first('meta_keywords') }}
            </span>
                @endif
            </div>
            @endif
            <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
                {!! Form::label('text', 'Text:') !!}
                {!! Form::textarea('text',null,['class' => ($model->exists && $model->content_type == App\CmsPage::CONTENT_PAGE)?'ck-editor':'']) !!}
                @if ($errors->has('text'))
                    <span class="help-block">
                {{ $errors->first('text') }}
            </span>
                @endif
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {{--{!! Form::hidden('user_id', $user_id) !!}--}}
            {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/cms/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@endsection