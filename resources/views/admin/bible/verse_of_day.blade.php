@extends('admin.layouts.layout')

{{--
@section('breadcrumbs', Breadcrumbs::render('admin','verse of the day'))
--}}

@section('content')
    <div class="box box-primary">
        <div class="box-body j-admin-verses-filters">
            {!! Form::open(['method' => 'post','url' => '/admin/bible/verseday','files' => true]) !!}
            <div class="form-group">
                {!! Form::select('book', [0 => 'All Books']+$filters['books'], Request::input('book'),['class' => 'form-control', 'style' => 'width: 170px;']) !!}
            </div>
            <div class="form-group">
                {!! Form::select('chapter',array_merge([0 => 'All Chapters'],(Request::input('book') == 0?[]:$filters['chapters'])), Request::input('chapter'),['class' => 'form-control',(Request::input('book') == 0?'disabled':''), 'style' => 'width: 170px;']) !!}
            </div>
            <div class="form-group">
                {!! Form::select('verse',array_merge([0 => 'All Verses'],(Request::input('chapter') == 0?[]:$filters['verses'])), Request::input('verse'),['class' => 'form-control', (Request::input('chapter') == 0?'disabled':''), 'style' => 'width: 115px;']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('Starts From') !!}
                <div>
                    <div class="radio-inline mt15">
                        {!! Form::radio('tomorrow', '0', true, ["class" => "cust-radio", 'id' => 'tomorrow-off']) !!}
                        <label for="tomorrow-off" class="label-radio cu-label">Today</label>
                    </div>
                    <div class="radio-inline mt15">
                        {!! Form::radio('tomorrow', '1', false, ["class" => "cust-radio", 'id' => 'tomorrow-on']) !!}
                        <label for="tomorrow-on" class="label-radio cu-label">Tomorrow</label>
                    </div>
                </div>
            </div>
            {!! Form::token() !!}

            <div class="form-group">
                {!! Form::label('image', 'Verse Image (desktop):') !!}
                <div class="clearfix">
                    <div id="img-thumb-preview" class="edit-images-thumbs product-images">
                        @if($verseOfDay->image)
                            <div class="img-thumb">
                                <img height="100" width="100" src="{!! Config::get('app.verseOfDayImages').'thumbs/'.$verseOfDay->image !!}" />
                            </div>
                        @endif
                    </div>
                    <div class="fallback"> <!-- this is the fallback if JS isn't working -->
                        <input name="image" type="file" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('image', 'Verse Image (mobile):') !!}
                <div class="clearfix">
                    <div id="img-thumb-preview" class="edit-images-thumbs product-images">
                        @if($verseOfDay->image_mobile)
                            <div class="img-thumb">
                                <img height="100" width="100" src="{!! Config::get('app.verseOfDayImages').'thumbs/'.$verseOfDay->image_mobile !!}" />
                            </div>
                        @endif
                    </div>
                    <div class="fallback"> <!-- this is the fallback if JS isn't working -->
                        <input name="image_mobile" type="file" />
                    </div>
                </div>
            </div>
            {!! Form::button('Set',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="c-center-content2 mt8">
                <div class="col-left1 h-ill5">
                    {{--<div class="luke">LUKE 6:37</div>--}}
                </div>
                <div class="col-right1">
                    <p class="p-2">
                        {!! $verseOfDay->verse->verse_text !!}
                    </p>
                    <br />
                    <p class="p-2">
                        <span class="ital">
                            <a href="{!! url('reader/verse?'.http_build_query(
                                [
                                    'book' => $verseOfDay->verse->book_id,
                                    'chapter' => $verseOfDay->verse->chapter_num,
                                    'verse' => $verseOfDay->verse->verse_num
                                ]),[],false) !!}" class="" style="color: #7b828a;">
                                {!! strtoupper($verseOfDay->verse->booksListEn->book_name).' '.$verseOfDay->verse->chapter_num.':'.$verseOfDay->verse->verse_num !!}
                            </a>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection