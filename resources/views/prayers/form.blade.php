{!! Form::model($model, ['method' => ($model->exists?'put':'post'), 'id' => 'prayer-form', 'class' => 'entry-form','role' => 'form','files' => true]) !!}
<div class="box-body">
    @if($model->verse)
        <div class="form-group form-verse-text">
            {!! Form::hidden('full_screen',0) !!}
            {!! Form::hidden('bible_version') !!}
            {!! Form::hidden('verse_id') !!}
            {!! Form::hidden('highlighted_text') !!}
            <span class="form-verse-quotes">“</span>
            <div style="margin-bottom:10px;">
                {!! Html::link('/reader/verse?'.http_build_query(
                    [
                        'version' => $model->bible_version,
                        'book' => $model->verse->book_id,
                        'chapter' => $model->verse->chapter_num,
                        'verse' => $model->verse->verse_num
                    ]
                    ),ViewHelper::getVerseNum($model->verse).($model->bible_version?' ('.ViewHelper::getVersionName($model->bible_version).')':''), ['class'=>'form-verse-num','style' => '']) !!}
            </div>
            <div class="form-highlighted-text">
                <i>{!! $model->highlighted_text !!}</i>
            </div>
        </div>
    @else
        {!! Form::hidden('rel',Request::get('rel')) !!}
    @endif
    <div class="form-group {{ $errors->has('prayer_text') ? ' has-error' : '' }}">
        {!! Form::label('prayer_text', 'Description:',['class' => 'text']) !!}
        {!! Form::textarea('prayer_text',null,['id' => 'prayer-text']) !!}
        @if ($errors->has('prayer_text'))
            <span class="help-block">
                {{ $errors->first('prayer_text') }}
            </span>
        @endif
    </div>
    {{--@if($model->exists)--}}
    <div class="form-group">
        <div class="checkbox">
            {!! Form::hidden('answered', 0) !!}
            {!! Form::checkbox('answered', 1,$model->answered,['id' => 'answered','class' => 'cust-radio']) !!}
            <label class="label-checkbox" for="answered">Answered</label>
        </div>
    </div>
    {{--@endif--}}

    <div class="form-group">
        {!! Form::label('tags', 'Tags:') !!}
        {!! Form::select('tags[]', $model->availableTags(), $model->tags->pluck('id')->toArray(),['placeholder' => '','multiple' => true,'class' => 'clear-fix j-tags', 'style' => '']) !!}
    </div>

    <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
        {!! Form::label('image', 'Images:') !!}
        <div class="clearfix">
            <div id="img-thumb-preview" class="edit-images-thumbs prayers-images pull-left">
                @if($model->images)
                    @foreach($model->images as $image)
                        <div class="img-thumb pull-left">
                            <img height="100" width="100" src="{!! Config::get('app.prayersImages').$model->id.'/thumbs/'.$image->image !!}" />
                            <i data-url="/prayers/delete-image" data-filename="{!! $image->image !!}" class="j-remove-image fa fa-times-circle fa-4x" style="position:absolute; top: 24px; left: 28px; color: #f4645f; cursor: pointer; opacity: 0;"></i>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="fallback pull-left"> <!-- this is the fallback if JS isn't working -->
                <input title="{!! Auth::check() && Auth::user()->isPremium()?'':'Premium Feature' !!}" name="file[]" type="file" {!! Auth::check() && Auth::user()->isPremium()?'':'disabled' !!} multiple />
            </div>
        </div>
        @if ($errors->has('image'))
            <span class="help-block">
                {{ $errors->first('image') }}
            </span>
        @endif
    </div>

    <div class="form-group access-level j-access-level {{ $errors->has('access_level') ? ' has-error' : '' }}">
        {!! Form::label('access_level', "Accessibility:") !!}
        <div class="radio">
            {!! Form::radio('access_level', App\Note::ACCESS_PRIVATE, true,['id' => 'private','class' => 'cust-radio']) !!}
            <label for="private" class="label-radio cu-label">
                <i class="fa bs-s-onlyme" aria-hidden="true"></i>
                Private
            </label>
        </div>
        <div class="radio">
            {!! Form::radio('access_level', App\Note::ACCESS_PUBLIC_ALL, false,['id' => 'public-all','class' => 'cust-radio']) !!}
            <label for="public-all" class="label-radio cu-label">
                <i class="fa bs-s-public" aria-hidden="true"></i>
                Public - share with everyone
            </label>
        </div>
        <div class="radio">
            {!! Form::radio('access_level', ViewHelper::checkEntryAccess($model), ViewHelper::checkEntryAccess($model),['id' => 'public-groups','class' => 'cust-radio']) !!}
            <label for="public-groups" class="label-radio cu-label">
                <i class="fa bs-community" aria-hidden="true"></i>
                Public - share with Groups I am member of
            </label>
            <div class="share-with-groups">
                <div class="radio j-all-groups {!! ViewHelper::checkEntryAccess($model)?'':'disabled' !!}">
                    {!! Form::radio('share_for_groups', App\Note::ACCESS_PUBLIC_GROUPS, $model->access_level == App\Note::ACCESS_PUBLIC_GROUPS,['id' => 'public-groups-all','class' => 'cust-radio',ViewHelper::checkEntryAccess($model)?'':'disabled])']) !!}
                    <label for="public-groups-all" class="label-radio cu-label">
                        All my groups
                    </label>
                </div>
                <div class="radio j-specific-groups {!! ViewHelper::checkEntryAccess($model)?'':'disabled' !!}">
                    {!! Form::radio('share_for_groups', App\Note::ACCESS_SPECIFIC_GROUPS, $model->access_level == App\Note::ACCESS_SPECIFIC_GROUPS, ['id' => 'public-groups-specific','class' => 'cust-radio',ViewHelper::checkEntryAccess($model)?'':'disabled])']) !!}
                    <label for="public-groups-specific" class="label-radio cu-label">
                        Selected groups
                    </label>
                </div>
                {!! Form::select('groups[]', $content['groups'], $model->groupsShares->pluck('id')->toArray(),['placeholder' => 'Select groups...','multiple' => true,'class' => 'clear-fix j-groups j-select2', 'style' => '',$model->access_level == App\Note::ACCESS_SPECIFIC_GROUPS?'':'disabled']) !!}
            </div>
        </div>
        @if ($errors->has('access_level'))
            <span class="help-block">
            {{ $errors->first('access_level') }}
        </span>
        @endif
    </div>
