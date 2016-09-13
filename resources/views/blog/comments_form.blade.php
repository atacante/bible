@if(Auth::check() && Auth::user()->isPremium())
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
@else
    <div style="margin: 10px 0;">Only premium users can leave comments</div>
@endif