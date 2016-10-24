{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'people-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('people_name') ? ' has-error' : '' }}">
        {!! Form::label('people_name', 'Name:') !!}
        {!! Form::text('people_name') !!}
        @if ($errors->has('people_name'))
            <span class="help-block">
                        {{ $errors->first('people_name') }}
                    </span>
        @endif
    </div>
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! Form::hidden('associate_verses', 0) !!}
                {!! Form::checkbox('associate_verses', 1,true) !!}
                <span>Associate name with verses <i>(Will be associated with all the verses and lexicon phrases found by character name)</i></span>
            </label>
        </div>
    </div>
    <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
        {!! Form::label('image', 'Images:') !!}
        <div class="clearfix">
            <div id="img-thumb-preview" class="edit-images-thumbs people-images pull-left">
                @if($model->images)
                    @foreach($model->images as $image)
                        <div class="img-thumb pull-left">
                            <img height="100" width="100" src="{!! Config::get('app.peopleImages').'thumbs/'.$image->image !!}" />
                            <i data-filename="{!! $image->image !!}" class="j-remove-image fa fa-times-circle fa-4x" style="position:absolute; top: 24px; left: 28px; color: #f4645f; cursor: pointer; opacity: 0;"></i>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="fallback pull-left"> <!-- this is the fallback if JS isn't working -->
                <input name="file[]" type="file" multiple />
            </div>
        </div>
        {{--<div id="my-dropzone" class="dropzone clearfix">--}}
            {{--<div id="img-thumb-preview" class="pull-left">--}}
            {{--</div>--}}
            {{--<i class="j-select-image pull-left fa fa-plus-circle fa-4x" style="color: #367fa9; padding: 46px; cursor: pointer;"></i>--}}
        {{--</div>--}}

        @if ($errors->has('image'))
            <span class="help-block">
                        {{ $errors->first('image') }}
                    </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('people_description') ? ' has-error' : '' }}">
        {!! Form::label('people_description', 'Description:') !!}
        {!! Form::textarea('people_description',null,['id' => 'location-desc']) !!}
        @if ($errors->has('people_description'))
            <span class="help-block">
                {{ $errors->first('people_description') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/peoples/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}
{{--@include('admin.partials.imageupload')--}}