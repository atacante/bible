{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'location-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('location_name') ? ' has-error' : '' }}">
        {!! Form::label('location_name', 'Name:') !!}
        {!! Form::text('location_name') !!}
        @if ($errors->has('location_name'))
            <span class="help-block">
                        {{ $errors->first('location_name') }}
                    </span>
        @endif
    </div>
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! Form::hidden('associate_verses', 0) !!}
                {!! Form::checkbox('associate_verses', 1,true) !!}
                <span>Associate name with verses <i>(Will be associated with all the verses and lexicon phrases found by location name)</i></span>
            </label>
        </div>
    </div>
    <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
        {!! Form::label('image', 'Images:') !!}
        <div class="clearfix">
            <div id="img-thumb-preview" class="edit-images-thumbs location-images pull-left">
                @if($model->images)
                    @foreach($model->images as $image)
                        <div class="img-thumb pull-left">
                            <img height="100" width="100" src="{!! Config::get('app.locationImages').'thumbs/'.$image->image !!}" />
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
    <div class="form-group {{ $errors->has('location_description') ? ' has-error' : '' }}">
        {!! Form::label('location_description', 'Description:') !!}
        {!! Form::textarea('location_description',null,['id' => 'location-desc']) !!}
        @if ($errors->has('location_description'))
            <span class="help-block">
                {{ $errors->first('location_description') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('g_map') ? ' has-error' : '' }}">
        {!! Form::label('g_map', 'Map Embed Code:') !!}
        {!! Form::textarea('g_map',null,['rows' => 3,'placeholder' => 'Example: <iframe src="https://www.google.com/maps/embed?pb=PARAMS" frameborder="0" style="border:0" allowfullscreen></iframe>']) !!}
        @if ($errors->has('g_map'))
            <span class="help-block">
                {{ $errors->first('g_map') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/location/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}
@include('admin.partials.imageupload')