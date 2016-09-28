@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('lexiconItem',$lexiconCode,$lexiconName,($model->booksListEn->book_name.' '.$model->chapter_num.':'.$model->verse_num)))

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{!! $model->booksListEn->book_name.' '.$model->chapter_num.':'.$model->verse_num !!}</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        {{--<form role="form">--}}
        {!! Form::model($model, [/*'url' => ViewHelper::adminUrlSegment().'/lexicon/update/'.$lexiconCode.'/'.$model->id, */'method' => 'put', 'class' => 'panel','role' => 'form']) !!}
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
            {{--<div class="form-group {{ $errors->has('strong_1_word_def') ? ' has-error' : '' }}">
                {!! Form::label('strong_1_word_def', 'One word definition:') !!}
                {!! Form::text('strong_1_word_def') !!}
                @if ($errors->has('strong_1_word_def'))
                    <span class="help-block">
                        {{ $errors->first('strong_1_word_def') }}
                    </span>
                @endif
            </div>--}}
            <div class="form-group {{ $errors->has('definition') ? ' has-error' : '' }}">
                {!! Form::label('definition', 'Definition:') !!}
                {!! Form::text('definition') !!}
                @if ($errors->has('definition'))
                    <span class="help-block">
                        {{ $errors->first('definition') }}
                    </span>
                @endif
            </div>
            <div class="form-group {{ $errors->has('symbolism') ? ' has-error' : '' }}">
                {!! Form::label('symbolism', 'Symbolism:') !!}
                {!! Form::textarea('symbolism') !!}
                @if ($errors->has('symbolism'))
                    <span class="help-block">
                        {{ $errors->first('symbolism') }}
                    </span>
                @endif
            </div>
            <div class="form-group">
                {!! Form::label('lexicons', 'Save symbolism also for:') !!}
                @foreach($lexicons as $lexicon)
                    @if($lexicon['lexicon_code'] != $lexiconCode)
                    <div>
                        {!! Form::checkbox('lexicons[]', $lexicon['lexicon_code'], (!Request::has('lexicons') || in_array($lexicon['lexicon_code'],Request::get('lexicons',[])))) !!}
                        {!! $lexicon['lexicon_name'] !!} - <i>{!! $lexicon['phrase'] !!}</i>
                    </div>
                    @endif
                @endforeach
            </div>
            <div class="form-group">
                {!! Form::label('locations', 'Locations:') !!}
                {!! Form::select('locations[]', $locations, $model->locations->pluck('id')->toArray(), ['id' => 'j-select-locations','multiple' => 'multiple']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('peoples', 'People:') !!}
                {!! Form::select('peoples[]', $peoples, $model->peoples->pluck('id')->toArray(), ['id' => 'j-select-peoples','multiple' => 'multiple']) !!}
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