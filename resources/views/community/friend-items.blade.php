@if(count($content['people']))
    @foreach($content['people'] as $people)
        <div data-itemid="{!! $people->id !!}" class="col-md-6 clearfix friend-item">
            <div class="pull-left" style="margin-right: 10px;">
                @if($people->avatar)
                    <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$people->id.'/thumbs/'.$people->avatar !!}" />
                @else
                    <div class="no-avatar img-thumbnail">
                        <div class="no-avatar-text text-center"><i class="fa fa-user fa-4x"></i></div>
                    </div>
                @endif
            </div>
            <div class="pull-left" style="margin-right: 10px; width: 200px;">
                <div><strong>{!! $people->name !!}</strong></div>
                <div style="line-height: 16px; font-size: 12px;">
                    <span style="color:#90949c;">{!! str_limit(strip_tags($people->about_me,'<p></p>'), $limit = 100, $end = '... ') !!}</span>
                </div>
            </div>
            <div>
                {!! str_limit(strip_tags($people->people_description,'<p></p>'), $limit = 500, $end = '... '.Html::link(url('/peoples/view/'.$people->id,[],false), 'View Details', ['class' => 'btn btn-success','style' => 'padding: 0 5px;'], true)) !!}
            </div>
            <div class="pull-right" style="width: 125px; text-align: right;">
                @if(Request::get('type') == 'inbox-requests')
                    <a href="{!! url('/user/approve-friend-request/'.$people->id,[],false) !!}" class="btn btn-success j-approve-friend-request">Approve</a>
                    <a href="{!! url('/community/find-friends?type=my',[],false) !!}" class="btn btn-success j-friends-btn hidden">Friends</a>
                    <a href="{!! url('/user/reject-friend-request/'.$people->id,[],false) !!}" class="btn btn-danger j-reject-friend-request"
                       data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-itemid="{!! $people->id !!}"
                       data-header="Reject Request"
                       data-callclass="j-reject-friend-request"
                       data-confirm="Are you sure you want to reject this request?">Reject</a>
                    <a href="{!! url('/user/ignore-friend-request/'.$people->id,[],false) !!}" class="btn btn-danger j-ignore-friend-request"
                       data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-itemid="{!! $people->id !!}"
                       data-header="Ignore Request"
                       data-callclass="j-ignore-friend-request"
                       data-confirm="Are you sure you want to ignore this request?">Ignore</a>
                @else
                    <a href="{!! url('/user/remove-friend/'.$people->id,[],false) !!}" class="btn btn-danger j-remove-friend {!! in_array($people->id,$myFriends)?'':'hidden' !!}"
                       data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-itemid="{!! $people->id !!}"
                       data-header="Remove user from friends"
                       data-callclass="j-remove-friend"
                       data-confirm="Are you sure you want to remove this user from friends?">Unfriend</a>
                    <a href="{!! url('/user/cancel-friend-request/'.$people->id,[],false) !!}" class="btn btn-danger j-cancel-friend-request {!! in_array($people->id,$myRequests) && !in_array($people->id,$myFriends)?'':'hidden' !!}"
                       data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-itemid="{!! $people->id !!}"
                       data-header="Cancel Request"
                       data-callclass="j-cancel-friend-request"
                       data-confirm="Are you sure you want to cancel this request?">Cancel Request</a>
                        @if(in_array($people->id,$requests))
                            @if(in_array($people->id,$ignoredRequests))
                                <a href="#" class="btn btn-danger disabled">Ignored</a>
                            @else
                                <a href="{!! url('/user/approve-friend-request/'.$people->id,[],false) !!}" class="btn btn-success j-approve-friend-request {!! !in_array($people->id,$myFriends) && in_array($people->id,$requests)?'':'hidden' !!}">Confirm Request</a>
                            @endif
                        @else
                            <a href="{!! url('/user/request-friend/'.$people->id,[],false) !!}" class="btn btn-{!! in_array($people->id,$requests)?'success':'primary' !!} j-follow-friend {!! in_array($people->id,$myFriends) || in_array($people->id,$myRequests)?'hidden':'' !!}">Add Friend</a>
                        @endif
                @endif
                @if(in_array($people->id,$myFriends))
                @else
                @endif
            </div>
        </div>
    @endforeach
@else
    <p class="text-center">No any results found</p>
@endif
@if($content['nextPage'])
    <div class="u-footer load-more-block text-center">
        {!! Html::link('/community/find-friends?'.http_build_query(
            array_merge(Request::input(),['page' => $content['nextPage']])
        ),'Load More', ['class'=>'btn btn-default load-more','style' => 'width:100%;']) !!}
    </div>
@endif