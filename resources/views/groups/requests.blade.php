@if(count($content['requests']))
    @foreach($content['requests'] as $member)
        <div class="my-item friend-item">
            <div class="my-inner-friends-item">
                @if($member->avatar)
                    <div class="friend-image" style="background: url('{!! $member->avatar!=''?Config::get('app.userAvatars').$member->id.'/thumbs/'.$member->avatar:'' !!}') center no-repeat;"></div>
                @else
                    <div class="friend-image"></div>
                @endif

                <div class="c-friend">
                    <div class="c-friend-text">
                        <span class="friend-name">{!! $member->name !!}{!! Auth::user() && Auth::user()->id == $member->id?' (you)':'' !!}</span>
                        <div>
                            {!! str_limit(strip_tags($member->about_me,'<p></p>'), $limit = 15, $end = '... ') !!}
                        </div>
                    </div>
                </div>

                <div class="btns-group">
                    @if(Auth::user() && Auth::user()->id != $member->id)
                        <a title="Approve" href="{!! url('/groups/approve-group-join-request',[$model->id,$member->id],false) !!}" class="btn1-icon j-approve-group-join-request"><i class="bs-checkmark cu-btn-ic"></i></a>
                        <a title="Reject" href="{!! url('/groups/reject-group-join-request',[$model->id,$member->id],false) !!}" class="btn2-icon j-reject-group-join-request"
                           data-toggle="modal"
                           data-target="#cancel-request-sm"
                           data-itemid="{!! $member->id !!}"
                           data-header="Reject Request"
                           data-callclass="j-reject-group-join-request"
                           data-confirm="Are you sure you want to reject this request?"><i class="bs-close cu-btn-ic"></i></a>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@endif
<div class="clearfix"></div>
@if($content['nextPage'])
    <div class="col-md-12 text-center load-more-block">
        {!! Html::link('/groups/requests/'.$model->id.'?'.http_build_query(
                array_merge(Request::all(),['page' => $content['nextPage']])
            ),'See More', ['class'=>'btn btn-default load-more','style' => 'width:100%;']) !!}
    </div>
@endif