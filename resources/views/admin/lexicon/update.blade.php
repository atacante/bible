@extends('admin.layouts.layout')

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{!! $model->booksListEn->book_name.' '.$model->chapter_num.':'.$model->verse_num !!}</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{--<form role="form">--}}
        {!! Form::model($model, [/*'url' => '/admin/lexicon/update/'.$lexiconCode.'/'.$model->id, */'method' => 'put', 'class' => 'panel','role' => 'form']) !!}
        <div class="box-body">
            <div class="form-group {{ $errors->has('verse_part') ? ' has-error' : '' }}">
                {!! Form::label('verse_part', 'Verse Text:') !!}
                {!! Form::text('verse_part') !!}
                @if ($errors->has('verse_part'))
                    <span class="help-block">
                        {{ $errors->first('verse_part') }}
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('strong_num') ? ' has-error' : '' }}">
                {!! Form::label('strong_num', 'Strong\'s:') !!}
                {!! Form::text('strong_num') !!}
                @if ($errors->has('strong_num'))
                    <span class="help-block">
                        {{ $errors->first('strong_num') }}
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('transliteration') ? ' has-error' : '' }}">
                {!! Form::label('transliteration', 'Transliteration:') !!}
                {!! Form::text('transliteration') !!}
                @if ($errors->has('transliteration'))
                    <span class="help-block">
                        {{ $errors->first('transliteration') }}
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('strong_1_word_def') ? ' has-error' : '' }}">
                {!! Form::label('strong_1_word_def', 'One word definition:') !!}
                {!! Form::text('strong_1_word_def') !!}
                @if ($errors->has('strong_1_word_def'))
                    <span class="help-block">
                        {{ $errors->first('strong_1_word_def') }}
                    </span>
                @endif
            </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/lexicon/view/'.$lexiconCode),'Cancel', ['class'=>'btn btn-default']) !!}
        </div>
        {!! Form::close() !!}
    </div>
@endsection