<div class="j-short-verses-filters {!! Request::input('adv')?'hidden':'' !!}">
    {!! Form::open(['method' => 'get','url' => '/user/my-journey']) !!}
    <div class="form-group relative">
        <span class="absolute adv-search j-show-adv-search">
            <i class="bs-settings"></i>&nbsp;&nbsp;Advanced search&nbsp;&nbsp;
        </span>
        <span class="absolute search-back"><i class="bs-search"></i></span>
        <div class="hidden">{!! Form::label('search', 'Search') !!}</div>
        {!! Form::text('search',Request::input('search'),['placeholder' => 'Type a keyword to search records...','class' => 'form-control','style' => '']) !!}
    </div>
    {!! Form::hidden('adv',0) !!}
    {!! Form::close() !!}
</div>
<div class="j-admin-verses-filters {!! Request::input('adv')?'':'hidden' !!} ">
    <h3>
        <span class="adv-search">
           <i class="bs-settings"></i>&nbsp;&nbsp;Advanced search&nbsp;&nbsp;
        </span>
    </h3>
    {!! Form::open(['method' => 'get','url' => '/user/my-journey']) !!}
    <div class="row">
        <div class="col-xs-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('search', 'Keyword') !!}</div>
                    <div class="col-xs-9">{!! Form::text('search',Request::input('search'),['placeholder' => 'Type a keyword to search records...','class' => 'form-control','style' => '']) !!}</div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('types', 'Entry Type') !!}</div>
                    {{--<input id="check-full" class="cust-radio" type="checkbox" name="check-full" value="fulltime">--}}
                    <div class="col-xs-3">
                        {!! Form::checkbox('types[]', 'note', in_array('note',Request::input('types', [])) , ['id'=>'type-note' ,'class'=>'cust-radio']) !!}
                        <label class="label-checkbox" for="type-note">Note</label>
                    </div>
                    <div class="col-xs-3">
                        {!! Form::checkbox('types[]', 'journal', in_array('journal',Request::input('types', [])) , ['id'=>'type-journal' ,'class'=>'cust-radio']) !!}
                        <label class="label-checkbox" for="type-journal">Journal</label>
                    </div>
                    <div class="col-xs-3">
                        {!! Form::checkbox('types[]', 'prayer', in_array('prayer',Request::input('types', [])) , ['id'=>'type-prayer' ,'class'=>'cust-radio']) !!}
                        <label class="label-checkbox" for="type-prayer">Prayer</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('version', 'Bible version') !!}</div>
                    <div class="col-xs-9">{!! Form::select('version', array_merge(['all' => 'All Versions'],$filters['versions']), Request::input('version','all'),['class' => 'form-control', 'style' => '']) !!}</div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('chapter', 'Chapter') !!}</div>
                    <div class="col-xs-9">{!! Form::select('chapter',array_merge([0 => 'All Chapters'],(Request::input('book') == 0?[]:$filters['chapters'])), Request::input('chapter'),['class' => 'form-control',(Request::input('book') == 0?'disabled':''), 'style' => '']) !!}</div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('tags', 'Tags') !!}</div>
                    <div class="col-xs-9">{!! Form::select('tags[]', $filters['tags'], Request::input('tags'),['multiple' => true,'class' => 'clear-fix j-tags', 'style' => '']) !!}</div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('date_from', 'Date') !!}</div>
                    <div class="col-xs-9">
                        <div class="input-group input-daterange">
                           {!! Form::text('date_from',Request::input('date_from'),['placeholder' => 'mm/dd/yyyy','class' => 'form-control datepicker','style' => '']) !!}
                            <span class="input-group-addon">to</span>
                            {!! Form::text('date_to',Request::input('date_to'),['placeholder' => 'mm/dd/yyyy','class' => 'form-control datepicker','style' => '']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('book', 'Book') !!}</div>
                    <div class="col-xs-9">{!! Form::select('book', array_merge([0 => 'All Books'],$filters['books']), Request::input('book'),['class' => 'form-control', 'style' => '']) !!}</div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">{!! Form::label('verse', 'Verse') !!}</div>
                    <div class="col-xs-9">{!! Form::select('verse',array_merge([0 => 'All Verses'],(Request::input('chapter') == 0?[]:$filters['verses'])), Request::input('verse'),['class' => 'form-control', (Request::input('chapter') == 0?'disabled':''), 'style' => '']) !!}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::button('Go',['type' => 'submit','class' => 'btn btn-primary pull-left']) !!}
        {!! Html::link('/user/my-journey','Reset', ['class'=>'btn btn-danger pull-left reset-filter']) !!}
    </div>
    {!! Form::hidden('adv',1) !!}
    {!! Form::close() !!}
</div>