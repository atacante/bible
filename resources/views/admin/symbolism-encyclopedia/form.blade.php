{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'symbolism-encyclopedia-form', 'class' => 'panel','role' => 'form','files' => true]) !!}
<div class="box-body">
    <div class="form-group {{ $errors->has('term_name') ? ' has-error' : '' }}">
        {!! Form::label('term_name', 'Name:') !!}
        {!! Form::text('term_name') !!}
        @if ($errors->has('term_name'))
            <span class="help-block">
                        {{ $errors->first('term_name') }}
                    </span>
        @endif
    </div>
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! Form::hidden('associate_lexicons', 0) !!}
                {!! Form::checkbox('associate_lexicons', 1,true) !!}
                <span>Associate term with lexicons <i>(Will be filled symbolism in all the lexicon phrases found by term name. <b>May take a lot of time.</b>)</i></span>
            </label>
        </div>
    </div>
    <div class="form-group {{ $errors->has('term_description') ? ' has-error' : '' }}">
        {!! Form::label('term_description', 'Description:') !!}
        {!! Form::textarea('term_description',null,['id' => 'term-desc','class'=>'ckeditor']) !!}
        @if ($errors->has('term_description'))
            <span class="help-block">
                {{ $errors->first('term_description') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/admin/symbolism-encyclopedia/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}
{{--@include('admin.partials.imageupload')--}}