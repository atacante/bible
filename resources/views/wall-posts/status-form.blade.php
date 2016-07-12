<div class="related-item" style="background: #f9f9f9; padding: 5px 0;">
    <div class="item-body">
        {!! Form::model($status, ['method' => 'post','url' => '/wall-posts/post-status', 'id' => 'status-form', 'class' => '','role' => 'form','files' => true]) !!}
        {!! Form::hidden('wall_type',$wallType) !!}
        @if(isset($groupId))
        {!! Form::hidden('rel_id',$groupId) !!}
        @endif
        <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
            {!! Form::textarea('text',null,['id' => 'status-text','placeholder' => "What's on your mind?",'rows' => 3]) !!}
            @if ($errors->has('text'))
                <span class="help-block">
                    {{ $errors->first('text') }}
                </span>
            @endif
        </div>
        <div class="box-footer">
            {!! Form::select('access_level',
                [
                    App\WallPost::ACCESS_PUBLIC_ALL => 'Public',
                    App\WallPost::ACCESS_PUBLIC_FRIENDS => 'Only friends',
                    App\WallPost::ACCESS_PRIVATE => 'Only me',
                ],
                Request::input('access_level',App\WallPost::ACCESS_PUBLIC_ALL),['class' => 'form-control pull-right', 'style' => 'width: 125px;']) !!}
            <div class="pull-right" style="padding: 6px 10px;">Privacy</div>
            {!! Form::button('Post', ['type'=>'submit','class'=>'btn btn-primary']) !!}
        </div>
        {!! Form::close() !!}
    </div>
</div>