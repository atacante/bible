 {{-- ---------------- Settings popup ---------------- --}}
            @if(Request::segment(1) == 'reader')
                <div class="popup-new j-popup-settings" style="display: none">
                    <div class="popup-arrow"></div>

                    <div>
                        <h4 class="popup-title">
                            Settings
                        </h4>
{{--                        {!!  Form::open(['method' => 'get','url' => '/reader/'.Request::segment(2)]) !!}--}}
                        @if(isset($content['showRelated']))
                            <div class="mt15" style="position: relative; margin-top: 20px !important;">
                                {{--<input type="checkbox">--}}
                                @if(ViewHelper::checkNotifTooltip('got_related_records_tooltip'))
                                    <div class="cu-starsolid-settings-popup">i</div>
                                @endif
                                {!! Form::hidden('related', 0) !!}
                                {!! Form::checkbox('related', null, ($content['showRelated'])?1:0, ['id'=>'check-related','class'=>'cust-radio']) !!}
                                <label class="label-checkbox" for="check-related">Show Related Records</label>
                            </div>
                        @endif
                        {{--{!! Form::hidden('version', Request::input('version', Config::get('app.defaultBibleVersion'))) !!}--}}
                        {{--{!! Form::hidden('book', Request::input('book', Config::get('app.defaultBookNumber'))) !!}--}}
                        {{--{!! Form::hidden('chapter', Request::input('chapter', Config::get('app.defaultChapterNumber'))) !!}--}}
                        @if(Request::segment(2) == 'verse')
                            {!! Form::hidden('verse', Request::input('verse', false)) !!}
                        @endif

                        {{--@if($compareVersions = Request::input('compare', false))
                            @foreach($compareVersions as $version)
                                {!! Form::hidden('compare[]', $version) !!}
                            @endforeach
                        @endif--}}
                        <div title="{{ ViewHelper::getContent(App\CmsPage::CONTENT_TOOLTIP,'diff_explain')->text  }}" class="mt15 {!! Request::input('compare', false) || Request::segment(2) == 'verse'?'':'checkbox-disabled' !!}" style="position: relative; {{ !isset($content['showRelated'])?'margin-top: 20px !important;':'' }}">
                            @if(ViewHelper::checkNotifTooltip('got_chapter_diff_tooltip') || ViewHelper::checkNotifTooltip('got_verse_diff_tooltip'))
                                <div class="cu-starsolid-settings-popup">i</div>
                            @endif
                            @if(Request::input('compare', false) || Request::segment(2) == 'verse')
                                {!! Form::hidden('diff','off') !!}
                            @endif
                            {!! Form::checkbox('diff', null, ((Request::input('compare', false) || Request::segment(2) == 'verse') && (!Request::input('diff',false) || Request::input('diff',false) == 'on'))?1:0, ['id'=>'check-diff','class'=>'cust-radio',Request::input('compare', false) || Request::segment(2) == 'verse'?'':'disabled']) !!}
                            <label class="label-checkbox" for="check-diff">Show difference</label>
                        </div>
                        @if(isset($content['readerMode']))
                            <div class="mt16">
                                <div title="{{ ViewHelper::getContent(App\CmsPage::CONTENT_TOOLTIP,'beginner_mode')->text  }}" class="radio-inline">
                                    {!! Form::radio('readerMode', 'beginner', ($content['readerMode'] == 'beginner'),['id'=>'check-beginner','class'=>'cust-radio']) !!}
                                    <label class="label-radio" for="check-beginner">{!! Config::get('app.readerModes.beginner') !!}</label>
                                </div>
                                <div title="{{ ViewHelper::getContent(App\CmsPage::CONTENT_TOOLTIP,'intermediate_mode')->text  }}" class="radio-inline">
                                    {!! Form::radio('readerMode', 'intermediate', ($content['readerMode'] == 'intermediate'),['id'=>'check-intermediate','class'=>'cust-radio']) !!}
                                    <label class="label-radio" for="check-intermediate">{!! Config::get('app.readerModes.intermediate') !!}</label>
                                </div>
                            </div>
                        @endif
                        <div class="mt16">
                            {!! Form::button('OK', ['type'=>'submit','class'=>'btn1 cu-btn1']) !!}
                        </div>
{{--                        {!! Form::close() !!}--}}
                    </div>
                </div>
            @endif



