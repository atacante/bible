{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'group-form', 'class' => '   ','role' => 'form','files' => true]) !!}

    <div class="form-group row mt3 {{ $errors->has('group_name') ? ' has-error' : '' }}">
        {!! Form::label('group_name', 'Name', ['class' => 'col-xs-2']) !!}
        <div class="col-xs-10">
            {!! Form::text('group_name',null,['class' => 'input1']) !!}
            @if ($errors->has('group_name'))
                <span class="help-block">
                    {{ $errors->first('group_name') }}
                </span>
            @endif
        </div>
    </div>

    {{--<div class="form-group row {{ $errors->has('group_email') ? ' has-error' : '' }}">
        {!! Form::label('group_email', 'Email', ['class' => 'col-xs-2']) !!}
        <div class="col-xs-10">
            {!! Form::text('group_email',null,['class' => 'input1']) !!}
            @if ($errors->has('group_email'))
                <span class="help-block">
                    {{ $errors->first('group_email') }}
                </span>
            @endif
        </div>
    </div>--}}

    <div class="form-group row {{ $errors->has('group_desc') ? ' has-error' : '' }}">
        {!! Form::label('group_desc', 'Description', ['class' => 'col-xs-2']) !!}
        <div class="col-xs-10">
            {!! Form::textarea('group_desc',null,['id' => 'group-text','rows' => 3, 'class' => 'input1']) !!}
            @if ($errors->has('group_desc'))
                <span class="help-block">
                    {{ $errors->first('group_desc') }}
                </span>
            @endif
        </div>
    </div>

    <div class="form-group row {{ $errors->has('group_image') ? ' has-error' : '' }}">
        {!! Form::label('group_image', 'Image', ['class' => 'col-xs-2']) !!}
        <div class="col-xs-10">
            <div id="img-thumb-preview" class="edit-images-thumbs group-images pull-left">
                @if($model->group_image)
                    <div class="img-thumb pull-left">
                        <img height="100" width="100" src="{!! Config::get('app.groupImages').$model->id.'/thumbs/'.$model->group_image !!}" />
                        <i data-filename="{!! $model->group_image !!}" class="j-remove-image fa fa-times-circle fa-4x" style="position:absolute; top: 24px; left: 28px; color: #f4645f; cursor: pointer; opacity: 0;"></i>
                    </div>
                @endif
            </div>
            <div class="fallback pull-left"> <!-- this is the fallback if JS isn't working -->
                <input name="file" type="file" />
            </div>

            @if ($errors->has('group_image'))
                <span class="help-block">
                {{ $errors->first('group_image') }}
            </span>
            @endif
        </div>
    </div>

    <div class="form-group row {{ $errors->has('access_level') ? ' has-error' : '' }}">
        {!! Form::label('access_level', "Accessibility", ['class' => 'col-xs-2']) !!}
        <div class="col-xs-10">
            <div class="radio" style="margin-top: 0">
                {!! Form::radio('access_level', App\Group::ACCESS_PUBLIC, true, ['id' => 'public','class' => 'cust-radio']) !!}
                <label  for="public" class="label-radio cu-label2">
                    <i class="fa bs-s-public cu-s-public" aria-hidden="true"></i>
                    Public
                    <div class="sub-text-radio">Anyone can see the group, its members and their posts.</div>
                </label>
            </div>
            <div class="radio">
                {!! Form::radio('access_level', App\Group::ACCESS_SECRET, false, ['id' => 'secret','class' => 'cust-radio']) !!}
                <label for="secret" class="label-radio cu-label2">
                    <i class="fa bs-s-onlyme cu-s-public" aria-hidden="true"></i>
                    Private
                    <div class="sub-text-radio">Anyone can find the group and see who's in it. Only members can see posts.</div>
                </label>
            </div>

            @if ($errors->has('access_level'))
                <span class="help-block">
                    {{ $errors->first('access_level') }}
                </span>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xs-offset-2 col-xs-10">
            {!! Form::button('Save', ['type'=>'submit','class'=>'btn2-kit cu-btn-pad1 mr7']) !!}
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/groups?type=my'),'Cancel', ['class'=>'btn4-kit cu-btn-pad1']) !!}
        </div>
    </div>
{!! Form::close() !!}