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
        <div class="item-footer">
            @role('user')
            <div class="add-comment">
                <div>
                    {!! Form::open(['method' => 'post','url' => '/blog/save-comment']) !!}
                    {!! Form::hidden('id',$article->id) !!}
                    <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}" style="margin: 0;">
                        {!! Form::textarea('text',Request::input('text'),['placeholder' => 'Write a comment...','class' => 'form-control','style' => '','rows' => 2]) !!}
                        @if ($errors->has('text'))
                            <span class="help-block">
                                {{ $errors->first('text') }}
                            </span>
                        @endif
                    </div>
                    {!! Form::token() !!}
                    {!! Form::button('Post',['type' => 'submit','class' => 'btn btn-primary','style' => 'padding: 2px 12px; margin-top:5px;']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            @endrole
            <div class="item-comments">
                <div class="comments-list">
                    @if($article->comments->count())
                        @foreach($article->comments as $comment)
                            <div style="margin-top: 5px;">
                                <div class="" style="position: absolute;">
                                    <img class="img-thumbnail" height="54" width="54" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$comment->user->id.'/thumbs/'.$comment->user->avatar !!}"/>
                                </div>
                                <div class="" style="margin-left: 60px; min-height: 54px;">
                                    {!! $comment->text !!}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/blog'),'Back to list', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
@stop
