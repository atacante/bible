@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row wall">
        <div class="col-md-3">
            @include('blog.categories')
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="c-white-content c-blog">
                        <div class="related-item">
                            <div class="clearfix">
                                <div class="item-header mb5">
                                    <div class="cu-date1">&nbsp; {!! $article->humanLastUpdate() !!}</div>
                                    <h3 class="h3-sub-kit pull-left">{{$article->title}}</h3>
                                </div>
                                <div class="item-body wall-text2">{!! $article->text !!}</div>
                                <span class="pull-right">Posted by <b>{!! $article->author_name?$article->author_name:$article->user->name !!}</b></span>
                            </div>
                            @include('blog.comments')
                        </div>
                    </div>
                </div>
            </div>

            <div class="row m-btn">
                <div class="col-xs-12">
                    <div class="text-center">
                        {!! Html::link((($url = Session::get('backUrl'))?$url:'/blog'),'Back to list', ['class'=>'btn1 pull-right']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
