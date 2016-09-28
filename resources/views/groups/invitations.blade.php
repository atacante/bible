@if(count($content['invitations']))
    @foreach($content['invitations'] as $member)
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

                <div class="btns-group1">
                    @if(Auth::user() && Auth::user()->id != $member->id)
                    <a href="{!! url('/groups/cancel-request/'.$model->id.'/'.$member->id,[],false) !!}" class="btn2 cu9-btn1 j-cancel-request" data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-header="Cancel Request"
                       data-callclass="j-cancel-request"
                       data-confirm="Are you sure you want to cancel this request?">Cancel <br> Request</a>
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
            ),'See More', ['class'=>'btn1 load-more load-more','style' => 'width:100%;']) !!}
    </div>
@endif