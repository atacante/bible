@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">{!! $article->title !!}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="line-height: 30px;">
            <div class="clearfix location-item-details">
{{--                <div class="pull-left" style="margin-right: 10px;">
                    <img src="{!! Config::get('app.blogImages').$article->img !!}"
                         class="img-thumbnail" alt="" style="cursor: pointer;">
                </div>--}}
                <div>
                    {!! $article->text !!}
                </div>
            </div>
        </div>
    </div>
    <div class="related-item">
        @include('blog.comments')
    </div>
    <div class="row">
        <div class="text-center">
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/blog'),'Back to list', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
@stop
