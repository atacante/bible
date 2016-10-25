<div class="entry-form">
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
        <div class="form-group">
            {!! Form::label('note_text', 'Note Text:',['class' => 'text']) !!}
            {!! $model->note_text !!}
        </div>
        @if(Auth::user() && $model->user_id == Auth::user()->id  && count($model->tags))
            <div class="form-group c-journey-tags">
                {!! Form::label('tags', 'Tags:',['class' => 'text']) !!}

                @foreach($model->tags as $tag)
                    {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[]), '#'.$tag->tag_name, ['class' => 'link-tag'], true)}}
                @endforeach
            </div>
            <div class="clearfix"></div>
        @endif
    </div>
    <div class="box-footer">
        {!! Form::button('Close', ['type'=>'button','class'=>'btn4-kit cu-btn-pad1', 'data-dismiss'=>'modal']) !!}
    </div>
</div>