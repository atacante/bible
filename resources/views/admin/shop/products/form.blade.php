{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'product-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
        {!! Form::label('category_id', 'Category (product will be assigned to specific category):') !!}
        {!! Form::select('category_id', $categories, $model->category_id ,['placeholder' => 'Select Category...']) !!}
        @if ($errors->has('category_id'))
            <span class="help-block">
                {{ $errors->first('category_id') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
        {!! Form::label('name', 'Name:') !!}
        {!! Form::text('name') !!}
        @if ($errors->has('name'))
            <span class="help-block">
                {{ $errors->first('name') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
        {!! Form::label('image', 'Images:') !!}
        <div class="clearfix">
            <div id="img-thumb-preview" class="edit-images-thumbs product-images pull-left">
                @if($model->images)
                    @foreach($model->images as $image)
                        <div class="img-thumb pull-left">
                            <img height="100" width="100" src="{!! Config::get('app.productImages').'thumbs/'.$image->image !!}" />
                            <i data-filename="{!! $image->image !!}" class="j-remove-image fa fa-times-circle fa-4x" style="position:absolute; top: 24px; left: 28px; color: #f4645f; cursor: pointer; opacity: 0;"></i>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="fallback pull-left"> <!-- this is the fallback if JS isn't working -->
                <input name="file[]" type="file" multiple />
            </div>
        </div>

        @if ($errors->has('image'))
            <span class="help-block">
                {{ $errors->first('image') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('short_description') ? ' has-error' : '' }}">
        {!! Form::label('short_description', 'Short Description:') !!}
        {!! Form::text('short_description') !!}
        @if ($errors->has('short_description'))
            <span class="help-block">
                {{ $errors->first('short_description') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('long_description') ? ' has-error' : '' }}">
        {!! Form::label('long_description', 'Long Description:') !!}
        {!! Form::textarea('long_description',null,['id' => 'product-desc']) !!}
        @if ($errors->has('long_description'))
            <span class="help-block">
                {{ $errors->first('long_description') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
        {!! Form::label('price', 'Price:') !!}
        {!! Form::text('price') !!}
        @if ($errors->has('price'))
            <span class="help-block">
                {{ $errors->first('price') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('external_link') ? ' has-error' : '' }}">
        {!! Form::label('external_link', 'External Link:') !!}
        {!! Form::text('external_link') !!}
        @if ($errors->has('external_link'))
            <span class="help-block">
                {{ $errors->first('external_link') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('homepage_position') ? ' has-error' : '' }}">
        {!! Form::label('homepage_position', 'Homepage Position:') !!}
        {!! Form::select('homepage_position', $model->getFreePositions(), $model->homepage_position ,['placeholder' => 'None']) !!}
        @if ($errors->has('homepage_position'))
            <span class="help-block">
                {{ $errors->first('homepage_position') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/shop-products/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}