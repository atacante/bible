{{-- Web site Title --}}
@section('title')
    {!! $page->meta_title !!}
@stop

@section('meta_description')
    <meta name="description" content="{!! $page->meta_description !!}"/>
@stop

@section('meta_keywords')
    <meta name="keywords" content="{!! $page->meta_keywords !!}"/>
@stop

@section('meta_twitter')
@stop

@section('meta_fb')
@stop

@section('meta_google')
@stop