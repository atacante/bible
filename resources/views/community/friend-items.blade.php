@if(count($content['people']))
    @foreach($content['people'] as $people)
        <div data-itemid="{!! $people->id !!}" class="my-item">
            <div class="my-inner-friends-item">
                @if($people->avatar)
                    <div class="friend-image" style="background: url('{!! $people->avatar!=''?Config::get('app.userAvatars').$people->id.'/thumbs/'.$people->avatar:'' !!}') center no-repeat;"></div>
                @else
                    <div class="friend-image"></div>
                @endif
                <div class="c-friend">
                    <div class="c-friend-text">
                        <a class="j-friend-item friend-name" data-userid="{!! $people->id !!}" href="{!! url('/user/view',[$people->id]) !!}">{!! $people->name !!}</a>
                        <br>
                        {!! str_limit(strip_tags($people->about_me,'<p></p>'), $limit = 15, $end = '... ') !!}
                    </div>
                </div>
                <div class="btns-group">
                    @if(Request::get('type') == 'inbox-requests')
                        <a href="{!! url('/user/approve-friend-request/'.$people->id,[],false) !!}" class="btn1 cu7-btn1 j-approve-friend-request">Approve</a>
                        <a href="{!! url('/community/find-friends?type=my',[],false) !!}" class="btn2 cu2-btn1 j-friends-btn hidden">Friends</a>
                        <a href="{!! url('/user/reject-friend-request/'.$people->id,[],false) !!}" style="left: auto; right: 25px;" class="btn2 cu8-btn1 j-reject-friend-request"
                           data-toggle="modal"
                           data-target="#cancel-request-sm"
                           data-itemid="{!! $people->id !!}"
                           data-header="Reject Request"
                           data-callclass="j-reject-friend-request"
                           data-confirm="Are you sure you want to reject this request?">Reject</a>
                        <a href="{!! url('/user/ignore-friend-request/'.$people->id,[],false) !!}" class="btn2 cu8-btn1 j-ignore-friend-request"
                           data-toggle="modal"
                           data-target="#cancel-request-sm"
                           data-itemid="{!! $people->id !!}"
                           data-header="Ignore Request"
                           data-callclass="j-ignore-friend-request"
                           data-confirm="Are you sure you want to ignore this request?">Ignore</a>
                    @else
                        <a href="{!! url('/user/remove-friend/'.$people->id,[],false) !!}" class="btn2 cu2-btn1 j-remove-friend {!! in_array($people->id,$myFriends)?'':'hidden' !!}"
                           data-toggle="modal"
                           data-target="#cancel-request-sm"
                           data-itemid="{!! $people->id !!}"
                           data-header="Remove user from friends"
                           data-callclass="j-remove-friend"
                           data-confirm="Are you sure you want to remove this user from friends?">Unfriend</a>

                        <a href="{!! url('/user/cancel-friend-request/'.$people->id,[]) !!}" class="btn2 cu4-btn1 j-cancel-friend-request {!! in_array($people->id,$myRequests) && !in_array($people->id,$myFriends)?'':'hidden' !!}"
                           data-toggle="modal"
                           data-target="#cancel-request-sm"
                           data-itemid="{!! $people->id !!}"
                           data-header="Cancel Request"
                           data-callclass="j-cancel-friend-request"
                           data-confirm="Are you sure you want to cancel this request?">Cancel Request</a>
                            @if(in_array($people->id,$requests))
                                @if(in_array($people->id,$ignoredRequests))
                                    <a href="#" class="btn2 cu2-btn1 disabled" style="">Ignored</a>
                                @else
                                    <a href="{!! url('/user/approve-friend-request/'.$people->id,[],false) !!}" class="btn2 cu4-btn1 j-approve-friend-request {!! !in_array($people->id,$myFriends) && in_array($people->id,$requests)?'':'hidden' !!}">Confirm Request</a>
                                @endif
                            @else
                                <a href="{!! url('/user/request-friend/'.$people->id,[],false) !!}" class="btn1 cu2-btn1 j-follow-friend {!! in_array($people->id,$myFriends) || in_array($people->id,$myRequests)?'hidden':'' !!}">Add Friend</a>
                            @endif
                    @endif
                    @if(in_array($people->id,$myFriends))
                    @else
                    @endif
                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="text-center">No results found</p>
@endif
@if($content['nextPage'])
    <div class="u-footer load-more-block text-center">
        {!! Html::link('/community/find-friends?'.http_build_query(
            array_merge(Request::input(),['page' => $content['nextPage']])
        ),'Load More', ['class'=>'btn1 load-more','style' => 'width:100%;']) !!}
    </div>
@endif