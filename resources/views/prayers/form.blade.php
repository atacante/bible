{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'prayer-form', 'class' => '   ','role' => 'form','files' => true]) !!}
<div class="box-body">
    @if($model->verse)
        <div class="form-group">
            {!! Form::hidden('full_screen',0) !!}
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
    @else
        {!! Form::hidden('rel',Request::get('rel')) !!}
    @endif
    <div class="form-group {{ $errors->has('prayer_text') ? ' has-error' : '' }}">
        {!! Form::label('prayer_text', 'Description:') !!}
        {!! Form::textarea('prayer_text',null,['id' => 'prayer-text']) !!}
        @if ($errors->has('prayer_text'))
            <span class="help-block">
                {{ $errors->first('prayer_text') }}
            </span>
        @endif
    </div>
    @if($model->exists)
    <div class="form-group">
        <div class="checkbox">
            <label>
                {!! Form::hidden('answered', 0) !!}
                {!! Form::checkbox('answered', 1,true) !!}
                <span>Answered</span>
            </label>
        </div>
    </div>
    @endif
    <div class="form-group">
        {!! Form::label('tags', 'Tags:') !!}
        {!! Form::select('tags[]', $model->availableTags(), $model->tags->pluck('id')->toArray(),['placeholder' => '','multiple' => true,'class' => 'clear-fix j-tags', 'style' => '']) !!}
    </div>
    <div class="form-group {{ $errors->has('access_level') ? ' has-error' : '' }}">
        {!! Form::label('access_level', "Accessibility:") !!}
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Note::ACCESS_PRIVATE, true) !!}
                <i class="fa fa-lock" aria-hidden="true"></i>
                Private
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('access_level', App\Note::ACCESS_PUBLIC_ALL, false) !!}
                <i class="fa fa-globe" aria-hidden="true"></i>
                Public - share with everyone
            </label>
        </div>
        <div class="radio">
            <label>
                {!! Form::radio('access_level', ViewHelper::checkEntryAccess($model), ViewHelper::checkEntryAccess($model)) !!}
                <i class="fa fa-users" aria-hidden="true"></i>
                Public - share with Groups I am member of
                <div class="radio j-all-groups {!! ViewHelper::checkEntryAccess($model)?'':'disabled' !!}">
                    <label>
                        {!! Form::radio('share_for_groups', App\Note::ACCESS_PUBLIC_GROUPS, $model->access_level == App\Note::ACCESS_PUBLIC_GROUPS,['class'=>'',ViewHelper::checkEntryAccess($model)?'':'disabled']) !!}
                        All my groups
                    </label>
                </div>
                <div class="radio j-specific-groups {!! ViewHelper::checkEntryAccess($model)?'':'disabled' !!}">
                    <label>
                        {!! Form::radio('share_for_groups', App\Note::ACCESS_SPECIFIC_GROUPS, $model->access_level == App\Note::ACCESS_SPECIFIC_GROUPS,['class'=>'',ViewHelper::checkEntryAccess($model)?'':'disabled']) !!}
                        Selected groups
                    </label>
                </div>
                {!! Form::select('groups[]', $content['groups'], $model->groupsShares->pluck('id')->toArray(),['placeholder' => 'Select groups...','multiple' => true,'class' => 'clear-fix j-groups j-select2', 'style' => '',$model->access_level == App\Note::ACCESS_SPECIFIC_GROUPS?'':'disabled']) !!}
            </label>
        </div>
        @if ($errors->has('access_level'))
            <span class="help-block">
                {{ $errors->first('access_level') }}
            </span>
        @endif
    </div>
    <div class="form-group {!! Request::get('extraFields')?'':'hidden' !!} {{ $errors->has('note_text') ? ' has-error' : '' }}">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" class="collapsed" data-toggle="collapse" data-parent="#none"
                           href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            {!! ($model->exists && $model->note)?'Related Note':'Add a Note' !!}
                        </a>
                    </h4>
                </div>
                <div id="collapseOne"
                     class="panel-collapse collapse {!! (($model->exists && $model->note) || (old() && old()['note_text']))?'in':'' !!}"
                     role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        {!! Form::textarea('note_text',($model->exists && $model->note)?$model->note->note_text:null,['id' => 'note-text']) !!}
                        @if ($errors->has('note_text'))
                            <span class="help-block">
                                {{ $errors->first('note_text') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group {!! Request::get('extraFields')?'':'hidden' !!} {{ $errors->has('journal_text') ? ' has-error' : '' }}">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a role="button" class="collapsed" data-toggle="collapse" data-parent="#none"
                           href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            {!! ($model->exists && $model->journal)?'Related Journal Entry':'Add Journal Entry' !!}
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo"
                     class="panel-collapse collapse {!! (($model->exists && $model->journal) || (old() && old()['journal_text']))?'in':'' !!}"
                     role="tabpane2" aria-labelledby="headingTwo">
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
</div>
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn btn-primary']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/journal/list/'),'Cancel', ['class'=>'btn btn-danger']) !!}
</div>
{!! Form::close() !!}