<div id="prayers" class="verse-prayers my-prayers-list j-my-prayers-list mt3">
    <h4 class="h4-study mb3">
        <i class="bs-pray cu-pray"></i>
        My <span>Prayers</span> for this Study Verse
        {!! Html::link('/prayers/create','Create Prayer', ['class'=>'btn1-kit cu-study-btn j-create-prayer']) !!}
    </h4>

    <ul class="study-list">

        @if($content['prayers']->count())
            @foreach($content['prayers'] as $prayer)
                <li>

                    <div class="prayer-text j-prayer-text" data-prayerid="{!! $prayer->id !!}">
                        {!! str_limit(strip_tags($prayer->prayer_text,'<p></p>'), $limit = 300, $end = '...') !!}
                    </div>
                    @if($prayer->answered)
                        <div style="color: #00a65a;">
                            <i class="fa fa-check-circle" aria-hidden="true" style="color: #00a65a;"></i> Answered
                        </div>
                    @endif
                    <div class="c-study-bottom">
                        <div class="c-journey-tags">
                            @if(count($prayer->tags))
                                @foreach($prayer->tags as $tag)
                                    {{ Html::link(url('user/my-journey?'.http_build_query(['tags[]' => $tag->id]),[]), '#'.$tag->tag_name, ['class' => 'link-tag'], true)}}
                                @endforeach
                            @endif
                        </div>
                        <ul class="icons-list pull-right">
                            <li>
                                <a title="Print Prayer" href="#" data-prayerid="{!! $prayer->id !!}" class="j-print-prayer">
                                    <i class="bs-print"></i>
                                </a>
                            </li>
                            <li>
                                <a title="Edit Prayer" class="j-create-prayer" href="{!! url('/prayers/update/'.$prayer->id) !!}">
                                    <i class="bs-edit"></i>
                                </a>
                            </li>
                            <li>
                                <a title="Delete Prayer" href="{!! url('/prayers/delete',$prayer->id) !!}" data-toggle="modal" data-target="#confirm-delete" data-header="Delete Confirmation" data-confirm="Are you sure you want to delete this item?">
                                    <i class="bs-remove"></i>
                                </a>
                            </li>

                        </ul>
                        <div class="c-journey-date cu-date1">
                            {!! $prayer->humanLastUpdate($prayer::DFORMAT) !!}
                            {{--{!! $note->created_at->format($note::DFORMAT) !!}--}}
                            - {!! ViewHelper::getAccessLevelIcon($prayer->access_level) !!}
                        </div>

                    </div>

                </li>
            @endforeach
        @else
            <li class="text-center">
                You haven’t got any Prayers yet.
            </li>
        @endif

    </ul>




   {{-- <table class="table table-bordered table-hover">
        <tbody>
        @if($content['prayers']->count())
            @foreach($content['prayers'] as $prayer)
                <tr>
                    --}}{{--<td width="20"><input data-prayerid="{!! $prayer->id !!}" type="checkbox" class="check"></td>--}}{{--
                    <td>
                        <div class="prayer-text j-prayer-text" data-prayerid="{!! $prayer->id !!}">
                            {!! str_limit(strip_tags($prayer->prayer_text,'<p></p>'), $limit = 300, $end = '...') !!}
                        </div>
                        @if($prayer->answered)
                            <div style="color: #00a65a;">
                                <i class="fa fa-check-circle" aria-hidden="true" style="color: #00a65a;"></i> Answered
                            </div>
                        @endif
                    </td>
                    <td>
                        @if(count($prayer->tags))
                            @foreach($prayer->tags as $tag)
                                {{ Html::link(url('prayers/list?'.http_build_query(['tags[]' => $tag->id]),[]), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                <br/>
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">{!! ViewHelper::getAccessLevelIcon($prayer->access_level) !!}</td>
                    <td>{!! $prayer->created_at->format($prayer::DFORMAT) !!}</td>--}}{{--H:i--}}{{--
                    <td class="text-center">
                        <a title="Print Prayer" href="#" data-prayerid="{!! $prayer->id !!}"
                           class="j-print-prayer"><i
                                    class="fa fa-print fa-2x"
                                    style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                        <a title="Edit Prayer" class="j-create-prayer"
                           href="{!! url('/prayers/update/'.$prayer->id) !!}"><i
                                    class="fa fa-edit"
                                    style="color: #367fa9; font-size: 1.4em; margin-right: 5px;"></i></a>
                        <a title="Delete Prayer" href="{!! url('/prayers/delete',$prayer->id) !!}"
                           data-toggle="modal"
                           data-target="#confirm-delete" data-header="Delete Confirmation"
                           data-confirm="Are you sure you want to delete this item?"><i class="fa fa-trash"
                                                                                        style="color: #367fa9; font-size: 1.4em;"></i></a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">
                    You haven’t got any Prayers yet.
                </td>
            </tr>
        @endif
        </tbody>
    </table>--}}
</div>
