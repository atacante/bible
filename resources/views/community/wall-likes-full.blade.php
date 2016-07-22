@if(count($content['likes']))
    @foreach($content['likes'] as $like)
        <div class="clearfix col-md-6 friend-item" style="margin-bottom: 15px;">
            <div class="pull-left" style="margin-right: 10px;">
                @if($like->avatar)
                    <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$like->id.'/thumbs/'.$like->avatar !!}"/>
                @else
                    <div class="no-avatar img-thumbnail">
                        <div class="no-avatar-text text-center"><i class="fa fa-user fa-4x"></i></div>
                    </div>
                @endif
            </div>
            <div class="pull-left" style="margin-right: 10px; width: 130px;">
                <div><strong>{!! $like->name !!}{!! Auth::user() && Auth::user()->id == $like->id?' (you)':'' !!}</strong></div>
                <div style="line-height: 16px; font-size: 12px;">
                    <span style="color:#90949c;">{!! str_limit(strip_tags($like->about_me,'<p></p>'), $limit = 70, $end = '... ') !!}</span>
                </div>
            </div>
            <div>
                {!! str_limit(strip_tags($like->people_description,'<p></p>'), $limit = 500, $end = '... '.Html::link(url('/peoples/view/'.$like->id,[],false), 'View Details', ['class' => 'btn btn-success','style' => 'padding: 0 5px;'], true)) !!}
            </div>
            <div class="pull-left">
                @if(Auth::user() && Auth::user()->id != $like->id)
                    <a href="{!! url('/user/remove-friend/'.$like->id,[],false) !!}" class="btn btn-danger j-remove-friend {!! in_array($like->id,$myFriends)?'':'hidden' !!}"
                       data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-itemid="{!! $like->id !!}"
                       data-header="Remove user from friends"
                       data-callclass="j-remove-friend"
                       data-confirm="Are you sure you want to remove this user from friends?">Unfriend</a>
                    <a href="{!! url('/user/cancel-friend-request/'.$like->id,[],false) !!}" class="btn btn-danger j-cancel-friend-request {!! in_array($like->id,$myRequests) && !in_array($like->id,$myFriends)?'':'hidden' !!}"
                       data-toggle="modal"
                       data-target="#cancel-request-sm"
                       data-itemid="{!! $like->id !!}"
                       data-header="Cancel Request"
                       data-callclass="j-cancel-friend-request"
                       data-confirm="Are you sure you want to cancel this request?">Cancel Request</a>
                    @if(in_array($like->id,$requests))
                        @if(in_array($like->id,$ignoredRequests))
                            <a href="#" class="btn btn-danger disabled">Ignored</a>
                        @else
                            <a href="{!! url('/user/approve-friend-request/'.$like->id,[],false) !!}" class="btn btn-success j-approve-friend-request {!! !in_array($like->id,$myFriends) && in_array($like->id,$requests)?'':'hidden' !!}">Confirm Request</a>
                        @endif
                    @else
                        <a href="{!! url('/user/request-friend/'.$like->id,[],false) !!}" class="btn btn-{!! in_array($like->id,$requests)?'success':'primary' !!} j-follow-friend {!! in_array($like->id,$myFriends) || in_array($like->id,$myRequests)?'hidden':'' !!}">Add Friend</a>
                    @endif
                @endif
            </div>
        </div>
    @endforeach
@endif
<div class="clearfix"></div>
@if($content['nextPage'])
    <div class="text-center load-more-block">
        {!! Html::link('/'.ViewHelper::getEntryControllerName($item->type).'/likes/'.$item->id.'/full?'.http_build_query(
                array_merge(Request::all(),['page' => $content['nextPage']])
            ),'See More', ['class'=>'btn btn-default load-more','style' => 'width:100%;']) !!}
    </div>
@endif