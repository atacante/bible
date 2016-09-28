<div class="row friend-item">
    <div class="col-md-3">
        <div>
            @if($model->avatar)
                <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$model->id.'/thumbs/'.$model->avatar !!}" />
            @else
                <div class="no-avatar img-thumbnail">
                    <div class="no-avatar-text text-center"><i class="fa fa-user fa-4x"></i></div>
                </div>
            @endif
        </div>
        <div>
            <a href="{!! url('/user/remove-friend/'.$model->id,[],false) !!}" class="btn btn-danger j-remove-friend {!! in_array($model->id,$myFriends)?'':'hidden' !!}"
               data-toggle="modal"
               data-target="#cancel-request-sm"
               data-itemid="{!! $model->id !!}"
               data-header="Remove user from friends"
               data-callclass="j-remove-friend"
               data-confirm="Are you sure you want to remove this user from friends?">Unfriend</a>
            <a href="{!! url('/user/cancel-friend-request/'.$model->id,[],false) !!}" class="btn btn-danger j-cancel-friend-request {!! in_array($model->id,$myRequests) && !in_array($model->id,$myFriends)?'':'hidden' !!}"
               data-toggle="modal"
               data-target="#cancel-request-sm"
               data-itemid="{!! $model->id !!}"
               data-header="Cancel Request"
               data-callclass="j-cancel-friend-request"
               data-confirm="Are you sure you want to cancel this request?">Cancel Request</a>
            @if(in_array($model->id,$requests))
                @if(in_array($model->id,$ignoredRequests))
                    <a href="#" class="btn btn-danger disabled">Ignored</a>
                @else
                    <a href="{!! url('/user/approve-friend-request/'.$model->id,[],false) !!}" class="btn btn-success j-approve-friend-request {!! !in_array($model->id,$myFriends) && in_array($model->id,$requests)?'':'hidden' !!}">Confirm Request</a>
                @endif
            @else
                <a href="{!! url('/user/request-friend/'.$model->id,[],false) !!}" class="btn btn-{!! in_array($model->id,$requests)?'success':'primary' !!} j-follow-friend {!! in_array($model->id,$myFriends) || in_array($model->id,$myRequests)?'hidden':'' !!}">Add Friend</a>
            @endif
        </div>
    </div>
    <div class="col-md-9">
        <div><strong>{!! $model->name !!}</strong></div>
        <div style="line-height: 16px; font-size: 12px;">
            <div><strong>About me</strong></div>
            <div style="color:#90949c;">{!! $model->about_me !!}</div>
        </div>
        @if($model->country_id)
            <div style="line-height: 16px; font-size: 12px;">
                <strong>Country</strong>
                <span style="color:#90949c;">{!! $model->country->nicename !!}</span>
            </div>
        @endif
        @if($model->state)
            <div style="line-height: 16px; font-size: 12px;">
                <strong>State</strong>
                <span style="color:#90949c;">{!! $model->state !!}</span>
            </div>
        @endif
        @if($model->city)
            <div style="line-height: 16px; font-size: 12px;">
                <strong>City</strong>
                <span style="color:#90949c;">{!! $model->city !!}</span>
            </div>
        @endif
        @if($model->church_name)
            <div style="line-height: 16px; font-size: 12px;">
                <strong>Church</strong>
                <span style="color:#90949c;">{!! $model->church_name !!}</span>
            </div>
        @endif
        @if(count($relatedGroups))
            <div style="line-height: 16px; font-size: 12px; margin-top: 5px;">
                <div><strong>Related groups:</strong></div>
                <div>
                @foreach($relatedGroups as $key => $group)
                    <a href="{!! url('groups/view',[$group->id]) !!}" target="_blank">{!! $group->group_name !!}</a>{!! $key+1 < count($relatedGroups)?',':'' !!}
                @endforeach
                </div>
            </div>
        @endif
    </div>
</div>