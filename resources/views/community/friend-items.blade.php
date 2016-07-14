@if(count($content['people']))
    @foreach($content['people'] as $people)
        <div class="col-md-6 clearfix friend-item">
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
                    <a href="{!! url('/user/reject-friend-request/'.$people->id,[],false) !!}" class="btn btn-danger j-reject-friend-request">Reject</a>
                    <a href="{!! url('/user/ignore-friend-request/'.$people->id,[],false) !!}" class="btn btn-danger j-ignore-friend-request">Ignore</a>
                @else
                    <a href="{!! url('/user/remove-friend/'.$people->id,[],false) !!}" class="btn btn-danger j-remove-friend {!! in_array($people->id,$myFriends)?'':'hidden' !!}">Unfriend</a>
                    <a href="{!! url('/user/remove-friend-request/'.$people->id,[],false) !!}" class="btn btn-danger j-remove-friend-request {!! in_array($people->id,$myRequests) && !in_array($people->id,$myFriends)?'':'hidden' !!}">Cancel Request</a>
                    <a href="{!! url('/user/request-friend/'.$people->id,[],false) !!}" class="btn btn-{!! in_array($people->id,$requests)?'success':'primary' !!} j-follow-friend {!! in_array($people->id,$myFriends) || in_array($people->id,$myRequests)?'hidden':'' !!}">
                        @if(in_array($people->id,$requests))
                            Confirm Request
                        @else
                            Add Friend
                        @endif
                    </a>
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