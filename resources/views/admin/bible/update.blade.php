@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('verse',$versionCode,$versionName,($model->booksListEn->book_name.' '.$model->chapter_num.':'.$model->verse_num )))

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{!! $model->booksListEn->book_name.' '.$model->chapter_num.':'.$model->verse_num !!}</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{--<form role="form">--}}
        {!! Form::model($model, ['method' => 'put', 'class' => 'panel','role' => 'form']) !!}
        <div class="box-body">
            <div class="form-group {{ $errors->has('verse_text') ? ' has-error' : '' }}">
                {!! Form::label('verse_text', 'Verse Text:') !!}
                {!! Form::textarea('verse_text') !!}
                @if ($errors->has('verse_text'))
                    <span class="help-block">
                        {{ $errors->first('verse_text') }}
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('locations', 'Locations:') !!}
                {!! Form::select('locations[]', $locations, $model->locations->pluck('id')->toArray(), ['id' => 'j-select-locations','multiple' => 'multiple']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('peoples', 'Peoples:') !!}
                {!! Form::select('peoples[]', $peoples, $model->peoples->pluck('id')->toArray(), ['id' => 'j-select-peoples','multiple' => 'multiple']) !!}
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
            {!! Html::link((($url = Session::get('backUrl'))?$url:ViewHelper::adminUrlSegment().'/bible/verses/'.$versionCode),'Cancel', ['class'=>'btn btn-default']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@endsection