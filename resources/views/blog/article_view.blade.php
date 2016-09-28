@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row wall">
        <div class="col-xs-3">
            @include('blog.categories')
        </div>
        <div class="col-xs-9">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center">{!! $article->title !!}</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12" style="line-height: 30px;">
                    <div class="clearfix location-item-details">
                        <div>
                            {!! $article->text !!}
                        </div>
                    </div>
                </div>
            </div>
            @include('blog.comments')
            <div class="row">
                <div class="text-center">
                    {!! Html::link((($url = Session::get('backUrl'))?$url:'/blog'),'Back to list', ['class'=>'btn btn-primary']) !!}
                </div>
            </div>
        </div>
    </div>
@stop
