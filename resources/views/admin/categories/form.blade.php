{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'category-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
        {!! Form::label('title', 'Title:') !!}
        {!! Form::text('title') !!}
        @if ($errors->has('title'))
            <span class="help-block">
                {{ $errors->first('title') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/categories/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}