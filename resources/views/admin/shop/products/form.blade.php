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
        {!! Form::textarea('long_description',null,['id' => 'location-desc']) !!}
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
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/shop-products/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}