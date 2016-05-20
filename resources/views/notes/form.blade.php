{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'note-form', 'class' => '   ','role' => 'form','files' => true]) !!}
<div class="box-body">
    @if($model->verse)
        <div class="form-group">
            {!! Form::hidden('bible_version') !!}
            {!! Form::hidden('verse_id') !!}
            {!! Form::hidden('highlighted_text') !!}
            <div style="margin-bottom:10px;">
                {!! Html::link('/reader/verse?'.http_build_query(
                    [
                        'version' => $model->bible_version,
                        'book' => $model->verse->book_id,
                        'chapter' => $model->verse->chapter_num,
                        'verse' => $model->verse->verse_num
                    ]
                    ),ViewHelper::getVerseNum($model->verse).($model->bible_version?' ('.ViewHelper::getVersionName($model->bible_version).')':''), ['class'=>'label label-success','style' => '']) !!}
            </div>
            <div>
                {!! Form::label('verse_num', 'Verse:') !!}
                <i>{!! $model->highlighted_text !!}</i>
            </div>
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
    <div class="form-group {{ $errors->has('journal_text') ? ' has-error' : '' }}">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" class="collapsed" data-toggle="collapse" data-parent="#none"
                           href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {!! ($model->exists && $model->journal)?'Related Journal Entry':'Add a Journal Entry' !!}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne"
                     class="panel-collapse collapse {!! (($model->exists && $model->journal) || (old() && old()['journal_text']))?'in':'' !!}"
                     role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::textarea('journal_text',($model->exists && $model->journal)?$model->journal->journal_text:null,['id' => 'journal-text']) !!}
                        @if ($errors->has('journal_text'))
                            <span class="help-block">
                                {{ $errors->first('journal_text') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('prayer_text') ? ' has-error' : '' }}">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a role="button" class="collapsed" data-toggle="collapse" data-parent="#none"
                           href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {!! ($model->exists && $model->prayer)?'Related Prayer':'Add Prayer' !!}
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo"
                     class="panel-collapse collapse {!! (($model->exists && $model->prayer) || (old() && old()['prayer_text']))?'in':'' !!}"
                     role="tabpane2" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        {!! Form::textarea('prayer_text',($model->exists && $model->prayer)?$model->prayer->prayer_text:null,['id' => 'prayer-text']) !!}
                        @if ($errors->has('prayer_text'))
                            <span class="help-block">
                                {{ $errors->first('prayer_text') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/notes/list/'),'Cancel', ['class'=>'btn btn-danger']) !!}
</div>
{!! Form::close() !!}