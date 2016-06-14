<div id="prayers" class="verse-prayers my-prayers-list j-my-prayers-list">
    <h4>My Prayers for this Study Verse</h4>
    {!! Html::link('/prayers/create','Create Prayer', ['class'=>'btn btn-success j-create-prayer','style' => 'margin-bottom:10px;']) !!}
    {{--<a title="Print selected prayers" href="#" class="pull-right j-print-all-prayers"><i--}}
    {{--class="fa fa-print fa-2x" style=""></i></a>--}}
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            {{--<th width="20"><input type="checkbox" id="checkAll"/></th>--}}
            <th>Prayer Text</th>
            <th width="150">Tags</th>
            <th width="100" class="text-center">Accessibility</th>
            <th width="100" class="text-center">Created</th>
            <th width="90" class="text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if($content['prayers']->count())
            @foreach($content['prayers'] as $prayer)
                <tr>
                    {{--<td width="20"><input data-prayerid="{!! $prayer->id !!}" type="checkbox" class="check"></td>--}}
                    <td>
                        <div class="prayer-text j-prayer-text"
                             data-prayerid="{!! $prayer->id !!}">{!! str_limit(strip_tags($prayer->prayer_text,'<p></p>'), $limit = 300, $end = '...') !!}</div>
                    </td>
                    <td>
                        @if(count($prayer->tags))
                            @foreach($prayer->tags as $tag)
                                {{ Html::link(url('prayers/list?'.http_build_query(['tags[]' => $tag->id]),[],false), $tag->tag_name, ['class' => 'label label-info'], true)}}
                                <br/>
                            @endforeach
                        @endif
                    </td>
                    <td class="text-center">{!! ViewHelper::getAccessLevelIcon($prayer->access_level) !!}</td>
                    <td>{!! $prayer->created_at->format('m/d/Y') !!}</td>{{--H:i--}}
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
    </table>
</div>
