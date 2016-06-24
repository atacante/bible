{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'group-form', 'class' => '   ','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('group_name') ? ' has-error' : '' }}">
        {!! Form::label('group_name', 'Name:') !!}
        {!! Form::text('group_name',null,[]) !!}
        @if ($errors->has('group_name'))
            <span class="help-block">
                {{ $errors->first('group_name') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('group_email') ? ' has-error' : '' }}">
        {!! Form::label('group_email', 'Email:') !!}
        {!! Form::text('group_email',null,[]) !!}
        @if ($errors->has('group_email'))
            <span class="help-block">
                {{ $errors->first('group_email') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('group_desc') ? ' has-error' : '' }}">
        {!! Form::label('group_desc', 'Description:') !!}
        {!! Form::textarea('group_desc',null,['id' => 'group-text','rows' => 3]) !!}
        @if ($errors->has('group_desc'))
            <span class="help-block">
                {{ $errors->first('group_desc') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('access_level') ? ' has-error' : '' }}">
        {!! Form::label('access_level', "Accessibility:") !!}
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Group::ACCESS_PUBLIC, true) !!}
                <i class="fa fa-globe" aria-hidden="true"></i>
                Public <br />
                <span style="color: #ccc;">Anyone can see the group, its members and their posts.</span>
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Group::ACCESS_SECRET, false) !!}
                <i class="fa fa-lock" aria-hidden="true"></i>
                Secret<br />
                <span style="color: #ccc;">Anyone can find the group and see who's in it. Only members can see posts.</span>
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Group::ACCESS_PUBLIC_MEMBERS, false) !!}
                <i class="fa fa-users" aria-hidden="true"></i>
                Closed<br />
                <span style="color: #ccc;">Only members can find the group and see posts.</span>
            </label>
        </div>
        @if ($errors->has('access_level'))
            <span class="help-block">
                {{ $errors->first('access_level') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/groups?type=my'),'Cancel', ['class'=>'btn btn-danger']) !!}
</div>
{!! Form::close() !!}