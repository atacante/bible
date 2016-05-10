{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'journal-form', 'class' => '   ','role' => 'form','files' => true]) !!}
<div class="box-body">
    @if($model->verse)
    <div class="form-group">
        {!! Form::hidden('bible_version') !!}
        {!! Form::hidden('verse_id') !!}
        {!! Form::label('verse_num', 'Verse:') !!}
        <div>{!! ViewHelper::getVerseNum($model->verse).($model->bible_version?' ('.ViewHelper::getVersionName($model->bible_version).')':'') !!}</div>
    </div>
    @endif
    <div class="form-group {{ $errors->has('journal_text') ? ' has-error' : '' }}">
        {!! Form::label('journal_text', 'Description:') !!}
        {!! Form::textarea('journal_text',null,['id' => 'journal-text']) !!}
        @if ($errors->has('journal_text'))
            <span class="help-block">
                {{ $errors->first('journal_text') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/journal/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}