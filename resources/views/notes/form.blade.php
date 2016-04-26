{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'note-form', 'class' => '   ','role' => 'form','files' => true]) !!}
<div class="box-body">
    @if($model->verse)
    <div class="form-group">
        {!! Form::label('verse_num', 'Verse:') !!}
        <div>{!! ViewHelper::getVerseNum($model->verse) !!}</div>
    </div>
    @endif
    <div class="form-group {{ $errors->has('note_text') ? ' has-error' : '' }}">
        {!! Form::label('note_text', 'Description:') !!}
        {!! Form::textarea('note_text',null,['id' => 'note-text']) !!}
        @if ($errors->has('note_text'))
            <span class="help-block">
                {{ $errors->first('note_text') }}
            </span>
        @endif
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/notes/list/'),'Cancel', ['class'=>'btn btn-default']) !!}
</div>
{!! Form::close() !!}