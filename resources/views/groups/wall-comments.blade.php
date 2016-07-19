<div class="item-comments">
    @role('user')
    <div class="add-comment">
        {{--<div class="" style="position: absolute;">--}}
            {{--<img class="img-thumbnail" height="54" width="54" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar !!}" />--}}
        {{--</div>--}}
        <div class="" style="/*margin-left: 60px;*/">
            {!! Form::open(['method' => 'post','url' => '/'.ViewHelper::getEntryControllerName($item->type).'/save-comment','class' => 'j-comment-form']) !!}
            {!! Form::hidden('id',$item->id) !!}
            <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}" style="margin: 0;">
                {!! Form::textarea('text',Request::input('text'),['placeholder' => 'Write a comment...','class' => 'form-control','style' => '','rows' => 2]) !!}
                @if ($errors->has('text'))
                    <span class="help-block">
                {{ $errors->first('text') }}
            </span>
                @endif
            </div>
            {!! Form::token() !!}
            {!! Form::button('Post',['type' => 'submit','class' => 'btn btn-primary j-save-comment','style' => 'padding: 2px 12px; margin-top:5px;']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    @endrole
    {{--@if($item->comments->count())
    <div>Comments</div>
    @endif--}}
    <div class="comments-list j-comments-list">
        @include('community.wall-comment-items')
    </div>
</div>
