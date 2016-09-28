{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'article-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">
        {!! Form::label('category_id', 'Category (article will be assigned to specific category):') !!}
        {!! Form::select('category_id', $categories, $model->category_id ,['placeholder' => 'Select Category...']) !!}
        @if ($errors->has('category_id'))
            <span class="help-block">
                {{ $errors->first('category_id') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title') !!}
        @if ($errors->has('title'))
            <span class="help-block">
                {{ $errors->first('title') }}
            </span>
        @endif
    </div>
    <div class="form-group {{ $errors->has('text') ? ' has-error' : '' }}">
        {!! Form::label('text', 'Text:') !!}
        {!! Form::textarea('text',null,['id' => 'location-desc']) !!}
        @if ($errors->has('text'))
            <span class="help-block">
                {{ $errors->first('text') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::hidden('user_id', $user_id) !!}
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/articles/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}