</div>
<div class="add-note {!! Request::get('extraFields')?'':'hidden' !!} {{ $errors->has('note_text') ? ' has-error' : '' }}">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                    <i class="bs-note"></i>
                    <a role="button" class="j-collapse collapsed" data-toggle="collapse" data-parent="#none"
                       href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        {!! ($model->exists && $model->note)?'Related Note':'Add a Note' !!}
                        <i class="bs-arrowleft j-arrow-up-down arrow-down"></i>
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
<div class="add-journal {!! Request::get('extraFields')?'':'hidden' !!} {{ $errors->has('journal_text') ? ' has-error' : '' }}">
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title">
                    <i class="bs-journal"></i>
                    <a role="button" class="j-collapse collapsed" data-toggle="collapse" data-parent="#none"
                       href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        {!! ($model->exists && $model->journal)?'Related Journal Entry':'Add Journal Entry' !!}
                        <i class="bs-arrowleft j-arrow-up-down arrow-down"></i>
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
<!-- /.box-body -->

<div class="box-footer">
    {!! Form::button('Save', ['type'=>'submit','class'=>'btn2-kit cu-btn-pad1']) !!}
    {!! Html::link((($url = Session::get('backUrl'))?$url:'/journal/list/'),'Cancel', ['class'=>'btn4-kit cu-btn-pad1']) !!}
</div>
{!! Form::close() !!}