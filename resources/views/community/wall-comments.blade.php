<div class="item-comments">
    <div class="comments-list j-comments-list">
        @include('community.wall-comment-items')
    </div>
    @role('user')
    <div class="add-comment c-wall-comment">
        <div class="c-area-group2">
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
            {!! Form::button('Post',['type' => 'submit','class' => 'btn4 btn4-cu1 j-save-comment']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    @endrole
</div>
